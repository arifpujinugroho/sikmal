@extends('layout.master')

@section('title')
{{Auth::user()->mahasiswa->namapanggilan}}
@endsection

@section('header')

@endsection

@section('content')
    <!-- Page body start -->
    <div class="page-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Product detail page start -->
                    <div class="card product-detail-page">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-5 col-xs-12">
                                    <div class="port_details_all_img row">
                                        <div class="col-lg-12 m-b-15">
                                            <div id="big_banner">
                                                <div class="port_big_img">
                                                    @if(Auth::user()->mahasiswa->fotoresmi == "NULL" || Auth::user()->mahasiswa->fotoresmi == "")
                                                        <img style="cursor: pointer;" class="img img-fluid ganti-foto" data-toggle="tooltip" title="Ganti Foto" data-nim="{{Auth::user()->mahasiswa->nim}}" data-namalengkap="{{Auth::user()->mahasiswa->namalengkap}}" src="{{url('storage/foto/'.Auth::user()->mahasiswa->tahunpcb.'/client/'.Auth::user()->mahasiswa->fotoawal)}}" alt="Foto {{Auth::user()->mahasiswa->namapanggilan}}">
                                                    @else
                                                        <img class="img img-fluid" src="{{url('storage/foto/'.Auth::user()->mahasiswa->tahunpcb.'/resmi/'.Auth::user()->mahasiswa->fotoresmi)}}" alt="Foto {{Auth::user()->mahasiswa->namapanggilan}}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-xs-12 product-detail">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <span class="txt-muted d-inline-block">Tahun Pendaftaran :  {{Auth::user()->mahasiswa->tahunpcb}} </span>
                                            <span class="f-right text-danger"><strong>{{Auth::user()->mahasiswa->status_crew}} </strong></span>
                                        </div>
                                        <div>
                                            <div class="col-lg-12">
                                                <h1 class="pro-desc f-w-300"><strong> {{Auth::user()->mahasiswa->namalengkap}} </strong> <br><small class="text-muted">&nbsp;&nbsp;&nbsp;{{Auth::user()->mahasiswa->namapanggilan}}</small></h1>
                                            </div>
                                            <div class="col-lg-12">
                                                <span class=""> {{Auth::user()->mahasiswa->nim}} </span>
                                            </div>
                                        </div>
                                            <div class="col-lg-12">
                                                @if ($status > 0)
                                                    <span class="text-primary product-price">{{$jabatan->posisi_lengkap}}</span> 
                                                    <span class="text-primary">Tahun : {{$jabatan->tahun}}</span>
                                                @endif
                                                <br>
                                                <hr>
                                                <table>
                                                    <tr>
                                                        <th>
                                                            Program Studi :
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            {{Auth::user()->mahasiswa->prodi->nama_prodi}}
                                                        </td>
                                                    </tr>
                                                </table>
                                                <br>
                                                <table>
                                                    <tr>
                                                        <th>
                                                            Jenis Kelamin :
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            @if(Auth::user()->mahasiswa->jenis_kel == "P")
                                                                Perempuan
                                                            @else
                                                                Laki-Laki
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                                <br>
                                                <table>
                                                    <tr>
                                                        <th>
                                                            Agama :
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            {{Auth::user()->mahasiswa->agama}}
                                                        </td>
                                                    </tr>
                                                </table>
                                                <hr>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<button class="btn btn-primary">Edit</button>--}}
                    </div>
                    <!-- Product detail page end -->
                </div>
            </div>
        </div>
        <!-- Page body end -->
@endsection

@section('end')
<div class="modal fade" id="foto-Ganti" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ganti Foto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{url('preview/gantifoto')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="nim" id="nim-mhsiswa">                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="text" name="namalengkap" id="detail-namalengkap" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                                <input type="file" id="file" name="foto" class="form-control" required>
                        </div>
                    </div>
                </div>                    
            </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                    <button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>     
@endsection

@section('footer')
<script>
        (function($) {
            $.fn.checkFileType = function(options) {
                var defaults = {
                    allowedExtensions: [],
                    success: function() {},
                    error: function() {}
                };
                options = $.extend(defaults, options);
        
                return this.each(function() {
        
                    $(this).on('change', function() {
                        var value = $(this).val(),
                            file = value.toLowerCase(),
                            extension = file.substring(file.lastIndexOf('.') + 1);
        
                        if ($.inArray(extension, options.allowedExtensions) == -1) {
                            options.error();
                            $(this).focus();
                        } else {
                            options.success();
        
                        }
        
                    });
        
                });
            };
        
        })(jQuery);
        
        var uploadField = document.getElementById("file");
        uploadField.onchange = function() {
            if(this.files[0].size > 5055650){
                new PNotify({
                        title: 'File Oversize',
                        text: 'Maaf, File Max 5MB ',
                        type: 'error'
                });
                console.log("file size = " + this.files[0].size + "/5055650")
                this.value = "";
            };
        };
        
        $(function() {
            $('#file').checkFileType({
                allowedExtensions: ['jpg', 'jpeg','png'],
                error: function() {
                    new PNotify({
                        title: 'File not Image',
                        text: 'Maaf, hanya type image yang diupload ',
                        type: 'error'
                    });
                    document.getElementById("file").value = "";
                }
            });
        });
</script>
<script>
    $('.ganti-foto').click(function() {
            $('#detail-namalengkap').val($(this).data('namalengkap'));
            $('#nim-mhsiswa').val($(this).data('nim'));
            $('#foto-Ganti').modal('show');
        });
</script>
    
@endsection