@extends('layout.master')

@section('title')
List Download PKM UNY
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
                        <h4>List Download PKM UNY</h4>
                        <span><strong>Daftar File PKM yang di download</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">lo</a> </li>
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
                            LIST Download PKM
                            {{--<form action="">
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
                            </form>--}}
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="excel-bg" class="table table-striped">
                                    <thead>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim</th>
                                        <th>Judul</th>
                                        <th>Proposal</th>
                                        <th>Lap. Kemajuan</th>
                                        <th>Lap. Akhir</th>
                                        <th>File PPT</th>
                                        <th>Poster</th>
                                        <th>Artikel</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td>
                                                        @if(is_null($t->file_proposal))
                                                            <label class="label label-danger">Belum Upload Proposal</label>
                                                        @else
                                                            <a href="{{url('kmhs/downpro')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Proposal</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_laporan_kemajuan))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload Lap. Kemajuan</label>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('kmhs/downlapkem')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Lap.Kemajuan</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_laporan_akhir))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload Lap. Akhir</label>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('kmhs/downlapakh')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Lap.Akhir</button></a><br>
                                                            <p>{{$t->time_proposal}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_ppt))
                                                            @if ($t->status == "3" || $t->status == "4")
                                                                <label class="label label-danger">Belum Upload PPT</label>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos didanai</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('kmhs/downppt')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh File PPT</button></a>
                                                        @endif
                                                    </td>
                                                    <td>   
                                                        @if(is_null($t->poster))
                                                            @if ($t->status == "4")
                                                                <label class="label label-danger">Belum Upload Poster</label>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos Pimnas</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('kmhs/downposter')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Poster</button></a><br>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_null($t->file_artikel))
                                                            @if ($t->status == "4")
                                                                <label class="label label-danger">Belum Upload Artikel</label>
                                                            @else
                                                                <label class="label label-warning">Belum Lolos Pimnas</label>
                                                            @endif
                                                        @else
                                                            <a href="{{url('kmhs/downartikel')}}/{{Crypt::encryptString($t->id_file_pkm)}}"><button class="btn btn-mini btn-success"><i class="fa fa-download"></i> Unduh Poster</button></a><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center f-s-italic">Belum ada Usulan PKM</td>
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

@section('footer')


    <!-- data-table js -->
    <script src="{{url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/jszip.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/jszip.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js')}}"></script>
@endsection
