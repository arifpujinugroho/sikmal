@extends('layout.master')

@section('title')
Referensi Judul
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-files bg-c-lite-green"></i>
                    <div class="d-inline">
                        <h4>Referensi Judul PKM UNY</h4>
                        <span><strong>Jumlah referensi judul PKM : {{ $jml }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Referensi Judul</a> </li>
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
                            <form action="">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <input type="text" name="keyword" class="form-control" placeholder="Search..." value="{{ request('keyword') }}">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th width="12px">Tahun PKM</th>
                                        <th width="16px">Skim PKM</th>
                                        <th>Judul PKM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th width="23px">Status</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td class="text-center">{{ $t->tahun }}</td>
                                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td class="text-capitalize">{{ $t->nama}}</td>
                                                    <td>
                                                            @if ($t->status == "1")
                                                                Pengajuan Proposal
                                                            @elseif($t->status == "2")
                                                                Lolos Proses Upload
                                                            @elseif($t->status == "3")
                                                            Lolos Didanai
                                                            @elseif($t->status == "4")
                                                            Lolos Pimnas
                                                            @elseif($t->status == "5")
                                                            Juara 1 Presentasi
                                                            @elseif($t->status == "6")
                                                            Juara 2 Presentasi
                                                            @elseif($t->status == "7")
                                                            Juara 3 Presentasi
                                                            @elseif($t->status == "8")
                                                            Juara Favorit Presentasi
                                                            @elseif($t->status == "9")
                                                            Juara 1 Poster
                                                            @elseif($t->status == "10")
                                                            Juara 2 Presentasi
                                                            @elseif($t->status == "11")
                                                            Juara 3 Presentasi
                                                            @elseif($t->status == "12")
                                                            Juara Favorit Presentasi
                                                            @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center text-danger f-s-italic">Daftar referensi judul belum ada</td>
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
