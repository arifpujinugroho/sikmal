@extends('layout.master')

@section('title')
List {{$ntipe}} {{$ntahun}} ({{date('d-m-Y')}})
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
        var table = $('#list-table').DataTable({
                'dom': 'Bfrtip',
                'buttons': [{
                    extend: 'excelHtml5',
                    //exportOptions: {
                    //    columns: ':visible'
                    //}
                }, 'colvis'],
                "columnDefs": [
                    { "width": "5%", "targets": 0 }
                ],
                "ordering": true,
                //"serverSide": true,
                "responsive" : true,
                "info": true,
                'serverMethod': 'get',
                "paging": true,
                "processing": true,
                'ajax': {
                    'url': '{{url("/admin/datalistpkm")}}/{{$tahunpkm}}/{{$tipepkm}}',
                    'dataSrc': '',
                },
                'columns': [
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nim'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return data.nama_prodi + ' - ' + data.jenjang_prodi;
                        }
                    },
                    {
                        data: 'nama_dosen'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            if((data.nidn_dosen == "" || data.nidn_dosen == null) && (data.nidk_dosen == "" || data.nidk_dosen == null)){
                                return 'NIP. '+ data.nip_dosen;
                            }else{
                                if(data.nidn_dosen == "" || data.nidn_dosen == null){
                                    return 'NIDK. '+data.nidk_dosen;
                                }else{
                                    return 'NIDN. '+ data.nidn_dosen;
                                }
                            }
                        }
                    },
                    {
                        data: 'nama_singkat'
                    },
                    {
                        data: 'skim_singkat'
                    },
                    {
                        data: 'judul'
                    },
                    {
                        data: 'keyword'
                    },
                    @if($tipepkm == "1")
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            var bilangan = data.dana_pkm;
                            var	reverse = bilangan.toString().split('').reverse().join(''),
	                            ribuan 	= reverse.match(/\d{1,3}/g);
	                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return 'Rp. '+ribuan;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return data.durasi+' Bulan';
                        }
                    },
                    @endif
                    @if($tipepkm == "3")
                    {
                        data: 'linkurl'
                    },
                    @endif
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
                        data: null,
                        render: function(data, type, full, row) {
                            if (data.confirm_up == "Y") {
                                return '<label class="label label-success">Sudah Konfirmasi</label>';
                            } else {
                                return '<label class="label label-danger">Belum Lolos Upload/Belum Konfirmasi</label>';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            if (data.self == "N") {
                                return '<label class="label label-success">Keinginan Sendiri</label>';
                            } else {
                                return '<label class="label label-warning">Atas Kewajiban</label>';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            if (data.file_proposal == null) {
                                return '<label class="label label-danger">Belum Upload Proposal</label>';
                            } else {
                                return '<label class="label label-success">Sudah Upload Proposal</label>';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return '<div class="btn-group">'+
                                   '<button data-kode="'+data.id+'" data-toggle="tooltip" title="Anggota PKM '+data.nama+'" class="anggota-pkm btn btn-mini btn-default"><i class="icofont icofont-people"></i></button>'+
                                   '<button data-kode="'+data.id+'" data-toggle="tooltip" title="Lihat PKM '+data.nama+'" class="lihat-pkm btn btn-mini btn-primary"><i class="icofont icofont-eye-alt"></i></button>'+
                                   '</div>';
                        }
                    }
                ]
        });

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });
    
        $('#list-table tbody').on('click', '.anggota-pkm', function(event) {
            event.preventDefault();
            $.ajax({
                    url: '{{ URL::to("/admin")}}/anggota-pkm',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        kode: $(this).data('kode')
                    },
                })
                    
                .done(function(data) {
                    content = $('<div></div>');
                    content.append('<h5 class="text-danger">Ketua</h5><br>');
                    content.append('<p>NIM : ' + data.ketua.nim + '</p>');
                    content.append('<p>Nama : ' + data.ketua.nama + '</p>');
                    content.append('<p>Telp. : ' + data.ketua.telepon + '</p>');
                    content.append('<p>Prodi : ' + data.ketua.nama_prodi + ' - ' + data.ketua.jenjang_prodi + '</p>');
                    content.append('<p>Fakultas : ' + data.ketua.nama_fakultas + '</p>');
                    // content.append('<p>Fakultas : ' + data.ketua.email + '</p>');
                    content.append('<br>');
                    $.each(data.anggota, function(index, val) {
                        content.append('<hr>');
                        content.append('<h5>Anggota ' + (index + 1) + '</h5>');
                        content.append('<p>NIM : ' + val.nim + '</p>');
                        content.append('<p>Nama : ' + val.nama + '</p>');
                        content.append('<p>Telp. : ' + val.telepon + '</p>');
                        content.append('<p>Prodi : ' + val.nama_prodi + ' - ' + val.jenjang_prodi + '</p>');
                        content.append('<p>Fakultas : ' + val.nama_fakultas + '</p>');
                        content.append('<br>');
                    });
                    $('#modalAnggota .modal-body').html(content);
                    $('#modalAnggota').modal('show');
                })
                .fail(function() {
                    new PNotify({
                        title: 'Kesalahan Server!',
                        text: 'Terjadi Kesalahan, Silakan Reaload Sistem',
                        icon: 'icofont icofont-info-circle',
                        type: 'error'
                    });
                    console.log('Error!');
                });
        });

        
        $('#list-table tbody').on('click', '.lihat-pkm', function() {
            var id = $(this).data('kode');
            window.open('{{url("/admin/pkm")}}/'+id, '_blank');
            //window.location.replace('{{url("/admin/pkm")}}/'+id);
        });


    });
</script>
<script>
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
                    LIST {{$ntipe}} {{$ntahun}} <br><small>({{TglIndo::Tgl_indo(date('Y-m-d'))}})</small>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table id="list-table" class="table table-striped" style="width:100%">
                            <thead>
                                <th width="10%">Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th width="10%">Prodi</th>
                                <th>Dosbing</th>
                                <th>Email</th>
                                <th>NIDN/NIDK/NIP</th>
                                <th>Fakultas</th>
                                <th>Skim PKM</th>
                                <th>Judul</th>
                                <th>Kata Kunci</th>
                                @if($tipepkm == "1")
                                <th>Dana PKM</th>
                                <th>Durasi</th>
                                @endif
                                @if($tipepkm == "3")
                                <th>Link Video</th>
                                @endif
                                <th>Status</th>
                                <th>Konfirmasi</th>
                                <th>Usulan</th>
                                <th>File</th>
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

@section('end')

<div class="modal fade" id="modalAnggota" tabindex="-1" role="dialog" aria-labelledby="modalAnggota" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Anggota</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection