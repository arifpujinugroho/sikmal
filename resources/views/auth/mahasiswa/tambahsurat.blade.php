@extends('layout.master')

@section('title')
Tambah Pengajuan Surat
@endsection

@section('header')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')
{{-- Page-body start --}}
    <div class="page-body">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <form method="POST" action="{{ url('mhs/tambahpkm') }}" enctype="multipart/form-data" class="form-validation" id="number_form">
                    @csrf
                    <div class="form-group">
                        <label>Jenis Surat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list-alt fa-fw"></i></span>
                            <select class="validate[required] form-control" name="tipepkm" id="tipepkm">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="{{Crypt::encryptString("1") }}">Observasi</option>
                                <option value="{{Crypt::encryptString("2") }}">Keterangan PKM</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Surat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list-alt fa-fw"></i></span>
                            <select class="validate[required] form-control" name="tipepkm" id="tipepkm">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="{{Crypt::encryptString("1") }}">Observasi</option>
                                <option value="{{Crypt::encryptString("2") }}">Keterangan PKM</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>PKM yang dipilih</label>
				    	<div class="input-group">
				    		<span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
				    		<input type="text" name="judulpkm" id="judulpkm" class="validate[required] form-control" placeholder="Pilih PKM" readonly>
                            <input type="hidden" name="kode" id="kode">
				    		<span class="input-group-addon cursor-hand" id="search" data-toggle="modal" data-target="#myModal">Cari <i class="fa fa-search fa-fw"></i></span>
				    	</div>
				    	<p class="help-block"><strong>Silahkan ketik Kode PKM dan klik tombol cari terlebih dahulu.</strong></p>
				    </div>
                    <hr>
                            <span class="text-danger" id="text_akhir">Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman</span>
                            <button type="submit" id="Simpan" class="btn btn-success m-b-0 f-right">Simpan</button>
                </form>

            </div>
        </div>
    </div>
    {{-- Page-body end --}}

@endsection

@section('end')
 <!-- Modal -->
 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Modal title</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
             </div>
             <div class="modal-body">
                <table id="lookup" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tahun</th>
                            <th>Judul</th>
                            <th>Skim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n=1; ?>
                        @foreach($pkm as $data)
                        <tr class="pilih" data-kode="{{Crypt::encrypt(Crypt::encryptString($data->id_file_pkm))}}" data-judul="<?php echo $data->judul; ?>" >
                            <td>{{$n}}
                            <td>{{$data->tahun}}</td>
                            <td>{{$data->judul}}</td>
                            <td>{{$data->skim_singkat}}</td>
                        </tr>
                        <?php $n++; ?>
                        @endforeach
                    </tbody>
                </table>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             </div>
         </div>
     </div>
 </div>
@endsection

@section('footer')
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

<script type="text/javascript">
    $(document).on('click', '.pilih', function (e) {
        document.getElementById("judulpkm").value = $(this).attr('data-judul');
        document.getElementById("kode").value = $(this).attr('data-kode');
        $('#myModal').modal('hide');
    });

     $(function () {
        $("#lookup, #lookup2").dataTable();
    });
</script>
@endsection
