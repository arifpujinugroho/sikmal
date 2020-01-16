@extends('layout.master')

@section('title')
List Akun Simbelmawa UNY
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
                        <h4>List Akun Simbelmawa UNY</h4>
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
                                <table id="akunSimbel" class="table table-striped">
                                    <thead>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim</th>
                                        <th>Status</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Prodi</th>
                                    </thead>
                                    {{--<tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td>{{ $t->nama }}</td>
                                                    <td>{{ $t->nim }}</td>
                                                    <td>{{ $t->nama_prodi }} - {{$t->jenjang_prodi}}</td>
                                                    <td>001038{{$t->nim}}</td>
                                                    <td>
                                                        @if(is_null($t->pass_simbel))
                                                            <span class="text-danger">Belum di Input</span> 
                                                        @else
                                                            {{$t->pass_simbel}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($t->status == "1")
                                                            Pengajuan Proposal
                                                        @elseif($t->status == "2")
                                                            Lolos Proses Upload
                                                        @elseif($t->status == "3")
                                                            Lolos Didanai
                                                        @elseif($t->status == "4")
                                                            Lolos Pimnas
                                                        @else
                                                            {{$t->status}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                    <button data-nama="{{$t->nama}}" data-kode="{{Crypt::encryptString($t->id_file_pkm)}}" data-token="{{Crypt::encryptString($t->nim)}}" data-status="{{$t->status}}" data-password="{{$t->pass_simbel}}" class="editSimbel btn btn-mini btn-warning" data-toggle="tooltip" title="Edit {{$t->nama}}"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center f-s-italic">Belum ada Usulan PKM</td>
                                            </tr>
                                        @endif
                                    </tbody>--}}
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
$(document).ready(function(){
    var table =  $('#akunSimbel').DataTable({
            'dom': 'Bfrtip',
            'buttons': [{
                extend: 'excelHtml5',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="F"]', sheet).each(function() {
                        if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                            $(this).attr('s', '20');
                        }
                    });
                }
            }],
          'serverMethod': 'get',
          "paging":   true,
          "ordering": true,
          "info":     false,
           "lengthMenu" : [[10, 25, 50, -1], [10, 25, 50, "All"]],
          'ajax': {
              'url':'{{url("/kmhs/datasimbel")}}/{{$tahunpkm}}/{{$tipepkm}}',
              'dataSrc': '',
              'async': false,
          },
          'columns': [
             { data: 'nama' },
             { data: null,
                 render: function ( data, type, full,row) {
                     return '<p class="copyText" data-nim="'+data.nim+'">'+data.nim+'</p>';
                 }
             },
             { data: null,
                render: function ( data, type, full,row) {
                    if(data.status == "1"){
                        return 'Pengajuan Proposal';
                    }else if(data.status == "2"){
                        return 'Lolos Proses Upload';
                    }else if(data.status == "3"){
                        return 'Lolos Didanai';
                    }else if(data.status == "4"){
                        return 'Lolos Pimnas';
                    }else{
                        return data.status;
                    }
                }
             },
             { data: null,
                 render: function ( data, type, full,row) {
                     return '001038'+data.nim;
                 }
             },
             { data: 'pass_simbel' },
             { data: null,
                 render: function ( data, type, full,row) {
                     return data.nama_prodi+' - '+data.jenjang_prodi;
                 }
             },
          ]
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
    
    <script src="{{url('assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js')}}"></script>
@endsection
