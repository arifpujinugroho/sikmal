@extends('layout.master')

@section('title')
List Cari Jodoh Kelompok
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
                    <i class="icofont icofont-emo-heart-eyes bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>Cari Jodoh Kelompok</h4>
                        <span class="text-info">Daftar Tim yang kekurangan <del class="text-muted">kasih sayang</del> Anggota</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Biro Jodoh</a> </li>
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
                        @if($jmlme <= 2)
                        <div class="card-header">
                            <button data-toggle="modal" data-target="#tambah-cardoh" class="tambahCardoh btn btn-sm btn-success f-right">Tambah CarDoh</button>
                        </div>
                        @endif
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="OkeDulu" class="table table-striped">
                                    <thead>
                                        <th>Nama Ketua Tim</th>
                                        <th>NIM</th>
                                        <th>Prodi</th>
                                        <th>Kurang Anggota</th>
                                        <th>Skim PKM</th>
                                        <th>Kebutuhan</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @if ($jmlall != 0 || $jmlme != 0)
                                            @if($jmlme != 0)
                                                @foreach ($me as $m)
                                                <tr>
                                                    <td>{{$m->nama}}</td>
                                                    <td>{{$m->nim}}</td>
                                                    <td>{{$m->nama_prodi}}</td>
                                                    <td>{{$m->jml_anggota}}</td>
                                                    <td>{{$m->cardoh_skim}}</td>
                                                    <td>{{$m->kebutuhan}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button data-kode="{{Crypt::encryptString($m->id_cardoh)}}" data-nama="{{$m->nama}}" data-jml="{{$m->jml_anggota}}" data-skim="{{$m->cardoh_skim}}" data-kebutuhan="{{$m->kebutuhan}}" class="editCardoh btn btn-mini btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></button>
                                                            <button data-kode="{{Crypt::encryptString($m->id_cardoh)}}" class="hapusCardoh btn btn-mini btn-danger" data-toggle="tooltip" title="hapus"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            @foreach ($all as $a)
                                                <tr>
                                                    <td>{{$a->nama}}</td>
                                                    <td>{{$a->nim}}</td>
                                                    <td>{{$a->nama_prodi}}</td>
                                                    <td>{{$a->jml_anggota}}</td>
                                                    <td>{{$a->cardoh_skim}}</td>
                                                    <td>{{$a->kebutuhan}}</td>
                                                    <td>
                                                        <button data-email="{{$a->email}}" data-nama="{{$a->nama}}" class="lihatCardoh btn btn-mini btn-warning" data-toggle="tooltip" title="Hubungi"><i class="fa fa-info"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center f-s-italic">Belum ada List Cari Jodoh PKM</td>
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

@section('end')
<!-- Modal -->
<div class="modal fade" id="tambah-cardoh" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Cari Jodoh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{url('mhs/cardoh/tambahanggota')}}" method="post">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                      <label>Skim PKM</label>
                      <select class="form-control" name="skim" required>
                        <option value="">--Skim PKM--</option>
                        @foreach ($skim as $s)
                            <option value="{{$s->skim_singkat}}">{{$s->skim_singkat}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kurang Anggota</label>
                      <select class="form-control" name="jml_anggota" required>
                        <option value="">--Kurang Anggota--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kebutuhan</label>
                      <input type="text"
                        class="form-control" name="kebutuhan" placeholder="Kebutuhan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-cardoh" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{url('mhs/cardoh/editanggota')}}" method="post">
            @csrf
            <input name="kode" type="hidden" id="kode">
                <div class="modal-body">
                    <div class="form-group">
                      <label>Skim PKM</label>
                      <select class="form-control" name="skim" id="skim" required>
                        <option value="">--Skim PKM--</option>
                        @foreach ($skim as $s)
                            <option value="{{$s->skim_singkat}}">{{$s->skim_singkat}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kurang Anggota</label>
                      <select class="form-control" name="jml_anggota" id="jml_anggota" required>
                        <option value="">--Kurang Anggota--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kebutuhan</label>
                      <input type="text"
                        class="form-control" name="kebutuhan" id="kebutuhan" placeholder="Kebutuhan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="lihat-cardoh" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hubungi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body text-center">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

<script>
        
$(document).ready(function () {
    $('#OkeDulu').DataTable( {
        "ordering" : true,
        "info" :     false,
      "responsive" : true,
    });

    $('#OkeDulu tbody').on( 'click', '.lihatCardoh', function () {
        var nama = $(this).data('nama');
        var email = $(this).data('email');
        $('#lihat-cardoh .modal-body').html('Berikut adalah email '+nama+'<br><a href="mailto:'+email+'" target="_blank">'+email+'</a>');
        $('#lihat-cardoh').modal('show');
    } );
    
    $('#OkeDulu tbody').on( 'click', '.editCardoh', function () {
        $('#kode').val($(this).data('kode'));
        $('#skim').val($(this).data('skim'));
        $('#kebutuhan').val($(this).data('kebutuhan'));
        $('#jml_anggota').val($(this).data('jml'));
        $('#edit-cardoh').modal('show');
    } );

    $('#OkeDulu tbody').on( 'click', '.hapusCardoh', function () {
        var code = "{{ csrf_token() }}";
        var kode = $(this).data('kode');
        swal({
            title: "Hapus?",
            text: "kamu mau menghapus list ini?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                url: "{{url('mhs/cardoh/hapus')}}",
                type: "POST",
                data: {_token : code, kode: kode},
                success: function () {
                    swal("Dihapus!", "Berhasil dihapus", "success");
                    setTimeout(function () {
			        	location.reload();
			        }, 1500);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Error Hapus!", "Silakan Coba Lagi", "error");
                }
            });
        });
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

    <script src="{{url('assets/pages/data-table/js/data-table-custom.js')}}"></script>
@endsection
