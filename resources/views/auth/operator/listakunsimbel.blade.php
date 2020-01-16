@extends('layout.master')

@section('title')
List Akun Simbelmawa {{$fakultas}}
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
                        <h4>List Akun Simbelmawa {{$fakultas}}</h4>
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
                        <li class="breadcrumb-item"><a href="#!">Akun Simbelmawa</a> </li>
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
                            LIST Akun Simbelmawa
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="excel-bg" class="table table-striped">
                                    <thead>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim</th>
                                        <th>Prodi</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Konfirmasi</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->nama_prodi }} - {{$t->jenjang_prodi}}</td>
                                                    <td>
                                                        @if($t->status != "1")
                                                        001038{{$t->nim}}
                                                        @else
                                                            <label class="label label-warning">Belum Lolos Upload External</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($t->status != "1")
                                                            @if(is_null($t->pass_simbel))
                                                                Belum di Input
                                                            @else
                                                                {{$t->pass_simbel}}
                                                            @endif
                                                        @else
                                                            <label class="label label-warning">Belum Lolos Upload External</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    @if($t->confirm_up == "Y")
                                                    Sudah Konfirmasi
                                                    @else
                                                    Belum Konfirmasi/Belum lulus
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center f-s-italic">Belum ada Usulan PKM</td>
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
