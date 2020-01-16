@extends('layout.master')

@section('title')
File Download
@stop

@section('header')
	<link rel="stylesheet" type="text/css" href="{{url('assets/css/simple-line-icons.cs')}}s">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/ionicons.css')}}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')

<div class="row">
    <!-- Client Map Start -->
    <div class="col-md-6">
        <div class="card client-map">
            <div class="card-block">
                <h5><i class="fa fa-download"></i> File Download</h5>
                <div class="dt-responsive table-responsive">
                    <table id="cobaDulu" class="table table-hover table-striped nowrap">
                        <thead>
                            <th>Judul</th>
                            <th class="text-center">Download</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
            <div class="card client-map">
                <div class="card-block">
                    <h5><i class="fa fa-download"></i> Link Download</h5>
                    <div class="dt-responsive table-responsive">
                        <table id="OkeDulu" class="table table-hover table-striped nowrap">
                            <thead>
                                <th>Judul</th>
                                <th class="text-center">Download</th>
                            </thead>
                            <tbody>
                                @foreach ($download as $d)
                                    <tr>
                                        <td>{{$d->judul}}</td>
                                        <td><a href="{{$d->link_file}}" target="_blank"><button type="submit" class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i></button></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <!-- Client Map End -->
</div>
@endsection

@section('footer')

    <!-- data-table js -->
    <script src="{{url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/jszip.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/pages/data-table/js/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>



<script>
$(document).ready(function(){
var table =  $('#cobaDulu').DataTable({
      'serverMethod': 'get',
      "responsive" : true,
      "processing": true,
        "searching": false,
        "paging":   false,
        "ordering": false,
        "info":     false,
       //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      'ajax': {
          'url':'{{url("/mhs/filedownloadgdrive")}}',
          'dataSrc': ''
      },
      'columns': [
         { data: 'filename' },
         { data: null,
            render: function ( data, type, full,row) {
                return '<form action="{{url("/downfilegdrive")}}" method="get"><input type="hidden" name="path" value="'+ data.path +'"><input type="hidden" name="type" value="'+ data.mimetype +'"><input type="hidden" name="name" value="'+ data.name +'"><button type="submit" class="btn btn-mini btn-success"><i class="fa fa-download" aria-hidden="true"></i></button></form>';
            }
        },
      ]
   });

   $('#OkeDulu').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
      "responsive" : true,
    } );

});

</script>

@endsection