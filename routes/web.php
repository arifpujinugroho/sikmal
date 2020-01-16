<?php

use Illuminate\Support\Facades\Artisan;
//use Crypt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Guest Controller//////////////////////////////////////////////////
//Get tanpa sso
//Route::get('/', 'GuestController@Front');
//Route::get('register', 'GuestController@Registrasi');
Route::get('prodi', 'GuestController@Prodi');
Route::get('all-fakultas', 'GuestController@AllFakultas');
Route::get('all-prodi', 'GuestController@AllProdi');
Route::get('downfilegdrive', 'GuestController@DownFileDrive');
//Route::get('cek-mhs','GuestController@CekMhs');
//Route::get('verify', 'GuestController@Verify')->name('signup.verify');
Route::get('uji-coba', function () {
    return view('guest\ujicoba');
});
//Post
Route::post('authlogin', 'GuestController@Login');
//Route::post('daftar', 'GuestController@Daftar');
////////////////////////////////////////////////////////////////////


////////////////////////////////  !!INTI!! RUNNING MENGGUNAKAN SSO //////////////////////
Route::get('/', 'AuthCasController@Front');
Route::get('/loginsso', 'AuthCasController@LoginSSO');
Route::get('/daftarakun', 'AuthCasController@DaftarAkun')->middleware('ssoregis', 'sso');
Route::post('/daftarsso', 'AuthCasController@DaftarSso')->middleware('ssoregis', 'sso');
/////////////////////////////////





