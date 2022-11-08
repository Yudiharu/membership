@extends('adminlte::page')

@section('title', 'Kegiatan')

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
                    @permission('create-customer')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Kegiatan</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>KEGIATAN</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>id</th>
                        <th>Description</th>
                        <th width="10%">CFS</th>
                        <th width="10%">Container</th>
                        <th width="10%">Lainnya</th>
                        <th>Coa</th>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Nama customer', 'Deskripsi:') }}
                            {{ Form::text('description', null, ['class'=> 'form-control','id'=>'Desc1','required'=>'required', 'placeholder'=>'Nama Kegiatan','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('alamat', 'COA:') }}
                            {{ Form::select('kode_coa', $Coa->sort(), null, ['class'=> 'form-control select2','id'=>'Coa1', 'placeholder'=>'','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('alamat', 'Tipe Kegiatan:') }}<br>
                            <input type="hidden" name="container" value="0" />
                            <input type="hidden" name="cfs" value="0" />
                            <input type="hidden" name="lainnya" value="0" />
                            <input type="checkbox" name="cfs" id="cfs1" value="1" onclick="cekbokcfs()" />&nbsp;CFS&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="container" id="cont1" value="1" onclick="cekbokcont()"/>&nbsp;Container&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="lainnya" id="lain1" value="1" onclick="cekboklain()"/>&nbsp;Lainnya
                            <!-- <input type="checkbox" name="non_container" id="noncont1" value="1"/>&nbsp;Non Container<br>
                            <input type="checkbox" name="cfs" id="cfs1" value="1"/>&nbsp;CFS -->
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
                    {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'Kode','readonly']) }}
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('Nama customer', 'Deskripsi:') }}
                                {{ Form::text('description', null, ['class'=> 'form-control','id'=>'Desc2','required'=>'required', 'placeholder'=>'Nama Kegiatan','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('alamat', 'COA:') }}
                                {{ Form::select('kode_coa', $Coa->sort(), null, ['class'=> 'form-control select2','id'=>'Coa2', 'placeholder'=>'','style'=>'width: 100%']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('alamat', 'Tipe Kegiatan:') }}<br>
                                <input type="hidden" name="container" value="0" />
                                <input type="hidden" name="lainnya" value="0" />
                                <input type="hidden" name="cfs" value="0" />
                                <input type="checkbox" name="cfs" id="cfs2" value="1" onclick="cekbokcfs2()" />&nbsp;CFS&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="container" id="cont2" value="1" onclick="cekbokcont2()"/>&nbsp;Container&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="lainnya" id="lain2" value="1" onclick="cekboklain2()"/>&nbsp;Lainnya
                                <!-- <input type="checkbox" name="non_container" id="noncont2" value="1"/>&nbsp;Non Container<br>
                                <input type="checkbox" name="cfs" id="cfs2" value="1"/>&nbsp;CFS -->
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

            .add-button {
                background-color: #00FF00;
                bottom: 246px;
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
            <!-- <a href="#" id="addtarif"><button type="button" class="btn btn-xs add-button" data-toggle="modal" data-target="">ADD TARIF <i class="fa fa-plus"></i></button></a> -->

            @permission('update-customer')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editkegiatan" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-customer')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuskegiatan" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.edit-button').hide();
            $('.hapus-button').hide();
            $('.add-button').hide();
            $('.tombol1').hide();
            $('.tombol2').hide();
            $('.back2Top').show();
        }

        function cekbokcont()
        {
            document.getElementById("cfs1").checked = false;
            document.getElementById("lain1").checked = false;
            document.getElementById("cont1").checked = true;
        }

        function cekbokcfs()
        {
            document.getElementById("cfs1").checked = true;
            document.getElementById("lain1").checked = false;
            document.getElementById("cont1").checked = false;
        }

        function cekboklain()
        {
            document.getElementById("cfs1").checked = false;
            document.getElementById("lain1").checked = true;
            document.getElementById("cont1").checked = false;
        }

        function cekbokcont2()
        {
            document.getElementById("cfs2").checked = false;
            document.getElementById("lain2").checked = false;
            document.getElementById("cont2").checked = true;
        }

        function cekbokcfs2()
        {
            document.getElementById("cfs2").checked = true;
            document.getElementById("lain2").checked = false;
            document.getElementById("cont2").checked = false;
        }

        function cekboklain2()
        {
            document.getElementById("cfs2").checked = false;
            document.getElementById("lain2").checked = true;
            document.getElementById("cont2").checked = false;
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
                ajax: '{!! route('kegiatan.data') !!}',
                columns: [
                    { data: 'id', name: 'id', visible: false },
                    { data: 'description', name: 'description' },
                    { data: 'cfs',  
                        render: function( data, type, full ) {
                        return format_type(data); }, sortable: false
                    },
                    { data: 'container',  
                        render: function( data, type, full ) {
                        return format_type(data); }, sortable: false
                    }, 
                    { data: 'lainnya',  
                        render: function( data, type, full ) {
                        return format_type(data); }, sortable: false
                    }, 
                    { data: 'coa.account', 
                        render: function ( data, type, row ) {
                        return row.coa.account + ' - ' + row.coa.ac_description;
                    }, searchable : false },
                ]
            });
        });

        function format_type(n) {
            if(n == '1'){
                var stat = "<span style='color:#0eab25'><b>✔</b></span>";
            }else{
                var stat = "<span style='color:#c91a1a'><b>✘</b></span>";
            }
            return stat;
        }

        $(document).ready(function(){
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });
            
            $('[data-toggle="tooltip"]').tooltip();   

            $("input[name='npwp']").on("keyup change", function(){
            $("input[name='number']").val(destroyMask(this.value));
                this.value = createMask($("input[name='number']").val());
            })

            $("input[name='hp']").on("keyup change", function(){
            $("input[name='number1']").val(destroyMask2(this.value));
                this.value = createMask2($("input[name='number1']").val());
            })

            $("input[name='fax']").on("keyup change", function(){
            $("input[name='numberfax']").val(destroyMask3(this.value));
                this.value = createMask3($("input[name='numberfax']").val());
            })

            $("input[name='telp']").on("keyup change", function(){
            $("input[name='numbertelp']").val(destroyMask4(this.value));
                this.value = createMask4($("input[name='numbertelp']").val());
            })

            $("input[name='contact_pic']").on("keyup change", function(){
            $("input[name='numberpic']").val(destroyMask5(this.value));
                this.value = createMask5($("input[name='numberpic']").val());
            })

            function createMask(string){
                return string.replace(/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/,"$1.$2.$3.$4-$5.$6");
            }

            function destroyMask(string){
                return string.replace(/\D/g,'').substring(0,15);
            }

            function createMask2(string){
                return string.replace(/(\d{4})(\d{4})(\d{4})/,"($1)-$2-$3");
            }

            function destroyMask2(string){
                return string.replace(/\D/g,'').substring(0,12);
            }

            function createMask3(string){
                return string.replace(/(\d{4})(\d{6})/,"($1)-$2");
            }

            function destroyMask3(string){
                return string.replace(/\D/g,'').substring(0,10);
            }

            function createMask4(string){
                return string.replace(/(\d{4})(\d{6})/,"($1)-$2");
            }

            function destroyMask4(string){
                return string.replace(/\D/g,'').substring(0,10);
            }

            function createMask5(string){
                return string.replace(/(\d{4})(\d{4})(\d{4})/,"($1)-$2-$3");
            }

            function destroyMask5(string){
                return string.replace(/\D/g,'').substring(0,12);
            }

            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.edit-button').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data-table').DataTable().row(select).data();
                    var id_kegiatan = data['id'];
                    var add = $("#addtarif").attr("href","http://localhost/gui_front_pbm_laravel/admin/kegiatan/"+id_kegiatan+"/detail");
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    $('.add-button').show();
                }
            });

            $('#editkegiatan').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var id = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('kegiatan.edit_kegiatan') !!}',
                    type: 'POST',
                    data : {
                        'id': id
                    },
                    success: function(results) {
                        console.log(results);
                        if (results.container == 1) {
                            document.getElementById("cont2").checked = true;
                            document.getElementById("lain2").checked = false;
                            document.getElementById("cfs2").checked = false;
                        }else if (results.cfs == 1) {
                            document.getElementById("cont2").checked = false;
                            document.getElementById("lain2").checked = false;
                            document.getElementById("cfs2").checked = true;
                        }else if (results.lainnya == 1) {
                            document.getElementById("cont2").checked = false;
                            document.getElementById("lain2").checked = true;
                            document.getElementById("cfs2").checked = false;
                        }
                        $('#Kode').val(results.id);
                        $('#Desc2').val(results.description);
                        $('#Coa2').val(results.kode_coa).trigger('change');
                        $('#editform').modal('show');
                    }
                });
            });

            $('#hapuskegiatan').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
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
                            url: '{!! route('kegiatan.hapus_kegiatan') !!}',
                            type: 'POST',
                            data : {
                                'id': id
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
             $('#data-table').DataTable().ajax.reload(null,false);;
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
                    url:'{!! route('kegiatan.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Desc1').val('');
                        document.getElementById("cont1").checked = false;
                        $('#Coa1').val('').trigger('change');
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
                    url:'{!! route('kegiatan.ajaxupdate') !!}',
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