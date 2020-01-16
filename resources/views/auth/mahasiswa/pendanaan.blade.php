@extends('layout.master')

@section('title')
Pendanaan PKM
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
                <i class="icofont icofont-list bg-c-blue"></i>
                <div class="d-inline">
                    <h4>Laporan Keuangan</h4>
                    <span>Judul PKM : <strong class="text-danger">{{$pkm->judul}}</strong><br>Pendanaan Belmawa : <strong class="text-danger">Rp. {{number_format($pkm->dana_dikti, 0, ".", ".")}},-</strong></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Laporan Keuangan</a> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->
<!-- Page-body start -->
<div class="page-body">
    <input type="hidden" id="idpkm" value="{{$id}}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            Penggunaan Dana : Rp. <strong id="selfDana">#</strong>,-
                        </div>
                        <div class="col-md-4">
                            Presentase Penggunaan Dana : <strong id="persenDana">#</strong>%
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
        </div>
        <div class="card-block">
            <div class="table-responsive">
                <table id="danaTable" class="table table-striped" width="100%">
                    <thead>
                        <th>Aksi</th>
                        <th>Tanggal</th>
                        <th>Pembelian</th>
                        <th>Kategori</th>
                        <th>Toko</th>
                        <th>Volume</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>PPn</th>
                        <th>PPh21</th>
                        <th>PPh22</th>
                        <th>PPh23</th>
                        <th>PPh26</th>
                    </thead>
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
<script src="{{url('assets/custom/mhs-pendanaan.js')}}"></script>
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