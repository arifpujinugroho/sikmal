@extends('layout.master')

@section('title')
Tambah Pengajuan PKM
@endsection

@section('header')
<link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap3-wysihtml5.css') }}">
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>Tambah Pengajuan PKM</h4>
                        <span><strong> PKM yang ON : </strong> </span>

                        @if ($modeupload == 0)
                        <span><label class="label label-danger" data-toggle="tooltip" title="Semua PKM OFF">Tidak ada PKM yang Aktif</label></span>
                        @endif

                        @if($lima->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="5 Bidang ON">5 Bidang</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="5 Bidang OFF">5 Bidang</label></span>--}}
                        @endif

                        @if($dua->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="2 Bidang ON">2 Bidang</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="2 Bidang OFF">2 Bidang</label></span>--}}
                        @endif

                        @if($gfk->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="PKM GFK ON">PKM GFK</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="PKM GFK OFF">PKM GFK</label></span>--}}
                        @endif

                        @if($sug->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="SUG ON">SUG</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="SUG OFF">SUG</label></span>--}}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/mhs/list-pkm')}}">List PKM</a> </li>
                        <li class="breadcrumb-item"><a href="#!">Tambah PKM</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

@if ($modeupload > 0)
@if (request('ketua_kelompok') == "Yes" && request('jika_terjadi_kesalahan') == "SiapBersedia" && request('judul') == "12" && request('ttd_lempeng') == "BukanCropping" && request('ttd_biodata') == "BukanCropping" && request('halaman_proposal') == "10Lembar")
    {{-- Page-body start --}}
    <div class="page-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <form method="POST" action="{{ url('mhs/tambahpkm') }}" enctype="multipart/form-data" class="form-validation" id="number_form">
                    @csrf
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div id="form-pkm">
                        <div class="form-group">
                                <label>Judul PKM *(Max 20 kata)</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
                                    <textarea name="judul" id="judul" class="validate[required] form-control" rows="3" placeholder="Judul PKM"></textarea>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Keyword / Kata Kunci</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tags fa-fw"></i></span>
                                    <input type="text" name="keyword" id="keyword" class="validate[required] form-control" placeholder="Keyword / Kata Kunci PKM">
                                </div>
                        </div>
                        <div id="form-abstrak" class="form-group">
                            <label>Abstrak</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                                <textarea name="abstrak" class="validate[required] form-control" id="abstrak" cols="30" ></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="form-dana">
                            <label>Dana Pengajuan PKM</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp. </span>
                                <input type="text" name="danapkm" id="danapkm" class="validate[required,custom[number]] form-control" placeholder="Contoh : 12500000">
                            </div>
                            <label class="text-danger" id="textDana"></label>
                        </div>
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
                        <div class="form-group" id="form-durasi">
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
                        </div>
                        <div class="form-group">
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
                        </div>
                    </div>
                    <hr>
                            <span class="text-danger" id="text_akhir">Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman</span>
                            <button type="submit" id="Simpan" class="btn btn-success m-b-0 f-right">Simpan</button>
                </form>

            </div>
        </div>
    </div>
    {{-- Page-body end --}}
@else
    <div class="alert alert-warning" role="alert">
        <strong>Maaf anda belum menceklis semua syarat sebelum mengajukan PKM.</strong> silakan kembali ke daftar pengajuan pkm atau <a href="{{url('mhs/list-pkm')}}" class="alert-link">Klik Disini</a>
    </div>
@endif
@else
    <div class="alert alert-danger">
        Opps Maaf... Sistem Tambah PKM sudah ditutup.
    </div>
@endif

@endsection


