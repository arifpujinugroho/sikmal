@extends('layout.master')

@section('title')
List Dosen PKM
@endsection

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')

<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    LIST Dosen Bimbingan PKM (Max Dosen = {{$maxdos}})
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="simpletable" class="table table-striped">
                            <thead>
                                <th>No.</th>
                                <th>Nama Dosen</th>
                                <th>Fakultas</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php $n = 1; ?>
                                @foreach ($list as $t)
                                <tr>
                                    <td>{{ $n }}</td>
                                    <td>{{ $t->nama_dosen }}</td>
                                    <td>{{ $t->nama_singkat }}</td>
                                    <td>{{ $t->jumlah }}</td>
                                    <td><button type="button" class="LihatPKM btn btn-mini btn-primary" data-id="{{$t->id}}" data-toggle="tooltip" title="List PKM {{$t->nama_dosen}}"><i class="fa fa-eye" aria-hidden="true"></i></button></td>
                                </tr>
                                <?php $n++; ?>
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
 $('.LihatPKM').click(function(){
    var thn = "{{$tahunpkm}}";
    var dsn = $(this).data('id');
    window.location.replace('{{url('admin/listdosenbimbing')}}/'+thn+'/'+dsn);
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

<script src="{{url('assets/pages/data-table/js/data-table-custom.js')}}"></script>


@endsection