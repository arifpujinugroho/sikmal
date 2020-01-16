@extends('layout.master')

@section('title')
List Akun Simbelmawa UNY
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
                    LIST Akun Simbelmawa
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
        </div>
        <div class="card-block">
            <div class="table-responsive">
                <table id="akunSimbel" class="table table-striped" width="100%">
                    <thead>
                        <th>Aksi</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nim</th>
                        <th>Status</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Pendanaan PKM</th>
                        <th>Prodi</th>
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
<div class="modal fade" id="edit-akun" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" name="file" id="file">
            <input type="hidden" name="nim" id="nim">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" id="nama" placeholder="Nama Mahasiswa" readonly>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" id="password" placeholder="Password Simbel" autofocus>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status-sim" id="status">
                        <option value="1">Pengajuan Proposal</option>
                        <option value="2">Lolos Proses Upload</option>
                        <option value="3">Lolos didanai</option>
                        <option value="4">Lolos Pimnas</option>
                        <option value="5">Juara 1 presentasi</option>
                        <option value="6">Juara 2 Presentasi</option>
                        <option value="7">Juara 3 Presentasi</option>
                        <option value="8">Juara Favorit Presentasi</option>
                        <option value="9">Juara 1 Poster</option>
                        <option value="10">Juara 2 Poster</option>
                        <option value="11">Juara 3 Poster</option>
                        <option value="12">Juara Favorit Poster</option>
                    </select>
                </div>
                <div class="form-group" id="dana">
                    <label>Pendanaan Lolos PKM</label>
                    <input type="number" class="form-control" id="dana_dikti" placeholder="Contoh : 12500000" autofocus>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveSimbel" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<script>
    $(document).ready(function() {
        var table = $('#akunSimbel').DataTable({
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
            "processing": true,
            "ordering": true,
            'responsive':true,
            "info": true,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'ajax': {
                'url': '{{url("/admin/datasimbel")}}/{{$tahunpkm}}/{{$tipepkm}}',
                'dataSrc': '',
            },
            'columns': [
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '<button data-nama="' + data.nama + '" data-file="' + data.id_file_pkm + '" data-nim="' + data.nim + '" data-status="' + data.status + '" data-password="' + data.pass_simbel + '" data-dana_dikti="'+data.dana_dikti+'" class="editSimbel btn btn-mini btn-warning" data-toggle="tooltip" title="Edit ' + data.nama + '"><i class="fa fa-edit"></i></button>'
                    }
                },
                {
                    data: 'nama'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '<p class="copyText" data-nim="' + data.nim + '">' + data.nim + '</p>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if (data.status == "1") {
                            return 'Pengajuan Proposal';
                        } else if (data.status == "2") {
                            return 'Lolos Proses Upload';
                        } else if (data.status == "3") {
                            return 'Lolos Didanai';
                        } else if (data.status == "4") {
                            return 'Lolos Pimnas';
                        } else {
                            return data.status;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '001038' + data.nim;
                    }
                },
                {
                    data: 'pass_simbel'
                },
                {
                    data: 'dana_dikti'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return data.nama_prodi + ' - ' + data.jenjang_prodi;
                    }
                },
            ]
        });

        

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });
        //setInterval(function() {
        //table.ajax.reload();
        //}, 500 );

        $('#akunSimbel tbody').on('click', '.editSimbel', function() {
            $('#file').val($(this).data('file'));
            $('#nim').val($(this).data('nim'));
            $('#nama').val($(this).data('nama'));
            $('#password').val($(this).data('password'));
            $('#dana_dikti').val($(this).data('dana_dikti'));
            $('#status').val($(this).data('status'));
            var stat = $(this).data('status');
            if(stat > 2){
                $('#dana').show();
            }else{
                $('#dana').hide();
            }
            $('#edit-akun').modal('show');
            $('#passwod').focus();
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
            var dana_dikti = $('#dana_dikti').val();

            $.get("{{ URL::to('/admin/updatesimbel') }}", {
                    file: file,
                    nim: nim,
                    status: status,
                    password: password,
                    dana_dikti: dana_dikti
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

        $('#status').on('change', function() {
		    var status = $(this).val();
            if(status > 2){
                $('#dana').show();
            }else{
                $('#dana').hide();
            }
        });


    });
</script>

<script>

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