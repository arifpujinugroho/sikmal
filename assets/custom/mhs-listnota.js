
$(document).ready(function() {
    var thisurl = $('#thisurl').val();
    var id = $('#idpkm').val();

    var table = $('#danaTable').DataTable({
        'dom': 'Bfrtip',
        'buttons': [
            {
                text: 'Kembali ke Laporan',
                className: 'btn-mini btn-warning',
                action: function(e, dt, node, config) {
                    window.open(thisurl+'/dana/'+id,'_self');
                }
            },
            {
                text: 'Tambah Nota',
                className: 'btn-mini',
                action: function(e, dt, node, config) {
                    window.open(thisurl+'/dana/nota/tambah?id='+id,'_self');
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
            'url': thisurl+'/dana/datanota/'+id,
            'dataSrc': '',
        },
        'columns': [
            {
                data: 'tgl_nota'
            },
            {
                data: 'nama_toko'
            },
            {
                data: null,
                render: function(data, type, full, row) {
                    return '<a href="'+thisurl+'/dana/nota/lihat?id='+data.id+'"><button class="btn btn-mini btn-success">lihat</button></a>'
                }
            },
        ]
    });

    $('#reload-tabel').click(function() {
        table.ajax.reload();
    });
    //setInterval(function() {
    //table.ajax.reload();
    //}, 500 );

});
