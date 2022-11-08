@extends('adminlte::page')

@section('title', 'Trucking Vendor Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    @permission('delete-pembayaran')
    <button type="button" class="btn btn-success btn-xs" onclick="hapus()"><i class="fa fa-times-circle"></i> Delete All</button>
    @endpermission
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Trucking Vendor <b>{{$pembayaran->no_pembayaran}}</b></font>
    </span>
@include('sweet::alert')
<body onLoad="load()">
    <div class="box box-danger">
        <div class="box-body"> 
            <div class="addform">
                    @include('errors.validation')
                    {!! Form::open(['id'=>'ADD_DETAIL']) !!}
                      <center><kbd>ADD FORM</kbd></center><br>
                        <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('no_pembayaran', 'No Transaksi:') }}
                                        {{ Form::text('no_pembayaran',$pembayaran->no_pembayaran, ['class'=> 'form-control','readonly','id'=>'nopembayaran']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_pemilik', 'Pemilik Mobil:') }}
                                        <button type="button" class="btn btn-primary btn-xs" title="Get SPB" onclick="getspb()" id='submit3'>Get SPB</button>
                                        {{ Form::hidden('kode_pemilik',$pembayaran->kode_pemilik, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'kode_pemilik','required','readonly']) }}
                                        
                                        {{ Form::text('nama_pemilik',$nama_pemilik, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'nama_pemilik','required','readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('tanggalkembali_dari', 'Tgl SPB Kembali Dari:') }}
                                        {{ Form::date('tanggalkembali_dari',$pembayaran->tanggalkembali_dari,['class'=> 'form-control','id'=>'dari' ,'required','readonly'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('tanggalkembali_sampai', 'Tgl SPB Kembali Sampai:') }}
                                        {{ Form::date('tanggalkembali_sampai',$pembayaran->tanggalkembali_sampai,['class'=> 'form-control','id'=>'sampai' ,'required','readonly'])}}
                                    </div>
                                </div>
                            </div>                      
                    {!! Form::close() !!}    
            </div>
    </div>
</div>


   <div class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Transaksi</th>
                        <th>No JO</th>
                        <th>No SPB</th>
                        <th>Tanggal SPB</th>
                        <th>Tanggal Kembali SPB</th>
                        <th>Mobil</th>
                        <th>Sopir</th>
                        <th>Container</th>
                        <th>Gudang</th>
                        <th>Tarif</th>
                        <th>Uang Jalan</th>
                        <th>Sisa</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                     </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-warning">
                            <th class="text-center" colspan="9">Total</th>
                            <th id="grandtotal">-</th>
                            <th id="grandtotal2">-</th>
                            <th id="grandtotal3">-</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <button type="button" class="back2Top btn btn-warning btn-xs" id="back2Top"><i class="fa fa-arrow-up" style="color: #fff"></i> <i>{{ $nama_company }}</i> <b>({{ $nama_lokasi }})</b></button>

        <style type="text/css">
            #back2Top {
                width: 400px;
                line-height: 27px;
                overflow: hidden;
                z-index: 999;
                display: none;
                cursor: pointer;
                position: fixed;
                bottom: 0;
                text-align: left;
                font-size: 15px;
                color: #000000;
                text-decoration: none;
            }
            #back2Top:hover {
                color: #fff;
            }

            /* Button used to open the contact form - fixed at the bottom of the page */
            #about {
              top: 70px;
              background-color: #4CAF50;
            }

            #blog {
              top: 130px;
              background-color: #2196F3;
            }

            #projects {
              top: 190px;
              background-color: #f44336;
            }

            #contact {
              top: 250px;
              background-color: #555
            }

            .hapus-button {
                background-color: #F63F3F;
                bottom: 66px;
            }

            #mySidenav button {
              position: fixed;
              right: -30px;
              transition: 0.3s;
              padding: 4px 8px;
              width: 90px;
              text-decoration: none;
              font-size: 12px;
              color: white;
              border-radius: 5px 0 0 5px ;
              opacity: 0.8;
              cursor: pointer;
              text-align: left;
            }
        </style>

        <div id="mySidenav" class="sidenav">
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuspembayaran" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
        </div>
</body>
@stop

@push('css')

