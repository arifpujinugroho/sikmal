@extends('layout.master')

@section('title')
Rekapan PKM UNY
@endsection

@section('header')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{url('assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css')}}">
@endsection

@section('content')
    <!-- Page-header start -->
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icofont icofont-speed-meter bg-c-blue"></i>
                    <div class="d-inline">
                        <h4>Rekapan UNY</h4>
                        <span><strong>Rekapan <strong class="text-danger">{{$tipe}}</strong> Tahun Anggaran <strong class="text-danger">{{$tahun}}</strong>.</strong></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Grafik PKM</a> </li>
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
                        <h4>Baru pengajuan usulan PKM</h4>
                    </div>
                    <div class="card-block">
                        <div class="chart" id="bar-chart" style="height: 300px;"></div>
                    </div>
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="usulanTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Fakultas</th>
                                        @if ($tpkm->tipe == '2 Bidang')
                                        <th>PKM GT</th>
                                        <th>PKM AI</th>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                        <th>PKM KC</th>
                                        <th>PKM M</th>
                                        <th>PKM T</th>
                                        <th>PKM K</th>
                                        <th>PKM PE</th>
                                        <th>PKM PSH</th>
                                        @elseif($tpkm->tipe == 'PKM GFK')
                                        <th>PKM GFK</th>
                                        @elseif($tpkm->tipe == 'SUG')
                                        <th>SUG</th>
                                        @endif
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        $totGT = 0;
                                        $totAI = 0;
                                        $totKC = 0;
                                        $totM = 0;
                                        $totT = 0;
                                        $totK = 0;
                                        $totPE = 0;
                                        $totPSH = 0;
                                        $totGFK = 0;
                                        $totSUG = 0;
                                        $totSemua2 = 0;
                                        $totSemua5 = 0;
                                        $totSemuaGFK = 0;
                                        $totSemuaSUG = 0;
                                    ?>
                                    @foreach($data as $d)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $d['nama_singkat'] }}</td>
                                        @if ($tpkm->tipe == '2 Bidang')
                                            <td>{{ $d['PKMGT'] }}</td>
                                            <td>{{ $d['PKMAI'] }}</td>
                                            <td>{{ $d['PKMGT']+$d['PKMAI'] }}</td>
                                            <?php
                                                $totGT +=$d['PKMGT'];
                                                $totAI +=$d['PKMAI'];
                                                $totSemua2 += ($d['PKMGT']+$d['PKMAI'])
                                            ?>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                            <td>{{ $d['PKMKC'] }}</td>
                                            <td>{{ $d['PKMM'] }}</td>
                                            <td>{{ $d['PKMT'] }}</td>
                                            <td>{{ $d['PKMK'] }}</td>
                                            <td>{{ $d['PKMPE'] }}</td>
                                            <td>{{ $d['PKMPSH'] }}</td>
                                            <?php
                                                $totKC +=$d['PKMKC'];
                                                $totM +=$d['PKMM'];
                                                $totT +=$d['PKMT'];
                                                $totK +=$d['PKMK'];
                                                $totPE +=$d['PKMPE'];
                                                $totPSH +=$d['PKMPSH'];
                                                $totSemua5 += ($d['PKMKC']+$d['PKMM']+$d['PKMT']+$d['PKMK']+$d['PKMPE']+$d['PKMPSH'])
                                            ?>
                                            <td>{{ $d['PKMKC']+$d['PKMM']+$d['PKMT']+$d['PKMK']+$d['PKMPE']+$d['PKMPSH'] }}</td>
                                        @elseif ($tpkm->tipe == 'PKM GFK')
                                            <td>{{ $d['PKMGFK'] }}</td>
                                            <td>{{ $d['PKMGFK'] }}</td>
                                            <?php
                                                $totGFK +=$d['PKMGFK'];
                                                $totSemuaGFK += ($d['PKMGFK'])
                                            ?>
                                        @elseif ($tpkm->tipe == 'SUG')
                                            <td>{{ $d['SUG'] }}</td>
                                            <td>{{ $d['SUG'] }}</td>
                                            <?php
                                                $totSUG +=$d['SUG'];
                                                $totSemuaSUG += ($d['SUG'])
                                            ?>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-dark">
                                        <td></td>
                                        <td><b>Total Usulan PKM</b></td>
                                        @if ($tpkm->tipe == '2 Bidang')
                                            <td>{{ $totGT }}</td>
                                            <td>{{ $totAI }}</td>
                                            <td>{{ $totSemua2 }}</td>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                            <td>{{ $totKC }}</td>
                                            <td>{{ $totM }}</td>
                                            <td>{{ $totT }}</td>
                                            <td>{{ $totK }}</td>
                                            <td>{{ $totPE }}</td>
                                            <td>{{ $totPSH }}</td>
                                            <td>{{ $totSemua5 }}</td>
                                        @elseif ($tpkm->tipe == 'PKM GFK')
                                            <td>{{ $totGFK }}</td>
                                            <td>{{ $totSemuaGFK }}</td>
                                        @elseif ($tpkm->tipe == 'SUG')
                                            <td>{{ $totSUG }}</td>
                                            <td>{{ $totSemuaSUG }}</td>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Usulan PKM Sudah Lengkap</h4>
                    </div>
                    <div class="card-block">
                        <div class="chart" id="bar-lengkap" style="height: 300px;"></div>
                    </div>
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="lengkapTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Fakultas</th>
                                        @if ($tpkm->tipe == '2 Bidang')
                                        <th>PKM GT</th>
                                        <th>PKM AI</th>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                        <th>PKM KC</th>
                                        <th>PKM M</th>
                                        <th>PKM T</th>
                                        <th>PKM K</th>
                                        <th>PKM PE</th>
                                        <th>PKM PSH</th>
                                        @elseif($tpkm->tipe == 'PKM GFK')
                                        <th>PKM GFK</th>
                                        @elseif($tpkm->tipe == 'SUG')
                                        <th>SUG</th>
                                        @endif
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $li = 1;
                                        $ltotGT = 0;
                                        $ltotAI = 0;
                                        $ltotKC = 0;
                                        $ltotM = 0;
                                        $ltotT = 0;
                                        $ltotK = 0;
                                        $ltotPE = 0;
                                        $ltotPSH = 0;
                                        $ltotGFK = 0;
                                        $ltotSUG = 0;
                                        $ltotSemua2 = 0;
                                        $ltotSemua5 = 0;
                                        $ltotSemuaGFK = 0;
                                        $ltotSemuaSUG = 0;
                                    ?>
                                    @foreach($datal as $d)
                                    <tr>
                                        <td>{{ $li++ }}</td>
                                        <td>{{ $d['nama_singkat'] }}</td>
                                        @if ($tpkm->tipe == '2 Bidang')
                                            <td>{{ $d['PKMGT'] }}</td>
                                            <td>{{ $d['PKMAI'] }}</td>
                                            <td>{{ $d['PKMGT']+$d['PKMAI'] }}</td>
                                            <?php
                                                $ltotGT +=$d['PKMGT'];
                                                $ltotAI +=$d['PKMAI'];
                                                $ltotSemua2 += ($d['PKMGT']+$d['PKMAI'])
                                            ?>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                            <td>{{ $d['PKMKC'] }}</td>
                                            <td>{{ $d['PKMM'] }}</td>
                                            <td>{{ $d['PKMT'] }}</td>
                                            <td>{{ $d['PKMK'] }}</td>
                                            <td>{{ $d['PKMPE'] }}</td>
                                            <td>{{ $d['PKMPSH'] }}</td>
                                            <?php
                                                $ltotKC +=$d['PKMKC'];
                                                $ltotM +=$d['PKMM'];
                                                $ltotT +=$d['PKMT'];
                                                $ltotK +=$d['PKMK'];
                                                $ltotPE +=$d['PKMPE'];
                                                $ltotPSH +=$d['PKMPSH'];
                                                $ltotSemua5 += ($d['PKMKC']+$d['PKMM']+$d['PKMT']+$d['PKMK']+$d['PKMPE']+$d['PKMPSH'])
                                            ?>
                                            <td>{{ $d['PKMKC']+$d['PKMM']+$d['PKMT']+$d['PKMK']+$d['PKMPE']+$d['PKMPSH'] }}</td>
                                        @elseif ($tpkm->tipe == 'PKM GFK')
                                            <td>{{ $d['PKMGFK'] }}</td>
                                            <td>{{ $d['PKMGFK'] }}</td>
                                            <?php
                                                $ltotGFK +=$d['PKMGFK'];
                                                $ltotSemuaGFK += ($d['PKMGFK'])
                                            ?>
                                        @elseif ($tpkm->tipe == 'SUG')
                                            <td>{{ $d['SUG'] }}</td>
                                            <td>{{ $d['SUG'] }}</td>
                                            <?php
                                                $ltotSUG +=$d['SUG'];
                                                $ltotSemuaSUG += ($d['SUG'])
                                            ?>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-success">
                                        <td></td>
                                        <td><b>Total Keseluruhan</b></td>
                                        @if ($tpkm->tipe == '2 Bidang')
                                            <td>{{ $ltotGT }}</td>
                                            <td>{{ $ltotAI }}</td>
                                            <td>{{ $ltotSemua2 }}</td>
                                        @elseif ($tpkm->tipe == '5 Bidang')
                                            <td>{{ $ltotKC }}</td>
                                            <td>{{ $ltotM }}</td>
                                            <td>{{ $ltotT }}</td>
                                            <td>{{ $ltotK }}</td>
                                            <td>{{ $ltotPE }}</td>
                                            <td>{{ $ltotPSH }}</td>
                                            <td>{{ $ltotSemua5 }}</td>
                                        @elseif ($tpkm->tipe == 'PKM GFK')
                                            <td>{{ $ltotGFK }}</td>
                                            <td>{{ $ltotSemuaGFK }}</td>
                                        @elseif ($tpkm->tipe == 'SUG')
                                            <td>{{ $ltotSUG }}</td>
                                            <td>{{ $ltotSemuaSUG }}</td>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-body end -->
