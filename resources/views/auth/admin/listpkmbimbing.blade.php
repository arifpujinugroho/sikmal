@extends('layout.master')

@section('title')
List Bimbingan {{$ntahun}} {{$dosen->nama_dosen}} ({{date('d-m-Y')}})
@endsection

@section('header')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('footer')
<script>
    $(document).ready(function() {
        var table = $('#bimbinganTable').DataTable({
                'dom': 'Bfrtip',
                'buttons': [{
                    extend: 'excelHtml5'
                    //exportOptions: {
                    //    columns: ':visible'
                    //}
                }, 
                //'colvis'
                ],
                "ordering": true,
                //"serverSide": true,
                "responsive" : true,
                "info": true,
                'serverMethod': 'get',
                "paging": true,
                "processing": true,
                'ajax': {
                    'url': '{{url("/admin/datalistdosenbimbing")}}/{{$tahunpkm}}/{{$dsn}}',
                    'dataSrc': '',
                },
                'columns': [
                    {
                        data: 'nama'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return data.nama_prodi + ' - ' + data.jenjang_prodi;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            var nomer = data.telepon;

                            if(nomer.slice(0, 1) == "0"){
                                var wa = nomer.slice(1);
                                return '<a href="https://wa.me/62'+wa+'" target="_blank"> <i class="fa fa-whatsapp text-success" aria-hidden="true"></i> '+nomer+'</a>';
                            }else{
                                return '<a href="https://wa.me/'+nomer+'" target="_blank"> <i class="fa fa-whatsapp text-success" aria-hidden="true"></i> '+nomer+'</a>';
                            }
                        }
                    },
                    {
                        data: 'skim_singkat'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            if (data.statuspkm == 1) {
                                return 'Pengajuan Proposal';
                            } 
                            else if(data.statuspkm == 2){
                                return 'Lolos Proses Upload';
                            } 
                            else if(data.statuspkm == 3){
                                return 'Lolos Didanai';
                            }
                            else if(data.statuspkm == 4){
                                return 'Lolos Pimnas';
                            }
                            else if(data.statuspkm == 5){
                                return 'Juara 1 Presentasi';
                            }
                            else if(data.statuspkm == 6){
                                return 'Juara 2 Presentasi';
                            }
                            else if(data.statuspkm == 7){
                                return 'Juara 3 Presentasi';
                            }
                            else if(data.statuspkm == 8){
                                return 'Juara Favorit Presentasi';
                            }
                            else if(data.statuspkm == 9){
                                return 'Juara 1 Poster';
                            }
                            else if(data.statuspkm == 10){
                                return 'Juara 2 Poster';
                            }
                            else if(data.statuspkm == 11){
                                return 'Juara 3 Poster';
                            }
                            else if(data.statuspkm == 12){
                                return 'Juara Favorit Poster';
                            }
                             else{
                                return '<label class="label label-danger">Kesalahan Sistem</label>';
                            }
                        }
                    },
                    {
                        data: 'judul'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return '<button data-kode="'+data.id+'" data-toggle="tooltip" title="Lihat PKM '+data.nama+'" class="lihat-pkm btn btn-mini btn-default"><i class="icofont icofont-eye-alt"></i></button>';
                        }
                    }
                ]
        });

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });

        
        $('#bimbinganTable tbody').on('click', '.lihat-pkm', function() {
            var id = $(this).data('kode');
            window.location.replace('{{url("/admin/pkm")}}/'+id);
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

@section('content')

<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
                    <h5>List PKM Bimbingan 
                        @if($dosen->jns_kel_dosen == "L")
                         Pak 
                        @else
                         Bu 
                        @endif 
                        {{$dosen->nama_dosen}}</h5>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table id="bimbinganTable" class="table table-striped table-bordered nowrap" width="100%">
                            <thead>
                                <th width="12px">Ketua PKM</th>
                                <th width="12px">Prodi Ketua</th>
                                <th>Telepon</th>
                                <th width="23px">Status</th>
                                <th width="16px">Skim PKM</th>
                                <th>Judul PKM</th>
                                <th>Aksi</th>
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