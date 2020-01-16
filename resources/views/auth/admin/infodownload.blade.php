@extends('layout.master')

@section('title')
File Download
@stop

@section('header')
	<link rel="stylesheet" type="text/css" href="{{url('assets/css/simple-line-icons.cs')}}s">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/ionicons.css')}}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
<input type="hidden" id="token" value="{{ csrf_token() }}">
<div class="row">
    <!-- Client Map Start -->
    
    <div class="col-md-6">
        <div class="card client-map">
            <div class="card-block">
                <button class="btn btn-mini btn-primary f-right" id="link_modal" data-toggle="tooltip" title="Tambah Link"><i class="fa fa-plus" aria-hidden="true"></i></button>
                <h5><i class="fa fa-link"></i> Link Google Drive</h5>
                <span><small> File yang <strong class="text-primary">Lebih</strong> dari 10MB</small></span>
                <div class="table-responsive">
                    <table id="OkeDulu" class="table table-hover table-stripped nowrap" width="100%">
                        <thead>
                            <th>Judul</th>
                            <th class="text-center">Aksi</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card client-map">
            <div class="card-block">
                <h5><i class="fa fa-download"></i> File Download</h5>
                <span><small> File yang <strong class="text-danger">kurang</strong> dari 10MB</small></span>
                <div class="table-responsive">
                    <table id="cobaDulu" class="table table-hover table-stripped nowrap" width="100%">
                        <thead>
                            <th>Judul</th>
                            <th class="text-center">Download</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Client Map End -->
</div>
@endsection

@section('end')
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="tambahLink" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_link">Tambah Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_link">
                <div class="form-group">
                  <label for="judulfile">Judul File</label>
                  <input type="text" class="form-control" id="judulfile" placeholder="Judul File">
                </div>
                <div class="form-group">
                  <label for="judulfile">Link File</label>
                  <input type="url" class="form-control" id="linkfile" placeholder="Link File">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="tbh_link" class="btn btn-mini btn-primary">Tambah</button>
                <button type="button" id="edt_link" class="btn btn-mini btn-warning">Edit</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

    <!-- data-table js -->
    <script src="{{url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/jszip.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

