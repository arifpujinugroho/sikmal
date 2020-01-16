@extends('layout.master')

@section('title')
Nota {{$nota->nama_toko}}
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
    <input type="hidden" id="id" value="{{$id}}">
    <input type="hidden" id="encrypt" value="{{$encrypt}}">
    <input type="hidden" id="namanota" value="{{$nota->nama_toko}}">
    <input type="hidden" id="idtoko" value="{{$nota->id_toko}}">
    <input type="hidden" id="idpkm" value="{{$nota->id_file_pkm}}">
    <input type="hidden" id="kenaPajak">
    <div class="row">
    <!-- Tasks Sale Start -->
    <div class="col-md-12 col-xl-12 ">
        <div class="card task-sale-card">
            <div class="card-header ">
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li id="reload-tabel" data-toggle="tooltip" title="Refresh Data"><i class="icofont icofont-refresh"></i></li>
                    </ul>
                </div>
                <div class="card-header-left">
                    <h5>Data Toko <strong class="text-primary">{{$nota->nama_toko}}</strong></h5>
                </div>
            </div>
            <div class="card-block-big ">
                <div class="row">
                    <div class="col-sm-6 ">
                        <h4 class="text-c-green d-inline-block m-b-20 f-50" id="jmlBrg">#</h4>
                        <div class="d-inline-block m-l-5 super ">
                            <p class="text-muted  m-b-0 f-w-400 ">Jumlah Transaksi</p>
                            <p class="text-muted  m-b-0 f-w-400 ">Pembelian Toko</p>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <h5 class="text-muted d-inline-block m-b-20 f-50" id="ttlBrg">#</h5>
                    </div>
                </div>
                <div class="row p-b-20" id="htmlPajak">
                    <div class="col-sm-6" id="BtnSiup">
                        <button class="btn btn-mini btn-success">Lihat SIUP</button>
                        <button class="btn btn-mini btn-warning">Update SIUP</button>
                    </div>
                    <div class="col-sm-6 " id="BtnNpwp">
                        <button class="btn btn-mini btn-success">Lihat NPWP</button>
                        <button class="btn btn-mini btn-warning">Update NPWP</button>
                    </div>
                </div>
                <div class="row p-t-20 b-t-default">
                    <div class="col-sm-6 ">
                        <h3 class="text-muted d-inline-block m-b-20 "><i class="fa fa-building" aria-hidden="true"></i></h3>
                        <div class="d-inline-block m-l-5 top m-t-10">
                            <p class=" m-b-0 f-w-400 f-14 text-uppercase">{{$nota->nama_toko}}</p>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <h3 class="text-muted d-inline-block m-b-20 "><i class="fa fa-calendar" aria-hidden="true"></i></h3>
                        <div class="d-inline-block m-l-5 top m-t-10">
                            <p class=" m-b-0 f-w-400 f-14 text-uppercase">{{TglIndo::Tgl_indo($nota->tgl_nota)}}</p>
                        </div>
                    </div>
                </div>
                <div class="m-t-5">
                    <img src="{{url('storage/files/nota/'.$nota->file_nota)}}" alt="Nota" class="img-rounded top img-50">
                    <div class="d-inline-block m-l-50  m-t-15">
                        <span class="f-w-400 f-16 text-c-green"><button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#lihatNotaModal">Lihat Nota</button></span>
                    </div>
                    <div class="d-inline-block m-l-50 m-t-15 ">
                        <span class="f-w-400 f-16 text-c-green"><button class="btn btn-sm btn-outline-warning" id="uploadUlangBtn">Upload Ulang Nota</button></span>
                    </div>
                    <div class="d-inline-block m-l-50  m-t-15">
                        <span class="f-w-400 f-16 text-c-green"><button class="btn btn-sm btn-outline-primary" id="editNotaBtn">Edit Nota</button></span>
                    </div>
                    <div class="d-inline-block m-l-50 m-t-15 ">
                        <span class="f-w-400 f-16 text-c-green"><button class="btn btn-sm btn-outline-danger" id="hapusNotaBtn">Hapus Nota</button></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="table-responsive">
                    <table id="danaTable" class="table table-striped" width="100%">
                        <thead>
                            <th>Aksi</th>
                            <th>Pembelian</th>
                            <th>Kategori</th>
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
    </div>
</div>
</div>
<!-- Page-body end -->
@endsection