//Routing Mahasiswa======================================================
Route::prefix('mhs')->group(function () {

    //Get Mahasiswa Controller
    Route::get('/', 'GetMahasiswaController@Home');
    Route::get('biodata', 'GetMahasiswaController@Biodata');
    Route::get('/list-pkm', 'GetMahasiswaController@ListPKM');
    Route::get('/list-dosen', 'GetMahasiswaController@ListDosen');
    Route::get('/list-kemajuan', 'GetMahasiswaController@ListKemajuan');
    Route::get('/list-akhir', 'GetMahasiswaController@ListAkhir');
    Route::get('/artikel-poster', 'GetMahasiswaController@ListPoster');
    Route::get('/info-download', 'GetMahasiswaController@InfoDownload');
    Route::get('/referensi-judul', 'GetMahasiswaController@ReferensiJudul');
    Route::get('/cardoh/anggota', 'GetMahasiswaController@CardohAnggota'); //tambah
    Route::get('/cardoh/ide', 'GetMahasiswaController@CardohIde'); //tambah
    Route::get('/cardoh/kelompok', 'GetMahasiswaController@CardohKelompok'); //tambah
    Route::get('/tambah-pkm', 'GetMahasiswaController@TambahPKM');
    Route::get('/skimpkm', 'GetMahasiswaController@SkimPKM');
    Route::get('/cek-dosen', 'GetMahasiswaController@CekDosen');
    Route::get('/dosen-fakultas', 'GetMahasiswaController@DosenFakultas');
    Route::get('/pkm/{id}', 'GetMahasiswaController@Lihatpkm');
    Route::get('/pkm/', function () {
        return Redirect::to('mhs/list-pkm')->with('pkm', 'kosong');
    });
    Route::get('/cek-mhs', 'GetMahasiswaController@CekMhs');
    Route::get('/downpro/{kode}', 'GetMahasiswaController@DownloadProposal');
    Route::get('/downlempeng/{kode}', 'MargeWordController@DownloadLemPeng')->middleware('mhs');
    Route::get('/cekdownlempeng', 'GetMahasiswaController@CekDownLemPeng');
    Route::get('/downlapkemajuan/{kode}', 'GetMahasiswaController@DownloadLapKemajuan');
    Route::get('/downlapakhir/{kode}', 'GetMahasiswaController@DownloadLapAkhir');
    Route::get('/downppt/{kode}', 'GetMahasiswaController@DownloadPPT');
    Route::get('/filedownloadgdrive', 'GetMahasiswaController@GdriveFileDownload');
    Route::get('surat',      'GetMahasiswaController@ListSurat');
    Route::get('tambah-surat',      'GetMahasiswaController@TambahSurat');
    Route::get('/dana/list', 'GetMahasiswaController@ListKeuangan');
    Route::get('/dana/{id}', 'GetMahasiswaController@Pendanaan');
    Route::get('/dana/data/{id}', 'GetMahasiswaController@DataPendanaan');
    Route::get('/dana/datanota/{id}', 'GetMahasiswaController@DataListNota');
    Route::get('/dana/datatransaksi/{id}', 'GetMahasiswaController@DataLihatNota');
    Route::get('/dana/nota/list', 'GetMahasiswaController@ListNota');
    Route::get('/dana/nota/tambah', 'GetMahasiswaController@TambahNota');
    Route::get('/dana/nota/lihat', 'GetMahasiswaController@LihatNota');
    Route::get('/alltoko', 'GetMahasiswaController@AllToko');
    Route::get('/totaltoko', 'GetMahasiswaController@TotalToko');
    Route::get('/penggunaandana', 'GetMahasiswaController@PenggunaanDana');
    Route::get('/ceksiupnpwp', 'GetMahasiswaController@CekSiupNpwp');
    
    //Post Mahasiswa Controller
    route::post('gantipass', 'PostMahasiswaController@GantiPass');
    route::post('gantidata', 'PostMahasiswaController@GantiData');
    route::post('gantifoto', 'PostMahasiswaController@GantiFoto');
    route::post('tambahpkm', 'PostMahasiswaController@TambahPKM');
    route::post('tambahanggota', 'PostMahasiswaController@TambahAnggota');
    route::post('hapusanggota', 'PostMahasiswaController@HapusAnggota');
    route::post('uploadproposal', 'PostMahasiswaController@UploadProposal');
    route::post('revisiproposal', 'PostMahasiswaController@RevisiProposal');
    route::post('uploadlapkemajuan', 'PostMahasiswaController@UploadLapKemajuan');
    route::post('uploadlapakhir', 'PostMahasiswaController@UploadLapAkhir');
    route::post('uploadppt', 'PostMahasiswaController@UploadPPT');
    route::post('editpkm', 'PostMahasiswaController@EditPKM');
    route::post('hapuspkm', 'PostMahasiswaController@HapusPKM');
    route::post('cardoh/tambahanggota',     'PostMahasiswaController@CarDohAnggotaTambah');
    route::post('cardoh/editanggota',       'PostMahasiswaController@CarDohAnggotaEdit');
    route::post('cardoh/tambahide',         'PostMahasiswaController@CarDohIdeTambah');
    route::post('cardoh/editide',           'PostMahasiswaController@CarDohIdeEdit');
    route::post('cardoh/tambahkelompok',    'PostMahasiswaController@CarDohKelompokTambah');
    route::post('cardoh/editkelompok',      'PostMahasiswaController@CarDohKelompokEdit');
    route::post('cardoh/hapus',      'PostMahasiswaController@CarDohHapus');
    route::post('accanggota',      'PostMahasiswaController@AccAnggota'); //tambah
    route::post('confirm',      'PostMahasiswaController@ConfirmPKM'); //tambah
    route::post('tambahtoko', 'PostMahasiswaController@TambahToko');
    route::post('tambahnota', 'PostMahasiswaController@TambahNota');
    route::post('uploadulangnota', 'PostMahasiswaController@GantiNota');
    route::post('editnota', 'PostMahasiswaController@EditNota');
    route::post('hapusnota', 'PostMahasiswaController@HapusNota');
    route::post('edittransaksi', 'PostMahasiswaController@EditTransaksi');
    route::post('tambahtransaksi', 'PostMahasiswaController@TambahTransaksi');
    route::post('hapustransaksi', 'PostMahasiswaController@HapusTransaksi');

});
//======================================================================


