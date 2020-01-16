
$(document).ready(function() {
    var code = $('#token').val();
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
        //'dom': 'Bfrtip',
        //'buttons': [
        //    {
        //        text: 'Tambah Nota',
        //        className: 'btn-mini',
        //        action: function(e, dt, node, config) {
        //            window.open(thisurl+'/dana/nota/tambah?id='+id,'_self');
        //        }
        //    },
        //    {
        //        text: 'List Nota',
        //        className: 'btn-inverse btn-mini',
        //        action: function(e, dt, node, config) {
        //            window.open(thisurl+'/dana/nota/list?id='+id,'_self');
        //        }
        //    }
        //],
        'serverMethod': 'get',
        "paging": false,
        "processing": true,
        "ordering": true,
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
                    if(data.cek_pembelian == 1){
                        return '<div class="btn-group">'+
                        //'<a href="'+thisurl+'/dana/nota/lihat?id='+data.id_nota+'"><button class="btn btn-mini btn-default"><i class="fa fa-eye"></i></button></a>'+
                        '<button data-toggle="tooltip" title="Edit '+data.nama_pembelian+'" '+
                            'data-id="'+data.id+'"'+
                            'data-nama="'+data.nama_pembelian+'"'+
                            'data-kategori="'+data.id_kategori+'"'+
                            'data-volume="'+data.volume+'" '+
                            'data-nominal="'+data.nominal+'" '+
                            'data-ppn="'+data.ppn+'"'+
                            'data-pph21="'+data.pph21+'"'+
                            'data-pph22="'+data.pph22+'"'+
                            'data-pph23="'+data.pph23+'"'+
                            'data-pph26="'+data.pph26+'"'+
                            'data-tolak="'+data.cek_pembelian+'"'+
                            'data-komentar="'+data.komentar_pembelian+'"'+
                            'class="LihatTran btn btn-mini btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>'+
                        '</button>'+
                        '<button data-toggle="tooltip" title="Hapus Acc '+data.nama_pembelian+'" data-id="'+data.id+'" data-nama="'+data.nama_pembelian+'" class="HapusAcc btn btn-mini btn-danger"><i class="fa fa-trash"></i></button>'+
                        '</div>';
                    }else{
                        return '<div class="btn-group">'+
                        //'<a href="'+thisurl+'/dana/nota/lihat?id='+data.id_nota+'"><button class="btn btn-mini btn-default"><i class="fa fa-eye"></i></button></a>'+
                        '<button data-toggle="tooltip" title="Edit '+data.nama_pembelian+'" '+
                            'data-id="'+data.id+'"'+
                            'data-nama="'+data.nama_pembelian+'"'+
                            'data-kategori="'+data.id_kategori+'"'+
                            'data-volume="'+data.volume+'" '+
                            'data-nominal="'+data.nominal+'" '+
                            'data-ppn="'+data.ppn+'"'+
                            'data-pph21="'+data.pph21+'"'+
                            'data-pph22="'+data.pph22+'"'+
                            'data-pph23="'+data.pph23+'"'+
                            'data-pph26="'+data.pph26+'"'+
                            'data-tolak="'+data.cek_pembelian+'"'+
                            'data-komentar="'+data.komentar_pembelian+'"'+
                            'class="LihatTran btn btn-mini btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>'+
                        '</button>'+
                        '<button data-toggle="tooltip" title="Acc '+data.nama_pembelian+'" data-id="'+data.id+'" data-nama="'+data.nama_pembelian+'" class="AccTran btn btn-mini btn-success"><i class="fa fa-check-circle"></i></button>'+
                        '</div>';
                    }
                }
            },
            {data: null,
                render: function(data, type, full, row) {
                    return '<p class="lihatNota"  data-file="'+data.file_nota+'" data-nama="'+data.nama_toko+'">'+data.tgl_nota+'</p>';
                }
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    if(data.cek_pembelian == "" || data.cek_pembelian == null){
                        if(data.komentar_pembelian == "" || data.komentar_pembelian == null){
                            return '<p>'+data.nama_pembelian+'</p>';
                        }else{
                            return '<p class="text-warning" data-toggle="tooltip" title="'+data.komentar_pembelian+'">'+data.nama_pembelian+' <i class="fa fa-commenting"></i></p>';
                        }
                    }else{
                        if(data.cek_pembelian == 1){
                            return '<p class="text-success"> <i class="fa fa-check-circle text-success" aria-hidden="true" data-toggle="tooltip" title="Sudah Dicek"></i> '+data.nama_pembelian+'</p>';
                        }else if(data.cek_pembelian == 2){
                            //if(data.komentar_pembelian == "" || data.komentar_pembelian == null){
                            //    return '<i class="fa fa-exclamation-triangle"></i><p class="text-danger">'+data.nama_pembelian+'</p>';
                            //}else{
                                return '<p class="text-danger" data-toggle="tooltip" title="'+data.komentar_pembelian+'"><i class="fa fa-exclamation-triangle"></i> '+data.nama_pembelian+' <i class="fa fa-commenting"></i></p>';
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
                    return '<p class="danaToko" data-nama="'+data.nama_toko+'" data-toko="'+data.id_toko+'">'+data.nama_toko+'</p>';
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

                var bilanganpajak = data.less;
                var	reversepajak = bilanganpajak.toString().split('').reverse().join(''),
                    pajak 	= reversepajak.match(/\d{1,3}/g);
                    pajak	= pajak.join('.').split('').reverse().join('');
                    
                new PNotify({
                    text: 'Total dana '+nama+' : Rp.'+ribuan+',-  || Kena Pajak :'+pajak,
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

    
    $('#danaTable tbody').on('click', '.lihatNota', function() {
        var nama = $(this).data('nama');
        var link = $(this).data('file');

        $('#labellihatToko').html('Nota '+nama);
        $('#lihatBody').html('<img src="http://pkm.kemahasiswaan.uny.ac.id/storage/files/nota/'+link+'" class="img-fluid" alt="nota '+nama+'">');
        $('#lihatSurat').modal('show');
    });

    
    $('#danaTable tbody').on('click', '.LihatTran', function() {
        $('#id_transaksi').val($(this).data('id'));
        $('#tNamaPembelian').val($(this).data('nama'));
        $('#tKategori').val($(this).data('kategori'));
        $('#tVolume').val($(this).data('volume'));
        $('#tHargaSatuan').val($(this).data('nominal'));
        $('#tJumlah').val("");
        $('#tPPn').val($(this).data('ppn'));
        $('#tPPh21').val($(this).data('pph21'));
        $('#tPPh22').val($(this).data('pph22'));
        $('#tPPh23').val($(this).data('pph23'));
        $('#tPPh26').val($(this).data('pph26'));
        $('#ttolak').val($(this).data('tolak'));
        $('#tkomentar').val($(this).data('komentar'));
        $('#ftsiup').hide();
        $('#ftJumlah').hide();
        $('#ftnpwp').hide();
        $('#formTransaksi').attr('disabled','disabled');
        //$('#formTransaksi').removeAttr('disabled');
        $('#tambahTransaksiBtn').hide();
        $('#editTransaksiBtn').show();
        $('#saveTransaksiBtn').hide();
        $('#transaksiModal').modal('show');
        $('#transaksiTitle').html('Lihat Transaksi');
        $('#tNamaPembelian').focus();
    });
    

    $('#editTransaksiBtn').click(function(){
        //$('#formTransaksi').attr('disabled','disabled');
        $('#formTransaksi').removeAttr('disabled');
        $('#tambahTransaksiBtn').hide();
        $('#editTransaksiBtn').hide();
        $('#saveTransaksiBtn').show();
        //$('#transaksiModal').modal('show');
        $('#transaksiTitle').html('Edit Transaksi');
        $('#tNamaPembelian').focus();
    });

    
    $('#saveTransaksiBtn').click(function(){
        var idtr = $('#id_transaksi').val();
        var nama = $('#tNamaPembelian').val();
        var kategori = $('#tKategori').val();
        var volume = $('#tVolume').val();
        var harga = $('#tHargaSatuan').val();
        //$('#tJumlah').val();
        var ppn = $('#tPPn').val();
        var pph21 = $('#tPPh21').val();
        var pph22 = $('#tPPh22').val();
        var pph23 = $('#tPPh23').val();
        var pph26 = $('#tPPh26').val();
        var tolak = $('#ttolak').val();
        var komentar = $('#tkomentar').val();

        $.ajax({
            url: thisurl+'/edittransaksi',
            type: "POST",
            data: {
                _token: code,
                idtr: idtr,
                nama : nama,
                kategori: kategori,
                volume: volume,
                nominal: harga,
                ppn :ppn,
                pph21:pph21,
                pph22:pph22,
                pph23:pph23,
                pph26:pph26,
                tolak:tolak,
                komentar:komentar,
            },
            success: function(data) {
                if ($.isEmptyObject(data)) {
                    new PNotify({
                        title: 'Gagal Simpan!',
                        text: 'Maaf Gagal Menyimpan data, Silakan Cek Kembali',
                        icon: 'icofont icofont-info-circle',
                        type: 'warning'
                    });
                    table.ajax.reload();
                    dataCall();
                } else {
                    table.ajax.reload();
                    dataCall();
                    $('#transaksiModal').modal('hide');
                    new PNotify({
                        title: 'Transaksi Berhasil Diubah',
                        text:  nama+' berhasil diubah',
                        icon:  'icofont icofont-info-circle',
                        type:  'success'
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Error!", "Gagal mengambil data!", "error");
            }
        });
    });

    

    $('#danaTable tbody').on('click', '.HapusAcc', function() {
        var idtr = $(this).data('id');
        var nama = $(this).data('nama');

        swal({
            title: "Hapus Acc "+nama+"??",
            text: "Apakah anda ingin menghapus Acc Transaksi ini?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: thisurl+'/hapusacctransaksi',
                type: "POST",
                data: {
                    _token: code,
                    idtr: idtr,
                },
                success: function() {
                    swal("Acc Dihapus!", "Anda berhasil menghapus Acc Transakasi "+nama, "success");
                    table.ajax.reload();
                    dataCall();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Dihapus!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });

    

    $('#danaTable tbody').on('click', '.AccTran', function() {
        var idtr = $(this).data('id');
        var nama = $(this).data('nama');

        swal({
            title: "Setujui "+nama+"??",
            text: "Apakah anda ingin menyetujui Transaksi ini?",
            type: "info",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Setuju",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: thisurl+'/acctransaksi',
                type: "POST",
                data: {
                    _token: code,
                    idtr: idtr,
                },
                success: function() {
                    swal(" Disetujui!", "Anda berhasil menyetujui Transakasi "+nama, "success");
                    table.ajax.reload();
                    dataCall();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Acc!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });

});