@section('end')
<!-- Modal -->
<div class="modal fade" id="SUGmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">PERINGATAN !</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><label>Mahasiswa yang tergabung dalam kelompok penelitian maksimal berada pada <strong class="text-danger">semester VI sebagai Ketua</strong>. Sedangkan sebagai <strong class="text-danger">anggota maksimal semester IV</strong></label></li>
                        <li class="list-group-item"><label>Masing-masing mahasiswa hanya terlibat pada satu judul penelitian, baik sebagai ketua maupun sebagai anggota.</label></li>
                        <li class="list-group-item"><label>Keanggotaan mahasiswa dalam kelompok harus berasal dari <strong class="text-danger">minimal 2 (dua) angkatan yang berbeda</strong></label></li>
                        <li class="list-group-item"><label>Jumlah anggota peneliti terdiri dari <strong class="text-danger">3 orang</strong> termasuk ketua peneliti.</label></li>
                        <li class="list-group-item"><label>Jangka waktu penelitian <strong class="text-danger">dilaksanakan selama 3 bulan</strong> sejak pengumuman hasil seleksi proposal.</label></li>
                        <li class="list-group-item"><label>Seorang pembimbing <strong class="text-danger">maksimal membimbing 2 judul</strong> penelitian.</label></li>
                        <li class="list-group-item"><label>Tagihan berupa <strong class="text-danger">laporan penelitian dan artikel</strong> sebagai bahan jurnal (hardcopy dan softcopy)</label></li>
                    </ul>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#exampleModal').on('show.bs.modal', event => {
        var button = $(event.relatedTarget);
        var modal = $(this);
        // Use above variables to manipulate the DOM
        
    });
</script>
@endsection

@section('footer')

<script type="text/javascript" src="{{url('assets/js/bootstrap3-wysihtml5.all.js')}}"></script>

    <script>
        $('#judul').on('keyup', function(){
            var stringIn = $(this).val();
            //exclude  start and end white-space
            str = stringIn.replace(/(^\s*)|(\s*$)/gi,"");

            //convert 2 or more spaces to 1
            str = str.replace(/[ ]{2,}/gi," ");

            // exclude newline with a start spacing
            str = str.replace(/\n /,"\n");

            //out
            str = str.split(' ').length;

            if(str > 20){
                swal("Max Kata dalam Judul!", "Judul anda sudah lebih dari 20 kata", "error");
                $('#text_akhir').html('Maaf judul anda lebih dari 20 Kata');
                $('#Simpan').attr('disabled','disabled');
            }else{
                $('#text_akhir').html('Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman');
                $('#Simpan').removeAttr('disabled');
            }
        });

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
        });

        $('#tipepkm').on('change', function() {
		    $('#skimpkm').html('<option value="">-- Pilih Skim PKM --</option>');
		    var tipepkm = $(this).val();
            var input_help = $('#skimpkm').parent().parent().children().last();
            var input_icon = $('#skimpkm').parent().children().first().children();

            input_icon.removeClass('fa-th-list').addClass('fa-spinner fa-spin');
		    input_help.hide();
            $('#form-pkm').hide();

		    $.get("{{ url('/mhs/skimpkm') }}", {tipepkm: tipepkm})
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
                $('#form-dana').show();
                $('#form-durasi').show();
                $('#judul').focus();
            }else if(skim == '7'|| skim == '8'){
                $('#form-abstrak').show();
                $('#form-linkurl').hide();
                $('#form-pkm').show();
                $('#form-dana').hide();
                $('#form-durasi').hide();
                $('#judul').focus();
            }else if(skim == '9'){
                $('#form-abstrak').show();
                $('#form-linkurl').show();
                $('#form-dana').hide();
                $('#form-durasi').hide();
                $('#form-pkm').show();
                $('#judul').focus();
            }else if(skim == '10'){
                $('#form-abstrak').show();
                $('#form-linkurl').hide();
                $('#form-pkm').show();
                $('#form-dana').hide();
                $('#form-durasi').hide();
                $('#judul').focus();
                $('#SUGmodal').modal('show');
            }else{
                $('#form-abstrak').hide();
                $('#form-linkurl').hide();
                $('#form-pkm').hide();
            }
        });

        $('#nama_dosen').on('change', function() {
            var input_icon = $('#nidn_dosen').parent().children().first().children();

            input_icon.removeClass('fa-thumb-tack').addClass('fa-spinner fa-spin');

        	var nama_dosen = $(this).val();
            var id_fakultas = $('#id_fakultas').val();

        	$.get("{{ URL::to('/mhs/cek-dosen') }}", {nama_dosen: nama_dosen, id_fakultas: id_fakultas})
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

        	$.get("{{ URL::to('/mhs/dosen-fakultas') }}", {id_fakultas: id_fakultas, tipepkm:tipepkm})
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
