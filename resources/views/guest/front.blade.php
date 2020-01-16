@extends('guest.front-master')

@section('title')
Front Page
@stop

@section('header')
<link rel="stylesheet" type="text/css" href="{{url('assets/css/simple-line-icons.cs')}}s">
<link rel="stylesheet" type="text/css" href="{{url('assets/css/ionicons.css')}}">
@endsection

@section('content')

@if (session('register') == "success")
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <strong>Success</strong> Silakan cek email student anda untuk aktivasi Akun.
</div>
@endif
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-10">
            <div class="">
                <h2 class="">Selamat Datang di Sistem Management PKM UNY</h2>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

<div class="row">
    <!-- Client Map Start -->
    <div class="col-md-4">
        <div class="card client-map">
            <div class="card-block">
                <h5>Login</h5>
                <hr>
                <form action="{{url('/authlogin')}}" method="post">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username/NIM"
                            value="{{request('username')}}" autofocus>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                            value="{{request('password')}}" placeholder="Password">
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-sm btn-success">Login</button>
                        <a href="{{url('/register')}}" type="button" class="btn btn-sm btn-warning">Registrasi</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card client-map">
            <div class="card-block">
                <h5>Status Aktif PKM</h5>
                <br>
                <table class="table">
                    <?php $c=1; ?>
                    @foreach ($tipeaktif as $ta)
                    <tr>
                        <td>{{$c}}</td>
                        <td class="text-capitalize">{{$ta->tipe}}</td>
                        <td><i class="ion-chevron-right"></i></td>
                        <td>
                            @if ($ta->status_upload == 1)
                            <label class="label label-success">Aktif</label>
                            @else
                            <label class="label label-danger">Tidak Aktif</label>
                            @endif
                        </td>
                    </tr>
                    <?php $c++; ?>
                    @endforeach
                </table>
            </div>
            <div class="card-block">
                <table class="table" id="tableCoba">
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card client-map">
            <div class="card-block">
                <!-- InstaWidget -->
                {{--<a href="https://instagram.com/pkmcenter_uny"
                    id="link-5174b9f40d1a8c9fc1c7bd7aa7394710e460694ebbce987771a22affe58ad46a">@pkmcenter_uny</a>
                <script
                    src="https://instawidget.net/js/instawidget.js?u=5174b9f40d1a8c9fc1c7bd7aa7394710e460694ebbce987771a22affe58ad46a&width=100%">
                </script>--}}
            </div>
        </div>
    </div>
    <!-- Client Map End -->
</div>
@endsection
