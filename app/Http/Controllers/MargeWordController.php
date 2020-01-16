<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
use App\Model\FailUp;
use App\Model\FilePKM;
use App\Model\TipePKM;
use App\Model\SkimPKM;
use App\Model\TahunPKM;
use App\Model\Fakultas;
use App\Model\NilaiPKM;
use App\Model\UserDikti;
use App\Model\AddConfig;
use App\Model\DetailPKM;
use App\Model\AnggotaPKM;
use App\Model\IdentitasMahasiswa;
use App\Providers\DOCXTemplate;

class MargeWordController extends Controller
{
        //
        public function DownloadLemPeng($kode)
        {
                \Carbon\Carbon::setLocale('id');

                $id = Crypt::decryptString($kode);

                $pkm = FilePKM::select('*')
                        ->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
                        ->join('tipe_pkm', 'tipe_pkm.id', 'skim_pkm.id_tipe_pkm')
                        ->join('detail_pkm', 'detail_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
                        ->join('user_dikti', 'user_dikti.id_file_pkm', 'file_pkm.id')
                        ->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')
                        ->join('anggota_pkm', 'anggota_pkm.id_file_pkm', 'file_pkm.id')
                        ->where('file_pkm.id', '=', $id)
                        ->firstOrFail();

                $jumlahanggota = AnggotaPKM::whereid_file_pkm($id)->where('jabatan', '!=', 'Ketua')->count();

                $ketuapkm = AnggotaPKM::whereid_file_pkm($id)
                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                        ->where('anggota_pkm.jabatan', '=', 'Ketua')
                        ->firstOrFail();
                $anggota1 = AnggotaPKM::whereid_file_pkm($id)
                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                        ->where('jabatan', '=', 'Anggota 1')
                        ->firstOrFail();
                $anggota2 = AnggotaPKM::whereid_file_pkm($id)
                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                        ->where('jabatan', '=', 'Anggota 2')
                        ->firstOrFail();
                $dosen = Dosen::whereid($pkm->id_dosen)->firstOrFail();

                if ($pkm->id_skim_pkm == 1 || $pkm->id_skim_pkm == 2 || $pkm->id_skim_pkm == 10) {
                        //untuk SKIM PKM PE DAN PSH serta SUG

                        $cek = FilePKM::whereid($id)->firstOrFail();
                        $cek->template = "Y";
                        $cek->save();

                        $template = 'assets/document/TemplatePKMP.docx';
                        $docx = new DOCXTemplate($template);
                        $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                        $docx->set('TAHUNPKM', $pkm->tahun);
                        $docx->set('SKIMPKM', $pkm->skim_lengkap);
                        $docx->set('JUDUL', $pkm->judul);
                        $docx->set('JUDULPKM', strtoupper($pkm->judul));
                        $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                        $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                        $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                        //Ketua
                        $docx->set('NAMAKETUA', $ketuapkm->nama);
                        $docx->set('NIMKETUA', $ketuapkm->nim);
                        $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                        $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                        $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                        if ($ketuapkm->jenis_kelamin == "L") {
                                $docx->set('KELAMINKETUA', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINKETUA', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                        $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                        $docx->set('EMAILKETUA', $ketuapkm->email);
                        $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                        $docx->set('HPKETUA', $ketuapkm->telepon);
                        //Anggota 1
                        $docx->set('NAMAANGGOTA1', $anggota1->nama);
                        $docx->set('NIMANGGOTA1', $anggota1->nim);
                        $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                        $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                        $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                        if ($anggota1->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA1', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                        $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                        $docx->set('EMAILANGGOTA1', $anggota1->email);
                        $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                        $docx->set('HPANGGOTA1', $anggota1->telepon);
                        //Anggota 2
                        $docx->set('NAMAANGGOTA2', $anggota2->nama);
                        $docx->set('NIMANGGOTA2', $anggota2->nim);
                        $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                        $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                        $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                        if ($anggota2->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA2', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                        $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                        $docx->set('EMAILANGGOTA2', $anggota2->email);
                        $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                        $docx->set('HPANGGOTA2', $anggota2->telepon);
                        //Dosen
                        $docx->set('NAMADOSEN', $dosen->nama_dosen);
                        if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                $docx->set('NIDN', $dosen->nip_dosen);
                                $docx->set('NID', 'NIP');
                        } else {
                                if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                        $docx->set('NIDN', $dosen->nidn_dosen);
                                        $docx->set('NID', 'NIDN');
                                } else {
                                        $docx->set('NIDN', $dosen->nidk_dosen);
                                        $docx->set('NID', 'NIDK');
                                }
                        }
                        $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                        $docx->set('EMAILDOSEN', $dosen->email_dosen);
                        if ($dosen->jns_kal_dosen == "L") {
                                $docx->set('KELAMINDOSEN', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINDOSEN', 'Perempuan');
                        }
                        $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                        $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                        $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                        $docx->set('DURASI', $pkm->durasi);

                        $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                } else if ($pkm->id_skim_pkm == 5) {
                        //untuk SKIM PKM KC///////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $cek = FilePKM::whereid($id)->firstOrFail();
                        $cek->template = "Y";
                        $cek->save();

                        $template = 'assets/document/TemplatePKMKC.docx';
                        $docx = new DOCXTemplate($template);
                        $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                        $docx->set('TAHUNPKM', $pkm->tahun);
                        $docx->set('SKIMPKM', $pkm->skim_lengkap);
                        $docx->set('JUDUL', $pkm->judul);
                        $docx->set('JUDULPKM', strtoupper($pkm->judul));
                        $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                        $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                        $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                        //Ketua
                        $docx->set('NAMAKETUA', $ketuapkm->nama);
                        $docx->set('NIMKETUA', $ketuapkm->nim);
                        $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                        $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                        $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                        if ($ketuapkm->jenis_kelamin == "L") {
                                $docx->set('KELAMINKETUA', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINKETUA', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                        $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                        $docx->set('EMAILKETUA', $ketuapkm->email);
                        $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                        $docx->set('HPKETUA', $ketuapkm->telepon);
                        //Anggota 1

                        $docx->set('NAMAANGGOTA1', $anggota1->nama);
                        $docx->set('NIMANGGOTA1', $anggota1->nim);
                        $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                        $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                        $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                        if ($anggota1->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA1', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                        $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                        $docx->set('EMAILANGGOTA1', $anggota1->email);
                        $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                        $docx->set('HPANGGOTA1', $anggota1->telepon);
                        //Anggota 2
                        $docx->set('NAMAANGGOTA2', $anggota2->nama);
                        $docx->set('NIMANGGOTA2', $anggota2->nim);
                        $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                        $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                        $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                        if ($anggota2->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA2', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                        $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                        $docx->set('EMAILANGGOTA2', $anggota2->email);
                        $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                        $docx->set('HPANGGOTA2', $anggota2->telepon);
                        //Dosen
                        $docx->set('NAMADOSEN', $dosen->nama_dosen);
                        if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                $docx->set('NIDN', $dosen->nip_dosen);
                                $docx->set('NID', 'NIP');
                        } else {
                                if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                        $docx->set('NIDN', $dosen->nidn_dosen);
                                        $docx->set('NID', 'NIDN');
                                } else {
                                        $docx->set('NIDN', $dosen->nidk_dosen);
                                        $docx->set('NID', 'NIDK');
                                }
                        }
                        $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                        $docx->set('EMAILDOSEN', $dosen->email_dosen);
                        if ($dosen->jns_kal_dosen == "L") {
                                $docx->set('KELAMINDOSEN', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINDOSEN', 'Perempuan');
                        }
                        $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                        $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                        $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                        $docx->set('DURASI', $pkm->durasi);

                        $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                } else if ($pkm->id_skim_pkm == 7) {
                        //untuk SKIM PKM GT///////////////////////////////////////////////////////////////////////////////////////////////////////////////


                        $cek = FilePKM::whereid($id)->firstOrFail();
                        $cek->template = "Y";
                        $cek->save();

                        $template = 'assets/document/TemplatePKMGT.docx';
                        $docx = new DOCXTemplate($template);
                        $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                        $docx->set('TAHUNPKM', $pkm->tahun);
                        $docx->set('SKIMPKM', $pkm->skim_lengkap);
                        $docx->set('JUDUL', $pkm->judul);
                        $docx->set('JUDULPKM', strtoupper($pkm->judul));
                        $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                        $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                        //Ketua
                        $docx->set('NAMAKETUA', $ketuapkm->nama);
                        $docx->set('NIMKETUA', $ketuapkm->nim);
                        $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                        $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                        $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                        if ($ketuapkm->jenis_kelamin == "L") {
                                $docx->set('KELAMINKETUA', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINKETUA', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                        $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                        $docx->set('EMAILKETUA', $ketuapkm->email);
                        $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                        $docx->set('HPKETUA', $ketuapkm->telepon);
                        //Anggota 1
                        $docx->set('NAMAANGGOTA1', $anggota1->nama);
                        $docx->set('NIMANGGOTA1', $anggota1->nim);
                        $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                        $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                        $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                        if ($anggota1->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA1', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                        $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                        $docx->set('EMAILANGGOTA1', $anggota1->email);
                        $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                        $docx->set('HPANGGOTA1', $anggota1->telepon);
                        //Anggota 2
                        $docx->set('NAMAANGGOTA2', $anggota2->nama);
                        $docx->set('NIMANGGOTA2', $anggota2->nim);
                        $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                        $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                        $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                        if ($anggota2->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA2', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                        $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                        $docx->set('EMAILANGGOTA2', $anggota2->email);
                        $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                        $docx->set('HPANGGOTA2', $anggota2->telepon);
                        //Dosen
                        $docx->set('NAMADOSEN', $dosen->nama_dosen);
                        if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                $docx->set('NIDN', $dosen->nip_dosen);
                                $docx->set('NID', 'NIP');
                        } else {
                                if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                        $docx->set('NIDN', $dosen->nidn_dosen);
                                        $docx->set('NID', 'NIDN');
                                } else {
                                        $docx->set('NIDN', $dosen->nidk_dosen);
                                        $docx->set('NID', 'NIDK');
                                }
                        }
                        $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                        $docx->set('EMAILDOSEN', $dosen->email_dosen);
                        if ($dosen->jns_kal_dosen == "L") {
                                $docx->set('KELAMINDOSEN', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINDOSEN', 'Perempuan');
                        }
                        $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                        $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                        $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                        $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                } else if ($pkm->id_skim_pkm == 8) {
                        //untuk SKIM PKM AI///////////////////////////////////////////////////////////////////////////////////////////


                        $cek = FilePKM::whereid($id)->firstOrFail();
                        $cek->template = "Y";
                        $cek->save();

                        $template = 'assets/document/TemplatePKMAI.docx';
                        $docx = new DOCXTemplate($template);
                        $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                        $docx->set('TAHUNPKM', $pkm->tahun);
                        $docx->set('SKIMPKM', $pkm->skim_lengkap);
                        $docx->set('JUDUL', $pkm->judul);
                        $docx->set('JUDULPKM', strtoupper($pkm->judul));
                        $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                        $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                        //Ketua
                        $docx->set('NAMAKETUA', $ketuapkm->nama);
                        $docx->set('NIMKETUA', $ketuapkm->nim);
                        $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                        $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                        $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                        if ($ketuapkm->jenis_kelamin == "L") {
                                $docx->set('KELAMINKETUA', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINKETUA', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                        $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                        $docx->set('EMAILKETUA', $ketuapkm->email);
                        $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                        $docx->set('HPKETUA', $ketuapkm->telepon);
                        //Anggota 1

                        $docx->set('NAMAANGGOTA1', $anggota1->nama);
                        $docx->set('NIMANGGOTA1', $anggota1->nim);
                        $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                        $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                        $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                        if ($anggota1->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA1', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                        $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                        $docx->set('EMAILANGGOTA1', $anggota1->email);
                        $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                        $docx->set('HPANGGOTA1', $anggota1->telepon);
                        //Anggota 2
                        $docx->set('NAMAANGGOTA2', $anggota2->nama);
                        $docx->set('NIMANGGOTA2', $anggota2->nim);
                        $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                        $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                        $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                        if ($anggota2->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA2', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                        $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                        $docx->set('EMAILANGGOTA2', $anggota2->email);
                        $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                        $docx->set('HPANGGOTA2', $anggota2->telepon);
                        //Dosen
                        $docx->set('NAMADOSEN', $dosen->nama_dosen);
                        if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                $docx->set('NIDN', $dosen->nip_dosen);
                                $docx->set('NID', 'NIP');
                        } else {
                                if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                        $docx->set('NIDN', $dosen->nidn_dosen);
                                        $docx->set('NID', 'NIDN');
                                } else {
                                        $docx->set('NIDN', $dosen->nidk_dosen);
                                        $docx->set('NID', 'NIDK');
                                }
                        }
                        $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                        $docx->set('EMAILDOSEN', $dosen->email_dosen);
                        if ($dosen->jns_kal_dosen == "L") {
                                $docx->set('KELAMINDOSEN', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINDOSEN', 'Perempuan');
                        }
                        $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                        $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                        $docx->set('PRODIDOSEN', $dosen->prodi_dosen);


                        $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                } else if ($pkm->id_skim_pkm == 9) {
                        //untuk SKIM PKM GFK//////////////////////////////////////////////////////////////////////////////////////////////


                        $cek = FilePKM::whereid($id)->firstOrFail();
                        $cek->template = "Y";
                        $cek->save();

                        $template = 'assets/document/TemplatePKMGFK.docx';
                        $docx = new DOCXTemplate($template);
                        $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                        $docx->set('TAHUNPKM', $pkm->tahun);
                        $docx->set('SKIMPKM', $pkm->skim_lengkap);
                        $docx->set('JUDUL', $pkm->judul);
                        $docx->set('JUDULPKM', strtoupper($pkm->judul));
                        $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                        $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                        //Ketua
                        $docx->set('NAMAKETUA', $ketuapkm->nama);
                        $docx->set('NIMKETUA', $ketuapkm->nim);
                        $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                        $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                        $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                        if ($ketuapkm->jenis_kelamin == "L") {
                                $docx->set('KELAMINKETUA', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINKETUA', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                        $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                        $docx->set('EMAILKETUA', $ketuapkm->email);
                        $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                        $docx->set('HPKETUA', $ketuapkm->telepon);
                        //Anggota 1
                        $docx->set('NAMAANGGOTA1', $anggota1->nama);
                        $docx->set('NIMANGGOTA1', $anggota1->nim);
                        $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                        $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                        $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                        if ($anggota1->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA1', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                        $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                        $docx->set('EMAILANGGOTA1', $anggota1->email);
                        $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                        $docx->set('HPANGGOTA1', $anggota1->telepon);
                        //Anggota 2
                        $docx->set('NAMAANGGOTA2', $anggota2->nama);
                        $docx->set('NIMANGGOTA2', $anggota2->nim);
                        $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                        $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                        $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                        if ($anggota2->jenis_kelamin == "L") {
                                $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINANGGOTA2', 'Perempuan');
                        }
                        $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                        $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                        $docx->set('EMAILANGGOTA2', $anggota2->email);
                        $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                        $docx->set('HPANGGOTA2', $anggota2->telepon);
                        //Dosen
                        $docx->set('NAMADOSEN', $dosen->nama_dosen);
                        if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                $docx->set('NIDN', $dosen->nip_dosen);
                                $docx->set('NID', 'NIP');
                        } else {
                                if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                        $docx->set('NIDN', $dosen->nidn_dosen);
                                        $docx->set('NID', 'NIDN');
                                } else {
                                        $docx->set('NIDN', $dosen->nidk_dosen);
                                        $docx->set('NID', 'NIDK');
                                }
                        }
                        $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                        $docx->set('EMAILDOSEN', $dosen->email_dosen);
                        if ($dosen->jns_kal_dosen == "L") {
                                $docx->set('KELAMINDOSEN', 'Laki-laki');
                        } else {
                                $docx->set('KELAMINDOSEN', 'Perempuan');
                        }
                        $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                        $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                        $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                        $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                } else if ($pkm->id_skim_pkm == 3 || $pkm->id_skim_pkm == 4 || $pkm->id_skim_pkm == 6) {
                        //untuk SKIM PKM T(3) & PKM K(4) & PKM M(6)/////////////////////////////////////////////////////////////////////////////////////////////////////

                        if ($jumlahanggota == "2") {
                                //jumlah anggota 2

                                $cek = FilePKM::whereid($id)->firstOrFail();
                                $cek->template = "Y";
                                $cek->save();

                                if ($pkm->id_skim_pkm == 3) {
                                        //SKIM PKM T(3)
                                        $template = 'assets/document/TemplatePKMT3.docx';
                                } else if ($pkm->id_skim_pkm == 4) {
                                        //SKIM PKM K(3)
                                        $template = 'assets/document/TemplatePKMK3.docx';
                                } else if ($pkm->id_skim_pkm == 6) {
                                        //SKIM PKM M(3)
                                        $template = 'assets/document/TemplatePKMM3.docx';
                                }

                                $docx = new DOCXTemplate($template);
                                $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                                $docx->set('TAHUNPKM', $pkm->tahun);
                                $docx->set('SKIMPKM', $pkm->skim_lengkap);
                                $docx->set('JUDUL', $pkm->judul);
                                $docx->set('JUDULPKM', strtoupper($pkm->judul));
                                $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                                $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                                $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                                //Ketua
                                $docx->set('NAMAKETUA', $ketuapkm->nama);
                                $docx->set('NIMKETUA', $ketuapkm->nim);
                                $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                                $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                                $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                                if ($ketuapkm->jenis_kelamin == "L") {
                                        $docx->set('KELAMINKETUA', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINKETUA', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                                $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                                $docx->set('EMAILKETUA', $ketuapkm->email);
                                $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                                $docx->set('HPKETUA', $ketuapkm->telepon);
                                //Anggota 1
                                $docx->set('NAMAANGGOTA1', $anggota1->nama);
                                $docx->set('NIMANGGOTA1', $anggota1->nim);
                                $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                                $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                                $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                                if ($anggota1->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA1', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                                $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                                $docx->set('EMAILANGGOTA1', $anggota1->email);
                                $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                                $docx->set('HPANGGOTA1', $anggota1->telepon);
                                //Anggota 2
                                $docx->set('NAMAANGGOTA2', $anggota2->nama);
                                $docx->set('NIMANGGOTA2', $anggota2->nim);
                                $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                                $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                                $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                                if ($anggota2->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA2', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                                $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                                $docx->set('EMAILANGGOTA2', $anggota2->email);
                                $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                                $docx->set('HPANGGOTA2', $anggota2->telepon);
                                //Dosen
                                $docx->set('NAMADOSEN', $dosen->nama_dosen);
                                if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                        $docx->set('NIDN', $dosen->nip_dosen);
                                        $docx->set('NID', 'NIP');
                                } else {
                                        if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                                $docx->set('NIDN', $dosen->nidn_dosen);
                                                $docx->set('NID', 'NIDN');
                                        } else {
                                                $docx->set('NIDN', $dosen->nidk_dosen);
                                                $docx->set('NID', 'NIDK');
                                        }
                                }
                                $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                                $docx->set('EMAILDOSEN', $dosen->email_dosen);
                                if ($dosen->jns_kal_dosen == "L") {
                                        $docx->set('KELAMINDOSEN', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINDOSEN', 'Perempuan');
                                }
                                $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                                $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                                $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                                $docx->set('DURASI', $pkm->durasi);

                                $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                        } else if ($jumlahanggota == "3") {

                                $anggota3 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 3')
                                        ->firstOrFail();

                                $cek = FilePKM::whereid($id)->firstOrFail();
                                $cek->template = "Y";
                                $cek->save();

                                if ($pkm->id_skim_pkm == 3) {
                                        //SKIM PKM T(3)
                                        $template = 'assets/document/TemplatePKMT4.docx';
                                } else if ($pkm->id_skim_pkm == 4) {
                                        //SKIM PKM K(3)
                                        $template = 'assets/document/TemplatePKMK4.docx';
                                } else if ($pkm->id_skim_pkm == 6) {
                                        //SKIM PKM M(3)
                                        $template = 'assets/document/TemplatePKMM4.docx';
                                }

                                $docx = new DOCXTemplate($template);
                                $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                                $docx->set('TAHUNPKM', $pkm->tahun);
                                $docx->set('SKIMPKM', $pkm->skim_lengkap);
                                $docx->set('JUDUL', $pkm->judul);
                                $docx->set('JUDULPKM', strtoupper($pkm->judul));
                                $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                                $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                                $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                                //Ketua
                                $docx->set('NAMAKETUA', $ketuapkm->nama);
                                $docx->set('NIMKETUA', $ketuapkm->nim);
                                $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                                $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                                $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                                if ($ketuapkm->jenis_kelamin == "L") {
                                        $docx->set('KELAMINKETUA', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINKETUA', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                                $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                                $docx->set('EMAILKETUA', $ketuapkm->email);
                                $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                                $docx->set('HPKETUA', $ketuapkm->telepon);
                                //Anggota 1
                                $docx->set('NAMAANGGOTA1', $anggota1->nama);
                                $docx->set('NIMANGGOTA1', $anggota1->nim);
                                $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                                $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                                $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                                if ($anggota1->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA1', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                                $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                                $docx->set('EMAILANGGOTA1', $anggota1->email);
                                $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                                $docx->set('HPANGGOTA1', $anggota1->telepon);
                                //Anggota 2
                                $docx->set('NAMAANGGOTA2', $anggota2->nama);
                                $docx->set('NIMANGGOTA2', $anggota2->nim);
                                $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                                $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                                $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                                if ($anggota2->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA2', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                                $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                                $docx->set('EMAILANGGOTA2', $anggota2->email);
                                $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                                $docx->set('HPANGGOTA2', $anggota2->telepon);
                                //Anggota 3
                                $docx->set('NAMAANGGOTA3', $anggota3->nama);
                                $docx->set('NIMANGGOTA3', $anggota3->nim);
                                $docx->set('TAHUNANGKATANANGGOTA3', '20' . substr($anggota3->nim, 0, 2));
                                $docx->set('PRODIANGGOTA3', $anggota3->nama_prodi);
                                $docx->set('TEMPATANGGOTA3', $anggota3->tempatlahir);
                                if ($anggota3->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA3', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA3', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA3', TglIndo::Tgl_indo($anggota3->tanggallahir));
                                $docx->set('ALAMATANGGOTA3', $anggota3->alamat);
                                $docx->set('EMAILANGGOTA3', $anggota3->email);
                                $docx->set('FAKULTASANGGOTA3', $anggota3->nama_fakultas);
                                $docx->set('HPANGGOTA3', $anggota3->telepon);
                                //Dosen
                                $docx->set('NAMADOSEN', $dosen->nama_dosen);
                                if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                        $docx->set('NIDN', $dosen->nip_dosen);
                                        $docx->set('NID', 'NIP');
                                } else {
                                        if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                                $docx->set('NIDN', $dosen->nidn_dosen);
                                                $docx->set('NID', 'NIDN');
                                        } else {
                                                $docx->set('NIDN', $dosen->nidk_dosen);
                                                $docx->set('NID', 'NIDK');
                                        }
                                }
                                $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                                $docx->set('EMAILDOSEN', $dosen->email_dosen);
                                if ($dosen->jns_kal_dosen == "L") {
                                        $docx->set('KELAMINDOSEN', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINDOSEN', 'Perempuan');
                                }
                                $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                                $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                                $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                                $docx->set('DURASI', $pkm->durasi);

                                $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                        } else if ($jumlahanggota == "4") {
                                //jumlah anggota 4
                                $anggota3 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 3')
                                        ->firstOrFail();
                                $anggota4 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 4')
                                        ->firstOrFail();

                                $cek = FilePKM::whereid($id)->firstOrFail();
                                $cek->template = "Y";
                                $cek->save();

                                if ($pkm->id_skim_pkm == 3) {
                                        //SKIM PKM T(3)
                                        $template = 'assets/document/TemplatePKMT5.docx';
                                } else if ($pkm->id_skim_pkm == 4) {
                                        //SKIM PKM K(3)
                                        $template = 'assets/document/TemplatePKMK5.docx';
                                } else if ($pkm->id_skim_pkm == 6) {
                                        //SKIM PKM M(3)
                                        $template = 'assets/document/TemplatePKMM5.docx';
                                }
                                //return Response::download($template);
                                $docx = new DOCXTemplate($template);
                                $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                                $docx->set('TAHUNPKM', $pkm->tahun);
                                $docx->set('SKIMPKM', $pkm->skim_lengkap);
                                $docx->set('JUDUL', $pkm->judul);
                                $docx->set('JUDULPKM', strtoupper($pkm->judul));
                                $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                                $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                                $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                                //Ketua
                                $docx->set('NAMAKETUA', $ketuapkm->nama);
                                $docx->set('NIMKETUA', $ketuapkm->nim);
                                $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                                $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                                $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                                if ($ketuapkm->jenis_kelamin == "L") {
                                        $docx->set('KELAMINKETUA', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINKETUA', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                                $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                                $docx->set('EMAILKETUA', $ketuapkm->email);
                                $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                                $docx->set('HPKETUA', $ketuapkm->telepon);
                                //Anggota 1
                                $docx->set('NAMAANGGOTA1', $anggota1->nama);
                                $docx->set('NIMANGGOTA1', $anggota1->nim);
                                $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                                $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                                $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                                if ($anggota1->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA1', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                                $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                                $docx->set('EMAILANGGOTA1', $anggota1->email);
                                $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                                $docx->set('HPANGGOTA1', $anggota1->telepon);
                                //Anggota 2
                                $docx->set('NAMAANGGOTA2', $anggota2->nama);
                                $docx->set('NIMANGGOTA2', $anggota2->nim);
                                $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                                $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                                $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                                if ($anggota2->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA2', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                                $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                                $docx->set('EMAILANGGOTA2', $anggota2->email);
                                $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                                $docx->set('HPANGGOTA2', $anggota2->telepon);
                                //Anggota 3
                                $docx->set('NAMAANGGOTA3', $anggota3->nama);
                                $docx->set('NIMANGGOTA3', $anggota3->nim);
                                $docx->set('TAHUNANGKATANANGGOTA3', '20' . substr($anggota3->nim, 0, 2));
                                $docx->set('PRODIANGGOTA3', $anggota3->nama_prodi);
                                $docx->set('TEMPATANGGOTA3', $anggota3->tempatlahir);
                                if ($anggota3->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA3', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA3', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA3', TglIndo::Tgl_indo($anggota3->tanggallahir));
                                $docx->set('ALAMATANGGOTA3', $anggota3->alamat);
                                $docx->set('EMAILANGGOTA3', $anggota3->email);
                                $docx->set('FAKULTASANGGOTA3', $anggota3->nama_fakultas);
                                $docx->set('HPANGGOTA3', $anggota3->telepon);
                                //Anggota 4
                                $docx->set('NAMAANGGOTA4', $anggota4->nama);
                                $docx->set('NIMANGGOTA4', $anggota4->nim);
                                $docx->set('TAHUNANGKATANANGGOTA4', '20' . substr($anggota4->nim, 0, 2));
                                $docx->set('PRODIANGGOTA4', $anggota4->nama_prodi);
                                $docx->set('TEMPATANGGOTA4', $anggota4->tempatlahir);
                                if ($anggota4->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA4', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA4', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA4', TglIndo::Tgl_indo($anggota4->tanggallahir));
                                $docx->set('ALAMATANGGOTA4', $anggota4->alamat);
                                $docx->set('EMAILANGGOTA4', $anggota4->email);
                                $docx->set('FAKULTASANGGOTA4', $anggota4->nama_fakultas);
                                $docx->set('HPANGGOTA4', $anggota4->telepon);
                                //Dosen
                                $docx->set('NAMADOSEN', $dosen->nama_dosen);
                                if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                        $docx->set('NIDN', $dosen->nip_dosen);
                                        $docx->set('NID', 'NIP');
                                } else {
                                        if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                                $docx->set('NIDN', $dosen->nidn_dosen);
                                                $docx->set('NID', 'NIDN');
                                        } else {
                                                $docx->set('NIDN', $dosen->nidk_dosen);
                                                $docx->set('NID', 'NIDK');
                                        }
                                }
                                $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                                $docx->set('EMAILDOSEN', $dosen->email_dosen);
                                if ($dosen->jns_kal_dosen == "L") {
                                        $docx->set('KELAMINDOSEN', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINDOSEN', 'Perempuan');
                                }
                                $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                                $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                                $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                                $docx->set('DURASI', $pkm->durasi);

                                $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                        } else if ($jumlahanggota == "5") {
                                //jumlah anggota 5
                                $anggota3 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 3')
                                        ->firstOrFail();
                                $anggota4 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 4')
                                        ->firstOrFail();

                                $anggota5 = AnggotaPKM::whereid_file_pkm($id)
                                        ->join('identitas_mahasiswa', 'identitas_mahasiswa.id', 'anggota_pkm.id_mahasiswa')
                                        ->join('prodi', 'prodi.id', 'identitas_mahasiswa.id_prodi')
                                        ->join('fakultas', 'fakultas.id', 'prodi.id_fakultas')
                                        ->where('jabatan', '=', 'Anggota 5')
                                        ->firstOrFail();

                                $cek = FilePKM::whereid($id)->firstOrFail();
                                $cek->template = "Y";
                                $cek->save();

                                if ($pkm->id_skim_pkm == 3) {
                                        //SKIM PKM T(3)
                                        $template = 'assets/document/TemplatePKMT6.docx';
                                } else if ($pkm->id_skim_pkm == 4) {
                                        //SKIM PKM K(3)
                                        $template = 'assets/document/TemplatePKMK5.docx';
                                } else if ($pkm->id_skim_pkm == 6) {
                                        //SKIM PKM M(3)
                                        $template = 'assets/document/TemplatePKMM6.docx';
                                }
                                //return Response::download($template);
                                $docx = new DOCXTemplate($template);
                                $docx->set('SKIMPKMJUDUL', strtoupper($pkm->skim_lengkap));
                                $docx->set('TAHUNPKM', $pkm->tahun);
                                $docx->set('SKIMPKM', $pkm->skim_lengkap);
                                $docx->set('JUDUL', $pkm->judul);
                                $docx->set('JUDULPKM', strtoupper($pkm->judul));
                                $docx->set('JUMLAHANGGOTA', $jumlahanggota);
                                $docx->set('DANA', number_format($pkm->dana_pkm, 0, ".", "."));
                                $docx->set('TANGGAL', TglIndo::Tgl_indo(date("Y-n-d")));
                                //Ketua
                                $docx->set('NAMAKETUA', $ketuapkm->nama);
                                $docx->set('NIMKETUA', $ketuapkm->nim);
                                $docx->set('TAHUNANGKATANKETUA', '20' . substr($ketuapkm->nim, 0, 2));
                                $docx->set('PRODIKETUA', $ketuapkm->nama_prodi);
                                $docx->set('TEMPATKETUA', $ketuapkm->tempatlahir);
                                if ($ketuapkm->jenis_kelamin == "L") {
                                        $docx->set('KELAMINKETUA', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINKETUA', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRKETUA', TglIndo::Tgl_indo($ketuapkm->tanggallahir));
                                $docx->set('ALAMATKETUA', $ketuapkm->alamat);
                                $docx->set('EMAILKETUA', $ketuapkm->email);
                                $docx->set('FAKULTASKETUA', $ketuapkm->nama_fakultas);
                                $docx->set('HPKETUA', $ketuapkm->telepon);
                                //Anggota 1
                                $docx->set('NAMAANGGOTA1', $anggota1->nama);
                                $docx->set('NIMANGGOTA1', $anggota1->nim);
                                $docx->set('TAHUNANGKATANANGGOTA1', '20' . substr($anggota1->nim, 0, 2));
                                $docx->set('PRODIANGGOTA1', $anggota1->nama_prodi);
                                $docx->set('TEMPATANGGOTA1', $anggota1->tempatlahir);
                                if ($anggota1->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA1', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA1', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA1', TglIndo::Tgl_indo($anggota1->tanggallahir));
                                $docx->set('ALAMATANGGOTA1', $anggota1->alamat);
                                $docx->set('EMAILANGGOTA1', $anggota1->email);
                                $docx->set('FAKULTASANGGOTA1', $anggota1->nama_fakultas);
                                $docx->set('HPANGGOTA1', $anggota1->telepon);
                                //Anggota 2
                                $docx->set('NAMAANGGOTA2', $anggota2->nama);
                                $docx->set('NIMANGGOTA2', $anggota2->nim);
                                $docx->set('TAHUNANGKATANANGGOTA2', '20' . substr($anggota2->nim, 0, 2));
                                $docx->set('PRODIANGGOTA2', $anggota2->nama_prodi);
                                $docx->set('TEMPATANGGOTA2', $anggota2->tempatlahir);
                                if ($anggota2->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA2', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA2', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA2', TglIndo::Tgl_indo($anggota2->tanggallahir));
                                $docx->set('ALAMATANGGOTA2', $anggota2->alamat);
                                $docx->set('EMAILANGGOTA2', $anggota2->email);
                                $docx->set('FAKULTASANGGOTA2', $anggota2->nama_fakultas);
                                $docx->set('HPANGGOTA2', $anggota2->telepon);
                                //Anggota 3
                                $docx->set('NAMAANGGOTA3', $anggota3->nama);
                                $docx->set('NIMANGGOTA3', $anggota3->nim);
                                $docx->set('TAHUNANGKATANANGGOTA3', '20' . substr($anggota3->nim, 0, 2));
                                $docx->set('PRODIANGGOTA3', $anggota3->nama_prodi);
                                $docx->set('TEMPATANGGOTA3', $anggota3->tempatlahir);
                                if ($anggota3->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA3', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA3', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA3', TglIndo::Tgl_indo($anggota3->tanggallahir));
                                $docx->set('ALAMATANGGOTA3', $anggota3->alamat);
                                $docx->set('EMAILANGGOTA3', $anggota3->email);
                                $docx->set('FAKULTASANGGOTA3', $anggota3->nama_fakultas);
                                $docx->set('HPANGGOTA3', $anggota3->telepon);
                                //Anggota 4
                                $docx->set('NAMAANGGOTA4', $anggota4->nama);
                                $docx->set('NIMANGGOTA4', $anggota4->nim);
                                $docx->set('TAHUNANGKATANANGGOTA4', '20' . substr($anggota4->nim, 0, 2));
                                $docx->set('PRODIANGGOTA4', $anggota4->nama_prodi);
                                $docx->set('TEMPATANGGOTA4', $anggota4->tempatlahir);
                                if ($anggota4->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA4', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA4', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA4', TglIndo::Tgl_indo($anggota4->tanggallahir));
                                $docx->set('ALAMATANGGOTA4', $anggota4->alamat);
                                $docx->set('EMAILANGGOTA4', $anggota4->email);
                                $docx->set('FAKULTASANGGOTA4', $anggota4->nama_fakultas);
                                $docx->set('HPANGGOTA4', $anggota4->telepon);
                                //Anggota 5
                                $docx->set('NAMAANGGOTA5', $anggota5->nama);
                                $docx->set('NIMANGGOTA5', $anggota5->nim);
                                $docx->set('TAHUNANGKATANANGGOTA5', '20' . substr($anggota5->nim, 0, 2));
                                $docx->set('PRODIANGGOTA5', $anggota5->nama_prodi);
                                $docx->set('TEMPATANGGOTA5', $anggota5->tempatlahir);
                                if ($anggota5->jenis_kelamin == "L") {
                                        $docx->set('KELAMINANGGOTA5', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINANGGOTA5', 'Perempuan');
                                }
                                $docx->set('TGLLAHIRANGGOTA5', TglIndo::Tgl_indo($anggota5->tanggallahir));
                                $docx->set('ALAMATANGGOTA5', $anggota5->alamat);
                                $docx->set('EMAILANGGOTA5', $anggota5->email);
                                $docx->set('FAKULTASANGGOTA5', $anggota5->nama_fakultas);
                                $docx->set('HPANGGOTA5', $anggota5->telepon);

                                //Dosen
                                $docx->set('NAMADOSEN', $dosen->nama_dosen);
                                if ($dosen->nidn_dosen == "" && $dosen->nidk_dosen == "") {
                                        $docx->set('NIDN', $dosen->nip_dosen);
                                        $docx->set('NID', 'NIP');
                                } else {
                                        if ($dosen->nidn_dosen != "" || $dosen->nidn_dosen != NULL) {
                                                $docx->set('NIDN', $dosen->nidn_dosen);
                                                $docx->set('NID', 'NIDN');
                                        } else {
                                                $docx->set('NIDN', $dosen->nidk_dosen);
                                                $docx->set('NID', 'NIDK');
                                        }
                                }
                                $docx->set('ALAMATDOSEN', $dosen->alamat_dosen);
                                $docx->set('EMAILDOSEN', $dosen->email_dosen);
                                if ($dosen->jns_kal_dosen == "L") {
                                        $docx->set('KELAMINDOSEN', 'Laki-laki');
                                } else {
                                        $docx->set('KELAMINDOSEN', 'Perempuan');
                                }
                                $docx->set('TEMPATDOSEN', $dosen->tempatlahir_dosen);
                                $docx->set('TGLDOSEN', TglIndo::Tgl_indo($dosen->tanggallahir_dosen));
                                $docx->set('PRODIDOSEN', $dosen->prodi_dosen);

                                $docx->set('DURASI', $pkm->durasi);

                                $docx->downloadAs('Template ' . $pkm->skim_singkat . '.docx');
                        }
                }
        }
}