//Routing Admin---------------------------------------------------------
Route::prefix('admin')->group(function () {
    //Get Admin Controller
    Route::get('/', 'GetAdminController@Home');
    Route::get('pilihpkm', 'GetAdminController@PilihPKM');
    Route::get('pilihdownloadpkm', 'GetAdminController@PilihPKM');
    Route::get('pilihmaksdosen', 'GetAdminController@PilihPKM');
    Route::get('pilihakunsimbelmawa', 'GetAdminController@PilihPKM');
    Route::get('pilihgrafik', 'GetAdminController@PilihPKM');
    Route::get('pilihpenil', 'GetAdminController@PilihPKM');
    Route::get('pilihnilpro', 'GetAdminController@PilihPKM');
    Route::get('pilihnilin', 'GetAdminController@PilihPKM');
    Route::get('pilihkeuangan', 'GetAdminController@PilihPKM');
    Route::get('aktif-pkm', 'GetAdminController@AktifPKM');
    Route::get('user/opt', 'GetAdminController@UserOperator');
    Route::get('user/mhs', 'GetAdminController@UserMhs');
    Route::get('user/datamhs', 'GetAdminController@DataUserMhs');
    Route::get('user/rkp', 'GetAdminController@UserPerekap');
    Route::get('setting', 'GetAdminController@Setting');
    Route::get('allcallcenter', 'GetAdminController@AllCallCenter');
    Route::get('print/{kode}',      'GetAdminController@PrintKode');
    Route::get('listpkm/{tahunpkm}/{tipepkm}',      'GetAdminController@ListPKM');
    Route::get('datalistpkm/{tahunpkm}/{tipepkm}',      'GetAdminController@DataListPKM');
    Route::get('/list-dosen', 'GetAdminController@ListDosen');
    Route::get('/datalistdosen', 'GetAdminController@DataListDosen');
    Route::get('/editdosen', 'GetAdminController@EditDosen');
    Route::get('listmaksdosen/{tahunpkm}',      'GetAdminController@ListMaksDosen');
    Route::get('listdosenbimbing/{tahunpkm}/{dsn}',      'GetAdminController@ListDosenBimbing');
    Route::get('datalistdosenbimbing/{tahunpkm}/{dsn}',      'GetAdminController@DataListDosenBimbing');
    Route::get('downloadpkm/{tahunpkm}/{tipepkm}',  'GetAdminController@ListDownloadPKM');
    Route::get('akunsimbel/{tahunpkm}/{tipepkm}',   'GetAdminController@ListAkunSimbel');
    Route::get('setpenil/{tahunpkm}/{tipepkm}',   'GetAdminController@ListPenil');
    Route::get('grafikpkm/{tahunpkm}/{tipepkm}',    'GetAdminController@GrafikPKM');
    Route::get('nilpro/{tahunpkm}/{tipepkm}',    'GetAdminController@NilaiProposal');
    Route::get('inputcustom',    'GetAdminController@InputCustom');
    Route::get('/skimpkm', 'GetAdminController@SkimPKM');
    Route::get('/cek-dosen', 'GetAdminController@CekDosen');
    Route::get('/dosen-fakultas', 'GetAdminController@DosenFakultas');
    Route::get('/cek-mhs', 'GetAdminController@CekMhs');
    Route::get('/cek-mhs-custom', 'GetAdminController@CekMhsCustom');
    Route::get('/pkm/{id}', 'GetAdminController@Lihatpkm');
    Route::get('/pkm/', function () {
        return Redirect::to('/')->with('pkm', 'kosong');
    });
    Route::get('/info-download', 'GetAdminController@InfoDownload');
    Route::get('/listtoko', 'GetAdminController@ListToko');
    Route::get('/listkeuangan/{tahunpkm}/{tipepkm}', 'GetAdminController@ListLaporanKeuangan');
    Route::get('/dana/{id}', 'GetAdminController@Pendanaan');
    Route::get('/dana/data/{id}', 'GetAdminController@DataPendanaan');
    
    //data
    Route::get('datagrafikpkm/{tahunpkm}/{tipepkm}', 'GetAdminController@DataGrafikPKM');
    Route::get('datagrafikpkmlengkap/{tahunpkm}/{tipepkm}', 'GetAdminController@DataGrafikPKMLengkap');
    Route::get('datasimbel/{tahunpkm}/{tipepkm}',   'GetAdminController@DataAkunSimbel');
    Route::get('datareviewer/',   'GetAdminController@DataReviewer');
    Route::get('datadosen/',   'GetAdminController@DataDosen');
    Route::get('datapenil/{tahunpkm}/{tipepkm}',   'GetAdminController@DataPenil');
    Route::get('dataproposal/{tahunpkm}/{tipepkm}',   'GetAdminController@DataProposal');
    Route::get('anggota-pkm',   'GetAdminController@GetAnggotaPKM');
    Route::get('/downpro/{kode}', 'GetAdminController@DownloadProposal');
    Route::get('/downppt/{kode}', 'GetAdminController@DownloadPPT');
    Route::get('alltipepkm', 'GetAdminController@AllTipePKM');
    Route::get('alltahunpkm', 'GetAdminController@AllTahunPKM');
    Route::get('alldownload', 'GetAdminController@AllDownload');
    Route::get('alltoko', 'GetAdminController@AllToko');
    Route::get('/filedownloadgdrive', 'GetAdminController@GdriveFileDownload');
    Route::get('/datalistkeuangan', 'GetAdminController@DataListKeuangan');
    Route::get('/totaltoko', 'GetAdminController@TotalToko');
    Route::get('/penggunaandana', 'GetAdminController@PenggunaanDana');
    
    
    //Post Admin Controller
    route::get('updatesimbel', 'PostAdminController@EditSimbel');
    route::post('ubahdatamhs', 'PostAdminController@EditDataMahasiswa');
    route::post('uploadproposal', 'PostAdminController@UploadProposal');
    route::post('gantipass', 'PostAdminController@GantiPass');
    route::post('resetopt', 'PostAdminController@ResetOpt');
    route::post('hapusopt', 'PostAdminController@HapusOpt');
    route::post('hapusreviewer', 'PostAdminController@HapusReviewer');
    route::post('tambahopt', 'PostAdminController@TambahOperator');
    route::post('tambahreviewer', 'PostAdminController@TambahReviewer');
    route::post('inputrev', 'PostAdminController@InputReviewer');
    route::post('inputcustom', 'PostAdminController@InputCustom');
    route::post('statuspkm', 'PostAdminController@StatusPKM');
    route::post('tahunpkm', 'PostAdminController@TahunPKM');
    route::post('tambahtahunpkm', 'PostAdminController@TambahTahunPKM');
    route::post('tambahanggota', 'PostAdminController@TambahAnggota');
    route::post('hapuspkm', 'PostAdminController@HapusPKM');
    route::post('hapusanggota', 'PostAdminController@HapusAnggota');
    route::post('uploadproposalcustom', 'PostAdminController@UploadProposalCustom');
    route::post('revisiproposal', 'PostAdminController@RevisiProposal');
    route::post('editpkm', 'PostAdminController@EditPKM');
    route::post('accanggota',      'PostAdminController@AccAnggota'); //tambah
    route::post('tambahlink', 'PostAdminController@TambahLink');
    route::post('editlink', 'PostAdminController@EditLink');
    route::post('hapuslink', 'PostAdminController@HapusLink');
    route::post('tambahtoko', 'PostAdminController@TambahToko');
    route::post('edittoko', 'PostAdminController@EditToko');
    route::post('hapustoko', 'PostAdminController@HapusToko');
    route::post('uploadsurat', 'PostAdminController@UploadSurat');
    route::post('acctransaksi', 'PostAdminController@AccTransaksi');
    route::post('hapusacctransaksi', 'PostAdminController@HapusAccTransaksi');
    route::post('edittransaksi', 'PostAdminController@EditTransaksi');
});
//-----------------------------------------------------------------------



