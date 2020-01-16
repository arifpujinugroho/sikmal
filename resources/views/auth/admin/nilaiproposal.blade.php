@extends('layout.master')

@section('title')
Perekapan Proposal PKM {{$namatipe}} Tahun {{$namatahun}}
@endsection

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')

<!-- Page-body start -->
<div class="page-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
				<h5>List Penilaian {{$namatipe}} Tahun {{$namatahun}}</h5>
				</div>
					<div class="card-block">
						<div class="dt-responsive table-responsive">
							<table id="daftar-proposal" class="table table-striped table-bordered ">
								<thead>
									<th>Nilai</th>
									<th>Nama Reviewer</th>
									<th>Nama Mahasiswa</th>
									<th width="16px">Skim PKM</th>
									<th>Status PKM</th>
									<th>Judul</th>
								</thead>
							</table>
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
				'dom': 'Bfrtip',
        		'buttons': [{
        		    extend: 'excelHtml5',
        		    customize: function(xlsx) {
        		        var sheet = xlsx.xl.worksheets['sheet1.xml'];
        		        $('row c[r^="F"]', sheet).each(function() {
        		            if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
        		                $(this).attr('s', '20');
        		            }
        		        });
        		    }
        		}],
				'serverMethod': 'get',
				"paging": true,
				"ordering": true,
				//'responsive':true,
				"info": true,
				//"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
				'ajax': {
					'url': '{{url("/admin/dataproposal")}}/{{$tahunpkm}}/{{$tipepkm}}',
					'dataSrc': '',
				},
				'columns': [
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
						data: 'nama_dosen'
					},
					{
						data: null,
						render: function(data, type, full, row) {
							return data.nama + ' - '+data.nim+' - <br> ' + data.nama_prodi;
						}
					},
					{
						data: 'skim_singkat'
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
						data: 'judul'
					},
				]
			});
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
				<form method="POST" action="{{ url('reviewer/inputnilai') }}" id="number_form">
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
								<textarea class="validate[required] form-control" rows="3" name="notenilai" id="notenilai" placeholder="Catatan Penilai" required></textarea>
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