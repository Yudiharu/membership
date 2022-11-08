@extends('adminlte::page')

@section('title', 'Trucking')

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

                    @permission('create-truckingnon')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Trucking</button>
                    @endpermission

                    <!--<button type="button" class="btn btn-primary btn-xs" onclick="getkode()">-->
                    <!--    <i class="fa fa-bullhorn"></i> Get New Kode Customer</button>-->

                    <span class="pull-right">  
                        <b>Keterangan Warna:&nbsp;&nbsp;&nbsp;&nbsp;</b>
                        <font style="background-color:#ffdbd3;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Ada SPBNC yg belum kembali.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <font style="font-size: 16px;"><b>TRUCKING</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Trucking</th>
                        <th>No Job Order</th>
                        <th>Tanggal Trucking</th>
                        <th>Nama Customer</th>
                        <th>Jumlah SPB</th>
                        <th>GT Tarif</th>
                        <th>GT Uang Jalan</th>
                        <th>GT BBM</th>
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
                                    {{ Form::label('no_joborder', 'No Job Order:') }}
                                    {{ Form::select('no_joborder',$Joborder->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Job1','required'=>'required','onchange'=>"getdata();"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_truckingnon', 'Tanggal Trucking:') }}
                                    {{ Form::date('tanggal_truckingnon', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_customer', 'Nama Customer:') }}
                                    {{ Form::text('kode_customer',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Customer1','required'=>'required','readonly']) }}
                                </div>
                            </div> -->
                            
                            {{ Form::hidden('kode_customer',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Customer1','required'=>'required','readonly']) }}

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
                                    {{ Form::label('no_truckingnon', 'No Trucking:') }}
                                    {{ Form::text('no_truckingnon', null, ['class'=> 'form-control','id'=>'Trucking','readonly']) }}
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_joborder', 'No Job Order:') }}
                                    {{ Form::select('no_joborder',$Joborder->sort(),null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Job','required'=>'required','onchange'=>"getdata2();"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_truckingnon', 'Tanggal Trucking:') }}
                                    {{ Form::date('tanggal_truckingnon', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_customer', 'Nama Customer:') }}
                                    {{ Form::text('kode_customer',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Customer','required'=>'required','readonly']) }}
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

      <div class="modal fade" id="addpayment" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" style=" height: 1%; border: none">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Print SPB per part (per 80 SPB)</h4>
                </div>
                <div class="box-body">
                    <div class="addform">
                    @include('errors.validation')
                    {!! Form::open(['id'=>'BIG_PRINT']) !!}
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('Noo', 'No Trucking:') }}
                                {{ Form::text('no_truckingnon',null,['class'=> 'form-control','style'=>'width: 100%','id'=>'NoTruck1', 'readonly']) }}
                            </div>
                        </div>
    
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('parrt', 'Pilih Part:') }}
                                {{ Form::select('part', [],null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Parto','required']) }}
                            </div>
                        </div>
    
                        <div class="col-md-12">
                            <span class="pull-right">
                                <!-- {{ Form::submit('Add Item', ['class' => 'btn btn-primary btn-xs','id'=>'submit']) }}   -->
                                <a href="#" target="_blank" id="printpay">
                                <button type="button" class="btn btn-danger btn-sm printpaybutton" id="printbutton">PRINT <i class="fa fa-print"></i></button></a>
                            </span>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

            .add-button2 {
                background-color: #ff9900;
                bottom: 126px;
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

            .view2-button {
                background-color: #F63F3F;
                bottom: 276px;
            }

            .tombol1 {
                background-color: #149933;
                bottom: 306px;
            }

            .tombol2 {
                background-color: #ff9900;
                bottom: 336px;
            }

            .print-button {
                background-color: #F63F3F;
                bottom: 366px;
            }

            .print2-button {
                bottom: 426px;
            }

            .tombol3 {
                background-color: #149933;
                bottom: 396px;
            }

            #mySidenav button {
              position: fixed;
              right: -60px;
              transition: 0.3s;
              padding: 4px 8px;
              width: 120px;
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
            @permission('update-truckingnon')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="edittruckingnon" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-truckingnon')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapustruckingnon" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('add-truckingnon')
            <a href="#" id="addtruckingnon"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD SPB <i class="fa fa-plus"></i></button></a>
            @endpermission

            @permission('post-truckingnon')
            <button type="button" class="btn btn-success btn-xs tombol1" id="button1">POST <i class="fa fa-bullhorn"></i></button>
            @endpermission

            @permission('unpost-truckingnon')
            <button type="button" class="btn btn-warning btn-xs tombol2" id="button2">UNPOST <i class="fa fa-undo"></i></button>
            @endpermission

            @permission('view-truckingnon')
            <button type="button" class="btn btn-primary btn-xs view-button" id="button5">VIEW <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('view-truckingnon')
            <button type="button" class="btn btn-danger btn-xs view2-button" id="button6">VIEW SPBNC <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('print-truckingnon')
            <button type="button" class="btn bg-black btn-xs print2-button" id="addpayment_p" data-toggle="modal" data-target="#addpayment">PRINT SPB PER PART<i class="fa fa-print"></i></button>
            <a href="#" target="_blank" id="printtruckingnon"><button type="button" class="btn btn-danger btn-xs print-button" id="button7">PRINT <i class="fa fa-print"></i></button></a>
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
            $('.tombol3').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.add-button').hide();
            $('.add-button2').hide();
            $('.view-button').hide();
            $('.print-button').hide();
            $('.print2-button').hide();
            $('.view2-button').hide();
            $('.back2Top').show();
        }

        function getkode(){
            swal({
                title: "Get New Kode Customer?",
                text: "Customer",
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
                        url:'{!! route('truckingnon.getkode') !!}',
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

        $(function() {          
            $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('truckingnon.data') !!}',
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status_kembali'] == "FALSE") {
                    $('td', row).css('background-color', '#ffdbd3');
                }
            },
            columns: [
                { data: 'no_truckingnon', name: 'no_truckingnon' },
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'tanggal_truckingnon', name: 'tanggal_truckingnon' },
                { data: 'customer.nama_customer', name: 'customer.nama_customer' },
                { data: 'total_item', name: 'total_item' },
                { data: 'gt_tarif', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'gt_uang_jalan', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'gt_bbm', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
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
            }else{
                var stat = "<span style='color:#1a80c9'><b>RECEIVED</b></span>";
                return n.replace(/RECEIVED/, stat);
            }
        }

        function getdata(){
            var no_joborder= $('#Job1').val();

            var submit = document.getElementById("submit");
            $.ajax({
                url:'{!! route('truckingnon.getdata') !!}',
                type:'POST',
                data : {
                        'id': no_joborder,
                    },
                success: function(result) {
                        $('#Customer1').val(result.kode_customer);
                    },
            });
        }

        function getdata2(){
            var no_joborder= $('#Job').val();

            var submit = document.getElementById("submit");
            $.ajax({
                url:'{!! route('truckingnon.getdata2') !!}',
                type:'POST',
                data : {
                        'id': no_joborder,
                    },
                success: function(result) {
                        $('#Customer').val(result.kode_customer);
                    },
            });
        }

        function createTable(result){

        var total_harga = 0;
        var grand_total = 0;
        var total_harga2 = 0;
        var grand_total2 = 0;
        var total_harga3 = 0;
        var grand_total3 = 0;

        $.each( result, function( key, row ) {
            total_harga += row.tarif_gajisopir;
            total_harga2 += row.uang_jalan;
            total_harga3 += row.bbm;
            grand_total = formatRupiah(total_harga);
            grand_total2 = formatRupiah(total_harga2);
            grand_total3 = formatRupiah(total_harga3);
        });

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.no_spb+"</td>";
                    my_table += "<td>"+row.no_spb_manual+"</td>";
                    my_table += "<td>"+row.tanggal_spb+"</td>";
                    my_table += "<td>"+row.tanggal_kembali+"</td>";
                    my_table += "<td>"+formatRupiah(row.total_berat)+"</td>";
                    my_table += "<td>"+row.kode_pemilik+"</td>";
                    my_table += "<td>"+row.kode_mobil+"</td>";
                    my_table += "<td>"+row.kode_sopir+"</td>";
                    my_table += "<td>"+formatRupiah(row.tarif_gajisopir)+"</td>";
                    my_table += "<td>"+formatRupiah(row.uang_jalan)+"</td>";
                    my_table += "<td>"+formatRupiah(row.bbm)+"</td>";
                    my_table += "<td>"+row.dari+"</td>";
                    my_table += "<td>"+row.tujuan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table class="table table-bordered" style="font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No SPB</th>'+
                                '<th>No SPB Manual</th>'+
                                '<th>Tanggal SPB</th>'+
                                '<th>Tanggal Kembali SPB</th>'+
                                '<th>Berat</th>'+
                                '<th>Pemilik</th>'+
                                '<th>Mobil</th>'+
                                '<th>Sopir</th>'+
                                '<th>Tarif Sopir</th>'+
                                '<th>Uang Jalan</th>'+
                                '<th>BBM</th>'+
                                '<th>Dari</th>'+
                                '<th>Tujuan</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '<tfoot>'+
                            '<tr class="bg-info">'+
                                '<th class="text-center" colspan="8">Total</th>'+
                                '<th>Rp '+grand_total+'</th>'+
                                '<th>Rp '+grand_total2+'</th>'+
                                '<th>Rp '+grand_total3+'</th>'+
                                '<th colspan="2"></th>'+
                            '</tr>'+
                        '</tfoot>'+
                        '</table>';

                    // $(document).append(my_table);
            
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        function createTable2(result){

        var my_table = "";

        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.no_spb+"</td>";
                    my_table += "<td>"+row.no_spb_manual+"</td>";
                    my_table += "<td>"+row.tanggal_spb+"</td>";
                    my_table += "<td>"+formatRupiah(row.total_berat)+"</td>";
                    my_table += "<td>"+row.dari+"</td>";
                    my_table += "<td>"+row.tujuan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table class="table table-bordered" style="font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No SPB</th>'+
                                '<th>No SPB Manual</th>'+
                                '<th>Tanggal SPB</th>'+
                                '<th>Berat</th>'+
                                '<th>Dari</th>'+
                                '<th>Tujuan</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
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
                    $('.tombol3').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.add-button').hide();
                    $('.add-button2').hide();
                    $('.view-button').hide();
                    $('.view2-button').hide();
                    $('.print-button').hide();
                    $('.print2-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');

                    closeOpenedRows(table, select);

                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.tombol3').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.add-button').hide();
                    $('.add-button2').hide();
                    $('.view-button').hide();
                    $('.print-button').hide();
                    $('.print2-button').hide();
                    $('.view2-button').hide();
                    
                    var colom = select.find('td:eq(8)').text();
                    var colom2 = select.find('td:eq(4)').text();
                    var no_truckingnon = select.find('td:eq(0)').text();
                    var status = colom;
                    var item = colom2;
                    var add = $("#addtruckingnon").attr("href",window.location.href+"/"+no_truckingnon+"/detail");
                    var print = $("#printtruckingnon").attr("href",window.location.href+"/export2?no_truckingnon="+no_truckingnon);
                    if(status == 'POSTED' && item > 0){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.tombol3').hide();
                        $('.add-button').hide();
                        $('.add-button2').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        if (item <= 80) {
                            $('.print-button').show();
                            $('.print2-button').hide();
                        }else {
                            $('.print-button').hide();
                            $('.print2-button').show();
                        }
                        $('.view-button').show();
                        $('.view2-button').show();
                    }else if(status =='OPEN' && item > 0){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.tombol3').hide();
                        $('.add-button').show();
                        $('.add-button2').show();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        if (item <= 80) {
                            $('.print-button').show();
                            $('.print2-button').hide();
                        }else {
                            $('.print-button').hide();
                            $('.print2-button').show();
                        }
                        $('.view-button').hide();
                        $('.view2-button').show();
                    }else if(status =='OPEN' && item == 0){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.tombol3').hide();
                        $('.add-button').show();
                        $('.add-button2').hide();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.print-button').hide();
                        $('.print2-button').hide();
                        $('.view2-button').hide();
                    }else if(status =='CLOSED'){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.tombol3').hide();
                        $('.add-button').hide();
                        $('.add-button2').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        if (item <= 80) {
                            $('.print-button').show();
                            $('.print2-button').hide();
                        }else {
                            $('.print-button').hide();
                            $('.print2-button').show();
                        }
                        $('.view-button').show();
                        $('.view2-button').hide();
                    }
                }
            });

            $('#printbutton').click(function(){
                var no_truckingnon = $('#NoTruck1').val();
                var parts = $('#Parto').val();
                $("#printpay").attr("href",window.location.href+"/export3?no_truckingnon="+no_truckingnon+"&part="+parts);
            });

            $('#addpayment_p').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                $.ajax({
                    url: '{!! route('truckingnon.bigprint') !!}',
                    type: 'POST',
                    data : {
                        'id': no_truckingnon
                    },
                    success: function(data) {
                        $('#addpayment').modal('show');
                        $('#NoTruck1').val(no_truckingnon);
                        $('#Parto').empty();
                        console.log(data.options);
                        $.each(data.options, function(key, value){
                            $('#Parto').val('');
                            $("#Parto").append('<option value="'+ key +'">' + value + '</option>');
                            $('#Parto').val('');
                        });
                    }
                });
            });

            $('#button1').click( function () {
                var select = $('.selected').closest('tr');
                var colom = select.find('td:eq(0)').text();
                var no_truckingnon = colom;
                console.log(no_truckingnon);
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
                            url: '{!! route('truckingnon.post') !!}',
                            type: 'POST',
                            data : {
                                'id': no_truckingnon
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
                var no_truckingnon = colom;
                console.log(no_truckingnon);
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
                            url: '{!! route('truckingnon.unpost') !!}',
                            type: 'POST',
                            data : {
                                'id': no_truckingnon
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
                var no_truckingnon = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingnon.showdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': no_truckingnon
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

            $('#button6').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingnon.showdetailspbnc') !!}',
                    type: 'POST',
                    data : {
                        'id': no_truckingnon
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

                                row.child( createTable2(result) ).show();
                                select.addClass('shown');

                                openRows.push(select);
                            }
                        }
                    }
                });
            });

            $('#edittruckingnon').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingnon.edit_truckingnon') !!}',
                    type: 'POST',
                    data : {
                        'id': no_truckingnon
                    },
                    success: function(results) {
                        $('#Trucking').val(results.no_truckingnon);
                        $('#Job').val(results.no_joborder);
                        $('#Tanggal').val(results.tanggal_truckingnon);
                        $('#Customer').val(results.kode_customer);
                        $('#editform').modal('show');
                        }
         
                });
            });

            $('#hapustruckingnon').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
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
                            url: '{!! route('truckingnon.hapus_truckingnon') !!}',
                            type: 'POST',
                            data : {
                                'id': no_truckingnon
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
            $('.tombol3').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.view2-button').hide();
            $('.print-button').hide();
            $('.print2-button').hide();
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
                    url:'{!! route('truckingnon.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Job1').val('').trigger('change');
                        $('#Tanggal1').val('');
                        $('#Customer1').val('').trigger('change');
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
                    url:'{!! route('truckingnon.updateajax') !!}',
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