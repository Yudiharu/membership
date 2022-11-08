@extends('adminlte::page')

@section('title', 'Tarif Umum')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#historybiaya">
<i class="fa fa-address-book"></i> History Biaya</button>
@include('sweet::alert')
<body onLoad="load()">
<div class="box box-danger" style="width: 100%; height: 30%;">
    <div class="box-body">
        <div class="addform">
            @include('errors.validation')
            {!! Form::open(['id'=>'ADD_DETAIL']) !!}
            <center><b><dialog open>TARIF BIAYA UMUM</dialog></b></center><br><br><br><br>
            <div class="row">
            <?php if ($Tarif != null) { ?>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                        {{ Form::date('tgl_berlaku', $Tarif->tgl_berlaku,['class'=> 'form-control','id'=>'Tgl1','required'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Admin:') }}
                        {{ Form::text('biaya_admin', $Tarif->biaya_admin,['class'=> 'form-control','id'=>'Admin1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Pass Tongkang:') }}
                        {{ Form::text('biaya_pass_tongkang', $Tarif->biaya_pass_tongkang,['class'=> 'form-control','id'=>'Tongkang1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Pass Truck:') }}
                        {{ Form::text('biaya_pass_truck', $Tarif->biaya_pass_truck,['class'=> 'form-control','id'=>'Truck1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Penumpukan CFS:') }}
                        {{ Form::text('biaya_penumpukan_cfs', $Tarif->biaya_penumpukan_cfs,['class'=> 'form-control','id'=>'Cfs1'])}}
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                        {{ Form::date('tgl_berlaku', null,['class'=> 'form-control','id'=>'Tgl1','required'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Admin:') }}
                        {{ Form::text('biaya_admin', null,['class'=> 'form-control','id'=>'Admin1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Pass Tongkang:') }}
                        {{ Form::text('biaya_pass_tongkang', null,['class'=> 'form-control','id'=>'Tongkang1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Pass Truck:') }}
                        {{ Form::text('biaya_pass_truck', null,['class'=> 'form-control','id'=>'Truck1'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Biaya', 'Biaya Penumpukan CFS:') }}
                        {{ Form::text('biaya_penumpukan_cfs', null,['class'=> 'form-control','id'=>'Cfs1']) }}
                    </div>
                </div>
            <?php } ?>
                <div class="col-md-2">
                    <br>
                    {{ Form::submit('➕ SIMPAN/UPDATE', ['class' => 'btn btn-success btn-sm','style'=>'height: 40px;','id'=>'submit']) }}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="col-md-12" id="tabelbiaya2">
   <div class="box box-danger">
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    {{ Form::label('Biaya', 'Daftar Biaya Umum:') }}
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="data3-table" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr class="bg-danger">
                                    <th>id</th>
                                    <th>Tgl Berlaku</th>
                                    <th>Biaya Admin</th>
                                    <th>Biaya Pass Tongkang</th>
                                    <th>Biaya Pass Truck</th>
                                    <th>Biaya Penumpukan CFS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="back2Top btn btn-warning btn-xs" id="back2Top"><i class="fa fa-arrow-up" style="color: #fff"></i> <i>{{ $nama_company }}</i> <b>({{ $nama_lokasi }})</b></button>
</div>

<div class="modal fade" id="historybiaya" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style=" height: 1%; border: none">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="box-body"> 
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('labels', 'History Biaya Umum:') }}
                        </div>
                    </div>                
                </div>
                <div class="container-fluid table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr class="bg-warning">
                                <th>id</th>
                                <th>Tgl Berlaku</th>
                                <th>Biaya Admin</th>
                                <th>Biaya Pass Tongkang</th>
                                <th>Biaya Pass Truck</th>
                                <th>Biaya Penumpukan CFS</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="modal-footer">
                            
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
            bottom: 120px;
        }

        .add-button {
            background-color: #00E0FF;
            bottom: 126px;
        }

        .edit-button {
            background-color: #FDA900;
            bottom: 150px;
        }

        .view-button {
            background-color: #149933;
            bottom: 156px;
        }

        #mySidenav button {
            position: fixed;
            right: -40px;
            transition: 0.3s;
            padding: 4px 8px;
            width: 120px;
            text-decoration: blink;
            font-size: 12px;
            color: white;
            border-radius: 15px 5px 5px 15px;
            opacity: 0.7;
            cursor: pointer;
            text-align: left;
        }

        #mySidenav button:hover {
            right: 0;
            opacity: 1.0;
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
        <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuscfs" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>

        <button type="button" class="btn btn-warning btn-xs edit-button" id="editcfs" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
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
            $('.edit-button').hide();
            $('.hapus-button').hide();
            $('.tambahalat').hide();
            $('.tambahsize').hide();
            $('.editbutton').hide();
            $('.hapusbutton').hide();
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
            $('#data2-table').DataTable({
                processing: true,
                serverSide: true,
                "lengthChange": false,
                "pageLength": 5,
                "bFilter": false,
                "bPaginate": true,
                // "scrollY": 160,
                ajax:'{!! route('tarifumum.getDatabyID') !!}',
                columns: [
                    { data: 'id', name: 'id', visible: false},
                    { data: 'tgl_berlaku', name: 'tgl_berlaku' },
                    { data: 'biaya_admin', name: 'biaya_admin' },
                    { data: 'biaya_pass_tongkang', name: 'biaya_pass_tongkang' },
                    { data: 'biaya_pass_truck', name: 'biaya_pass_truck' },
                    { data: 'biaya_penumpukan_cfs', name: 'biaya_penumpukan_cfs' },
                ]
            });

            $('#data3-table').DataTable({
                processing: true,
                serverSide: true,
                "lengthChange": false,
                "bFilter": false,
                "bPaginate": false,
                "scrollY": 160,
                ajax:'{!! route('tarifumum.getDatabyID2') !!}',
                columns: [
                    { data: 'id', name: 'id', visible: false},
                    { data: 'tgl_berlaku', name: 'tgl_berlaku' },
                    { data: 'biaya_admin', name: 'biaya_admin' },
                    { data: 'biaya_pass_tongkang', name: 'biaya_pass_tongkang' },
                    { data: 'biaya_pass_truck', name: 'biaya_pass_truck' },
                    { data: 'biaya_penumpukan_cfs', name: 'biaya_penumpukan_cfs' },
                ]
            });
        });

        function format_type(n) {
            if(n == '1'){
                    var stat = "<span style='color:#0eab25'><b>SW</b></span>";
            }else if (n == '2'){
                    var stat = "<span style='color:#c91a1a'><b>MB</b></span>";
            }
            return stat;
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
            placeholder: "Pilih Alat",
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
             $('#data3-table').DataTable().ajax.reload(null,false);;
        }

        $(document).ready(function() {
            var table = $('#data3-table').DataTable();
            
            $('#data3-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-black text-bold') ) {
                    $(this).removeClass('selected bg-black text-bold');
                    $('.hapus-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-black text-bold');
                    $(this).addClass('selected bg-black text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data3-table').DataTable().row(select).data();
                    closeOpenedRows(table, select);
                    var id = data['id'];
                    $('.hapus-button').show();
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
        });

        $('#ADD_DETAIL').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_DETAIL");
            var formData = registerForm.serialize();
            // Check if empty of not
            $.ajax({
                url:'{!! route('tarifumum.store_tarif') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#Tgl1').val('');
                    $('#Admin1').val('');
                    $('#Tongkang1').val('');
                    $('#Truck1').val('');
                    refreshTable();
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal(data.title, data.message, "error");
                    }
                },
            });
        });

        $('#hapuscfs').click( function () {
            var select = $('.selected').closest('tr');
            var data = $('#data3-table').DataTable().row(select).data();
            var id = data['id'];
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
                        url: '{!! route('tarifumum.hapus_tarif') !!}',
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
                            $('.hapus-button').hide();
                        }
                    });
                }
            });
        });


        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
        }
    </script>
@endpush