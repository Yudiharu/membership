@extends('adminlte::page')

@section('title', 'Hasil Bagi Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="tablefresh()"><i class="fa fa-refresh"></i> Refresh</button>
    @permission('delete-hasilbagi')
    <button type="button" class="btn btn-success btn-xs" onclick="hapus()"><i class="fa fa-times-circle"></i> Delete All</button>
    @endpermission
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Hasil Bagi <b>{{$hasilbagi->no_hasilbagi}}</b></font>
    </span>
@include('sweet::alert')
{{ Form::hidden('Link',request()->getSchemeAndHttpHost(), ['class'=> 'form-control','readonly','id'=>'Link1']) }}
<body onLoad="load()">
    <div class="box box-danger">
        <div class="box-body"> 
            <div class="addform">
                    @include('errors.validation')
                    {!! Form::open(['id'=>'ADD_DETAIL']) !!}
                      <center><kbd>ADD FORM</kbd></center><br>
                        <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group1">
                                        {{ Form::label('no_hasilbagi', 'No Hasil Bagi:') }}
                                        {{ Form::text('no_hasilbagi',$hasilbagi->no_hasilbagi, ['class'=> 'form-control','readonly','id'=>'nohasilbagi']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_sopir', 'Sopir:') }}
                                        <button type="button" class="btn btn-primary btn-xs" title="Get SPB" onclick="getspb()" id='submit3'>Get SPB</button>
                                        {{ Form::hidden('kode_sopir',$hasilbagi->kode_sopir, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'kode_sopir','required','readonly']) }}
                                        
                                        {{ Form::text('nama_sopir',$nama_sopir, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'nama_sopir','required','readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('spb_dari', 'Tgl SPB Kembali Dari:') }}
                                        {{ Form::date('spb_dari',$hasilbagi->spb_dari,['class'=> 'form-control','id'=>'dari' ,'required','readonly'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('spb_sampai', 'Tgl SPB Kembali Sampai:') }}
                                        {{ Form::date('spb_sampai',$hasilbagi->spb_sampai,['class'=> 'form-control','id'=>'sampai' ,'required','readonly'])}}
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
                        <th>No Hasil Bagi</th>
                        <th>No SPB</th>
                        <th>No SPB Manual</th>
                        <th>Tanggal SPB</th>
                        <th>Tanggal SPB Kembali</th>
                        <th>Mobil</th>
                        <th>Gudang</th>
                        <th>Container</th>
                        <th>Muatan</th>
                        <th>Tarif HBU</th>
                        <th>Uang Jalan</th>
                        <th>BBM</th>
                        <th>Sisa</th>
                        <th>Sisa UJ-BBM</th>
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
                            <th id="grandtotal4">-</th>
                            <th id="grandtotal5">-</th>
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
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapushasilbagi" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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

        function load(){
            startTime();
            $('.hapus-button').hide();
            $('.back2Top').show();
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
        var no_hasilbagi = $('#nohasilbagi').val();
        var link = $('#Link1').val();
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:link+'/gui_front_02/admin/hasilbagidetail/getDatabyID?id='+no_hasilbagi,
            data:{'no_hasilbagi':no_hasilbagi},
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

                    grandTotal4 = api
                        .column( 12 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    grandTotal5 = api
                        .column( 13 )
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

                    $( api.column( 12 ).footer() ).html(
                        ''+formatRupiah(grandTotal4)
                    );

                    $( api.column( 13 ).footer() ).html(
                        ''+formatRupiah(grandTotal5)
                    );
                },

            columns: [
                { data: 'no_hasilbagi', name: 'no_hasilbagi' },
                { data: 'no_spb', name: 'no_spb' },
                { data: 'no_spb_manual', name: 'no_spb_manual' },
                { data: 'tanggal_spb', name: 'tanggal_spb' },
                { data: 'tanggal_kembali', name: 'tanggal_kembali' },
                { data: 'mobil.nopol', name: 'mobil.nopol' },
                { data: 'gudangdetail.nama_gudang', "defaultContent": "-" },
                { data: 'kode_container', name: 'kode_container' },
                { data: 'muatan', name: 'muatan' },
                { data: 'tarif', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'uang_jalan', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'bbm', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'sisa', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'sisa_ujbbm', 
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
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.hapus-button').hide();
                }
                else {
                    $('.hapus-button').show();
                    table.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                }
            } );

            $('#hapushasilbagi').click( function () {
                var select = $('.selected').closest('tr');
                var no_hasilbagi = select.find('td:eq(0)').text();
                var no_spb = select.find('td:eq(1)').text();
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
                            url: '{!! route('hasilbagidetail.hapus_hasilbagi') !!}',
                            type: 'POST',
                            data : {
                                'no_hasilbagi': no_hasilbagi,
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
        } );

        function getspb()
        {
            var no_hasilbagi = $('#nohasilbagi').val();
            var kode_sopir = $('#kode_sopir').val();
            var dari = $('#dari').val();
            var sampai = $('#sampai').val();
            
                swal({
                    title: "Anda yakin data sudah benar?",
                    text: "Pastikan dahulu nama sopir yang dipilih",
                    type: "question",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, benar!",
                    cancelButtonText: "Batal!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{!! route('hasilbagidetail.getspb') !!}',
                            type: 'POST',
                            data : {
                                'no_hasilbagi': no_hasilbagi,
                                'kode_sopir': kode_sopir,
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

        function formatNumber(n) {
            if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
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

        $('.select2').select2({
            placeholder: "Pilih",
            allowClear: true,
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function refreshTable() {
             $('#data2-table').DataTable().ajax.reload(null,false);;
        }

        function hapus() {
            var no_hasilbagi= $('#nohasilbagi').val();

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
                    url:'{!! route('hasilbagidetail.hapusall') !!}',
                    type:'POST',
                    data : {
                            'id': no_hasilbagi
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
@endpush