
$(document).ready(function() {
    var thisurl = $('#thisurl').val();
    var id = $('#idpkm').val();

    function dataCall(){
        $.ajax({
            url: thisurl+'/penggunaandana',
            type: "GET",
            data: {
                idpkm: id,
            },
            success: function(data) {
                var bilangan = data.self;
                var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                var persen = (data.self/data.danai)*100;
                    $('#persenDana').html(persen);
                    $('#selfDana').html(ribuan);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Error!", "Gagal mengambil data!", "error");
            }
        });
    };
    dataCall();

    var table = $('#danaTable').DataTable({
        'dom': 'Bfrtip',
        'buttons': [
            {
                text: 'Tambah Nota',
                className: 'btn-mini',
                action: function(e, dt, node, config) {
                    window.open(thisurl+'/dana/nota/tambah?id='+id,'_self');
                }
            },
            {
                text: 'List Nota',
                className: 'btn-inverse btn-mini',
                action: function(e, dt, node, config) {
                    window.open(thisurl+'/dana/nota/list?id='+id,'_self');
                }
            }
        ],
        'serverMethod': 'get',
        "paging": false,
        "processing": true,
        "ordering": false,
        'responsive':true,
        "info": true,
        //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "zeroRecords": "Maaf Belum ada data Pembelian"
        },
        'ajax': {
            'url': thisurl+'/dana/data/'+id,
            'dataSrc': '',
        },
        'columns': [
            {
                data: null,
                render: function(data, type, full, row) {
                    return '<a href="'+thisurl+'/dana/nota/lihat?id='+data.id_nota+'"><button class="btn btn-mini btn-success"><i class="fa fa-eye"></i></button></a>'
                }
            },
            {
                data: 'tgl_nota'
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.cek_pembelian == "" || data.cek_pembelian == null){
                        if(data.komentar_pembelian == "" || data.komentar_pembelian == null){
                            return '<p>'+data.nama_pembelian+'</p>';
                        }else{
                            return '<p class="lihatKomentar text-warning" data-komentar="'+data.komentar_pembelian+'" data-toggle="tooltip" title="Lihat Komentar">'+data.nama_pembelian+' <i class="fa fa-commenting"></i></p>';
                        }
                    }else{
                        if(data.cek_pembelian == 1){
                            return '<p class="text-success"> <i class="fa fa-check-circle text-success" aria-hidden="true" data-toggle="tooltip" title="Sudah Dicek"></i> '+data.nama_pembelian+'</p>';
                        }else if(data.cek_pembelian == 2){
                            //if(data.komentar_pembelian == "" || data.komentar_pembelian == null){
                            //    return '<i class="fa fa-exclamation-triangle"></i><p class="text-danger">'+data.nama_pembelian+'</p>';
                            //}else{
                                return '<p class="lihatKomentar text-danger" data-komentar="'+data.komentar_pembelian+'" data-toggle="tooltip" title="Lihat Komentar"><i class="fa fa-exclamation-triangle"></i> '+data.nama_pembelian+' <i class="fa fa-commenting"></i></p>';
                            //}
                        }
                    }   
                }
            },
            {
                data: 'nama_kategori'
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    return '<p class="" data-nama="'+data.nama_toko+'" data-toko="'+data.id_toko+'">'+data.nama_toko+'</p>';
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    var bilangan = data.volume;
                    var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                    return ribuan;
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    var bilangan = data.nominal;
                    var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                    return ribuan;
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    var bilangan = data.volume * data.nominal;
                    var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                    return ribuan;
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.ppn == "" || data.ppn == null){
                        return "-";
                    }else{
                        //10%
                        var jml = data.volume * data.nominal;
                        var ppn = (10/11)*(jml)*(10/100);
                        var bilangan = ppn.toFixed(0);
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                        ribuan 	= reverse.match(/\d{1,3}/g);
                        ribuan	= ribuan.join('.').split('').reverse().join('');
                        return '<p data-toggle="tooltip" title="10%">'+ribuan+'</p>';
                    }
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.pph21 == "" || data.pph21 == null){
                        return "-";
                    }else{
                        if(data.pph21 == 1){
                            //2,5%
                            var jml = data.volume * data.nominal;
                            var pph21_1 = (2.5/100)*jml;
                            var bilangan = pph21_1.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="2,5%">'+ribuan+'</p>';
                        } else if(data.pph21 == 2){
                            //5%
                            var jml = data.volume * data.nominal;
                            var pph21_2 = (5/100)*jml;
                            var bilangan = pph21_2.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="5%">'+ribuan+'</p>';
                        } else if(data.pph21 == 3){
                            //6%
                            var jml = data.volume * data.nominal;
                            var pph21_3 = (6/100)*jml;
                            var bilangan = pph21_3.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="6%">'+ribuan+'</p>';
                        } else if(data.pph21 == 4){
                            //15%
                            var jml = data.volume * data.nominal;
                            var pph21_4 = (15/100)*jml;
                            var bilangan = pph21_4.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="10%">'+ribuan+'</p>';
                        }
                    }
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.pph22 == "" || data.pph22 == null){
                        return "-";
                    }else{
                        //1,5%
                        var jml = data.volume * data.nominal;
                        var pph22 = (10/11)*(jml)*(1.5/100);
                        var bilangan = pph22.toFixed(0);
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                        ribuan 	= reverse.match(/\d{1,3}/g);
                        ribuan	= ribuan.join('.').split('').reverse().join('');
                        return '<p data-toggle="tooltip" title="1,5%">'+ribuan+'</p>';
                    }
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.pph23 == "" || data.pph23 == null){
                        return "-";
                    }else{
                        if(data.pph23 == 1){
                            //2%
                            var jml = data.volume * data.nominal;
                            var pph23_1 = (2/100)*jml;
                            var bilangan = pph23_1.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="2%">'+ribuan+'</p>';
                        } else if(data.pph23 == 2){
                            //4%
                            var jml = data.volume * data.nominal;
                            var pph23_2 = (4/100)*jml;
                            var bilangan = pph23_2.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="4%">'+ribuan+'</p>';
                        } else if(data.pph23 == 3){
                            //2% (khusus)
                            var jml = data.volume * data.nominal;
                            var pph23_3 = (10/11)*(jml)*(2/100);
                            var bilangan = pph23_3.toFixed(0);
                            var	reverse = bilangan.toString().split('').reverse().join(''),
                            ribuan 	= reverse.match(/\d{1,3}/g);
                            ribuan	= ribuan.join('.').split('').reverse().join('');
                            return '<p data-toggle="tooltip" title="2% khusus">'+ribuan+'</p>';
                        }
                    }
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.pph26 == "" || data.pph26 == null){
                        return "-";
                    }else{
                        //2%
                        var jml = data.volume * data.nominal;
                        var pph26 = (2/100)*jml;
                        var bilangan = pph26.toFixed(0);
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                        ribuan 	= reverse.match(/\d{1,3}/g);
                        ribuan	= ribuan.join('.').split('').reverse().join('');
                        return '<p data-toggle="tooltip" title="2%">'+ribuan+'</p>';
                    }
                }
            },
        ]
    });

    $('#reload-tabel').click(function() {
        table.ajax.reload();
        dataCall();
    });

    $('#danaTable tbody').on('click', '.lihatKomentar', function() {
        var komentar = $(this).data('komentar');
        swal(komentar);

    });
    $('#danaTable tbody').on('click', '.danaToko', function() {
        var idtoko = $(this).data('toko');
        var nama = $(this).data('nama');
        $.ajax({
            url: thisurl+'/totaltoko',
            type: "GET",
            data: {
                idtoko: idtoko,
                idpkm: id,
            },
            success: function(data) {
                var bilangan = data.total;
                var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                new PNotify({
                    text: 'Total dana '+nama+' : Rp.'+ribuan,
                    addclass: 'stack-bottom-left bg-info',
                    //stack: 'stack_bottom_left',
                    icon: 'icofont icofont-info-circle',
                    type: 'info'
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Error!", "Gagal mengambil data!", "error");
            }
        });
    });
    //setInterval(function() {
    //table.ajax.reload();
    //}, 500 );

});