@endsection

@section('footer')
    <!-- data-table js -->
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

    <script src="{{url('assets/js/raphael-2.1.0.min.js')}}"></script>
    <script src="{{url('assets/js/morris.min.js')}}"></script>

    <script>
    $('#usulanTable').DataTable({
        searching: false,
        paging:   false,
        ordering: false,
        info:     false,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function() {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });

    $('#lengkapTable').DataTable({
        searching: false,
        paging:   false,
        ordering: false,
        info:     false,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function() {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });
    </script>

    <script>
        $(function() {
            
            $.ajax({
                url: '{{ URL::to('dsn/datagrafikpkm') }}/{{$tahunpkm}}/{{$tipepkm}}',
                dataType: 'JSON',
                type: 'GET',
                data: {get_values: true},
                success: function(response) {
                    Morris.Bar({
                        element: 'bar-chart',
                        data: response,
                        xkey: 'nama_singkat',
                        @if ($tpkm->tipe == '2 Bidang')
                        ykeys: ['PKMAI','PKMGT'],
                        labels: ['PKMAI','PKMGT']
                        @elseif ($tpkm->tipe == '5 Bidang')
                        ykeys: ['PKMKC','PKMT','PKMM','PKMPE','PKMPSH','PKMK'],
                        labels: ['PKMKC','PKMT','PKMM','PKMPE','PKMPSH','PKMK']
                        @elseif ($tpkm->tipe == 'PKM GFK')
                        ykeys: ['PKMGFK'],
                        labels: ['PKMGFK']
                        @elseif ($tpkm->tipe == 'SUG')
                        ykeys: ['SUG'],
                        labels: ['SUG']
                        @endif
                    });
                }
            });
        });
    </script>
    <script>
        $(function() {

            $.ajax({
                url: '{{ URL::to('dsn/datagrafikpkmlengkap') }}/{{$tahunpkm}}/{{$tipepkm}}',
                dataType: 'JSON',
                type: 'GET',
                data: {get_values: true},
                success: function(response) {
                    Morris.Bar({
                        element: 'bar-lengkap',
                        data: response,
                        xkey: 'nama_singkat',
                        @if ($tpkm->tipe == '2 Bidang')
                        ykeys: ['PKMAI','PKMGT'],
                        labels: ['PKMAI','PKMGT']
                        @elseif ($tpkm->tipe == '5 Bidang')
                        ykeys: ['PKMKC','PKMT','PKMM','PKMPE','PKMPSH','PKMK'],
                        labels: ['PKMKC','PKMT','PKMM','PKMPE','PKMPSH','PKMK']
                        @elseif ($tpkm->tipe == 'PKM GFK')
                        ykeys: ['PKMGFK'],
                        labels: ['PKMGFK']
                        @elseif ($tpkm->tipe == 'SUG')
                        ykeys: ['SUG'],
                        labels: ['SUG']
                        @endif
                    });
                }
            });
        });
    </script>
@endsection
