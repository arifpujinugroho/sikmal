@extends('layout.master')

@section('title')

@endsection

@section('header')

@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-clip-board bg-c-yellow"></i>
                <div class="d-inline">
                    <?php
                        date_default_timezone_set("Asia/Jakarta");
                        $t = time();
                        $jam = date("G",$t);  
                    ?>

                        @if ($jam <= 10 )
                           		<h4 class="">Selamat Pagi PKM Center {{$fakultas}}
                           		    <svg version="1.1" class="climacon climacon_sunrise" viewBox="15 15 70 70">
	                    				<g class="climacon_iconWrap climacon_iconWrap-sunrise">
	                    					<g class="climacon_componentWrap climacon_componentWrap-sunrise">
	                    						<g class="climacon_componentWrap climacon_componentWrap-sunSpoke">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-east" d="M32.003,54h-4c-1.104,0-2,0.896-2,2s0.896,2,2,2h4c1.104,0,2-0.896,2-2S33.106,54,32.003,54z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-northEast" d="M38.688,41.859l-2.828-2.828c-0.781-0.78-2.047-0.78-2.828,0c-0.781,0.781-0.781,2.047,0,2.828l2.828,2.828c0.781,0.781,2.047,0.781,2.828,0C39.469,43.906,39.469,42.641,38.688,41.859z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M50.001,40.002c1.104,0,1.999-0.896,1.999-2v-3.999c0-1.104-0.896-2-1.999-2c-1.105,0-2,0.896-2,2v3.999C48.001,39.106,48.896,40.002,50.001,40.002z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-northWest" d="M66.969,39.031c-0.779-0.78-2.048-0.78-2.828,0l-2.828,2.828c-0.779,0.781-0.779,2.047,0,2.828c0.781,0.781,2.049,0.781,2.828,0l2.828-2.828C67.749,41.078,67.749,39.812,66.969,39.031z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-west" d="M71.997,54h-3.999c-1.104,0-1.999,0.896-1.999,2s0.896,2,1.999,2h3.999c1.104,0,2-0.896,2-2S73.104,54,71.997,54z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-sunBody">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunBody" d="M50.001,44.002c-6.627,0-11.999,5.371-11.999,11.998c0,1.404,0.254,2.747,0.697,3.999h4.381c-0.683-1.177-1.079-2.54-1.079-3.999c0-4.418,3.582-7.999,8-7.999c4.417,0,7.998,3.581,7.998,7.999c0,1.459-0.396,2.822-1.078,3.999h4.381c0.443-1.252,0.697-2.595,0.697-3.999C61.999,49.373,56.627,44.002,50.001,44.002z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-arrow">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_arrow climacon_component-stroke_arrow-up" d="M50.001,63.046c0.552,0,0.999-0.447,0.999-1v-3.827l2.536,2.535c0.39,0.391,1.022,0.391,1.414,0c0.39-0.391,0.39-1.023,0-1.414l-4.242-4.242c-0.391-0.391-1.024-0.391-1.414,0l-4.242,4.242c-0.391,0.391-0.391,1.023,0,1.414c0.391,0.391,1.023,0.391,1.414,0l2.535-2.535v3.827C49.001,62.599,49.448,63.046,50.001,63.046z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-horizonLine">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_horizonLine" d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z" />
	                    						</g>
	                    					</g>
	                    				</g>
	                    			</svg>
                           		</h4>
                        @elseif ($jam > 10 && $jam <= 15 )
	                           <h4>Selamat Siang PKM Center {{$fakultas}}
	                                <svg version="1.1" class="climacon climacon_cloudSunFill" viewBox="15 15 70 70">
	                    		    	<g class="climacon_iconWrap climacon_cloudSunFill-iconWrap">
	                    		    		<g class="climacon_componentWrap climacon_componentWrap-sun climacon_componentWrap-sun_cloud">
	                    		    			<g class="climacon_componentWrap climacon_componentWrap_sunSpoke">
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z" />
	                    		    				<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z" />
	                    		    			</g>
	                    		    			<g class="climacon_wrapperComponent climacon_wrapperComponent-sunBody">
	                    		    				<circle class="climacon_component climacon_component-stroke climacon_component-stroke_sunBody" cx="58.033" cy="41.612" r="11.999" />
	                    		    				<circle class="climacon_component climacon_component-fill climacon_component-fill_sunBody" fill="#FFFFFF" cx="58.033" cy="41.612" r="7.999" />
	                    		    			</g>
	                    		    		</g>
	                    		    		<g class="climacon_wrapperComponent climacon_wrapperComponent-cloud">
	                    		    			<path class="climacon_component climacon_component-stroke climacon_component-stroke_cloud" d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z" />
	                    		    			<path class="climacon_component climacon_component-fill climacon_component-fill_cloud" fill="#FFFFFF" d="M60.035,61.641c4.418,0,8-3.582,8-7.998c0-4.418-3.582-8-8-8c-1.6,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.976-9.29-11.668-9.29c-6.627,0-11.999,5.372-11.999,11.999c0,6.627,5.372,11.998,11.999,11.998C47.65,61.641,57.016,61.641,60.035,61.641z" />
	                    		    		</g>
	                    		    	</g>
	                    		    </svg>
	                           </h4>
                        @elseif ($jam > 15 && $jam <= 19 )
                               <h4>Selamat Sore PKM Center {{$fakultas}}
                                    <svg version="1.1" class="climacon climacon_sunset" viewBox="15 15 70 70">
	                    				<g class="climacon_iconWrap climacon_iconWrap-sunset">
	                    					<g class="climacon_componentWrap climacon_componentWrap-sunset">
	                    						<g class="climacon_componentWrap climacon_componentWrap-sunSpoke">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-east" d="M32.003,54h-4c-1.104,0-2,0.896-2,2s0.896,2,2,2h4c1.104,0,2-0.896,2-2S33.106,54,32.003,54z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-northEast" d="M38.688,41.859l-2.828-2.828c-0.781-0.78-2.047-0.78-2.828,0c-0.781,0.781-0.781,2.047,0,2.828l2.828,2.828c0.781,0.781,2.047,0.781,2.828,0C39.469,43.906,39.469,42.641,38.688,41.859z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-north" d="M50.001,40.002c1.104,0,1.999-0.896,1.999-2v-3.999c0-1.104-0.896-2-1.999-2c-1.105,0-2,0.896-2,2v3.999C48.001,39.106,48.896,40.002,50.001,40.002z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-northWest" d="M66.969,39.031c-0.779-0.78-2.048-0.78-2.828,0l-2.828,2.828c-0.779,0.781-0.779,2.047,0,2.828c0.781,0.781,2.049,0.781,2.828,0l2.828-2.828C67.749,41.078,67.749,39.812,66.969,39.031z" />
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunSpoke climacon_component-stroke_sunSpoke-west" d="M71.997,54h-3.999c-1.104,0-1.999,0.896-1.999,2s0.896,2,1.999,2h3.999c1.104,0,2-0.896,2-2S73.104,54,71.997,54z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-sunBody">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_sunBody" d="M50.001,44.002c-6.627,0-11.999,5.371-11.999,11.998c0,1.404,0.254,2.747,0.697,3.999h4.381c-0.683-1.177-1.079-2.54-1.079-3.999c0-4.418,3.582-7.999,8-7.999c4.417,0,7.998,3.581,7.998,7.999c0,1.459-0.396,2.822-1.078,3.999h4.381c0.443-1.252,0.697-2.595,0.697-3.999C61.999,49.373,56.627,44.002,50.001,44.002z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-arrow">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_arrow climacon_component-stroke_arrow-down" d="M50,49.107c0.552,0,1,0.448,1,1.002v3.83l2.535-2.535c0.391-0.391,1.022-0.391,1.414,0c0.391,0.391,0.391,1.023,0,1.414l-4.242,4.242c-0.392,0.391-1.022,0.391-1.414,0l-4.242-4.242c-0.391-0.391-0.391-1.023,0-1.414c0.392-0.391,1.023-0.391,1.414,0L49,53.939v-3.83C49,49.555,49.447,49.107,50,49.107z" />
	                    						</g>
	                    						<g class="climacon_wrapperComponent climacon_wrapperComponent-horizonLine">
	                    							<path class="climacon_component climacon_component-stroke climacon_component-stroke_horizonLine" d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z" />
	                    						</g>
	                    					</g>
	                    				</g>
	                    			</svg>
                               </h4>
                        @elseif ($jam > 19 )
	                       		<h4>Selamat Malam PKM Center {{$fakultas}}
	                       		    <svg version="1.1" class="climacon climacon_cloudMoonFill" viewBox="15 15 70 70">
	                    				<g class="climacon_iconWrap climacon_iconWrap-cloudMoonFill">
	                    					<g class="climacon_wrapperComponent climacon_wrapperComponent-moon climacon_componentWrap-moon_cloud">
	                    						<path class="climacon_component climacon_component-stroke climacon_component-stroke_moon" d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z" />
	                    						<path class="climacon_component climacon_component-fill climacon_component-fill_moon" fill="#FFFFFF" d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z" />
	                    					</g>
	                    					<g class="climacon_wrapperComponent climacon_wrapperComponent-cloud">
	                    						<path class="climacon_component climacon_component-stroke climacon_component-stroke_cloud" d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z" />
	                    						<path class="climacon_component climacon_component-fill climacon_component-fill_cloud" fill="#FFFFFF" d="M60.035,61.641c4.418,0,8-3.582,8-7.998c0-4.418-3.582-8-8-8c-1.6,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.976-9.29-11.668-9.29c-6.627,0-11.999,5.372-11.999,11.999c0,6.627,5.372,11.998,11.999,11.998C47.65,61.641,57.016,61.641,60.035,61.641z" />
	                    					</g>
	                    				</g>
	                    			</svg>
	                       		</h4>
                        @endif 
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
            <li class="breadcrumb-item">
            </li>
        </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

@if ($call->jml < 1)
	<div class="alert alert-danger">
		<strong>Perhatian!!</strong> Daftar Call Center Fakultas Belum Ditambah
	</div>
@endif

<div class="page-body">
    <div class="row">
        <!-- crew start -->
        <div class="col-md-6 col-xl-6">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont icofont-users-alt-2 bg-c-pink card1-icon"></i>
                    <span class="text-c-pink f-w-600">Pengajuan PKM Tahun ini</span>
                    <h4><small>{{$jmlyear}} Proposal</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-pink f-16 icofont icofont-users-alt-2 m-r-10"></i>Jumlah pengajuan tahun ini
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- crew end -->
        <!-- total start -->
        <div class="col-md-6 col-xl-6">
            <div class="card widget-card-1">
                <div class="card-block-small">
                    <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                    <span class="text-c-blue f-w-600">Total Pengajuan PKM</span>
                    <h4><small>{{$jml}} Proposal</small></h4>
                    <div>
                        <span class="f-left m-t-10 text-muted">
                            <i class="text-c-blue f-16 icofont icofont-refresh m-r-10"></i>Total Pengajuan Proposal PKM
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total end -->
    </div>
</div>


    
@endsection

@section('footer')
    
@endsection