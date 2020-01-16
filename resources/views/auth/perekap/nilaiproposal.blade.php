@extends('layout.master')

@section('title')
Perekapan Proposal PKM
@endsection

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
	<div class="row align-items-end">
		<div class="col-lg-8">
			<div class="page-header-title">
				<i class="icofont icofont-speed-meter bg-c-blue"></i>
				<div class="d-inline">
					<h4>List Penilaian</h4>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="page-header-breadcrumb">
				<ul class="breadcrumb-title">
					<li class="breadcrumb-item">
						<a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
					</li>
					<li class="breadcrumb-item"><a href="#!">Penilaian Proposal</a> </li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- Page-header end -->

<!-- Page-body start -->
<div class="page-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					{{--<div class="f-right"><button id="tambahUpload" class="btn btn-success btn-sm">Tambah Rekapan</button></div>
                        </div>--}}
					<div class="card-block">
						<div class="dt-responsive table-responsive">
							<table id="daftar-proposal" class="table table-striped table-bordered ">
								<thead>
									<th width="16px">Aksi</th>
									<th>Nilai</th>
									<th>Status PKM</th>
									<th width="16px">Skim PKM</th>
									<th>Nama Mahasiswa</th>
									<th>Email</th>
									<th>Judul</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<!-- Basic Button table end -->
			</div>
		</div>
	</div>
	<!-- Page-body end -->
	@endsection




	@section('footer')
	<script>
		$(document).ready(function() {
			var table = $('#daftar-proposal').DataTable({
				///'dom': 'Bfrtip',
				'serverMethod': 'get',
				"paging": true,
				"ordering": true,
				//'responsive':true,
				"info": true,
				//"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
				'ajax': {
					'url': '{{url("/reviewer/nilai/dataproposal")}}',
					'dataSrc': '',
					'async': false,
				},
				'columns': [{
						data: null,
						render: function(data, type, full, row) {
							return '<div class="btn-group">' +
								'<a href="{{url('/reviewer/downpro')}}/' + data.id_file_pkm + '/' + data.file_proposal + '"><button class="btn btn-mini btn-default" data-toggle="tooltip" title="Download PKM ' + data.nama + '"><i class="fa fa-download" aria-hidden="true"></i></button></a>' +
								'<button data-skim="' + data.skim_singkat + '" data-id="' + data.id_file_pkm + '" data-note="' + data.note_proposal + '" data-nama="' + data.nama + '" data-n1="' + data.proposal1 + '" data-n2="' + data.proposal2 + '" data-n3="' + data.proposal3 + '" data-n4="' + data.proposal4 + '" data-n5="' + data.proposal5 + '" data-n6="' + data.proposal6 + '" data-n7="' + data.proposal7 + '" data-n8="' + data.proposal8 + '" data-n9="' + data.proposal9 + '" data-n10="' + data.proposal10 + '" class="NilaiPro btn btn-mini btn-success" data-toggle="tooltip" title="Nilai PKM ' + data.nama + '"><i class="fa fa-list"></i></button>' +
								'</div>';
						}
					},
					{
						data: null,
						render: function(data, type, full, row) {
							if (data.skim_singkat == "PKM-PSH" || data.skim_singkat == "PKM-PE") {
								var s1 = data.proposal1 * 15;
								var s2 = data.proposal2 * 15;
								var s3 = data.proposal3 * 10;
								var s4 = data.proposal4 * 20;
								var s5 = data.proposal5 * 15;
								var s6 = data.proposal6 * 10;
								var s7 = data.proposal7 * 5;
								var s8 = data.proposal8 * 5;
								var s9 = data.proposal9 * 5;

								var psh = s1 + s2 + s3 + s4 + s5 + s6 + s7 + s8 + s9;
								return psh;

							} else if (data.skim_singkat == "PKM-KC") {
								var c1 = data.proposal1 * 20;
								var c2 = data.proposal2 * 25;
								var c3 = data.proposal3 * 10;
								var c4 = data.proposal4 * 25;
								var c5 = data.proposal5 * 10;
								var c6 = data.proposal6 * 5;
								var c7 = data.proposal7 * 5;

								var kc = c1 + c2 + c3 + c4 + c5 + c6 + c7;
								return kc;

							} else if (data.skim_singkat == "PKM-K") {
								var k1 = data.proposal1 * 20;
								var k2 = data.proposal2 * 25;
								var k3 = data.proposal3 * 10;
								var k4 = data.proposal4 * 25;
								var k5 = data.proposal5 * 10;
								var k6 = data.proposal6 * 5;
								var k7 = data.proposal7 * 5;

								var k = k1 + k2 + k3 + k4 + k5 + k6 + k7;
								return k;
							} else if (data.skim_singkat == "PKM-M") {
								var m1 = data.proposal1 * 10;
								var m2 = data.proposal2 * 25;
								var m3 = data.proposal3 * 15;
								var m4 = data.proposal4 * 25;
								var m5 = data.proposal5 * 15;
								var m6 = data.proposal6 * 5;
								var m7 = data.proposal7 * 5;

								var m = m1 + m2 + m3 + m4 + m5 + m6 + m7;
								return m;

							} else if (data.skim_singkat == "PKM-T") {
								var t1 = data.proposal1 * 20;
								var t2 = data.proposal2 * 25;
								var t3 = data.proposal3 * 10;
								var t4 = data.proposal4 * 25;
								var t5 = data.proposal5 * 10;
								var t6 = data.proposal6 * 5;
								var t7 = data.proposal7 * 5;

								var t = t1 + t2 + t3 + t4 + t5 + t6 + t7;
								return t;
							}
						}
					},
					{
						data: null,
						render: function(data, type, full, row) {
							if (data.self == "Y") {
								return '<label class="label label-warning">Atas Kewajiban</label>';
							} else {
								return '<label class="label label-success">Keinginan Sendiri</label>';
							}
						}
					},
					{
						data: 'skim_singkat'
					},
					{
						data: null,
						render: function(data, type, full, row) {
							return data.nama + '<br>' + data.nama_prodi;
						}
					},
					{
						data: 'email'
					},
					{
						data: 'judul'
					},
				]
			});

			function setRadio(name, value) {
				$("input[name="+name+"]").val([value]);
			}




			$('#daftar-proposal tbody').on('click', '.NilaiPro', function() {
				//data value
				var skim = $(this).data('skim');
				var n1 = $(this).data('n1');
				var n2 = $(this).data('n2');
				var n3 = $(this).data('n3');
				var n4 = $(this).data('n4');
				var n5 = $(this).data('n5');
				var n6 = $(this).data('n6');
				var n7 = $(this).data('n7');
				var n8 = $(this).data('n8');
				var n9 = $(this).data('n9');
				var n10 = $(this).data('n10');
				$('#namamahasiswa').val($(this).data('nama'));
				$('#notenilai').val($(this).data('note'));
				$('#id_pkm').val($(this).data('id'));

				var f1 = $('#f1');
				var f2 = $('#f2');
				var f3 = $('#f3');
				var f4 = $('#f4');
				var f5 = $('#f5');
				var f6 = $('#f6');
				var f7 = $('#f7');
				var f8 = $('#f8');
				var f9 = $('#f9');
				var f10 = $('#f10');

				var l1 = $('#l1');
				var l2 = $('#l2');
				var l3 = $('#l3');
				var l4 = $('#l4');
				var l5 = $('#l5');
				var l6 = $('#l6');
				var l7 = $('#l7');
				var l8 = $('#l8');
				var l9 = $('#l9');
				var l10 = $('#l10');

				var v1 = $('#v1');
				var v2 = $('#v2');
				var v3 = $('#v3');
				var v4 = $('#v4');
				var v5 = $('#v5');
				var v6 = $('#v6');
				var v7 = $('#v7');
				var v8 = $('#v8');
				var v9 = $('#v9');
				var v10 = $('#v10');

				setRadio('v1',n1);
				setRadio('v2',n2);
				setRadio('v3',n3);
				setRadio('v4',n4);
				setRadio('v5',n5);
				setRadio('v6',n6);
				setRadio('v7',n7);
				setRadio('v8',n8);
				setRadio('v9',n9);

				$('#skim_pkm').val(skim);
				if (skim == "PKM-PSH" || skim == "PKM-PE") {
					l1.html('1. Gagasan (orisinalitas, unik, dan bermanfaat) (Bobot 15)');
					l2.html('<br>2. Perumusan masalah (fokus dan Atraktif) (Bobot 15)');
					l3.html('<br>3. Tinjauan Pustaka (state of the art) (Bobot 10)');
					l4.html('<br>4. Kesesuaian dan Kemutahiran Metode Penelitian (Bobot 20)');
					l5.html('<br>5. Kontribusi Perkembangan Ilmu dan Teknologi (Bobot 15)');
					l6.html('<br>6. Potensi Publikasi Artikel Ilmiah/HKI (Bobot 10)');
					l7.html('<br>7. Kemanfaatan (Bobot 5)');
					l8.html('<br>8. Jadwal dan Waktu Lengkap serta Jelas (Bobot 5)');
					l9.html('<br>9. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.removeAttr('hidden');
					f9.removeAttr('hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.attr('required', '');
					v9.attr('required', '');

				} else if (skim == "PKM-K") {
					l1.html('1. Gagasan (unik, dan bermanfaat) (Bobot 20)');
					l2.html('<br>2. Keunggulan Produk/Jasa (Bobot 5)');
					l3.html('<br>3. Peluang Pasar (Bobot 20)');
					l4.html('<br>4. Potensi Perolehan Profit (Bobot 20)');
					l5.html('<br>5. Potensi Keberlanjutan Usaha (Bobot 25)');
					l6.html('<br>6. Jadwal dan Waktu Lengkap serta Jelas (Bobot 5)');
					l7.html('<br>7. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.removeAttr('required');
					v9.removeAttr('required');

				} else if (skim == "PKM-KC") {
					l1.html('1. Gagasan (Orisinalitas, Unik, dan Manfaat Masa Depan) (Bobot 20)');
					l2.html('<br>2. Kemutakhiran IPTEK yang Diadopsi (Bobot 25)');
					l3.html('<br>3. Kesesuaian Metode Pelaksanaan (Bobot 10)');
					l4.html('<br>4. Kontribusi Produk Luaran terhadap Perkembangan IPTEK (Bobot 25)');
					l5.html('<br>5. Potensi Publikasi Artikel Ilmiah/HKI (Bobot 10)');
					l6.html('<br>6. Jadwal dan Waktu Lengkap serta Jelas (Bobot 5)');
					l7.html('<br>7. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.removeAttr('required');
					v9.removeAttr('required');

				} else if (skim == "PKM-M") {
					l1.html('1. Perumusan Masalah (Bobot 10)');
					l2.html('<br>2. Ketepatan Solusi (Fokus dan Atraktif) (Bobot 25)');
					l3.html('<br>3. Ketepatan Masyarakat Sasaran (Bobot 15)');
					l4.html('<br>4. Nilai Tambah untuk Masyarakat Sasaran (Bobot 25)');
					l5.html('<br>5. Keberlanjutan Program (Bobot 15)');
					l6.html('<br>6. Penjadwalan Kegiatan dan Personalia:Lengkap, Jelas, Waktu dan Personalianya Sesuai (Bobot 5)');
					l7.html('<br>7. Penyusaunan Anggaran Biaya:Lengkap, Rinci, Wajar dan Jelas Peruntukannya (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.removeAttr('required');
					v9.removeAttr('required');


				} else if (skim == "PKM-T") {
					l1.html('1. Kemutakhiran IPTEK yang diadopsi (Bobot 20)');
					l2.html('<br>2. Ketepatan Solusi (Fokus dan Atraktif) (Bobot 25)');
					l3.html('<br>3. Komitmen Kemitraan (Bobot 10)');
					l4.html('<br>4. Nilai Tambah bagi Mitra (Bobot 25)');
					l5.html('<br>5. Potensi Paten/HKI (Bobot 10)');
					l6.html('<br>6. Penjadwalan Kegiatan dan Personalia:Jadwal dan Waktu Lengkap serta Jelas (Bobot 5)');
					l7.html('<br>7. Penyusaunan Anggaran Biaya:Lengkap, Rinci, Wajar dan Jelas Peruntukannya (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.removeAttr('required');
					v9.removeAttr('required');


				} else if (skim == "PKM-GT") {
					l1.html('1. Format Tulis: </br> Ukuran kertas, tipografi, kerapihan ketik, tata letak, jumlah halaman </br> Penggunaan Bahasa Indonesia yang baik dan benar </br> Kesesuaian dengan format penulisan yang tercantum di Pedoman (Bobot 15)');
					l2.html('<br>2. Gagasan: </br> Kreativitas Gagasan </br> Kelayakan Implementasi (Bobot 40)');
					l3.html('<br>3. Sumber Informasi: </br> Kesesuaian sumber informasi dengan gagasan yang ditawarkan </br> Akurasi dan aktualisasi informasi (Bobot 25)');
					l4.html('<br>4. Kesimpulan: </br> Prediksi hasil implementasi gagasan (Bobot 20)');

					f5.attr('hidden', 'hidden');
					f6.attr('hidden', 'hidden');
					f7.attr('hidden', 'hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.removeAttr('required');
					v6.removeAttr('required');
					v7.removeAttr('required');
					v8.removeAttr('required');
					v9.removeAttr('required');

				} else if (skim == "PKM-AI") {
					l1.html('1. Judul: Kesesuaian isi dan judul artikel (Bobot 5)');
					l2.html('<br>2. Abstrak: Latar Belakang, Tujuan, Metode, Hasil, Kesimpulan, Kata Kunci (Bobot 10)');
					l3.html('<br>3. Pendahuluan: Persoalan yang mendasari pelaksanaan uraian dasar-dasar keilmuan yang mendukung kemutakhiran substansi pekerjaan (Bobot 10)');
					l4.html('<br>4. Tujuan: Menemukan teknik/konsep/metode sebagai jawaban atas persoalan (Bobot 5)');
					l5.html('<br>5. Metode: Kesesuaian dengan yang akan diselesaikan, pengembangan metode baru, penggunaan metode yang sudah ada (Bobot 25)');
					l6.html('<br>6. Hasil dan Pembahasan: Kumpulan dan kejelasan penampilan data proses/teknik pengolahan data, ketajaman analisis dan sintesis data, perbandingan hasil dengan hipotesis atau hasil sejenis sebelumnya (Bobot 30)');
					l7.html('<br>7. Kesimpulan: Tingkat ketercapaian hasil dengan tujuan (Bobot 10)');
					l8.html('<br>8. Daftar Pustaka: Ditulis dengan sistem havard (nama,tahun), sesuai dengan uraian sitasi, kemutakhiran pustaka (Bobot 5)');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.removeAttr('hidden');
					f9.attr('hidden', 'hidden');

					v5.attr('required', '');
					v6.attr('required', '');
					v7.attr('required', '');
					v8.attr('required', '');
					v9.removeAttr('required');

				} else if (skim == "PKM-GFK") {
					l1.html('1. Sistematika dan Kejelasan Alur Pikir (Bobot 15)');
					l2.html('<br>2. Penguasaan topik dan kreativitas solusi yang diajukan (Bobot 50)');
					l3.html('<br>3. Dinamika dan Kualitas Visualisasi Konten (Bobot 25)');
					l4.html('<br>4. Durasi maksimal 10 menit (Bobot 10)');

					f5.attr('hidden', 'hidden');
					f6.attr('hidden', 'hidden');
					f7.attr('hidden', 'hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

					v5.removeAttr('required');
					v6.removeAttr('required');
					v7.removeAttr('required');
					v8.removeAttr('required');
					v9.removeAttr('required');
				}

				$('#modalNilai').modal('show');
			});

			

		});

		$('#modalNilai').on('hidden.bs.modal', function(e) {
			$(this)
				.find("input,textarea,select")
				.val('')
				.end()
				.find("input[type=checkbox], input[type=radio]")
				.prop("checked", "")
				.end();
		});
	</script>


	<!-- data-table js -->
	<script src="{{url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/js/jszip.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/js/pdfmake.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/js/vfs_fonts.js')}}"></script>
	<script src="{{url('assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/extensions/buttons/js/buttons.flash.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/extensions/buttons/js/jszip.min.js')}}"></script>
	<script src="{{url('assets/pages/data-table/extensions/buttons/js/vfs_fonts.js')}}"></script>
	<script src="{{url('assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{url('assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

	<script src="{{url('assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js')}}"></script>
	@endsection

	@section('end')
	<!-- Modal -->
	<div class="modal fade" id="modalNilai" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form method="GET" action="{{ url('reviewer/inputnilai') }}" id="number_form">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">Nilai Proposal</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Nama Mahasiswa</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
								<input type="text" id="namamahasiswa" class="validate[required] form-control" placeholder="Nama Mahasiswa" readonly>
								<input type="hidden" name="id" id="id_pkm">
							</div>
						</div>

						<div class="form-group">
							<label class="jenispkm-block">Skim PKM</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								<input type="text" id="skim_pkm" class="validate[required] form-control" placeholder="Skim PKM" readonly>
							</div>
						</div>
						<hr>
						<div class="form-group" id="f1">
							<strong><label id="l1">L1</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" id="v1" name="v1" value="1" required>
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" name="v1" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" name="v1" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" name="v1" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" name="v1" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" class="v1" name="v1" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>

						<div class="form-group" id="f2">
							<strong><label id="l2">L2</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" id="v2" value="1" required>
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v2" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f3">
							<strong><label id="l3">L3</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" value="1" required>
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" id="v3" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v3" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f4">
							<strong><label id="l4">L4</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" id="v4" value="1" required>
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v4" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f5">
							<strong><label id="l5">L5</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" id="v5" value="1">
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v5" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f6">
							<strong><label id="l6">L6</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" id="v6" value="1">
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v6" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f7">
							<strong><label id="l7">L7</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" id="v7" value="1">
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v7" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f8">
							<strong><label id="l8">L8</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" id="v8" value="1">
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v8" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>
						<div class="form-group" id="f9">
							<strong><label id="l9">L9</label></strong>
							<div class="form-radio">
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" id="v9" value="1">
										<i class="helper"></i>1 (Buruk)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" value="2">
										<i class="helper"></i>2 (Sangat Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" value="3">
										<i class="helper"></i>3 (Kurang)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" value="5">
										<i class="helper"></i>5 (Cukup)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" value="6">
										<i class="helper"></i>6 (Baik)
									</label>
								</div>
								<div class="radio radio-inline">
									<label>
										<input type="radio" name="v9" value="7">
										<i class="helper"></i>7 (Sangat Baik)
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Catatan Penilai</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
								<textarea class="validate[required] form-control" rows="10" name="notenilai" id="notenilai" placeholder="Catatan Penilai" required></textarea>
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-sm btn-primary">Save</button>
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	@endsection