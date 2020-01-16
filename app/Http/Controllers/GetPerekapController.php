<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use Crypt;
use Response;
use App\User;
use Redirect;
use App\Model\Prodi;
use App\Model\FilePKM;
use App\Model\CallCenter;
use App\Model\Fakultas;
use App\Model\IdentitasMahasiswa;


class GetPerekapController extends Controller
{
	/////JANGAN DIHAPUS /////
	//ini konstraktor buat middleware sebagai Perekap
	public function __construct()
	{
		$this->middleware(['rkp', 'sso']);
	}
	////////////////////////////////////////////////

	public function NilaiProposal()
	{
		$saya = Auth::user()->identitas_mahasiswa->nim;

		$list = FilePKM::select('*')
			->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
			->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
			->where('nilai_pkm.perekap_proposal', 'like', $saya)
			->get();

		$call = $this->CallCenter();

		return view('auth.perekap.nilaiproposal', compact('list', 'call'));
	}

	public function NilaiInterview()
	{
		if (Auth::user()->level == "PerekapMahasiswa") {
			$saya = Auth::user()->identitas_mahasiswa->nim;
		} else {
			$saya = Auth::user()->dosen->nama_dosen;
		}
		$data = FilePKM::select('*')
			->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')
			->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')
			->where('nilai_pkm.perekap_proposal', 'like', $saya)
			->get();

		return view('auth.perekap.nilaiinterview', compact('data'));
	}

	public function getNilai($type = '')
	{
		$parent_nilai = 'admin/nilai';

		switch ($type) {

			case 'proposal':

				$moderekap =  Pengaturan::wheretipe('moderekap')->first()->isinya;

				$saya = Auth::user()->perekap->nama_perekap;

				$data = FilePKM::select('file_pkm.id', 'file_pkm.kode_pkm', 'file_pkm.jenis_pkm', 'file_pkm.preview_pro', 'file_pkm.nilai_pkm', 'file_pkm.np1', 'file_pkm.np2', 'file_pkm.np3', 'file_pkm.np4', 'file_pkm.np5', 'file_pkm.np6', 'file_pkm.np7', 'file_pkm.np8', 'file_pkm.np9', 'file_pkm.np10', 'file_pkm.penilai_pkm', 'file_pkm.note_nilai')

					->join('detail_file_pkm', 'detail_file_pkm.id_file', '=', 'file_pkm.id')

					->join('detail_pkm', 'detail_pkm.id', '=', 'detail_file_pkm.id_detail_pkm')

					->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'detail_pkm.id_ketua')

					->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')

					->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')

					->where('file_pkm.penilai_pkm', 'like', $saya)

					->orderBy('file_pkm.updated_at', 'DESC')

					->get();



				return View::make('perekap.nilaiproposal')

					->withModerekap($moderekap)

					->withData($data)

					->withParent_nilai($parent_nilai);

				break;


			case 'interview':

				$saya = Auth::user()->perekap->nama_perekap;

				$data = FilePKM::select('file_pkm.id', 'file_pkm.kode_pkm', 'file_pkm.jenis_pkm', 'file_pkm.nilai_inter', 'file_pkm.ni1', 'file_pkm.ni2', 'file_pkm.ni3', 'file_pkm.ni4', 'file_pkm.ni5', 'file_pkm.perekap_inter', 'file_pkm.note_inter', 'identitas_mahasiswa.nama', 'identitas_mahasiswa.nim', 'identitas_mahasiswa.telepon', 'prodi.nama_prodi', 'fakultas.nama_singkat')

					->join('detail_file_pkm', 'detail_file_pkm.id_file', '=', 'file_pkm.id')

					->join('detail_pkm', 'detail_pkm.id', '=', 'detail_file_pkm.id_detail_pkm')

					->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'detail_pkm.id_ketua')

					->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')

					->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')

					->join('tahun_anggaran_pkm', 'tahun_anggaran_pkm.id', '=', 'detail_pkm.id_tahun_anggaran_pkm')

					->where('tahun_anggaran_pkm.id', 14)

					->where('file_pkm.perekap_inter', 'like', $saya)

					->orderBy('file_pkm.updated_at', 'DESC')

					->get();



				return View::make('perekap.nilaiinterview')

					->withData($data)

					->withParent_nilai($parent_nilai);

				break;

			case 'cekadministrasi':

				$saya = Auth::user()->perekap->nama_perekap;

