<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\FailUp;
use App\Model\Prodi;
use App\Model\DBMHS;
use App\Model\Dosen;
use App\Model\TipePKM;
use App\Model\Fakultas;
use App\Model\IdentitasMahasiswa;


class AuthCasController extends Controller
{
    //Get
    public function Front()
    {
        $tipeaktif = TipePKM::all();
        if (Auth::check()) {
            $leveluser = Auth::user()->level;

            if ($leveluser == 'SuperAdmin') {
                return Redirect::to('/admin');
            } elseif ($leveluser == 'Kemahasiswaan') {
                return Redirect::to('/kmhs');
            } elseif ($leveluser == 'Perekap') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Reviewer') {
                return Redirect::to('/dsn');
            } elseif ($leveluser == 'Dosen') {
                return Redirect::to('/dsn');
            } elseif ($leveluser == 'Mahasiswa') {
                return Redirect::to('/mhs');
            } elseif ($leveluser == 'Operator') {
                return Redirect::to('/opt');
            }
        } else {
             //return view('guest.sso', compact('tipeaktif'));
            return view('layout.500');
        }
    }

    //sistem login sso
    public function LoginSSO()
    {
        try {
            \Cas::authenticate();
            $email = \Cas::getCurrentUser();
            $cekakun = (User::whereusername($email)->count() == 0) ? true : false;
            if ($cekakun) {
                //cek apakah data mahasiswa sudah ada atau belum
                $is_data_available = (IdentitasMahasiswa::whereemail($email)->count() == 0) ? true : false;

                // jika belum ada buat user dan identitas baru
                if ($is_data_available) {

                    //cek apakah data ada didatabase mahasiswa pusat
                    $is_mhs_available = (DBMHS::whereemail_mahasiswa($email)->count() > 0) ? true : false;

                    ///jika data ditemukan di datapusat mahasiswa
                    if ($is_mhs_available) {
                        DB::beginTransaction();
                        try {
                            //ambil pusat data mahasiswa
                            $dbmhs = DBMHS::whereemail_mahasiswa($email)->first();

                            $idfakultas = Prodi::whereid($dbmhs->id_prodi_mahasiswa)->first()->id_fakultas;
                            $fakultas = Fakultas::whereid($idfakultas)->first()->nama_singkat;
                            $nim = $dbmhs->nim_mahasiswa;


                            $user = new User();
                            $user->username = $nim;
                            $user->password = Hash::make($dbmhs->email_mahasiswa);
                            $user->level = "Mahasiswa";
                            $user->save();

                            $mhs = new IdentitasMahasiswa();
                            $mhs->nim = $nim;
                            $mhs->email = $dbmhs->email_mahasiswa;
                            $mhs->nama = $dbmhs->nama_mahasiswa;
                            $mhs->id_prodi = $dbmhs->id_prodi_mahasiswa;
                            $mhs->jenis_kelamin = $dbmhs->jns_kel_mahasiswa;
                            $mhs->tempatlahir = $dbmhs->tmptlahir_mahasiswa;
                            $mhs->tanggallahir = $dbmhs->tgllahir_mahasiswa;
                            $mhs->crypt_token = Crypt::encrypt($dbmhs->email_mahasiswa);
                            $mhs->id_user = $user->id;
                            $mhs->save();


                            DB::commit();
                            Auth::loginUsingId($mhs->id_user);
                            return redirect('/');
                        } catch (\Exception $e) {
                            DB::rollback();
                        }

                        //jika tidak tidak terdaftar di data pusat mahasiswa
                    } else {
                        $dosen_available = (Dosen::whereemail_dosen($email)->count() != 0) ? true : false;
                        if ($dosen_available) {
                            DB::beginTransaction();
                            try {
                                $dsn = Dosen::whereemail_dosen($email)->first();

                                $user = new User();
                                $user->username = $dsn->email_dosen;
                                $user->password = Hash::make($dsn->email_dosen);
                                $user->level = "Dosen";
                                $user->save();

                                DB::commit();
                                Auth::loginUsingId($user->id);
                                return redirect('/');
                            } catch (\Exception $e) {
                                DB::rollback();
                            }
                        } else {
                            \Cas::logout();
                            abort(403, 'Maaf data anda tidak ditemukan sebagai mahasiswa atau segera hubungi PKM Center UNY!');
                        }
                    }

                    //jika data sudah ada di identitas mahasiswa
                } else {
                    //login menggunakan identitas mahasiswa
                    Auth::loginUsingId(IdentitasMahasiswa::whereemail($email)->first()->id_user);
                    return Redirect('/');
                }
            } else {
                Auth::loginUsingId(User::whereusername($email)->first()->id);
                return Redirect('/');
            }
        } catch (\Exception $e) {
            \Cas::logout();
            abort(403, 'Auth SSO Mahasiswa UNY telah Gagal');
        }
    }

    public function DaftarAkun()
    {
        $prodi = Prodi::whereid(Auth::user()->identitas_mahasiswa->id_prodi)->first();
        return view('guest.registerakun', compact('prodi'));
    }

    public function DaftarSso(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pasfoto' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'alamat' => 'required',
            'telepon' => 'required',
            'ukuranbaju' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('validator', 'failed');
        }

        DB::beginTransaction();
        try {
            $email = Auth::user()->identitas_mahasiswa->email;
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
            $mhs->telepon = $request->get('telepon');
            $mhs->backup_telepon = $request->get('backup_telepon');
            $mhs->pasfoto = $nama_file;
            $mhs->ukuranbaju = $request->get('ukuranbaju');
            $mhs->crypt_token = Crypt::encrypt($email);
            $mhs->kelengkapan = "Y"; //buat nentukan pernah membuat akun atau belum
            $mhs->save();

            $user = User::whereid(Auth::user()->id)->first();
            $user->email_verified_at = new \DateTime();
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

            DB::commit();
            return Redirect('/')->with('register', 'success');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
