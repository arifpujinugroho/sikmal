@extends('layout.master')

@section('title')
{{$data->nama_operator}}
@endsection

@section('header')

@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-listine-dots bg-c-lite-green"></i>
                    <div class="d-inline">
                        <h4>Identitas Operator</h4>
                        <span>Informasi Operator yang telah terdaftar</span>
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
                <li class="breadcrumb-item"><a href="#!">Biodata</a>
                </li>
            </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->
@if (Crypt::decrypt(Auth::user()->operator->opt_status) == Auth::user()->username)
<div class="alert alert-danger">
        <strong>Username dan Password anda sama!! <br>
        Mohon demi kenyamanan dan keamanan, mohon sekiranya untuk <a class="ubahPassword alert-link text-danger">mengubah passsword</a> anda, terima kasih</strong>
</div>
@endif

    <!-- Page body start -->
    <div class="page-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Product detail page start -->
                    <div class="card product-detail-page">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-5 col-xs-12">
                                    <div class="port_details_all_img row">
                                        <div class="col-lg-12 m-b-15">
                                            <div id="big_banner">
                                                <div class="port_big_img">
                                                    <img class="img img-fluid" src="{{url('storage/files/pasfoto/'.$data->logo)}}" alt="Foto {{$data->nama_operator}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-xs-12 product-detail">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="btn-group f-right">
                                                    <button id="ubahFoto" data-toggle="tooltip" title="Ubah Foto Profil" class="btn btn-mini btn-info">Ubah Foto</button>
                                                <button id="ubahData" data-toggle="tooltip" title="Ubah Data Diri" class="btn btn-mini btn-warning">Ubah Data</button>
                                                <button data-toggle="tooltip" title="Ubah Password" class="ubahPassword btn btn-mini btn-danger">Ubah Password</button>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-lg-12">
                                                <h1 class="pro-desc f-w-300 text-capitalize"><strong> {{$data->nama_operator}} </strong> <br><small class="text-muted text-capitalize">&nbsp;&nbsp;&nbsp;{{Auth::user()->username}}</small></h1>
                                            </div>
                                        </div>
                                            <div class="col-lg-12">
                                                    <span class="text-primary product-price">{{$data->nama_fakultas}}</span>
                                                <br>
                                                <hr>
                                                <br>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="btn-group f-right">
                                                </div>
                                                <p><strong>Instagram : </strong> {{$data->instagram_operator}}</p>
                                                <p><strong>Jenis kata2 : </strong> {{$data->tipe_quote}}</p>
                                                <p><strong>kata2 untuk mahasiswa : </strong> {{$data->quotes}}</p>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product detail page end -->
                </div>
            </div>
        </div>
        <!-- Page body end -->
@endsection

@section('end')
<div class="modal fade" id="ubah-password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{url('opt/gantipass')}}"  method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icofont icofont-id-card"></i></span>
                                <input type="password" name="password" id="formpassword" minlength="6" class="form-control" placeholder="Password" value="{{Crypt::decrypt(Auth::user()->operator->opt_status)}}" required>
                                <span class="input-group-addon bg-warning" id="show" onclick="ShowPassword()"><i class="icofont icofont-eye-blocked"></i></span>
                                <span class="input-group-addon bg-warning" style="display:none" id="hide" onclick="HidePassword()"><i class="icofont icofont-eye"></i></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah-foto" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
            <form action="{{url('opt/gantifoto')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                              <label for="">Pas Foto (max 5 Mb)</label>
                              <input type="file" class="form-control" name="foto" id="foto" placeholder="Foto" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div></form>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-biodata" tabindex="-1" role="dialog" aria-labelledby="tambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="tambahAnggotaLabel">Edit Biodata</h4>
            </div>
        <form action="{{url('opt/gantidata')}}" method="post">
        @csrf
            <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Operator</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" name="nama" id="nama" class="validate[required] form-control" placeholder="Nama Operator" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="instagram" id="instagram" class="validate[required] form-control" placeholder="Instagram">
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="">Tipe Kata-kata</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag fa-fw" aria-hidden="true"></i></span>
                      <select class="validate[required] form-control" name="tipe_kata" id="tipe_kata" required>
                        <option value="Quote">Quote</option>
                        <option value="Pengumuman">Pengumuman</option>
                        <option value="Peringatan">Peringatan</option>
                      </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Kata-Kata</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-edit fa-fw"></i></span>
                            <textarea class="validate[required] form-control" rows="3" name="quote" id="quote" placeholder="Kata-kata"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan <i class="fa fa-save"></i></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection





@section('footer')
<script>

    $('.ubahPassword').click(function(){
        $('#ubah-password').modal('show');
    });

    $('#ubahFoto').click(function(){
        $('#ubah-foto').modal('show');
    });

    $('#ubahData').click(function(){
        $('#nama').val('{{Auth::user()->operator->nama_operator}}');
        $('#instagram').val('{{Auth::user()->operator->instagram_operator}}');
        $('#tipe_kata').val('{{Auth::user()->operator->tipe_quote}}');
        $('#quote').val('{{Auth::user()->operator->quotes}}');
        $('#edit-biodata').modal('show');
    });

    function ShowPassword()
	{
			document.getElementById("formpassword").type="text";
			document.getElementById("show").style.display="none";
			document.getElementById("hide").style.display="block";
	}
	function HidePassword()
	{
			document.getElementById("formpassword").type = "password";
			document.getElementById("show").style.display="block";
			document.getElementById("hide").style.display="none";
	}

</script>
@endsection
