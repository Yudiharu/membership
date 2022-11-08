@extends('adminlte::page')

@section('title', 'Job Order Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
    <?php if ($joborder->type_jo == '4') { ?>
        <a href="detail2" id="addjor"><button type="button" class="btn bg-black btn-xs add2-button" data-toggle="modal" data-target="">JOB REQUEST <i class="fa fa-plus"></i></button></a>
    <?php } ?>
        <font style="font-size: 16px;"> Job Order <b>{{$joborder->no_joborder}}</b></font>
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
                            {{ Form::label('no_joborder', 'No Job Order:') }}
                            {{ Form::text('no_joborder',$joborder->no_joborder, ['class'=> 'form-control','readonly','id'=>'nojoborder']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('desc', 'Description:') }}
                            {{ Form::textarea('deskripsi',null, ['class'=> 'form-control','id'=>'Desc1','rows'=>'3','required','autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            {{ Form::label('qtys', 'Qty:') }}
                            {{ Form::text('qty',null, ['class'=> 'form-control','id'=>'Qty1','required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Sats', 'Satuan:') }}
                            {{ Form::select('satuan', $Satuan, null, ['class'=> 'form-control select2','id'=>'Satuan1','required','autocomplete'=>'off','style'=>'width: 100%','placeholder'=>'']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Hargs', 'Harga:') }}
                            {{ Form::text('harga',null, ['class'=> 'form-control','id'=>'Harga1','required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Mobs', 'Mob Demob:') }}
                            {{ Form::text('mob_demob',0, ['class'=> 'form-control','id'=>'Mob1','autocomplete'=>'off']) }}
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
                    {{ Form::hidden('id',null, ['class'=> 'form-control','id'=>'ID','readonly']) }}
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('no_joborder', 'No Job Order:') }}
                            {{ Form::text('no_joborder',$joborder->no_joborder, ['class'=> 'form-control','readonly','id'=>'nojoborder2']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('desc', 'Description:') }}
                            {{ Form::textarea('deskripsi',null, ['class'=> 'form-control','id'=>'Desc2','rows'=>'3','required','autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            {{ Form::label('qtys', 'Qty:') }}
                            {{ Form::text('qty',null, ['class'=> 'form-control','id'=>'Qty2','required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Sats', 'Satuan:') }}
                            {{ Form::select('satuan', $Satuan, null, ['class'=> 'form-control select2','id'=>'Satuan2','required','autocomplete'=>'off','style'=>'width: 100%','placeholder'=>'']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Hargs', 'Harga:') }}
                            {{ Form::text('harga',null, ['class'=> 'form-control','id'=>'Harga2','required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Mobs', 'Mob Demob:') }}
                            {{ Form::text('mob_demob',0, ['class'=> 'form-control','id'=>'Mob2','autocomplete'=>'off']) }}
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

   <div class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>id</th>
                        <th>No Jo</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Mob Demob</th>
                        <th>Total Harga</th>
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
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusjoborderdetail" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
        var no_joborder = $('#nojoborder').val();
        var link = $('#Link1').val();
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:link+'/gui_front_02/admin/joborderdetail/getDatabyID?id='+no_joborder,
            data:{'no_joborder':no_joborder},
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'no_joborder', name: 'no_joborder', visible: false },
                { data: 'deskripsi', name: 'deskripsi' },
                { data: 'qty',  
                    render: function( data, type, full ) {
                    return parseFloat(data).toFixed(3); }
                },
                { data: 'satuan', name: 'satuan' },
                { data: 'harga',  
                    render: function( data, type, full ) {
                    return formatNumber(parseFloat(data).toFixed(2)); }
                },
                { data: 'mob_demob',
                    render: function( data, type, full ) {
                    return formatNumber(parseFloat(data).toFixed(2)); }
                },
                { data: 'total_harga', 
                    render: function( data, type, full ) {
                    return formatNumber(parseFloat(data).toFixed(2)); }
                },
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
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                }else{
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#data2-table').DataTable().row(select).data();

                    closeOpenedRows(table, select);

                    // var link = $('#Link1').val();

                    var no_joborder = data['no_joborder'];
                    var id = data['id'];

                    // var add2 = $("#addjor").attr("href",link+"/gui_front_02/admin/joborder/"+no_joborder+"/detail2");
                    
                    $('.hapus-button').show();
                    $('.edit-button').show();
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

            $('#hapusjo').click( function () {
                table.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var no_jo = $.trim($('#nojoborder3').val());
                var no_jor = $.trim($('#noreqjo').val());
                var select = $('.selected').closest('tr');
                var no_joborder = select.find('td:eq(0)').text();
                var no_req_jo = select.find('td:eq(1)').text();
                var kode_container = select.find('td:eq(2)').text();
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
                            url: '{!! route('joborderdetail.hapus_noreqjo') !!}',
                            type: 'POST',
                            data : {
                                'no_joborder': no_joborder,
                                'no_req_jo': no_req_jo,
                                'kode_container': kode_container,
                            },

                            success: function (results) {
                                $('#nocontainer').val('');
                                $('#kodesize').val('').trigger('change');
                                $('#statusmuatan').val('').trigger('change');
                                $('#dari').val('');
                                $('#tujuan').val('');
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                document.getElementById("nocontainer").readOnly = false;
                                refreshTable();
                                tablejor(no_jor);
                            }
                        });
                    }
                });
            });

            $('#editjo').click( function () {
                table.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var no_jo = $.trim($('#nojoborder3').val());
                var no_jor = $.trim($('#noreqjo').val());
                var select = $('.selected').closest('tr');

                var no_jo = $('#nojoborder3').val();
                var no_req_jo = $('#noreqjo').val();
                var kode_container = $('#nocontainer').val();
                var size = $('#kodesize').val();
                var status = $('#statusmuatan').val();
                var dari = $('#dari').val();
                var tujuan = $('#tujuan').val();
                var row = table2.row( select );
                
                $.ajax({
                    url: '{!! route('joborderdetail.edit_noreqjo') !!}',
                    type: 'POST',
                    data : {
                        'no_jo': no_jo,
                        'no_req_jo': no_req_jo,
                        'kode_container': kode_container,
                        'size': size,
                        'status': status,
                        'dari': dari,
                        'tujuan': tujuan,
                    },

                    success: function (results) {
                        $('#nocontainer').val('');
                        $('#kodesize').val('').trigger('change');
                        $('#statusmuatan').val('').trigger('change');
                        $('#dari').val('');
                        $('#tujuan').val('');
                        if (results.success === true) {
                            swal("Berhasil!", results.message, "success");
                        } else {
                            swal("Gagal!", results.message, "error");
                        }
                        refreshTable();
                        tablejor(no_jor);
                    }
                });
            });

            $('#addjobutton').click( function () {
                var select = $('.selected').closest('tr');
                var no_jo = $('#nojoborder').val();
                var no_jor = select.find('td:eq(1)').text();
                $.ajax({
                    url: '{!! route('joborderdetail.getDatajor') !!}',
                    type: 'GET',
                    data : {
                        'no_jo': no_jo,
                        'id': no_jor,
                    },
                    success: function(result) {
                        console.log(result);

                        Table2.clear().draw();
                        Table2.rows.add(result).draw();
                
                        $('#addjoform').modal('show');
                        $('#nojoborder3').val(no_jo);  
                        $('#noreqjo').val(no_jor);                     
                    }
                });
            });

            $('#editjoborderdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id = data['id'];
                $.ajax({
                    url: '{!! route('joborderdetail.edit_joborderdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#ID').val(results.id);
                        $('#nojoborder2').val(results.no_joborder);
                        $('#Desc2').val(results.deskripsi);
                        $('#Qty2').val(results.qty);
                        $('#Satuan2').val(results.satuan).trigger('change');
                        $('#Harga2').val(results.harga);
                        $('#Mob2').val(results.mob_demob);
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapusjoborderdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id = data['id'];
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
                            url: '{!! route('joborderdetail.hapus_joborderdetail') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
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

        function formatNumber(n) {
            if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function tablejor(kode){
        $.ajax({
            url: '{!! route('joborderdetail.getDatajor') !!}',
            type: 'GET',
            data : {
                'id': kode
            },
            success: function(result) {
                Table2.clear().draw();
                Table2.rows.add(result).draw();
                
                document.getElementById("nocontainer").readOnly = false;
                $('#addjoform').modal('show');
                $('.editbutton').hide();
                $('.hapusbutton').hide();
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
                url:'{!! route('joborderdetail.store') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    $('#Desc1').val('');
                    $('#Qty1').val('');
                    $('#Harga1').val('');
                    $('#Mob1').val('');
                    $('#Satuan1').val('').trigger('change');
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
                    url:'{!! route('joborderdetail.updateajax') !!}',
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