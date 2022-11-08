@extends('adminlte::page')

@section('title', 'Premi Operator Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <button type="button" class="btn btn-success btn-xs" onclick="hapus()"><i class="fa fa-times-circle"></i> Delete All</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> No Premi <b>{{$insentif->no_insentif}}</b></font>
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
            {{ Form::hidden('no_insentif', $insentif->no_insentif, ['class'=> 'form-control','id'=>'NoInsentif1','readonly']) }}
            {{ Form::hidden('kode_operator', $insentif->kode_operator, ['class'=> 'form-control','id'=>'KodeOp1','readonly']) }}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('nomors', 'Nama Operator:') }}
                            {{ Form::text('operator',$Operator->nama_operator, ['class'=> 'form-control','readonly','id'=>'Nama1']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('qtys', 'Tgl Pakai Dari:') }}
                            {{ Form::text('tgl_pakai_dari', $insentif->tgl_pakai_dari, ['class'=> 'form-control','id'=>'TglDari1','readonly','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('qtys', 'Tgl Pakai Sampai:') }}
                            {{ Form::text('tgl_pakai_sampai', $insentif->tgl_pakai_sampai, ['class'=> 'form-control','id'=>'TglSampai1','readonly','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="button" class="btn btn-primary btn-sm" title="Get Pemakaian" onclick="getpakai()" id='submit3'>Get Pemakaian</button>
                    </div>
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('NamaAlat', 'Nama Alat:') }}
                            {{ Form::text('nama_alat', null,['class'=> 'form-control','id'=>'NamaAlat1' ,'required','readonly'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('TypeAlat', 'Kode Tagging:') }}
                            {{ Form::text('type', null,['class'=> 'form-control','id'=>'AsetAlat1' ,'required','readonly'])}}
                        </div>
                    </div>
                    
                </div> 
                <!-- <span class="pull-right"> 
                    {{ Form::submit('Add Item', ['class' => 'btn btn-success btn-sm','id'=>'submit']) }}  
                </span>          -->              
                {!! Form::close() !!}    
            </div>
        
            <div class="editform">
                @include('errors.validation')
                {!! Form::open(['id'=>'UPDATE_DETAIL']) !!}
                <center><kbd>EDIT FORM</kbd></center><br>
                <div class="row">
                    {{ Form::hidden('id',null, ['class'=> 'form-control','id'=>'ID','readonly']) }}
                    
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

   <div class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>id</th>
                        <th>No Insentif</th>
                        <th>No TimeSheet</th>
                        <th>No Pemakaian</th>
                        <th>No JO</th>
                        <th>Tgl Pakai</th>
                        <th>Hari Libur</th>
                        <th>Jam Dr</th>
                        <th>Jam Sp</th>
                        <th>HM Dr</th>
                        <th>HM Sp</th>
                        <th>Istirahat</th>
                        <th>Stand By</th>
                        <th>Total Jam</th>
                        <th>Total HM</th>
                        <th>Total Helper</th>
                        <th>Premi/Jam</th>
                        <th>Premi Hari Libur</th>
                        <th>Total Insentif</th>
                        <th>Luar Kota?</th>
                     </tr>
                    </thead>
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
            @permission('add-jo')
            <button type="button" class="btn btn-info btn-xs add-button" id="addjobutton" data-toggle="modal" data-target="#addjoform"><i class="fa fa-plus"></i> ADD CONTAINER</button>
            @endpermission

            <!-- @permission('add-jo')
            <a href="#" id="addjo"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD CONTAINER <i class="fa fa-plus"></i></button></a>
            @endpermission -->

            @permission('update-jo')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editjoborderdetail" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-jo')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusinsentif" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            <!-- @permission('add-jo')
            <button type="button" class="btn btn-success btn-xs view-button" id="viewjobreq">VIEW JOB REQUEST <i class="fa fa-eye"></i></button>
            @endpermission -->
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
            $('.editform').hide();
            $('.back2Top').show();
            $('.add-button').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
        }

        function getpakai()
        {
            var no_insentif = $('#NoInsentif1').val();
            var kode_operator = $('#KodeOp1').val();
            var tgldari = $('#TglDari1').val();
            var tglsampai = $('#TglSampai1').val();
                swal({
                    title: "Anda yakin data sudah benar?",
                    text: "Pastikan dahulu nama operator yang dipilih",
                    type: "question",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, benar!",
                    cancelButtonText: "Batal!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{!! route('insentifoperatordetail.getpakai') !!}',
                            type: 'POST',
                            data : {
                                'no_insentif': no_insentif,
                                'kode_operator': kode_operator,
                                'tgldari': tgldari,
                                'tglsampai': tglsampai,
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

        function hapus() {
            var no_insentif= $('#NoInsentif1').val();
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
                    url:'{!! route('insentifoperatordetail.hapusall') !!}',
                    type:'POST',
                    data : {
                            'id': no_insentif
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
        
        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        function createTable2(result){

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.kode_container+"</td>";
                    my_table += "<td>"+row.kode_size+"</td>";
                    my_table += "<td>"+row.status_muatan+"</td>";
                    my_table += "<td>"+row.dari+"</td>";
                    my_table += "<td>"+row.tujuan+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered table-hover" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Container</th>'+
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
        
    $(function(){
        var no_insentif = $('#NoInsentif1').val();
        var link = $('#Link1').val();
        $('#data2-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:link+'/gui_front_02/admin/insentifoperatordetail/getDatabyID?id='+no_insentif,
            data:{'no_insentif':no_insentif},
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'no_insentif', name: 'no_insentif', visible: false },
                { data: 'no_timesheet', name: 'no_timesheet' },
                { data: 'no_pemakaian', name: 'no_pemakaian' },
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'tgl_pakai', name: 'tgl_pakai' },
                { data: 'hari_libur', 
                    render: function( data, type, full ) {
                    return formatStat(data); }
                },
                { data: 'jam_dr', "defaultContent": "<i>00:00:00</i>" },
                { data: 'jam_sp', "defaultContent": "<i>00:00:00</i>" },
                { data: 'hm_dr', "defaultContent": "<i>0</i>" },
                { data: 'hm_sp', "defaultContent": "<i>0</i>" },
                { data: 'istirahat', "defaultContent": "<i>00:00:00</i>" },
                { data: 'stand_by', "defaultContent": "<i>00:00:00</i>" },
                { data: 'total_jam', "defaultContent": "<i>00:00:00</i>" },
                { data: 'total_hm', 
                    render: function( data, type, full ) {
                    return formatNumber(parseFloat(data).toFixed(2)); }
                },
                { data: 'total_helper', "defaultContent": "<i>-</i>" },
                { data: 'premi_perjam', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_libur', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'total_insentif', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'luar_kota', 
                    render: function( data, type, full ) {
                    return formatStat2(data); }
                },
            ]
        });
    });

    function formatNumber(m) {
        if(m == null){
            return '0';
        }else{
            return m.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
    }

    function formatStat(n) {
        if(n == '0'){
            var stat = "<span style='color:#030100'><b>Tidak</b></span>";
        }else if(n == '1'){
            var stat = "<span style='color:#0eab25'><b>Ya</b></span>";
        }
        return stat;
    }

    function formatStat2(n) {
        if(n == '0'){
            var stat = "<span style='color:#030100'><b>Tidak</b></span>";
        }else if(n == '1'){
            var stat = "<span style='color:#0eab25'><b>Ya</b></span>";
        }
        return stat;
    }

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
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('#NamaAlat1').val('');
                    $('#AsetAlat1').val('');
                }else{
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#data2-table').DataTable().row(select).data();

                    closeOpenedRows(table, select);

                    var no_insentif = data['no_insentif'];
                    var id = data['id'];
                    $('.hapus-button').show();
                    $('.edit-button').hide();
                    $.ajax({
                        url: '{!! route('insentifoperatordetail.show_alat') !!}',
                        type: 'POST',
                        data : {
                            'id': id,
                        },
                        success: function(results) {
                            console.log(results);
                            $('#NamaAlat1').val(results.nama_alat);
                            $('#AsetAlat1').val(results.type);
                        }
                    });
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
            
            $('#hapusinsentif').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var no_ts = data['no_timesheet'];
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
                            url: '{!! route('insentifoperatordetail.hapusdetail') !!}',
                            type: 'POST',
                            data : {
                                'no_ts': no_ts
                            },

                            success: function (results) {
                                swal("Berhasil!", results.message, "success");
                                refreshTable();
                            }
                        });
                    }
                });
            });
            
        });

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

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
        }
    </script>
@endpush