<script>
$(document).ready(function(){

   var tablelink = $('#OkeDulu').DataTable( {
      "paging":   false,
      "ordering": false,
      "info":     false,
      "searching": false,
      "processing": true,
      "responsive" : true,
      'ajax': {
          'url':'{{url("/admin/alldownload")}}',
          'dataSrc': ''
      },
      'columns': [
         { 
             data: 'judul'
         },
         { data: null,
            render: function ( data, type, full,row) {
                return '<div class="btn-group">'+
                       '<a href="'+ data.link_file +'" target="_blank"><button type="submit" class="btn btn-mini btn-secondary" data-toggle="tooltip" title="'+ data.link_file+'"><i class="fa fa-link" aria-hidden="true"></i></button></a>'+
                       '<button type="button" data-id="'+ data.id+'" data-nama="'+ data.judul +'" data-link="'+ data.link_file +'" class="linkedit btn btn-mini btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>'+
                       '<button type="button" data-id="'+ data.id +'" data-nama="'+ data.judul +'" class="linkhapus btn btn-mini btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
                       '</div>';
            }
        },
      ]
    });

    $('#link_modal').click(function(){
        $('#judulfile').val('');
        $('#linkfile').val('');
        $('#id_link').val('');
        $('#tbh_link').show();
        $('#edt_link').hide();
        $('#title_link').html('Tambah Link');
        $('#tambahLink').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.linkedit', function() {
        $('#judulfile').val($(this).data('nama'));
        $('#linkfile').val($(this).data('link'));
        $('#id_link').val($(this).data('id'));
        $('#tbh_link').hide();
        $('#edt_link').show();
        $('#title_link').html('Edit Link');
        $('#tambahLink').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.linkhapus', function() {
        var code = $('#token').val();
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        swal({
            title: "Hapus "+nama,
            text: "Apakah ingin hapus "+nama+" ?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: "{{url('admin/hapuslink')}}",
                type: "POST",
                data: {
                    _token: code,
                    id: id,
                },
                success: function() {
                    swal(" Dihapus!", "Anda berhasil menghapus "+nama, "success");
                    tablelink.ajax.reload();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Diaktifkan!", "Silakan Coba Lagi", "error");
                }
            });
        });

    });


    $('#tbh_link').click(function(){
        var code = $('#token').val();
        var jdl = $('#judulfile').val();
        var link = $('#linkfile').val();

        if(jdl != "" && link != ""){
                
            $.post("{{ URL::to('/admin/tambahlink') }}", {
                _token: code,
                jdl: jdl,
                link: link
            })
            .done(function(result) {
                if ($.isEmptyObject(result)) {
                    new PNotify({
                        title: 'Gagal Simpan!',
                        text: 'Maaf Gagal Menyimpan data, Silakan Cek Kembali',
                        icon: 'icofont icofont-info-circle',
                        type: 'warning'
                    });
                    tablelink.ajax.reload();
                } else {
                    $('#tambahLink').modal('hide');
                    $('#ket').val("");
                    $('#tahun').val("");
                    new PNotify({
                        title: 'Link Berhasil Ditambah',
                        text:  jdl+' berhasil ditambah',
                        icon:  'icofont icofont-info-circle',
                        type:  'success'
                    });
                    tablelink.ajax.reload();
                }
            })
            .fail(function() {
                $('#tambahLink').modal('hide');
                new PNotify({
                    title: 'Kesalahan server!',
                    text: 'Ada yang tidak beres dengan server',
                    icon: 'icofont icofont-info-circle',
                    type: 'error'
                });
            }); 
        }else{
            new PNotify({
                title: 'Form ada yang kosong',
                text: 'Harap isi semua form input!',
                icon: 'icofont icofont-info-circle',
                type: 'error'
            });
        }
    });

    $('#edt_link').click(function(){
        var code = $('#token').val();
        var jdl = $('#judulfile').val();
        var link = $('#linkfile').val();
        var id = $('#id_link').val();

        if(id != "" && jdl != "" && link != ""){
                
            $.post("{{ URL::to('/admin/editlink') }}", {
                _token: code,
                jdl: jdl,
                link: link,
                id: id
            })
            .done(function(result) {
                if ($.isEmptyObject(result)) {
                    new PNotify({
                        title: 'Gagal Simpan!',
                        text: 'Maaf Gagal Mengedit Data, Silakan Cek Kembali',
                        icon: 'icofont icofont-info-circle',
                        type: 'warning'
                    });
                    tablelink.ajax.reload();
                } else {
                    $('#tambahLink').modal('hide');
                    $('#ket').val("");
                    $('#tahun').val("");
                    new PNotify({
                        title: 'Link Berhasil Diedit',
                        text:  jdl+' berhasil diedit',
                        icon:  'icofont icofont-info-circle',
                        type:  'success'
                    });
                    tablelink.ajax.reload();
                }
            })
            .fail(function() {
                $('#tambahLink').modal('hide');
                new PNotify({
                    title: 'Kesalahan server!',
                    text: 'Ada yang tidak beres dengan server',
                    icon: 'icofont icofont-info-circle',
                    type: 'error'
                });
            }); 
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





















<script>
$(document).ready(function(){
var table =  $('#cobaDulu').DataTable({
      'serverMethod': 'get',
      "responsive" : true,
      "processing": true,
      "searching": false,
      "paging":   false,
      "ordering": false,
      "info":     false,
      //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      'ajax': {
          'url':'{{url("/admin/filedownloadgdrive")}}',
          'dataSrc': ''
      },
      'columns': [
         { data: 'filename' },
         { data: null,
            render: function ( data, type, full,row) {
                return '<form action="{{url("/downfilegdrive")}}" method="get"><input type="hidden" name="path" value="'+ data.path +'"><input type="hidden" name="type" value="'+ data.mimetype +'"><input type="hidden" name="name" value="'+ data.name +'"><button type="submit" class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i></button></form>';
            }
        },
      ]
   });


});

</script>

@endsection