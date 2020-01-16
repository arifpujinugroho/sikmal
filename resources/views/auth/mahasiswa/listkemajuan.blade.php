@extends('layout.master')

@section('title')
List PKM Mahasiswa Lolos Didanai
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-speed-meter bg-c-blue"></i>
                <div class="d-inline">
                    <h4>List Laporan Kemajuan</h4>
                    @if($modeupload > 0)
                    <span><strong>Mode Upload : <span class="text-success">Ada yang di Aktifkan</span> || Jumlah PKM yang Lolos Didanai : {{ $jml }}</strong></span>
                    @else
                    <span><strong>Mode Upload : <span class="text-danger">Tidak ada PKM yang Aktif</span> || Jumlah PKM yang Lolos Didanai : {{ $jml }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">List Laporan Kemajuan</a> </li>
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
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-striped table-bordered nowrap">
                            <thead>
                                <th width="12px">Tahun PKM</th>
                                <th width="16px">Skim PKM</th>
                                <th>Judul PKM</th>
                                <th width="14px">Laporan Kemajuan</th>
                                <th width="14px">File PPT</th>
                            </thead>
                            <tbody>
                                @if ($jml != 0)
                                @foreach ($list as $t)
                                <tr>
                                    <td class="text-center">{{ $t->tahun }}</td>
                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                    <td>{{ $t->judul }}</td>
                                    <td class="text-center">
                                        @if(is_null($t->file_laporan_kemajuan))
                                            @if($t->status_kemajuan == 1 && $t->aktif == 1)
                                                <button class="uploadKemajuan btn btn-mini btn-warning" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}"><i class="fa fa-upload"></i>Unggah Lap.Kemajuan</button>
                                            @else
                                                <label class="label label-danger">Belum Upload Lap. Kemajuan</label>
                                            @endif
                                        @else
                                            <div class="">
                                                <a href="{{url('mhs/downlapkemajuan')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i>Unduh</button></a>
                                                @if($t->status_kemajuan == 1 && $t->aktif == 1)
                                                <button class="uploadKemajuan btn btn-mini btn-default" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-toggle="tooltip" title="Upload Revisi Laporan Kemajuan"><i class="fa fa-upload"></i>Revisi</button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(is_null($t->file_ppt))
                                            @if($t->status_kemajuan == 1 && $t->aktif == 1)
                                                <button class="uploadPPT btn btn-mini btn-warning" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}"><i class="fa fa-upload"></i>Unggah PPT</button>
                                            @else
                                                <label class="label label-danger">Belum Upload PPT</label>
                                            @endif
                                        @else
                                            <div class="">
                                                <a href="{{url('mhs/downppt')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success" ><i class="fa fa-download" aria-hidden="true"></i>Unduh</button></a>
                                                @if($t->status_kemajuan == 1 && $t->aktif == 1)
                                                <button class="uploadPPT btn btn-mini btn-default" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-toggle="tooltip" title="Upload Revisi File PPT"><i class="fa fa-upload"></i>Revisi</button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center text-danger f-s-italic">Belum ada PKM lolos
                                        didanai</td>
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
<div class="modal fade" id="lap-kemajuan" tabindex="-1" role="dialog" aria-labelledby="uploadProposalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{url('mhs/uploadlapkemajuan')}}" class="form-validation" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="uploadProposalLabel">Upload Laporan Kemajuan</h4>
			</div>
			<div class="modal-body">
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


<div class="modal fade" id="file-ppt" tabindex="-1" role="dialog" aria-labelledby="uploadProposalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{url('mhs/uploadppt')}}" class="form-validation" method="post" enctype="multipart/form-data">
			@csrf
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Upload File PPT</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
						<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
					</div>
					<p class="help-block"><strong style="color: red">Format PPTX Maks 5 MB</strong></p>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="kode_token" id="kode_ppt">
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
        $('.uploadKemajuan').click(function(){
            $('#kode_kemajuan').val($(this).data('kode'));
            $('#lap-kemajuan').modal('show');
        });

        $('.uploadPPT').click(function(){
            $('#kode_ppt').val($(this).data('kode'));
            $('#file-ppt').modal('show');
        });
    </script>
@endsection

