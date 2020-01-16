@extends('layout.master')

@section('title')
List Dosen UNY
@endsection

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('footer')
<script>
    $(document).ready(function() {
        var table = $('#listDosen').DataTable({
            "ordering": true,
            "info": false,
            'serverMethod': 'get',
            "paging": true,
            "processing": true,
            "responsive":true,
            "language": {
                "processing": "<strong class='text-danger'>Silakan Tunggu..</strong>"
            },
            'ajax': {
                'url': '{{url("/admin/datalistdosen")}}',
                'dataSrc': '',
            },
            'columns': [{
                    data: null,
                    render: function(data, type, full, row) {
                        return '<button data-nama="' + data.nama_dosen + '" data-id="' + data.id + '" data-nip="' + data.nip_dosen + '" data-nidn="' + data.nidn_dosen + '" data-nidk="' + data.nidk_dosen + '" data-password="' + data.simbel_akun + '" data-email="' + data.email_dosen + '" data-prodi="' + data.prodi_dosen + '" data-fakultas="' + data.nama_singkat + '" class="editSimbel btn btn-mini btn-warning" data-toggle="tooltip" title="Edit ' + data.nama_dosen + '"><i class="fa fa-edit"></i></button>'
                    }
                },
                {
                    data: 'nama_dosen'
                },
                {
                    data: 'simbel_akun'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if (data.nidn_dosen == "" || data.nidn_dosen == null) {
                            return '<label class="label label-danger">Belum ada NIDN</label>';
                        } else {
                            return data.nidn_dosen;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if (data.nidk_dosen == "" || data.nidk_dosen == null) {
                            return '<label class="label label-danger">Belum ada NIDK</label>';
                        } else {
                            return data.nidk_dosen;
                        }
                    }
                },
                {
                    data: 'email_dosen'
                },
                {
                    data: 'nip_dosen'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return data.nama_singkat + ' - ' + data.prodi_dosen;
                    }
                },
                {
                    data: 'keahlian'
                },
            ]
        });

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });

        $('#listDosen tbody').on('click', '.editSimbel', function() {
            $('#form-id').val($(this).data('id'));
            $('#form-nama').val($(this).data('nama'));
            $('#form-nip').val($(this).data('nip'));
            $('#form-nidn').val($(this).data('nidn'));
            $('#form-nidk').val($(this).data('nidk'));
            $('#form-pass').val($(this).data('password'));
            $('#form-email').val($(this).data('email'));
            $('#editDosen').modal('show');
            $('#form-pass').focus();
        });

        $('#saveDosen').click(function() {
            var id = $('#form-id').val();
            var nidn = $('#form-nidn').val();
            var nidk = $('#form-nidk').val();
            var pass = $('#form-pass').val();

            $.get("{{ URL::to('/admin/editdosen') }}", {
                    id: id,
                    nidn: nidn,
                    nidk: nidk,
                    pass: pass
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
                        $('#editDosen').modal('hide');
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
                    $('#editDosen').modal('hide');
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
@endsection



@section('content')
<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
                    <h5>List Dosen UNY</h5>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table id="listDosen" class="table table-striped table-bordered nowrap" width="100%">
                            <thead>
                                <th>Aksi</th>
                                <th>Nama Dosen</th>
                                <th>Pass Simbel</th>
                                <th>NIDN</th>
                                <th>NIDK</th>
                                <th>Email Dosen</th>
                                <th>NIP</th>
                                <th>Prodi Dosen</th>
                                <th>Keahlian</th>
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
<!-- Modal -->
<div class="modal fade" id="editDosen" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="form-id">
                <div class="form-group">
                    <label for="">Nama Dosen</label>
                    <input type="text" class="form-control" id="form-nama" placeholder="Nama Dosen" readonly>
                </div>
                <div class="form-group">
                    <label for="">NIP</label>
                    <input type="number" class="form-control" id="form-nip" placeholder="NIP Dosen" readonly>
                </div>
                <div class="form-group">
                    <label for="">Email Dosen</label>
                    <input type="email" class="form-control" id="form-email" placeholder="Email Dosen" readonly>
                </div>
                <div class="form-group">
                    <label for="">NIDN</label>
                    <input type="number" class="form-control" id="form-nidn" placeholder="NIDN Dosen">
                </div>
                <div class="form-group">
                    <label for="">NIDK</label>
                    <input type="number" class="form-control" id="form-nidk" placeholder="NIDK Dosen">
                </div>
                <div class="form-group">
                    <label for="">Password Simbel</label>
                    <input type="text" class="form-control" id="form-pass" placeholder="Pass Simbel">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveDosen" class="btn btn-sm btn-success">Simpan</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection