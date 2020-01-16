@extends('layout.master')

@section('title')
List Call Center
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>List Call Center</h4>
                        <span><strong>Jumlah Call Center : {{ $call->jml }}</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Call Center</a> </li>
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
                            @if($call->jml < 5) 
                            <div class="f-right"><a href="#!"><button class="tambahCallCenter btn btn-success btn-sm">Tambah Call Center</button></a></div>
                            @endif()
                        </div>
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <th>No</th>
                                        <th>NIM Call Center</th>
                                        <th>Nama Call Center</th>
                                        <th>Whatsapp</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c=1; ?>
                                           @if($call->jmlcu > 0)
                                           <tr></tr>
                                                @foreach ($call->cu as $item) 
                                                <tr>
                                                    <td class="text-center">{{$c}}</td>
                                                    <td>{{ $item->nim_callcenter }}</td>
                                                    <td>{{ $item->nama_callcenter }}</td>
                                                    <td>{{ $item->whatsapp }}</td>
                                                    <td></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            @foreach ($call->cc as $t)
                                                <tr>
                                                    <td class="text-center">{{$c}}</td>
                                                    <td>{{ $t->nim_callcenter }}</td>
                                                    <td>{{ $t->nama_callcenter }}</td>
                                                    <td>{{ $t->whatsapp }}</td>
                                                    <td>
                                                        <a href="{{url('opt/hapus/callcenter')}}/{{ Crypt::encryptString($t->id) }}"><button class="btn btn-danger btn-sm">&times;</button></a>
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
@if($call->jml < 5) 
@include('layout.modal.optcallcenter')
@endif
@endsection
