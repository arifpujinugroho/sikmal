<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Hash;
use DB;
use Auth;
use Crypt;
use Storage;
use File;
use App\User;
use Redirect;
use DataTables;
use App\Model\Nota;
use App\Model\UangPKM;
use App\Model\Kategori;
use App\Model\SkimPKM;
use App\Model\Download;
use App\Model\FilePKM;
use App\Model\AnggotaPKM;
use App\Model\TipePKM;
use App\Model\TahunPKM;
use App\Model\AddConfig;
use App\Model\Prodi;
use App\Model\Toko;
use App\Model\Dosen;
use App\Model\Fakultas;
use App\Model\CallCenter;
use App\Model\IdentitasMahasiswa;

class GetAdminController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Admin
    public function __construct()
    {
        //$this->middleware('admin');
        $this->middleware(['admin', 'sso']);
    }
    ////////////////////////////////////////////////


    public function Home()
    {
        $tahun = TahunPKM::whereaktif(1)->first()->tahun;

        $limbid = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('id_tipe_pkm', '=', 1)
            ->count();
        $lenglimbid = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->whereid_tipe_pkm(1)
            ->whereNotNull('file_proposal')
            ->count();

        $dubid = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('id_tipe_pkm', '=', 2)
            ->count();
        $lengdubid = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->whereid_tipe_pkm(2)
            ->whereNotNull('file_proposal')
            ->count();

        $gfk = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('id_tipe_pkm', '=', 3)
            ->count();
        $lenggfk = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->whereid_tipe_pkm(3)
            ->whereNotNull('file_proposal')
            ->count();

        $sug = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->where('id_tipe_pkm', '=', 4)
            ->count();
        $lengsug = FilePKM::whereid_tahun_pkm(TahunPKM::whereaktif(1)->first()->id)
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->whereid_tipe_pkm(4)
            ->whereNotNull('file_proposal')
            ->count();

        return view('auth.admin.home', compact('tahun', 'limbid', 'lenglimbid', 'dubid', 'lengdubid', 'gfk', 'lenggfk', 'sug', 'lengsug'));
    }

    public function ListPKM(Request $request, $tahunpkm, $tipepkm)
    {
        $ntipe = TipePKM::whereid($tipepkm)->first()->tipe;
        $ntahun = TahunPKM::whereid($tahunpkm)->first()->tahun;

        return view('auth.admin.listpkm', compact('tipepkm', 'tahunpkm', 'ntipe', 'ntahun'));
    }



    public function ListMaksDosen($tahunpkm)
    {


        $list = FilePKM::select('dosen.id', 'nama_dosen', DB::raw('count(id_dosen) as jumlah'), 'nama_singkat')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('fakultas', 'fakultas.id', '=', 'dosen.id_fakultas')
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            //->where('file_pkm.ni1', '!=', 'null')
            ->groupBy('id_dosen')
            //->having('jumlah','>=', $this->MaxDosen())
            ->orderBy('nama_dosen', 'asc')
            ->get();

        //return $list;

        $maxdos = AddConfig::wheretipe('maxdosen')->first()->konten;

        return view('auth.admin.listmaksdosen', compact('list', 'tahunpkm', 'maxdos'));
    }

    public function ListDosenBimbing($tahunpkm, $dsn)
    {
        $dosen = Dosen::whereid($dsn)->first();
        $ntahun = TahunPKM::whereid($tahunpkm)->first()->tahun;
        return view('auth.admin.listpkmbimbing', compact('tahunpkm', 'dsn', 'dosen', 'ntahun'));
    }


    public function ListDownloadPKM($tahunpkm, $tipepkm)
    {


        $jml = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->count();

        $list = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->get();

        return view('auth.admin.listdownloadpkm', compact('jml', 'list'));
    }

    public function ListAkunSimbel($tahunpkm, $tipepkm)
    {
        return view('auth.admin.listakunsimbel', compact('tahunpkm', 'tipepkm'));
    }

    public function ListPenil($tahunpkm, $tipepkm)
    {
        $dsn = User::wherelevel('Reviewer')->join('dosen', 'dosen.email_dosen', 'users.username')->get();
        return view('auth.admin.setpenil', compact('dsn', 'tahunpkm', 'tipepkm'));
    }

    public function PrintKode($kode)
    {
        $idpkm = $kode;

        $pkm = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('file_pkm.id', '=', $idpkm)
            ->first();

        $jmlanggota = AnggotaPKM::whereid_file_pkm($idpkm)->count();

        $anggota1 = AnggotaPKM::whereid_file_pkm($idpkm)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('jabatan', '=', 'Anggota 1')
            ->first();
        $anggota2 = AnggotaPKM::whereid_file_pkm($idpkm)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('jabatan', '=', 'Anggota 2')
            ->first();
        $anggota3 = AnggotaPKM::whereid_file_pkm($idpkm)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('jabatan', '=', 'Anggota 3')
            ->first();
        $anggota4 = AnggotaPKM::whereid_file_pkm($idpkm)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('jabatan', '=', 'Anggota 4')
            ->first();
        $anggota5 = AnggotaPKM::whereid_file_pkm($idpkm)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('jabatan', '=', 'Anggota 5')
            ->first();

        return view('auth.admin.printkode', compact('pkm', 'jmlanggota', 'anggota1', 'anggota2', 'anggota3', 'anggota4', 'anggota5'));
    }


    public function AktifPKM()
    {
        return view('auth.admin.aktifpkm');
    }

    public function PilihPKM()
    {
        $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
        $tipe = TipePKM::select('id', 'tipe')->get();
        return view('auth.admin.listpilih', compact('tahun', 'tipe'));
    }

    public function UserMhs(Request $request)
    {
        return view('auth.admin.usermhs');
    }

    public function UserOperator()
    {
        $opt = User::wherelevel("Operator")
            ->join('operator', 'operator.id_user', 'users.id')
            ->join('fakultas', 'fakultas.id', 'operator.id_fakultas')
            ->get();
        $fakultas = Fakultas::all();

        return view('auth.admin.useropt', compact('opt', 'fakultas'));
    }
    
    public function UserPerekap()
    {
        return view('auth.admin.userrkp');
    }

    public function GrafikPKM($tahunpkm, $tipepkm)
    {
        $tpkm = TipePKM::whereid($tipepkm)->firstOrFail();

        for ($i = 0; $i < 7; $i++) {
            if ($tpkm->tipe == '2 Bidang') {
                $AI = $this->statpkm($tahunpkm, $i + 1, 'PKM-AI', 'PKMAI');
                $GT = $this->statpkm($tahunpkm, $i + 1, 'PKM-GT', 'PKMGT');

                $data[$i] = array_merge($GT[0]->toArray(), $AI[0]->toArray());
            } else if ($tpkm->tipe == '5 Bidang') {
                $KC =   $this->statpkm($tahunpkm, $i + 1, 'PKM-KC', 'PKMKC');
                $K =    $this->statpkm($tahunpkm, $i + 1, 'PKM-K', 'PKMK');
                $M =    $this->statpkm($tahunpkm, $i + 1, 'PKM-M', 'PKMM');
                $PE =   $this->statpkm($tahunpkm, $i + 1, 'PKM-PE', 'PKMPE');
                $PSH =  $this->statpkm($tahunpkm, $i + 1, 'PKM-PSH', 'PKMPSH');
                $T =    $this->statpkm($tahunpkm, $i + 1, 'PKM-T', 'PKMT');
                $P =    $this->statpkm($tahunpkm, $i + 1, 'PKM-P', 'PKMP');

                $data[$i] = array_merge(
                    $KC[0]->toArray(),
                    $K[0]->toArray(),
                    $M[0]->toArray(),
                    $PE[0]->toArray(),
                    $PSH[0]->toArray(),
                    $T[0]->toArray(),
                    $P[0]->toArray()
                );
            } else if ($tpkm->tipe == 'PKM GFK') {
                $GFK = $this->statpkm($tahunpkm, $i + 1, 'PKM-GFK', 'PKMGFK');

                $data[$i] = array_merge($GFK[0]->toArray());
            } else if ($tpkm->tipe == 'SUG') {
                $SUG = $this->statpkm($tahunpkm, $i + 1, 'SUG', 'SUG');
                $data[$i] = array_merge($SUG[0]->toArray());
            }
        }

        //data lengkap
        for ($i = 0; $i < 7; $i++) {
            if ($tpkm->tipe == '2 Bidang') {
                $AI = $this->statpkml($tahunpkm, $i + 1, 'PKM-AI', 'PKMAI');
                $GT = $this->statpkml($tahunpkm, $i + 1, 'PKM-GT', 'PKMGT');

                $datal[$i] = array_merge($GT[0]->toArray(), $AI[0]->toArray());
            } else if ($tpkm->tipe == '5 Bidang') {
                $KC =   $this->statpkml($tahunpkm, $i + 1, 'PKM-KC', 'PKMKC');
                $K =    $this->statpkml($tahunpkm, $i + 1, 'PKM-K', 'PKMK');
                $M =    $this->statpkml($tahunpkm, $i + 1, 'PKM-M', 'PKMM');
                $PE =   $this->statpkml($tahunpkm, $i + 1, 'PKM-PE', 'PKMPE');
                $PSH =  $this->statpkml($tahunpkm, $i + 1, 'PKM-PSH', 'PKMPSH');
                $T =    $this->statpkml($tahunpkm, $i + 1, 'PKM-T', 'PKMT');
                $P =    $this->statpkml($tahunpkm, $i + 1, 'PKM-P', 'PKMP');

                $datal[$i] = array_merge(
                    $KC[0]->toArray(),
                    $K[0]->toArray(),
                    $M[0]->toArray(),
                    $PE[0]->toArray(),
                    $PSH[0]->toArray(),
                    $T[0]->toArray(),
                    $P[0]->toArray()
                );
            } else if ($tpkm->tipe == 'PKM GFK') {
                $GFK = $this->statpkml($tahunpkm, $i + 1, 'PKM-GFK', 'PKMGFK');

                $datal[$i] = array_merge($GFK[0]->toArray());
            } else if ($tpkm->tipe == 'SUG') {
                $SUG = $this->statpkml($tahunpkm, $i + 1, 'SUG', 'SUG');
                $datal[$i] = array_merge($SUG[0]->toArray());
            }
        }
        $tahun = TahunPKM::whereid($tahunpkm)->firstOrFail()->tahun;
        $tipe = TipePKM::whereid($tipepkm)->firstOrFail()->tipe;


        //return Response::json($data);
        return view('auth.admin.grafikpkm', compact('data', 'datal', 'tpkm', 'tahun', 'tahunpkm', 'tipepkm', 'tipe'));
    }

    public function ListDosen()
    {
        return view('auth.admin.daftardosen');
    }


    public function NilaiProposal($tahunpkm, $tipepkm)
    {
        $namatahun = TahunPKM::whereid($tahunpkm)->first()->tahun;
        $namatipe = TipePKM::whereid($tipepkm)->first()->tipe;
        return view('auth.admin.nilaiproposal', compact('namatahun', 'namatipe', 'tahunpkm', 'tipepkm'));
    }

    public function Setting(Request $request)
    {
        $tahun_anggaran = TahunPKM::orderBy('tahun_anggaran', 'asc')->get();

        return view('auth.admin.setting', compact('tahun_anggaran'));
    }

    public function LihatPKM($id)
    {
        $list_fakultas = Fakultas::all();

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

                $cekacc = AnggotaPKM::whereid_file_pkm($id)->where('jabatan', '!=', 'ketua')->where('acc_anggota', 'like', 'N')->count();
                
                $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
                $skim = SkimPKM::all();

                return view('auth.admin.lihatpkm', compact('id', 'dosen', 'pkm', 'jumlahanggota', 'ketuapkm', 'anggotapkm', 'list_fakultas', 'cekacc','tahun','skim'));
    }

    public function InfoDownload(Request $request)
    {
        return view('auth.admin.infodownload');
    }

    public function ListToko(Request $request)
    {
        return view('auth.admin.listtoko');
    }

    public function InputCustom()
    {
        $tipepkm = TipePKM::all();
        $tahun = TahunPKM::all();
        $list_fakultas = Fakultas::all();

        return view('auth.admin.inputcustom', compact('list_fakultas','tipepkm', 'tahun'));
    }

    public function ListLaporanKeuangan($tahunpkm,$tipepkm)
    {
        return view('auth.admin.listkeuangan', compact('tahunpkm','tipepkm'));
    }

    public function Pendanaan($id)
    {
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {
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

            $kate = Kategori::all();

            $encrypt = Crypt::encryptString($id);

            return view('auth.admin.pendanaan', compact('id','kate','encrypt', 'pkm','jumlah'));
        } else {
            return redirect()->back()->with('dana', 'empty');
        }
    }

    public function DataPendanaan($id)
    {
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {

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
                        'nota.id_toko',
                        'nota.file_nota',
                        'nota.tgl_nota',
                        'kategori.nama_kategori',
                        'uang_pkm.id_kategori',
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
    }

    
    ///Ended VIEW----------////////////////////-------------------=====================================


    //Data Fungtion------------------------------------------------------------------------------------
    public function DataGrafikPKM($tahunpkm, $tipepkm)
    {
        $tpkm = TipePKM::whereid($tipepkm)->firstOrFail();

        for ($i = 0; $i < 7; $i++) {
            if ($tpkm->tipe == '2 Bidang') {
                $AI = $this->statpkm($tahunpkm, $i + 1, 'PKM-AI', 'PKMAI');
                $GT = $this->statpkm($tahunpkm, $i + 1, 'PKM-GT', 'PKMGT');

                $data[$i] = array_merge($GT[0]->toArray(), $AI[0]->toArray());
            } else if ($tpkm->tipe == '5 Bidang') {
                $KC =   $this->statpkm($tahunpkm, $i + 1, 'PKM-KC', 'PKMKC');
                $K =    $this->statpkm($tahunpkm, $i + 1, 'PKM-K', 'PKMK');
                $M =    $this->statpkm($tahunpkm, $i + 1, 'PKM-M', 'PKMM');
                $PE =   $this->statpkm($tahunpkm, $i + 1, 'PKM-PE', 'PKMPE');
                $PSH =  $this->statpkm($tahunpkm, $i + 1, 'PKM-PSH', 'PKMPSH');
                $T =    $this->statpkm($tahunpkm, $i + 1, 'PKM-T', 'PKMT');
                $P =    $this->statpkm($tahunpkm, $i + 1, 'PKM-P', 'PKMP');

                $data[$i] = array_merge(
                    $KC[0]->toArray(),
                    $K[0]->toArray(),
                    $M[0]->toArray(),
                    $PE[0]->toArray(),
                    $PSH[0]->toArray(),
                    $T[0]->toArray(),
                    $P[0]->toArray()
                );
            } else if ($tpkm->tipe == 'PKM GFK') {
                $GFK = $this->statpkm($tahunpkm, $i + 1, 'PKM-GFK', 'PKMGFK');

                $data[$i] = array_merge($GFK[0]->toArray());
            } else if ($tpkm->tipe == 'SUG') {
                $SUG = $this->statpkm($tahunpkm, $i + 1, 'SUG', 'SUG');
                $data[$i] = array_merge($SUG[0]->toArray());
            }
        }


        return Response::json($data);
        //return view('auth.admin.grafikpkm', compact('data'));

    }

    

    public function DataGrafikPKMLengkap($tahunpkm, $tipepkm)
    {
        $tpkm = TipePKM::whereid($tipepkm)->firstOrFail();

        for ($i = 0; $i < 7; $i++) {
            if ($tpkm->tipe == '2 Bidang') {
                $AI = $this->statpkml($tahunpkm, $i + 1, 'PKM-AI', 'PKMAI');
                $GT = $this->statpkml($tahunpkm, $i + 1, 'PKM-GT', 'PKMGT');

                $data[$i] = array_merge($GT[0]->toArray(), $AI[0]->toArray());
            } else if ($tpkm->tipe == '5 Bidang') {
                $KC =   $this->statpkml($tahunpkm, $i + 1, 'PKM-KC', 'PKMKC');
                $K =    $this->statpkml($tahunpkm, $i + 1, 'PKM-K', 'PKMK');
                $M =    $this->statpkml($tahunpkm, $i + 1, 'PKM-M', 'PKMM');
                $PE =   $this->statpkml($tahunpkm, $i + 1, 'PKM-PE', 'PKMPE');
                $PSH =  $this->statpkml($tahunpkm, $i + 1, 'PKM-PSH', 'PKMPSH');
                $T =    $this->statpkml($tahunpkm, $i + 1, 'PKM-T', 'PKMT');
                $P =    $this->statpkml($tahunpkm, $i + 1, 'PKM-P', 'PKMP');

                $data[$i] = array_merge(
                    $KC[0]->toArray(),
                    $K[0]->toArray(),
                    $M[0]->toArray(),
                    $PE[0]->toArray(),
                    $PSH[0]->toArray(),
                    $T[0]->toArray(),
                    $P[0]->toArray()
                );
            } else if ($tpkm->tipe == 'PKM GFK') {
                $GFK = $this->statpkml($tahunpkm, $i + 1, 'PKM-GFK', 'PKMGFK');

                $data[$i] = array_merge($GFK[0]->toArray());
            } else if ($tpkm->tipe == 'SUG') {
                $SUG = $this->statpkml($tahunpkm, $i + 1, 'SUG', 'SUG');
                $data[$i] = array_merge($SUG[0]->toArray());
            }
        }


        return Response::json($data);
        //return view('auth.admin.grafikpkm', compact('data'));

    }

    public function statpkm($tahun, $i, $pkm, $name)
    {
        $data = FilePKM::select(array('fakultas.nama_singkat', DB::raw('count(skim_pkm.skim_singkat) as ' . $name)))
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tahun_pkm.id', $tahun)
            ->where('skim_pkm.skim_singkat', '=', $pkm)
            ->where('fakultas.id', '=', $i)
            ->get();

        return $data;
    }

    public function statpkml($tahun, $i, $pkm, $name)
    {
        $data = FilePKM::select(array('fakultas.nama_singkat', DB::raw('count(skim_pkm.skim_singkat) as ' . $name)))
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('file_pkm.file_proposal', '!=', '')
            ->where('tahun_pkm.id', $tahun)
            ->where('skim_pkm.skim_singkat', '=', $pkm)
            ->where('fakultas.id', '=', $i)
            ->get();

        return $data;
    }

    

    public function EditDosen(Request $request)
    {
        DB::beginTransaction();
        try {
            $dsn = Dosen::whereid($request->get('id'))->first();
            $dsn->nidn_dosen = $request->get('nidn');
            $dsn->nidk_dosen = $request->get('nidk');
            $dsn->simbel_akun = $request->get('pass');
            $dsn->save();

            DB::commit();
            return "1";
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function AllTipePKM()
    {
        $tipe = TipePKM::all();
        return $tipe;
    }

    public function AllTahunPKM()
    {
        $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
        return $tahun;
    }

    public function AllDownload()
    {
        $down = Download::all();
        return $down;
    }

    public function AllToko()
    {
        $down = Toko::all();
        return $down;
    }

    public function AllCallCenter(Request $request)
    {
        $cekcc = CallCenter::whereid_fakultas($request->get('fakultas'))->count();
        if ($cekcc == 0) {
            return "";
        } else {
            $cc = CallCenter::whereid_fakultas($request->get('fakultas'))->get();
            return Response::json($cc);
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

    public function SkimPKM(Request $request)
    {
        $tipepkm = Crypt::decryptString($request->get('tipepkm'));
        $skimpkm = SkimPKM::where('id_tipe_pkm', '=', $tipepkm)->get();
        return $skimpkm;
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

    public function DosenFakultas(Request $request)
    {
        $fakultas = $request->get('id_fakultas');

        //$tipepkm = Crypt::decryptString($request->get('tipepkm'));

        /*
        $notAvailableDosen = FilePKM::select('dosen.nip_dosen', DB::raw('count(id_dosen) as jumlah'))
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            //->where('tipe_pkm.id', '=', $tipepkm)
            //->where('tahun_pkm.aktif', 1)
            ->groupBy('dosen.nip_dosen')
            //->having('jumlah', '>=', $this->MaxDosen())
            ->pluck('dosen.nip_dosen')->all();
        */
        //$dosen;
        //if (count($notAvailableDosen) == 0) {
            $dosen = Dosen::select('nama_dosen')->where('id_fakultas', '=', $fakultas)->orderBy('nama_dosen', 'asc')->get();
        /*} else {
            $dosen = Dosen::select('nama_dosen')
                ->where('id_fakultas', '=', $fakultas)
                ->whereNotIn('nip_dosen', $notAvailableDosen)
                ->orderBy('nama_dosen', 'asc')
                ->get();
        }
        */

        return $dosen;
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

    public function CekMhsCustom(Request $request)
    {
        $nim = $request->get('nim');

        $mahasiswa = IdentitasMahasiswa::wherenim($nim)->first();
        if (is_object($mahasiswa)) {
            $retval = IdentitasMahasiswa::select('nama', 'nama_prodi', 'jenjang_prodi',  'jenis_kelamin', 'kelengkapan')->where('identitas_mahasiswa.nim', '=', $nim)->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')->first();
            $kodenya = Crypt::encryptString($mahasiswa->email);

            $response = new \stdClass();
            $response->isi = $retval;
            $response->kode = $kodenya;
            return Response::json($response);
                
        } else {
            $cekbase = DBMHS::wherenim_mahasiswa($nim)->first();
            if (is_object($cekbase)) {
                $retval = DBMHS::select('nama_mahasiswa', 'nama_prodi', 'jenjang_prodi', 'jns_kel_mahasiswa')
                    ->join('arifpujin_pkm_v3.prodi', 'prodi.id', '=', 'db_mahasiswa.id_prodi_mahasiswa') ///ini harus disesuaikan
                    ->where('nim_mahasiswa', '=', $nim)->first();

                $kodenya = Crypt::encryptString($cekbase->email_mahasiswa);

                $usr = new User();
                $usr->username = $cekbase->nim_mahasiswa;
                $usr->password = Hash::make($cekbase->email_mahasiswa);
                $usr->level = "Mahasiswa";
                $usr->save();

                $mhs = new IdentitasMahasiswa();
                $mhs->nim = $cekbase->nim_mahasiswa;
                $mhs->nama = $cekbase->nama_mahasiswa;
                $mhs->email = $cekbase->email_mahasiswa;
                $mhs->jenis_kelamin = $cekbase->jns_kel_mahasiswa;
                $mhs->id_prodi = $cekbase->id_prodi_mahasiswa;
                $mhs->tanggallahir = $cekbase->tgllahir_mahasiswa;
                $mhs->tempatlahir = $cekbase->tmptlahir_mahasiswa;
                $mhs->crypt_token = Crypt::encrypt($cekbase->email_mahasiswa);
                $mhs->id_user = $usr->id;
                $mhs->save();

                $response = new \stdClass();
                $response->isi = $retval;
                $response->kode = $kodenya;
                return Response::json($response);
            } else {
                return $cekbase;
            }
        }
    }



    /////////////////////////////////////// Return Data ////////////////////////////////////////////////////


    //data untuk List PKM
    public function DataListPKM($tahunpkm, $tipepkm)
    {

        $list = FilePKM::select(
            'identitas_mahasiswa.nama',
            'identitas_mahasiswa.nim',
            'identitas_mahasiswa.email',
            'prodi.nama_prodi',
            'prodi.jenjang_prodi',
            'dosen.nama_dosen',
            'dosen.nip_dosen',
            'dosen.nidn_dosen',
            'dosen.nidk_dosen',
            'skim_pkm.skim_singkat',
            'fakultas.nama_singkat',
            'file_pkm.judul',
            'file_pkm.keyword',
            'file_pkm.dana_pkm',
            'file_pkm.durasi',
            'file_pkm.status as statuspkm',
            'file_pkm.linkurl',
            'file_pkm.self',
            'file_pkm.file_proposal',
            'file_pkm.file_laporan_kemajuan',
            'file_pkm.file_laporan_akhir',
            'file_pkm.confirm_up',
            'file_pkm.id'
        )
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->get();
        return $list;
    }

    public function DataProposal($tahunpkm, $tipepkm)
    {

        $data = FilePKM::select('nama', 'nama_dosen', 'self', 'skim_singkat', 'nim','email', 'judul', 'prodi.*', 'nilai_pkm.*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('dosen', 'dosen.email_dosen', 'nilai_pkm.penilai_proposal')
            ->join('tahun_pkm', 'file_pkm.id_tahun_pkm', '=', 'tahun_pkm.id')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->wherenotnull('file_proposal')
            ->get();

        return $data;
    }


    public function DataPenil($tahunpkm, $tipepkm)
    {
        $data = FilePKM::select('nama', 'nim', 'judul', 'skim_singkat', 'penilai_proposal', 'nilai_pkm.id_file_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->wherenotnull('file_proposal')
            ->get();
        return $data;
    }


    public function DataDosen()
    {
        $data = Dosen::select(
            'dosen.id as id_dosen',
            'nama_singkat',
            'nama_fakultas',
            'nama_dosen',
            'email_dosen',
            'nip_dosen',
            'nidn_dosen',
            'nidk_dosen'
        )
            ->join('fakultas', 'fakultas.id', 'dosen.id_fakultas')
            ->get();

        return $data;
    }

    public function DataListKeuangan(Request $request)
    {
        return FilePKM::select('user_dikti.dana_acc','file_pkm.id','identitas_mahasiswa.nama','tahun_pkm.tahun','skim_pkm.skim_singkat','file_pkm.judul','user_dikti.dana_dikti')
        ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
        ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
        ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
        ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
        ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
        ->where('anggota_pkm.jabatan', '=', 'Ketua')
        ->where('tipe_pkm.id', '=', $request->get('tipe'))
        ->where('file_pkm.id_tahun_pkm', '=', $request->get('thn'))
        ->where('file_pkm.status', '!=', 1)
        ->Where('file_pkm.status', '!=', 2)
        ->get();
    }

    public function GetAnggotaPKM(Request $request)
    {
        $id = $request->get('kode');

        $data = AnggotaPKM::select('nim', 'nama', 'telepon', 'nama_prodi', 'jenjang_prodi', 'nama_fakultas')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('anggota_pkm.id_file_pkm', '=', $id)
            ->first();

        $anggota =  AnggotaPKM::select('nim', 'nama', 'telepon', 'nama_prodi', 'jenjang_prodi', 'nama_fakultas')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
            ->where('anggota_pkm.jabatan', '!=', 'Ketua')
            ->where('anggota_pkm.id_file_pkm', '=', $id)
            ->get();

        $response = new \stdClass();
        $response->ketua = $data;
        $response->anggota = $anggota;
        return json_encode($response);
    }


    public function DataReviewer()
    {
        $data = User::Select('users.id as id_user', 'nama_dosen', 'nip_dosen', 'nidn_dosen', 'nidk_dosen', 'nama_singkat')->wherelevel('Reviewer')
            ->join('dosen', 'dosen.email_dosen', 'users.username')
            ->join('fakultas', 'fakultas.id', 'dosen.id_fakultas')
            ->get();
        return $data;
    }


    public function DataListDosen()
    {
        $dosen = Dosen::select('nama_dosen', 'dosen.id', 'simbel_akun', 'nidn_dosen', 'nip_dosen', 'email_dosen', 'nidk_dosen', 'nama_singkat', 'prodi_dosen', 'keahlian')->join('fakultas', 'fakultas.id', 'dosen.id_fakultas')->where('dosen.id_fakultas', '!=', 8)->get();
        return $dosen;
    }


    public function DataUserMhs(Request $request)
    {
        return DataTables::of(IdentitasMahasiswa::select('identitas_mahasiswa.id', 'nama', 'jenis_kelamin', 'pass_simbel', 'nim', 'nama_prodi', 'jenjang_prodi', 'ukuranbaju', 'telepon', 'backup_telepon', 'alamat')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->get())->make(true);
    }



    public function DataListDosenBimbing($tahunpkm, $dsn)
    {
        return  FilePKM::select('file_pkm.id','nama', 'skim_singkat', 'nama_prodi', 'jenjang_prodi', 'telepon', 'judul', 'file_pkm.status as statuspkm')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('file_pkm.id_dosen', '=', $dsn)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->orderBy('file_pkm.id', 'desc')
            ->get();
    }


    public function DataAkunSimbel($tahunpkm, $tipepkm)
    {
        $list = FilePKM::select('identitas_mahasiswa.nim', 'file_pkm.status', 'identitas_mahasiswa.pass_simbel','user_dikti.dana_dikti', 'prodi.nama_prodi', 'prodi.jenjang_prodi', 'anggota_pkm.id_file_pkm', 'identitas_mahasiswa.nama')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->get();

        return $list;
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
        $cekidpkm = FilePKM::whereid($id)->count();
        if ($cekidpkm > 0) {

                $t = UangPKM::where('nota.id_file_pkm','=',$id)->join('nota','nota.id','uang_pkm.id_nota')->value(DB::raw("SUM(uang_pkm.volume * uang_pkm.nominal)"));
                $j = FilePKM::where('file_pkm.id','=',$id)->join('user_dikti','user_dikti.id_file_pkm','file_pkm.id')->first()->dana_dikti;

                $response = new \stdClass();
                $response->self = (int)$t;
                $response->danai = (int)$j;
                return Response::json($response);
        } else {
            return "";
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
    
}
