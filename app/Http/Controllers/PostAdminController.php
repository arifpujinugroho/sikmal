<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Operator;
use Hash;
use DB;
use File;
use Crypt;
use Redirect;
use Validator;
use Storage;
use App\Model\Nota;
use App\Model\UangPKM;
use App\Model\Kategori;
use App\Model\SkimPKM;
use App\Model\FilePKM;
use App\Model\AnggotaPKM;
use App\Model\TipePKM;
use App\Model\TahunPKM;
use App\Model\Prodi;
use App\Model\Toko;
use App\Model\Fakultas;
use App\Model\Download;
use App\Model\CallCenter;
use App\Model\UserDikti;
use App\Model\NilaiPKM;
use App\Model\IdentitasMahasiswa;

class PostAdminController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Admin
    public function __construct()
    {
        //$this->middleware('admin');
        $this->middleware(['admin', 'sso']);
    }
    ////////////////////////////////////////////////
    //
    public function TambahOperator(Request $request)
    {
        $cekuser = (User::whereusername($request->get('un'))->count() == 0) ? true : false;
        if ($cekuser) {

            $cekopt = (Operator::whereid_fakultas($request->get('fak'))->count() == 0) ? true : false;
            if ($cekopt) {
                DB::transaction(function () {
                    $usr = new User();
                    $usr->username = $request->get('un');
                    $usr->password = Hash::make($request->get('un'));
                    $usr->level = "Operator";
                    $usr->save();

                    $opt = new Operator();
                    $opt->id_user = $usr->id;
                    $opt->nama_operator = $request->get('nama');
                    $opt->id_fakultas = $request->get('fak');
                    $opt->opt_status = Crypt::encrypt($usr->username);
                    $opt->save();
                });
                return "success";
            } else {
                return "created";
            }
        } else {
            return "error";
        }
    }

    public function ResetOpt(Request $request)
    {
        $user = User::whereid($request->get('id'))->first();

        $usr = User::whereid($request->get('id'))->first();
        $usr->password = Hash::make($user->username);
        $usr->save();

        $opt = Operator::whereid_user($request->get('id'))->first();
        $opt->opt_status = Crypt::encrypt($user->username);
        $opt->save();
    }

    public function HapusOpt(Request $request)
    {
        $opt = Operator::whereid_user($request->get('id'))->delete();
        $usr = User::whereid($request->get('id'))->delete();
    }
    public function UploadProposal(Request $request)
    {
        $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
        $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
        $idprodi = IdentitasMahasiswa::whereid($request->get('id_mhs'))->first()->id_prodi;
        $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
        $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
        $id_file_pkm = Crypt::decrypt($request->get('kode_token'));
        $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
        $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
        $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
        $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
        $nim = IdentitasMahasiswa::whereid($request->get('id_mhs'))->first()->nim;

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
        $uppro->time_proposal = date("d/m/Y/ H:m:s");
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

        return redirect()->back()->with('upload', 'success');
    }

    public function EditDataMahasiswa(Request $request)
    {
        try {
            $id = ($request->get('kode'));

            $mhs = IdentitasMahasiswa::whereid($id)->firstOrFail();
            $mhs->alamat = $request->get('alamat');
            $mhs->telepon = $request->get('telepon');
            $mhs->backup_telepon = $request->get('backup_telepon');
            $mhs->ukuranbaju = $request->get('ukuranbaju');
            $mhs->save();

            return redirect()->back()->with('data', 'success');
        } catch (\Exception $e) {
            abort(403, 'Gagal memecahkan kode');
        }
    }
    public function EditSimbel(Request $request)
    {
        try {
            $id = $request->get('file');
            $nim = $request->get('nim');

            $file = FilePKM::whereid($id)->firstOrFail();
            $file->status = $request->get('status');
            $file->save();

            $dikti = IdentitasMahasiswa::wherenim($nim)->firstOrFail();
            $dikti->pass_simbel = $request->get('password');
            $dikti->save();
            
            $dana = UserDikti::whereid_file_pkm($id)->firstOrFail();
            $dana->dana_dikti = $request->get('dana_dikti');
            $dana->save();

            return 1;
        } catch (\Exception $e) {
            return '';
        }
    }

    public function HapusReviewer(Request $request)
    {
        try {
            $dsn = User::whereid($request->get('id'))->first();
            $dsn->level = 'Dosen';
            $dsn->save();
        } catch (\Exception $e) {
            abort(403, 'Gagal Hapus Reviewer');
        }
    }

    public function TambahReviewer(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = $request->get('email');
            $cekuser = User::whereusername($email)->count();
            if ($cekuser == 0) {
                $usr = new User();
                $usr->username = $email;
                $usr->password = Hash::make($email);
                $usr->level = "Reviewer";
                $usr->save();
            } else {
                $user = User::whereusername($email)->first();
                $user->level = "Reviewer";
                $user->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function InputReviewer(Request $request)
    {
        //DB::beginTransaction();
        //try {
            $id = $request->get('id');
            $c = NilaiPKM::whereid_file_pkm($id)->first();
            $c->penilai_proposal = $request->get('email');
            $c->save();
            
         //   DB::commit();
            return redirect()->back()->with('data', 'success');
        //} catch (\Exception $e) {
        //    DB::rollback();
        //}
    }
    
    public function StatusPKM(Request $request)
    {
        $id = $request->get('id');
        $aktif = $request->get('aktif');
        $jenis = $request->get('jenis');

        if($aktif == "on"){
            $status = "0";
        }else{
            $status = "1";
        }

        if($jenis == "tambah"){
            $s = TipePKM::whereid($id)->first();
            $s->status_tambah = $status;
            $s->save();
        }elseif ($jenis == "edit") {
            $s = TipePKM::whereid($id)->first();
            $s->status_edit = $status;
            $s->save();
        }elseif ($jenis == "hapus") {
            $s = TipePKM::whereid($id)->first();
            $s->status_hapus = $status;
            $s->save();
        }elseif ($jenis == "proposal") {
            $s = TipePKM::whereid($id)->first();
            $s->status_upload = $status;
            $s->save();
        }elseif ($jenis == "kemajuan") {
            $s = TipePKM::whereid($id)->first();
            $s->status_kemajuan = $status;
            $s->save();
        }elseif ($jenis == "akhir") {
            $s = TipePKM::whereid($id)->first();
            $s->status_akhir = $status;
            $s->save();
        }
    }

    public function TahunPKM(Request $request)
    {
        $id = $request->get('id');
        
        $ison = TahunPKM::whereaktif(1)->first();
        $ison->aktif = 0;
        $ison->save();

        $thn = TahunPKM::whereid($id)->first();
        $thn->aktif = 1;
        $thn->save();
    }
    
    public function TambahTahunPKM(Request $request)
    {
        $thn = $request->get('thn');
        $ket = $request->get('ket');

        if($thn != "" && $ket != ""){
            $t = is_numeric($thn);
            if($t){
                $p = strlen($thn);
                if($p == 4){
                    $tambah = new TahunPKM();
                    $tambah->tahun = $thn;
                    $tambah->keterangan = $ket;
                    $tambah->save();
                    
                    return 1;
                }else{
                    return "";
                }
            }else{
                return "";
            }
        }else{
            return "";
        }
    }

    //untuk menambah pkm
    public function InputCustom(Request $request)
    {
        DB::beginTransaction();
            try {
            $email = Crypt::decryptString($request->get('kode'));
            $id_ketua = IdentitasMahasiswa::whereemail($email)->first()->id;
            $nim = IdentitasMahasiswa::whereemail($email)->first()->nim;
            $idtipepkm = Crypt::decryptString($request->get('tipepkm'));
            $idskimpkm = $request->get('skimpkm');
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
            $idprodi = IdentitasMahasiswa::whereemail($email)->first()->id_prodi;
            $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
                $pkm = new FilePKM();
                $pkm->id_tahun_pkm = $request->get('tahunpkm');
                $pkm->id_skim_pkm = $request->get('skimpkm');
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
                $pkm->status = $request->get('status');
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

                return redirect('admin/pkm/' . $pkm->id)->with('createpkm', 'success');
            } catch (\Exception $e) {
                DB::rollback();
            }
    }

    

    public function TambahAnggota(Request $request)
    {
        try {
            $email = Crypt::decryptString($request->get('kode'));
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            //$peran = Crypt::decryptString($request->get('peran'));
        } catch (\Exception $e) {
            abort(403, 'Gagal Memecah Kode Daftar');
        }
        $cekmhs = (IdentitasMahasiswa::whereemail($email)->count() == 0) ? true : false;
        $jabatan = AnggotaPKM::whereid_file_pkm($id_file_pkm)->count();
        $pilihjabatan = $jabatan + 1;

        if (!$cekmhs) {
                $idmhs = IdentitasMahasiswa::whereemail($email)->first()->id;

                $tbhangt = new AnggotaPKM();
                $tbhangt->id_file_pkm = $id_file_pkm;
                $tbhangt->id_mahasiswa = $idmhs;
                $tbhangt->jabatan = $pilihjabatan;
                $tbhangt->save();

                
                return redirect()->back()->with('tbhanggota', 'success');
        } else {
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

                return redirect()->back()->with('tbhanggota', 'success');
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

            return redirect('admin')->with('hapuspkm', 'success');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function HapusAnggota(Request $request)
    {
        $id_mahasiswa = Crypt::decryptString($request->get('kode_data'));
        $id_file_pkm = Crypt::decryptString($request->get('kode_token'));

        $hps = AnggotaPKM::whereid_mahasiswa($id_mahasiswa)->where('id_file_pkm', '=', $id_file_pkm)->first();
        $hps->delete();

        return redirect()->back()->with('hpsaggt', 'success');
    }

    public function UploadProposalCustom(Request $request)
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
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            $idketua = AnggotaPKM::whereid_file_pkm($id_file_pkm)->where('jabatan','=','Ketua')->first();
            $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $idprodi = IdentitasMahasiswa::whereid($idketua)->first()->id_prodi;
            $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
            $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
            $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
            $nim = IdentitasMahasiswa::whereid($idketua)->first()->nim;

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
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
            $idketua = AnggotaPKM::whereid_file_pkm($id_file_pkm)->where('jabatan','=','Ketua')->first();
            $tahun_aktif = TahunPKM::whereaktif(1)->first()->tahun;
            $id_tahun_aktif = TahunPKM::whereaktif(1)->first()->id;
            $idprodi = IdentitasMahasiswa::whereid($idketua)->first()->id_prodi;
            $idfakultas = Prodi::whereid($idprodi)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $idskimpkm = FilePKM::whereid($id_file_pkm)->first()->id_skim_pkm;
            $idtipepkm = SkimPKM::whereid($idskimpkm)->first()->id_tipe_pkm;
            $skimpkm = SkimPKM::whereid($idskimpkm)->first()->skim_singkat;
            $tipepkm = TipePKM::whereid($idtipepkm)->first()->tipe;
            $nim = IdentitasMahasiswa::whereid($idketua)->first()->nim;

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

        DB::beginTransaction();
        try {
            $id_file_pkm = Crypt::decryptString($request->get('kode_token'));
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
            $edtpkm->id_tahun_pkm = $request->get('tahun');
            $edtpkm->id_skim_pkm = $request->get('skim');
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
    
    public function TambahLink(Request $request)
    {
        $jdl = $request->get('jdl');
        $link = $request->get('link');

        if($jdl != "" && $link != ""){
            $tambah = new Download();
            $tambah->judul = $jdl;
            $tambah->link_file = $link;
            $tambah->save();
            
            return 1;
        }else{
            return "";
        }
    }

    public function EditLink(Request $request)
    {
        $id = $request->get('id');
        $jdl = $request->get('jdl');
        $link = $request->get('link');

        if($jdl != "" && $link != "" && $id != ""){
            $edit = Download::whereid($id)->first();
            $edit->judul = $jdl;
            $edit->link_file = $link;
            $edit->save();
            
            return 1;
        }else{
            return "";
        }
    }

    public function HapusLink(Request $request)
    {
        Download::whereid($request->get('id'))->delete();
    }
    
    public function TambahToko(Request $request)
    {
        $nama = $request->get('nama');
        $alamat = $request->get('alamat');
        $verify = $request->get('verify');

        if($nama != "" && $alamat != "" && $verify != ""){
            $tambah = new Toko();
            $tambah->nama_toko = $nama;
            $tambah->alamat_toko = $alamat;
            $tambah->verify_toko = $verify;
            $tambah->save();
            
            return 1;
        }else{
            return "";
        }
    }

    public function EditToko(Request $request)
    {
        $id = $request->get('id');
        $nama = $request->get('nama');
        $alamat = $request->get('alamat');
        $verify = $request->get('verify');

        if($nama != "" && $alamat != "" && $verify != "" && $id != ""){
            $edit = Toko::whereid($id)->first();
            $edit->nama_toko = $nama;
            $edit->alamat_toko = $alamat;
            $edit->verify_toko = $verify;
            $edit->save();
            
            return 1;
        }else{
            return "";
        }
    }

    public function HapusToko(Request $request)
    {
        Toko::whereid($request->get('id'))->delete();
    }

    public function UploadSurat(Request $request)
    {
        if($request->get('tipe') == 'npwp'){
            $id = $request->get('id');

            $file = $request->file('file');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = 'NPWP'.$id.'-'.$pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/surat';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);


            $uppro = Toko::whereid($request->get('id'))->first();
            $uppro->npwp_toko = $nama_file;
            $uppro->save();

        }else{
            $id = $request->get('id');

            $file = $request->file('file');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = 'SIUP'.$id.'-'.$pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/surat';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $file->move($destinasi, $nama_file);


            $uppro = Toko::whereid($request->get('id'))->first();
            $uppro->siup_toko = $nama_file;
            $uppro->save();
        }
    }
    

    public function HapusAccTransaksi(Request $request)
    {
        $id = $request->get('idtr');

        $t = UangPKM::whereid($id)->first();
        $t->cek_pembelian = "";
        $t->save();

        if($t){
            return 1;
        }else{
            return"";
        }
    }

    public function AccTransaksi(Request $request)
    {
        $id = $request->get('idtr');

        $t = UangPKM::whereid($id)->first();
        $t->cek_pembelian = 1;
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
        $t->cek_pembelian = $request->get('tolak');
        $t->komentar_pembelian = $request->get('komentar');
        $t->save();

        if($t){
            return 1;
        }else{
            return"";
        }
    }
}
