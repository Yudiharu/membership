@extends('adminlte::page')

@section('title', 'Trucking Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Trucking <b>{{$truckingnon->no_truckingnon}}</b></font>
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
                                    <div class="form-group">
                                        {{ Form::label('no_truckingnon', 'No Trucking Non:') }}
                                        {{ Form::text('no_truckingnon',$truckingnon->no_truckingnon, ['class'=> 'form-control','readonly','id'=>'notruckingnon']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-xs" title="Create SPB" onclick="createspbnon()" id='submit3'>Create SPB</button>
                                        {{ Form::label('no_spb', 'No SPB:') }}
                                        {{ Form::text('no_spb',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'spb','required','readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group1">
                                        {{ Form::label('tanggal_spb', 'Tanggal SPB:') }}
                                        {{ Form::date('tanggal_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'tanggal' ,'required'=>'required','onchange'=>"tgl();"])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group2">
                                        {{ Form::label('total_berat', 'Total Berat:') }}
                                        {{ Form::text('total_berat',0, ['class'=> 'form-control','id'=>'berat','readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <b>Keterangan Warna:</b><br>
                                        <font style="background-color:#ffdbd3;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Belum input detail item.<br>
                                        <font style="background-color:#DCFFBF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Belum input tanggal kembali.
                                    </div>
                                </div>
                            </div> 
                                <span class="pull-right"> 
                                        {{ Form::submit('Add Item', ['class' => 'btn btn-success btn-sm','id'=>'submit']) }}  
                                </span>                       
                    {!! Form::close() !!}    
            </div>
        
            <div class="editform">
                @include('errors.validation')
                {!! Form::open(['id'=>'UPDATE_DETAIL']) !!}
                    <center><kbd>EDIT FORM</kbd></center><br>
                            <div class="row">   
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::hidden('id',null, ['class'=> 'form-control','readonly','id'=>'ID']) }}
                                        {{ Form::label('no_truckingnon', 'No Trucking Non:') }}
                                        {{ Form::text('no_truckingnon',$truckingnon->no_truckingnon, ['class'=> 'form-control','readonly','id'=>'notruckingnon2']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('no_spb', 'No SPB:') }}
                                        {{ Form::text('no_spb',null, ['class'=> 'form-control','id'=>'spb2','readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('tanggal_spb', 'Tanggal SPB:') }}
                                        {{ Form::date('tanggal_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'tanggal2' ,'required'=>'required','onchange'=>"tgl2();"])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('total_berat', 'Total Berat:') }}
                                        {{ Form::text('total_berat',null, ['class'=> 'form-control','id'=>'berat2','readonly']) }}
                                    </div>
                                </div>
                            </div> 
                            <div class="row-md-2">
                                <span class="pull-right"> 
                                        {{ Form::submit('Update', ['class' => 'btn btn-success btn-sm','id'=>'submit2']) }}
                                        <button type="button" class="btn btn-danger btn-sm" onclick="cancel_edit()">Cancel</button>&nbsp;
                                </span>
                            </div>
                {!! Form::close() !!}  
        </div>
    </div>
</div>

            <div class="modal fade" id="editform2" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Edit Data</h4>
                    </div>
                    @include('errors.validation')
                    {!! Form::open(['id'=>'EDIT_SPB']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_truckingnon', 'No Trucking Non:') }}
                                    {{ Form::text('no_truckingnon',$truckingnon->no_truckingnon, ['class'=> 'form-control','readonly','id'=>'notruckingnon3']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_spb', 'No SPB:') }}
                                    {{ Form::text('no_spb',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'spb3','required','readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_spb_manual', 'No SPB Manual:') }}
                                    {{ Form::text('no_spb_manual',null, ['class'=> 'form-control','id'=>'spbmanual3','autocomplete'=>'off','autocomplete'=>'off','required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_spb', 'Tanggal SPB:') }}
                                    {{ Form::date('tanggal_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'tanggal3' ,'required'=>'required','onchange'=>"tgl();",'readonly'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tanggal_kembali', 'Tanggal Kembali SPB:') }}
                                    {{ Form::date('tanggal_kembali', null,['class'=> 'form-control','id'=>'kembali3' ,'required'=>'required','onchange'=>"tgl();"])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('total_berat', 'Total Berat:') }}
                                    {{ Form::text('total_berat',0, ['class'=> 'form-control','id'=>'berat3','readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <br>
                                    {{ Form::label('kode_mobil', 'Mobil:') }}
                                    {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'kode_mobil3','required'=>'required','onchange'=>"getaset();"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group3">
                                    <br>
                                    {{ Form::label('no_asset_mobil', 'No Asset:') }}
                                    {{ Form::select('no_asset_mobil',$Asmobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'asset_mobil3']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group4">
                                    <br>
                                    {{ Form::label('kode_sopir', 'Sopir:') }}
                                    {{ Form::select('kode_sopir',$Sopir->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'kode_sopir3','required']) }}
                                </div>
                                <div class="form-group5">
                                    <br>
                                    {{ Form::label('sopir', 'Sopir:') }}
                                    {{ Form::text('sopir',null, ['class'=> 'form-control','id'=>'Namasopir3','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group6">
                                    <br>
                                    {{ Form::label('nama_pemilik', 'Pemilik:') }}
                                    {{ Form::text('nama_pemilik', null, ['class'=> 'form-control','id'=>'Pemilik3','readonly']) }}
                                    {{ Form::hidden('kode_pemilik', null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'Kodepemilik3']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group7">
                                    <br>
                                    {{ Form::label('tarif_gajisopir', 'Tarif Gaji Sopir:') }}
                                    {{ Form::text('tarif_gajisopir',null, ['class'=> 'form-control','id'=>'gaji3','onkeyup'=>"cektarif2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Tarif gaji tidak boleh lebih kecil dari uang jalan dan BBM",'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group8">
                                    <br>
                                    {{ Form::label('uang_jalan', 'Uang Jalan:') }}
                                    {{ Form::text('uang_jalan',null, ['class'=> 'form-control','id'=>'uang3','onkeyup'=>"cekuang2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Uang Jalan tidak boleh lebih besar dari tarif gaji dan tidak lebih kecil dari BBM",'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group9">
                                    <br>
                                    {{ Form::label('bbm', 'BBM:') }}
                                    {{ Form::text('bbm',null, ['class'=> 'form-control','id'=>'bbm3','onkeyup'=>"cekbbm2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"BBM tidak boleh lebih besar dari tarif gaji dan uang jalan",'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group10">
                                    <br>
                                    {{ Form::label('dari', 'Dari:') }}
                                    {{ Form::text('dari',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'dari3','required'=>'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group11">
                                    <br>
                                    {{ Form::label('tujuan', 'Tujuan:') }}
                                    {{ Form::text('tujuan',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'tujuan3','required'=>'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            {{ Form::submit('Update data', ['class' => 'btn btn-success','id'=>'submit4']) }}
                            {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                        </div>
                    </div>
                    {!! Form::close() !!}
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

<div class="modal fade" id="addspbform" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="box-body"> 
            <div class="addform">
                @include('errors.validation')
                {!! Form::open(['id'=>'ADD_SPB']) !!}
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('no_truckingnon', 'No Trck.:') }}
                        {{ Form::text('no_truckingnon',$no_truckingnon, ['class'=> 'form-control','style'=>'width: 100%','id'=>'notruckingnon','required','readonly']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('no_spbnon', 'No SPB:') }}
                        {{ Form::text('no_spbnon',null, ['class'=> 'form-control','readonly','id'=>'spbnon','readonly']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('kode_item', 'Item:') }}
                        {{ Form::text('kode_item',null, ['class'=> 'form-control','required','id'=>'item','autocomplete'=>'off']) }}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        {{ Form::label('qty', 'QTY:') }}
                        {{ Form::text('qty',null, ['class'=> 'form-control','required','id'=>'qty','onkeyup'=>'gettotal()','autocomplete'=>'off']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('berat_satuan', 'Berat Satuan:') }}
                        {{ Form::text('berat_satuan',null, ['class'=> 'form-control','required','id'=>'berats','onkeyup'=>'gettotal()','autocomplete'=>'off']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::text('total_berat',null, ['class'=> 'form-control','required','id'=>'total', 'readonly','placeholder'=>'Total Berat']) }}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        {{ Form::textarea('keterangan', null, ['class'=> 'form-control','rows'=>'1','id'=>'keterangan', 'placeholder'=>'Keterangan', 'autocomplete'=>'off']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <span class="pull-right"> 
                        {{ Form::submit('Add Item', ['class' => 'btn btn-success btn-xs','id'=>'submit']) }}  
                        <button type="button" class="btn btn-warning btn-xs editbutton" id="editspb" data-toggle="modal" data-target="">
                            <i class="fa fa-edit"></i> EDIT
                        </button>
                        <button type="button" class="btn btn-danger btn-xs hapusbutton" id="hapusspb">
                            <i class="fa fa-times-circle"></i> HAPUS
                        </button>
                    </span>    
                </div>                    
            {!! Form::close() !!}
            </div>
        </div>

        <div class="container-fluid">
            <table class="table table-bordered table-striped table-hover" id="addspb-table" width="100%" style="font-size: 12px;">
                <thead>
                    <tr class="bg-warning">
                        <th>No SPB</th>
                        <th>No JO</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Berat Satuan</th>
                        <th>Total Berat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
            
        <div class="modal-footer">
                
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


   <div class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Trucking</th>
                        <th>No SPB</th>
                        <th>No SPB Manual</th>
                        <th>Tanggal SPB</th>
                        <th>Tanggal Kembali SPB</th>
                        <th>Total Item</th>
                        <th>Mobil</th>
                        <th>Sopir</th>
                        <th>Pemilik</th>
                        <th>Gaji Sopir</th>
                        <th>Uang Jalan</th>
                        <th>BBM</th>
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
            .add-button {
                background-color: #00E0FF;
                bottom: 96px;
            }

            .hapus-button {
                background-color: #F63F3F;
                bottom: 126px;
            }

            .edit-button {
                background-color: #FDA900;
                bottom: 156px;
            }

            .view-button {
                background-color: #149933;
                bottom: 186px;
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
            @permission('add-truckingnon')
            <button type="button" class="btn btn-info btn-xs add-button" id="addspbbutton" data-toggle="modal" data-target="#addspbform"><i class="fa fa-plus"></i> DETAIL SPB</button>
            @endpermission

            @permission('update-truckingnon')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="edittruckingnon" data-toggle="modal" data-target="">EDIT TGL SPB <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-truckingnondetail')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapustruckingnon" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('add-truckingnon')
            <a href="#" id="editspbkembali"><button type="button" class="btn btn-success btn-xs view-button" data-toggle="modal" data-target="">EDIT SPB KEMBALI <i class="fa fa-edit"></i></button></a>
            @endpermission
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
            $('.editform').hide();
            $('.back2Top').show();
            $('.form-group1').hide();
            $('.form-group2').hide();
            $('.form-group6').hide();
            submit.disabled = true;
            $('.add-button').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.editbutton').hide();
            $('.hapusbutton').hide();
        }

        function getaset(){
            var mobil = $('#kode_mobil3').val();
            $.ajax({
                url: '{!! route('truckingnondetail.get_aset') !!}',
                type: 'POST',
                data : {
                    'kode_mobil': mobil,
                },
                success: function(results) {
                    $('#asset_mobil3').val(results.no_aset).trigger('change');
                }
            });
        }

        function pemilik(){
            var no_spb = $('#spb3').val();
            var mobil = $('#kode_mobil3').val();
            $.ajax({
                    url: '{!! route('truckingnondetail.pemilik') !!}',
                    type: 'POST',
                    data : {
                        'kode_mobil': mobil,
                        'no_spb': no_spb,
                    },
                    success: function(results) {
                        $('#Pemilik3').val(results.pemilik);
                        $('#Namasopir3').val(results.kode_sopir);
                        if(results.pemilik != 'GEMILANG UTAMA INTERNASIONAL, PT'){
                            // $('#asset_mobil3').val('').trigger('change');
                            // $('.form-group3').hide();
                            // $('.form-group4').hide();
                            // $('.form-group5').show();
                            // document.getElementById("Namasopir3").required = true;
                            // document.getElementById("kode_sopir3").required = false;
                        }else{
                            $('.form-group3').show();
                            $('.form-group4').show();
                            $('.form-group5').hide();
                            $('#asset_mobil3').val(results.no_asset_mobil).trigger('change');
                            document.getElementById("Namasopir3").required = false;
                            document.getElementById("kode_sopir3").required = true;
                        }
                        $('#Kodepemilik3').val(results.kode_pemilik);
                        console.log($('#Kodepemilik3').val());
                    }
                });
        }

        function tgl(){
            var tgl = $('#tanggal3').val();
            var balik = $('#kembali3').val();
            if ( balik < tgl ){
                submit4.disabled = true;
            }else {
                submit4.disabled = false;
            }
        }

        function tgl2(){
            var tgl = $('#tanggal2').val();
            var balik = $('#kembali2').val();
            if ( balik < tgl ){
                submit2.disabled = true;
            }else {
                submit2.disabled = false;
            }
        }
        
        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        function createTable2(result){

        var total_berat = 0;

        $.each( result, function( key, row ) {
            total_berat += row.total_berat;
        });

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.kode_item+"</td>";
                    my_table += "<td>"+row.qty+"</td>";
                    my_table += "<td>"+formatRupiah(row.berat_satuan)+"</td>";
                    my_table += "<td>"+formatRupiah(row.total_berat)+"</td>";
                    my_table += "<td>"+row.keterangan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered table-hover" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Item</th>'+
                                '<th>Qty</th>'+
                                '<th>Berat Satuan</th>'+
                                '<th>Total Berat</th>'+
                                '<th>Keterangan</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '<tfoot>'+
                            '<tr class="bg-info">'+
                                '<th class="text-center" colspan="3">Total</th>'+
                                '<th>'+formatRupiah(total_berat)+'</th>'+
                                '<th></th>'+
                            '</tr>'+
                        '</tfoot>'+
                        '</table>';

                    // $(document).append(my_table);
            
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        function createspbnon()
        {
            var no_truckingnon = $('#notruckingnon').val();
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
            })
            $.ajax({
                url:'{!! route('truckingnondetail.createspbnon') !!}',
                type:'POST',
                data : {
                        'no_truckingnon': no_truckingnon,
                    },
                success: function(result) {
                        console.log(result);
                        if (result.success === false){
                            swal("Gagal!", result.message, "error");
                        }else{
                            swal(
                                'Berhasil!',
                                'No SPB Berhasil dibentuk',
                                'success'
                            )
                            $('.form-group1').show();
                            $('.form-group2').show();
                            $('#spb').val(result.hasil);
                            submit.disabled = false;
                        }
                    },
            });
        }
        
    $(function(){
        var no_truckingnon = $('#notruckingnon').val();
        var link = $('#Link1').val();
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:link+'/gui_front_02/admin/truckingnondetail/getDatabyID?id='+no_truckingnon,
            data:{'no_truckingnon':no_truckingnon},
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['total_item'] == 0) {
                    $('td', row).css('background-color', '#ffdbd3');
                }else if (data['tanggal_kembali'] == null) {
                    $('td', row).css('background-color', '#DCFFBF');
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
                { data: 'no_truckingnon', name: 'no_truckingnon' },
                { data: 'no_spb', name: 'no_spb' },
                { data: 'no_spb_manual', name: 'no_spb_manual' },
                { data: 'tanggal_spb', name: 'tanggal_spb' },
                { data: 'tanggal_kembali', "defaultContent": "<i>Not set</i>" },
                { data: 'total_item', "defaultContent": "<i>Not set</i>" },
                { data: 'mobil.nopol', "defaultContent": "<i>Not set</i>" },
                { data: 'sopir.nama_sopir',
                    render: function( data, type, full ) {
                    return formatSopir(data, full); }
                },
                { data: 'nama_vendor', name: 'vendor.nama_vendor', "defaultContent": "<i>Not set</i>" },
                { data: 'tarif_gajisopir', 
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
                { data: 'dari', "defaultContent": "<i>Not set</i>" },
                { data: 'tujuan', "defaultContent": "<i>Not set</i>" },
            ]
            
        });
        
    });

    Table2 = $("#addspb-table").DataTable({
        data:[],
        columns: [
            { data: 'no_spbnon', name: 'no_spbnon' },
            { data: 'no_joborder', name: 'no_joborder' },
            { data: 'kode_item', name: 'kode_item' },
            { data: 'qty', name: 'qty' },
            { data: 'berat_satuan', name: 'berat_satuan' },
            { data: 'total_berat', name: 'total_berat' },      
            { data: 'keterangan', name: 'keterangan' },               
        ],      
    });

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

    function tablespb(kode){
        $.ajax({
            url: '{!! route('truckingnondetail.getDataspb') !!}',
            type: 'GET',
            data : {
                'id': kode
            },
            success: function(result) {
                Table2.clear().draw();
                Table2.rows.add(result).draw();
                
                document.getElementById("item").readOnly = false;
                $('#addspbform').modal('show');
                $('.editbutton').hide();
                $('.hapusbutton').hide();
            }
        });
    }

    $('#editform2').on('show.bs.modal', function () {
        $('.form-group4').show();
        $('.form-group5').hide();
    })

        $(document).ready(function() {
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            $('[data-toggle="tooltip"]').tooltip();   

            var table = $('#data2-table').DataTable();

            $('#data2-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                }
                else {
                    document.getElementById("item").readOnly = false;

                    $('#item').val('');
                    $('#qty').val('');
                    $('#berats').val('');
                    $('#total').val('');
                    $('#keterangan').val('');

                    table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');

                    closeOpenedRows(table, select);

                    var no_truckingnon = select.find('td:eq(0)').text();
                    var no_spb = select.find('td:eq(1)').text();
                    var item = select.find('td:eq(5)').text();
                    var add = $("#addtruckingnon2").attr("href","http://localhost/gui_front_pbm_laravel/admin/truckingnon/"+no_truckingnon+"/detail3");
                    if(item == 0){
                        $('.add-button').show();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').hide();
                    }else{
                        $('.add-button').show();
                        $('.hapus-button').hide();
                        $('.edit-button').show();
                        $('.view-button').show();
                    }
                }
            } );

            $('#editspbkembali').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                var no_spbnon = select.find('td:eq(1)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingnondetail.edit_truckingnon2') !!}',
                    type: 'POST',
                    data : {
                        'no_truckingnon': no_truckingnon,
                        'no_spbnon': no_spbnon,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#notruckingnon3').val(results.no_truckingnon);
                        $('#spb3').val(results.no_spb);
                        $('#spbmanual3').val(results.no_spb_manual);
                        $('#tanggal3').val(results.tanggal_spb);
                        $('#kembali3').val(results.tanggal_kembali);
                        $('#berat3').val(results.total_berat);
                        $('#kode_mobil3').val(results.kode_mobil).trigger('change');
                        $('#asset_mobil3').val(results.no_asset_mobil).trigger('change');
                        $('#kode_sopir3').val(results.kode_sopir).trigger('change');
                        $('#Namasopir3').val(results.kode_sopir);
                        $('#gaji3').val(results.tarif_gajisopir);
                        $('#uang3').val(results.uang_jalan);
                        $('#bbm3').val(results.bbm);
                        $('#dari3').val(results.dari);
                        $('#tujuan3').val(results.tujuan);
                        $('#editform2').modal('show');
                    }
                });
            });

            var table2 = $('#addspb-table').DataTable();

            $('#addspb-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.editbutton').hide();
                    $('.hapusbutton').hide();
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();

                    $('#item').val('');
                    $('#qty').val('');
                    $('#berats').val('');
                    $('#total').val('');
                    $('#keterangan').val('');

                    document.getElementById("item").readOnly = false;
                }
                else {
                    table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    $('.editbutton').show();
                    $('.hapusbutton').show();
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();

                    var kode_item = select.find('td:eq(2)').text();
                    var qty = select.find('td:eq(3)').text();
                    var berat = select.find('td:eq(4)').text();
                    var total = select.find('td:eq(5)').text();
                    var keterangan = select.find('td:eq(6)').text();

                    document.getElementById("item").readOnly = true;

                    $('#item').val(kode_item);
                    $('#qty').val(qty);
                    $('#berats').val(berat);
                    $('#total').val(total);
                    $('#keterangan').val(keterangan);
                }
            });

            $('#hapusspb').click( function () {
                table.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var no_spb = $.trim($('#spbnon').val());
                var select = $('.selected').closest('tr');
                var no_spbnon = select.find('td:eq(0)').text();
                var kode_item = select.find('td:eq(2)').text();
                var row = table2.row( select );
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
                            url: '{!! route('truckingnondetail.hapus_spbnon') !!}',
                            type: 'POST',
                            data : {
                                'no_spbnon': no_spbnon,
                                'kode_item': kode_item,
                            },

                            success: function (results) {
                                $('#item').val('');
                                $('#qty').val('');
                                $('#berats').val('');
                                $('#total').val('');
                                $('#keterangan').val('');
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                document.getElementById("item").readOnly = false;
                                refreshTable();
                                tablespb(no_spb);
                            }
                        });
                    }
                });
            });

            $('#editspb').click( function () {
                table.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var no_spb = $.trim($('#spbnon').val());
                var select = $('.selected').closest('tr');

                var no_spbnon = $('#spbnon').val();
                var kode_item = $('#item').val();
                var qty = $('#qty').val();
                var berat = $('#berats').val();
                var total = $('#total').val();
                var keterangan = $('#keterangan').val();
                var row = table2.row( select );
                
                $.ajax({
                    url: '{!! route('truckingnondetail.edit_spbnon') !!}',
                    type: 'POST',
                    data : {
                        'no_spbnon': no_spbnon,
                        'kode_item': kode_item,
                        'qty': qty,
                        'berat': berat,
                        'total': total,
                        'keterangan': keterangan,
                    },

                    success: function (results) {
                        $('#item').val('');
                        $('#qty').val('');
                        $('#berats').val('');
                        $('#total').val('');
                        $('#keterangan').val('');
                        if (results.success === true) {
                            swal("Berhasil!", results.message, "success");
                        } else {
                            swal("Gagal!", results.message, "error");
                        }
                        refreshTable();
                        tablespb(no_spb);
                    }
                });
            });

            $('#addspbbutton').click( function () {
                var select = $('.selected').closest('tr');
                var no_spbnon = select.find('td:eq(1)').text();
                $.ajax({
                    url: '{!! route('truckingnondetail.getDataspb') !!}',
                    type: 'GET',
                    data : {
                        'id': no_spbnon
                    },
                    success: function(result) {
                        console.log(result);

                        Table2.clear().draw();
                        Table2.rows.add(result).draw();
                
                        $('#addspbform').modal('show');
                        $('#spbnon').val(no_spbnon);                    
                    }
                });
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

            $('#edittruckingnon').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                var no_spbnon = select.find('td:eq(1)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingnondetail.edit_truckingnon') !!}',
                    type: 'POST',
                    data : {
                        'no_truckingnon': no_truckingnon,
                        'no_spbnon': no_spbnon
                    },
                    success: function(results) {
                        console.log(results);
                        $('#ID').val(results.id);
                        $('#notruckingnon2').val(results.no_truckingnon);
                        $('#spb2').val(results.no_spb);
                        $('#tanggal2').val(results.tanggal_spb);
                        $('#berat2').val(results.total_berat);
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapustruckingnon').click( function () {
                var select = $('.selected').closest('tr');
                var no_truckingnon = select.find('td:eq(0)').text();
                var no_spbnon = select.find('td:eq(1)').text();
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
                            url: '{!! route('truckingnondetail.hapus_truckingnon') !!}',
                            type: 'POST',
                            data : {
                                'no_truckingnon': no_truckingnon,
                                'no_spbnon': no_spbnon
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

        function formatNumber(n) {
            console.log(n);
            if(n == null || n == 0){
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

        function gettotal(){
            var qty= $('#qty').val();
            var berat= $('#berats').val();

            var total = qty * berat;
            $('#total').val(total);
        }

        function cektarif(){
            var tarif_gajisopir = parseInt($('#gaji2').val());
            var uang_jalan = parseInt($('#uang2').val());
            var bbm = parseInt($('#bbm2').val());

            if(tarif_gajisopir < uang_jalan || tarif_gajisopir < bbm){
                submit2.disabled = true;
            }else{
                submit2.disabled = false;
            }
        }

        function cekuang(){
            var tarif_gajisopir = parseInt($('#gaji2').val());
            var uang_jalan = parseInt($('#uang2').val());
            var bbm = parseInt($('#bbm2').val());

            if(uang_jalan > tarif_gajisopir || uang_jalan < bbm){
                submit2.disabled = true;
            }else{
                submit2.disabled = false;
            }
        }

        function cekbbm(){
            var tarif_gajisopir = parseInt($('#gaji2').val());
            var uang_jalan = parseInt($('#uang2').val());
            var bbm = parseInt($('#bbm2').val());

            if(bbm > tarif_gajisopir || bbm > uang_jalan){
                submit2.disabled = true;
            }else{
                submit2.disabled = false;
            }
        }

        function cektarif2(){
            var tgl = $('#tanggal3').val();
            var balik = $('#kembali3').val();
            var tarif_gajisopir = parseInt($('#gaji3').val());
            var uang_jalan = parseInt($('#uang3').val());
            var bbm = parseInt($('#bbm3').val());

            if(tarif_gajisopir < uang_jalan || tarif_gajisopir < bbm){
                submit4.disabled = true;
            }else{
                if ( balik < tgl ){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekuang2(){
            var tgl = $('#tanggal3').val();
            var balik = $('#kembali3').val();
            var tarif_gajisopir = parseInt($('#gaji3').val());
            var uang_jalan = parseInt($('#uang3').val());
            var bbm = parseInt($('#bbm3').val());

            if(uang_jalan > tarif_gajisopir || uang_jalan < bbm){
                submit4.disabled = true;
            }else{
                if ( balik < tgl ){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekbbm2(){
            var tgl = $('#tanggal3').val();
            var balik = $('#kembali3').val();
            var tarif_gajisopir = parseInt($('#gaji3').val());
            var uang_jalan = parseInt($('#uang3').val());
            var bbm = parseInt($('#bbm3').val());

            if(bbm > tarif_gajisopir || bbm > uang_jalan){
                submit4.disabled = true;
            }else{
                if ( balik < tgl ){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
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
            if ((("0123456789").indexOf(keychar) > -1)) {
                return true;
            } else
            if (decimal && (keychar == ".")) {
                return true;
            } else return false;
        }

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

        $('#ADD_SPB').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
            })
            e.preventDefault();
            var no_spb = $.trim($('#spbnon').val());
            var registerForm = $("#ADD_SPB");
            var formData = registerForm.serialize();

            // Check if empty of not
            $.ajax({
                url:'{!! route('truckingnondetail.store2') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#item').val('');
                    $('#qty').val('');
                    $('#berats').val('');
                    $('#total').val('');
                    $('#keterangan').val('');
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal("Gagal!", data.message, "error");
                    }
                    refreshTable();
                    tablespb(no_spb);
                },
            });
        });

        $('#EDIT_SPB').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
            })
            e.preventDefault();
            
            var registerForm = $("#EDIT_SPB");
            var formData = registerForm.serialize();
                $.ajax({
                    url:'{!! route('truckingnondetail.updateajax3') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        $('#editform2').modal('hide');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                        } else {
                            swal("Gagal!", data.message, "error");
                        }   
                    },
                });
            
        });

        $('#ADD_DETAIL').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
            })
            e.preventDefault();
            var registerForm = $("#ADD_DETAIL");
            var formData = registerForm.serialize();

            // Check if empty of not
            $.ajax({
                    url:'{!! route('truckingnondetail.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#spb').val('');
                        $('#spbmanual').val('');
                        $('#tanggal').val('');
                        $('#berat').val(0);
                        $('#Pemilik1').val('');
                        $('#kode_pemilik').val('');
                        $('#kode_mobil').val('').trigger('change');
                        $('#kode_sopir').val('').trigger('change');
                        $('#gaji').val('');
                        $('#uang').val('');
                        $('#bbm').val('');
                        $('#dari').val('').trigger('change');
                        $('#tujuan').val('').trigger('change');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                            $('.form-group1').hide();
                            $('.form-group2').hide();
                            submit.disabled = true;
                        } else {
                            swal("Gagal!", data.message, "error");
                        }
                    },
                });
            
        });

        $('#UPDATE_DETAIL').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
            })
            e.preventDefault();
            
            var registerForm = $("#UPDATE_DETAIL");
            var formData = registerForm.serialize();
                $.ajax({
                    url:'{!! route('truckingnondetail.updateajax') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        if(data.success === true) {
                            swal("Berhasil!", data.message, "success");
                        }else{
                            swal("Gagal!", data.message, "error");
                        }
                        refreshTable();
                        $(".addform").show();
                        $(".editform").hide();
                    
                    },
                });
        });

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
        }
    </script>
@endpush