@extends('adminlte::page')

@section('title', 'Gudang Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="tablefresh()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Gudang <b>{{$gudang->kode_shipper}}</b></font>
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
                                {{ Form::hidden('kode_shipper',$gudang->kode_shipper, ['class'=> 'form-control','readonly','id'=>'kodeshipper']) }}

                                <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('nama_customer', 'Nama Shipper:') }}
                                        {{ Form::text('nama_customer',$gudang->customer->nama_customer, ['class'=> 'form-control','readonly','id'=>'namacustomer']) }}
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('nama_gudang', 'Nama Gudang:') }}
                                        {{ Form::text('nama_gudang',null,
                                         ['class'=> 'form-control','style'=>'width: 100%','placeholder' => 'Nama Gudang','required'=>'required','id'=>'namagudang', 'onkeypress'=>"return pulsar(event,this)",'autocomplete'=>'off']) }}
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
                                
                                {{ Form::hidden('id',null, ['class'=> 'form-control','readonly','id'=>'ID','readonly']) }}
                                    
                                {{ Form::hidden('kode_shipper',$gudang->kode_shipper, ['class'=> 'form-control','readonly','id'=>'kodeshipper2']) }}
                                
                                {{ Form::hidden('kode_gudang',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => 'Nama Gudang','required'=>'required','id'=>'kodegudang2','readonly']) }}
                                
                                <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('nama_customer', 'Nama Shipper:') }}
                                        {{ Form::text('nama_customer',$gudang->customer->nama_customer, ['class'=> 'form-control','readonly','id'=>'namacust']) }}
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        {{ Form::label('nama_gudang', 'Nama Gudang:') }}
                                        {{ Form::text('nama_gudang',null,
                                         ['class'=> 'form-control','style'=>'width: 100%','placeholder' => 'Nama Gudang','required'=>'required','id'=>'namagudang2', 'onkeypress'=>"return pulsar(event,this)",'autocomplete'=>'off']) }}
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

