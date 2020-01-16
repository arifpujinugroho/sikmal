@extends('layout.master')

@section('title')
Admin Dashboard
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-clip-board bg-c-yellow"></i>
                <div class="d-inline">
                    <h4>Selamat Datang Admin</h4>
                    <span>Tahun Anggaran yang aktif adalah : {{$tahun}}</span>
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
        <!-- total start -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                    <span class="text-c-blue f-w-600">PKM 5 Bidang</span>
                    <h4><small>{{ $lenglimbid }}/{{ $limbid }} PKM</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-blue f-16 icofont icofont-refresh m-r-10"></i>Total PKM 5 Bidang {{$tahun}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total end -->
        <!-- crew start -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont icofont-users-alt-2 bg-c-pink card1-icon"></i>
                    <span class="text-c-pink f-w-600">PKM KT</span>
                    <h4><small>{{ $lengdubid }}/{{ $dubid }} PKM</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-pink f-16 icofont icofont-users-alt-2 m-r-10"></i>Total PKM 2 Bidang {{$tahun}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- crew end -->
        <!-- alumni start -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont-graduate-alt bg-c-green card1-icon"></i>
                    <span class="text-c-green f-w-600">PKM GFK</span>
                    <h4><small>{{ $lenggfk }}/{{ $gfk }} PKM</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-green f-16 icofont icofont-graduate-alt m-r-10"></i>Total PKM GFK {{$tahun}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- calon start -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont-architecture-alt bg-c-yellow card1-icon"></i>
                    <span class="text-c-yellow f-w-600">SUG</span>
                    <h4><small>{{ $lengsug }}/{{ $sug }} SUG</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-yellow f-16 icofont icofont-architecture-alt m-r-10"></i>Total SUG {{$tahun}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- calon end -->
    </div>
</div>
@endsection