
$(document).ready(function() {
    var code = $('#token').val();
    var encrypt = $('#encrypt').val();
    var thisurl = $('#thisurl').val();
    var idpkm = $('#idpkm').val();
    var id = $('#id').val();
    var idtoko = $('#idtoko').val();
    var namanota = $('#namanota').val();
    $('#htmlPajak').hide();
    
    function CekSiupNpwp(){
        $.ajax({
            url: thisurl+'/ceksiupnpwp',
            type: "GET",
            data: {
                idtoko: idtoko,
                idpkm: idpkm,
            },
            success: function(data) {
                if ($.isEmptyObject(data)) {
                    var cekNilaiPajak= $('#kenaPajak').val();
                    if(cekNilaiPajak >= 1000000){
                        $('#uploadSiupNpwp').modal('show');
                    }
                }else{
                    $('#BtnSiup').html('<button data-file="'+data.siup_log+'" class="lihatSiup btn btn-mini btn-success">Lihat SIUP</button><button class="updateSiup btn btn-mini btn-warning">Update SIUP</button>');
                    if(data.npwp_log == "" || data.npwp_log == null){
                        $('#BtnNpwp').html('<button class="updateNpwp btn btn-mini btn-warning" data-jenis="upload">Upload NPWP</button>');
                    }else{
                        $('#BtnNpwp').html('<button data-file="'+data.npwp_log+'" class="lihatNpwp btn btn-mini btn-success">Lihat NPWP</button><button data-jenis="update" class="updateNpwp btn btn-mini btn-warning">Update NPWP</button>');
                    }
                    $('#htmlPajak').show();
                }                    
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Error!", "Gagal mengambil data!", "error");
            }
        });
    }
    
    function dataCall(){
        $.ajax({
            url: thisurl+'/totaltoko',
            type: "GET",
            data: {
                idtoko: idtoko,
                idpkm: idpkm,
            },
            success: function(data) {
                var bilangan = data.total;
                var	reverse = bilangan.toString().split('').reverse().join(''),
                    ribuan 	= reverse.match(/\d{1,3}/g);
                    ribuan	= ribuan.join('.').split('').reverse().join('');
                    $('#jmlBrg').html(data.jumlah);
                    $('#ttlBrg').html('Rp. '+ribuan);
                    $('#kenaPajak').val(data.less); 
                    CekSiupNpwp();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                swal("Error!", "Gagal mengambil data!", "error");
            }
        });
    };
    dataCall();
    CekSiupNpwp();


    var table = $('#danaTable').DataTable({
        'dom': 'Bfrtip',
        'buttons': [
            {
                text: 'Kembali ke Laporan',
                className: 'btn-mini btn-warning',
                action: function(e, dt, node, config) {
                    window.open(thisurl+'/dana/'+idpkm,'_self');
                }
            },
            {
                text: 'Tambah Transaksi',
                className: 'btn-mini',
                action: function(e, dt, node, config) {
                    $('#id_transaksi').val("");
                    $('#tNamaPembelian').val("");
                    $('#tKategori').val("");
                    $('#tVolume').val("");
                    $('#tHargaSatuan').val("");
                    $('#tJumlah').val("");
                    $('#tPPn').val("");
                    $('#tPPh21').val("");
                    $('#tPPh22').val("");
                    $('#tPPh23').val("");
                    $('#tPPh26').val("");
                    $('#ftsiup').hide();
                    $('#ftJumlah').hide();
                    $('#ftnpwp').hide();
                    //$('#formTransaksi').attr('disabled','disabled');
                    $('#formTransaksi').removeAttr('disabled');
                    $('#tambahTransaksiBtn').show();
                    $('#editTransaksiBtn').hide();
                    $('#saveTransaksiBtn').hide();
                    $('#transaksiModal').modal('show');
                    $('#transaksiTitle').html('Tambah Transaksi');
                    $('#tNamaPembelian').focus();
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
            "zeroRecords": "Maaf Belum ada Nota"
        },
        'ajax': {
            'url': thisurl+'/dana/datatransaksi/'+id,
            'dataSrc': '',
        },
        'columns': [
            {
                data: null,
                render: function(data, type, full, row) {
                    return  '<div class="btn-group">'+
                            '<button data-toggle="tooltip" title="Lihat/Edit '+data.nama_pembelian+'" '+
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
                                'class="LihatTran btn btn-mini btn-success"><i class="fa fa-eye" aria-hidden="true"></i>'+
                            '</button>'+
                            '<button data-toggle="tooltip" title="Hapus '+data.nama_pembelian+'" data-id="'+data.id+'" data-nama="'+data.nama_pembelian+'" class="HapusTran btn btn-mini btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
                            '</div>';
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

    var toko = $('#lookup').DataTable({
        'serverMethod': 'get',
        "paging": true,
        "processing": true,
        "ordering": true,
        'responsive':true,
        "info": true,
        //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "zeroRecords": "Maaf Belum ada data Pembelian"
        },
        'ajax': {
            'url': thisurl+'/alltoko',
            'dataSrc': '',
        },
        'columns': [
            {
                data: null,
                render: function(data, type, full, row) {
                    return '<button class="pilih btn btn-primary btn-mini" data-nama="'+data.nama_toko+'" data-kode="'+data.id+'"><i class="fa fa-plus" aria-hidden="true"></i></button>'
                }
            },
            { data: null,
                render: function ( data, type, full,row) {
                    if(data.rekomen == 1){
                    return '<i class="fa fa-check-circle text-success" aria-hidden="true" data-toggle="tooltip" title="Terverifikasi"></i> '+data.nama_toko;
                    } else {
                        return data.nama_toko;
                    }
                }
            },
            {
                data: 'alamat_toko'
            }
        ]
    });

    $('#reload-tabel').click(function() {
        dataCall();
        CekSiupNpwp();
        table.ajax.reload();
        toko.ajax.reload();
    });

    $('#uploadUlangBtn').click(function(){
        $('#uploadUlangModal').modal('show');
    });

    $('#editNotaBtn').click(function(){
        $('#editNotaModal').modal('show');
    });

    $('#hapusNotaBtn').click(function(){
        swal({
            title: "Hapus Nota ini??",
            text: "Jika anda menghapus nota ini, maka semua transaksi yang ada di nota ini akan terhapus!",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: thisurl+'/hapusnota',
                type: "POST",
                data: {
                    _token: code,
                    encrypt: encrypt,
                },
                success: function() {
                    swal(" Dihapus!", "Anda berhasil menghapus Nota ini", "success");
                    window.open(thisurl+'/dana/'+idpkm,'_self');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Dihapus!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });
    
    $('#search').click(function(){
        $('#editNotaModal').modal('hide');
        $('#myModal').modal('show');
    });

    $('#lookup tbody').on('click', '.pilih', function() {
        $('#namatoko').val($(this).data('nama'));
        $('#id_toko').val($(this).data('kode'));
        $('#myModal').modal('hide');
        $('#editNotaModal').modal('show');
    });

    $('#tambahModal').click(function(){
        $('#myModal').modal('hide');
        $('#tambahLink').modal('show');
    });
    
    $('#tbh_toko').click(function(){
        var nama = $('#formnamatoko').val();
        var alamat = $('#formalamattoko').val();

        if(nama != "" && alamat != ""){
                
            $.post(thisurl+'/tambahtoko', {
                _token: code,
                nama: nama,
                alamat: alamat
            })
            .done(function(result) {
                if ($.isEmptyObject(result)) {
                    new PNotify({
                        title: 'Gagal Simpan!',
                        text: 'Maaf Gagal Menyimpan data, Silakan Cek Kembali',
                        icon: 'icofont icofont-info-circle',
                        type: 'warning'
                    });
                    toko.ajax.reload();
                } else {
                    toko.ajax.reload();
                    $('#tambahLink').modal('hide');
                    $('#formnamatoko').val("");
                    $('#formalamattoko').val("");
                    new PNotify({
                        title: 'Toko Berhasil Ditambah',
                        text:  nama+' berhasil ditambah',
                        icon:  'icofont icofont-info-circle',
                        type:  'success'
                    });
                    $('#myModal').modal('show');
                }
            })
            .fail(function() {
                $('#tambahLink').modal('hide');
                new PNotify({
                    title: 'Kesalahan server!',
                    text: 'Ada yang tidak beres dengan server',
                    icon: 'icofont icofont-info-circle',
                    type: 'error'
                });
            }); 
        }else{
            new PNotify({
                title: 'Form ada yang kosong',
                text: 'Harap isi semua form input!',
                icon: 'icofont icofont-info-circle',
                type: 'warning'
            });
        }
    });

    $('#tVolume').keyup(function() {
        var vol = $('#tVolume').val();
        var price = $('#HargaSatuan').val();
        var jml = vol*price;
        $('#tJumlah').val(jml);
    });

    $('#tHargaSatuan').keyup(function() {
        var vol = $('#tVolume').val();
        var price = $('#HargaSatuan').val();
        var jml = vol*price;
        $('#tJumlah').val(jml);
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

    
    $('#tambahTransaksiBtn').click(function(){
        //var id = $('#id_transaksi').val();
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

        $.ajax({
            url: thisurl+'/tambahtransaksi',
            type: "POST",
            data: {
                _token: code,
                encrypt:encrypt,
                nama : nama,
                kategori: kategori,
                volume: volume,
                nominal: harga,
                ppn :ppn,
                pph21:pph21,
                pph22:pph22,
                pph23:pph23,
                pph26:pph26,
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
                        title: 'Transaksi Berhasil Ditambah',
                        text:  nama+' berhasil ditambah',
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

        $.ajax({
            url: thisurl+'/edittransaksi',
            type: "POST",
            data: {
                _token: code,
                encrypt : encrypt,
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

    $('#danaTable tbody').on('click', '.HapusTran', function() {
        var idtr = $(this).data('id');
        var nama = $(this).data('nama');

        swal({
            title: "Hapus "+nama+"??",
            text: "Apakah anda ingin menghapus Transaksi ini?",
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Hapus",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: thisurl+'/hapustransaksi',
                type: "POST",
                data: {
                    _token: code,
                    idtr: idtr,
                },
                success: function() {
                    swal(" Dihapus!", "Anda berhasil menghapus Transakasi"+nama, "success");
                    table.ajax.reload();
                    dataCall();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swal("Error Dihapus!", "Silakan Coba Lagi", "error");
                }
            });
        });
    });

});
