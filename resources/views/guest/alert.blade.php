@extends('guest.front-master')

@section('title')
Front Page
@stop

@section('header')
    <!-- weather-icons -->
	<link rel="stylesheet" type="text/css" href="{{url('assets/icon/weather-icons/css/weather-icons.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('assets/icon/weather-icons/css/weather-icons-wind.min.css')}}">
	<!--SVG Icons Animated-->
	<link rel="stylesheet" type="text/css" href="{{url('assets/icon/SVG-animated/svg-weather.css')}}">
	
	<link rel="stylesheet" type="text/css" href="{{url('assets/css/simple-line-icons.cs')}}s">
	<link rel="stylesheet" type="text/css" href="{{url('assets/css/ionicons.css')}}">
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-10">
            <div class="">
                <h2 class="">Pemberitahuan</h2>
            </div>
        </div>
    </div>
</div>

@if (session('login') == "notadmin")
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-10">
            <div class="">
                <h2 class="">Pemberitahuan</h2>
            </div>
        </div>
    </div>
</div>
@endsection