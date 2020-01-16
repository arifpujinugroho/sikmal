@extends('layout.master')

@section('title')
Tambah Nota Pembelian
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
                <form method="POST" action="{{ url('mhs/tambahnota') }}" enctype="multipart/form-data" class="form-validation" id="number_form">
                    @csrf
                    <input type="hidden" name="encrypt" value="{{$encrypt}}">
                    <div class="form-group">
                        <label>Toko Pembelian</label>
				    	<div class="input-group">
				    		<span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
				    		<input type="text" name="namatoko" id="namatoko" class="validate[required] form-control" placeholder="Pilih Toko" readonly>
                            <input type="hidden" name="id_toko" id="id_toko" class="validate[required]">
				    		<span class="input-group-addon cursor-hand" id="search" data-toggle="modal" data-target="#myModal">Cari <i class="fa fa-search fa-fw"></i></span>
				    	</div>
                    </div>
                    <div id="form_tambahan">
                        <div class="form-group">
                            <label for="">Tanggal Pembelian</label>
                            <div class="input-group">
				        	<span class="input-group-addon"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i></span>
                            <input type="date" name="tgl_nota" id="tgl_nota" class="validate[required] form-control" placeholder="Tanggal Pembelian">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="filenota">File Nota</label>
                            <div class="input-group">
				        		<span class="input-group-addon"><i class="fa fa-file-image-o fa-fw"></i></span>
                                <input type="file" class="validate[required] form-control form-control-file" name="filenota" id="filenota" placeholder="File Nota" aria-describedby="fileHelpId">
                            </div>
                          <small id="fileHelpId" class="form-text text-danger">File Gambar max 5 MB </small>
                        </div>
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
                 <h5 class="modal-title">Daftar Toko</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
             </div>
             <div class="modal-body">
                <div class="table-responsive">
                <table id="lookup" class="table table-hover table-striped" width="100%">
                    <thead>
                        <tr>
                            <th style="max-width:10%">Aksi</th>
                            <th style="max-width:45%">Nama Toko</th>
                            <th style="max-width:45%">Alamat</th>
                        </tr>
                    </thead>
                </table>
                </div>
                <hr>
                <p>Jika Toko tidak terdapat di tabel ini maka klik => <button id="tambahModal" class="btn btn-mini btn-warning">Tambah Toko</button></p>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
             </div>
         </div>
     </div>
 </div>

 
<div class="modal fade" id="tambahLink" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_link">Tambah Toko</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_link">
                <div class="form-group">
                  <label for="judulfile">Nama Toko</label>
                  <input type="text" class="form-control" id="formnamatoko" placeholder="Nama Toko">
                </div>
                <div class="form-group">
                  <label for="judulfile">Alamat Toko</label>
                  <input type="text" class="form-control" id="formalamattoko" placeholder="Alamat">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="tbh_toko" class="btn btn-mini btn-primary">Tambah</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script src="{{url('assets/custom/mhs-tambahnota.js')}}"></script>
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
@endsection
