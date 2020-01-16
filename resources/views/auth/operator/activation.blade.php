@extends('layout.master')

@section('title')
Aktivasi {{$fakultas}}
@endsection


@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont  icofont-stock-search bg-c-yellow"></i>
                <div class="d-inline">
                    <h4>Aktivasi Akun Mahasiswa</h4>
                    <span>Aktivasi jika gagal/bermasalah</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Aktivasi</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <!-- Search result card start -->
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <p class="txt-highlight text-center m-t-20">Hanya digunakan untuk mengaktivasi Akun Mahasiswa {{$fakultas}} dengan mengunakan nim.
                            </p>
                        </div>
                    </div>
                    <div class="row seacrh-header">
                        <div class="col-lg-6 offset-lg-3 offset-sm-3 col-sm-6 offset-sm-1 col-xs-12">
				            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                    <input type="text" name="nim" id="nim" class="form-control" placeholder="NIM Mahasiswa" required>
                                    <span class="input-group-addon cursor-hand" id="search">Cari <i class="fa fa-search fa-fw"></i></span>
                                </div>
                                <p class="help-block text-center noticeBlock"><strong>Silahkan ketik NIM dan klik tombol cari terlebih dahulu.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Search result card end -->

        </div>
    </div>
</div>
@endsection


@section('end')
@include('layout.modal.optaktivasi');
@endsection