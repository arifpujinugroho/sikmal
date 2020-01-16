@extends('layout.master')

@section('title')
Status & Tahun PKM
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
                    <h6><Strong>Status PKM</Strong></h6>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel-status" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table id="statusPkm" class="table table-stripped table-hover" width="100%">
                            <thead>
                                <th>Tipe PKM</th>
                                <th>Mode Tambah</th>
                                <th>Mode Edit</th>
                                <th>Mode Proposal</th>
                                <th>Mode Lap Kemajuan</th>
                                <th>Mode Lap Akhir</th>
                                <th>Mode Hapus</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Basic Button table end -->
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h6><Strong>Tahun PKM</Strong></h6>
                    <button class="btn btn-mini btn-success" data-toggle="modal" data-target="#tambah-tahun"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Tahun PKM</button>
                    <div class="card-header-right">
                        <ul class="card-option">
                            <li id="reload-tabel-tahun" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table id="TahunPkm" class="table table-stripped table-hover" width="100%">
                            <thead>
                                <th>Tahun PKM</th>
                                <th>Keterangan</th>
                                <th>Mode</th>
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
<div class="modal fade" id="tambah-tahun" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tahun PKM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <label for="">Tahun <small class="text-danger">(4 Digit)</small></label>
                  <input type="number" class="form-control" id="tahun" aria-describedby="helpId" placeholder="Contoh : 1997">
                </div>
                <div class="form-group">
                  <label for="">Keterangan</small></label>
                  <input type="text" class="form-control" id="ket" aria-describedby="helpId" placeholder="Keterangan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="tambahtahun" class="btn btn-sm btn-success">Save</button>
                <button type="button" id="cancel-btn" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
 $(document).ready(function() {
        var status = $('#statusPkm').DataTable({
            //'dom': 'Bfrtip',
            'serverMethod': 'get',
            "paging": false,
            "searching": false,
            "processing": true,
            "ordering": false,
            'responsive':true,
            "info": false,
            'ajax': {
                'url': '{{url("/admin/alltipepkm")}}',
                'dataSrc': '',
            },
            'columns': [
                {
                    data: 'tipe'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_tambah == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="tambah" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-jenis="tambah" data-aktif="off" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_edit == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="edit" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="off" data-jenis="edit" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_upload == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="proposal" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="off" data-jenis="proposal" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_kemajuan == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="kemajuan" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+ 
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="off" data-jenis="kemajuan" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_akhir == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="akhir" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="off" data-jenis="akhir" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.status_hapus == 1){
                            return '<div class="btn-group">'+
                                    '<button type="button" class="btn btn-info btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="on" data-jenis="hapus" class="statusClick btn btn-info btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tipe+'" data-id="'+data.id+'" data-aktif="off" data-jenis="hapus" class="statusClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                }
            ]
        });

        $('#reload-tabel-status').click(function() {
            status.ajax.reload();
        });

        $('#statusPkm tbody').on('click', '.statusClick', function() {
            var code = "{{ csrf_token() }}";
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var aktif = $(this).data('aktif');
            var jenis = $(this).data('jenis');
            
            //tambah
            if(jenis == "tambah"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Tambah "+nama,
                        text: "Ingin menonaktifkan fitur tambah "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Tambah "+nama,
                        text: "Ingin mengaktifkan fitur tambah "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }
            
            //edit
            }else if(jenis == "edit"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Edit "+nama,
                        text: "Ingin menonaktifkan fitur edit "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Edit "+nama,
                        text: "Ingin mengaktifkan fitur edit "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }

            //proposal
            }else if(jenis == "proposal"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Proposal "+nama,
                        text: "Ingin menonaktifkan fitur upload proposal "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Proposal "+nama,
                        text: "Ingin mengaktifkan fitur upload proposal "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }
            
            //kemajuan
            }else if(jenis == "kemajuan"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Kemajuan "+nama,
                        text: "Ingin menonaktifkan fitur upload laporan kemajuan "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Kemajuan "+nama,
                        text: "Ingin mengaktifkan fitur upload laporan kemajuan "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }
            
            //akhir
            }else if(jenis == "akhir"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Akhir "+nama,
                        text: "Ingin menonaktifkan fitur upload laporan akhir "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Akhir "+nama,
                        text: "Ingin mengaktifkan fitur upload laporan akhir "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }
            
            //hapus
            }else if(jenis == "hapus"){
                if(aktif == "on"){
                    swal({
                        title: "Nonaktifkan Hapus "+nama,
                        text: "Ingin menonaktifkan fitur hapus "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Nonaktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Dinonaktifkan!", "Anda berhasil menonaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Dinonaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }else{
                    swal({
                        title: "Aktifkan Hapus "+nama,
                        text: "Ingin mengaktifkan fitur hapus "+nama+" ?",
                        type: "warning",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Aktifkan",
                        closeOnConfirm: false
                    }, function() {
                        $.ajax({
                            url: "{{url('admin/statuspkm')}}",
                            type: "POST",
                            data: {
                                _token: code,
                                id: id,
                                jenis: jenis,
                                aktif: aktif
                            },
                            success: function() {
                                swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                                status.ajax.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                            }
                        });
                    });
                }
            }
        });

 });
