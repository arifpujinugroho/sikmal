@extends('layout.master')

@section('title')
@if(Request::path() == 'admin/pilihpkm')
Detail PKM
@elseif(Request::path() == 'admin/pilihdownloadpkm')
Download PKM
@elseif(Request::path() == 'admin/pilihmaksdosen')
Maks Dosen
@elseif(Request::path() == 'admin/pilihakunsimbelmawa')
Akun Simbelmawa
@elseif(Request::path() == 'admin/pilihgrafik')
Pilih Grafik PKM
@elseif(Request::path() == 'admin/pilihpenil')
Pilih Set Penilaian
@elseif(Request::path() == 'admin/pilihnilpro')
Pilih Nilai Proposal
@elseif(Request::path() == 'admin/pilihnilin')
Pilih Nilai Interview
@elseif(Request::path() == 'admin/pilihkeuangan')
Pilih Laporan Keuangan
@endif
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-layout bg-c-blue"></i>
                    <div class="d-inline">
                        @if(Request::path() == 'admin/pilihpkm')
                        <h4>Detail PKM UNY</h4>
                        <span>Pilih Daftar Pengajuan PKM UNY</span>
                        @elseif(Request::path() == 'admin/pilihdownloadpkm')
                        <h4>Download PKM</h4>
                        <span>Pilih Daftar Download PKM UNY</span>
                        @elseif(Request::path() == 'admin/pilihmaksdosen')
                        <h4>Max Dosen</h4>
                        <span>Pilih Daftar Maksimal Dosen UNY</span>
                        @elseif(Request::path() == 'admin/pilihakunsimbelmawa')
                        <h4>Akun Simbelmawa</h4>
                        <span>Pilih Daftar Akun Simbelmawa Pengajuan PKM UNY</span>
                        @elseif(Request::path() == 'admin/pilihgrafik')
                        <h4>Grafik dan Rekapan PKM</h4>
                        <span>Pilih Daftar Rekapan PKM UNY</span>
                        @elseif(Request::path() == 'admin/pilihpenil')
                        <h4>Set Penilaian PKM</h4>
                        <span>Pilih Set Penilaian PKM</span>
                        @elseif(Request::path() == 'admin/pilihnilpro')
                        <h4>Nilai Proposal PKM</h4>
                        <span>Pilih Daftar Nilai Proposal</span>
                        @elseif(Request::path() == 'admin/pilihnilin')
                        <h4>Nilai Interview PKM</h4>
                        <span>Pilih Daftar Nilai Interview</span>
                        @elseif(Request::path() == 'admin/pilihkeuangan')
                        <h4>Laporan Keuangan</h4>
                        <span>Pilih Daftar Laporan Keuangan</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}">
                                <i class="icofont icofont-home"></i>
                            </a>
                        </li>
                <li class="breadcrumb-item"><a href="#!">PKM</a>
                </li>
            </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->
   
    

    
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <!-- Search result card start -->
                <div class="card">
                <div class="card-block  panels-wells">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            @if(Request::path() == 'admin/pilihpkm')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihdownloadpkm')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Download PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihmaksdosen')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Maksimal Dosen berdasarkan <strong class="text-danger">Tahun Anggaran</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihakunsimbelmawa')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Akun Simbelmawa PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihgrafik')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Rekapan PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihpenil')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Set Penilaian PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihnilpro')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Nilai Proposal PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihnilin')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Nilai Interview PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @elseif(Request::path() == 'admin/pilihkeuangan')
                            <p class="txt-highlight text-center m-t-20">Pilihlah Daftar Laporan Keuangan PKM berdasarkan <strong class="text-danger">Tahun Anggaran</strong> dan <strong class="text-danger">Tipe PKM</strong> yang ingin ditampilkan.</p>
                            @endif
                        </div>
                    </div>
                    <div class="row seacrh-header">
                        <div class="col-lg-6 offset-lg-3 offset-sm-6 col-sm-6 offset-sm-1 col-xs-12">
                            @if(Request::path() == 'admin/pilihgrafik')
                            <div class="panel panel-warning">
                                <div class="panel-heading bg-warning">
                            @else
                            <div class="panel panel-primary">
                                <div class="panel-heading bg-primary">
                            @endif
                                    @if(Request::path() == 'admin/pilihpkm')
                                    Detail PKM
                                    @elseif(Request::path() == 'admin/pilihdownloadpkm')
                                    Download PKM
                                    @elseif(Request::path() == 'admin/pilihmaksdosen')
                                    Maksimal Dosen
                                    @elseif(Request::path() == 'admin/pilihakunsimbelmawa')
                                    Akun Simbelmawa
                                    @elseif(Request::path() == 'admin/pilihgrafik')
                                    Rekapan PKM
                                    @elseif(Request::path() == 'admin/pilihpenil')
                                    Set Penilaian PKM
                                    @elseif(Request::path() == 'admin/pilihnilpro')
                                    Nilai Proposal PKM
                                    @elseif(Request::path() == 'admin/pilihnilin')
                                    Nilai Interview PKM
                                    @elseif(Request::path() == 'admin/pilihkeuangan')
                                    Laporan Keuangan PKM
                                    @endif
                                </div>
                                <div class="panel-body">
                                        @if(Request::path() == 'admin/pilihpkm')
                                            <p>Cari Pengajuan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="listpkm">
                                        @elseif(Request::path() == 'admin/pilihdownloadpkm')
                                            <p>Cari Download PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="downloadpkm">
                                        @elseif(Request::path() == 'admin/pilihmaksdosen')
                                            <p>Cari Daftar Maks Dosen PKM berdasarkan Tahun Anggaran</p>
                                            <input type="hidden" id="link" value="listmaksdosen">
                                        @elseif(Request::path() == 'admin/pilihakunsimbelmawa')
                                            <p>Cari Akun Simbelmawa PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="akunsimbel">
                                        @elseif(Request::path() == 'admin/pilihgrafik')
                                            <p>Pilih Rekapan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="grafikpkm">
                                        @elseif(Request::path() == 'admin/pilihpenil')
                                            <p>Pilih Set Penilaian PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="setpenil">
                                        @elseif(Request::path() == 'admin/pilihnilpro')
                                            <p>Pilih Nilai Proposal PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="nilpro">
                                        @elseif(Request::path() == 'admin/pilihnilin')
                                            <p>Pilih Nilai Interview PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="nilin">
                                        @elseif(Request::path() == 'admin/pilihkeuangan')
                                            <p>Pilih Laporan Keuangan PKM berdasarkan Tahun dan Tipe PKM</p>
                                            <input type="hidden" id="link" value="listkeuangan">
                                        @endif
                                            <div class="form-group">
                                                <select id="tahun" name="tahunpkm" class="form-control">
                                                    @foreach ($tahun as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tahun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if(Request::path() != 'admin/pilihmaksdosen')
                                                <select id="tipe" name="tipepkm" class="form-control">
                                                    @foreach ($tipe as $d)
                                                    <option value="{{ $d->id }}">{{ $d->tipe }}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                        </div>
                                        @if(Request::path() == 'admin/pilihgrafik')
                                        <div class="panel-footer text-warning">
                                            <button id="goLink" type="submit" class="btn btn-sm btn-warning">Cari</button>
                                        @else
                                        <div class="panel-footer text-primary">
                                            <button id="goLink" type="submit" class="btn btn-sm btn-primary">Cari</button>
                                        @endif
                                            
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Search result card end -->
            
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
$('#goLink').click(function(){
 var link = $('#link').val();
 var thn = $('#tahun').val();
 var tipe = $('#tipe').val();
 if(link == "listmaksdosen"){
 window.open('{{url("admin")}}/'+link+'/'+thn,'_self');
 }else{
 window.open('{{url("admin")}}/'+link+'/'+thn+'/'+tipe,'_self');
 }
});
</script>
@endsection