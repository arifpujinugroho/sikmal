@extends('layout.master')

@section('title')
List PKM Mahasiswa
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-speed-meter bg-c-blue"></i>
                <div class="d-inline">
                    <h4>List PKM Mahasiswa</h4>
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
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-striped table-bordered nowrap">
                            <thead>
                                <th width="4px">No</th>
                                <th width="23px">Status</th>
                                <th width="12px">Ketua PKM</th>
                                <th width="12px">Prodi Ketua</th>
                                <th width="16px">Skim PKM</th>
                                <th>Telepon</th>
                                <th>Status Upload</th>
                                <th>Judul PKM</th>
                                {{--<th width="12px">Aksi</th>--}}
                            </thead>
                            <tbody>
                                @if ($jml != 0)
                                <?php $n = 1; ?>
                                @foreach ($list as $t)
                                <tr>
                                    <td class="text-center">{{ $n }}</td>
                                    <td>
                                        @if ($t->file_proposal == Null)
                                        <strong class="text-danger">Input Belum Lengkap</strong>
                                        @else
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
                                        @endif
                                    </td>
                                    <td>{{ $t->nama }}</td>
                                    <td>{{ $t->nama_prodi }}</td>
                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                    <td>{{$t->telepon}}</td>
                                    <td>
                                        @if(is_null($t->file_proposal))
                                        <label class="label label-danger">Belum Upload Proposal</label><br>
                                        @else
                                        <a href="{{url('dsn/downpro')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Proposal</button></a><br>
                                        <p>{{$t->time_proposal}}</p>
                                        @endif
                                    </td>
                                    <td>{{ $t->judul }}</td>
                                    {{--<td>
                                            <a href="{{url('/mhs/pkm')}}/{{ $t->id_file_pkm }}" data-toggle="tooltip" title="Lihat" ><button class="btn btn-mini btn-default"><i class="icofont icofont-eye-alt"></i> Lihat PKM</button></a>
                                    </td>--}}
                                </tr>
                                <?php $n++; ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center f-s-italic">Belum ada daftar PKM</td>
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