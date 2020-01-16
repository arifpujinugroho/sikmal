@extends('layout.master')

@section('title')
Mahasiswa UNY
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
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                                </ul>
                            </div>
                            <h5>List Mahasiswa UNY <small>(Serverside/Live Server)</small></h5>
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="mhsTable" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nim Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Ukuran Baju</th>
                                        <th>Telepon</th>
                                        <th>Telepon Cadangan</th>
                                        <th>Alamat</th>
                                        <th>Pass Simbelmawa</th>
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
<div class="modal fade" id="lihat-mahasiswa" tabindex="-1" role="dialog" aria-labelledby="tambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="tambahAnggotaLabel">Lihat Mahasiswa</h4>
            </div>
            <div class="modal-body">
                    <form action="{{url('admin/ubahdatamhs')}}" id="formulir" class="form-validation" method="POST">
                    @csrf
                    <input type="hidden" name="kode" id="kode">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
                            <input type="text" id="nama" class="validate[required] form-control" placeholder="Nama Mahasiswa" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
                            <input type="text" id="nim" class="validate[required] form-control" placeholder="NIM Mahasiswa" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>
                            <input type="text" id="prodi" class="validate[required] form-control" placeholder="Prodi Mahasiswa" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-venus-mars fa-fw"></i></span>
                            <input type="text" id="jns_kelamin" class="validate[required] form-control" placeholder="Jenis Kelamin Mahasiswa" readonly>
                        </div>
                    </div>
                    <fieldset id="form" disabled>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                            <textarea class="validate[required] form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat Anggota"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-whatsapp fa-fw"></i></span>
                            <input type="text" name="telepon" id="telepon" class="validate[custom[number]] form-control" placeholder="Telepon Anggota">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                            <input type="text" name="backup_telepon" id="backup_telepon" class=" form-control" placeholder="Telepon Candangan (Optional)">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-shirtsinbulk fa-fw"></i></span>
                            <select class="validate[required] form-control" name="ukuranbaju" id="ukuranbaju">
                                <option value="">-- Pilih Ukuran Baju --</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">XXXL</option>
                                <option value="XXXXL">XXXXL</option>
                            </select>
                        </div>
                    </div>
                </fieldset>    
            </div>
            <div class="modal-footer">
                <button type="submit" id="simpan" class="btn btn-success">Simpan <i class="fa fa-save"></i></button>
        </form>
                <button id="edit" class="editData btn btn-warning">Edit <i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
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
    
<!-- Custom js -->
<script>
    $(document).ready(function() {
        var table = $('#mhsTable').DataTable({
                'dom': 'Bfrtip',
                'buttons': [
                //    {
                //    extend: 'excelHtml5',
                //    exportOptions: {
                //        columns: ':visible'
                //    }
                //},
                 'colvis'],
                "ordering": true,
                "language": {
                    "processing": "<i class='fa fa-spinner fa-spin fa-3x fa-fw'></i> <strong class='text-danger'> Silakan Tunggu...</strong>"
                },
                "serverSide": true,
                "responsive" : true,
                "info": true,
                'serverMethod': 'get',
                "paging": true,
                "processing": true,
                'ajax': {
                    'url': '{{url("/admin/user/datamhs")}}',
                    //'dataSrc': '',
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
                        data: null,
                        render: function(data, type, full, row) {
                            if(data.jenis_kelamin == "L"){
                                return "Laki-laki";
                            }else{
                                return "Perempuan";
                            }
                        }
                    },
                    {
                        data: 'ukuranbaju'
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
                        data: null,
                        render: function(data, type, full, row) {
                            var nomer = data.backup_telepon;
                            if(nomer == null){
                                return "-"
                            }else{
                                if(nomer.slice(0, 1) == "0"){
                                    var wa = nomer.slice(1);
                                    return '<a href="https://wa.me/62'+wa+'" target="_blank"><i class="fa fa-whatsapp text-success" aria-hidden="true"></i> '+nomer+'</a>';
                                }else{
                                    return '<a href="https://wa.me/'+nomer+'" target="_blank"> <i class="fa fa-whatsapp text-success" aria-hidden="true"></i> '+nomer+'</a>';
                                }
                            }
                        }
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'pass_simbel'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            return '<button data-kode="'+data.id+'" data-nim="'+data.nim+'" data-nama="'+data.nama+'" data-prodi="'+data.nama_prodi+' - '+data.jenjang_prodi+'" data-jnskel="'+data.jenis_kelamin+'" data-alamat="'+data.alamat+'" data-telepon="'+data.telepon+'" data-butelpon="'+data.backup_telepon+'" data-ukuranbaju="'+data.ukuranbaju+'" data-toggle="tooltip" title="Lihat/Edit '+data.nama+'" class="lihatEditData btn btn-mini btn-default"><i class="icofont icofont-eye-alt"></i></button>';
                        }
                    }
                ]
        });

        $('#reload-tabel').click(function() {
            table.ajax.reload();
        });

        $('#mhsTable tbody').on('click', '.lihatEditData', function() {
            var jnskel = $(this).data('jnskel');
            $('#form').attr('disabled','disabled');
            $('#edit').show();
            $('#simpan').hide();
            $('#simpan').attr('disabled','disabled');
            $('#kode').val($(this).data('kode'));
            $('#nama').val($(this).data('nama'));
            $('#nim').val($(this).data('nim'));
            $('#prodi').val($(this).data('prodi'));
            if (jnskel == "L") {
                $('#jns_kelamin').val('Laki-laki');
            } else {
                $('#jns_kelamin').val('Perempuan');
            }
            $('#alamat').val($(this).data('alamat'));
            $('#telepon').val($(this).data('telepon'));
            $('#backup_telepon').val($(this).data('butelepon'));
            $('#ukuranbaju').val($(this).data('ukuranbaju'));
            $('#lihat-mahasiswa').modal('show');
        });

    });
</script>
<script>
    $('.editData').click(function(){
        $('#form').removeAttr('disabled');
        $('#edit').hide();
        $('#simpan').show();
        $('#simpan').removeAttr('disabled');
    });
</script>
@endsection