@endpush
@push('js')
    <script>
        $(window).scroll(function() {
            var height = $(window).scrollTop();
            if (height > 1) {
                $('#back2Top').show();
            } else {
                $('#back2Top').show();
            }
        });
        
        $(document).ready(function() {
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });
        });

        function load(){
            startTime();
            $('.hapus-button').hide();
            $('.editform').hide();
            $('.back2Top').show();
            submit.disabled = true;
        }
        
        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }
        
    $(function(){
        var no_pembayaran = $('#nopembayaran').val();
        
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:'http://localhost/gui_front_02/admin/pembayarandetail/getDatabyID?id='+no_pembayaran,
            data:{'no_pembayaran':no_pembayaran},
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status_spb'] == 1) {
                    $('td', row).css('background-color', '#ffdbd3');
                }
            },
            footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
        
        
                    // Total over this page
                    grandTotal = api
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    grandTotal2 = api
                        .column( 10 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    grandTotal3 = api
                        .column( 11 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        
                    // Update footer
                    $( api.column( 9 ).footer() ).html(
                        ''+formatRupiah(grandTotal)
                    );

                    $( api.column( 10 ).footer() ).html(
                        ''+formatRupiah(grandTotal2)
                    );

                    $( api.column( 11 ).footer() ).html(
                        ''+formatRupiah(grandTotal3)
                    );
                },

            columns: [
                { data: 'no_pembayaran', name: 'no_pembayaran' },
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'no_spb', name: 'no_spb' },
                { data: 'tgl_spb', name: 'tgl_spb' },
                { data: 'tgl_kembali', name: 'tgl_kembali' },
                { data: 'mobil.nopol', name: 'mobil.nopol' },
                { data: 'sopir.nama_sopir', 
                    render: function( data, type, full ) {
                    return formatSopir(data, full); }
                },
                { data: 'kode_container', name: 'kode_container' },
                { data: 'gudangdetail.nama_gudang', "defaultContent": "-" },
                { data: 'tarif', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'uang_jalan', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'sisa', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'dari', name: 'dari' },
                { data: 'tujuan', name: 'tujuan' },
            ]
            
        });
        
    });

        $(document).ready(function() {
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            var table = $('#data2-table').DataTable();

            $('#data2-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.hapus-button').hide();
                }
                else {
                    $('.hapus-button').show();
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                }
            });

            $('#hapuspembayaran').click( function () {
                var select = $('.selected').closest('tr');
                var no_pembayaran = select.find('td:eq(0)').text();
                var no_joborder = select.find('td:eq(1)').text();
                var no_spb = select.find('td:eq(2)').text();
                var row = table.row( select );
                swal({
                    title: "Hapus?",
                    text: "Pastikan dahulu item yang akan di hapus",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{!! route('pembayarandetail.hapus_pembayaran') !!}',
                            type: 'POST',
                            data : {
                                'no_pembayaran': no_pembayaran,
                                'no_joborder': no_joborder,
                                'no_spb': no_spb,
                            },

                            success: function (results) {
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                refreshTable();
                            }
                        });
                    }
                });
            });
        });
    
        function getspb()
        {
            var no_pembayaran = $('#nopembayaran').val();
            var kode_pemilik = $('#kode_pemilik').val();
            var dari = $('#dari').val();
            var sampai = $('#sampai').val();
            
                swal({
                    title: "Anda yakin data sudah benar?",
                    text: "Pastikan dahulu nama pemilik mobil yang dipilih",
                    type: "question",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, benar!",
                    cancelButtonText: "Batal!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{!! route('pembayarandetail.getspb') !!}',
                            type: 'POST',
                            data : {
                                'no_pembayaran': no_pembayaran,
                                'kode_pemilik': kode_pemilik,
                                'dari': dari,
                                'sampai': sampai,
                            },

                            success: function (results) {
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                refreshTable();
                            }
                        });
                    }
                });
        }

        function formatSopir(n, m) {
            console.log(m);
            if(n == null){
                var stat = m["kode_sopir"];
                return stat;
            }else{
                var str = n;
                var result = str;
                return result;
            }
        }

        function formatNumber(n) {
                if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function hapus() {
            var no_pembayaran= $('#nopembayaran').val();

            swal({
            title: "Hapus?",
            text: "Pastikan dahulu item yang akan di hapus",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value === true) {
                $.ajax({
                    url:'{!! route('pembayarandetail.hapusall') !!}',
                    type:'POST',
                    data : {
                            'id': no_pembayaran
                        },
                    success: function(result) {
                            console.log(result);

                            if (result.success === true) {
                                swal("Berhasil!", result.message, "success");
                            } else {
                                swal("Gagal!", result.message, "error");
                            }
                            // window.location.reload();
                            refreshTable();
                         }
                });
            }
            });
        }

    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table=$('#data-table').DataTable({
                scrollY: true,
                scrollX: true,
            
            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function refreshTable() {
             $('#data2-table').DataTable().ajax.reload(null,false);;
        }
    </script>
@endpush