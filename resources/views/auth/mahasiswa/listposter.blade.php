@extends('layout.master')

@section('title')
List PKM Mahasiswa Lolos Pimnas
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>List Artikel Ilmiah dan Poster</h4>
                        <span><strong>Jumlah PKM yang Lolos Pimnas : {{ $jml }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List Poster</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <!-- Page-body start -->
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th width="12px">Tahun PKM</th>
                                        <th width="16px">Skim PKM</th>
                                        <th>Judul PKM</th>
                                        <th width="12px">Desc PKM</th>
                                        <th width="12px">Poster</th>
                                        <th width="12px">Artikel</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td class="text-center">{{ $t->tahun }}</td>
                                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td class="text-center">
                                                        @if(is_null($t->poster))
                                                            @if($t->aktif == 1)
                                                                <button class="uploadPoster btn btn-mini btn-warning" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}"><i class="fa fa-upload"></i>Unggah Lap.Akhir</button>
                                                            @else
                                                                <label class="label label-danger">Belum Upload Poster</label>
                                                            @endif
                                                        @else
                                                            <div class="">
                                                                <a href="{{url('mhs/downposter')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i>Unduh</button></a>
                                                                @if($t->aktif == 1)
                                                                <button class="uploadPoster btn btn-mini btn-default" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-toggle="tooltip" title="Upload Revisi Poster"><i class="fa fa-upload"></i>Revisi</button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(is_null($t->poster))
                                                            @if($t->aktif == 1)
                                                                <button class="uploadPoster btn btn-mini btn-warning" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}"><i class="fa fa-upload"></i>Unggah Lap.Akhir</button>
                                                            @else
                                                                <label class="label label-danger">Belum Upload Poster</label>
                                                            @endif
                                                        @else
                                                            <div class="">
                                                                <a href="{{url('mhs/downposter')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i>Unduh</button></a>
                                                                @if($t->aktif == 1)
                                                                <button class="uploadPoster btn btn-mini btn-default" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-toggle="tooltip" title="Upload Revisi Poster"><i class="fa fa-upload"></i>Revisi</button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(is_null($t->file_artikel))
                                                            @if($t->aktif == 1)
                                                                <button class="uploadArtikel btn btn-mini btn-warning" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}"><i class="fa fa-upload"></i>Unggah Artikel</button>
                                                            @else
                                                                <label class="label label-danger">Belum Upload File Artikel</label>
                                                            @endif
                                                        @else
                                                            <div class="">
                                                                <a href="{{url('mhs/downartikel')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i>Unduh</button></a>
                                                                @if($t->aktif == 1)
                                                                <button class="uploadArtikel btn btn-mini btn-default" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-toggle="tooltip" title="Upload Revisi Artikel"><i class="fa fa-upload"></i>Revisi</button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center text-danger f-s-italic">Belum ada PKM lolos PIMNAS</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Button table end -->
                </div>
        </div>
    </div>
    <!-- Page-body end -->
@endsection

