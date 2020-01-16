@extends('layout.master')

@section('title')
Input Custom PKM
@endsection

@section('footer')
    <script>

        $('#danapkm').on('keyup', function(){
            var input = $(this).val();
            var min = 5000000;
            var max = 12500000;

            if (input <= max && input >= min){
                $('#text_akhir').html('Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman');
                $('#textDana').html('');
                $('#textDana').hide();
                $('#Simpan').removeAttr('disabled');
            }else{
                $('#textDana').show();
                $('#textDana').html('Dana yang diajukan minimal Rp.'+min+',- dan maksimal Rp.'+max+',-');
                $('#text_akhir').html('Maaf dana pkm anda tidak sesuai dengan pedoman');
                $('#Simpan').attr('disabled','disabled');
            };
        });

        $(document).ready(function(){
            jQuery('.form-validation').validationEngine();
            $('#textDana').hide();
            $('#form-pkm').hide();
            $('#formnamaketua').hide();
            $('#formtipepkm').hide();
            $('#formskimpkm').hide();
            $('#resetnim').hide();
        });

        $('#tahunpkm').on('change', function() {
		    var tahun = $(this).val();
            if(tahun == ""){
            $('#formtipepkm').hide();
            $('#formskimpkm').hide();
            $('#form-pkm').hide();
                new PNotify({
				    title: 'Tahun belum terpilih',
				    text: 'Pilihlah Tahun PKM Custom',
				    icon: 'icofont icofont-info-circle',
				    type: 'warning'
				    });
            }else{
            $('#formtipepkm').show();
            $('#formskimpkm').show();
            }
        });

        $('#resetnim').click(function(){
			var input_help = $(this).parent().parent().children().last();
			$('#search').show();
			$('#resetnim').hide();
			$('#nim').val('');         
			$('#kode').val('');
			$('#nama').val('');
			$('#prodi').val('');
			$('#nim').removeAttr('readonly');
			input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');            
        });

        $('#search').click(function() {
			var nim = $('#nim').val();
			if (nim != '') {
				var input_icon = $(this).parent().children().first().children();
				var input_help = $(this).parent().parent().children().last();

				input_icon.removeClass('fa-user').addClass('fa-spinner fa-spin');
				input_help.hide();

				$.get("{{ URL::to('/admin/cek-mhs-custom') }}", {
						nim: nim,
					})
					.done(function(result) {
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
						if ($.isEmptyObject(result)) {
							new PNotify({
								title: 'NIM Not Founded!',
								text: 'Mahasiswa tidak terdaftar.',
								icon: 'icofont icofont-info-circle',
								type: 'error'
							});
							input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
							input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
							input_help.show();
							$('#search').show();
							$('#resetnim').hide();
							$('#nim').removeAttr('readonly');
						} else {
							if ($.isNumeric(result)) {
								new PNotify({
									title: 'Gagal Tambah Anggota!',
									text: 'Anggota sudah mengikuti 2 kelompok.',
									icon: 'icofont icofont-info-circle',
									type: 'error'
								});
								input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
								input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
								input_help.show();
							    $('#search').show();
							    $('#resetnim').hide();
							    $('#nim').removeAttr('readonly');
							} else if ((result.isi.kelengkapan) == "Y" || (result.isi.kelengkapan) == "N") {
								input_help.children().html('Mahasiswa sudah terdaftar silahkan langsung pilih peran dan tombol simpan');
								input_help.show();
								$('#nim').attr('readonly', 'readonly');
								$('#kode').val(result.kode);
								$('#nama').val(result.isi.nama);
								$('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi);
								if ((result.isi.jenis_kelamin) == "P") {
									$('#jns_kelamin').val("Perempuan");
								} else {
									$('#jns_kelamin').val("Laki-laki");
								}
							    $('#search').hide();
							    $('#resetnim').show();
							} else {
								input_help.children().html('Mahasiswa ditemukan silahkan lengkapi data');
								input_help.show();
								$('#nim').attr('readonly', 'readonly');
								$('#kode').val(result.kode);
								$('#nama').val(result.isi.nama_mahasiswa);
								$('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi);
								if ((result.isi.jns_kel_mahasiswa) == "P") {
									$('#jns_kelamin').val("Perempuan");
								} else {
									$('#jns_kelamin').val("Laki-laki");
								}
							    $('#search').hide();
							    $('#resetnim').show();
							};
						}
					})
					.fail(function() {
						toastr.error('Kesalahan server! Silahkan reload halaman dan coba lagi');
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
						input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
						input_help.show();
					});
			}else{
                new PNotify({
					title: 'NIM Kosong!',
					text: 'Masukan NIM Ketua Terlebih Dahulu!.',
					icon: 'icofont icofont-info-circle',
					type: 'warning'
				});
            }
		});

        $('#tipepkm').on('change', function() {
		    $('#skimpkm').html('<option value="">-- Pilih Skim PKM --</option>');
		    var tipepkm = $(this).val();
            var input_help = $('#skimpkm').parent().parent().children().last();
            var input_icon = $('#skimpkm').parent().children().first().children();

            input_icon.removeClass('fa-th-list').addClass('fa-spinner fa-spin');
		    input_help.hide();
            $('#form-pkm').hide();

		    $.get("{{ url('/admin/skimpkm') }}", {tipepkm: tipepkm})
		    .done(function(result) {
		    	if ($.isEmptyObject(result)) {
                    input_help.show();
                    input_icon.removeClass('fa-spinner fa-spin').addClass('fa-th-list');
		    	} else {
		    		$.each(result, function(index, value) {
                        input_icon.removeClass('fa-spinner fa-spin').addClass('fa-th-list');
                        $('#form-pkm').hide();
                        $('#skimpkm').append('<option value="'+value.id+'">'+value.skim_lengkap+'</option>');

		    		});
		    	};
		    })
		    .fail(function(result) {
                input_icon.removeClass('fa-spinner fa-spin').addClass('fa-th-list');
		    	input_help.children().html('Kesalahan server, silahkan ganti tipe pkm ke pilihan lain dan pilih lagi tipe pkm anda');
		    	input_help.show();
                $('#form-pkm').hide();
		    });
        });

        $('#skimpkm').on('change', function() {

            var skim = $(this).val();

            if(skim == '1' || skim == '2' || skim == '3' || skim == '4' || skim == '5' || skim == '6'){
                $('#form-abstrak').hide();
                $('#form-linkurl').hide();
                $('#form-pkm').show();
                $('#formnamaketua').show();
                $('#form-dana').show();
                $('#form-durasi').show();
                $('#nim').focus();
            }else if(skim == '7'|| skim == '8' || skim == '10'){
                $('#form-abstrak').show();
                $('#form-linkurl').hide();
                $('#form-pkm').show();
                $('#formnamaketua').show();
                $('#form-dana').hide();
                $('#form-durasi').hide();
                $('#nim').focus();
            }else if(skim == '9'){
                $('#form-abstrak').show();
                $('#form-linkurl').show();
                $('#form-dana').hide();
                $('#form-durasi').hide();
                $('#form-pkm').show();
                $('#formnamaketua').show();
                $('#nim').focus();
            }else{
                $('#form-abstrak').hide();
                $('#form-linkurl').hide();
                $('#form-pkm').hide();
                $('#formnamaketua').hide();
            }
        });

        $('#nama_dosen').on('change', function() {
            var input_icon = $('#nidn_dosen').parent().children().first().children();

            input_icon.removeClass('fa-thumb-tack').addClass('fa-spinner fa-spin');

        	var nama_dosen = $(this).val();
            var id_fakultas = $('#id_fakultas').val();

        	$.get("{{ URL::to('/admin/cek-dosen') }}", {nama_dosen: nama_dosen, id_fakultas: id_fakultas})
        	.done(function(result) {
                if ($.isEmptyObject(result.dosen.nidn_dosen) && $.isEmptyObject(result.dosen.nidk_dosen)) {
                    input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
				    $('#nidn_dosen').val(result.dosen.nip_dosen);
        		    $('#kodedosen').val(result.kode);
        		    $('#tipenidn').val('NIP');
                    new PNotify({
				    title: 'NIDN/NIDK Not Found!',
				    text: 'Dosen tersebut Menggunakan NIP',
				    icon: 'icofont icofont-info-circle',
				    type: 'danger'
				    });
			    } else {
                    if($.isEmptyObject(result.dosen.nidn_dosen)){
                        input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
        		        $('#nidn_dosen').val(result.dosen.nidk_dosen);
        		        $('#kodedosen').val(result.kode);
        		        $('#tipenidn').val('NIDK');
                        new PNotify({
				            title: 'NIDK Only',
				            text: 'Dosen masih menggunakan NIDK, atau hubungi PKM Center untuk memperbarui NIDN',
				            icon: 'icofont icofont-info-circle',
				            type: 'warning'
				        });
                    } else{
                        input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
        		        $('#nidn_dosen').val(result.dosen.nidn_dosen);
        		        $('#kodedosen').val(result.kode);
        		        $('#tipenidn').val('NIDN');
                    }
                }
        	})
        	.fail(function() {
                input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
        		toastr.error('Kesalahan server! Nidn tidak valid');
        	});
        });

        $('#id_fakultas').on('change', function() {
        	$('#nama_dosen').html('<option value="">-- Nama Dosen --</option>');
        	var id_fakultas = $(this).val();
            var tipepkm = $('#tipepkm').val();
        	var input_icon = $('#nama_dosen').parent().children().first().children();
        	var input_help = $('#nama_dosen').parent().parent().children().last();

        	input_icon.removeClass('fa-user').addClass('fa-spinner fa-spin');
        	//input_help.hide();

        	$.get("{{ URL::to('/admin/dosen-fakultas') }}", {id_fakultas: id_fakultas, tipepkm:tipepkm})
        	.done(function(result) {

        		if ($.isEmptyObject(result)) {
                    input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
        			input_help.show();
        			toastr.error('Dosen tidak ditemukan!');
        		} else {
        			$.each(result, function(index, value) {
                        input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
        				$('#nama_dosen').append('<option value="'+value.nama_dosen+'">'+value.nama_dosen+'</option>');
        			});
        		};
        	})
        	.fail(function(result) {
                input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
        		toastr.error('Kesalahan server! Hasil tidak valid');
        	});
        });
    </script>
@endsection


@section('content')
    <!-- Page-header start 
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>Input Custom PKM</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/pilihpkm')}}">PKM</a> </li>
                        <li class="breadcrumb-item"><a href="#!">Input Custom</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
     Page-header end -->

    <div class="page-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <form method="POST" action="{{ url('admin/inputcustom') }}" enctype="multipart/form-data" class="form-validation" id="number_form">
                    @csrf
                    <div class="form-group">
                        <label>Tahun PKM</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list-alt fa-fw"></i></span>
                            <select class="validate[required] form-control" name="tahunpkm" id="tahunpkm">
                                <option value="">-- Pilih Tahun PKM --</option>
                                @foreach($tahun as $t)
                                <option value="{{$t->id}}">{{ $t->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="formtipepkm">
                        <label>Tipe PKM</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list-alt fa-fw"></i></span>
                            <select class="validate[required] form-control" name="tipepkm" id="tipepkm">
                                <option value="">-- Pilih Tipe PKM --</option>
                                @foreach($tipepkm as $t_p)
                                <option value="{{Crypt::encryptString($t_p->id) }}">{{ $t_p->tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="formskimpkm">
                        <label>Skim PKM</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-th-list fa-fw"></i></span>
                            <select class="validate[required] form-control" name="skimpkm" id="skimpkm">
                                <option value="">-- Pilih Skim PKM --</option>
                            </select>
                        </div>
                        <p class="help-block"><strong>Silahkan pilih Tipe PKM terlebih dahulu.</strong></p>
                    </div>
                    <hr>
                    <div id="formnamaketua">
                        <div class="form-group">
    	    		    	<div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
    	    		    		<input type="text" name="nim" id="nim" class="validate[required,custom[integer]] form-control" placeholder="NIM Ketua">
    	    		    		<span class="input-group-addon cursor-hand" id="search">Cari <i class="fa fa-search fa-fw"></i></span>
    	    		    		<span class="input-group-addon cursor-hand bg-warning" id="resetnim">Reset NIM <i class="fa fa-refresh fa-fw"></i></span>
                                <input type="hidden" name="kode" id="kode">
    	    		    	</div>
    	    		    	<p class="help-block"><strong>Silahkan ketik NIM dan klik tombol cari terlebih dahulu.</strong></p>
    	    		    </div>
    	    			<div class="form-group">
    	    				<div class="input-group">
    	    					<span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
    	    					<input type="text" id="nama" class="validate[required] form-control" placeholder="Nama Mahasiswa" readonly>
    	    				</div>
    	    			</div>
    	    			<div class="form-group">
    	    				<div class="input-group">
    	    					<span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>
    	    					<input type="text" id="prodi" class="validate[required] form-control" placeholder="Prodi Mahasiswa" readonly>
    	    				</div>
    	    			</div>
                    </div>
                    <div id="form-pkm">
                        <div class="form-group">
                            <label>Status PKM</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>
                                <select class="validate[required] form-control" name="status" id="status" >
                                    <option value="">-- Status PKM --</option>
                                    <option value="1">Pengajuan Proposal</option>
                                    <option value="2">Lolos Proses Upload</option>
                                    <option value="3">Lolos Pendanaan PKM</option>
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
                        </div>
                        <div class="form-group">
                                <label>Judul PKM</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
                                    <textarea name="judul" id="judul" class="validate[required] form-control" rows="3" placeholder="Judul PKM"></textarea>
                                </div>
                        </div>

                        <input type="hidden" name="keyword" value="Dibuat oleh Sistem/Admin" placeholder="Keyword / Kata Kunci PKM">
                        {{--<div class="form-group">
                            <label>Keyword / Kata Kunci</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tags fa-fw"></i></span>
                                <input type="text" name="keyword" id="keyword" class="validate[required] form-control" placeholder="Keyword / Kata Kunci PKM">
                            </div>
                        </div>--}}
                        <div id="form-abstrak" class="form-group">
                            <label>Abstrak</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                                <textarea name="abstrak" class="validate[required] form-control" id="abstrak" cols="30" ></textarea>
                            </div>
                        </div>
                        
                        <input type="hidden" name="danapkm" value="12500000">
                        {{--<div class="form-group" id="form-dana">
                            <label>Dana Pengajuan PKM</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp. </span>
                                <input type="text" name="danapkm" id="danapkm" class="validate[required,custom[number]] form-control" placeholder="Contoh : 12500000">
                            </div>
                            <label class="text-danger" id="textDana"></label>
                        </div>--}}
                        <div id="form-linkurl" class="form-group">
                                <label>Link URL Video</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
                                    <input type="url" name="linkurl" id="linkurl" class="validate[required, custom[url]] form-control" placeholder="URL Video">
                                </div>
                        </div>
                        <div class="form-group">
                            <label>Dosen Fakultas</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>
                                <select class="validate[required] form-control" name="id_fakultas" id='id_fakultas'>
                                    <option value="">-- Nama Fakultas --</option>
                                    @foreach($list_fakultas as $fakultas)
                                        <option value="{{$fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Dosen</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <select class="validate[required] form-control" name="nama_dosen" id='nama_dosen'>
                                    <option value="">-- Nama Dosen --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIDN Dosen</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-thumb-tack fa-fw"></i></span>
                                <input type="hidden" name="tipenidn" id="tipenidn">
                                <input type="hidden" name="kodedosen" id="kodedosen">
                                <input type="text" readonly name="nidndosen" id="nidn_dosen" class="disabled validate[required] form-control" placeholder="NIDN Dosen Pendamping">
                            </div>
                        </div>
                        <input type="hidden" name="durasi" value="5">
                        {{--<div class="form-group" id="form-durasi">
                            <label>Durasi Penelitian</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa fa-calendar fa-fw"></i></span>
                                <select class="validate[required] form-control" name="durasi">
                                    <option value="">-- Durasi Penelitian --</option>
                                        <option value="1">1 Bulan</option>
                                        <option value="2">2 Bulan</option>
                                        <option value="3">3 Bulan</option>
                                        <option value="4">4 Bulan</option>
                                        <option value="5">5 Bulan</option>
                                </select>
                            </div>
                        </div>--}}
                        <input type="hidden" name="self" value="N">
                        {{--<div class="form-group">
                            <label><strong>Apakah anda diwajibkan untuk membuat Proposal Usulan PKM? <small class="text-danger">(Tidak mempengaruhi penilaian)</small></strong></label>
                            <div class="form-radio">
                                <div class="radio radio-inline">
                                    <label>
                                        <input type="radio" name="self" value="Y" required>
                                        <i class="helper"></i>Ya
                                    </label>
                                </div>
                                <div class="radio radio-inline">
                                    <label>
                                        <input type="radio" name="self" value="N">
                                        <i class="helper"></i>Tidak
                                    </label>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    <hr>
                            <span class="text-danger" id="text_akhir">Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman</span>
                            <button type="submit" id="Simpan" class="btn btn-success m-b-0 f-right">Simpan</button>
                </form>

            </div>
        </div>
    </div>
@endsection
