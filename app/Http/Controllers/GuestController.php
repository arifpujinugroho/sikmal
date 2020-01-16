<?php

namespace App\Http\Controllers;

use App\Providers\DOCXTemplate;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use File;
use Hash;
use Auth;
use Mail;
use Crypt;
use Storage;
use Response;
use App\User;
use Redirect;
use App\Model\FailUp;
use App\Model\Prodi;
use App\Model\DBMHS;
use App\Model\TipePKM;
use App\Model\Fakultas;
use App\Model\IdentitasMahasiswa;

class GuestController extends Controller
{
    ///// ------------------GET------------------ /////

    //untuk fungsi tampilan utama atau homepage
    public function Front()
    {
        $tipeaktif = TipePKM::all();
        if (Auth::check()) {
            $leveluser = Auth::user()->level;

            if ($leveluser == 'SuperAdmin') {
                return Redirect::to('/admin');
            } elseif ($leveluser == 'PerekapMahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Mahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Operator') {
                return Redirect::to('/opt');
            }
        } else {
            return view('guest.front', compact('tipeaktif'));
        }
    }

    //untuk fungsi tampilan resgistrasi
    public function Registrasi()
    {
        $fakultas = Fakultas::all();
        if (Auth::check()) {
            $leveluser = Auth::user()->level;

            if ($leveluser == 'SuperAdmin') {
                return Redirect::to('/admin');
            } elseif ($leveluser == 'PerekapMahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Mahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Operator') {
                return Redirect::to('/opt');
            }
        } else {
            return view('guest.register', compact('fakultas'));
        }
    }

    //untuk mendapatkan semua data di tabel fakultas
    public function AllFakultas()
    {
        $fakultas = Fakultas::all();

        return $fakultas;
    }

    //untuk mendapatkan semua data di tabel prodi
    public function AllProdi()
    {
        $prodi = Prodi::all();

        return $prodi;
    }

    //untuk mendapatkan Prodi berdasarkan ID Fakultas
    public function Prodi(Request $request)
    {
        $fakultas = $request->get('fakultas');
        $prodi = Prodi::where('id_fakultas', '=', $fakultas)->get();
        return $prodi;
    }

    public function CekMhs(Request $request)
    {
        $email = $request->get('email');
        $mahasiswa = IdentitasMahasiswa::whereemail($email)->first();
        if (is_object($mahasiswa)) {
            if ($mahasiswa->kelengkapan == "Y") {
                // ini jika sudah tercreate akun/atau sudah pernah buat akun (kelengkapan sudah lengkap)
                return 1;
            } else {
                $mhs = IdentitasMahasiswa::select('nama_prodi', 'jenjang_prodi', 'kelengkapan', 'jenis_kelamin', 'nama', 'alamat', 'telepon', 'backup_telepon', 'tanggallahir', 'tempatlahir', 'ukuranbaju')
                    ->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')
                    ->where('email', '=', $email)->first();

                $kodenya = Crypt::encryptString($email);

                $response = new \stdClass();
                $response->isi = $mhs;
                $response->kode = $kodenya;
                return Response::json($response);
            }
        } else {
            $cekbase = DBMHS::whereemail_mahasiswa($email)->first();
            if (is_object($cekbase)) {
                $retval = DBMHS::select('nama_mahasiswa', 'nama_prodi', 'jenjang_prodi')
                    ->join('arifpujin_pkm_v3.prodi', 'prodi.id', '=', 'db_mahasiswa.id_prodi_mahasiswa') ///ini harus disesuaikan
                    ->where('email_mahasiswa', '=', $email)->first();

                $kodenya = Crypt::encryptString($email);

                $response = new \stdClass();
                $response->isi = $retval;
                $response->kode = $kodenya;
                return Response::json($response);
            } else {
                $response = new \stdClass();
                $response->en = "empty";
                return Response::json($response);
            }
        }
    }

    public function Verify()
    {
        if (empty(request('token'))) {
            // jika token tidak tersedia
            return redirect('/');
        }
        // decrypt email
        $decryptedEmail = Crypt::decrypt(request('token'));
        // cari data email email
        $mhs = IdentitasMahasiswa::whereEmail($decryptedEmail)->count();
        //
        if ($mhs > 0) {
            $cek = IdentitasMahasiswa::whereEmail($decryptedEmail)->first();
            $user = User::whereid($cek->id_user)->first();
            $user->email_verified_at = new \DateTime();
            $user->save();
            // autologin
            Auth::loginUsingId($user->id);
            return redirect('/');
        } else {
            return redirect('/')->with('login', 'notverified');
        }
    }

    public function DownFileDrive(Request $request)
    {
        //untuk download lewat drive
        $rawData = Storage::cloud()->get($request->get('path'));
        return response($rawData, 200)
            ->header('ContentType', $request->get('type'))
            ->header('Content-Disposition', "attachment; filename=" . $request->get('name') . "");
    }




    ///// ------------------POST------------------ //////

    public function Login(Request $request)
    {

        if (Auth::attempt(['username' => $request->get('username'), 'password' => $request->get('password')])) {
            $user = User::find(Auth::id());
            //cek password harus enskripsi lagi atau tidak
            if (Hash::needsRehash($user->password)) {
                $password = Hash::make($request->get('password'));
                $user->password = $password;
                $user->save();
            }

            /* Di Disable karena diutamakan menggunakan SSO buat keamanan
            if ($user->level == 'SuperAdmin') {
                //return Redirect::to('/admin');
                Auth::logout();
            } elseif ($user->level == 'PerekapMahasiswa') {
                //return Redirect::to('/mhs')
            } elseif ($user->level == 'Mahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($user->level == 'Operator') {
                return Redirect::to('/opt');
            }
            */

            if ($user->level == 'Operator') {
                return Redirect::to('/opt');
            } else {
                return Redirect::to('/');
                //return redirect()->route('logout');
            }
        } else {
            return Redirect::to('/')->with('login', 'error');
        }
    }

