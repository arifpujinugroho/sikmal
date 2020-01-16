$(document).ready(function() {
    var thisurl = $('#thisurl').val();
    //var id = $('#idpkm').val();
    $('#form_tambahan').hide();

    var table = $('#lookup').DataTable({
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
        table.ajax.reload();
    });

    $('#lookup tbody').on('click', '.pilih', function() {
        $('#namatoko').val($(this).data('nama'));
        $('#id_toko').val($(this).data('kode'));
        $('#myModal').modal('hide');
        $('#form_tambahan').show();
    });

    $('#tambahModal').click(function(){
        $('#myModal').modal('hide');
        $('#tambahLink').modal('show');
    });

    
    $('#tbh_toko').click(function(){
        var code = $('#token').val();
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
                    table.ajax.reload();
                } else {
                    table.ajax.reload();
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

});