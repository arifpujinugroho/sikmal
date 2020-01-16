$(document).ready(function() {
    var thisurl = $('#thisurl').val();
    var thn = $('#ftahun').val();
    var tipe = $('#ftipe').val();

    var Data =  $('#danaTable').DataTable({
            'serverMethod': 'get',
            "paging": true,
            "processing": true,
            "ordering": true,
            'responsive':true,
            "info": true,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "zeroRecords": "Maaf Belum ada data Lolos Pendanaan"
            },
            'ajax': {
                'url': thisurl+'/datalistkeuangan?tipe='+tipe+'&thn='+thn,
                'dataSrc': '',
            },
            'columns': [
                {
                    data: null,
                    render: function(data, type, full, row) {
                        return '<a href="'+thisurl+'/dana/'+data.id+'"><button class="btn btn-mini btn-success"><i class="fa fa-eye"></i></button></a>'
                    }
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        if(data.dana_acc == "" || data.dana_acc == null){
                            return '<p>'+data.nama+'</p>'
                        }else{
                            return '<p class="text-success"><i class="fa fa-check-circle" aria-hidden="true"></i> '+data.nama+'</p>'
                        }
                    }
                },
                {
                    data: 'tahun'
                },
                {
                    data: 'skim_singkat'
                },
                {
                    data: null,
                    render: function(data, type, full, row) {
                        var bilangan = data.dana_dikti;
                        var	reverse = bilangan.toString().split('').reverse().join(''),
                        ribuan 	= reverse.match(/\d{1,3}/g);
                        ribuan	= ribuan.join('.').split('').reverse().join('');
                        return ribuan;
                    }
                },
                {
                    data: 'judul'
                },
            ]
        });
    
    $('#reload-tabel').click(function() {
        Data.ajax.reload();
    });



});