<div class="modal fade" id="addtarifform" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="box-body"> 
            <div class="addform">
                @include('errors.validation')
                {!! Form::open(['id'=>'ADD_TARIF']) !!}
                    {{ Form::hidden('kode_gudang',null, ['class'=> 'form-control','readonly','id'=>'kodegudang4']) }}
                                        
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('nama_gudang', 'Nama Gudang:') }}
                            {{ Form::text('nama_gudang',null,['class'=> 'form-control','style'=>'width: 100%','id'=>'namagudang4', 'readonly']) }}
                        </div>
                    </div>

                    {{ Form::hidden('kode_shipper',null, ['class'=> 'form-control','readonly','id'=>'kodeshipper4']) }}
                                        
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('nama_shipper', 'Nama Shipper:') }}
                            {{ Form::text('nama_shipper',$gudang->customer->nama_customer, ['class'=> 'form-control','style'=>'width: 100%','id'=>'namashipper4', 'readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('tanggal_berlaku', 'Tanggal Berlaku:') }}
                            {{ Form::date('tanggal_berlaku',null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'tanggal4','required']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('tarif_trucking', 'Tarif Trucking:') }}
                            {{ Form::text('tarif_trucking',0, ['class'=> 'form-control','style'=>'width: 100%','required'=>'required','id'=>'tarif4']) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="pull-right"> 
                            {{ Form::submit('Add Item', ['class' => 'btn btn-success btn-xs','id'=>'submit']) }}  
                            <!-- <button type="button" class="btn btn-warning btn-xs editbutton" id="edittarif" data-toggle="modal" data-target="">
                                <i class="fa fa-edit"></i> EDIT
                            </button> -->
                            @permission('delete-tarif')
                            <button type="button" class="btn btn-danger btn-xs hapusbutton" id="hapustarif">
                                <i class="fa fa-times-circle"></i> HAPUS
                            </button>
                            @endpermission
                        </span>    
                    </div>                    
                {!! Form::close() !!}
            </div>
        </div>

        <div class="container-fluid">
            <table class="table table-bordered table-striped table-hover" id="addtarif-table" width="100%" style="font-size: 12px;">
                <thead>
                    <tr class="bg-warning">
                        <th>Kode Gudang</th>
                        <th>Kode Shipper</th>
                        <th>Tarif Trucking</th>
                        <th>Tanggal Berlaku</th>
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
                    <tr class="bg-danger">
                        <th>Kode Shipper</th>
                        <th>Kode Gudang</th>
                        <th>Nama Gudang</th>
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
              right: -30px;
              transition: 0.3s;
              padding: 4px 8px;
              width: 80px;
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
            @permission('add-gudang')
            <button type="button" class="btn btn-info btn-xs add-button" id="addtarifbutton" data-toggle="modal" data-target="#addtarifform"><i class="fa fa-plus"></i> ADD TARIF</button>
            @endpermission

            @permission('update-gudang')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editgudangdetail" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-gudang')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusgudangdetail" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.tarifform').hide();
            $('.back2Top').show();
            $('.add-button').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
        }
        
        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        function tabletarif(kode){
        $.ajax({
            url: '{!! route('gudangdetail.getDatatarif') !!}',
            type: 'GET',
            data : {
                'id': kode
            },
            success: function(result) {
                Table2.clear().draw();
                Table2.rows.add(result).draw();
    
                $('#addtarifform').modal('show');
                $('.editbutton').hide();
                $('.hapusbutton').hide();
            }
        });
    }

        function createTable2(result){

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.tanggal_berlaku+"</td>";
                    my_table += "<td>"+formatRupiah(row.tarif_trucking)+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered table-hover" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Tanggal Berlaku</th>'+
                                '<th>Tarif Trucking</th>'+
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
        var kode_shipper = $('#kodeshipper').val();
        
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:'http://localhost/gui_front_pbm_laravel/admin/gudangdetail/getDatabyID?id='+kode_shipper,
            data:{'kode_shipper':kode_shipper},

            columns: [
                { data: 'kode_shipper', name: 'kode_shipper', visible: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'nama_gudang', name: 'nama_gudang' },
            ]
            
        });
        
    });

    Table2 = $("#addtarif-table").DataTable({
        data:[],
        columns: [
            { data: 'kode_gudang', name: 'kode_gudang', visible: false },
            { data: 'kode_shipper', name: 'kode_shipper', visible: false },
            { data: 'tarif_trucking', 
                render: function( data, type, full ) {
                return formatNumber2(data); }
            },
            { data: 'tanggal_berlaku', name: 'tanggal_berlaku' },     
        ],      
    });


    function formatNumber2(m) {
        if(m == null){
            return '';
        }else{
            return m.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
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
                }
                else {
                    $('#tarif4').val('');
                    $('#tanggal4').val('');

                    table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');

                    closeOpenedRows(table, select);

                    var data = $('#data2-table').DataTable().row(select).data();
                    var kode_shipper = data['kode_shipper'];
                    var kode_gudang = data['id'];
                    $('.add-button').show();
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    $('.view-button').show();
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

            var table2 = $('#addtarif-table').DataTable();

            $('#addtarif-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.editbutton').hide();
                    $('.hapusbutton').hide();
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();

                    $('#tarif4').val('');
                    $('#tanggal4').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    $('.editbutton').show();
                    $('.hapusbutton').show();
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();

                    var nama_gudang = select.find('td:eq(0)').text();
                    var data = $('#data2-table').DataTable().row(select).data();
                    var kode_shipper = data['kode_shipper'];
                    var kode_gudang = data['id'];

                    var data = $('#addtarif-table').DataTable().row(select).data();
                    var tarif_trucking = data['tarif_trucking'];
                    var tanggal_berlaku = data['tanggal_berlaku'];

                    $('#kodegudang4').val(kode_gudang);
                    $('#kodeshipper4').val(kode_shipper);
                    $('#tanggal4').val(tanggal_berlaku);
                    $('#tarif4').val(tarif_trucking);
                }
            });

            $('#editgudangdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var kode_shipper = data['kode_shipper'];
                var kode_gudang = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('gudangdetail.edit_gudangdetail') !!}',
                    type: 'POST',
                    data : {
                        'kode_shipper': kode_shipper,
                        'kode_gudang': kode_gudang,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#kodegudang2').val(results.kode_gudang);
                        $('#kodeshipper2').val(results.kode_shipper);
                        $('#namagudang2').val(results.nama_gudang);
                        $('#tarif2').val(results.tarif_trucking);
                        $('#ID').val(results.id);
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapusgudangdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var kode_shipper = data['kode_shipper'];
                var kode_gudang = data['id'];
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
                            url: '{!! route('gudangdetail.hapus_gudangdetail') !!}',
                            type: 'POST',
                            data : {
                                'kode_shipper': kode_shipper,
                                'kode_gudang': kode_gudang,
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

            $('#hapustarif').click( function () {
                table.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var kodegudang = $.trim($('#kodegudang4').val());
                var select = $('.selected').closest('tr');

                var data = $('#data2-table').DataTable().row(select).data();
                var kode_shipper = data['kode_shipper'];
                var kode_gudang = data['id'];
                var tanggal_berlaku = select.find('td:eq(1)').text();
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
                            url: '{!! route('gudangdetail.hapus_tarifdetail') !!}',
                            type: 'POST',
                            data : {
                                'kode_gudang': kode_gudang,
                                'kode_shipper': kode_shipper,
                                'tanggal_berlaku': tanggal_berlaku
                            },

                            success: function (results) {
                                $('#tarif4').val('');
                                $('#tanggal4').val('');
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                refreshTable();
                                tabletarif(kodegudang);
                            }
                        });
                    }
                });
            });

            $('#addtarifbutton').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var kode_shipper = data['kode_shipper'];
                var kode_gudang = data['id'];
                var nama_gudang = select.find('td:eq(0)').text();
                $.ajax({
                    url: '{!! route('gudangdetail.getDatatarif') !!}',
                    type: 'GET',
                    data : {
                        'id': kode_gudang
                    },
                    success: function(result) {

                        Table2.clear().draw();
                        Table2.rows.add(result).draw();
                
                        $('#addtarifform').modal('show');
                        $('#kodegudang4').val(kode_gudang);    
                        $('#namagudang4').val(nama_gudang);   
                        $('#kodeshipper4').val(kode_shipper);                   
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

        function tablefresh(){
                window.table.draw();
            } 

        function refreshTable() {
             $('#data2-table').DataTable().ajax.reload(null,false);;
        }

        $('#ADD_DETAIL').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
            })
            e.preventDefault();
            var registerForm = $("#ADD_DETAIL");
            var formData = registerForm.serialize();

            // Check if empty of not
            $.ajax({
                    url:'{!! route('gudangdetail.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#namagudang').val('');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
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
                    url:'{!! route('gudangdetail.updateajax') !!}',
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
                        $(".tarifform").hide();
                    
                    },
                });
            
        });
               
        function edit(id, url) {
           
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

               $.ajax({
                    type: 'GET',
                    url: url,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        $(".editform").show();
                        $('#kodegudang2').val(results.kode_gudang);
                        $('#kodeshipper2').val(results.kode_shipper);
                        $('#namagudang2').val(results.nama_gudang);
                        $('#tarif2').val(results.tarif_trucking);
                        $('#ID').val(results.id);
                        $(".addform").hide();
                        $(".tarifform").hide();
                       },
                        error : function() {
                        alert("Nothing Data");
                    }
                });
                     
        }

        function tarif(id, url) {
           
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

               $.ajax({
                    type: 'GET',
                    url: url,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        $(".tarifform").show();
                        $('#kodegudang3').val(results.kode_gudang);
                        $('#kodeshipper3').val(results.kode_shipper);
                        $('#tarif3').val(results.tarif_trucking);
                        $('#tanggal3').val(results.tanggal_berlaku);
                        $('#ID3').val(results.id);
                        $(".addform").hide();
                        $(".editform").hide();
                       },
                        error : function() {
                        alert("Nothing Data");
                    }
                });
                     
        }

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
            $(".tarifform").hide();
        }

        function edit_tarif(){
            var kode_gudang = $('#kodegudang3').val();
            var kode_shipper = $('#kodeshipper3').val();
            var add = $("#viewtarif").attr("href","http://localhost/gui_front_pbm_laravel/admin/gudang/"+kode_gudang+kode_shipper+"/detail2");
        }

        function del(id, url) {
            swal({
            title: "Hapus?",
            text: "Pastikan dulu data yang akan dihapus!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Ya, Hapus!",
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

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    
                    success: function (results) {
                    console.log(results);
                        refreshTable();
                            if (results.success === true) {
                                swal("Berhasil!", results.message, "success");
                                
                            } else {
                                swal("Gagal!", results.message, "error");
                            }
                        }
                });
            }
            });
        }

        $('#ADD_TARIF').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
            })
            e.preventDefault();
            var kode_gudang = $.trim($('#kodegudang4').val());
            var registerForm = $("#ADD_TARIF");
            var formData = registerForm.serialize();

            // Check if empty of not
            $.ajax({
                url:'{!! route('gudangdetail.store2') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#tarif4').val('');
                    $('#tanggal4').val('');
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal("Gagal!", data.message, "error");
                    }
                    refreshTable();
                    tabletarif(kode_gudang);
                },
            });
        });
    </script>
@endpush