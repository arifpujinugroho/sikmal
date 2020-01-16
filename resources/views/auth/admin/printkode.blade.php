<!DOCTYPE html>

<!--


	***** HAYO.. Mau Ngapain Buka Inpect Element? *****
   ******** JANGAN ISENG BUKA BUKA KODINGANNYA ***********

//=========================================================//
//         //\\       ||====    ||      ||==========       //
//        //  \\      ||  //    ||      ||                 //
//       //    \\     || //     ||      ||========         //
//      //======\\    || \\     ||      ||                 //
//     //        \\   ||  \\    ||      ||                 //
//    //          \\  ||   \\   ||      ||                 //
//                                                         //
//        Created By Arif Puji Nugroho (Indonesia)         //
//     Website    : https://arifpn.id/                     //
//     GitHub     : https://Github.com/arifpujinugroho     //
//     Facebook   : https://facebook.com/arifpujin         //
//     Instagram  : https://instagram.com/arifpn.id        //
//     Whatsapp   : +6285885994505                         //
//     Email      : arifpujinugroho@gmail.com              //
//=========================================================//





-->







<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Halaman Print A4</title>
    <meta name="author" content="Web Developer PKM Center UNY - Arif Puji Nugroho">
    <link rel="icon" href="{{url('assets/images/favicon2.ico')}}" sizes="32x32" />
    <link rel="apple-touch-icon-precomposed" href="{{url('assets/images/favicon2.ico')}}" />
    <meta property="og:description" content="Sebuah Sistem bagi Para Pejuang PKM untuk Menuju UNY Juara.">
</head>
<style type="text/css">
    /* Kode CSS Untuk PAGE ini dibuat oleh http://jsfiddle.net/2wk6Q/1/ */
    body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "New Times Roman";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 20mm;
        margin: 10mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .subpage {
        padding: 1cm;
        height: 257mm;
        outline: 2cm rgb(255, 255, 255) solid;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {

        html,
        body {
            width: 210mm;
            height: 297mm;
        }

        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>

<body>
    <div class="book">
        <div class="page" style="text-align: center;">
            <div class="subpage">
                <img src="{{url('assets/images/uny.png')}}" alt="" height="180px">
                <h2>{{$pkm->kode_pkm}}</h2>
                <br>
                <INI style="font-weight: normal;">PROPOSAL PROGRAM KREATIVITAS MAHASISWA <br><span style="font-weight: bold;">{{$pkm->judul}}</span></h3>
                    <h3 style="font-weight: normal;">Bidang Kegiatan : <br> <span style="font-weight: bold;">{{strtoupper($pkm->skim_lengkap)}}</span></h3>
                    <p>Diusulkan oleh :</p>
                    <p>Ketua PKM || {{$pkm->nama_prodi}} - {{$pkm->jenjang_prodi}} || 20{{substr($pkm->nim, 0, 2)}}</p>
                    <p>Anggota 1 || {{$anggota1->nama_prodi}} - {{$anggota1->jenjang_prodi}} ||
                        20{{substr($anggota1->nim, 0, 2)}}</p>
                    <p>Anggota 2 || {{$anggota2->nama_prodi}} - {{$anggota2->jenjang_prodi}} ||
                        20{{substr($anggota2->nim, 0, 2)}}</p>
                    @if($jmlanggota == 4)
                    <p>Anggota 3 || {{$anggota3->nama_prodi}} - {{$anggota3->jenjang_prodi}} ||
                        20{{substr($anggota3->nim, 0, 2)}}</p>
                    @elseif($jmlanggota == 5)
                    <p>Anggota 3 || {{$anggota3->nama_prodi}} - {{$anggota3->jenjang_prodi}} ||
                        20{{substr($anggota3->nim, 0, 2)}}</p>
                    <p>Anggota 4 || {{$anggota4->nama_prodi}} - {{$anggota4->jenjang_prodi}} ||
                        20{{substr($anggota4->nim, 0, 2)}}</p>
                    @endif
                    <br>
                    <h3>UNIVERSITAS NEGERI YOGYAKARTA <br> {{$pkm->tahun}}</h3>
            </div>
        </div>

        <div class="page">
            <div class="subpage">
                <h4>Kode PKM : {{$pkm->kode_pkm}} || @if($pkm->self == "Y")
                    W
                    @else
                    TW
                    @endif<br> Judul PKM : {{$pkm->judul}}<br>Dana : Rp. {{number_format($pkm->dana_pkm, 0, ".", ".")}}
                    <br>
                    <hr><br>
                    @if($pkm->id_skim_pkm == 1 || $pkm->id_skim_pkm == 2)
                    <img src="{{url('assets/penilaian/PKM-Pnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 3)
                    <img src="{{url('assets/penilaian/PKM-Tnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 4)
                    <img src="{{url('assets/penilaian/PKM-Knilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 5)
                    <img src="{{url('assets/penilaian/PKM-KCnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 6)
                    <img src="{{url('assets/penilaian/PKM-Mnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 7)
                    <img src="{{url('assets/penilaian/PKM-GTnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 8)
                    <img src="{{url('assets/penilaian/PKM-GTnilai.PNG')}}" width="105%">
                    @elseif($pkm->id_skim_pkm == 9)
                    <img src="{{url('assets/penilaian/PKM-GTnilai.PNG')}}" width="105%">
                    @endif
                    <br><br>
                    <img src="{{url('assets/penilaian/Akhir.png')}}" width="105%">
            </div>
        </div>
    </div>
</body>

</html>
<script type="text/javascript">
    window.print();
</script>