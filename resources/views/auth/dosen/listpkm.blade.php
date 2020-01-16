@extends('layout.master')

@section('title')
List PKM 
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
                        <h4>List PKM UNY</h4>
                        <span><strong>Jumlah Pengusulan yang ditemukan : {{ $jml }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List PKM</a> </li>
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
                            LIST PKM
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="excel-bg" class="table table-striped">
                                    <thead>
                                        <th>Skim PKM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim</th>
                                        <th>Judul</th>
                                        <th>Dosen</th>
                                        <th>Usulan</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->skim_singkat }}</td>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td>{{ $t->nama_dosen }}</td>
                                                    @if ($t->self == "N")
                                                        <td>
                                                            <h6>
                                                            <label class="label label-success">Keinginan Sendiri</label>
                                                            </h6>
                                                        </td>
                                                    @else
                                                    <td>
                                                        <h6>
                                                        <label class="label label-warning">Atas Kewajiban</label>
                                                        </h6>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center f-s-italic">Belum ada Usulan PKM</td>
                                            </tr>
                                        @endif
                                    </tbody>
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

@section('end')
<div class="modal fade" id="lihat-pkm" tabindex="-1" role="dialog" aria-labelledby="lihatPKMLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="lihatPKMLabel">Lihat Data PKM</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
                    <label>Judul</label>
					<div class="input-group">
						<input type="text" id="judul" class="validate[required] form-control" placeholder="Judul PKM" readonly>
					</div>
				</div>
				<div class="form-group">
                    <label>Keyword</label>
					<div class="input-group">
						<input type="text" id="keyword" class="validate[required] form-control" placeholder="Keyword PKM" readonly>
					</div>
				</div>
				{{--@if ($pkm->id_tipe_pkm == "2" || $pkm->id_tipe_pkm == "3" || $pkm->id_tipe_pkm == "4")--}}
				<div id="form-abstrak" class="form-group">
					<label>Abstrak</label>
					<div class="input-group">
						<textarea class="validate[required] form-control" id="abstrak" cols="30" readonly></textarea>
					</div>
				</div>
				{{--@endif
				@if($pkm->id_tipe_pkm == "3")--}}
				<div id="form-linkurl" class="form-group">
						<label>Link URL</label>
						<div class="input-group">
							<input type="url"  id="linkurl" class="validate[required] form-control" placeholder="URL Video" readonly>
						</div>
				</div>
				{{--@endif--}}
				<div class="form-group">
						<label>Dana Pengajuan PKM</label>
						<div class="input-group">
							<input type="text"  id="danapkm" class="validate[required] form-control" placeholder="Dana PKM" readonly>
						</div>
				</div>
				<div class="form-group">
					<label>Nama Dosen</label>
					<div class="input-group">
                        <input type="text" readonly id='nama_dosen' class="disabled validate[required] form-control" placeholder="Nama Dosen Pendamping">
					</div>
				</div>
				<div class="form-group">
					<label>NIDN Dosen</label>
					<div class="input-group">
						<input type="text" readonly id="nidn_dosen" class="disabled validate[required] form-control" placeholder="NIDN Dosen Pendamping">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalAnggota" tabindex="-1" role="dialog" aria-labelledby="modalAnggota" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Anggota</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script>
    $('.anggota-pkm').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: '{{ URL::to('/admin')}}/anggota-pkm',
			type: 'get',
			dataType: 'json',
			data: {kode: $(this).data('kode')},
		})
		.done(function(data) {
			content = $('<div></div>');
			content.append('<h5 class="text-danger">Ketua</h5><br>');
			content.append('<p>NIM : '+data.ketua.nim+'</p>');
			content.append('<p>Nama : '+data.ketua.nama+'</p>');
			content.append('<p>Telp. : '+data.ketua.telepon+'</p>');
			content.append('<p>Prodi : '+data.ketua.nama_prodi+' - '+data.ketua.jenjang_prodi+'</p>');
			content.append('<p>Fakultas : '+data.ketua.nama_fakultas+'</p>');
			content.append('<br>');
			$.each(data.anggota, function(index, val) {
				content.append('<hr>');
				content.append('<h5>Anggota '+ (index+1) +'</h5>');
				content.append('<p>NIM : '+val.nim+ '</p>');
				content.append('<p>Nama : '+val.nama+ '</p>');
				content.append('<p>Telp. : '+val.telepon+ '</p>');
				content.append('<p>Prodi : '+val.nama_prodi+' - '+val.jenjang_prodi+'</p>');
				content.append('<p>Fakultas : '+val.nama_fakultas+ '</p>');
				content.append('<br>');
			});
			$('#modalAnggota .modal-body').html(content);
			$('#modalAnggota').modal('show');
		})
		.fail(function() {
			console.log("error");
		});
	});


    $('.lihatPKM').click(function(){
        $('#form-abstrak').hide();
        $('#form-linkurl').hide();
        var skim = $(this).data('skim');
        
        $('#judul').val($(this).data('judul'));
        $('#keyword').val($(this).data('keyword'));
        $('#abstrak').val($(this).data('abstrak'));
        $('#linkurl').val($(this).data('link'));
        $('#danapkm').val($(this).data('dana'));
        $('#nama_dosen').val($(this).data('dosen'));
        $('#nidn_dosen').val($(this).data('nidn'));
        
        if(skim == "PKM-GT" || skim == "PKM-AI" || skim == "PKM-GFK" || skim == "SUG"){
            $('#form-abstrak').show(); 
        }
        if(skim == "PKM-GFK"){
            $('#form-linkurl').show();
        }
        $('#lihat-pkm').modal('show');
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
