@extends('guest.front-master')

@section('title')
Registrasi
@stop

@section('header')	
<link rel="stylesheet" type="text/css" href="{{url('assets/css/validationEngine.jquery.css')}}">
@endsection

@section('content')
    {{-- Page-header start --}}
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-file-code bg-c-blue"></i>
                    <div class="d-inline">
                    <h4>Registrasi Akun</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">
                        <i class="icofont icofont-home"></i>
                    </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Registrasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
	{{-- Page-header end --}}

	<div class="alert alert-danger alert-dismissible" id="notis-danger">
	</div>
	

    {{-- Page-body start --}}
    <div class="page-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <form method="POST" action="{{ url('daftar') }}" class="form-validation" id="number_form"  enctype="multipart/form-data">
                    @csrf
					<input type="hidden" name="kode" id="kode">
                    <div class="form-group">
							<label>Email Mahasiswa</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
							<input type="text" name="email" id="email" class="validate[required] form-control" placeholder="username@student.uny.ac.id">
							<span class="input-group-addon cursor-hand" id="search">Cari <i class="fa fa-search fa-fw"></i></span>
							<span class="input-group-addon cursor-hand" onclick="reset_form()" id="reset">Reset Form</span>
						</div>
						<p class="help-block"><strong>Silahkan ketik Email UNY dan klik tombol cari terlebih dahulu.</strong></p>
					</div>
						<div class="form-group">
							<label>Nama Mahasiswa <small>(otomatis terisi)</small></label>
							<div class="input-group">
								<input type="text" name="nama" id="nama" class="validate[required] form-control" placeholder="Nama Mahasiswa (Terisi Otomatis)" readonly>
							</div>
						</div>
						<div class="form-group">
							<label>Prodi Mahasiswa <small>(otomatis terisi)</small></label>
							<div class="input-group">
								<input type="text" id="prodi" class="validate[required] form-control" placeholder="Prodi Mahasiswa (Terisi Otomatis)" readonly>
							</div>
						</div>
						<hr>
					<fieldset id="tambahan" disabled>
							<div class="form-group">
								<label>Password <strong class="text-danger">*</strong></label>
								<div class="input-group">
									<input type="password" name="password" id="password" class="validate[required,minSize[6]] form-control" placeholder="Password" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label>Ulangi Password <strong class="text-danger">*</strong></label>
								<div class="input-group">
									<input type="password" name="password_ulang" class="validate[required,equals[password]] form-control" placeholder="Ulangi Password" autocomplete="off">
								</div>
							</div>
						<div class="form-group">
							<label>Alamat <strong class="text-danger">*</strong></label>
							<div class="input-group">
								<textarea class="validate[required] form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label>Nomer Telepon <strong class="text-danger">*</strong></label>
							<div class="input-group">
								<input type="text" name="telepon" id="telepon" class="validate[custom[number]] form-control" placeholder="Format : 628XXXXXXXXX" required>
							</div>
						</div>
						<div class="form-group">
							<label>Nomer Telepon Cadangan <small>(Optional)</small></label>
							<div class="input-group">
								<input type="text" name="backup_telepon" id="backup_telepon" class="form-control" placeholder="Format : 628XXXXXXXXX">
							</div>
						</div>
					</fieldset>
					<fieldset id="biodata">
						<div class="form-group">
							<label>Tempat Lahir <strong class="text-danger">*</strong></label>
							<div class="input-group">
								<input type="text" name="tempatlahir" id="tempatlahir" class="validate[required] form-control" placeholder="Tempat Lahir" required>
							</div>
						</div>
						<div class="form-group">
							<label>Tanggal Lahir <strong class="text-danger">*</strong></label>
							<div class="input-group">
								<input type="date" name="tanggallahir" id="tanggallahir" class="validate[required] form-control" placeholder="Tanggal Lahir" required>
							</div>
						</div>
						<div class="form-group">
							<label>Ukuran Baju <strong class="text-danger">*</strong></label>
							<div class="input-group">
								<select class="validate[required] form-control" name="ukuranbaju" id="ukuranbaju">
									<option value="">-- Pilih Ukuran Baju --</option>
									<option value="S">S</option>
									<option value="M">M</option>
									<option value="L">L</option>
									<option value="XL">XL</option>
									<option value="XXL">XXL</option>
									<option value="XXXL">XXXL</option>
									<option value="XXXXL">XXXXL</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label>Pas Foto <strong class="text-danger">*</strong> (max size 5MB)</label>
							<div class="input-group">
								<input type="file" name="pasfoto" id="pasfoto" class="form-control" placeholder="Passfoto">
							</div>
						</div>
					</fieldset>
                    <br>
                            <span>Perhatikan!! tanda ( <strong class="text-danger">*</strong> )  wajib untuk diisi</span>
                            <button type="submit" id="daftar" class="btn btn-success m-b-0 f-right" disabled>Daftar</button>
                </form>
            
            </div>
        </div>
    </div>
    {{-- Page-body end --}}

@endsection