//Routing Operator=======================================================
Route::prefix('opt')->group(function () {
    //Get Operator Controller
    Route::get('/', 'GetOperatorController@Home');
    Route::get('pilihpkm', 'GetOperatorController@PilihPKM');
    Route::get('pilihdownloadpkm', 'GetOperatorController@PilihPKM');
    Route::get('pilihmaksdosen', 'GetOperatorController@PilihPKM');
    Route::get('pilihakunsimbelmawa', 'GetOperatorController@PilihPKM');
    Route::get('pilihgrafik', 'GetOperatorController@PilihPKM');
    Route::get('listpkm/{tahunpkm}/{tipepkm}', 'GetOperatorController@ListPKM');
    Route::get('listmaksdosen/{tahunpkm}/{tipepkm}',      'GetOperatorController@ListMaksDosen');
    Route::get('downloadpkm/{tahunpkm}/{tipepkm}', 'GetOperatorController@ListDownloadPKM');
    Route::get('akunsimbel/{tahunpkm}/{tipepkm}', 'GetOperatorController@ListAkunSimbel');
    Route::get('grafikpkm/{tahunpkm}/{tipepkm}', 'GetOperatorController@GrafikPKM');
    Route::get('datagrafikpkm/{tahunpkm}/{tipepkm}', 'GetOperatorController@DataGrafikPKM');
    Route::get('ceklistpkm', 'GetOperatorController@SearchListPKM');
    Route::get('cekmaksdosen',    'GetOperatorController@SearchMaksDosen');
    Route::get('cekdownloadpkm', 'GetOperatorController@SearchDownloadPKM');
    Route::get('cekakunsimbel', 'GetOperatorController@SearchAkunSimbelmawa');
    Route::get('cekgrafikpkm', 'GetOperatorController@SearchGrafikPKM');
    Route::get('mahasiswa', 'GetOperatorController@User');
    Route::get('biodata', 'GetOperatorController@Biodata');
    Route::get('anggota-pkm', 'GetOperatorController@GetAnggotaPKM');
    Route::get('cc', 'GetOperatorController@CallCenter');
    Route::get('callcenter', 'GetOperatorController@CC');
    Route::get('activationmhs', 'GetOperatorController@Activation');
    Route::get('cek-aktivasi', 'GetOperatorController@CekAktivation');
    Route::get('cek-callcenter', 'GetOperatorController@CekCallCenter');
    Route::get('hapus/callcenter/{kode}', 'GetOperatorController@HapusCallCenter');
    Route::get('/downpro/{kode}', 'GetOperatorController@DownloadProposal');


    //Post Operator Controller
    route::post('gantipass', 'PostOperatorController@GantiPass');
    route::post('gantidata', 'PostOperatorController@GantiData');
    route::post('gantifoto', 'PostOperatorController@GantiFoto');
    Route::post('aktifkan-mhs', 'PostOperatorController@AktivasiMhs');
    Route::post('add-callcenter', 'PostOperatorController@AddCallCenter');
    route::post('ubahdatamhs', 'PostOperatorController@EditDataMahasiswa');
    route::post('uploadproposal', 'PostOperatorController@UploadProposal');
});
//=======================================================================



