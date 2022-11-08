@extends('adminlte::page')

@section('title', 'SPB Container')

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

                    @permission('create-customer')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New SPB</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>SPB</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No SPB</th>
                        <th>SPB Manual</th>
                        <th>Tanggal SPB</th>
                        <th>SPB Kembali</th>
                        <th>Mobil</th>
                        <th>Sopir</th>
                        <th>Pemilik</th>
                        <th>Uang Jalan</th>
                        <th>BBM</th>
                        <th>B/P/A</th>
                        <th>Honor</th>
                        <th>Biaya Lainnya</th>
                        <th>Trucking</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addform" tabindex="-1" role="dialog">
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
                                    {{ Form::label('no_spbmanual', 'SPB Manual:') }}
                                    {{ Form::text('no_spbmanual', null, ['class'=> 'form-control','id'=>'Manual1', 'placeholder'=>'No SPB Manual', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tgl_spb', 'Tanggal SPB:') }}
                                    {{ Form::date('tgl_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tgl_kembali', 'Tanggal Kembali SPB:') }}
                                    {{ Form::date('tgl_kembali', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Kembali1' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_mobil', 'Mobil:') }}
                                    {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Mobil1','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_sopir', 'Sopir:') }}
                                    {{ Form::select('kode_sopir',$Sopir->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Sopir1','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_pemilik', 'Pemilik:') }}
                                    {{ Form::select('kode_pemilik',$Pemilik->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Pemilik1','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('uang_jalan', 'Uang Jalan:') }}
                                    {{ Form::text('uang_jalan', null, ['class'=> 'form-control','id'=>'Uang1', 'placeholder'=>'Uang Jalan', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bbm', 'BBM:') }}
                                    {{ Form::text('bbm', null, ['class'=> 'form-control','id'=>'Bbm1', 'placeholder'=>'BBM', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bpa', 'B/P/A:') }}
                                    {{ Form::text('bpa', null, ['class'=> 'form-control','id'=>'Bpa1', 'placeholder'=>'BPA', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('honor', 'Honor:') }}
                                    {{ Form::text('honor', null, ['class'=> 'form-control','id'=>'Honor1', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('biaya_lain', 'Biaya Lain:') }}
                                    {{ Form::text('biaya_lain', null, ['class'=> 'form-control','id'=>'Biaya1', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('trucking', 'Trucking:') }}
                                    {{ Form::text('trucking', null, ['class'=> 'form-control','id'=>'Trucking1', 'placeholder'=>'Trucking', 'autocomplete'=>'off','required']) }}
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
                                    {{ Form::label('no_spb', 'No SPB:') }}
                                    {{ Form::text('no_spb', null, ['class'=> 'form-control','id'=>'Spb','readonly']) }}
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_spbmanual', 'SPB Manual:') }}
                                    {{ Form::text('no_spbmanual', null, ['class'=> 'form-control','id'=>'Manual', 'placeholder'=>'No SPB Manual', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tgl_spb', 'Tanggal SPB:') }}
                                    {{ Form::date('tgl_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tgl_kembali', 'Tanggal Kembali SPB:') }}
                                    {{ Form::date('tgl_kembali', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Kembali' ,'required'=>'required'])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_mobil', 'Mobil:') }}
                                    {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Mobil','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_sopir', 'Sopir:') }}
                                    {{ Form::select('kode_sopir',$Sopir->sort(),null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Sopir','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kode_pemilik', 'Pemilik:') }}
                                    {{ Form::select('kode_pemilik',$Pemilik->sort(),null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Pemilik','required'=>'required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('uang_jalan', 'Uang Jalan:') }}
                                    {{ Form::text('uang_jalan', null, ['class'=> 'form-control','id'=>'Uang', 'placeholder'=>'Uang Jalan', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bbm', 'BBM:') }}
                                    {{ Form::text('bbm', null, ['class'=> 'form-control','id'=>'Bbm', 'placeholder'=>'BBM', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bpa', 'B/P/A:') }}
                                    {{ Form::text('bpa', null, ['class'=> 'form-control','id'=>'Bpa', 'placeholder'=>'BPA', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('honor', 'Honor:') }}
                                    {{ Form::text('honor', null, ['class'=> 'form-control','id'=>'Honor', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('biaya_lain', 'Biaya Lain:') }}
                                    {{ Form::text('biaya_lain', null, ['class'=> 'form-control','id'=>'Biaya', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                 </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('trucking', 'Trucking:') }}
                                    {{ Form::text('trucking', null, ['class'=> 'form-control','id'=>'Trucking', 'placeholder'=>'Trucking', 'autocomplete'=>'off','required']) }}
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
            .hapus-button {
                background-color: #F63F3F;
                bottom: 186px;
            }

            .edit-button {
                background-color: #FDA900;
                bottom: 216px;
            }

            /*.view-button {
                background-color: #1674c7;
                bottom: 246px;
            }*/

            .tombol1 {
                background-color: #149933;
                bottom: 276px;
            }

            .tombol2 {
                background-color: #ff9900;
                bottom: 306px;
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
            @permission('update-customer')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editspb" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-customer')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusspb" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('post-pembelian')
            <button type="button" class="btn btn-success btn-xs tombol1" id="button1">POST <i class="fa fa-bullhorn"></i></button>
            @endpermission

            @permission('unpost-pembelian')
            <button type="button" class="btn btn-warning btn-xs tombol2" id="button2">UNPOST <i class="fa fa-undo"></i></button>
            @endpermission

            <!-- @permission('view-pembelian')
            <button type="button" class="btn btn-primary btn-xs view-button" id="button5">VIEW <i class="fa fa-eye"></i></button>
            @endpermission -->
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
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.back2Top').show();
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
            ajax: '{!! route('spb.data') !!}',
            columns: [
                { data: 'no_spb', name: 'no_spb' },
                { data: 'no_spbmanual', name: 'no_spbmanual' },
                { data: 'tgl_spb', name: 'tgl_spb' },
                { data: 'tgl_kembali', name: 'tgl_kembali' },
                { data: 'mobil.nopol', name: 'mobil.nopol' },
                { data: 'sopir.nama_sopir', name: 'sopir.nama_sopir' },
                { data: 'pemilik.nama_pemilik', name: 'pemilik.nama_pemilik' },
                { data: 'uang_jalan',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'bbm',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'bpa',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'honor',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'biaya_lain',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'trucking',
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

        function createTable(result){

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.kode_kapal+"</td>";
                    my_table += "<td>"+row.voyage+"</td>";
                    my_table += "<td>"+row.port_loading+"</td>";
                    my_table += "<td>"+row.etd+"</td>";
                    my_table += "<td>"+row.port_transite+"</td>";
                    my_table += "<td>"+row.port_destination+"</td>";
                    my_table += "<td>"+row.eta+"</td>";
                    my_table += "<td>"+row.no_do+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered table-hover" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Nama Kapal</th>'+
                                '<th>Voyage</th>'+
                                '<th>Port Of Loading</th>'+
                                '<th>ETD</th>'+
                                '<th>Port Of Transite</th>'+
                                '<th>Port Of Destination</th>'+
                                '<th>ETA</th>'+
                                '<th>No DO</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '</table>';

                    // $(document).append(my_table);
            
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        $(document).ready(function(){
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var colom = select.find('td:eq(13)').text();
                    var no_spb = select.find('td:eq(0)').text();
                    var status = colom;
                    var add = $("#addpembelian").attr("href","http://localhost/gui_front_pbm_laravel/admin/joborder/"+no_spb+"/detail");
                    if(status == 'POSTED'){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.add-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                    }else if(status =='OPEN'){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').show();
                    }
                }
            });

            $('#button1').click( function () {
                var select = $('.selected').closest('tr');
                var colom = select.find('td:eq(0)').text();
                var no_spb = colom;
                console.log(no_spb);
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
                            url: '{!! route('spb.post') !!}',
                            type: 'POST',
                            data : {
                                'id': no_spb
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
                var no_spb = colom;
                console.log(no_spb);
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
                            url: '{!! route('spb.unpost') !!}',
                            type: 'POST',
                            data : {
                                'id': no_spb
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

            $('#button5').click( function () {
                var select = $('.selected').closest('tr');
                var no_spb = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('spb.showdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': no_spb
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.title == 'Gagal'){
                            $.notify(result.message);
                        }else{
                            if ( row.child.isShown() ) {
                            row.child.hide();
                            tr.removeClass('shown');
                        }
                        else {
                            var len = result.length;
                            for (var i = 0; i < len; i++) {
                                console.log(result[i].produk,result[i].satuan,result[i].qty,result[i].harga);
                                // alert(result[i].produk);
                            }

                            row.child( createTable(result) ).show();
                            // row.child( format(result) ).show();
                            select.addClass('shown');
                        }
                     }
                    }
                });
            });

            $('#editspb').click( function () {
                var select = $('.selected').closest('tr');
                var no_spb = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('spb.edit_spb') !!}',
                    type: 'POST',
                    data : {
                        'id': no_spb
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Spb').val(results.no_spb);
                        $('#Manual').val(results.no_spbmanual);
                        $('#Tanggal').val(results.tgl_spb);
                        $('#Kembali').val(results.tgl_kembali);
                        $('#Mobil').val(results.kode_mobil);
                        $('#Sopir').val(results.kode_sopir);
                        $('#Pemilik').val(results.kode_pemilik);
                        $('#Uang').val(results.uang_jalan);
                        $('#Bbm').val(results.bbm);
                        $('#Bpa').val(results.bpa);
                        $('#Honor').val(results.honor);
                        $('#Biaya').val(results.biaya_lain);
                        $('#Trucking').val(results.trucking);
                        $('#editform').modal('show');
                        }
         
                });
            });

            $('#hapusspb').click( function () {
                var select = $('.selected').closest('tr');
                var no_spb = select.find('td:eq(0)').text();
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
                            url: '{!! route('spb.hapus_spb') !!}',
                            type: 'POST',
                            data : {
                                'id': no_spb
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
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
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
                    url:'{!! route('spb.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Manual1').val('');
                        $('#Tanggal1').val('');
                        $('#Kembali1').val('');
                        $('#Mobil1').val('').trigger('change');
                        $('#Sopir1').val('').trigger('change');
                        $('#Pemilik1').val('').trigger('change');
                        $('#Uang1').val('');
                        $('#Bbm1').val('');
                        $('#Bpa1').val('');
                        $('#Honor1').val('');
                        $('#Biaya1').val('');
                        $('#Trucking1').val('');
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
                    url:'{!! route('spb.updateajax') !!}',
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
        
        function update() {
         e.preventDefault();
         alert('test update');
         var form_action = $("#editform").find("form").attr("action");
                $.ajax({
                    
                    url: form_action,
                    type: 'POST',
                    data:$('#Update').serialize(),
                    success: function(data) {
                        console.log(data);
                        $('#editform').modal('hide');
                        $.notify(data.message, "success");
                        refreshTable();
                    }
                });
        }
    </script>
@endpush