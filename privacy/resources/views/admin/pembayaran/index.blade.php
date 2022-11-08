@extends('adminlte::page')

@section('title', 'Trucking Vendor')

@section('content_header')
    
@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@include('sweet::alert')
<body onLoad="load()">
    <div class="box box-solid">
        <div class="box-body">
            <div class="box ">
                <div class="box-body">
                    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()" >
                            <i class="fa fa-refresh"></i> Refresh</button>

                    @permission('create-pembayaran')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Transaksi</button>
                    @endpermission

                    <!--<button type="button" class="btn btn-primary btn-xs" onclick="getkode()">-->
                    <!--    <i class="fa fa-bullhorn"></i> Get New Kode</button>-->

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>TRUCKING VENDOR</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Tgl SPB Kembali (dari)</th>
                        <th>Tgl SPB Kembali (sampai)</th>
                        <th>Pemilik Mobil</th>
                        <th>Total SPB</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addform" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Create Data</h4>
            </div>
            @include('errors.validation')
            {!! Form::open(['id'=>'ADD']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_pembayaran', 'Tanggal Transaksi:') }}
                                    {{ Form::date('tanggal_pembayaran', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_pemilik', 'Pemilik Mobil:') }}
                                    {{ Form::select('kode_pemilik',$Pemilik->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Pemilik1','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggalkembali_dari', 'Tgl SPB Kembali (Dari):') }}
                                    {{ Form::date('tanggalkembali_dari', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Dari1' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggalkembali_sampai', 'Tgl SPB Kembali (Sampai):') }}
                                    {{ Form::date('tanggalkembali_sampai', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Sampai1' ,'required'=>'required'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            {{ Form::submit('Create data', ['class' => 'btn btn-success crud-submit']) }}
                            {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                        </div>
                    </div>
                {!! Form::close() !!}
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="editform" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Data</h4>
            </div>
            @include('errors.validation')
            {!! Form::open(['id'=>'EDIT']) !!}
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_pembayaran', 'No Transaksi:') }}
                                    {{ Form::text('no_pembayaran', null, ['class'=> 'form-control','id'=>'Pembayaran','readonly']) }}
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_pembayaran', 'Tanggal Transaksi:') }}
                                    {{ Form::date('tanggal_pembayaran', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_pemilik', 'Pemilik Mobil:') }}
                                    {{ Form::select('kode_pemilik',$Pemilik->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Pemilik','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggalkembali_dari', 'Tgl SPB Kembali (Dari):') }}
                                    {{ Form::date('tanggalkembali_dari', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Dari' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggalkembali_sampai', 'Tgl SPB Kembali (Sampai):') }}
                                    {{ Form::date('tanggalkembali_sampai', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Sampai' ,'required'=>'required'])}}
                                </div>
                            </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        {{ Form::submit('Update data', ['class' => 'btn btn-success']) }}
                        {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                    </div>
                </div>
            {!! Form::close() !!}
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
        
        <div class="box box-solid">
            <div class="modal fade" id="cetakpembayaran"  role="dialog">
                <div class="modal-dialog modal-md" role="document">
                  <div class="modal-content">
                    @include('errors.validation')
                    {!! Form::open(['route' => ['pembayaran.export2'],'method' => 'get','id'=>'form', 'target'=>"_blank"]) !!}
                            <div class="modal-body">
                                <div class="row">
                                    {{ Form::hidden('no_pembayaran',null, ['class'=> 'form-control','id'=>'no_pembayaran']) }}
                                    <div class="col-sm-9">  
                                        <input type="checkbox" name="ttd" value="1"/>&nbsp;Cetak TTD di halaman baru<br>
                                    </div>
                                    <div class="col-sm-3">  
                                        {{ Form::submit('Cetak', ['class' => 'btn btn-success crud-submit']) }}
                                        {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}            
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
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
            .add-button {
                background-color: #00E0FF;
                bottom: 156px;
            }

            .hapus-button {
                background-color: #F63F3F;
                bottom: 186px;
            }

            .edit-button {
                background-color: #FDA900;
                bottom: 216px;
            }

            .view-button {
                background-color: #1674c7;
                bottom: 246px;
            }

            .tombol1 {
                background-color: #149933;
                bottom: 276px;
            }

            .tombol2 {
                background-color: #ff9900;
                bottom: 306px;
            }

            .print-button {
                background-color: #F63F3F;
                bottom: 336px;
            }

            #mySidenav button {
              position: fixed;
              right: -30px;
              transition: 0.3s;
              padding: 4px 8px;
              width: 70px;
              text-decoration: none;
              font-size: 12px;
              color: white;
              border-radius: 5px 0 0 5px ;
              opacity: 0.8;
              cursor: pointer;
              text-align: left;
            }

            #mySidenav button:hover {
              right: 0;
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
        </style>

        <div id="mySidenav" class="sidenav">
            @permission('update-pembayaran')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editpembayaran" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-pembayaran')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuspembayaran" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('add-pembayaran')
            <a href="#" id="addpembayaran"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD <i class="fa fa-plus"></i></button></a>
            @endpermission

            @permission('post-pembayaran')
            <button type="button" class="btn btn-success btn-xs tombol1" id="button1">POST <i class="fa fa-bullhorn"></i></button>
            @endpermission

            @permission('unpost-pembayaran')
            <button type="button" class="btn btn-warning btn-xs tombol2" id="button2">UNPOST <i class="fa fa-undo"></i></button>
            @endpermission

            @permission('view-pembayaran')
            <button type="button" class="btn btn-primary btn-xs view-button" id="button5">VIEW <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('print-pembayaran')
            <button type="button" class="btn btn-danger btn-xs print-button" id="button6" data-toggle="modal" data-target="">PRINT <i class="fa fa-print"></i></button>
            @endpermission
        </div>
</body>
@stop

@push('css')

@endpush
@push('js')
  
    <script type="text/javascript">      

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
            $('.tombol1').hide();
            $('.tombol2').hide();
            $('.hapus-button').hide();
            $('.add-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.print-button').hide();
            $('.back2Top').show();
        }

        function getkode(){
            swal({
                title: "Get New Kode?",
                text: "New Kode",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Ya, Update!",
                cancelButtonText: "Batal",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    swal({
                        title: "<b>Proses Sedang Berlangsung</b>",
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                                
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url:'{!! route('pembayaran.getkode') !!}',
                        type:'POST',
                        success: function(result) {
                            swal("Berhasil!", result.message, "success");
                            refreshTable();
                        },
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }

    function hanyaAngka(e, decimal) {
        var key;
        var keychar;
         if (window.event) {
             key = window.event.keyCode;
         } else
         if (e) {
             key = e.which;
         } else return true;
      
        keychar = String.fromCharCode(key);
        if ((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) ) {
            return true;
        } else
        if ((("0123456789").indexOf(keychar) > -1 || ("-").indexOf(keychar) > -1 || (".").indexOf(keychar) > -1 )) {
            return true;
        } else
        if (decimal && (keychar == ".")) {
            return true;
        } else return false;
    }   


    function pulsar(e,obj) {            
              tecla = (document.all) ? e.keyCode : e.which;
              //alert(tecla);
              if (tecla!="8" && tecla!="0"){
                obj.value += String.fromCharCode(tecla).toUpperCase();
                return false;
              }else{
                return true;
              }
    }

        $(function() {          
            $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('pembayaran.data') !!}',
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status'] == "OPEN") {
                    $('td', row).css('background-color', '#ffdbd3');
                }
            },
            columns: [
                { data: 'no_pembayaran', name: 'no_pembayaran' },
                { data: 'tanggal_pembayaran', name: 'tanggal_pembayaran' },
                { data: 'tanggalkembali_dari', name: 'tanggalkembali_dari' },
                { data: 'tanggalkembali_sampai', name: 'tanggalkembali_sampai' },
                { data: 'nama_vendor', name: 'vendor.nama_vendor' },
                { data: 'total_item', name: 'total_item' },
                { data: 'status', 
                    render: function( data, type, full ) {
                    return formatStatus(data); }
                },
            ]
            });
        });

        function formatNumber(n) {
                if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function formatStatus(n) {
            console.log(n);
            if(n == 'OPEN'){
                return n;
            }else if(n == 'POSTED'){
                var stat = "<span style='color:#0eab25'><b>POSTED</b></span>";
                return n.replace(/POSTED/, stat);
            }else if(n == 'CLOSED'){
                var stat = "<span style='color:#c91a1a'><b>CLOSED</b></span>";
                return n.replace(/CLOSED/, stat);
            }else if(n == 'INVOICED'){
                var stat = "<span style='color:#2a59a3'><b>INVOICED</b></span>";
                return n.replace(/INVOICED/, stat);
            }else{
                var stat = "<span style='color:#1a80c9'><b>RECEIVED</b></span>";
                return n.replace(/RECEIVED/, stat);
            }
        }

        function createTable(result){

        var total_harga = 0;
        var total_harga2 = 0;
        var total_harga3 = 0;

        var grand_total = 0;
        var grand_total2 = 0;
        var grand_total3 = 0;

        $.each( result, function( key, row ) {
            total_harga += row.tarif;
            total_harga2 += row.uang_jalan;
            total_harga3 += row.sisa;

            grand_total = formatRupiah(total_harga);
            grand_total2 = formatRupiah(total_harga2);
            grand_total3 = formatRupiah(total_harga3);
        });

        var my_table = "";

        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.no_joborder+"</td>";
                    my_table += "<td>"+row.no_spb+"</td>";
                    my_table += "<td>"+row.tgl_spb+"</td>";
                    my_table += "<td>"+row.tgl_kembali+"</td>";
                    my_table += "<td>"+row.kode_mobil+"</td>";
                    my_table += "<td>"+row.kode_sopir+"</td>";
                    my_table += "<td>"+row.kode_container+"</td>";
                    my_table += "<td>"+row.kode_gudang+"</td>";
                    my_table += "<td>"+formatRupiah(row.tarif)+"</td>";
                    my_table += "<td>"+formatRupiah(row.uang_jalan)+"</td>";
                    my_table += "<td>"+formatRupiah(row.sisa)+"</td>";
                    my_table += "<td>"+row.dari+"</td>";
                    my_table += "<td>"+row.tujuan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No JO</th>'+
                                '<th>No SPB</th>'+
                                '<th>Tgl. SPB</th>'+
                                '<th>Tgl. Kembali SPB</th>'+
                                '<th>Mobil</th>'+
                                '<th>Sopir</th>'+
                                '<th>Container</th>'+
                                '<th>Gudang</th>'+
                                '<th>Tarif</th>'+
                                '<th>Uang Jalan</th>'+
                                '<th>Sisa</th>'+
                                '<th>Dari</th>'+
                                '<th>Tujuan</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '<tfoot>'+
                            '<tr class="bg-info">'+
                                '<th class="text-center" colspan="8">Total</th>'+
                                '<th>'+grand_total+'</th>'+
                                '<th>'+grand_total2+'</th>'+
                                '<th>'+grand_total3+'</th>'+
                                '<th colspan="2"></th>'+
                            '</tr>'+
                        '</tfoot>'+
                        '</table>';

                    // $(document).append(my_table);
            
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        $(document).ready(function(){
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('.print-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');

                    closeOpenedRows(table, select);
                    
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('.print-button').hide();
                    
                    var colom2 = select.find('td:eq(5)').text();
                    var colom = select.find('td:eq(6)').text();
                    var no_pembayaran = select.find('td:eq(0)').text();
                    var status = colom;
                    var item = colom2;
                    var add = $("#addpembayaran").attr("href","http://localhost/gui_front_02/admin/pembayaran/"+no_pembayaran+"/detail");
                    var print = $("#printpembayaran").attr("href","http://localhost/gui_front_02/admin/pembayaran/export2?no_pembayaran="+no_pembayaran);

                    if(status == 'POSTED' && item > 0){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.add-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.print-button').show();
                        $('.view-button').show();
                    }else if(status =='OPEN' && item > 0){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.print-button').show();
                        $('.view-button').show();
                    }else if(status =='OPEN' && item == 0){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.print-button').hide();
                        $('.view-button').hide();
                    }else if(status =='INVOICED'){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.print-button').show();
                        $('.view-button').show();
                    }
                }
            });

            $('#button1').click( function () {
                var select = $('.selected').closest('tr');
                var colom = select.find('td:eq(0)').text();
                var no_pembayaran = colom;
                console.log(no_pembayaran);
                swal({
                    title: "Post?",
                    text: colom,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Posting!",
                    cancelButtonText: "Batal",
                    reverseButtons: !0
                    }).then(function (e) {
                        
                        if (e.value === true) {
                            swal({
                            title: "<b>Proses Sedang Berlangsung</b>",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                            })
                            
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // alert( table.rows('.selected').data().length +' row(s) selected' );
                        $.ajax({
                            url: '{!! route('pembayaran.post') !!}',
                            type: 'POST',
                            data : {
                                'id': no_pembayaran
                            },
                            success: function(result) {
                                console.log(result);
                                if (result.success === true) {
                                    swal(
                                    'Posted!',
                                    'Your file has been posted.',
                                    'success'
                                    )
                                    refreshTable();
                                }
                                else{
                                  swal({
                                      title: 'Error',
                                      text: result.message,
                                      type: 'error',
                                  })
                                }
                            },
                            error : function () {
                              swal({
                                  title: 'Oops...',
                                  text: 'Gagal',
                                  type: 'error',
                                  timer: '1500'
                              })
                            }
                        });
                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                })
            });

            $('#button2').click( function () {
                var select = $('.selected').closest('tr');
                var colom = select.find('td:eq(0)').text();
                var no_pembayaran = colom;
                console.log(no_pembayaran);
                swal({
                    title: "Unpost?",
                    text: colom,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Unpost!",
                    cancelButtonText: "Batal",
                    reverseButtons: !0
                    }).then(function (e) {
                        if (e.value === true) {
                            swal({
                            title: "<b>Proses Sedang Berlangsung</b>",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                            })
                            
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: '{!! route('pembayaran.unpost') !!}',
                            type: 'POST',
                            data : {
                                'id': no_pembayaran
                            },
                            success: function(result) {
                                console.log(result);
                                if (result.success === true) {
                                    swal(
                                    'Unposted!',
                                    'Data berhasil di Unpost.',
                                    'success'
                                    )
                                    refreshTable();
                                }
                                else{
                                  swal({
                                      title: 'Error',
                                      text: result.message,
                                      type: 'error',
                                  })
                                }
                            },
                            error : function () {
                              swal({
                                  title: 'Oops...',
                                  text: data.message,
                                  type: 'error',
                                  timer: '1500'
                              })
                            }
                        });
                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                })
            });

            var openRows = new Array();

            function closeOpenedRows(table, selectedRow) {
                $.each(openRows, function (index, openRow) {
                    // not the selected row!
                    if ($.data(selectedRow) !== $.data(openRow)) {
                        var rowToCollapse = table.row(openRow);
                        rowToCollapse.child.hide();
                        openRow.removeClass('shown');
                        var index = $.inArray(selectedRow, openRows);                        
                        openRows.splice(index, 1);
                    }
                });
            }

            $('#button5').click( function () {
                var select = $('.selected').closest('tr');
                var no_pembayaran = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('pembayaran.showdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': no_pembayaran
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.title == 'Gagal'){
                            $.notify(result.message);
                        }else{
                            if ( row.child.isShown() ) {
                                row.child.hide();
                                select.removeClass('shown');
                            }
                            else {
                                closeOpenedRows(table, select);

                                row.child( createTable(result) ).show();
                                select.addClass('shown');

                                openRows.push(select);
                            }
                        }
                    }
                });
            });

            $('#editpembayaran').click( function () {
                var select = $('.selected').closest('tr');
                var no_pembayaran = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('pembayaran.edit_pembayaran') !!}',
                    type: 'POST',
                    data : {
                        'id': no_pembayaran
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Pembayaran').val(results.no_pembayaran);
                        $('#Tanggal').val(results.tanggal_pembayaran);
                        $('#Dari').val(results.tanggalkembali_dari);
                        $('#Sampai').val(results.tanggalkembali_sampai);
                        $('#Pemilik').val(results.kode_pemilik).trigger('change');
                        $('#editform').modal('show');
                    }
         
                });
            });

            $('#hapuspembayaran').click( function () {
                var select = $('.selected').closest('tr');
                var no_pembayaran = select.find('td:eq(0)').text();
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
                            url: '{!! route('pembayaran.hapus_pembayaran') !!}',
                            type: 'POST',
                            data : {
                                'id': no_pembayaran
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
            
            $('#button6').click( function () {
                var select = $('.selected').closest('tr');
                var no_pembayaran = select.find('td:eq(0)').text();
                $('#no_pembayaran').val(no_pembayaran);
                $('#cetakpembayaran').modal('show');
            });
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
            $('#data-table').DataTable().ajax.reload(null,false);
            $('.tombol1').hide();
            $('.tombol2').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.print-button').hide();
            $('.add-button').hide();
        }
     
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });

        $('.modal-dialog').resizable({
    
        });

        $('#ADD').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD");
            var formData = registerForm.serialize();

                $.ajax({
                    url:'{!! route('pembayaran.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Tanggal1').val('');
                        $('#Dari1').val('');
                        $('#Sampai1').val('');
                        $('#Pemilik1').val('').trigger('change');
                        $('#addform').modal('hide');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                        } else {
                            swal("Gagal!", data.message, "error");
                        }   
                    },
                });
                });
            

        $('#EDIT').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#EDIT");
            var formData = registerForm.serialize();
         
                $.ajax({
                    url:'{!! route('pembayaran.updateajax') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#editform').modal('hide');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                        } else {
                            swal("Gagal!", data.message, "error");
                        }   
                    },
                });
            
        });
    </script>
@endpush