@extends('layout.master')

@section('title')
@if(Request::path() == 'opt/pilihpkm')
Detail PKM
@elseif(Request::path() == 'opt/pilihdownloadpkm')
Download PKM
@elseif(Request::path() == 'opt/pilihmaksdosen')
Maks Dosen
@elseif(Request::path() == 'opt/pilihakunsimbelmawa')
Akun Simbelmawa
@elseif(Request::path() == 'opt/pilihgrafik')
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
                        @if(Request::path() == 'opt/pilihpkm')
                        <h4>Detail PKM</h4>
                        <span>Pilih Daftar Pengajuan PKM {{$fakultas}}</span>
                        @elseif(Request::path() == 'opt/pilihdownloadpkm')
                        <h4>Download PKM</h4>
                        <span>Pilih Daftar Download PKM {{$fakultas}}</span>
                        @elseif(Request::path() == 'opt/pilihmaksdosen')
                        <h4>Dosen PKM UNY</h4>
                        <span>Pilih Daftar Dosen PKM UNY</span>
                        @elseif(Request::path() == 'opt/pilihakunsimbelmawa')
                        <h4>Akun Simbelmawa</h4>
                        <span>Pilih Daftar Akun Simbelmawa Pengajuan PKM {{$fakultas}}</span>
                        @elseif(Request::path() == 'opt/pilihgrafik')
                        <h4>Grafik dan Rekapan PKM</h4>
                        <span>Pilih Daftar Rekapan PKM {{$fakultas}}</span>
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
                            @if(Request::path() == 'opt/pilihpkm')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'opt/pilihdownloadpkm')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Download PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'opt/pilihmaksdosen')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Dosen PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'opt/pilihakunsimbelmawa')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Akun Simbelmawa PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'opt/pilihgrafik')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Rekapan PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @endif
                        </div>
                    </div>
                    <div class="row seacrh-header">
                        <div class="col-lg-6 offset-lg-3 offset-sm-6 col-sm-6 offset-sm-1 col-xs-12">
                            @if(Request::path() == 'opt/pilihgrafik')
                            <div class="panel panel-warning">
                                <div class="panel-heading bg-warning">
                            @else
                            <div class="panel panel-primary">
                                <div class="panel-heading bg-primary">
                            @endif
                                    @if(Request::path() == 'opt/pilihpkm')
                                    Detail PKM
                                    @elseif(Request::path() == 'opt/pilihdownloadpkm')
                                    Download PKM
                                    @elseif(Request::path() == 'opt/pilihmaksdosen')
                                    Dosen PKM
                                    @elseif(Request::path() == 'opt/pilihakunsimbelmawa')
                                    Akun Simbelmawa
                                    @elseif(Request::path() == 'opt/pilihgrafik')
                                    Rekapan PKM
                                    @endif
                                </div>
                                <div class="panel-body">
                                        @if(Request::path() == 'opt/pilihpkm')
                                            <p>Cari Pengajuan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('opt/ceklistpkm')}}" method="GET">
                                        @elseif(Request::path() == 'opt/pilihdownloadpkm')
                                            <p>Cari Download PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('opt/cekdownloadpkm')}}" method="GET">
                                        @elseif(Request::path() == 'opt/pilihmaksdosen')
                                            <p>Cari Daftar Dosen PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('opt/cekmaksdosen')}}" method="GET">
                                        @elseif(Request::path() == 'opt/pilihakunsimbelmawa')
                                            <p>Cari Akun Simbelmawa PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('opt/cekakunsimbel')}}" method="GET">
                                        @elseif(Request::path() == 'opt/pilihgrafik')
                                            <p>Pilih Rekapan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <form action="{{url('opt/cekgrafikpkm')}}" method="GET">
                                        @endif
                                            <div class="form-group">
                                                <select name="tahunpkm" class="form-control" required>
                                                    @foreach ($tahun as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tahun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <select name="tipepkm" class="form-control" required>
                                                    @foreach ($tipe as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tipe }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        @if(Request::path() == 'opt/pilihgrafik')
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