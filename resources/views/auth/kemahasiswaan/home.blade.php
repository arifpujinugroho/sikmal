@extends('layout.master')

@section('title')
Kemahasiswaan Dashboard
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-clip-board bg-c-yellow"></i>
                <div class="d-inline">
                    <h4>Selamat Datang Kemahasiswaan</h4>
                    <span>Halaman Utama Sistem PKM UNY</span>
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
        </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

<div class="page-body">
    <div class="row">
        <!-- crew start -->
        <div class="col-md-6 col-xl-6">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont icofont-users-alt-2 bg-c-pink card1-icon"></i>
                    <span class="text-c-pink f-w-600">Pengajuan PKM Tahun {{$thn}}</span>
                    <h4><small>{{$jmlyear}} Proposal</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-pink f-16 icofont icofont-users-alt-2 m-r-10"></i>Jumlah pengajuan tahun
                            ini
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- total start -->
        <div class="col-md-6 col-xl-6">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                    <span class="text-c-blue f-w-600">Total Pengajuan PKM</span>
                    <h4><small>{{$jml}} Proposal</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-blue f-16 icofont icofont-refresh m-r-10"></i>Total Pengajuan Proposal PKM
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total end -->
    </div>
</div>
@endsection