//Routing Perekap--------------------------------------------------------
Route::prefix('rkp')->group(function () {
    //Get Perekap Controller
    Route::get('/', 'GetPerekapController@Home');
    Route::get('nilai/proposal', 'GetPerekapController@NilaiProposal');
    Route::get('nilai/interview', 'GetPerekapController@NilaiInterview');

    Route::get('cek-kode', 'GetPerekapController@CekKode');

    //Post Perekap Controller
    route::post('gantipass', 'PostPerekapController@GantiPass');
});
//----------------------------------------------------------------------



//Routing Dosen--------------------------------------------------------
Route::prefix('dsn')->group(function () {
    //Get Dosen Controller
    Route::get('/', 'GetDosenController@Home');
    Route::get('/pilihpkm',       'GetDosenController@PilihPKM');
    Route::get('/pilihpkmbimbing',       'GetDosenController@PilihPKM');
    Route::get('/pilihgrafik', 'GetDosenController@PilihPKM');
    Route::get('/ceklistpkmbimbing',           'GetDosenController@SearchListPKMBimbing');
    Route::get('/ceklistpkm',           'GetDosenController@SearchListPKM');
    Route::get('/cekgrafikpkm',  'GetDosenController@SearchGrafikPKM');
    Route::get('/listpkm/{tahunpkm}/{tipepkm}',   'GetDosenController@ListPKM');
    Route::get('/listpkmbimbing/{tahunpkm}',   'GetDosenController@ListPKMBimbing');
    Route::get('/grafikpkm/{tahunpkm}/{tipepkm}',    'GetDosenController@GrafikPKM');
    Route::get('/cardoh/anggota',       'GetDosenController@CardohAnggota'); //tambah
    Route::get('/cardoh/ide',           'GetDosenController@CardohIde'); //tambah
    Route::get('/cardoh/kelompok',      'GetDosenController@CardohKelompok'); //tambah
    Route::get('/downpro/{kode}', 'GetDosenController@DownloadProposal');

    Route::get('datagrafikpkm/{tahunpkm}/{tipepkm}', 'GetDosenController@DataGrafikPKM');
    Route::get('datagrafikpkmlengkap/{tahunpkm}/{tipepkm}', 'GetDosenController@DataGrafikPKMLengkap');

    //PostDosen Controller
    route::post('gantipass', 'PostDosenController@GantiPass');
});
//----------------------------------------------------------------------



