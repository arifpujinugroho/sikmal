@extends('layout.master')

@section('title')
List Download PKM UNY
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
                        <h4>List Download PKM UNY</h4>
                        <span><strong>Daftar File PKM yang di download</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">lo</a> </li>
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
                            LIST Download PKM
                            {{--<form action="">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <input type="text" name="keyword" class="form-control" placeholder="Search..." value="{{ request('keyword') }}">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>--}}
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="excel-bg" class="table table-striped">
                                    <thead>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim</th>
                                        <th>Judul</th>
                                        <th>Proposal</th>
                                        <th>Lap. Kemajuan</th>
                                        <th>Lap. Akhir</th>
                                        <th>File PPT</th>
                                        <th>Poster</th>
                                        <th>Artikel</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td>
                                                        @if(is_null($t->file_proposal))
                                                            <label class="label label-danger">Belum Upload Proposal</label><br>
                                                            @if($t->status_upload == "1" && $t->aktif == "1")
                                                            <button data-mhs="{{ $t->id_mahasiswa }}" data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadProposal btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload Proposal</button>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downpro')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Proposal</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_laporan_kemajuan))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload Lap. Kemajuan</label><br>
                                                                @if($t->status_kemajuan == "1" && $t->aktif == "1")
                                                                <button data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadKemajuan btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload Lap.Kemajuan</button>
                                                                @endif
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downlapkem')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Lap.Kemajuan</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_laporan_akhir))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload Lap. Akhir</label><br>
                                                                @if($t->status_akhir == "1" && $t->aktif == "1")
                                                                <button data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadAkhir btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload Lap.Akhir</button>
                                                                @endif
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downlapakh')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Lap.Akhir</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_ppt))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload PPT</label><br>
                                                                <button data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadPPT btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload PPT</button>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downppt')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh File PPT</button></a>
                                                        @endif
                                                    </td>
                                                    <td>   
                                                        @if(is_null($t->poster))
                                                            @if ($t->status == "4")
                                                                <label class="label label-danger">Belum Upload Poster</label><br>
                                                                <button data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadPoster btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload Poster</button>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos Pimnas</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downposter')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Poster</button></a><br>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_artikel))
                                                            @if ($t->status == "4")
                                                                <label class="label label-danger">Belum Upload Artikel</label><br>
                                                                <button data-nama="{{ $t->nama }}" data-kode="{{Crypt::encrypt($t->id_file_pkm)}}" class="uploadArtikel btn btn-mini btn-default"><i class="fa fa-upload"></i> Upload Artikel</button>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos Pimnas</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('admin/downartikel')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Poster</button></a><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center f-s-italic">Belum ada Usulan PKM</td>
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
{{--Upload Proposal--}}
<div class="modal fade" id="upload-proposal" tabindex="-1" role="dialog" aria-labelledby="uploadProposalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{url('admin/uploadproposal')}}" class="form-validation" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="uploadProposalLabel">Upload Proposal</h4>
			</div>
			<div class="modal-body">
                <h6 id="nama-proposal"></h6>
				<div class="form-group">
					<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
						<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
					</div>
					<p class="help-block"><strong style="color: red">Format PDF Maks 5 MB</strong></p>
				</div>
			</div>
			<div class="modal-footer">
                <input type="hidden" name="id_mhs" id="mhs_proposal">
                <input type="hidden" name="kode_token" id="kode_proposal">
				<button type="submit" class="btn btn-success btn-mini">Simpan</button>
				<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

{{--upload lap Kemajuan--}}
<div class="modal fade" id="upload-kemajuan" tabindex="-1" role="dialog" aria-labelledby="uploadKemajuanLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{url('admin/uploadkemajuan')}}" class="form-validation" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="uploadKemajuanLabel">Upload Lap. Kemajuan</h4>
			</div>
			<div class="modal-body">
                <h6 id="nama-kemajuan"></h6>
				<div class="form-group">
					<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
						<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
					</div>
					<p class="help-block"><strong style="color: red">Format PDF Maks 5 MB</strong></p>
				</div>
			</div>
			<div class="modal-footer">
                <input type="hidden" name="kode_token" id="kode_kemajuan">
				<button type="submit" class="btn btn-success btn-mini">Simpan</button>
				<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

{{--upload laporan akhir--}}
<div class="modal fade" id="upload-akhir" tabindex="-1" role="dialog" aria-labelledby="uploadAkhirLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{url('admin/uploadakhir')}}" class="form-validation" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="uploadAkhirLabel">Upload Proposal</h4>
			</div>
			<div class="modal-body">
                <h6 id="nama-akhir"></h6>
				<div class="form-group">
					<div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
						<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
					</div>
					<p class="help-block"><strong style="color: red">Format PDF Maks 5 MB</strong></p>
				</div>
			</div>
			<div class="modal-footer">
                <input type="hidden" name="kode_token" id="kode_akhir">
				<button type="submit" class="btn btn-success btn-mini">Simpan</button>
				<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('footer')

<script>
    $('.uploadProposal').click(function(){
        var nama = $(this).data('nama');
        $('#nama-proposal').html('<h5 class="text-primary">Upload Proposal '+nama+'</h5>');
        $('#kode_proposal').val($(this).data('kode'));
        $('#mhs_proposal').val($(this).data('mhs'));
        $('#upload-proposal').modal('show');
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
