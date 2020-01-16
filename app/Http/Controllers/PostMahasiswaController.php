<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use File;
use Hash;
use Auth;
use Storage;
use Validator;
use Response;
use App\User;
use Redirect;
use App\Model\DBMHS;
use App\Model\Prodi;
use App\Model\Dosen;
use App\Model\Toko;
use App\Model\Nota;
use App\Model\CarDoh;
use App\Model\FailUp;
use App\Model\FilePKM;
use App\Model\TipePKM;
use App\Model\UangPKM;
use App\Model\SkimPKM;
use App\Model\TahunPKM;
use App\Model\Fakultas;
use App\Model\NilaiPKM;
use App\Model\UserDikti;
use App\Model\AddConfig;
use App\Model\DetailPKM;
use App\Model\AnggotaPKM;
use App\Model\IdentitasMahasiswa;

class PostMahasiswaController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Mahasiswa
    public function __construct()
    {
        $this->middleware(['mhs', 'sso']);
        //$this->middleware('mhs');
    }
    ////////////////////////////////////////////////

    public function GantiPass(Request $request)
    {
        $pass = $request->get('password');
        $isme = Auth::user()->identitas_mahasiswa->crypt_token;
        $dcrypt = Crypt::decrypt($isme);

        if ($pass == $dcrypt) {
            return redirect()->back()->with('password', 'old');
        } else {
            DB::beginTransaction();
            try {
                $ganti = User::whereusername(Auth::user()->identitas_mahasiswa->nim)->first();
                $ganti->password = Hash::make($pass);
                $ganti->save();

                $mhs = IdentitasMahasiswa::wherenim(Auth::user()->identitas_mahasiswa->nim)->first();
                $mhs->crypt_token = Crypt::encrypt($pass);
                $mhs->save();
                DB::commit();
                return redirect()->back()->with('password', 'success');
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
    }

    public function GantiData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alamat' => 'required',
            'telepon' => 'required',
            'ukuranbaju' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $saya = Auth::user()->identitas_mahasiswa->id;
            $mhs = IdentitasMahasiswa::whereid($saya)->first();
            $mhs->alamat = $request->get('alamat');
            $mhs->telepon = $request->get('telepon');
            $mhs->backup_telepon = $request->get('backup_telepon');
            $mhs->ukuranbaju = $request->get('ukuranbaju');
            $mhs->save();

            DB::commit();
            if ($mhs) {
                return redirect()->back()->with('data', 'success');
            } else {
                return redirect()->back()->with('data', 'failed');
            }
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function GantiFoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        DB::beginTransaction();
        try {
            $id = Auth::user()->identitas_mahasiswa->id;
            $saya = IdentitasMahasiswa::whereid($id)->first();
            $fotosaya = Auth::user()->identitas_mahasiswa->pasfoto;
            $destinasi = 'storage/files/pasfoto/' . $fotosaya;
            File::delete($destinasi);

            $idfakultas = Prodi::whereid($saya->id_prodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $nim = $saya->nim;
            $file = $request->file('foto');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = $fakultas . '-' . $nim . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/pasfoto/';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }
            $file->move($destinasi, $nama_file);

            try {
                $destinasifile = storage_path() . '/files/pasfoto/' . $nama_file;
                $fileData = File::get($destinasifile);
                $dir = '/';
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir = $contents->where('type', '=', 'dir')
                    ->where('filename', '=', 'foto')
                    ->first(); // There could be duplicate directory names!
                if (!$dir) {
                    return 'Directory does not exist!';
                }
                Storage::cloud()->put($dir['path'] . '/' . $nama_file, $fileData);
            } catch (\Exception $e) {
                $fail = new FailUp();
                $fail->tipe = "Foto";
                $fail->namafile = $nama_file;
                $fail->dir = $destinasifile;
                $fail->save();
            }

            if ($file) {
                $update = IdentitasMahasiswa::whereid($id)->first();
                $update->pasfoto = $nama_file;
                $update->save();

                DB::commit();
                return redirect()->back()->with('pasfoto', 'success');
            } else {
                return redirect()->back()->with('pasfoto', 'failed');
            }
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //untuk menambah pkm
    public function TambahPKM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipepkm' => 'required',
            'skimpkm' => 'required',
            'judul' => 'required',
            'self' => 'required',
            'kodedosen' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
        $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
        $id_ketua = Auth::user()->identitas_mahasiswa->id;
        $nim = Auth::user()->identitas_mahasiswa->nim;
        $idtipepkm = Crypt::decryptString($request->get('tipepkm'));
        $idskimpkm = $request->get('skimpkm');
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
        $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
        $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
        $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
        $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
        $cektambah = TipePKM::whereid($idtipepkm)->first()->status_tambah;

        if($cektambah == 1){
                        $jml_kata = str_word_count($request->get('judul'));
            if ($jml_kata > 20) {
                abort(403, 'Judul Lebih dari 20 Kata, mohon periksa kembali. atau hubungi TIM IT PKM Center');
            }
            if ($idtipepkm == 1) {
                if ($request->get('danapkm') > 12500000 && $request->get('danapkm') < 5000000) {
                    abort(403, 'Dana Kamu Mohon di cek Kembali, mohon periksa kembali. atau hubungi TIM IT PKM Center');
                }
            }

            //untuk pengecekan pembatasan pembuatan pkm
            if ($idtipepkm == "1" || $idtipepkm == "4") {
                $statusjmlkel = AnggotaPKM::whereid_mahasiswa($id_ketua)
                    ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                    ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                    ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                    ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                    ->where('tahun_pkm.aktif', '=', 1)
                    ->where('tipe_pkm.id', '=', $idtipepkm)
                    ->count();

                if ($statusjmlkel > 4) {
                    return redirect('mhs/list-pkm')->with('createpkm', 'morecreated');
                }
            }

            $cekpkm = FilePKM::select('*')
                ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                ->where('anggota_pkm.jabatan', '=', 1)
                ->where('anggota_pkm.id_mahasiswa', '=', $id_ketua)
                ->where('tipe_pkm.id', '=', $idtipepkm)
                ->where('file_pkm.id_tahun_pkm', '=', $id_tahun_aktif)
                ->count();

            if ($cekpkm < 1) {
                DB::beginTransaction();
                try {
                    $pkm = new FilePKM();
                    $pkm->id_tahun_pkm = $id_tahun_aktif;
                    $pkm->id_skim_pkm = $idskimpkm;
                    $pkm->judul = $request->get('judul');
                    $pkm->keyword = $request->get('keyword');
                    //untuk penambahan abstrak jika pkm kt, gfk, atau sug
                    if ($idtipepkm == '2' || $idtipepkm == '4' || $idtipepkm == '3') {
                        $pkm->abstrak = $request->get('abstrak');
                        //untuk penambahan linkurl untuk gfk
                    }
                    if ($idtipepkm == '3') {
                        $pkm->linkurl = $request->get('linkurl');
                    }
                    if ($idtipepkm == '1') {
                        $pkm->dana_pkm = $request->get('danapkm');
                    }
                    $pkm->self = $request->get('self');
                    $pkm->durasi = $request->get('durasi');
                    $pkm->id_dosen = Crypt::decryptString($request->get('kodedosen'));
                    $pkm->status = "1";
                    $pkm->save();

                    $random = mt_rand(0, 9);
                    $kodenya = $random . '' . $pkm->id . '' . $idfakultas;
                    $kode = FilePKM::whereid($pkm->id)->first();
                    if ($idskimpkm == 1) {
                        $kode->kode_pkm = 'H' . $kodenya;
                    } elseif ($idskimpkm == 2) {
                        $kode->kode_pkm = 'E' . $kodenya;
                    } elseif ($idskimpkm == 3) {
                        $kode->kode_pkm = 'T' . $kodenya;
                    } elseif ($idskimpkm == 4) {
                        $kode->kode_pkm = 'K' . $kodenya;
                    } elseif ($idskimpkm == 5) {
                        $kode->kode_pkm = 'C' . $kodenya;
                    } elseif ($idskimpkm == 6) {
                        $kode->kode_pkm = 'M' . $kodenya;
                    } elseif ($idskimpkm == 7) {
                        $kode->kode_pkm = 'G' . $kodenya;
                    } elseif ($idskimpkm == 8) {
                        $kode->kode_pkm = 'A' . $kodenya;
                    } elseif ($idskimpkm == 9) {
                        $kode->kode_pkm = 'F' . $kodenya;
                    } elseif ($idskimpkm == 10) {
                        $kode->kode_pkm = 'S' . $kodenya;
                    } else {
                        $kode->kode_pkm = 'Z' . $kodenya;
                    }
                    $kode->save();

                    $detail = new DetailPKM();
                    $detail->id_file_pkm = $pkm->id;
                    $detail->save();

                    $nilai = new NilaiPKM();
                    $nilai->id_file_pkm = $pkm->id;
                    $nilai->save();

                    $dikti = new UserDikti();
                    $dikti->id_file_pkm = $pkm->id;
                    $dikti->save();


                    $pemilik = new AnggotaPKM();
                    $pemilik->id_file_pkm = $pkm->id;
                    $pemilik->id_mahasiswa = $id_ketua;
                    $pemilik->jabatan = "1";
                    $pemilik->acc_anggota = "Y";
                    $pemilik->save();

                    DB::commit();

                    return redirect('mhs/pkm/' . $pkm->id)->with('createpkm', 'success');
                } catch (\Exception $e) {
                    DB::rollback();
                }
            } else {
                return redirect('mhs/list-pkm')->with('createpkm', 'created');
            }
        }else{
            return redirect('mhs/list-pkm')->with('createpkm', 'created');
        }
    }

    public function TambahAnggota(Request $request)
    {
        try {
            $email = Crypt::decryptString($request->get('kode'));
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        } catch (\Exception $e) {
            abort(403, 'Gagal Memecah Kode Daftar');
        }

        $cekedit = $this->CekStatusEdit($id_file_pkm);
        if($cekedit == 1){
            $cekmilik = $this->CekPemilikPKM($id_file_pkm);
            if($cekmilik == 1){
                $cekmhs = (IdentitasMahasiswa::whereemail($email)->count() == 0) ? true : false;
                $jabatan = AnggotaPKM::whereid_file_pkm($id_file_pkm)->count();
                $pilihjabatan = $jabatan + 1;

                if (!$cekmhs) {
                    DB::beginTransaction();
                    try {
                        $idmhs = IdentitasMahasiswa::whereemail($email)->first()->id;

                        $tbhangt = new AnggotaPKM();
                        $tbhangt->id_file_pkm = $id_file_pkm;
                        $tbhangt->id_mahasiswa = $idmhs;
                        $tbhangt->jabatan = $pilihjabatan;
                        $tbhangt->save();

                        DB::commit();
                        return redirect()->back()->with('tbhanggota', 'success');
                    } catch (\Exception $e) {
                        DB::rollback();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $dbmhs = DBMHS::whereemail_mahasiswa($email)->first();

                        $usr = new User();
                        $usr->username = $dbmhs->nim_mahasiswa;
                        $usr->password = Hash::make($dbmhs->nim_mahasiswa);
                        $usr->level = "Mahasiswa";
                        $usr->save();

                        $mhs = new IdentitasMahasiswa();
                        $mhs->nim = $dbmhs->nim_mahasiswa;
                        $mhs->nama = $dbmhs->nama_mahasiswa;
                        $mhs->email = $dbmhs->email_mahasiswa;
                        $mhs->alamat = $request->get('alamat');
                        $mhs->jenis_kelamin = $dbmhs->jns_kel_mahasiswa;
                        $mhs->telepon = $request->get('telepon');
                        $mhs->backup_telepon = $request->get('backup_telepon');
                        $mhs->id_prodi = $dbmhs->id_prodi_mahasiswa;
                        $mhs->ukuranbaju = $request->get('ukuranbaju');
                        $mhs->tanggallahir = $dbmhs->tgllahir_mahasiswa;
                        $mhs->tempatlahir = $dbmhs->tmptlahir_mahasiswa;
                        $mhs->crypt_token = Crypt::encrypt($dbmhs->email_mahasiswa);
                        $mhs->id_user = $usr->id;
                        $mhs->save();

                        $angtpkm = new AnggotaPKM();
                        $angtpkm->id_file_pkm = $id_file_pkm;
                        $angtpkm->id_mahasiswa = $mhs->id;
                        $angtpkm->jabatan = $pilihjabatan;
                        $angtpkm->save();

                        DB::commit();
                        return redirect()->back()->with('tbhanggota', 'success');
                    } catch (\Exception $e) {
                        DB::rollback();
                    }
                }
            } else {
                //tempat jika bukan pemilik PKM
            }
        } else {
            // tempat jika status_edit tidak on
        }
    }

    public function HapusAnggota(Request $request)
    {
        $id_mahasiswa = Crypt::decryptString($request->get('kode_data'));
        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        
        $cekedit = $this->CekStatusEdit($id_file_pkm);
        if($cekedit == 1){
            $cekmilik = $this->CekPemilikPKM($id_file_pkm);
            if($cekmilik == 1){

                $hps = AnggotaPKM::whereid_mahasiswa($id_mahasiswa)->where('id_file_pkm', '=', $id_file_pkm)->first();
                $hps->delete();

                return redirect()->back()->with('hpsaggt', 'success');

            } else {
                //tempat jika bukan pemilik PKM
            }
        } else {
            // tempat jika status_edit tidak on
        }
    }

    public function UploadProposal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        DB::beginTransaction();
        try {
            $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
            $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
            $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
            $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
            $nim = Auth::user()->identitas_mahasiswa->nim;

            $file = $request->file('file');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = $fakultas . '-' . $skimpkm . '-' . $nim . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm;

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);


            $uppro = FilePKM::whereid($id_file_pkm)->first();
            $uppro->file_proposal = $nama_file;
            $uppro->time_proposal = date("d/m/Y H:m:s");
            $uppro->save();

            $destinasifile = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm . '/' . $nama_file;
            $fileData = File::get($destinasifile);

            try {
                $dir = '1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'; //id file drive proposal
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir1 = $contents->where('type', '=', 'dir')
                    ->where('filename', '=', $tahun_aktif)
                    ->first(); // There could be duplicate directory names!
                if (!$dir1) {
                    $coba = Storage::cloud()->makeDirectory('1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/' . $tahun_aktif);
                    $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                    $dir2 = $contents2->where('type', '=', 'dir')
                        ->where('filename', '=', $tahun_aktif)
                        ->first();
                    Storage::cloud()->put($dir2['path'] . '/' . $nama_file, $fileData);
                } else {
                    Storage::cloud()->put($dir1['path'] . '/' . $nama_file, $fileData);
                }
            } catch (\Exception $e) {
                $fail = new FailUp();
                $fail->tipe = "Proposal";
                $fail->namafile = $nama_file;
                $fail->dir = $destinasifile;
                $fail->tambahan = $tahun_aktif;
                $fail->save();
            }

            DB::commit();
            return redirect()->back()->with('upload', 'success');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function RevisiProposal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        DB::beginTransaction();
        try {
            $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
            $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
            $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
            $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
            $nim = Auth::user()->identitas_mahasiswa->nim;

            $file = $request->file('file');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = $fakultas . '-' . $skimpkm . '-' . $nim . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm;

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);


            $uppro = FilePKM::whereid($id_file_pkm)->first();
            $uppro->file_proposal = $nama_file;
            $uppro->time_proposal = date("d/m/Y H:m:s");
            $uppro->save();

            $destinasifile = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm . '/' . $nama_file;
            $fileData = File::get($destinasifile);

            try {
                $dir = '1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'; //id folder drive proposal
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir1 = $contents->where('type', '=', 'dir')
                    ->where('filename', '=', $tahun_aktif)
                    ->first(); // There could be duplicate directory names!
                if (!$dir1) {
                    $coba = Storage::cloud()->makeDirectory('1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/' . $tahun_aktif);
                    $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                    $dir2 = $contents2->where('type', '=', 'dir')
                        ->where('filename', '=', $tahun_aktif)
                        ->first();
                    Storage::cloud()->put($dir2['path'] . '/' . $nama_file, $fileData);
                } else {
                    Storage::cloud()->put($dir1['path'] . '/' . $nama_file, $fileData);
                }
            } catch (\Exception $e) {
                $fail = new FailUp();
                $fail->tipe = "Proposal";
                $fail->namafile = $nama_file;
                $fail->dir = $destinasifile;
                $fail->tambahan = $tahun_aktif;
                $fail->save();
            }

            DB::commit();
            return redirect()->back()->with('upload', 'success');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function UploadLapKemajuan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
        $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
        $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
        $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
        $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
        $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
        $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
        $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
        $nim = Auth::user()->identitas_mahasiswa->nim;

        $file = $request->file('file');
        $extensi = $file->getClientOriginalExtension();
        $pin = mt_rand(00, 999);
        $nama_file = $fakultas . '-' . $skimpkm . '-' . $nim . '-' . $pin . '.' . $extensi;
        $destinasi = storage_path() . '/files/' . $tahun_aktif . '/laporan kemajuan/' . $tipepkm;

        if (!File::isDirectory($destinasi)) {
            File::makeDirectory($destinasi, 0775, true);
        }

        $file->move($destinasi, $nama_file);

        $uppro = FilePKM::whereid($id_file_pkm)->first();
        $uppro->file_laporan_kemajuan = $nama_file;
        $uppro->time_laporan_kemajuan = date("d/m/Y H:m:s");
        $uppro->save();

        $destinasifile = storage_path() . '/files/' . $tahun_aktif . '/laporan kemajuan/' . $tipepkm . '/' . $nama_file;
        $fileData = File::get($destinasifile);

        try {
            $dir = '1hmyaGHPUZsmuWsnzVkELrNwN_dCXX_4N/'; //id folder drive lapoan kemajuan
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir1 = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $tahun_aktif)
                ->first(); // There could be duplicate directory names!
            if (!$dir1) {
                $coba = Storage::cloud()->makeDirectory('1hmyaGHPUZsmuWsnzVkELrNwN_dCXX_4N/' . $tahun_aktif);
                $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir2 = $contents2->where('type', '=', 'dir')
                    ->where('filename', '=', $tahun_aktif)
                    ->first();
                Storage::cloud()->put($dir2['path'] . '/' . $nama_file, $fileData);
            } else {
                Storage::cloud()->put($dir1['path'] . '/' . $nama_file, $fileData);
            }
        } catch (\Exception $e) {
            $fail = new FailUp();
            $fail->tipe = "Kemajuan";
            $fail->namafile = $nama_file;
            $fail->dir = $destinasifile;
            $fail->tambahan = $tahun_aktif;
            $fail->save();
        }

        return redirect()->back()->with('upload', 'success');
    }

    public function UploadLapAkhir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
        $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
        $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
        $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
        $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
        $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
        $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
        $nim = Auth::user()->identitas_mahasiswa->nim;

        $file = $request->file('file');
        $extensi = $file->getClientOriginalExtension();
        $pin = mt_rand(00, 999);
        $nama_file = $fakultas . '-' . $skimpkm . '-' . $nim . '-' . $pin . '.' . $extensi;
        $destinasi = storage_path() . '/files/' . $tahun_aktif . '/laporan akhir/' . $tipepkm;

        if (!File::isDirectory($destinasi)) {
            File::makeDirectory($destinasi, 0775, true);
        }

        $file->move($destinasi, $nama_file);

        $uppro = FilePKM::whereid($id_file_pkm)->first();
        $uppro->file_laporan_akhir = $nama_file;
        $uppro->time_laporan_akhir = date("d/m/Y H:m:s");
        $uppro->save();

        $destinasifile = storage_path() . '/files/' . $tahun_aktif . '/laporan akhir/' . $tipepkm . '/' . $nama_file;
        $fileData = File::get($destinasifile);

        try {
            $dir = '1DmqhGgvbXInDE8gJZZJR0cH7_L6zipor/'; //id folder drive lapoan akhir
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir1 = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $tahun_aktif)
                ->first(); // There could be duplicate directory names!
            if (!$dir1) {
                $coba = Storage::cloud()->makeDirectory('1DmqhGgvbXInDE8gJZZJR0cH7_L6zipor/' . $tahun_aktif);
                $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir2 = $contents2->where('type', '=', 'dir')
                    ->where('filename', '=', $tahun_aktif)
                    ->first();
                Storage::cloud()->put($dir2['path'] . '/' . $nama_file, $fileData);
            } else {
                Storage::cloud()->put($dir1['path'] . '/' . $nama_file, $fileData);
            }
        } catch (\Exception $e) {
            $fail = new FailUp();
            $fail->tipe = "Akhir";
            $fail->namafile = $nama_file;
            $fail->dir = $destinasifile;
            $fail->tambahan = $tahun_aktif;
            $fail->save();
        }

        return redirect()->back()->with('upload', 'success');
    }

    public function UploadPPT(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'file' => 'required|file|mimes:ppt|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
        $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
        $idprodi = Auth::user()->identitas_mahasiswa->id_prodi;
        $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
        $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
        $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
        $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
        $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
        $nim = Auth::user()->identitas_mahasiswa->nim;

        $file = $request->file('file');
        $extensi = $file->getClientOriginalExtension();
        $pin = mt_rand(00, 999);
        $nama_file = $fakultas . '-' . $skimpkm . '-' . $nim . '-' . $pin . '.' . $extensi;
        $destinasi = storage_path() . '/files/' . $tahun_aktif . '/file ppt/' . $tipepkm;

        if (!File::isDirectory($destinasi)) {
            File::makeDirectory($destinasi, 0775, true);
        }

        $file->move($destinasi, $nama_file);

        $uppro = UserDikti::whereid_file_pkm($id_file_pkm)->first();
        $uppro->file_ppt = $nama_file;
        $uppro->save();

        $destinasifile = storage_path() . '/files/' . $tahun_aktif . '/file ppt/' . $tipepkm . '/' . $nama_file;
        $fileData = File::get($destinasifile);

        try {
            $dir = '1QqdpTOykNws4OomZ8OBZEKl5fQRptbkF/'; //id folder drive file ppt
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir1 = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $tahun_aktif)
                ->first(); // There could be duplicate directory names!
            if (!$dir1) {
                $coba = Storage::cloud()->makeDirectory('1QqdpTOykNws4OomZ8OBZEKl5fQRptbkF/' . $tahun_aktif);
                $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir2 = $contents2->where('type', '=', 'dir')
                    ->where('filename', '=', $tahun_aktif)
                    ->first();
                Storage::cloud()->put($dir2['path'] . '/' . $nama_file, $fileData);
            } else {
                Storage::cloud()->put($dir1['path'] . '/' . $nama_file, $fileData);
            }
        } catch (\Exception $e) {
            $fail = new FailUp();
            $fail->tipe = "PPT";
            $fail->namafile = $nama_file;
            $fail->dir = $destinasifile;
            $fail->tambahan = $tahun_aktif;
            $fail->save();
        }

        return redirect()->back()->with('upload', 'success');
    }

    //funsi untuk mengedit data pkm
    public function EditPKM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => 'required',
            'judul' => 'required',
            'kodedosen' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }
        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
        
        $cekedit = $this->CekStatusEdit($id_file_pkm);
        if($cekedit == 1){
            $cekmilik = $this->CekPemilikPKM($id_file_pkm);
            if($cekmilik == 1){
                DB::beginTransaction();
                try {
                    $id_skim_pkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
                    $id_tipe_pkm = SkimPKM::whereid($id_skim_pkm)->first()->id_tipe_pkm;

                    $jml_kata = str_word_count($request->get('judul'));
                    if ($jml_kata > 20) {
                        abort(403, 'Judul Lebih dari 20 Kata, mohon periksa kembali. atau hubungi TIM IT PKM Center');
                    }
                    if ($id_tipe_pkm == 1) {
                        if ($request->get('danapkm') > 12500000 && $request->get('danapkm') < 5000000) {
                            abort(403, 'Dana Kamu Mohon di cek Kembali, mohon periksa kembali. atau hubungi TIM IT PKM Center');
                        }
                    }

                    $edtpkm = FilePKM::whereid($id_file_pkm)->first();
                    $edtpkm->judul = $request->get('judul');
                    if ($id_tipe_pkm == '1') {
                        $edtpkm->dana_pkm = $request->get('dana_pkm');
                        $edtpkm->durasi = $request->get('durasi');
                        //untuk dana di Tipe 5 Bidang
                    }
                    $edtpkm->keyword = $request->get('keywords');
                    //untuk penambahan abstrak jika pkm kt, gfk, atau sug
                    if ($id_tipe_pkm == '2' || $id_tipe_pkm == '4' || $id_tipe_pkm == '3') {
                        $edtpkm->abstrak = $request->get('abstrak');
                        //untuk penambahan linkurl untuk gfk
                    }
                    if ($id_tipe_pkm == '3') {
                        $edtpkm->linkurl = $request->get('linkurl');
                    }

                    $edtpkm->id_dosen = Crypt::decryptString($request->get('kodedosen'));
                    $edtpkm->save();
                    DB::commit();
                    return redirect()->back()->with('editpkm', 'success');
                } catch (\Exception $e) {
                    DB::rollback();
                }
            } else {
                //tempat jika bukan pemilik PKM
            }
        } else {
            // tempat jika status_edit tidak on
        }
    }

    public function HapusPKM(Request $request)
    {
        DB::beginTransaction();
        try {
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            $file_pkm = FilePKM::whereid($id_file_pkm)->first();
            $idtipepkm = SkimPKM::whereid($file_pkm->id_skim_pkm)->first()->id_tipe_pkm;
            $idtahun = $file_pkm->id_tahun_pkm;
            $tahun_aktif = TahunPKM::whereid($idtahun)->first()->tahun;
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;

            if ($file_pkm->file_proposal != "") {
                $nama_proposal = $file_pkm->file_proposal;
                $fileproposal = storage_path() . '/files/' . $tahun_aktif . '/proposal/' . $tipepkm . '/' . $nama_proposal;
                File::delete($fileproposal);
            }
            if ($file_pkm->file_laporan_kemajuan != "") {
                $nama_laporan_kemajuan = $file_pkm->file_laporan_kemajuan;
                $filelapkemajuan = storage_path() . '/files/' . $tahun_aktif . '/laporan kemajuan/' . $tipepkm . '/' . $nama_laporan_kemajuan;
                File::delete($filelapkemajuan);
            }
            if ($file_pkm->file_laporan_akhir != "") {
                $nama_laporan_akhir = $file_pkm->file_laporan_akhir;
                $filelapakhir = storage_path() . '/files/' . $tahun_aktif . '/laporan kemajuan/' . $tipepkm . '/' . $nama_laporan_akhir;
                File::delete($filelapakhir);
            }

            $detailpkm = DetailPKM::whereid_file_pkm($id_file_pkm)->delete();

            $uangpkm = UangPKM::whereid_file_pkm($id_file_pkm)->delete();

            $dikti = UserDikti::whereid_file_pkm($id_file_pkm)->delete();

            $nilai = NilaiPKM::whereid_file_pkm($id_file_pkm)->delete();

            $anggota = AnggotaPKM::whereid_file_pkm($id_file_pkm)->delete();

            $filepkm = FilePKM::whereid($id_file_pkm)->delete();

            DB::commit();

            return redirect('mhs/list-pkm')->with('hapuspkm', 'success');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohAnggotaTambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $nim = Auth::user()->username;
            $id_mahasiswa = IdentitasMahasiswa::wherenim($nim)->first()->id;

            $a = new CarDoh();
            $a->tipe_cardoh = "1";
            $a->id_tahun_pkm = $id_tahun_aktif;
            $a->id_mahasiswa = $id_mahasiswa;
            $a->jml_anggota = $request->get('jml_anggota');
            $a->cardoh_skim = $request->get('skim');
            $a->kebutuhan = $request->get('kebutuhan');
            $a->save();

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohAnggotaEdit(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('kode'));
            $a = CarDoh::whereid($id)->firstOrFail();
            $a->jml_anggota = $request->get('jml_anggota');
            $a->cardoh_skim = $request->get('skim');
            $a->kebutuhan = $request->get('kebutuhan');
            $a->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohHapus(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('kode'));
            $a = CarDoh::whereid($id)->firstOrFail();
            $a->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohIdeTambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $nim = Auth::user()->username;
            $id_mahasiswa = IdentitasMahasiswa::wherenim($nim)->first()->id;

            $a = new CarDoh();
            $a->tipe_cardoh = "2";
            $a->id_tahun_pkm = $id_tahun_aktif;
            $a->id_mahasiswa = $id_mahasiswa;
            $a->cardoh_skim = $request->get('skim');
            $a->ide_kasar = $request->get('kebutuhan');
            $a->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohIdeEdit(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('kode'));
            $a = CarDoh::whereid($id)->firstOrFail();
            $a->cardoh_skim = $request->get('skim');
            $a->ide_kasar = $request->get('kebutuhan');
            $a->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohKelompokTambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $nim = Auth::user()->username;
            $id_mahasiswa = IdentitasMahasiswa::wherenim($nim)->first()->id;

            $a = new CarDoh();
            $a->tipe_cardoh = "3";
            $a->id_tahun_pkm = $id_tahun_aktif;
            $a->id_mahasiswa = $id_mahasiswa;
            $a->cardoh_skill = $request->get('kebutuhan');
            $a->save();

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function CarDohKelompokEdit(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('kode'));
            $a = CarDoh::whereid($id)->firstOrFail();
            $a->cardoh_skill = $request->get('kebutuhan');
            $a->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function AccAnggota(Request $request)
    {
        DB::beginTransaction();
        try {
            $idmhs = Crypt::decryptString($request->get('kode'));
            $idfilepkm = Crypt::decryptString($request->get('tipekode'));

            $acc = AnggotaPKM::whereid_file_pkm($idfilepkm)->where('id_mahasiswa', '=', $idmhs)->firstOrFail();
            $acc->acc_anggota = "Y";
            $acc->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function ConfirmPKM(Request $request)
    {
        DB::beginTransaction();
        try {
            $idfilepkm = Crypt::decryptString($request->get('kode'));

            $acc = FilePKM::whereid($idfilepkm)->firstOrFail();
            $acc->confirm_up = Crypt::decryptString($request->get('isi'));
            $acc->save();
            
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }


    public function CekStatusEdit($id_file_pkm)
    {
        $filepkm = FilePKM::whereid($id_file_pkm)->first();
        $idtipepkm = SkimPKM::whereid($filepkm->id_skim_pkm)->first()->id_tipe_pkm;
        $cekedit = TipePKM::whereid($idtipepkm)->first()->status_edit;

        return $cekedit;
    }

    public function CekPemilikPKM($id)
    {
        $iduser = Auth::user()->identitas_mahasiswa->id;
        $cekpemilikpkm = (AnggotaPKM::whereid_mahasiswa($iduser)
                ->join('file_pkm', 'file_pkm.id', 'anggota_pkm.id_file_pkm')
                ->where('file_pkm.id', '=', $id)
                ->count() == 0) ? true : false;
        if (!$cekpemilikpkm) {
            return 1;
        }else{
            return 0;
        }
    }
    
    public function TambahToko(Request $request)
    {
        $nama = $request->get('nama');
        $alamat = $request->get('alamat');

        if($nama != "" && $alamat != ""){
            $tambah = new Toko();
            $tambah->nama_toko = $nama;
            $tambah->alamat_toko = $alamat;
            $tambah->save();
            
            return 1;
        }else{
            return "";
        }
    }
    
    public function TambahNota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filenota' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($request->get('encrypt'));
            
            $pkm = FilePKM::select('*')
                ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                ->where('file_pkm.id', '=', $id)
                ->first();
            
            $t = new Nota();
            $t->id_file_pkm = $id;
            $t->tgl_nota = $request->get('tgl_nota');
            $t->id_toko = $request->get('id_toko');
            $t->save();

            $file = $request->file('filenota');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = 'Nota'. $t->id . '-' . $id . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/nota/';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);

            $f = Nota::whereid($t->id)->first();
            $f->file_nota = $nama_file;
            $f->save();

            DB::commit();
            return redirect('mhs/dana/nota/lihat?id=' . $t->id)->with('nota', 'create');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function GantiNota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filenota' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }
        
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('encrypt'));
            $filelama = Nota::whereid($id)->first()->file_nota;
            $destinasi = 'storage/files/nota/' . $filelama;
            File::delete($destinasi);

            $idpkm = Nota::whereid($id)->first()->id_file_pkm;
            $file = $request->file('filenota');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = 'Nota'. $id . '-' . $idpkm . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/nota/';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);

            $f = Nota::whereid($id)->first();
            $f->file_nota = $nama_file;
            $f->save();
            
            DB::commit();
            return redirect('mhs/dana/nota/lihat?id=' . $id)->with('nota', 'update');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function EditNota(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->get('encrypt'));
            $t = Nota::whereid($id)->first();
            $t->tgl_nota = $request->get('tgl_nota');
            $t->id_toko = $request->get('id_toko');
            $t->save();
            
            DB::commit();
            return redirect('mhs/dana/nota/lihat?id=' . $id)->with('nota', 'update');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function HapusNota(Request $request)
    {
        $id = Crypt::decryptString($request->get('encrypt'));

        $filelama = Nota::whereid($id)->first()->file_nota;
        $destinasi = 'storage/files/nota/' . $filelama;
        File::delete($destinasi);

        UangPKM::whereid_nota($id)->delete();
        Nota::whereid($id)->delete();
    }

    public function TambahTransaksi(Request $request)
    {
        $id = Crypt::decryptString($request->get('encrypt'));

        $t = new UangPKM();
        $t->id_kategori = $request->get('kategori');
        $t->id_nota = $id;
        $t->nama_pembelian = $request->get('nama');
        $t->volume = $request->get('volume');
        $t->nominal = $request->get('nominal');
        $t->ppn = $request->get('ppn');
        $t->pph21 = $request->get('pph21');
        $t->pph22 = $request->get('pph22');
        $t->pph23 = $request->get('pph23');
        $t->pph26 = $request->get('pph26');
        $t->save();

        if($t){
            return 1;
        }else{
            return"";
        }
    }

    public function EditTransaksi(Request $request)
    {
        $id = $request->get('idtr');

        $t = UangPKM::whereid($id)->first();
        $t->id_kategori = $request->get('kategori');
        $t->nama_pembelian = $request->get('nama');
        $t->volume = $request->get('volume');
        $t->nominal = $request->get('nominal');
        $t->ppn = $request->get('ppn');
        $t->pph21 = $request->get('pph21');
        $t->pph22 = $request->get('pph22');
        $t->pph23 = $request->get('pph23');
        $t->pph26 = $request->get('pph26');
        $t->cek_pembelian = "";
        $t->save();

        if($t){
            return 1;
        }else{
            return"";
        }
    }

    public function HapusTransaksi(Request $request)
    {
        $id = $request->get('idtr');

        $t = UangPKM::whereid($id)->delete();

        if($t){
            return 1;
        }else{
            return"";
        }
    }
}
