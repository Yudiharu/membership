@extends('adminlte::page')

@section('title', 'Pemakaian Alat Berat')

@section('content_header')
    
@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
@include('sweet::alert')
<body onLoad="load()">
    <div class="box box-solid">
        <div class="box-body">
            <div class="box ">
                <div class="box-body">
                    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()" >
                            <i class="fa fa-refresh"></i> Refresh</button>
                    @permission('create-pemakaianalatberat')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Pemakaian</button>
                    @endpermission

                    <!--<button type="button" class="btn btn-primary btn-xs" onclick="getkode()">-->
                    <!--    <i class="fa fa-bullhorn"></i> Get New Kode</button>-->

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>PEMAKAIAN ALAT BERAT</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Pemakaian</th>
                        <th>Tgl Pemakaian</th>
                        <th>No JO</th>
                        <th>Nama Customer</th>
                        <th>Type JO</th>
                        <th>Type Kegiatan</th>
                        <th>Status Lokasi</th>
                        <th>Total TimeSheet</th>
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
                            {{ Form::label('Tanggals', 'Tgl Pemakaian:') }}
                            {{ Form::date('tgl_pemakaian', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('customers', 'No JO:') }}
                            {{ Form::select('no_joborder',$Joborder->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'NoJO1','required','onchange'=>"getjo()"]) }}
                        </div>
                    </div>
                    {{ Form::hidden('kode_customer',null, ['class'=> 'form-control','id'=>'sKodeCust1','readonly']) }}
                    {{ Form::hidden('type_jo',null, ['class'=> 'form-control','id'=>'sTypeJO1','readonly']) }}
                    {{ Form::hidden('type_kegiatan',null, ['class'=> 'form-control','id'=>'sTypeKegiatan1','readonly']) }}
                    {{ Form::hidden('status_lokasi',null, ['class'=> 'form-control','id'=>'sLokasi1','readonly']) }}
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Nama', 'Nama Customer:') }}
                            {{ Form::text('nama_customer', null, ['class'=> 'form-control','id'=>'KodeCust1', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('TIpeJO', 'Type JO:') }}
                            {{ Form::text('tipe_jo', null, ['class'=> 'form-control','id'=>'TypeJO1', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Kegiatan', 'Type Kegiatan:') }}
                            {{ Form::text('tipe_kegiatan', null, ['class'=> 'form-control','id'=>'TypeKegiatan1', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'Status Lokasi:') }}
                            {{ Form::text('stat_lokasi', null, ['class'=> 'form-control','id'=>'Lokasi1', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
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
                    {{ Form::hidden('no_pemakaian',null, ['class'=> 'form-control','id'=>'NoPakai2','readonly']) }}
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tgl Pemakaian:') }}
                            {{ Form::date('tgl_pemakaian', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal2' ,'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('customers', 'No JO:') }}
                            {{ Form::select('no_joborder',$Joborder->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'NoJO2','required','onchange'=>"getjo2()"]) }}
                        </div>
                    </div>
                    {{ Form::hidden('kode_customer',null, ['class'=> 'form-control','id'=>'sKodeCust2','readonly']) }}
                    {{ Form::hidden('type_jo',null, ['class'=> 'form-control','id'=>'sTypeJO2','readonly']) }}
                    {{ Form::hidden('type_kegiatan',null, ['class'=> 'form-control','id'=>'sTypeKegiatan2','readonly']) }}
                    {{ Form::hidden('status_lokasi',null, ['class'=> 'form-control','id'=>'sLokasi2','readonly']) }}
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Nama', 'Nama Customer:') }}
                            {{ Form::text('nama_customer', null, ['class'=> 'form-control','id'=>'KodeCust2', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('TIpeJO', 'Type JO:') }}
                            {{ Form::text('tipe_jo', null, ['class'=> 'form-control','id'=>'TypeJO2', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Kegiatan', 'Type Kegiatan:') }}
                            {{ Form::text('tipe_kegiatan', null, ['class'=> 'form-control','id'=>'TypeKegiatan2', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'Status Lokasi:') }}
                            {{ Form::text('stat_lokasi', null, ['class'=> 'form-control','id'=>'Lokasi2', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
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
                background-color: #1674c7;
                bottom: 186px;
            }

            .viewjor-button {
                background-color: #00E0FF;
                bottom: 216px;
            }

            .tombol1 {
                background-color: #149933;
                bottom: 246px;
            }

            .tombol2 {
                background-color: #ff9900;
                bottom: 276px;
            }

            .printjr-button {
                background-color: #f44336;
                bottom: 246px;
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

            .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(https://img.pikbest.com/png-images/20190918/cartoon-snail-loading-loading-gif-animation_2734139.png!bw340) center no-repeat #fff;
            }
        </style>

        <div id="mySidenav" class="sidenav">
            @permission('update-pemakaianalatberat')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editjoborder" data-toggle="modal" data-target="">EDIT<i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-pemakaianalatberat')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusjoborder" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('add-pemakaianalatberat')
            <a href="#" id="addjo"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD DETAIL <i class="fa fa-plus"></i></button></a>
            @endpermission

            @permission('post-pemakaianalatberat')
            <button type="button" class="btn btn-success btn-xs tombol1" id="button1">POST <i class="fa fa-bullhorn"></i></button>
            @endpermission

            @permission('unpost-pemakaianalatberat')
            <button type="button" class="btn btn-warning btn-xs tombol2" id="button2">UNPOST <i class="fa fa-undo"></i></button>
            @endpermission

            @permission('view-pemakaianalatberat')
            <button type="button" class="btn btn-primary btn-xs view-button" id="button5">VIEW DETAIL <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('print-pemakaianalatberat')
            <a href="#" target="_blank" id="printjr"><button type="button" class="btn btn-danger btn-xs printjr-button" id="button11">PRINT<i class="fa fa-print"></i></button></a>
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
            $('.printjr-button').hide();
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
                        url:'{!! route('pemakaianalat.getkode') !!}',
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

    function getjo(){
        var no_jo = $('#NoJO1').val();
        $.ajax({
            url:'{!! route('pemakaianalat.getjo') !!}',
            type:'POST',
            data : {
                'no_jo': no_jo,
            },
            success: function(result) {
                $('#sKodeCust1').val(result.kode_customer);
                $('#sTypeJO1').val(result.type_jo);
                $('#sTypeKegiatan1').val(result.type_kegiatan);
                $('#sLokasi1').val(result.status_lokasi);
                $('#KodeCust1').val(result.nama_customer);
                $('#TypeJO1').val(result.tipe_jo);
                $('#TypeKegiatan1').val(result.tipe_kegiatan);
                $('#Lokasi1').val(result.nama_lokasi);
            },
        });
    }

    function getjo2(){
        var no_jo = $('#NoJO2').val();
        $.ajax({
            url:'{!! route('pemakaianalat.getjo2') !!}',
            type:'POST',
            data : {
                'no_jo': no_jo,
            },
            success: function(result) {
                $('#sKodeCust2').val(result.kode_customer);
                $('#sTypeJO2').val(result.type_jo);
                $('#sTypeKegiatan2').val(result.type_kegiatan);
                $('#sLokasi2').val(result.status_lokasi);
                $('#KodeCust2').val(result.nama_customer);
                $('#TypeJO2').val(result.tipe_jo);
                $('#TypeKegiatan2').val(result.tipe_kegiatan);
                $('#Lokasi2').val(result.nama_lokasi);
            },
        });
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
            ajax: '{!! route('pemakaianalat.data') !!}',
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status'] == "OPEN") {
                    $('td', row).css('background-color', '#ffdbd3');
                }
            },
            columns: [
                { data: 'no_pemakaian', name: 'no_pemakaian' },
                { data: 'tgl_pemakaian', name: 'tgl_pemakaian' },
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'customer.nama_customer', "defaultContent": "<i>Not set</i>" },
                { data: 'type_jo', 
                    render: function( data, type, full ) {
                    return type_jo(data); }
                },
                { data: 'type_kegiatan', 
                    render: function( data, type, full ) {
                    return type_kegiatan(data); }
                },
                { data: 'status_lokasi',  
                    render: function( data, type, full ) {
                    return status_lokasi(data); }
                },
                { data: 'total_timesheet', name: 'total_timesheet' },
                { data: 'status',
                    render: function( data, type, full ) {
                    return formatStat(data); }
                },
            ]
            });
        });
        
        function type_jo(n) {
            if(n == '1'){
                var stat = "<span style='color:#030100'><b>Bongkar Muat Curah</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Bongkar Muat Non Curah</b></span>";
            }else if(n == '3'){
                var stat = "<span style='color:#c91a1a'><b>Rental Alat</b></span>";
            }else if(n == '4'){
                var stat = "<span style='color:#1a80c9'><b>Trucking</b></span>";
            }else if(n == '5'){
                var stat = "<span style='color:#1a80c9'><b>Lain-lain</b></span>";
            }
            return stat;
        }

        function formatStat(n) {
            if(n == 'OPEN'){
                var stat = "<span style='color:#030100'><b>OPEN</b></span>";
            }else if(n == 'POSTED'){
                var stat = "<span style='color:#0eab25'><b>POSTED</b></span>";
            }
            return stat;
        }

        function type_kegiatan(n) {
            if(n == '1'){
                var stat = "<span style='color:#c91a1a'><b>Non Transhipment</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Transhipment</b></span>";
            }
            return stat;
        }

        function status_lokasi(n) {
            if(n == '1'){
                var stat = "<span style='color:#1a80c9'><b>Dalam Kota</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Luar Kota</b></span>";
            }
            return stat;
        }

        function formatStatus(n) {
            if(n == '1'){
                var stat = "<span style='color:#030100'><b>OPEN</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>POSTED</b></span>";
            }else if(n == '3'){
                var stat = "<span style='color:#c91a1a'><b>INVOICED</b></span>";
            }else if(n == '4'){
                var stat = "<span style='color:#1a80c9'><b>PAID</b></span>";
            }
            return stat;
        }

        function formatNumber(n) {
            if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function createTable(result){

        var my_table = "";

        $.each( result, function( key, row ) {
            my_table += "<tr>";
            my_table += "<td>"+row.kode_alat+"</td>";
            
            if (row.hitungan_pemakaian != '1') {
                my_table += "<td>Hour Meter</td>";
            }else {
                my_table += "<td>Jam</td>";
            }

            my_table += "<td>"+row.no_timesheet+"</td>";
            my_table += "<td>"+row.operator+"</td>";
            my_table += "<td>"+row.helper1+"</td>";
            my_table += "<td>"+row.helper2+"</td>";

            if (row.pekerjaan != null) {
                my_table += "<td>"+row.pekerjaan+"</td>";
            }else {
                my_table += "<td>-</td>";
            }
            
            my_table += "<td>"+row.tgl_pakai+"</td>";

            if (row.hari_libur != '1') {
                my_table += "<td>Tidak</td>";
            }else {
                my_table += "<td>Ya</td>";
            }
            
            if (row.jam_dr != null) {
                my_table += "<td>"+row.jam_dr+"</td>";
            }else {
                my_table += "<td>-</td>";
            }
            
            if (row.jam_sp != null) {
                my_table += "<td>"+row.jam_sp+"</td>";
            }else {
                my_table += "<td>-</td>";
            }
            
            if (row.istirahat != null) {
                my_table += "<td>"+row.istirahat+"</td>";
            }else {
                my_table += "<td>-</td>";
            }

            if (row.stand_by != null) {
                my_table += "<td>"+row.stand_by+"</td>";
            }else {
                my_table += "<td>-</td>";
            }

            if (row.hm_dr != null) {
                my_table += "<td>"+parseFloat(row.hm_dr).toFixed(2)+"</td>";
            }else {
                my_table += "<td>-</td>";
            }

            if (row.hm_sp != null) {
                my_table += "<td>"+parseFloat(row.hm_sp).toFixed(2)+"</td>";
            }else {
                my_table += "<td>-</td>";
            }
            
            my_table += "<td>"+row.total_jam+"</td>";
            my_table += "<td>"+formatNumber(parseFloat(row.total_hm).toFixed(2))+"</td>";

            if (row.no_insentif != null) {
                my_table += "<td>"+row.no_insentif+"</td>";
            }else {
                my_table += "<td>-</td>";
            }

            if (row.no_insentif_helper1 != null) {
                my_table += "<td>"+row.no_insentif_helper1+"</td>";
            }else {
                my_table += "<td>-</td>";
            }

            if (row.no_insentif_helper2 != null) {
                my_table += "<td>"+row.no_insentif_helper2+"</td>";
            }else {
                my_table += "<td>-</td>";
            }
            my_table += "</tr>";
        });

            my_table = '<table id="table-fixed" class="table table-bordered" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Kode Alat</th>'+
                                '<th>Hitungan Pemakaian</th>'+
                                '<th>No TimeSheet</th>'+
                                '<th>Operator</th>'+
                                '<th>Helper1</th>'+
                                '<th>Helper2</th>'+
                                '<th>Pekerjaan</th>'+
                                '<th>Tgl Pakai</th>'+
                                '<th>Hari Libur</th>'+
                                '<th>Jam Dr</th>'+
                                '<th>Jam Sp</th>'+
                                '<th>Istirahat</th>'+
                                '<th>Stand By</th>'+
                                '<th>HM Dr</th>'+
                                '<th>HM Sp</th>'+
                                '<th>Total Jam</th>'+
                                '<th>Total HM</th>'+
                                '<th>No Insentif Operator</th>'+
                                '<th>No Insentif Helper1</th>'+
                                '<th>No Insentif Helper2</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
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
                    my_table += "<td>"+row.no_req_jo+"</td>";
                    my_table += "<td>"+row.kode_container+"</td>";
                    my_table += "<td>"+row.kode_size+"</td>";
                    my_table += "<td>"+row.status_muatan+"</td>";
                    my_table += "<td>"+row.dari+"</td>";
                    my_table += "<td>"+row.tujuan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No Job Request</th>'+
                                '<th>No Container</th>'+
                                '<th>Size Container</th>'+
                                '<th>Status Muatan</th>'+
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

        $('#editform').on('show.bs.modal', function () {
            var optionVal = $("#Jenis").val();
            if (optionVal == '1' || optionVal == '2' || optionVal == '3') {
                $('.form-group20').show();
                $('.form-group21').show();
                $('.form-group22').show();
                $('.form-group23').show();
                $('.form-group24').show();
                $('.form-group25').show();
                $('.form-group26').show();
                $('.form-group27').show();
                $('.form-group28').show();
                $('.form-group29').show();
                $('.form-group30').show();
                $('.form-group31').show();
                $('.form-group32').show();
                $('.form-group33').show();
                $('.form-group34').show();
                $('.form-group35').show();
                $('.form-group36').show();
                $('.form-group37').show();
                $('.form-group38').show();
            }else{
                $('.form-group20').show();
                $('.form-group21').show();
                $('.form-group22').show();
                $('.form-group23').show();
                $('.form-group24').show();
                $('.form-group25').show();
                $('.form-group26').hide();
                $('.form-group27').hide();
                $('.form-group28').hide();
                $('.form-group29').hide();
                $('.form-group30').hide();
                $('.form-group31').hide();
                $('.form-group32').hide();
                $('.form-group33').hide();
                $('.form-group34').hide();
                $('.form-group35').hide();
                $('.form-group36').hide();
                $('.form-group37').hide();
                $('.form-group38').hide();
            }
        })

        $(document).ready(function(){   
            $(".se-pre-con").fadeOut("slow");
            
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
                    $('.printjr-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data-table').DataTable().row(select).data();

                    closeOpenedRows(table, select);
                    
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('.viewjor-button').hide();
                    $('.printjr-button').hide();
                    
                    var no_pemakaian = data['no_pemakaian'];
                    var status = data['status'];
                    var item = data['total_timesheet'];
                    var add = $("#addjo").attr("href",window.location.href+"/"+no_pemakaian+"/detail");
                    var print = $("#printjr").attr("href",window.location.href+"/exportpdf?no_pemakaian="+no_pemakaian);
                    if(status == 'POSTED'){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.add-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.printjr-button').show();
                    }else if(status =='OPEN' && item < 1){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').hide();
                        $('.printjr-button').hide();
                    }else if (status == 'OPEN' && item > 0){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.hapus-button').hide();
                        $('.edit-button').show();
                        $('.view-button').show();
                        $('.printjr-button').hide();
                    }
                }
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

            $('#button1').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_pemakaian = data['no_pemakaian'];
                swal({
                    title: "Post?",
                    text: no_pemakaian,
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
                            url: '{!! route('pemakaianalat.post') !!}',
                            type: 'POST',
                            data : {
                                'id': no_pemakaian
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
                var no_joborder = colom;
                console.log(no_joborder);
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
                            url: '{!! route('pemakaianalat.unpost') !!}',
                            type: 'POST',
                            data : {
                                'id': no_joborder
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
                var data = $('#data-table').DataTable().row(select).data();
                var no_pemakaian = data['no_pemakaian'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('pemakaianalat.showdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': no_pemakaian
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
                var no_joborder = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('pemakaianalat.showdetailjor') !!}',
                    type: 'POST',
                    data : {
                        'id': no_joborder
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

            $('#editjoborder').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_pemakaian = data['no_pemakaian'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('pemakaianalat.edit_pemakaian') !!}',
                    type: 'POST',
                    data : {
                        'no_pemakaian': no_pemakaian
                    },
                    success: function(results) {
                        $('#NoPakai2').val(no_pemakaian);
                        $('#NoJO2').val(results.no_joborder).trigger('change');
                        $('#editform').modal('show');
                    }
         
                });
            });

            $('#hapusjoborder').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_pemakaian = data['no_pemakaian'];
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
                            url: '{!! route('pemakaianalat.hapus_pemakaian') !!}',
                            type: 'POST',
                            data : {
                                'id': no_pemakaian
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
            $('.viewjor-button').hide();
            $('.viewpenyelesaian-button').hide();
            $('.viewbiaya-button').hide();
            $('.viewkasbon-button').hide();
            $('.viewaju-button').hide();
            $('.printjr-button').hide();
            $('.add-button').hide();

            $('.penyelesaian-button').hide();
            $('.biaya-button').hide();
            $('.kasbon-button').hide();
            $('.aju-button').hide();
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
            
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            
            console.log(formData);
            $.ajax({
                url:'{!! route('pemakaianalat.store') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#Tanggal1').val(today);
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
                    url:'{!! route('pemakaianalat.updateajax') !!}',
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