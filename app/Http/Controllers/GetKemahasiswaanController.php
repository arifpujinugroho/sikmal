<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Response;
use TglIndo;
use App\Model\FilePKM;
use App\Model\TahunPKM;
use App\Model\TipePKM;
use App\Model\SkimPKM;
use App\Model\Surat;
use Crypt;

class GetKemahasiswaanController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai kmhs
    public function __construct()
    {
        //$this->middleware('kmhs');
        $this->middleware(['kmhs', 'sso']);
    }
    ////////////////////////////////////////////////

    public function Home()
    {
        $jml = FilePKM::count();

        $jmlyear = FilePKM::select('*')
            ->join('tahun_pkm', 'tahun_pkm.id', '=', 'file_pkm.id_tahun_pkm')
            ->where('tahun_pkm.aktif', '=', 1)
            ->count();

        $thn = TahunPKM::whereaktif(1)->first()->tahun;

        return view('auth.kemahasiswaan.home', compact('jml', 'jmlyear', 'thn'));
    }

    public function ListPKM(Request $request, $tahunpkm, $tipepkm)
    {

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
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->get();



        return view('auth.kemahasiswaan.listpkm', compact('jml', 'list'));
    }

    public function ListMaksDosen($tahunpkm, $tipepkm)
    {


        $list = FilePKM::select('nama_dosen', DB::raw('count(id_dosen) as jumlah'))
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('dosen', 'dosen.id', '=', 'file_pkm.id_dosen')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->where('tipe_pkm.id', '=', $tipepkm)
            ->where('file_pkm.id_tahun_pkm', '=', $tahunpkm)
            ->groupBy('id_dosen')
            ->orderBy('nama_dosen', 'asc')
            ->get();

        //return $list;

        return view('auth.kemahasiswaan.listmaksdosen', compact('list'));
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

        return view('auth.kemahasiswaan.listdownloadpkm', compact('jml', 'list'));
    }
    public function ListAkunSimbel($tahunpkm, $tipepkm)
    {
        return view('auth.kemahasiswaan.listakunsimbel', compact('tahunpkm', 'tipepkm'));
    }


    public function PilihPKM()
    {
        $tahun = TahunPKM::orderBy('tahun', 'DESC')->get();
        $tipe = TipePKM::select('id', 'tipe')->get();
        return view('auth.kemahasiswaan.listpilih', compact('tahun', 'tipe'));
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
        return view('auth.kemahasiswaan.grafikpkm', compact('data', 'datal', 'tpkm', 'tahun', 'tahunpkm', 'tipepkm', 'tipe'));
    }

    public function ListSurat()
    {
        $surat = Surat::orderBy('tgl_pengajuan', 'DESC')
            ->join('file_pkm', 'file_pkm.id', 'surat.id_file_pkm')
            ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
            ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
            ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
            ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
            ->where('anggota_pkm.jabatan', '=', 'Ketua')->get();
        //return $surat;
        return view('auth.kemahasiswaan.listsurat', compact('surat'));
    }







    //-----------------------------------Data Output--------------------------------------------------//

    public function DataAkunSimbel($tahunpkm, $tipepkm)
    {
        $list = FilePKM::select('identitas_mahasiswa.nim', 'file_pkm.status', 'identitas_mahasiswa.pass_simbel', 'prodi.nama_prodi', 'prodi.jenjang_prodi', 'anggota_pkm.id_file_pkm', 'identitas_mahasiswa.nama')
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


    public function SearchListPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('kmhs/listpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
    }

    public function SearchMaksDosen(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('kmhs/listmaksdosen/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
    }

    public function SearchDownloadPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('kmhs/downloadpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
    }
    public function SearchAkunSimbelmawa(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('kmhs/akunsimbel/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
    }
    public function SearchGrafikPKM(Request $request)
    {
        try {
            $tahun = $request->get('tahunpkm');
            $tipepkm = $request->get('tipepkm');

            return redirect('kmhs/grafikpkm/' . $tahun . '/' . $tipepkm);
        } catch (\Exception $e) {
            abort(404, 'Akses terbatas');
        }
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
        //return view('auth.kmhs.grafikpkm', compact('data'));

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
        //return view('auth.kmhs.grafikpkm', compact('data'));

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
