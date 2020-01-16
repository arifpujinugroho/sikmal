@extends('layout.master')

@section('title')
List Surat
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
                        <h4>List Surat</h4>
                        <span><strong>Jumlah Pengusulan yang ditemukan :</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List Surat</a> </li>
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
                            LIST Surat
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="surat-tbl" class="table table-striped">
                                    <thead>
                                        <th>No.</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Tipe Surat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                    <?php $n=1; ?>
                                        @foreach ($surat as $t)
                                            <tr>
                                                <td>{{ $n }}</td>
                                                <td>{{ TglIndo::Tgl_indo($t->tgl_pengajuan) }}</td>
                                                <td>{{ $t->nama }}</td>
                                                <td>
                                                    @if($t->tipe_surat == 1)
                                                    Observasi
                                                    @elseif($t->tipe_surat == 2)
                                                    Keterangan PKM                                                       
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($t->status_surat ==  1)
                                                    Pengajuan
                                                    @elseif($t->status_surat ==  2)
                                                    Lagi di Proses
                                                    @elseif($t->status_surat ==  3)
                                                    Pengajuan TTD
                                                    @elseif($t->status_surat ==  4)
                                                    Selesai
                                                    @elseif($t->status_surat ==  5)
                                                    Sudah Diambil
                                                    @endif
                                                </td>
                                                <td>
                                                    {{--<div class="btn-group">--}}
                                                        <button data-kode="{{Crypt::encrypt($t->id_surat)}}" data-skim="{{ $t->skim_singkat }}" data-nomer="{{ $t->nomer_surat }}" data-kepada="{{ $t->kepada_surat }}" data-tujuan="{{ $t->tujuan_surat }}" data-alamat="{{ $t->lamat_surat }}" data-durasi="{{ $t->durasi_surat }}" data-toggle="tooltip" title="Surat {{ $t->nama }}" class="info-surat btn btn-mini btn-success"><i class="icofont icofont-info-circle"></i></button>
                                                        {{--<button data-skim="{{ $t->skim_singkat }}" data-judul="{{ $t->judul }}" data-keyword="{{ $t->keyword }}" data-abstrak="{{ $t->abstrak }}" data-link="{{ $t->linkurl }}" data-dana="Rp. {{number_format($t->dana_pkm, 0, ".", ".")}},-" data-dosen="{{ $t->nama_dosen }}" data-nidn="{{ $t->nidn_dosen }}" data-toggle="tooltip" title="Download Docx {{ $t->nama }}" class="lihatPKM btn btn-mini btn-default"><i class="icofont icofont-file-word"></i></button>
                                                    </div>--}}
                                                </td>
                                            </tr>
                                            <?php $n++; ?>
                                        @endforeach
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


@section('footer')
<script>
$(document).ready(function() {
    $('#surat-tbl').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            text: 'Tambah Surat',
            action: function(e, dt, node, config) {
                window.location = "{{url('mhs/tambah-surat')}}";
            }
        }]
    });
});

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
