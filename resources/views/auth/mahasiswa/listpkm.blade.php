@extends('layout.master')

@section('title')
List PKM Mahasiswa
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>List PKM Mahasiswa</h4>
                        <span><strong> Status PKM : </strong> </span>

                        @if ($modeupload == 0)
                        <span><label class="label label-danger" data-toggle="tooltip" title="Semua PKM OFF">Tidak ada PKM yang Aktif</label></span>
                        @endif

                        @if($lima->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="5 Bidang ON">5 Bidang</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="5 Bidang OFF">5 Bidang</label></span>--}}
                        @endif

                        @if($dua->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="2 Bidang ON">2 Bidang</label></span>
                        {{--@else
                        <span><label class="label label-danger" data-toggle="tooltip" title="2 Bidang OFF">2 Bidang</label></span>--}}
                        @endif

                        @if($gfk->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="PKM GFK ON">PKM GFK</label></span>
                        {{--@else
                            <span><label class="label label-danger" data-toggle="tooltip" title="PKM GFK OFF">PKM GFK</label></span>--}}
                        @endif

                        @if($sug->status_tambah == 1)
                        <span><label class="label label-success" data-toggle="tooltip" title="SUG ON">SUG</label></span>
                        {{--@else
                            <span><label class="label label-danger" data-toggle="tooltip" title="SUG OFF">SUG</label></span>--}}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">List PKM</a> </li>
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
                        <span><strong>Jumlah PKM yang pernah diikuti : {{ $jml }}</strong></span>
                        @if ($modeupload > 0)
                        <div class="f-right"><button id="tambahUpload" class="btn btn-success btn-sm">Tambah PKM</button></div>
                        @else
                        <div class="f-right"><a class="offupload" href="#!"><button class="btn btn-dark btn-sm disabled">Tambah PKM</button></a></div>
                        @endif
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th width="12px">Tahun PKM</th>
                                        <th width="16px">Skim PKM</th>
                                        <th>Judul PKM</th>
                                        <th>Peran</th>
                                        <th width="23px">Status</th>
                                        <th width="12px">Aksi</th>
                                    </thead>
                                    <tbody>
                                        @if ($jml != 0)
                                            @foreach ($list as $t)
                                                <tr>
                                                    <td class="text-center">{{ $t->tahun }}</td>
                                                    <td class="text-center">{{ $t->skim_singkat }}</td>
                                                    <td>{{ $t->judul }}</td>
                                                    <td class="text-capitalize">{{ $t->jabatan}}</td>
                                                    <td>
                                                        @if ($t->file_proposal == Null)
                                                            <strong class="text-danger">Input Belum Lengkap</strong>
                                                        @else
                                                            @if ($t->status == "1")
                                                                Pengajuan Proposal
                                                            @elseif($t->status == "2")
                                                                Lolos Proses Upload
                                                            @elseif($t->status == "3")
                                                                Lolos Didanai
                                                                @elseif($t->status == "4")
                                                                Lolos Pimnas
                                                                @elseif($t->status == "5")
                                                                Juara 1 Presentasi
                                                                @elseif($t->status == "6")
                                                                Juara 2 Presentasi
                                                                @elseif($t->status == "7")
                                                                Juara 3 Presentasi
                                                                @elseif($t->status == "8")
                                                                Juara Favorit Presentasi
                                                                @elseif($t->status == "9")
                                                                Juara 1 Poster
                                                                @elseif($t->status == "10")
                                                                Juara 2 Presentasi
                                                                @elseif($t->status == "11")
                                                                Juara 3 Presentasi
                                                                @elseif($t->status == "12")
                                                                Juara Favorit Presentasi
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                            <a href="{{url('/mhs/pkm')}}/{{ $t->id_file_pkm }}" data-toggle="tooltip" title="Lihat" ><button class="btn btn-mini btn-default"><i class="icofont icofont-eye-alt"></i> Lihat PKM</button></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center f-s-italic">Belum ada daftar PKM</td>
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

@if($modeupload > 0)
    @section('end')
        <!-- Modal -->
        <div class="modal fade" id="promise-pkm" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><strong>Perhatian</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{url('mhs/tambah-pkm')}}" method="get">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="ketua_kelompok" id="ketua_kelompok" value="Yes" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Secara sadar, saya pastikan bahwa <strong class="text-danger">saya adalah ketua kelompok</strong>  dari proposal yang diajukan.</span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="jika_terjadi_kesalahan" id="jika_terjadi_kesalahan" value="SiapBersedia" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Bilamana terdapat kesalahan dalam susunan personalia proposal ini, kelompok kami siap untuk didiskualifikasi.</span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" id="tambahan"  class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Saya sudah membaca dan memahami <strong>Pedoman PKM</strong> yang terbaru.</span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="judul" id="judul" value="12" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Judul pada proposal yang diusulkan <strong class="text-danger">tidak melebihi 20 kata</strong>.</span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="ttd_lempeng" id="ttd_lempeng" value="BukanCropping" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Tandatangan pada lembar pegesahan proposal bukan merupakan <i>cropping</i></span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="ttd_biodata" id="ttd_biodata" value="BukanCropping" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Tandatangan pada biodata kelompok dan dosen pembimbing bukan merupakan <i>cropping</i></span>
                        </label>
                        <br>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" name="halaman_proposal" id="halaman_proposal" value="10Lembar" class="custom-control-input" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Halaman proposal dari <strong>Bab 1 sampai Daftar pustaka sebanyak 10 lembar</strong></span>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Saya Sudah Membacanya</button>
                    </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('footer')
        <script>
        $('#tambahUpload').click(function(){
            $('#promise-pkm').modal('show');
        });
        </script>
    @endsection
@endif
