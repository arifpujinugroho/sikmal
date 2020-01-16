@extends('layout.master')

@section('title')
Pendanaan PKM
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
                <i class="icofont icofont-list bg-c-blue"></i>
                <div class="d-inline">
                    <h4>Laporan Keuangan</h4>
                    <span>Judul PKM : <strong class="text-danger">{{$pkm->judul}}</strong><br>Pendanaan Belmawa : <strong class="text-danger">Rp. {{number_format($pkm->dana_dikti, 0, ".", ".")}},-</strong></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Laporan Keuangan</a> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->
<!-- Page-body start -->
<div class="page-body">
    <input type="hidden" id="idpkm" value="{{$id}}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            Penggunaan Dana : Rp. <strong id="selfDana">#</strong>,-
                        </div>
                        <div class="col-md-4">
                            Presentase Penggunaan Dana : <strong id="persenDana">#</strong>%
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li id="reload-tabel" data-toggle="tooltip" title="Refresh Tabel"><i class="icofont icofont-refresh"></i></li>
                        </ul>
                    </div>
        </div>
        <div class="card-block">
            <div>
                <table id="danaTable" class="table table-striped" width="100%">
                    <thead>
                        <th>Aksi</th>
                        <th>Tanggal</th>
                        <th>Pembelian</th>
                        <th>Kategori</th>
                        <th>Toko</th>
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
    <!-- Basic Button table end -->
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
                <div class="form-group">
                  <label for="tPPh26">Jenis Transaksi</label>
                  <select class="form-control" name="ttolak" id="ttolak">
                    <option value="">--Acc?--</option>
                    <option value="1">Ya</option>
                    <option value="2">Tidak</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Komentar</label>
                  <input type="text" class="form-control" id="tkomentar" aria-describedby="helpId" placeholder="Komentar Transaksi">
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
<div class="modal fade" id="lihatSurat" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labellihatToko">Lihat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body" id="lihatBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-mini btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<!-- data-table js -->
<script src="{{url('assets/custom/admin-pendanaan.js')}}"></script>
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