//Routing Reviewer--------------------------------------------------------
Route::prefix('reviewer')->group(function () {
    //Get Perekap Controller
    Route::get('/', 'GetReviewerController@Home');
    Route::get('nilai/proposal', 'GetReviewerController@NilaiProposal');
    Route::get('nilai/dataproposal', 'GetReviewerController@DataProposal');
    Route::get('downpro/{kode}/{file_name}', 'GetReviewerController@DownloadProposal');

    //Post Reviewer
    Route::get('inputnilai', 'PostReviewerController@InputNilai');
});
//----------------------------------------------------------------------

//Routing Kemahasiswaan--------------------------------------------------------
Route::prefix('kmhs')->group(function () {
    //Get Perekap Controller
    Route::get('/', 'GetKemahasiswaanController@Home');
    Route::get('pilihpkm', 'GetKemahasiswaanController@PilihPKM');
    Route::get('pilihdownloadpkm', 'GetKemahasiswaanController@PilihPKM');
    Route::get('pilihmaksdosen', 'GetKemahasiswaanController@PilihPKM');
    Route::get('pilihakunsimbelmawa', 'GetKemahasiswaanController@PilihPKM');
    Route::get('pilihgrafik', 'GetKemahasiswaanController@PilihPKM');
    Route::get('ceklistpkm',    'GetKemahasiswaanController@SearchListPKM');
    Route::get('cekmaksdosen',    'GetKemahasiswaanController@SearchMaksDosen');
    Route::get('cekdownloadpkm', 'GetKemahasiswaanController@SearchDownloadPKM');
    Route::get('cekakunsimbel', 'GetKemahasiswaanController@SearchAkunSimbelmawa');
    Route::get('cekgrafikpkm',  'GetKemahasiswaanController@SearchGrafikPKM');
    Route::get('listpkm/{tahunpkm}/{tipepkm}',      'GetKemahasiswaanController@ListPKM');
    Route::get('listmaksdosen/{tahunpkm}/{tipepkm}',      'GetKemahasiswaanController@ListMaksDosen');
    Route::get('downloadpkm/{tahunpkm}/{tipepkm}',  'GetKemahasiswaanController@ListDownloadPKM');
    Route::get('akunsimbel/{tahunpkm}/{tipepkm}',   'GetKemahasiswaanController@ListAkunSimbel');
    Route::get('datasimbel/{tahunpkm}/{tipepkm}',   'GetKemahasiswaanController@DataAkunSimbel');
    Route::get('grafikpkm/{tahunpkm}/{tipepkm}',    'GetKemahasiswaanController@GrafikPKM');
    Route::get('listsurat',      'GetKemahasiswaanController@ListSurat');
    Route::get('/downpro/{kode}', 'GetKemahasiswaanController@DownloadProposal');
    Route::get('/downppt/{kode}', 'GetKemahasiswaanController@DownloadPPT');
    Route::get('datagrafikpkm/{tahunpkm}/{tipepkm}', 'GetKemahasiswaanController@DataGrafikPKM');
    Route::get('datagrafikpkmlengkap/{tahunpkm}/{tipepkm}', 'GetKemahasiswaanController@DataGrafikPKMLengkap');
});
//-----------------------------------------------------------------------








