@extends('layout.master')

@section('title')
List Keuangan PKM Mahasiswa
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>List Keuangan PKM</h4>
                        <span>List untuk pengelolaan keuangan PKM yang didanai</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List PKM</a> </li>
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
                        <span><strong>Jumlah PKM yang didanai : {{ $jml }}</strong></span>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                    <thead>
                                        <th width="12px">Aksi</th>
                                        <th width="12px">Tahun PKM</th>
                                        <th width="16px">Skim PKM</th>
                                        <th>Judul PKM</th>
                                        <th>Pendanaan</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>
                                                            <a href="{{url('/mhs/dana')}}/{{ $t->id_file_pkm }}" data-toggle="tooltip" title="Lihat" ><button class="btn btn-mini btn-success"><i class="icofont icofont-eye-alt"></i> Lihat Dana</button></a>
                                                    </td>
                                                    <td class="text-center">{{ $t->tahun }}</td>
                                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td class="text-capitalize">{{number_format($t->dana_dikti, 0, ".", ".")}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center f-s-italic">Belum ada daftar PKM yang lolos Didanai</td>
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

