@extends('layout.master')

@section('title')
Dashboard Dosen
@endsection

@section('header')

@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-clip-board bg-c-yellow"></i>
                <div class="d-inline">
                    <?php
                        date_default_timezone_set("Asia/Jakarta");
                        $t = time();
                        $jam = date("G",$t);
                    ?>

                    @if ($jam <= 10 ) <h4 class="">Selamat Pagi 
                        @if($dsn->jns_kel_dosen == "L")
                        Pak {{$dsn->nama_dosen}}
                                @if($dsn->simbel_akun != 0)<br>
                                    @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                        <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                    @else
                                        @if(is_null ($dsn->nidn_dosen) )
                                            <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                        @else
                                            <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                        @endif
                                    @endif
                                Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                @else
                                <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                @endif
                        @else 
                        Bu {{$dsn->nama_dosen}}
                                @if($dsn->simbel_akun != 0)<br>
                                    @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                        <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                    @else
                                        @if(is_null ($dsn->nidn_dosen) )
                                            <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                        @else
                                            <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                        @endif
                                    @endif
                                Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                @else
                                <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                @endif
                        @endif
                        </h4>
                    @elseif ($jam > 10 && $jam <= 15 ) <h4>Selamat Siang 
                            @if($dsn->jns_kel_dosen == "L")
                            Pak {{$dsn->nama_dosen}}
                                @if($dsn->simbel_akun != 0)<br>
                                    @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                        <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                    @else
                                        @if(is_null ($dsn->nidn_dosen) )
                                            <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                        @else
                                            <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                        @endif
                                    @endif
                                Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                @else
                                <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                @endif
                            @else 
                            Bu {{$dsn->nama_dosen}}
                                @if($dsn->simbel_akun != 0)<br>
                                    @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                        <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                    @else
                                        @if(is_null ($dsn->nidn_dosen) )
                                            <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                        @else
                                            <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                        @endif
                                    @endif
                                Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                @else
                                <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                @endif
                            @endif
                            </h4>
                            @elseif ($jam > 15 && $jam <= 19 ) <h4>Selamat Sore 
                                @if($dsn->jns_kel_dosen == "L")
                                Pak {{$dsn->nama_dosen}}
                                    @if($dsn->simbel_akun != 0)<br>
                                        @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                            <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                        @else
                                            @if(is_null ($dsn->nidn_dosen) )
                                                <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                            @else
                                                <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                            @endif
                                        @endif
                                    Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                    @else
                                    <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                    @endif
                                @else 
                                Bu {{$dsn->nama_dosen}}
                                    @if($dsn->simbel_akun != 0)<br>
                                        @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                            <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                        @else
                                            @if(is_null ($dsn->nidn_dosen) )
                                                <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                            @else
                                                <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                            @endif
                                        @endif
                                    Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                    @else
                                    <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                    @endif
                                @endif
                                </h4>
                            @elseif ($jam > 19 )
                                <h4>Selamat Malam 
                                    @if($dsn->jns_kel_dosen == "L")
                                    Pak {{$dsn->nama_dosen}}
                                        @if($dsn->simbel_akun != 0)<br>
                                            @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                                <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                            @else
                                                @if(is_null ($dsn->nidn_dosen) )
                                                    <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                                @else
                                                    <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                                @endif
                                            @endif
                                        Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                        @else
                                        <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                        @endif
                                    @else 
                                    Bu {{$dsn->nama_dosen}}
                                        @if($dsn->simbel_akun != 0)<br>
                                            @if($dsn->nidn_dosen == "" && $dsn->nidk_dosen == "")
                                                <small>Username Simbelmawa : {{$dsn->nip_dosen}}
                                            @else
                                                @if(is_null ($dsn->nidn_dosen) )
                                                    <small>Username Simbelmawa : {{$dsn->nidk_dosen}}
                                                @else
                                                    <small>Username Simbelmawa : {{$dsn->nidn_dosen}}
                                                @endif
                                            @endif
                                        Password Simbelmawa : {{$dsn->simbel_akun}} </small><br>
                                        @else
                                        <br><small class="text-warning">Maaf Akun Simbelmawa masih dalam proses input oleh TIM PKM Center</small>
                                        @endif
                                    @endif
                                </h4>
                                @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
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
@section('footer')
@endsection