@section('end')
<!-- Modal -->
<div class="modal fade" id="transaksiModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaksiTitle">Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <fieldset id="formTransaksi">
                    <input type="hidden" id="id_transaksi">
                <div class="form-group">
                  <label for="">Nama Toko</label>
                  <input type="text" class="form-control" aria-describedby="helpId" placeholder="Nama Toko" value="{{$nota->nama_toko}}" readonly>
                </div>
                <div class="form-group">
                  <label for="">Tanggal Nota</label>
                  <input type="text" class="form-control" aria-describedby="helpId" placeholder="Tanggal" value="{{TglIndo::Tgl_indo($nota->tgl_nota)}}" readonly>
                </div>
                <div class="form-group">
                  <label for="">Nama Pembelian</label>
                  <input type="text" class="form-control" id="tNamaPembelian" aria-describedby="helpId" placeholder="Nama Pembelian">
                </div>
                <div class="form-group">
                  <label for="">Jenis Pembelian</label>
                  <select class="form-control" id="tKategori">
                    <option value="">-- Jenis Pembelian --</option>
                    @foreach($kate as $k)
                    <option value="{{$k->id}}">{{$k->nama_kategori}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Volume</label>
                  <input type="number" class="form-control" id="tVolume" aria-describedby="helpId" placeholder="Contoh 10 atau 2.5">
                </div>
                <div class="form-group">
                  <label for="">Harga Satuan</label>
                  <input type="number" class="form-control" id="tHargaSatuan" aria-describedby="helpId" placeholder="Contoh 20000">
                </div>
                <div class="form-group" id="ftJumlah">
                  <label for="">Jumlah</label>
                  <input type="number" class="form-control" id="tJumlah" aria-describedby="helpId" placeholder="Jumlah" readonly>
                </div>
                <div class="form-group">
                  <label for="tPPn">PPn</label>
                  <select class="form-control" name="tPPn" id="tPPn">
                    <option value="">0%</option>
                    <option value="1">10%</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="tPPh21">PPh21</label>
                  <select class="form-control" name="tPPh21" id="tPPh21">
                    <option value="">0%</option>
                    <option value="1">2,5%</option>
                    <option value="2">5%</option>
                    <option value="3">6%</option>
                    <option value="4">15%</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="tPPh22">PPh22</label>
                  <select class="form-control" name="tPPh22" id="tPPh22">
                    <option value="">0%</option>
                    <option value="1">1,5%</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="tPPh23">PPh23</label>
                  <select class="form-control" name="tPPh23" id="tPPh23">
                    <option value="">0%</option>
                    <option value="1">2%</option>
                    <option value="2">4%</option>
                    <option value="3">2% Khusus</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="tPPh26">PPh26</label>
                  <select class="form-control" name="tPPh26" id="tPPh26">
                    <option value="">0%</option>
                    <option value="1">2%</option>
                  </select>
                </div>
                <div class="form-group" id="ftsiup">
                  <label for="tsiup">SIUP</label>
                  <input type="file" class="form-control-file" name="tsiup" id="tsiup" placeholder="SIUP" aria-describedby="fileHelpId">
                </div>
                <div class="form-group" id="ftnpwp">
                  <label for="tsiup">NPWP</label>
                  <input type="file" class="form-control-file" name="tnpwp" id="tnpwp" placeholder="NPWP" aria-describedby="fileHelpId">
                </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" id="tambahTransaksiBtn" class="btn btn-mini btn-primary">Tambah</button>
                <button type="button" id="editTransaksiBtn" class="btn btn-mini btn-warning">Edit</button>
                <button type="button" id="saveTransaksiBtn" class="btn btn-mini btn-success">Simpan</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="lihatNotaModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                <img src="{{url('storage/files/nota/'.$nota->file_nota)}}" class="img-fluid" alt="Nota">
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="uploadUlangModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Ulang Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{url('mhs/uploadulangnota')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{$encrypt}}" name="encrypt">
            <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                              <label for="">Foto Nota (max 5 Mb)</label>
                              <input type="file" class="form-control" name="filenota" id="filenota" placeholder="Nota" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-mini btn-primary">Save</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editNotaModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{url('mhs/editnota')}}" method="post" class="form-validation">
            @csrf
            <input type="hidden" value="{{$encrypt}}" name="encrypt">
            <div class="modal-body">
                    <div class="form-group">
                        <label>Toko Pembelian</label>
				    	<div class="input-group">
				    		<span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
				    		<input type="text" name="namatoko" id="namatoko" class="validate[required] form-control" placeholder="Pilih Toko" value="{{$nota->nama_toko}}" readonly>
                            <input type="hidden" name="id_toko" id="id_toko" class="validate[required]" value="{{$nota->id_toko}}">
				    		<span class="input-group-addon cursor-hand" id="search">Cari <i class="fa fa-search fa-fw"></i></span>
				    	</div>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal Pembelian</label>
                        <div class="input-group">
				    	<span class="input-group-addon"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i></span>
                        <input type="date" name="tgl_nota" id="tgl_nota" class="validate[required] form-control" placeholder="Tanggal Pembelian" value="{{$nota->tgl_nota}}" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-mini btn-primary">Save</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>
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



<div class="modal fade" id="uploadSiupNpwp" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Upload SIUP NPWP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                              <label for="">SIUP (max 5 Mb) <small class="text-danger">*</small></label>
                              <input type="file" class="form-control"  id="filesiup" placeholder="SIUP" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                              <label for="">NPWP (max 5 Mb)</label>
                              <input type="file" class="form-control" id="filenpwp" placeholder="NPWP" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="saveSiupNPWP" class="btn btn-mini btn-primary">Simpan</button>
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection


@section('footer')
<!-- data-table js -->
<script src="{{url('assets/custom/mhs-lihatnota.js')}}"></script>
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