				$data = FilePKM::select(
					'file_pkm.id',
					'detail_file_pkm.id_file',
					'file_pkm.judul',
					'file_pkm.jenis_pkm',
					'file_pkm.nama_file',
					'file_pkm.nama_dosen',
					'file_pkm.file_laporan_kemajuan',
					'file_pkm.file_laporan_akhir',
					'file_pkm.nama_file_revisi',
					'file_pkm.status_pkm',
					'identitas_mahasiswa.nama',
					'identitas_mahasiswa.nim',
					'identitas_mahasiswa.telepon',
					'prodi.nama_prodi',
					'fakultas.nama_singkat',
					'user_dikti.userdikti',
					'user_dikti.passdikti',
					'user_dikti.nilai',
					'user_dikti.status',
					'file_pkm.checked',
					'cek_administrasi.perekap',
					'cek_administrasi.cek1',
					'cek_administrasi.cek2',
					'cek_administrasi.cek3',
					'cek_administrasi.cek4',
					'cek_administrasi.cek5',
					'cek_administrasi.cek6',
					'cek_administrasi.cek7',
					'cek_administrasi.cek8',
					'cek_administrasi.cek9',
					'cek_administrasi.cek10',
					'cek_administrasi.cek11',
					'cek_administrasi.cek12',
					'cek_administrasi.cek13',
					'cek_administrasi.cek14',
					'cek_administrasi.cek15',
					'cek_administrasi.cek16',
					'cek_administrasi.cek17',
					'cek_administrasi.cek18',
					'cek_administrasi.cek19',
					'cek_administrasi.cek20',
					'cek_administrasi.cek21',
					'cek_administrasi.cek22',
					'cek_administrasi.cek23',
					'cek_administrasi.cek24',
					'cek_administrasi.cek25',
					'cek_administrasi.cek26'
				)

					->join('user_dikti', 'user_dikti.id_file', '=', 'file_pkm.id')

					->join('detail_file_pkm', 'detail_file_pkm.id_file', '=', 'file_pkm.id')

					->join('cek_administrasi', 'cek_administrasi.id_file_pkm', '=', 'file_pkm.id')

					->join('detail_pkm', 'detail_pkm.id', '=', 'detail_file_pkm.id_detail_pkm')

					->join('identitas_mahasiswa', 'identitas_mahasiswa.id', '=', 'detail_pkm.id_ketua')

					->join('prodi', 'prodi.id', '=', 'identitas_mahasiswa.id_prodi')

					->join('fakultas', 'fakultas.id', '=', 'prodi.id_fakultas')

					->join('tahun_anggaran_pkm', 'tahun_anggaran_pkm.id', '=', 'detail_pkm.id_tahun_anggaran_pkm')

					->where('tahun_anggaran_pkm.id', 14)

					->whereNotNull('file_pkm.ni1')

					->where('cek_administrasi.perekap', 'like', $saya)

					->orderBy('file_pkm.updated_at', 'ASC')

					->get();

				return View::make('perekap.cekadmininstrasi')->withData($data);

				break;




			default:

				App::abort(404);

				break;
		}
	}

	public function CekKode(Request $request)

	{
		$kodepkm = $request->get('kodepkm');

		$pkm = FilePKM::select(
			'skim_singkat',
			'id_skim_pkm',
			'kode_pkm', 
			'penilai_proposal', 
			'perekap_proposal'
		)

			->join('skim_pkm', 'skim_pkm.id', 'file_pkm.id_skim_pkm')

			->join('nilai_pkm', 'nilai_pkm.id_file_pkm', 'file_pkm.id')

			->join('tahun_pkm', 'tahun_pkm.id', 'file_pkm.id_tahun_pkm')

			->where('file_pkm.kode_pkm', 'like', $kodepkm)

			->where('tahun_pkm.aktif', '=', 1)

			->first();

		if (is_object($pkm)) {

			$retval = array_merge($pkm->toArray());

			return Response::json($retval);
		} else {

			return $pkm;
		}
	}

	public function CallCenter()
	{
		$mhs = Auth::user()->identitas_mahasiswa->id_prodi;
		$prodi = Prodi::whereid($mhs)->first();

		$jumlah = CallCenter::whereid_fakultas($prodi->id_fakultas)->count();
		$callcenter = CallCenter::whereid_fakultas($prodi->id_fakultas)->get();

		$response = new \stdClass();
		$response->jml = $jumlah;
		$response->cc = $callcenter;
		return $response;
	}
}