@section('footer')
<script type="text/javascript" src="{{url('assets/js/jquery.validationEngine-en.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/jquery.validationEngine.js')}}"></script>
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
    
    var uploadField = document.getElementById("pasfoto");
    uploadField.onchange = function() {
        if(this.files[0].size > 5055650){
            new PNotify({
                    title: 'File Oversize',
                    text: 'Maaf, File Max 5MB ',
                    type: 'error'
            });
            console.log("file size = " + this.files[0].size + "/5055650")
            this.value = "";
        };
    };
    
    $(function() {
        $('#pasfoto').checkFileType({
            allowedExtensions: ['jpg', 'jpeg','png'],
            error: function() {
                new PNotify({
                    title: 'File not Image',
                    text: 'Maaf, hanya type image yang diupload ',
                    type: 'error'
                });
                document.getElementById("pasfoto").value = "";
            }
        });
    });
</script>
<script type="text/javascript">
	$(document).ready(function(){
		jQuery('.form-validation').validationEngine();
		$('#daftar').attr('disabled', 'disabled');
		$('#tambahan').attr('disabled', 'disabled');
		$('#biodata').hide();
		$('#notis-danger').hide();
		$('#reset').hide();
		$('#search').show();	
	});

	function reset_form() {
    $('#daftar').attr('disabled', 'disabled');
    $('#tambahan').attr('disabled', 'disabled');
    $('#email').removeAttr('readonly');
    $('#biodata').hide();
    $('#kode').val('');
	$('#notis-danger').hide();
    $('#number_form')[0].reset();
    $('#search').show();
    $('#reset').hide();		
};

$('#search').click(function() {
    var email = $('#email').val();

    if (email == "") {
        new PNotify({
            title: 'Email Not Found',
            text: 'Mahasiswa tidak ditemukan.',
            icon: 'icofont icofont-info-circle',
            type: 'error'
    });
    
    } else {
    
        var input_icon = $(this).parent().children().first().children();
        var input_help = $(this).parent().parent().children().last();

        input_icon.removeClass('fa-user').addClass('fa-spinner fa-spin');
        input_help.hide();

        $.get("{{ URL::to('/cek-mhs') }}", {email: email})
        .done(function(result) {
            input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
                if ($.isNumeric(result)) {
                    new PNotify({
                        title: 'Akun telah terdaftar',
                        text: 'Akun telah terdaftar, Silakan login.',
                        icon: 'icofont icofont-info-circle',
                        type: 'error'
                    });
                    input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
                    input_help.children().html('Silahkan ketik Email dan klik tombol cari terlebih dahulu.');
					input_help.show();
					$('#notis-danger').html('<button class="close" data-dismiss="alert">&times;</button>Anda sudah pernah membuat akun. silakan lihat di email anda dan login.');
					$('#notis-danger').show();
                    $('#daftar').attr('disabled', 'disabled');
                    $('#tambahan').attr('disabled', 'disabled');
                    $('#biodata').hide();
                    $('#reset').hide();	
                    $('#search').show();

                } else if ((result.en) == "empty"){      
                        new PNotify({
                            title: 'Email Not Found',
                            text: 'Mahasiswa tidak ditemukan.',
                            icon: 'icofont icofont-info-circle',
                            type: 'error'
                    });
                    input_help.children().html('Mahasiswa belum terdaftar. Periksa Kembali');
                    input_help.show();
                    $('#daftar').attr('disabled', 'disabled');
                    $('#tambahan').attr('disabled', 'disabled');
                    $('#biodata').hide();
                    $('#reset').hide();	
                    $('#search').show();
                } else if ((result.isi.kelengkapan) == "N"){
                    new PNotify({
                        title: 'Nim tersebut pernah menjadi anggota',
                        text: '',
                        icon: 'icofont icofont-info-circle',
                        type: 'success'
                    });
                    input_help.children().html('Mahasiswa pernah terdaftar sebagai anggota silahkan perbaharui data');
                    input_help.show();
                    $('#email').attr('readonly', 'readonly');
                    $('#kode').val(result.kode);
                    $('#password').focus();
                    $('#nama').val(result.isi.nama);
                    $('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi)
                    $('#alamat').val(result.isi.alamat);
                    $('#telepon').val(result.isi.telepon);
                    $('#backup_telepon').val(result.isi.backup_telepon);
                    $('#jenis_kelamin').val(result.isi.jenis_kelamin);
                    $('#tanggallahir').val(result.isi.tanggallahir);
                    $('#tempatlahir').val(result.isi.tempatlahir);
                    $('#ukuranbaju').val(result.isi.ukuranbaju);
                    $('#daftar').removeAttr('disabled');
                    $('#tambahan').removeAttr('disabled');
                    $('#biodata').show();
                    $('#reset').show();	
                    $('#search').hide();
                }else{
                    input_help.children().html('NIM Mahasiswa terdaftar silahkan lengkapi data');
                    input_help.show();
                    $('#password').focus();
                    $('#email').attr('readonly', 'readonly');
                    $('#kode').val(result.kode);
                    $('#nama').val(result.isi.nama_mahasiswa);
                    $('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi)
                    $('#daftar').removeAttr('disabled');
                    $('#tambahan').removeAttr('disabled');
                    $('#biodata').show();
                    $('#reset').show();	
                    $('#search').hide();
                };
        })
        .fail(function() {
            toastr.error('Kesalahan server! Silahkan reload halaman dan coba lagi');
            input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
            input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
            input_help.show();
        });
        
    }
});
</script>
@endsection