    public function Daftar(Request $request)
    {

        try {
            $email = Crypt::decryptString($request->get('kode'));
        } catch (\Exception $e) {
            abort(403, 'Gagal Memecah Kode Daftar');
        }

        //cek apakah data mahasiswa sudah ada atau belum
        $is_data_available = (IdentitasMahasiswa::whereemail($email)->count() == 0) ? true : false;

        // jika belum ada buat user dan identitas baru, jika sudah adabuat user dan update data mahasiswa
        if ($is_data_available) {
            $dbmhs = DBMHS::whereemail_mahasiswa($email)->first();

            $idfakultas = Prodi::whereid($dbmhs->id_prodi_mahasiswa)->first()->id_fakultas;
            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
            $nim = $dbmhs->nim_mahasiswa;
            $file = $request->file('pasfoto');
            $extensi = $file->getClientOriginalExtension();
            $pin = mt_rand(00, 999);
            $nama_file = $fakultas . '-' . $nim . '-' . $pin . '.' . $extensi;
            $destinasi = storage_path() . '/files/pasfoto/';

            if (!File::isDirectory($destinasi)) {
                File::makeDirectory($destinasi, 0775, true);
            }

            $user = new User();
            $user->username = $nim;
            $user->password = Hash::make($request->get('password'));
            $user->level = "Mahasiswa";
            $user->save();

            $mhs = new IdentitasMahasiswa();
            $mhs->nim = $nim;
            $mhs->email = $dbmhs->email_mahasiswa;
            $mhs->nama = $dbmhs->nama_mahasiswa;
            $mhs->id_prodi = $dbmhs->id_prodi_mahasiswa;
            $mhs->alamat = $request->get('alamat');
            $mhs->jenis_kelamin = $dbmhs->jns_kel_mahasiswa;
            $mhs->telepon = $request->get('telepon');
            $mhs->backup_telepon = $request->get('backup_telepon');
            $mhs->crypt_token = Crypt::encrypt($request->get('password'));
            $mhs->tempatlahir = $request->get('tempatlahir');
            $mhs->tanggallahir = $request->get('tanggallahir');
            $mhs->pasfoto = $nama_file;
            $mhs->ukuranbaju = $request->get('ukuranbaju');
            $mhs->kelengkapan = "Y"; //buat menentukan sudah buat akun atau belum
            $mhs->id_user = $user->id;
            $mhs->save();

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
            try {
                Mail::to($mhs->email)->send(new VerifyEmail($mhs));
            } catch (\Exception $d) {
                abort(403, 'Gagal Mengirim Email UNY Student, Hubungi PKM Center Fakultas Anda Segera!!');
            }

            return Redirect('/')->with('register', 'success');

            //jika sudah ada di identitas sudah ada dan belum ada di akun (pernah jadi Anggota)
        } else {
            $cekmhs = (IdentitasMahasiswa::whereemail($email)->where('kelengkapan', '=', 'Y')->count() == 0) ? true : false;
            if ($cekmhs) {
                $dbmhs = DBMHS::whereemail_mahasiswa($email)->first();

                $idfakultas = Prodi::whereid($dbmhs->id_prodi_mahasiswa)->first()->id_fakultas;
                $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
                $nim = $dbmhs->nim_mahasiswa;
                $file = $request->file('pasfoto');
                $extensi = $file->getClientOriginalExtension();
                $pin = mt_rand(00, 999);
                $nama_file = $fakultas . '-' . $nim . '-' . $pin . '.' . $extensi;
                $destinasi = storage_path() . '/files/pasfoto/';

                if (!File::isDirectory($destinasi)) {
                    File::makeDirectory($destinasi, 0775, true);
                }

                $mhs = IdentitasMahasiswa::whereemail($email)->first();
                $mhs->alamat = $request->get('alamat');
                $mhs->jenis_kelamin = $dbmhs->jns_kel_mahasiswa;
                $mhs->telepon = $request->get('telepon');
                $mhs->backup_telepon = $request->get('backup_telepon');
                $mhs->crypt_token = Crypt::encrypt($request->get('password'));
                $mhs->tempatlahir = $request->get('tempatlahir');
                $mhs->tanggallahir = $request->get('tanggallahir');
                $mhs->pasfoto = $nama_file;
                $mhs->ukuranbaju = $request->get('ukuranbaju');
                $mhs->kelengkapan = "Y"; //buat nentukan pernah membuat akun atau belum
                $mhs->save();


                $user = new User();
                $user->id = $mhs->id_user;
                $user->username = $nim;
                $user->password = Hash::make($request->get('password'));
                $user->level = "Mahasiswa";
                $user->save();

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

                try {
                    Mail::to($mhs->email)->send(new VerifyEmail($mhs));
                } catch (\Exception $d) {
                    abort(500, 'Gagal Mengirim Ke Email UNY');
                }

                return Redirect('/')->with('register', 'success');
            } else {
                return Redirect('/');
            }
        }
    }

    public function Print()
    {

        $template = storage_path() . '/files/template.docx';
        //return Response::download($template);
        $docx = new DOCXTemplate($template);
        $docx->set('nama', 'Arif');
        $docx->set('umur', date('m.d.Y'));
        $docx->set('status', 'Pilot');

        $docx->downloadAs('test.docx');
    }
}
