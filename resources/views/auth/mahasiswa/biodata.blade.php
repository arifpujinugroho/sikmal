@extends('layout.master')

@section('title')
{{$data->nama}}
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
                        <h4>Identitas Mahasiswa</h4>
                        <span>Informasi Mahasiswa yang telah terdaftar</span>
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
@if (Crypt::decrypt(Auth::user()->identitas_mahasiswa->crypt_token) == Auth::user()->identitas_mahasiswa->nim)
<div class="alert alert-danger">
        <strong>Username dan Password anda sama!! <br>
        Mohon demi kenyamanan dan keamanan, mohon sekiranya untuk <a class="alert-link text-danger" href="{{url('mhs/biodata')}}">mengubah passsword</a> anda, terima kasih</strong>
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
                                                    <img class="img img-fluid" src="{{url('storage/files/pasfoto/'.$data->pasfoto)}}" alt="Foto {{$data->nama}}">
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
                                                {{--<button id="ubahPassword" data-toggle="tooltip" title="Ubah Password" class="btn btn-mini btn-danger">Ubah Password</button>--}}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-lg-12">
                                                <h1 class="pro-desc f-w-300 text-capitalize"><strong> {{$data->nama}} </strong> <br><small class="text-muted text-capitalize">&nbsp;&nbsp;&nbsp;{{$data->nim}}</small></h1>
                                            </div>
                                        </div>
                                            <div class="col-lg-12">
                                                    <span class="text-primary product-price">{{$data->nama_prodi}} - {{$data->jenjang_prodi}}</span>
                                                <br>
                                                <hr>
                                                <br>
                                            </div>
                                            <div class="col-lg-12"> 
                                                <div class="btn-group f-right">
                                                </div>
                                                <p><strong>Email : </strong> {{$data->email}}</p>
                                                <p><strong>Alamat : </strong> {{$data->alamat}}</p>
                                                <p><strong>Jenis Kelamin : </strong> 
                                                    @if($data->jenis_kelamin == "L")
                                                    Laki-Laki
                                                    @else 
                                                    Perempuan 
                                                    @endif
                                                </p>
                                                <p><strong>Telepon : </strong> {{$data->telepon}}</p>
                                                <p><strong>Telepon lain : </strong> {{$data->backup_telepon}}</p>
                                                <p class="text-capitalize"><strong>Tempat Lahir : </strong> {{$data->tempatlahir}}</p>
                                                <p><strong>Tanggal Lahir : </strong> {{TglIndo::Tgl_indo($data->tanggallahir)}}</p>
                                                <p><strong>Ukuran Baju : </strong> {{$data->ukuranbaju}}</p>
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
@include('layout.modal.mhsbiodata')
@endsection