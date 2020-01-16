<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use File;
use Response;
use App\Model\Dosen;
use App\Model\FilePKM;
use App\Model\SkimPKM;
use App\Model\TahunPKM;
use App\Model\TipePKM;

class GetReviewerController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Admin
    public function __construct()
    {
        //$this->middleware('reviewer');
        $this->middleware(['reviewer', 'sso']);
    }
    ////////////////////////////////////////////////
    public function DSN()
    {
        $auth = Auth::user()->username;
        $dsn = Dosen::whereemail_dosen($auth)->first();
        return $dsn;
    }

    public function NilaiProposal()
    {
        $dsn = $this->DSN();

       return view('auth.perekap.nilaiproposal', compact('dsn'));
    }

    public function DataProposal()
    {
        $dsn = $this->DSN();

        $data = FilePKM::select('nama','email','telepon','self','file_proposal','skim_singkat','nim','judul','prodi.*','nilai_pkm.*')
                ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', '=', 'file_pkm.id')
                ->join('skim_pkm', 'skim_pkm.id', '=', 'file_pkm.id_skim_pkm')
                ->join('tipe_pkm', 'tipe_pkm.id', '=', 'skim_pkm.id_tipe_pkm')
                ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'anggota_pkm.id_mahasiswa')
                ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
                ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', '=', 'file_pkm.id')
                ->join('tahun_pkm', 'file_pkm.id_tahun_pkm', '=', 'tahun_pkm.id')
                ->where('anggota_pkm.jabatan', '=', 'Ketua')
                ->where('tahun_pkm.aktif', '=', 1)
                ->where('penilai_proposal','=', $dsn->email_dosen)
                ->get();

        return $data;
    }

    public function DownloadProposal($kode,$file_name)
    {
        $file_pkm = FilePKM::whereid($kode)->first();
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