</script>

<script>
 $(document).ready(function() {
        var tahun = $('#TahunPkm').DataTable({
            //'dom': 'Bfrtip',
            'serverMethod': 'get',
            "paging": false,
            "searching": false,
            "processing": true,
            "ordering": false,
            'responsive':true,
            "info": false,
            'ajax': {
                'url': '{{url("/admin/alltahunpkm")}}',
                'dataSrc': '',
            },
            'columns': [
                {
                    data: 'tahun'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.aktif == 1){
                            return '<div class="btn-group">'+
                                    //'<button type="button" class="btn btn-success btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '<button type="button" style="cursor: default;" class="btn btn-block btn-primary btn-mini">ON</button>'+
                                    '</div>';
                        }else{
                            return '<div class="btn-group">'+
                                    '<button type="button" data-nama="'+data.tahun+'" data-id="'+data.id+'" data-aktif="off" class="tahunClick btn btn-default btn-mini">OFF</button>'+
                                    '<button type="button" class="btn btn-default btn-mini" disabled><i class="fa fa-fw"></i></button>'+
                                    '</div>';
                        }
                    }
                }
            ]
        });

        $('#reload-tabel-tahun').click(function() {
            tahun.ajax.reload();
        });

        $('#TahunPkm tbody').on('click', '.tahunClick', function() {
            var code = "{{ csrf_token() }}";
            var id = $(this).data('id');
            var nama = $(this).data('nama');
                    
            swal({
                title: "Aktifkan Tahun "+nama,
                text: "Ingin mengaktifkan tahun pkm "+nama+" ?",
                type: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonText: "Aktifkan",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: "{{url('admin/tahunpkm')}}",
                    type: "POST",
                    data: {
                        _token: code,
                        id: id
                    },
                    success: function() {
                        swal(" Diaktifkan!", "Anda berhasil mengaktifkan "+nama, "success");
                        tahun.ajax.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                    }
                });
            });

        });

        $('#tambahtahun').click(function(){
            var code = "{{ csrf_token() }}";
            var thn = $('#tahun').val();
            var ket = $('#ket').val();

            if(thn != "" && ket != ""){
                var p = thn.length;
                if(p == 4){
                    $.post("{{ URL::to('/admin/tambahtahunpkm') }}", {
                        _token: code,
                        thn: thn,
                        ket: ket
                    })
                    .done(function(result) {
                        if ($.isEmptyObject(result)) {
                            new PNotify({
                                title: 'Gagal Simpan!',
                                text: 'Maaf Gagal Menyimpan data, Silakan Cek Kembali',
                                icon: 'icofont icofont-info-circle',
                                type: 'warning'
                            });
                            tahun.ajax.reload();
                        } else {
                            $('#tambah-tahun').modal('hide');
                            $('#ket').val("");
                            $('#tahun').val("");
                            new PNotify({
                                title: 'Tahun Berhasil Ditambah',
                                text: 'Tahun PKM '+thn+' berhasil ditambah',
                                icon: 'icofont icofont-info-circle',
                                type: 'success'
                            });
                            tahun.ajax.reload();
                        }
                    })
                    .fail(function() {
                        $('#tambah-tahun').modal('hide');
                        new PNotify({
                            title: 'Kesalahan server!',
                            text: 'Ada yang tidak beres dengan server',
                            icon: 'icofont icofont-info-circle',
                            type: 'error'
                        });
                    }); 
                }else{
                    new PNotify({
                        title: 'Tahun tidak Sesuai',
                        text: thn+' Tidak Sesuai, Tahun Harus 4 Digit',
                        icon: 'icofont icofont-info-circle',
                        type: 'warning'
                    });
                }
            }else{
                new PNotify({
                    title: 'Form ada yang kosong',
                    text: 'Harap isi semua form input!',
                    icon: 'icofont icofont-info-circle',
                    type: 'error'
                });
            }
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
