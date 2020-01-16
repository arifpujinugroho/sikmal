<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use DB;
use File;
use Crypt;
use Response;
use App\User;
use Redirect;
use App\Model\DBMHS;
use App\Model\Prodi;
use App\Model\TipePKM;
use App\Model\Operator;
use App\Model\FilePKM;
use App\Model\SkimPKM;
use App\Model\Fakultas;
use App\Model\TahunPKM;
use App\Model\AnggotaPKM;
use App\Model\CallCenter;
use App\Model\IdentitasMahasiswa;


class GetOperatorController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Operator
    public function __construct()
    {
        $this->middleware('opt');
    }
    ////////////////////////////////////////////////


    public function Home()
    {
        $id_opt = Auth::user()->operator->id;
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;

        $jml = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->count();

        $jmlyear = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->where('tahun_pkm.aktif', '=', 1)
            ->count();

        $call = $this->CallCenter();

        return view('auth.operator.home', compact('fakultas', 'jml', 'jmlyear', 'call'));
    }

    public function GrafikPKM($tahunpkm, $tipepkm)
    {
        $id_fakultas = Auth::user()->operator->id_fakultas;

        $prodi = Prodi::select('id', 'nama_prodi')
            ->where('id_fakultas', $id_fakultas)
            ->get();

        $tpkm = TipePKM::whereid($tipepkm)->firstOrFail();

        $i = 0;
        foreach ($prodi as $prod) {
            if ($tpkm->tipe == '2 Bidang') {
                $AI = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-AI', 'PKMAI');
                $GT = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-GT', 'PKMGT');

                $data[$i] = array_merge($GT[0]->toArray(), $AI[0]->toArray());
            } else if ($tpkm->tipe == '5 Bidang') {
                $KC     = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-KC', 'PKMKC');
                $K      = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-K', 'PKMK');
                $M      = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-M', 'PKMM');
                $PE     = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-PE', 'PKMPE');
                $PSH    = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-PSH', 'PKMPSH');
                $T      = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-T', 'PKMT');
                $P      = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-P', 'PKMP');

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
                $GFK = $this->statpkmProdi($tahunpkm, $prod['id'], 'PKM-GFK', 'PKMGFK');

                $data[$i] = array_merge($GFK[0]->toArray());
            } else if ($tpkm->tipe == 'SUG') {
                $SUG = $this->statpkmProdi($tahunpkm, $prod['id'], 'SUG', 'SUG');

                $data[$i] = array_merge($SUG[0]->toArray());
            }
            $i++;
        }
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;

        $tahun = TahunPKM::whereid($tahunpkm)->firstOrFail()->tahun;
        $tipe = TipePKM::whereid($tipepkm)->firstOrFail()->tipe;

        return view('auth.operator.grafikpkm', compact('data', 'tpkm', 'fakultas', 'tahun', 'tahunpkm', 'tipepkm', 'tipe'));
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
    }

    public function PilihPKM()
    {
        $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
        $tipe = TipePKM::select('id', 'tipe')->get();
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;
        return view('auth.operator.listpilih', compact('tahun', 'tipe', 'fakultas'));
    }

    public function User(Request $request)
    {

        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;

        $jml = IdentitasMahasiswa::select('*')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->count();

        $list = IdentitasMahasiswa::select('*')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->get();


        return view('auth.operator.user', compact('jml', 'list', 'fakultas'));
    }

    public function SearchListPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('opt/listpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }

    public function SearchMaksDosen(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('opt/listmaksdosen/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
    }
    public function SearchDownloadPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('opt/downloadpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }
    public function SearchAkunSimbelmawa(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('opt/akunsimbel/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }
    public function SearchGrafikPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('opt/grafikpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(403, 'Akses terbatas');
        }
    }

    public function ListPKM(Request $request, $tahunpkm, $tipepkm)
    {
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;

        $jml = FilePKM::select('judul', 'skim_singkat', 'nim', 'self', 'nama', 'keyword', 'abstrak', 'linkurl', 'dana_pkm', 'nama_dosen', 'nidn_dosen')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->count();

        $list = FilePKM::select('id_file_pkm', 'judul', 'skim_singkat', 'nim', 'file_proposal', 'self', 'nama', 'keyword', 'abstrak', 'linkurl', 'dana_pkm', 'nama_dosen', 'nidn_dosen')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->get();



        return view('auth.operator.listpkm', compact('jml', 'list', 'fakultas'));
    }

    public function ListMaksDosen($tahunpkm, $tipepkm)
    {


        $list = FilePKM::select('nama_dosen', DB::raw('count(id_dosen) as jumlah'))
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            //->where('file_pkm.ni1', '!=', 'null')
            ->groupBy('id_dosen')
            //->having('jumlah','>=', $this->MaxDosen())
            ->orderBy('nama_dosen', 'asc')
            ->get();

        //return $list;

        return view('auth.operator.listmaksdosen', compact('list'));
    }

    public function ListDownloadPKM($tahunpkm, $tipepkm)
    {
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;


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
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->count();

        $list = FilePKM::select('*')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('user_dikti', 'user_dikti.id_file_pkm', '=', 'file_pkm.id')
            ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->get();

        return view('auth.operator.listdownloadpkm', compact('jml', 'list', 'fakultas'));
    }

    public function ListAkunSimbel($tahunpkm, $tipepkm)
    {
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;


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
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->count();

        $list = FilePKM::select('*')
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
            ->where('prodi.id_fakultas', '=', Auth::user()->operator->id_fakultas)
            ->get();

        return view('auth.operator.listakunsimbel', compact('jml', 'list', 'fakultas'));
    }

    public function CC()
    {
        $call = $this->CallCenter();

        return view('auth.operator.callcenter', compact('call'));
    }


    public function Activation()
    {
        $fakultas = Fakultas::whereid(Auth::user()->operator->id_fakultas)->first()->nama_fakultas;
        return view('auth.operator.activation', compact('fakultas'));
    }

    public function Biodata()
    {
        $opt = Auth::user()->operator->id;
        $data = Operator::select('*')->join('fakultas', 'fakultas.id', 'operator.id_fakultas')->where('operator.id', '=', $opt)->first();
        return view('auth.operator.biodata', compact('data'));
    }

    public function CekAktivation(Request $request)
    {
        $nim = $request->get('nim');

        $mahasiswa = User::whereusername($nim)
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.nim', 'users.username')
            ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
            ->first();
        if (is_object($mahasiswa)) {
            $id_fakultas = $mahasiswa->id_fakultas;
            if (Auth::user()->operator->id_fakultas == $id_fakultas) {
                if (empty($mahasiswa->email_verified_at)) {
                    $retval = IdentitasMahasiswa::select('nama', 'nim', 'nama_prodi', 'jenjang_prodi')
                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                        ->where('identitas_mahasiswa.nim', '=', $nim)
                        ->first();

                    $kodenya = Crypt::encryptString($mahasiswa->id_user);

                    $response = new \stdClass();
                    $response->isi = $retval;
                    $response->kode = $kodenya;
                    return Response::json($response);
                } else {
                    return 'activated';
                }
            } else {
                return 1;
            }
        } else {
            return $mahasiswa;
        }
    }



    public function CallCenter()
    {
        $opt = Auth::user()->operator->id_fakultas;

        $jumlah = CallCenter::whereid_fakultas($opt)->count();
        $callcenter = CallCenter::whereid_fakultas($opt)->get();
        $callcenteruniv = CallCenter::whereid_fakultas('0')->get();
        $jmlcu = CallCenter::whereid_fakultas('0')->count();

        $response = new \stdClass();
        $response->jml = $jumlah;
        $response->cc = $callcenter;
        $response->cu = $callcenteruniv;
        $response->jmlcu = $jmlcu;
        return $response;
        //return Response::json($response);
    }

    public function CekCallCenter(Request $request)
    {
        $nim = $request->get('nim');
        $cekcc = CallCenter::wherenim_callcenter($nim)->first();
        if (is_object($cekcc)) {
            return 1;
        } else {
            $dbmhs = DBMHS::wherenim_mahasiswa($nim)->first();
            if (is_object($dbmhs)) {
                $id_fakultas = Prodi::whereid($dbmhs->id_prodi_mahasiswa)->first()->id_fakultas;
                if (Auth::user()->operator->id_fakultas == $id_fakultas) {
                    $data = DBMHS::select('nama_mahasiswa', 'email_mahasiswa', 'jns_kel_mahasiswa', 'nama_prodi', 'jenjang_prodi')->join('arifpujin_pkm_v3.prodi', 'prodi.id', 'db_mahasiswa.id_prodi_mahasiswa')->where('nim_mahasiswa', '=', $nim)->first();
                    $kodenya = Crypt::encryptString($dbmhs->nim_mahasiswa);

                    $response = new \stdClass();
                    $response->data = $data;
                    $response->kode = $kodenya;
                    return Response::json($response);
                } else {
                    return "Forbiden";
                }
            } else {
                return $dbmhs;
            }
        }
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

    public function statpkmProdi($tahun, $i, $pkm, $name)
    {
        $data = FilePKM::select(array('prodi.nama_prodi', DB::raw('count(skim_pkm.skim_singkat) as ' . $name)))
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tahun_pkm.id', $tahun)
            ->where('skim_pkm.skim_singkat', '=', $pkm)
            ->where('prodi.id', '=', $i)
            ->get();

        return $data;
    }


    public function HapusCallCenter($kode)
    {
        try {
            $id = Crypt::decryptString($kode);
            CallCenter::whereid($id)->delete();
            return redirect()->back()->with('callcenter', 'deleted');
        } catch (\Exception $e) {
            abort(403, 'Gagal memecahkan kode hapus callcenter');
        }
    }

    public function GetAnggotaPKM(Request $request)
    {
        $id = crypt::decrypt($request->get('kode'));

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
