@extends('layout.master')

@section('title')
List Set Penilaian PKM
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
                    <h4>List Set Penilaian PKM</h4>
                    <span><strong>Penyettingan Penilaian PKM</strong></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Nilai PKM</a> </li>
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
                    LIST Set Penilaian PKM
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="pkm-tabel" class="table table-striped">
                            <thead>
                                <th>Aksi</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nim</th>
                                <th>Reviewer</th>
                                <th>Skim PKM</th>
                                <th>Judul</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page-body end -->
@endsection

@section('end')
<!-- Modal -->
<div class="modal fade" id="TambahReviewer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Reviewr</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{ url('admin/inputrev') }}" id="number_form">
					@csrf
                    <input type="hidden" name="id" id="id">

                    <div class="form-group">
                      <label for="namaPKM">Nama Mahasiswa</label>
                      <input type="text" name="namaPKM" id="namaPKM" class="form-control" placeholder="Nama Mahasiswa" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label>Tipe PKM</label>
                        <div class="input-group">
                            <select class="validate[required] form-control" name="email" id="email">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dsn as $t_p)
                                <option value="{{ $t_p->email_dosen }}">{{ $t_p->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<script>
    $(document).ready(function() {
        var table = $('#pkm-tabel').DataTable({
            ///'dom': 'Bfrtip',
            'serverMethod': 'get',
            "paging": true,
            "ordering": true,
            //'responsive':true,
            "info": true,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'ajax': {
                'url': '{{url("/admin/datapenil")}}/{{$tahunpkm}}/{{$tipepkm}}',
                'dataSrc': '',
                'async': false,
            },
            'columns': [{
                    data: null,
                    render: function(data, type, full, row) {
                        return '<div class="btn-group">'+
                        '<button data-nama="' + data.nama + '" data-id="' + data.id_file_pkm + '" data-nim="' + data.nim + '"  class="inputDosen btn btn-mini btn-warning" data-toggle="tooltip" title="Edit ' + data.nama + '"><i class="fa fa-edit"></i></button>'+
                        '<a href="{{url('admin/print')}}/'+data.id_file_pkm+'" target="_blank"><button class="btn btn-mini btn-default"><i class="icofont icofont-print"></i></button></a>'+
                        '</div>'
                    }
                },
                {
                    data: 'nama'
                },
                {
                    data: 'nim'
                },
                {
                    data: 'penilai_proposal'
                },
                {
                    data: 'skim_singkat'
                },
                {
                    data: 'judul'
                },
            ]
        });

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });

        $('#pkm-tabel tbody').on('click', '.inputDosen', function() {
            $('#id').val($(this).data('id'));
            $('#namaPKM').val($(this).data('nama'));
            $('#TambahReviewer').modal('show');
        });

        $('#akunSimbel tbody').on('click', '.copyText', function() {

            var copyText = $(this).data('nim');
            document.execCommand("copy", copyText);
            console.log(copyText);

        });

        $('#saveSimbel').click(function() {
            var file = $('#file').val();
            var nim = $('#nim').val();
            var password = $('#password').val();
            var status = $('#status').val();

            $.get("{{ URL::to('/admin/updatesimbel') }}", {
                    file: file,
                    nim: nim,
                    status: status,
                    password: password
                })
                .done(function(result) {
                    if ($.isEmptyObject(result)) {
                        new PNotify({
                            title: 'Gagal Simpan!',
                            text: 'Maaf Gagal Menyimpan data, silakan Refresh halaman',
                            icon: 'icofont icofont-info-circle',
                            type: 'warning'
                        });
                        table.ajax.reload();
                    } else {
                        $('#edit-akun').modal('hide');
                        new PNotify({
                            title: 'Berhasil di update',
                            text: 'Data Simbelmawa berhasil diupdate',
                            icon: 'icofont icofont-info-circle',
                            type: 'success'
                        });
                        table.ajax.reload();
                    }
                })
                .fail(function() {
                    $('#edit-akun').modal('hide');
                    new PNotify({
                        title: 'Kesalahan server!',
                        text: 'Ada yang tidak beres dengan server',
                        icon: 'icofont icofont-info-circle',
                        type: 'error'
                    });
                });
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