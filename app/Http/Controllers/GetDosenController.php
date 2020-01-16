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

class GetDosenController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Mahasiswa
    public function __construct()
    {
        $this->middleware(['dsn', 'sso']);
        //$this->middleware('mhs');
    }
    ////////////////////////////////////////////////


    public function Home()
    {
        $username = Auth::user()->username;
        $dsn = Dosen::whereemail_dosen($username)->first();

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

        $jml = FilePKM::select('*')
            ->where('file_pkm.id_dosen', '=', $dsn->id)
            ->count();

        $jmlyear = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->where('file_pkm.id_dosen', '=', $dsn->id)
            ->where('tahun_pkm.aktif', '=', 1)
            ->count();

        return view('auth.dosen.home', compact('dsn', 'jml', 'jmlyear','tahun', 'limbid', 'lenglimbid', 'dubid', 'lengdubid', 'gfk', 'lenggfk', 'sug', 'lengsug'));
    }

    public function PilihPKM()
    {
        $dsn = $this->DSN();
        $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
        $tipe = TipePKM::select('id', 'tipe')->get();
        return view('auth.dosen.listpilih', compact('dsn', 'tipe', 'tahun'));
    }

    public function CarDohAnggota()
    {
        $dsn = $this->DSN();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 1)
            ->count();

        return view('auth.dosen.cardoh_anggota', compact('dsn', 'jmlall', 'all'));
    }

    public function SearchListPKMBimbing(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');

            return redirect('dsn/listpkmbimbing/' . $tahun);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }

    public function SearchListPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('dsn/listpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }

    public function SearchGrafikPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('dsn/grafikpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }

    public function listPKMBimbing($tahunpkm)
    {

        $dsn = $this->DSN();

        $list = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('file_pkm.id_dosen', '=', $dsn->id)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->orderBy('file_pkm.id', 'desc')
            ->get();

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('file_pkm.id_dosen', '=', $dsn->id)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->orderBy('file_pkm.id', 'desc')
            ->count();

        return view('auth.dosen.listpkmbimbing', compact('jml', 'list', 'dsn'));
    }

    public function ListPKM(Request $request, $tahunpkm, $tipepkm)
    {

        $dsn = $this->DSN();
        $jml = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->count();

        $list = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->get();



        return view('auth.dosen.listpkm', compact('dsn', 'jml', 'list'));
    }



    public function CarDohIde()
    {
        $dsn = $this->DSN();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 2)
            ->count();

        return view('auth.dosen.cardoh_ide', compact('dsn', 'jmlall', 'all'));
    }

    public function CarDohKelompok()
    {
        $dsn = $this->DSN();

        $all = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->get();
        $jmlall = CarDoh::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', 'cardoh.id_tahun_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'cardoh.id_mahasiswa')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->where('tahun_pkm.aktif', '=', 1)
            ->where('tipe_cardoh', '=', 3)
            ->count();

        return view('auth.dosen.cardoh_kelompok', compact('dsn', 'jmlall', 'all'));
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

        $dsn = $this->DSN();
        //return Response::json($data);
        return view('auth.dosen.grafikpkm', compact('dsn', 'data', 'datal', 'tpkm', 'tahun', 'tahunpkm', 'tipepkm', 'tipe'));
    }

    public function DSN()
    {
        $auth = Auth::user()->username;
        $dsn = Dosen::whereemail_dosen($auth)->first();
        return $dsn;
    }

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
}
