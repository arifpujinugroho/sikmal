@extends('layout.master')

@section('title')
@if(Request::path() == 'dsn/pilihpkmbimbing')
PKM yang di Bimbing
@elseif(Request::path() == 'dsn/pilihpkm')
Detail PKM
@elseif(Request::path() == 'dsn/pilihgrafik')
Pilih Grafik PKM
@endif
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-layout bg-c-blue"></i>
                    <div class="d-inline">
                        @if(Request::path() == 'dsn/pilihpkmbimbing')
                        <h4>Daftar PKM yang Dibimbing</h4>
                        @elseif(Request::path() == 'dsn/pilihpkm')
                        <h4>Daftar PKM UNY</h4>
                        <span>Pilih Daftar Pengajuan PKM UNY</span>
                        @elseif(Request::path() == 'dsn/pilihgrafik')
                        <h4>Grafik dan Rekapan PKM</h4>
                        <span>Pilih Daftar Rekapan PKM UNY</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}">
                                <i class="icofont icofont-home"></i>
                            </a>
                        </li>
                <li class="breadcrumb-item"><a href="#!">PKM</a>
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
                <div class="card-block  panels-wells">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            @if(Request::path() == 'dsn/pilihpkmbimbing')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'dsn/pilihpkm')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar PKM UNY berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'dsn/pilihgrafik')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Rekapan PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @endif
                        </div>
                    </div>
                    <div class="row seacrh-header">
                        <div class="col-lg-6 offset-lg-3 offset-sm-6 col-sm-6 offset-sm-1 col-xs-12">
                            @if(Request::path() == 'dsn/pilihgrafik')
                            <div class="panel panel-warning">
                                <div class="panel-heading bg-warning">
                            @else
                            <div class="panel panel-primary">
                                <div class="panel-heading bg-primary">
                            @endif
                                    @if(Request::path() == 'dsn/pilihpkmbimbing')
                                    Daftar PKM yang Dibimbing
                                    @elseif(Request::path() == 'dsn/pilihpkm')
                                    Daftar PKM UNY
                                    @elseif(Request::path() == 'dsn/pilihgrafik')
                                    Rekapan PKM
                                    @endif
                                </div>
                                <div class="panel-body">
                                        @if(Request::path() == 'dsn/pilihpkmbimbing')
                                            <p>Cari Bimbingan PKM berdasarkan Tahun</p>
                                            <form action="{{url('dsn/ceklistpkmbimbing')}}" method="GET">
                                        @elseif(Request::path() == 'dsn/pilihpkm')
                                            <p>Cari Pengajuan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('dsn/ceklistpkm')}}" method="GET">
                                        @elseif(Request::path() == 'dsn/pilihgrafik')
                                            <p>Pilih Rekapan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('dsn/cekgrafikpkm')}}" method="GET">
                                        @endif
                                            <div class="form-group">
                                                <select name="tahunpkm" class="form-control" required>
                                                    @foreach ($tahun as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tahun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if(Request::path() == 'dsn/pilihpkm' || Request::path() == 'dsn/pilihgrafik')
                                                <select name="tipepkm" class="form-control" required>
                                                    @foreach ($tipe as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tipe }}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                        </div>
                                        @if(Request::path() == 'dsn/pilihgrafik')
                                        <div class="panel-footer text-warning">
                                            <button type="submit" class="btn btn-sm btn-warning">Cari</button>
                                        @else
                                        <div class="panel-footer text-primary">
                                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                                        @endif
                                            
                                        </div>
                                    </form>
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