//////Jagan Dihapus/////////////////////////////////////////////////////////////////////
Route::get('/logoutsso', function () {
    //Auth::logout();
    \Cas::logout();
    return Redirect::to('/')
        ->with('login', 'logout');
});
Route::get('/outself', function () {
    Auth::logout();
    //\Cas::logout();
    return Redirect::to('/')
        ->with('login', 'logout');
});

Route::get('cache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return Redirect()->back()->with('cache', 'clear');
});

Route::get('kode/{kode}', function ($kode) {
    $hasil = Crypt::decryptString($kode);
    return $hasil;
});

/*Route::get('/logout', function () {
    Auth::logout();
    return Redirect::to('/')
        ->with('login', 'logout');
})->name('logout');
/*/ //////////////////////////////////////////////////////////////////////////////////////
Route::get('/logout', function () {
    if (Auth::user()->level == 'Operator') {
        Auth::logout();
    } else {
        \Cas::logout();
    }
    return Redirect::to('/')
        ->with('login', 'logout');
})->name('logout');
/*/


Route::get('list-dir', function() {
    // The human readable folder name to get the contents of...
    // For simplicity, this folder is assumed to exist in the root directory.
    $folder = '/';
    // Get root directory contents...
    $contents = collect(Storage::cloud()->listContents('/', false));
    // Find the folder you are looking for...
    $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', $folder)
        ->first(); // There could be duplicate directory names!

    if ( ! $dir) {
        return 'No such folder!';
    }
    // Get the files inside the folder...
    $files = collect(Storage::cloud()->listContents($dir['path'], false))
        ->where('type', '=', 'file');
    return $files->mapWithKeys(function($file) {
        $filename = $file['filename'].'.'.$file['extension'];
        $path = $file['path'];
        // Use the path to download each file via a generated link..
        // Storage::cloud()->get($file['path']);
        return [$filename => $path];
    });
});

Route::get('list', function() {
    $dir = '1Hq3MJ5dsfrIaR40F7mvjBiHMJp_WEey0';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    //return $contents->where('type', '=', 'dir'); // directories
    return $contents->where('type', '=', 'file'); // files
});



Route::get('put-in-dir', function() {
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', 'Test Dir')
        ->first(); // There could be duplicate directory names!
    if ( ! $dir) {
        return 'Directory does not exist!';
    }
    Storage::cloud()->put($dir['path'].'/test.txt', 'Hello World');
    return 'File was created in the sub directory in Google Drive';
});

Route::get('dir', function() {
    $dir = '1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        $dir1 = $contents->where('type', '=', 'dir')
            ->where('filename', '=', '2019')
            ->first(); // There could be duplicate directory names!
            return $dir1;
        if ( ! $dir1) {
            $coba = Storage::cloud()->makeDirectory('1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/2019');
            $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
            $dir2 = $contents2->where('type', '=', 'dir')
            ->where('filename', '=', '2019')
            ->first();
            //Storage::cloud()->put($dir2['path'].'/test.txt', 'Hello World');
        //}else{
            //Storage::cloud()->put($dir1['path'].'/test.txt', 'Hello World');
        }
});

Route::get('get', function() {

    $filename = 'FT-16520241009-440.jpg';
    $dir = '1Hq3MJ5dsfrIaR40F7mvjBiHMJp_WEey0';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!
    //return $file; // array with file info
    $rawData = Storage::cloud()->get($file['path']);
    return response($rawData, 200)
        ->header('ContentType', $file['mimetype'])
        ->header('Content-Disposition', "attachment; filename=$filename");
});

Route::get('create-dir', function() {
    Storage::cloud()->makeDirectory('Test Dir');
    return 'Directory was created in Google Drive';
});

Route::get('delete', function() {
    $filename = 'test.txt';
    // First we need to create a file to delete
    Storage::cloud()->makeDirectory('Test Dir');
    // Now find that file and use its ID (path) to delete it
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!
    Storage::cloud()->delete($file['path']);
    return 'File was deleted from Google Drive';
});
*/
