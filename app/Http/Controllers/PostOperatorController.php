<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use DB;
use File;
use Storage;
use Crypt;
use Response;
use App\User;
use Redirect;
use App\Model\DBMHS;
use App\Model\Prodi;
use App\Model\TipePKM;
use App\Model\Operator;
use App\Model\SkimPKM;
use App\Model\FilePKM;
use App\Model\Fakultas;
use App\Model\TahunPKM;
use App\Model\AnggotaPKM;
use App\Model\CallCenter;
use App\Model\IdentitasMahasiswa;


class PostOperatorController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Operator
    public function __construct()
    {
        $this->middleware('opt');
    }
    ////////////////////////////////////////////////

    public function GantiPass(Request $request)
    {
            $pass = $request->get('password');
            $isme = Auth::user()->operator->opt_status;
            $dcrypt = Crypt::decrypt($isme);

            if($pass == $dcrypt){
                return redirect()->back()->with('password','old');
            }else{
                $ganti = User::whereusername(Auth::user()->username)->first();
                $ganti->password = Hash::make($pass);
                $ganti->save();

                $opt = Operator::whereid(Auth::user()->operator->id)->first();
                $opt->opt_status = Crypt::encrypt($pass);
                $opt->save();

                return redirect()->back()->with('password','success');
            }
    }

    public function GantiData(Request $request)
    {
        $saya = Auth::user()->Operator->id;
        $mhs = Operator::whereid($saya)->first();
        $mhs->nama_operator = $request->get('nama');
        $mhs->instagram_operator = $request->get('instagram');
        $mhs->tipe_quote = $request->get('tipe_kata');
        $mhs->quotes = $request->get('quote');
        $mhs->save();

        if ($mhs) {
            return redirect()->back()->with('data','success');
        } else {
            return redirect()->back()->with('data','failed');
        }
    }

    public function GantiFoto(Request $request)
    {
        $id = Auth::user()->operator->id;
        $saya = Operator::whereid($id)->first();
        $fotosaya = Auth::user()->operator->logo;
        $destinasi = 'storage/files/pasfoto/'.$fotosaya;
        File::delete($destinasi);

        $fakultas = Fakultas::whereid($saya->id_fakultas)->first()->nama_singkat;
        $file = $request->file('foto');
        $extensi = $file->getClientOriginalExtension();
        $pin = mt_rand(00, 999);
        $nama_file = $fakultas.'-Operator-'.$pin.'.'.$extensi;
        $destinasi = storage_path().'/files/pasfoto/';

        if (!File::isDirectory($destinasi)) {
            File::makeDirectory($destinasi, 0775, true);
        }
        $file->move($destinasi, $nama_file);

        try{
            $destinasifile = storage_path().'/files/pasfoto/'.$nama_file;
            $fileData = File::get($destinasifile);
            $dir = '/';
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir = $contents->where('type', '=', 'dir')
                ->where('filename', '=', 'foto')
                ->first(); // There could be duplicate directory names!
            if ( ! $dir) {
                return 'Directory does not exist!';
            }
            Storage::cloud()->put($dir['path'].'/'.$nama_file, $fileData);
        } catch (\Exception $e) {
            $fail = new FailUp();
            $fail->tipe = "Foto";
            $fail->namafile = $nama_file;
            $fail->dir = $destinasifile;
            $fail->save();
        }

        if ($file) {
            $update = Operator::whereid($id)->first();
            $update->logo = $nama_file;
            $update->save();

            return redirect()->back()->with('pasfoto','success');
        }else{
            return redirect()->back()->with('pasfoto','failed');
        }

    }

    public function AktivasiMhs(Request $request)
    {
        try {
            $id = Crypt::decryptString($request->get('kode'));
        } catch (\Exception $e) {
            abort(403,'Gagal memecahkan kode Aktivasi');
        }

        $user = User::whereid($id)->first();
        $user->email_verified_at = new \DateTime();
        $user->save();


        return redirect()->back()->with('aktivasi','success');
    }
    public function AddCallCenter(Request $request)
    {
        try {
            $nim = Crypt::decryptString($request->get('kode'));
        } catch (\Exception $e) {
            abort(403,'Gagal memecahkan kode pada Call Center');
        }

        $cekcc = CallCenter::wherenim_callcenter($nim)->first();
        if (is_object($cekcc)) {
            return redirect()->back()->with('callcenter','sudahada');
        } else {
            $dbmhs = DBMHS::wherenim_mahasiswa($nim)->first();
            if (is_object($dbmhs)) {
                $id_fakultas = Prodi::whereid($dbmhs->id_prodi_mahasiswa)->first()->id_fakultas;
                if (Auth::user()->operator->id_fakultas == $id_fakultas) {
                    $cc = new CallCenter();
                    $cc->nama_callcenter = $dbmhs->nama_mahasiswa;
                    $cc->nim_callcenter = $dbmhs->nim_mahasiswa;
                    $cc->email_callcenter = $dbmhs->email_mahasiswa;
                    $cc->id_fakultas = Auth::user()->operator->id_fakultas;
                    $cc->whatsapp = $request->get('telepon');
                    $cc->save();

                    return redirect()->back()->with('callcenter','success');
                } else {
                    abort(403,'Maaf, Akses data dibatasi');
                }
            } else {
                abort(403,'Maaf Data Mahasiswa Tidak ada');
            }

        }
    }

    public function postCekKode()

	{

		if (Request::ajax()) {

			$kodepkm = Input::get('kodepkm');

			$pkm = FilePKM::wherekode_pkm($kodepkm)

									//->join('detail_file_pkm', 'detail_file_pkm.id_file', '=', 'file_pkm.id')

	        					//	->join('detail_pkm', 'detail_pkm.id', '=', 'detail_file_pkm.id_detail_pkm')

	        						//->join('tahun_anggaran_pkm', 'tahun_anggaran_pkm.id' , '=', 'detail_pkm.id_tahun_anggaran_pkm' )

	            					//->where('tahun_anggaran_pkm.status', 1)

									->first();

			if (is_object($pkm)) {

                    $retval = array_merge($pkm->toArray());

					return Response::json($retval);

			} else {

				return $pkm;

			}

		}

    }

    public function UploadProposal(Request $request){
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
        $nama_file = $fakultas.'-'.$skimpkm.'-'.$nim.'-'.$pin.'.'.$extensi;
        $destinasi = storage_path().'/files/'.$tahun_aktif.'/proposal/'.$tipepkm;

        if (!File::isDirectory($destinasi)) {
            File::makeDirectory($destinasi, 0775, true);
        }

        $file->move($destinasi, $nama_file);


        $uppro = FilePKM::whereid($id_file_pkm)->first();
        $uppro->file_proposal = $nama_file;
        $uppro->time_proposal = date("d/m/Y/ H:m:s");
        $uppro->save();

        $destinasifile = storage_path().'/files/'.$tahun_aktif.'/proposal/'.$tipepkm.'/'.$nama_file;
        $fileData = File::get($destinasifile);

        try {
            $dir = '1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'; //id file drive proposal
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir1 = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $tahun_aktif)
                ->first(); // There could be duplicate directory names!
            if ( ! $dir1) {
                $coba = Storage::cloud()->makeDirectory('1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'.$tahun_aktif);
                $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                $dir2 = $contents2->where('type', '=', 'dir')
                ->where('filename', '=', $tahun_aktif)
                ->first();
                Storage::cloud()->put($dir2['path'].'/'.$nama_file, $fileData);
            }else{
                Storage::cloud()->put($dir1['path'].'/'.$nama_file, $fileData);
            }
        } catch (\Exception $e) {
            $fail = new FailUp();
            $fail->tipe = "Proposal";
            $fail->namafile = $nama_file;
            $fail->dir = $destinasifile;
            $fail->tambahan = $tahun_aktif;
            $fail->save();
        }

        return redirect()->back()->with('upload','success');
    }

    public function EditDataMahasiswa(Request $request)
    {
        try{
        $email = Crypt::decryptString($request->get('kode'));

        $mhs = IdentitasMahasiswa::whereemail($email)->firstOrFail();
        $mhs->telepon = $request->get('telepon');
        $mhs->backup_telepon = $request->get('backup_telepon');
        $mhs->ukuranbaju = $request->get('ukuranbaju');
        $mhs->save();

        return redirect()->back()->with('data','success');
        } catch(\Exception $e){
            abort(403,'Gagal memecahkan kode pada Call Center');
        }
    }

}
