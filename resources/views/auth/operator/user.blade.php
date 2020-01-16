@extends('layout.master')

@section('title')
Mahasiswa {{$fakultas}}
@endsection

@section('header')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>List Mahasiswa {{$fakultas}}</h4>
                        <span><strong>Jumlah Mahasiswa yang ditemukan : {{ $jml }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Mahasiswa</a> </li>
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
                        <div class="card-header">
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th>Nim Mahasiswa</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nama_prodi }} - {{ $t->jenjang_prodi }}</td>
                                                    <td>
                                                        <button data-kode="{{Crypt::encryptString($t->email)}}" data-nim="{{ $t->nim }}" data-nama="{{ $t->nama }}" data-prodi="{{ $t->nama_prodi }} - {{ $t->jenjang_prodi }}" data-jnskel="{{ $t->jenis_kelamin }}" data-telepon="{{ $t->telepon }}" data-butelpon="{{ $t->backup_telepon }}" data-ukuranbaju="{{ $t->ukuranbaju }}" data-toggle="tooltip" title="Lihat/Edit {{ $t->nama }}" class="lihatEditData btn btn-mini btn-default"><i class="icofont icofont-eye-alt"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center f-s-italic">Tidak ada List Mahasiswa</td>
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


@include('layout.modal.optuser')
