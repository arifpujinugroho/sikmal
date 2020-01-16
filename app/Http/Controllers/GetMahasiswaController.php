<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Providers\DOCXTemplate;
use DB;
use File;
use Hash;
use Auth;
use TglIndo;
use Storage;
use Response;
use App\User;
use Redirect;
use App\Model\Surat;
use App\Model\Nota;
use App\Model\UangPKM;
use App\Model\Kategori;
use App\Model\LogToko;
use App\Model\Toko;
use App\Model\DBMHS;
use App\Model\Prodi;
use App\Model\Dosen;
use App\Model\CarDoh;
use App\Model\FilePKM;
use App\Model\TipePKM;
use App\Model\SkimPKM;
use App\Model\TahunPKM;
use App\Model\Fakultas;
use App\Model\Download;
use App\Model\AddConfig;
use App\Model\AnggotaPKM;
use App\Model\UserDikti;
use App\Model\CallCenter;
use App\Model\IdentitasMahasiswa;
use App\Model\Operator;
use Illuminate\Support\Facades\View;

class GetMahasiswaController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Mahasiswa
    public function __construct()
    {
        $this->middleware(['mhs', 'sso']);
        //$this->middleware('mhs');
    }
    ////////////////////////////////////////////////


    public function Home()
    {
        $id_mhs = Auth::user()->identitas_mahasiswa->id;
        $idprodi_mhs = Auth::user()->identitas_mahasiswa->id_prodi;
        $idfakultas_mhs = Prodi::whereid($idprodi_mhs)->first()->id_fakultas;

        $opt = Operator::whereid_fakultas($idfakultas_mhs)->first();

        $findopt = Operator::whereid_fakultas($idfakultas_mhs)->count();

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->count();

        $jmlyear = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('tahun_pkm.aktif', '=', 1)
            ->count();

        $call = $this->CallCenter();
        $tipe = $this->StatusTipePKM();


        return view('auth.mahasiswa.home', compact('findopt', 'jml', 'jmlyear', 'call', 'opt', 'tipe'));
    }

    public function Biodata()
    {
        $call = $this->CallCenter();
        $mhs = Auth::user()->identitas_mahasiswa->id;
        $data = IdentitasMahasiswa::select('*')->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')->where('identitas_mahasiswa.id', '=', $mhs)->first();
        return view('auth.mahasiswa.biodata', compact('call', 'data'));
    }

    public function listPKM(Request $request)
    {

        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->orderBy('file_pkm.id', 'desc')
            ->get();

        $modeupload = TipePKM::select('*')->where('tipe_pkm.status_tambah', '!=', 0)->count();
        $tipepkm = TipePKM::wherestatus_tambah(1)->get();
        $lima = TipePKM::wheretipe('5 Bidang')->first();
        $dua = TipePKM::wheretipe('2 Bidang')->first();
        $gfk = TipePKM::wheretipe('PKM GFK')->first();
        $sug = TipePKM::wheretipe('SUG')->first();

        $call = $this->CallCenter();


        return view('auth.mahasiswa.listpkm', compact('jml', 'lima', 'dua', 'sug', 'gfk', 'list', 'modeupload', 'call'));
    }

    public function listKemajuan(Request $request)
    {

        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->orderBy('file_pkm.id', 'desc')
            ->get();


        $modeupload = TipePKM::select('*')->where('tipe_pkm.status_kemajuan', '!=', 0)->count();

        $call = $this->CallCenter();


        return view('auth.mahasiswa.listkemajuan', compact('jml', 'list', 'modeupload', 'call'));
    }

    public function listKeuangan(Request $request)
    {

        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->orderBy('file_pkm.id', 'desc')
            ->get();

            //return $list;
        $call = $this->CallCenter();


        return view('auth.mahasiswa.listkeuangan', compact('jml', 'list', 'call'));
    }

    public function listNota(Request $request)
    {
        $id = $request->get('id');
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {

                $cekdidanai = $this->CekDidanai($id);
                if($cekdidanai == 1){

                    $pkm = FilePKM::select('*')
                        ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                        ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                        ->join('detail_pkm', 'detail_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
                        ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                        ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
                        ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                        ->where('file_pkm.id', '=', $id)
                        ->first();

                    $jumlah = UangPKM::select(DB::raw('(nominal * volume) as Total'))
                                ->join('nota','nota.id','uang_pkm.id_nota')
                                ->where('nota.id_file_pkm','=',$id)
                                ->get();

                    $call = $this->CallCenter();

                    $encrypt = Crypt::encryptString($id);

                    return view('auth.mahasiswa.listnota', compact('id','encrypt', 'pkm', 'call','jumlah'));
                }else{
                    return redirect()->back()->with('dana', 'tidakdidanai');
                }
            } else {
                return redirect()->back()->with('dana', 'nothing');
            }
        } else {
            return redirect()->back()->with('dana', 'empty');
        }
    }

    

    public function DatalistNota($id)
    {
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {
                $list = Nota::select('nota.tgl_nota','toko.nama_toko','nota.id')
                ->join('toko','toko.id','nota.id_toko')
                ->where('nota.id_file_pkm','=',$id)
                ->get();
                return $list;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public function lihatNota(Request $request)
    {
        $id = $request->get('id');
        
        
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidnota = Nota::whereid($id)->count();
        if ($cekidnota > 0) {
            $nota = Nota::select('*')->join('toko','toko.id','nota.id_toko')->where('nota.id','=', $id)->first();
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $nota->id_file_pkm)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {

                $pkm = FilePKM::select('*')
                    ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                    ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                    ->join('detail_pkm', 'detail_pkm.id_file_pkm', 'file_pkm.id')
                    ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
                    ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
                    ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                    ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
                    ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                    ->where('file_pkm.id', '=', $nota->id_file_pkm)
                    ->first();
                $jml = UangPKM::whereid_nota($id)->count();

                $kate = Kategori::all();

                $call = $this->CallCenter();

                $encrypt = Crypt::encryptString($id);

                return view('auth.mahasiswa.lihatnota', compact('id','kate','encrypt', 'pkm', 'call','nota','jml'));
            } else {
                return redirect()->back()->with('nota', 'nothing');
            }
        } else {
            return redirect()->back()->with('nota', 'empty');
        }
    }

    public function DatalihatNota($id)
    {     
        
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidnota = Nota::whereid($id)->count();
        if ($cekidnota > 0) {
            $nota = Nota::select('*')->join('toko','toko.id','nota.id_toko')->where('nota.id','=', $id)->first();
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $nota->id_file_pkm)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {
                $list = UangPKM::select(
                    'uang_pkm.id',
                    'uang_pkm.volume',
                    'uang_pkm.nama_pembelian',
                    'uang_pkm.id_nota',
                    'uang_pkm.nominal',
                    'uang_pkm.ppn',
                    'uang_pkm.pph21',
                    'uang_pkm.pph22',
                    'uang_pkm.pph23',
                    'uang_pkm.pph26',
                    'uang_pkm.cek_pembelian',
                    'uang_pkm.komentar_pembelian',
                    'uang_pkm.id_kategori',
                    'kategori.nama_kategori'
                )->whereid_nota($id)->join('kategori','kategori.id','uang_pkm.id_kategori')->get();
                return $list;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public function listAkhir(Request $request)
    {

        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->whereNotNull('file_pkm.file_laporan_kemajuan')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->whereNotNull('file_pkm.file_laporan_kemajuan')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->orderBy('file_pkm.id', 'desc')
            ->get();


        $modeupload = TipePKM::select('*')->where('tipe_pkm.status_akhir', '!=', 0)->count();

        $call = $this->CallCenter();


        return view('auth.mahasiswa.listakhir', compact('jml', 'list', 'modeupload', 'call'));
    }

    public function listPoster(Request $request)
    {

        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->whereNotNull('file_pkm.file_laporan_akhir')
            ->where('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->whereNotNull('file_pkm.file_laporan_akhir')
            ->where('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->orderBy('file_pkm.id', 'desc')
            ->get();


        $modeupload = TipePKM::select('*')->where('tipe_pkm.status_akhir', '!=', 0)->count();

        $call = $this->CallCenter();


        return view('auth.mahasiswa.listposter', compact('jml', 'list', 'modeupload', 'call'));
    }

    public function ReferensiJudul(Request $request)
    {

        $jml = FilePKM::select('judul', 'tahun', 'skim_singkat', 'nama', 'nim', 'status')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'ketua')
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->orderBy('file_pkm.id', 'desc')
            ->get();


        $call = $this->CallCenter();

        return view('auth.mahasiswa.referensijudul', compact('jml', 'list', 'call'));
    }

    public function InfoDownload(Request $request)
    {
        $download = Download::all();
        $call = $this->CallCenter();
        return view('auth.mahasiswa.infodownload', compact('call', 'download'));
    }

    public function TambahPKM()
    {
        $modeupload = TipePKM::select('*')->where('tipe_pkm.status_tambah', '!=', 0)->count();
        $tipepkm = TipePKM::wherestatus_tambah(1)->get();
        $lima = TipePKM::wheretipe('5 Bidang')->first();
        $dua = TipePKM::wheretipe('2 Bidang')->first();
        $gfk = TipePKM::wheretipe('PKM GFK')->first();
        $sug = TipePKM::wheretipe('SUG')->first();
        $list_fakultas = Fakultas::all();

        $call = $this->CallCenter();
        return view('auth.mahasiswa.tambahpkm', compact('tipepkm', 'lima', 'dua', 'sug', 'gfk', 'list_fakultas', 'modeupload', 'call'));
    }

    public function CarDohAnggota()
    {
        $nim = Auth::user()->username;
        $saya = IdentitasMahasiswa::wherenim($nim)->first()->id;
        $skim = SkimPKM::all();

        $me = CarDoh::select('cardoh.id as id_cardoh', 'identitas_mahasiswa.nama', 'identitas_mahasiswa.nim', 'prodi.nama_prodi', 'jml_anggota', 'kebutuhan', 'ide_kasar', 'cardoh_skill', 'cardoh_skim')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->get();
        $jmlme = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->count();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->count();

        $call = $this->CallCenter();
        return view('auth.mahasiswa.cardoh_anggota', compact('skim', 'jmlme', 'jmlall', 'me', 'all', 'call'));
    }

    public function CarDohIde()
    {
        $nim = Auth::user()->username;
        $saya = IdentitasMahasiswa::wherenim($nim)->first()->id;
        $skim = SkimPKM::all();

        $me = CarDoh::select('cardoh.id as id_cardoh', 'identitas_mahasiswa.nama', 'identitas_mahasiswa.nim', 'prodi.nama_prodi', 'jml_anggota', 'kebutuhan', 'ide_kasar', 'cardoh_skill', 'cardoh_skim')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->get();
        $jmlme = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->count();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->count();

        $call = $this->CallCenter();
        return view('auth.mahasiswa.cardoh_ide', compact('skim', 'jmlme', 'jmlall', 'me', 'all', 'call'));
    }
    public function CarDohKelompok()
    {
        $nim = Auth::user()->username;
        $saya = IdentitasMahasiswa::wherenim($nim)->first()->id;
        $skim = SkimPKM::all();

        $me = CarDoh::select('cardoh.id as id_cardoh', 'identitas_mahasiswa.nama', 'identitas_mahasiswa.nim', 'prodi.nama_prodi', 'jml_anggota', 'kebutuhan', 'ide_kasar', 'cardoh_skill', 'cardoh_skim')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->get();
        $jmlme = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->where('cardoh.id_mahasiswa', '=', $saya)
            ->count();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->where('cardoh.id_mahasiswa', '!=', $saya)
            ->count();

        $call = $this->CallCenter();
        return view('auth.mahasiswa.cardoh_kelompok', compact('skim', 'jmlme', 'jmlall', 'me', 'all', 'call'));
    }

    public function LihatPKM($id)
    {
        $list_fakultas = Fakultas::all();
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {
                $pkm = FilePKM::select('*')
                    ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                    ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                    ->join('detail_pkm', 'detail_pkm.id_file_pkm', 'file_pkm.id')
                    ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
                    ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
                    ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                    ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
                    ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                    ->where('file_pkm.id', '=', $id)
                    ->first();
                $dosen = Dosen::select('*')->where('id', '=', $pkm->id_dosen)->first();

                //$dosen = Dosen::select('*')->whereid($pkm->id_dosen)->first();

                //return $dosen;

                $jumlahanggota = AnggotaPKM::whereid_file_pkm($id)->count();

                $ketuapkm = AnggotaPKM::whereid_file_pkm($id)
                    ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                    ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                    ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                    ->where('anggota_pkm.jabatan', '=', 'Ketua')
                    ->first();
                $anggotapkm = AnggotaPKM::whereid_file_pkm($id)
                    ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                    ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                    ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                    ->where('anggota_pkm.jabatan', '!=', 'Ketua')
                    ->orderBy('anggota_pkm.jabatan', 'asc')
                    ->get();
                $saya = AnggotaPKM::whereid_file_pkm($id)->whereid_mahasiswa(Auth::user()->identitas_mahasiswa->id)->first();

                $cekacc = AnggotaPKM::whereid_file_pkm($id)->where('jabatan', '!=', 'ketua')->where('acc_anggota', 'like', 'N')->count();

                $call = $this->CallCenter();

                $modetemplate = AddConfig::wheretipe('modetemplate')->first();

                return view('auth.mahasiswa.lihatpkm', compact('id', 'dosen', 'pkm', 'jumlahanggota', 'ketuapkm', 'anggotapkm', 'list_fakultas', 'saya', 'cekacc', 'call', 'modetemplate'));
            } else {
                return redirect()->back()->with('pkm', 'nothave');
            }
        } else {
            return redirect()->back()->with('pkm', 'kosong');
        }
    }

    public function ListDosen()
    {
        $dosen = Dosen::select('nama_dosen', 'nidn_dosen', 'nidk_dosen', 'prodi_dosen', 'keahlian')->where('dosen.id_fakultas', '!=', 8)->get();
        $call = $this->CallCenter();
        //return $dosen;
        return view('auth.mahasiswa.daftardosen', compact('call', 'dosen'));
    }

    public function ListSurat()
    {

        $call = $this->CallCenter();
        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $surat = Surat::orderBy('tgl_pengajuan', 'DESC')
            ->join('file_pkm', 'file_pkm.id', 'surat.id_file_pkm')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)->get();
        //return $surat;
        return view('auth.mahasiswa.listsurat', compact('surat', 'call'));
    }

    public function TambahSurat()
    {

        $call = $this->CallCenter();
        $id_mhs = Auth::user()->identitas_mahasiswa->id;

        $pkm = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
            ->orderBy('file_pkm.id', 'desc')
            ->get();

        //return $surat;
        return view('auth.mahasiswa.tambahsurat', compact('pkm', 'call'));
    }

    public function TambahNota(Request $request)
    {
        
        $id = $request->get('id');
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {
                $cekdidanai = $this->CekDidanai($id);
                if($cekdidanai == 1){

                    $encrypt = Crypt::encrypt($id);
                    $call = $this->CallCenter();
                    //return $surat;
                    return view('auth.mahasiswa.tambahnota', compact('encrypt' ,'call'));

                }else{
                return redirect()->back()->with('dana', 'tidakdidanai');
                }
            } else {
                return redirect()->back()->with('dana', 'nothing');
            }
        } else {
            return redirect()->back()->with('dana', 'empty');
        }
    }


    //untuk mendapatkan Prodi berdasarkan ID Fakultas
    public function SkimPKM(Request $request)
    {
        $tipepkm = Crypt::decryptString($request->get('tipepkm'));
        $skimpkm = SkimPKM::where('id_tipe_pkm', '=', $tipepkm)->get();
        return $skimpkm;
    }
    public function MaxDosen()
    {
        return AddConfig::wheretipe('maxdosen')->first()->konten;
    }
    public function CallCenter()
    {
        $mhs = Auth::user()->identitas_mahasiswa->id_prodi;
        $prodi = Prodi::whereid($mhs)->first();

        $jumlah = CallCenter::whereid_fakultas($prodi->id_fakultas)->count();
        $callcenter = CallCenter::whereid_fakultas($prodi->id_fakultas)->get();

        $response = new \stdClass();
        $response->jml = $jumlah;
        $response->cc = $callcenter;
        return $response;
    }

    public function StatusTipePKM()
    {
        $tipepkm = TipePKM::all();
        return $tipepkm;
    }

    public function DosenFakultas(Request $request)
    {
        $fakultas = $request->get('id_fakultas');

        //$tipepkm = Crypt::decryptString($request->get('tipepkm'));

        $notAvailableDosen = FilePKM::select('dosen.nip_dosen', DB::raw('count(id_dosen) as jumlah'))
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            //->where('tipe_pkm.id', '=', $tipepkm)
            ->where('tahun_pkm.aktif', 1)
            ->groupBy('dosen.nip_dosen')
            ->having('jumlah', '>=', $this->MaxDosen())
            ->pluck('dosen.nip_dosen')->all();

        $dosen;
        if (count($notAvailableDosen) == 0) {
            $dosen = Dosen::select('nama_dosen')->where('id_fakultas', '=', $fakultas)->orderBy('nama_dosen', 'asc')->get();
        } else {
            $dosen = Dosen::select('nama_dosen')
                ->where('id_fakultas', '=', $fakultas)
                ->whereNotIn('nip_dosen', $notAvailableDosen)
                ->orderBy('nama_dosen', 'asc')
                ->get();
        }

        return $dosen;
    }

    public function CekDosen(Request $request)
    {
        $nama_dosen = $request->get('nama_dosen');
        $fakultas_dosen = $request->get('id_fakultas');

        $dosen = Dosen::select('nidk_dosen', 'nidn_dosen', 'nip_dosen')
            ->where('nama_dosen', '=', $nama_dosen)
            ->where('id_fakultas', '=', $fakultas_dosen)
            ->first();
        $id_dosen = Dosen::select('id')
            ->where('nama_dosen', '=', $nama_dosen)
            ->where('id_fakultas', '=', $fakultas_dosen)
            ->first();
        $kodenya = Crypt::encryptString($id_dosen->id);

        $response = new \stdClass();
        $response->dosen = $dosen;
        $response->kode = $kodenya;

        return Response::json($response);
    }

    public function Pendanaan($id)
    {
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {

                $cekdidanai = $this->CekDidanai($id);
                if($cekdidanai == 1){

                    $pkm = FilePKM::select('*')
                        ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                        ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                        ->join('detail_pkm', 'detail_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
                        ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                        ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
                        ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                        ->where('file_pkm.id', '=', $id)
                        ->first();

                    $jumlah = UangPKM::select(DB::raw('(nominal * volume) as Total'))
                                ->join('nota','nota.id','uang_pkm.id_nota')
                                ->where('nota.id_file_pkm','=',$id)
                                ->get();

                    $call = $this->CallCenter();

                    $encrypt = Crypt::encryptString($id);

                    return view('auth.mahasiswa.pendanaan', compact('id','encrypt', 'pkm', 'call','jumlah'));

                }else{
                    return redirect()->back()->with('dana', 'tidakdidanai');
                }
            } else {
                return redirect()->back()->with('dana', 'nothing');
            }
        } else {
            return redirect()->back()->with('dana', 'empty');
        }
    }

    public function DataPendanaan($id)
    {
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {

                $list = UangPKM::select(
                        'uang_pkm.volume',
                        'uang_pkm.nama_pembelian',
                        'uang_pkm.id_nota',
                        'uang_pkm.nominal',
                        'uang_pkm.ppn',
                        'uang_pkm.pph21',
                        'uang_pkm.pph22',
                        'uang_pkm.pph23',
                        'uang_pkm.pph26',
                        'uang_pkm.cek_pembelian',
                        'uang_pkm.komentar_pembelian',
                        'nota.id_toko',
                        'nota.tgl_nota',
                        'kategori.nama_kategori',
                        'toko.nama_toko'
                        )
                        ->join('nota','nota.id','uang_pkm.id_nota')
                        ->join('toko','toko.id','nota.id_toko')
                        ->join('kategori','kategori.id','uang_pkm.id_kategori')
                        ->where('nota.id_file_pkm','=',$id)
                        ->get();

                return $list;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    


    public function CekMhs(Request $request)
    {
        $nim = $request->get('nim');
        try {
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        } catch (\Exception $e) {
            abort(403, 'Gagal Memecah Kode Daftar');
        }
        try {
            $tipe_pkm = Crypt::decryptString($request->get('tipe_kode'));
        } catch (\Exception $e) {
            abort(403, 'Gagal Memecah Kode Daftar');
        }

        $mahasiswa = IdentitasMahasiswa::wherenim($nim)->first();
        if (is_object($mahasiswa)) {
            $id_mhs = $mahasiswa->id;
            $status_anggota = (AnggotaPKM::whereid_file_pkm($id_file_pkm)
                ->where('anggota_pkm.id_mahasiswa', '=', $id_mhs)
                ->count() > 0) ? true : false;
            if (!$status_anggota) {
                if ($tipe_pkm == "1" || $tipe_pkm == "4") {
                    $statusjmlkel = AnggotaPKM::whereid_mahasiswa($id_mhs)
                        ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                        ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                        ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                        ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                        ->where('tahun_pkm.aktif', '=', 1)
                        ->where('tipe_pkm.id', '=', $tipe_pkm)
                        ->count();
                    if ($statusjmlkel < 6) {
                        $retval = IdentitasMahasiswa::select('nama', 'nama_prodi', 'jenjang_prodi',  'jenis_kelamin', 'kelengkapan')->where('identitas_mahasiswa.nim', '=', $nim)->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')->first();
                        $kodenya = Crypt::encryptString($mahasiswa->email);

                        $response = new \stdClass();
                        $response->isi = $retval;
                        $response->kode = $kodenya;
                        return Response::json($response);
                    } else {
                        return $statusjmlkel;
                    }
                } else {
                    $retval = IdentitasMahasiswa::select('nama', 'nama_prodi', 'jenjang_prodi',  'jenis_kelamin', 'kelengkapan')->where('identitas_mahasiswa.nim', '=', $nim)->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')->first();
                    $kodenya = Crypt::encryptString($mahasiswa->email);

                    $response = new \stdClass();
                    $response->isi = $retval;
                    $response->kode = $kodenya;
                    return Response::json($response);
                }
            } else {
                // ini jika sudah jadi anggota
                return 1;
            }
        } else {
            $cekbase = DBMHS::wherenim_mahasiswa($nim)->first();
            if (is_object($cekbase)) {
                $retval = DBMHS::select('nama_mahasiswa', 'nama_prodi', 'jenjang_prodi', 'jns_kel_mahasiswa')
                    ->join('arifpujin_pkm_v3.prodi', 'prodi.id', '=', 'db_mahasiswa.id_prodi_mahasiswa') ///ini harus disesuaikan
                    ->where('nim_mahasiswa', '=', $nim)->first();

                $kodenya = Crypt::encryptString($cekbase->email_mahasiswa);

                $response = new \stdClass();
                $response->isi = $retval;
                $response->kode = $kodenya;
                return Response::json($response);
            } else {
                return $cekbase;
            }
        }
    }


    public function DownloadProposal($kode)
    {
        $file_pkm = FilePKM::whereid(Crypt::decryptString($kode))->first();
        $file_name = $file_pkm->file_proposal;
        $idtipepkm = SkimPKM::whereid($file_pkm->id_skim_pkm)->first()->id_tipe_pkm;
        $idtahun = $file_pkm->id_tahun_pkm;
        $tahun_aktif = TahunPKM::whereid($idtahun)->first()->tahun;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;

        $file = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm . '/' . $file_name;
        if (File::isFile($file)) {

            return Response::download($file);
        }
    }


    public function DownloadLapKemajuan($kode)
    {
        $file_pkm = FilePKM::whereid(Crypt::decryptString($kode))->first();
        $file_name = $file_pkm->file_laporan_kemajuan;
        $idtipepkm = SkimPKM::whereid($file_pkm->id_skim_pkm)->first()->id_tipe_pkm;
        $idtahun = $file_pkm->id_tahun_pkm;
        $tahun_aktif = TahunPKM::whereid($idtahun)->first()->tahun;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;

        $file = storage_path() . '/files/' . $tahun_aktif . '/laporan kemajuan/' . $tipepkm . '/' . $file_name;
        if (File::isFile($file)) {

            return Response::download($file);
        }
    }

    public function DownloadLapAkhir($kode)
    {
        $file_pkm = FilePKM::whereid(Crypt::decryptString($kode))->first();
        $file_name = $file_pkm->file_laporan_akhir;
        $idtipepkm = SkimPKM::whereid($file_pkm->id_skim_pkm)->first()->id_tipe_pkm;
        $idtahun = $file_pkm->id_tahun_pkm;
        $tahun_aktif = TahunPKM::whereid($idtahun)->first()->tahun;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;

        $file = storage_path() . '/files/' . $tahun_aktif . '/laporan akhir/' . $tipepkm . '/' . $file_name;
        if (File::isFile($file)) {

            return Response::download($file);
        }
    }

    public function DownloadPPT($kode)
    {
        $file_pkm = FilePKM::whereid(Crypt::decryptString($kode))->first();
        $file_name = UserDikti::whereid_file_pkm(Crypt::decryptString($kode))->first()->file_ppt;
        $idtipepkm = SkimPKM::whereid($file_pkm->id_skim_pkm)->first()->id_tipe_pkm;
        $idtahun = $file_pkm->id_tahun_pkm;
        $tahun_aktif = TahunPKM::whereid($idtahun)->first()->tahun;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;

        $file = storage_path() . '/files/' . $tahun_aktif . '/file ppt/' . $tipepkm . '/' . $file_name;
        if (File::isFile($file)) {

            return Response::download($file);
        }
    }

    public function CekDownLemPeng(Request $request)
    {
        $id = Crypt::decryptString($request->get('kode'));
        $pkm = FilePKM::whereid($id)->first();

        if ($pkm->template == "Y") {

            $cekacc = AnggotaPKM::whereid_file_pkm($id)->where('jabatan', '!=', 'ketua')->where('acc_anggota', 'like', 'N')->count();
            if ($cekacc == 0) {
                return 1;
            } else {
                return "notacc";
            }
        } else {
            return "notdownload";
        }
    }

    public function GdriveFileDownload()
    {
        $dir = '1Prp2g5TArgds8ayCmiZneJvBOxxXI7Zw'; //folder file download G-Drive
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        //return $contents->where('type', '=', 'dir'); // directories
        //return $contents->where('type', '=', 'file'); // files
        return $contents; // files
    }
    public function AllToko()
    {
        $toko = Toko::select('id','nama_toko','alamat_toko','verify_toko as rekomen')->get();
        return $toko;
    }

    public function CekDidanai($id)
    {
        $t = FilePKM::whereid($id)
            ->where('file_pkm.status', '=', 3)
            ->orWhere('file_pkm.status', '=', 4)
            ->orWhere('file_pkm.status', '=', 5)
            ->count();
        if($t > 0){
            return 1;
        } else{
            return 0;
        }
    }
    
    public function TotalToko(Request $request)
    {
        $idpkm = $request->get('idpkm');
        $idtoko = $request->get('idtoko');

        $t = UangPKM::where('nota.id_file_pkm','=',$idpkm)->where('nota.id_toko','=',$idtoko)->join('nota','nota.id','uang_pkm.id_nota')->value(DB::raw("SUM(uang_pkm.volume * uang_pkm.nominal)"));
        $np = UangPKM::where('nota.id_file_pkm','=',$idpkm)->where('nota.id_toko','=',$idtoko)->join('nota','nota.id','uang_pkm.id_nota')->where('uang_pkm.id_kategori', '!=', 15)->Where('uang_pkm.id_kategori', '!=', 16)->value(DB::raw("SUM(uang_pkm.volume * uang_pkm.nominal)"));
        $j = UangPKM::where('nota.id_file_pkm','=',$idpkm)->where('nota.id_toko','=',$idtoko)->join('nota','nota.id','uang_pkm.id_nota')->count();
        
        $response = new \stdClass();
        $response->total = (int)$t;
        $response->jumlah = (int)$j;
        $response->less = (int)$np;
        return Response::json($response);

    }
    
    public function PenggunaanDana(Request $request)
    {
        $id = $request->get('idpkm');
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
            $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
            if (!$cekpemilikpkm) {

                $t = UangPKM::where('nota.id_file_pkm','=',$id)->join('nota','nota.id','uang_pkm.id_nota')->value(DB::raw("SUM(uang_pkm.volume * uang_pkm.nominal)"));
                $j = FilePKM::where('file_pkm.id','=',$id)->join('user_dikti','user_dikti.id_file_pkm','file_pkm.id')->first()->dana_dikti;

                $response = new \stdClass();
                $response->self = (int)$t;
                $response->danai = (int)$j;
                return Response::json($response);
            } else {
                return "";
            }
        } else {
            return "";
        }

    }

    public function CekSiupNpwp(Request $request)
    {
        $idpkm = $request->get('idpkm');
        $idtoko = $request->get('idtoko');

        $r = LogToko::select('siup_log','npwp_log')->whereid_toko($idtoko)->whereid_file_pkm($idpkm)->first();
        return $r;
    }

    
}
