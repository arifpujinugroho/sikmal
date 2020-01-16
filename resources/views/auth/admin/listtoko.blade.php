@extends('layout.master')

@section('title')
List Toko
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
<div class="row">
    <!-- Client Map Start -->
    
    <div class="col-md-12">
        <div class="card client-map">
            <div class="card-block">
                <button class="btn btn-mini btn-primary f-right" id="tambah_toko"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Toko</button>
                <h5><i class="fa fa-building" aria-hidden="true"></i> List Toko</h5>
                <br>
                <div class="table-responsive">
                    <table id="OkeDulu" class="table table-hover table-stripped nowrap" width="100%">
                        <thead>
                            <th>Nama Toko</th>
                            <th>NPWP</th>
                            <th>SIUP</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('end')
	<div class="modal fade" id="upload-file" tabindex="-1" role="dialog" aria-labelledby="uploadProposalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="uploadLabel">Upload File</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
								<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
							</div>
							<p class="help-block"><strong style="color: red">Format JPG, PNG, GIF Maks 5 MB</strong></p>
						</div>
					</div>
					<div class="modal-footer">
                        <input type="hidden" id="tipefile">
						<button type="submit" id="uploadfile" class="btn btn-success btn-mini">Simpan</button>
						<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
    </div>
            
<!-- Modal -->
<div class="modal fade" id="tambahLink" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_link">Tambah Toko</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_link">
                <div class="form-group">
                  <label for="judulfile">Nama Toko</label>
                  <input type="text" class="form-control" id="namatoko" placeholder="Nama Toko">
                </div>
                <div class="form-group">
                  <label for="judulfile">Alamat Toko</label>
                  <input type="text" class="form-control" id="alamattoko" placeholder="Alamat">
                </div>
                <div class="form-group">
                  <label for="">Status Toko</label>
                  <select class="form-control" id="verifytoko">
                    <option value="">--Status Toko--</option>
                    <option value="1">Terpercaya</option>
                    <option value="0">Belum</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="tbh_toko" class="btn btn-mini btn-primary">Tambah</button>
                <button type="button" id="edt_toko" class="btn btn-mini btn-warning">Edit</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="lihatSurat" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labellihatToko">Lihat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body" id="lihatBody">
            </div>
            <div class="modal-footer">
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
      //"paging":   false,
      //"ordering": false,
      //"info":     false,
      //"searching": false,
      "processing": true,
      "responsive" : true,
      'ajax': {
          'url':'{{url("/admin/alltoko")}}',
          'dataSrc': ''
      },
      'columns': [
         { data: null,
            render: function ( data, type, full,row) {
                if(data.verify_toko == 1){
                return data.nama_toko+' <i class="fa fa-check-circle text-success" aria-hidden="true" data-toggle="tooltip" title="Terverifikasi"></i>';
                } else {
                    return data.nama_toko;
                }
            }
        },
        { data: null,
            render: function ( data, type, full,row) {
                if(data.npwp_toko == "" || data.npwp_toko == null){
                    return '<label data-tipe="upload" data-id="'+data.id+'" class="uploadNPWP label label-danger"><i class="fa fa-upload" aria-hidden="true"></i> Belum Ada NPWP</label>';
                } else {
                    if(data.verify_toko == 1){
                        return '<label data-nama="'+data.nama_toko+'" data-file="'+data.npwp_toko+'" class="downNPWP label label-success">NPWP <i class="fa fa-eye" aria-hidden="true"></i></label>';
                    } else {
                        return '<label data-nama="'+data.nama_toko+'" data-file="'+data.npwp_toko+'" class="downNPWP label label-success">NPWP <i class="fa fa-eye" aria-hidden="true"></i></label>'+
                               '<label data-tipe="reupload" data-id="'+data.id+'" class="uploadNPWP label label-warning" data-toggle="tooltip" title="Reupload NPWP"><i class="fa fa-upload" aria-hidden="true"></i></label>';
                    }
                }
            }
        },
        { data: null,
            render: function ( data, type, full,row) {
                if(data.siup_toko == "" || data.siup_toko == null){
                    return '<label data-tipe="upload" data-id="'+data.id+'" class="uploadSIUP label label-warning"><i class="fa fa-upload" aria-hidden="true"></i> Belum Ada SIUP</label>';
                } else {
                    if(data.verify_toko == 1){
                        return '<label data-nama="'+data.nama_toko+'" data-file="'+data.siup_toko+'" class="downSIUP label label-success">SIUP  <i class="fa fa-eye" aria-hidden="true"></i></label>';
                    }else {
                        return '<label data-nama="'+data.nama_toko+'" data-file="'+data.siup_toko+'" class="downSIUP label label-success">SIUP  <i class="fa fa-eye" aria-hidden="true"></i></label>'+
                               '<label data-tipe="reupload" data-id="'+data.id+'" class="uploadSIUP label label-warning" data-toggle="tooltip" title="Reupload SIUP"><i class="fa fa-upload" aria-hidden="true"></i></label>';
                    }
                }
            }
        },
        { 
            data: 'alamat_toko'
        },
        { data: null,
            render: function ( data, type, full,row) {
                return '<div class="btn-group">'+
                       '<button type="button" data-id="'+ data.id+'" data-nama="'+ data.nama_toko +'" data-alamat="'+ data.alamat_toko +'" data-verify="'+data.verify_toko+'" class="linkedit btn btn-mini btn-warning" data-toggle="tooltip" title="Edit '+data.nama_toko+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>'+
                       '<button type="button" data-id="'+ data.id +'" data-nama="'+ data.nama_toko +'" class="linkhapus btn btn-mini btn-danger" data-toggle="tooltip" title="Hapus '+data.nama_toko+'"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
                       '</div>';
            }
        },
      ]
    });

    $('#tambah_toko').click(function(){
        $('#namatoko').val('');
        $('#alamattoko').val('');
        $('#verifytoko').val('');
        $('#id_link').val('');
        $('#tbh_toko').show();
        $('#edt_toko').hide();
        $('#title_link').html('Tambah Toko');
        $('#tambahLink').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.linkedit', function() {
        $('#namatoko').val($(this).data('nama'));
        $('#alamattoko').val($(this).data('alamat'));
        $('#verifytoko').val($(this).data('verify'));
        $('#id_link').val($(this).data('id'));
        $('#tbh_toko').hide();
        $('#edt_toko').show();
        $('#title_link').html('Edit Toko');
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
                url: "{{url('admin/hapustoko')}}",
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


    $('#tbh_toko').click(function(){
        var code = $('#token').val();
        var nama = $('#namatoko').val();
        var alamat = $('#alamattoko').val();
        var verify = $('#verifytoko').val();

        if(nama != "" && alamat != "" && verify != ""){
                
            $.post("{{ URL::to('/admin/tambahtoko') }}", {
                _token: code,
                nama: nama,
                alamat: alamat,
                verify: verify
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
                    new PNotify({
                        title: 'Toko Berhasil Ditambah',
                        text:  nama+' berhasil ditambah',
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

    $('#edt_toko').click(function(){
        var code = $('#token').val();
        var id = $('#id_link').val();
        var nama = $('#namatoko').val();
        var alamat = $('#alamattoko').val();
        var verify = $('#verifytoko').val();

        if(nama != "" && alamat != "" && verify != ""){
                
            $.post("{{ URL::to('/admin/edittoko') }}", {
                _token: code,
                id: id,
                nama: nama,
                alamat: alamat,
                verify: verify
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
                    new PNotify({
                        title: 'Toko Berhasil Diedit',
                        text:  nama+' berhasil diedit',
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

    $('#OkeDulu tbody').on('click', '.downNPWP', function() {
        var nama = $(this).data('nama');
        var link = $(this).data('file');

        $('#labellihatToko').html('NPWP '+nama);
        $('#lihatBody').html('<img src="{{url("/storage/files/surat")}}/'+link+'" class="img-fluid" alt="npwp '+nama+'">');
        $('#lihatSurat').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.downSIUP', function() {
        var nama = $(this).data('nama');
        var link = $(this).data('file');

        $('#labellihatToko').html('SIUP '+nama);
        $('#lihatBody').html('<img src="{{url("/storage/files/surat")}}/'+link+'" class="img-fluid" alt="siup '+nama+'">');
        $('#lihatSurat').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.uploadNPWP', function() {
        var tipe = $(this).data('tipe');
        if(tipe == "upload"){
            $('#uploadLabel').html('Unggah NPWP');
        } else{
            $('#uploadLabel').html('Unggah Ulang NPWP');
        }
        $('#tipefile').val('npwp');
        $('#id_link').val($(this).data('id'));
        $('#upload-file').modal('show');
    });

    $('#OkeDulu tbody').on('click', '.uploadSIUP', function() {
        var tipe = $(this).data('tipe');
        if(tipe == "upload"){
            $('#uploadLabel').html('Unggah SIUP');
        } else{
            $('#uploadLabel').html('Unggah Ulang SIUP');
        }
        $('#tipefile').val('siup');
        $('#id_link').val($(this).data('id'));
        $('#upload-file').modal('show');
    });


    $('#uploadfile').click(function(){
        var code = $('#token').val();
        var id = $('#id_link').val();
        var tipe = $('#tipefile').val();
        var file = document.getElementById("file").files[0];
        var formdata = new FormData();
        formdata.append("file", file);
        formdata.append("_token", code);
        formdata.append("tipe", tipe);
        formdata.append("id", id);
        
        // proses upload via AJAX disubmit ke 'URLNYA'
        // selama proses upload, akan menjalankan progressHandler()
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.open("POST", "http://pkm.kemahasiswaan.uny.ac.id/admin/uploadsurat/", true);
        ajax.send(formdata);
        ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            new PNotify({
                title: 'Berhasil',
                text:  'File berhasil diupload',
                icon:  'icofont icofont-info-circle',
                type:  'success'
            });
            tablelink.ajax.reload();
            $('#upload-file').modal('hide');
          }
        };
    });
    
});
</script>


<script>

function progressHandler(event){
    // hitung prosentase
    var percent = (event.loaded / event.total) * 100;
    // menampilkan prosentase ke komponen id 'progressBar'
    document.getElementById("progressBar").value = Math.round(percent);
    // menampilkan prosentase ke komponen id 'status'
    document.getElementById("status").innerHTML = Math.round(percent)+"% telah terupload";
    // menampilkan file size yg tlh terupload dan totalnya ke komponen id 'total'
    document.getElementById("total").innerHTML = "Telah terupload "+event.loaded+" bytes dari "+event.total;
    }
</script>


<script>
function uploadFile() {
    var code = $('#token').val();
    var id = $('#id_link').val();
    var file = document.getElementById("file").files[0];
    var formdata = new FormData();
    formdata.append("file", file);
    formdata.append("_token", code);
    formdata.append("id", id);
     
    // proses upload via AJAX disubmit ke 'URLNYA'
    // selama proses upload, akan menjalankan progressHandler()
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.open("POST", "http://pkm.kemahasiswaan.uny.ac.id/admin/uploadfile/", true);
    ajax.send(formdata);
    ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        $('#upload-file').modal('hide');
    }
  };
}
 
function progressHandler(event){
    // hitung prosentase
    var percent = (event.loaded / event.total) * 100;
    // menampilkan prosentase ke komponen id 'progressBar'
    document.getElementById("progressBar").value = Math.round(percent);
    // menampilkan prosentase ke komponen id 'status'
    document.getElementById("status").innerHTML = Math.round(percent)+"% telah terupload";
    // menampilkan file size yg tlh terupload dan totalnya ke komponen id 'total'
    document.getElementById("total").innerHTML = "Telah terupload "+event.loaded+" bytes dari "+event.total;
}

</script>








<script>
    
    (function($) {
        $.fn.checkFileType = function(options) {
            var defaults = {
                allowedExtensions: [],
                success: function() {},
                error: function() {}
            };
            options = $.extend(defaults, options);

            return this.each(function() {

                $(this).on('change', function() {
                    var value = $(this).val(),
                        file = value.toLowerCase(),
                        extension = file.substring(file.lastIndexOf('.') + 1);

                    if ($.inArray(extension, options.allowedExtensions) == -1) {
                        options.error();
                        $(this).focus();
                    } else {
                        options.success();

                    }

                });

            });
        };

    })(jQuery);

    var uploadField = document.getElementById("file");
    uploadField.onchange = function() {
        if (this.files[0].size > 5055650) {
            new PNotify({
                title: 'File Oversize',
                text: 'Maaf, File Max 5MB ',
                type: 'error'
            });
            console.log("file size = " + this.files[0].size + "/5000050")
            this.value = "";
        };
    };

    $(function() {
        $('#file').checkFileType({
            allowedExtensions: ['jpg','jpeg','png','gif'],
            error: function() {
                new PNotify({
                    title: 'File not Image',
                    text: 'Maaf, hanya type Gambar yang diupload ',
                    type: 'error'
                });
                document.getElementById("file").value = "";
            }
        });
    });
</script>

@endsection