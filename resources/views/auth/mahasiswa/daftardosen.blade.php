@extends('layout.master')

@section('title')
List Dosen
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
                        <h4>List Dosen UNY</h4>
                        <span><strong>Daftar Dosen UNY sesuai Database PKM Center UNY</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List Dosen</a> </li>
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
                                <table id="listDosen" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th>No.</th>
                                        <th>Nama Dosen</th>
                                        <th>Prodi Dosen</th>
                                        <th>Keahlian</th>
                                        <th>NIDN/NIDK</th>
                                    </thead>
                                    <tbody>
                                        <?php $c=1; ?>
                                            @foreach ($dosen as $t)
                                                <tr>
                                                    <td class="text-center">{{ $c }}</td>
                                                    <td class="text-capitalize">{{ $t->nama_dosen }}</td>
                                                    <td>{{ $t->prodi_dosen }}</td>
                                                    <td>{{ $t->keahlian}}</td>
                                                    <td>
                                                        @if ($t->nidn_dosen != "")
                                                            <label class="label label-success">Sudah Ada NIDN</label>
                                                        @elseif($t->nidn_dosen == "" && $t->nidk_dosen != "")
                                                            <label class="label label-warning">Hanya NIDK</label>
                                                            @elseif($t->nidn_dosen == "" && $t->nidk_dosen == "")    
                                                            <label class="label label-danger">Belum ada</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php $c++; ?>
                                            @endforeach
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
<script>
$(document).ready(function () {
    $('#listDosen').DataTable( {
        "ordering" : true,
        "info" :     false,
        "responsive" : true,
    });
});
</script>

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
    
<!-- Custom js -->
<script src="{{url('assets/pages/data-table/js/data-table-custom.js')}}"></script>
@endsection
