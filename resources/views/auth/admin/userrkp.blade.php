@extends('layout.master')

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('title')
Reviewer & Perekap
@endsection

@section('content')
<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reviewer</h4>
                    <button class="btn btn-sm btn-primary f-right" data-toggle="modal" data-target="#tambah-reviewer"><i class="fa fa-user-plus"></i> Tambah Reviewer</button>
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="reviewer-table" class="table table-stripped table-hover">
                            <thead>
                                <th>Nama Reviewer</th>
                                <th>NIDN/NIDK/NIP</th>
                                <th>Fakultas</th>
                                <th>Aksi</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Basic Button table end -->
        </div>
    </div>

    {{--Perekap--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Perekap</h4>
                    <button class="btn btn-sm btn-primary f-right" data-toggle="modal" data-target="#tambah-perekap"><i class="fa fa-user-plus"></i> Tambah Perekap</button>
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="perekap-table" class="table table-stripped table-hover">
                            <thead>
                                <th>Nama Perekap</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Aksi</th>
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

@section('end')
<!-- Tambah Perekap -->
<div class="modal fade" id="tambah-reviewer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="daftar-dosen" class="table table-hover table-striped">
                    <thead>
                        <th>Nama</th>
                        <th>Fakultas</th>
                        <th>Aksi</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

<script>
    //Khusus Tabel Reviewer
    $(document).ready(function() {
        var reviewer = $('#reviewer-table').DataTable({
            //'dom': 'Bfrtip',
            'serverMethod': 'get',
            "paging": true,
            "ordering": false,
            'responsive': true,
            "info": false,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'ajax': {
                'url': '{{url("/admin/datareviewer")}}',
                'dataSrc': '',
                'async': false,
            },
            'columns': [{
                    data: 'nama_dosen'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if (data.nidn_dosen == "" && data.nidk_dosen == "") {
                            return data.nip_dosen;
                        } else {
                            if (data.nidn_dosen != "") {
                                return data.nidk_dosen;
                            } else {
                                return data.nidn_dosen;
                            }
                        }
                    }
                },
                {
                    data: 'nama_singkat'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '<button data-nama="' + data.nama_dosen + '" data-id="' + data.id_user + '" class="hapusReviewer btn btn-mini btn-danger" data-toggle="tooltip" title="Hapus Reviewer ' + data.nama_dosen + '"><i class="fa fa-trash"></i></button>'
                    }
                },
            ]
        });

        var dosen = $('#daftar-dosen').DataTable({
            //'dom': 'Bfrtip',
            'serverMethod': 'get',
            "paging": true,
            "ordering": false,
            //'responsive':true,
            "info": false,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'ajax': {
                'url': '{{url("/admin/datadosen")}}',
                'dataSrc': '',
                'async': false,
            },
            'columns': [{
                    data: 'nama_dosen'
                },
                {
                    data: 'nama_fakultas'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '<button data-nama="' + data.nama_dosen + '" data-email="' + data.email_dosen + '" class="tambahReviewer btn btn-mini btn-success" data-toggle="tooltip" title="Add Reviewer"><i class="fa fa-user-plus"></i></button>'
                    }
                },
            ]
        });

        $('#reviewer-table tbody').on('click', '.hapusReviewer', function() {
            var code = "{{ csrf_token() }}";
            var nama = $(this).data('nama');
            var id = $(this).data('id');
            swal({
                title: "Hapus Reviewer " + nama + " ?",
                text: "Ingin menghapus " + nama + " dari Reviewer?",
                type: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonText: "Hapus " + nama,
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: "{{url('admin/hapusreviewer')}}",
                    type: "POST",
                    data: {
                        _token: code,
                        id: id
                    },
                    success: function() {
                        swal(nama + " Dihapus!", "Anda berhasil menghapus " + nama, "success");
                        //setTimeout(function () {
                        //	location.reload();
                        //}, 1500);
                        reviewer.ajax.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        swal("Error Hapus!", "Silakan Coba Lagi", "error");
                    }
                });
            });
        });

        $('#daftar-dosen tbody').on('click', '.tambahReviewer', function() {
            $('#cancel-btn').click();
            var code = "{{ csrf_token() }}";
            var nama = $(this).data('nama');
            var email = $(this).data('email');
            swal({
                title: "Tambah Reviewer "+nama+" ?",
                text: "Ingin menambah "+nama+" sebagai Reviewer?",
                type: "info",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonText: "Tambah Reviewer",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    url: "{{url('admin/tambahreviewer')}}",
                    type: "POST",
                    data: {_token : code, email: email},
                    success: function () {
                        swal(nama+" Ditambah!", "Anda berhasil menambah "+nama, "success");
                        //setTimeout(function () {
		    	        //	location.reload();
		    	        //}, 1500);
                        reviewer.ajax.reload();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Error!", "Silakan Coba Lagi", "error");
                    }
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