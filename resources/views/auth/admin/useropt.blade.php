@extends('layout.master')

@section('title')
Operator PKM UNY
@endsection

@section('content')
<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Operator</h5>
                    <button class="btn btn-sm btn-primary f-right" data-toggle="modal" data-target="#tambah-operator"><i class="fa fa-user-plus"></i> Tambah Operator</button>
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-stripped table-hover">
                            <thead>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Operator</th>
                                <th>Fakultas</th>
                                {{--<th>Password</th>--}}
                                <th>Call Center</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                <?php $c = 1; ?>
                                @foreach ($opt as $t)
                                <tr>
                                    <td>{{$c}}</td>
                                    <td>{{$t->username}}</td>
                                    <td>{{$t->nama_operator}}</td>
                                    <td>{{$t->nama_fakultas}}</td>
                                    {{--<td>{{Crypt::decrypt($t->opt_status)}}</td>--}}
                                    <td><button class="lihatCall btn btn-mini btn-success" data-nama="{{$t->nama_fakultas}}" data-fakultas="{{$t->id_fakultas}}"><i class="fa fa-whatsapp"></i> Call Center</button>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="resetOpt btn btn-mini btn-default" data-nama="{{$t->nama_operator}}" data-id="{{$t->id_user}}" data-toggle="tooltip" title="Reset {{$t->nama_operator}}"><i class="fa fa-refresh"></i></button>
                                            <button class="hapusOpt btn btn-mini btn-danger" data-nama="{{$t->nama_operator}}" data-id="{{$t->id_user}}" data-toggle="tooltip" title="Hapus {{$t->nama_operator}}"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php $c++; ?>
                                @endforeach
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
<div class="modal fade" id="tambah-operator" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Operator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" id="tbh-username" placeholder="Username Operator" required>
                </div>
                <div class="form-group">
                    <label>Nama Operator</label>
                    <input type="text" class="form-control" id="tbh-nama" placeholder="Nama Operator" required>
                </div>
                <div class="form-group">
                    <label>Fakultas</label>
                    <select class="form-control" id="tbh-fakultas" required>
                        <option value="">--Fakultas Operator--</option>
                        @foreach ($fakultas as $f)
                        <option value="{{$f->id}}">{{$f->nama_fakultas}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="tbh-opt" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="lihat-call" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Call Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                body
            </div>
            <div class="modal-footer">
                <button type="button" id="tbh-opt" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $('.lihatCall').click(function(event) {
        event.preventDefault();
        var fakultas = $(this).data('fakultas');
        var nama = $(this).data('nama');
        $.ajax({
                url: '{{ URL::to('/admin')}}/allcallcenter',
                type: 'GET',
                dataType: 'json',
                data: {
                    fakultas: $(this).data('fakultas')
                },
            })
            .done(function(data) {
                content = $('<div></div>');
                $.each(data, function(index, val) {
                    content.append('<p>Call Center ' + (index + 1) + '</p>');
                    content.append('<p>Nama : ' + val.nama_callcenter + '</p>');
                    content.append('<p>NIM : ' + val.nim_callcenter + '</p>');
                    content.append('<p>Telp. : <a href="https://wa.me/' + val.whatsapp + '" target="_blank">' + val.whatsapp + '</a></p>');
                    content.append('<br>');
                });
                $('#lihat-call .modal-title').html('Call Center ' + nama);
                $('#lihat-call .modal-body').html(content);
                $('#lihat-call').modal('show');
            })
            .fail(function() {
                console.log("error");
            });
    });


    $('.resetOpt').click(function() {
        var code = "{{ csrf_token() }}";
        var nama = $(this).data('nama');
        var id = $(this).data('id');
        swal({
            title: "Reset " + nama + " ?",
            text: "kamu mau mereset operator dengan nama " + nama + "?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Reset " + nama,
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: "{{url('admin/resetopt')}}",
                type: "POST",
                data: {
                    _token: code,
                    id: id
                },
                success: function() {
                    swal(nama + " Direset!", "Password operator jadi sesuai usename", "success");
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Reset!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });

    $('.hapusOpt').click(function() {
        var code = "{{ csrf_token() }}";
        var nama = $(this).data('nama');
        var id = $(this).data('id');
        swal({
            title: "Hapus " + nama + " ?",
            text: "kamu mau menghapus operator dengan nama " + nama + "?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus " + nama,
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: "{{url('admin/hapusopt')}}",
                type: "POST",
                data: {
                    _token: code,
                    id: id
                },
                success: function() {
                    swal(nama + " Dihapus!", "Anda berhasil menghapus " + nama, "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Hapus!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });

    $('#tbh-opt').click(function() {
        var un = $('#tbh-username').val();
        var nama = $('#tbh-nama').val();
        var fak = $('#tbh-fakultas').val();
        var code = "{{ csrf_token() }}";

        if (un == "" || nama == "" || fak == "") {
            new PNotify({
                title: 'Form Empty',
                text: 'Form tidak boleh ada yang kosong',
                icon: 'icofont icofont-info-circle',
                type: 'error'
            });
        } else {
            $.post("{{ URL::to('/admin/tambahopt') }}", {
                    _token: code,
                    nama: nama,
                    un: un,
                    fak: fak
                })
                .done(function(data) {
                    if (data == "success") {
                        location.reload();
                    } else if (data == "error") {
                        new PNotify({
                            title: 'Username used',
                            text: 'Username Sudah digunakan',
                            icon: 'icofont icofont-info-circle',
                            type: 'error'
                        });
                        $('#tbh-username').val('');
                    } else if (data == "created") {
                        new PNotify({
                            title: 'Operator Created',
                            text: 'Operator sudah ada',
                            icon: 'icofont icofont-info-circle',
                            type: 'warning'
                        });
                        $('#tbh-username').val('');
                        $('#tbh-nama').val('');
                        $('#tbh-fakultas').val('');
                        $('#tambah-operator').modal('hide');
                    }
                })
                .fail(function() {
                    new PNotify({
                        title: 'Something Wrong!',
                        text: 'Kesalahan server silakan coba refresh halaman',
                        icon: 'icofont icofont-info-circle',
                        type: 'error'
                    });
                });
        }
    });
</script>
@endsection