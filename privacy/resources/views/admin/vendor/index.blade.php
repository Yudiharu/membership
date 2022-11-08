@extends('adminlte::page')

@section('title', 'Vendor')

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
                    @permission('create-vendor')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Vendor</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>VENDOR</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>Kode Vendor</th>
                        <th>Nama Vendor</th>
                        <th>Type</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Hp</th>
                        <th>No. Rekening</th>
                        <th>Nama Kontak</th>
                        <th>Npwp</th>
                        <th>No COA</th>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('type', 'Type:') }}
                                    {{Form::select('type', ['1' => 'Vendor', '2' => 'Sopir'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'type1','required','onchange'=>"getcoa();"])}}
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    {{ Form::label('Nama Vendor', 'Nama Vendor:') }}
                                    {{ Form::text('nama_vendor', null, ['class'=> 'form-control','id'=>'Nama1','required'=>'required', 'placeholder'=>'NAMA VENDOR,CV','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: NAMA VENDOR,CV"]) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('Nama Vendor', 'Nama Vendor PO:') }}
                                    {{ Form::text('nama_vendor_po', null, ['class'=> 'form-control','id'=>'Nama2','required'=>'required', 'placeholder'=>'CV. NAMA VENDOR','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: CV. NAMA VENDOR"]) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('alamat', 'Alamat:') }}
                                    {{ Form::textArea('alamat', null, ['class'=> 'form-control','rows'=>'4','id'=>'Alamat1','required'=>'required', 'placeholder'=>'Alamat','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('telp', 'Telp:') }}
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp1', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Hp', 'Hp:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp1', 'placeholder'=>'No. HP','autocomplete'=>'off', 'name'=>'hp']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('norek_vendor', 'No. Rekening:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('norek_vendor', null, ['class'=> 'form-control','id'=>'Norek1', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'name'=>'norek']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Npwp', 'Npwp:') }}
                                    <input type="text" name="number" style="display:none;">
                                    {{ Form::text('npwp', null, ['class'=> 'form-control','id'=>'Npwp1', 'placeholder'=>'NPWP','autocomplete'=>'off', 'name'=>'npwp']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nama_kontak', 'Nama Kontak:') }}
                                    {{ Form::text('nama_kontak', null, ['class'=> 'form-control','id'=>'Kontak1', 'placeholder'=>'Nama Kontak','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status:') }}
                                    {{Form::select('status', ['Aktif' => 'Aktif', 'NonAktif' => 'NonAktif'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Status1','required'=>'required'])}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group2">
                                    {{ Form::label('kode_coa', 'Coa:') }}
                                    {{ Form::select('kode_coa',$Coa->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Coa']) }}
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
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('type', 'Type:') }}
                                {{Form::select('type', ['1' => 'Vendor', '2' => 'Sopir', '3' => 'Pemilik Mobil'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'type','required'=>'required','onchange'=>"getcoa2();"])}}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ Form::label('Nama Vendor', 'Nama Vendor:') }}
                                {{ Form::text('nama_vendor', null, ['class'=> 'form-control','id'=>'Nama','required'=>'required', 'placeholder'=>'Contoh: NAMA VENDOR,CV','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: NAMA VENDOR,CV", 'onkeypress'=>"return pulsar(event,this)",'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('Nama Vendor', 'Nama Vendor PO:') }}
                                    {{ Form::text('nama_vendor_po', null, ['class'=> 'form-control','id'=>'Namapo','required'=>'required', 'placeholder'=>'Contoh: CV. NAMA VENDOR','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: CV. NAMA VENDOR",'readonly']) }}
                                </div>
                            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('alamat', 'Alamat:') }}
                                {{ Form::textArea('alamat', null, ['class'=> 'form-control','rows'=>'4','id'=>'Alamat']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('telp', 'Telp:') }}
                                {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp','onkeypress'=>"return hanyaAngka(event)"]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('Hp', 'Hp:') }}
                                <input type="text" name="number1" style="display:none;">
                                {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp', 'name'=>'hp']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('norek_vendor', 'No. Rekening:') }}
                                <input type="text" name="number1" style="display:none;">
                                {{ Form::text('norek_vendor', null, ['class'=> 'form-control','id'=>'Norek', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'name'=>'norek']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('Npwp', 'Npwp:') }}
                                <input type="text" name="number" style="display:none;">
                                {{ Form::text('npwp', null, ['class'=> 'form-control','id'=>'Npwp','autocomplete'=>'off', 'name'=>'npwp']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('nama_kontak', 'Nama Kontak:') }}
                                {{ Form::text('nama_kontak', null, ['class'=> 'form-control','id'=>'Kontak','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('status', 'Status:') }}
                                {{Form::select('status', ['Aktif' => 'Aktif', 'NonAktif' => 'NonAktif'], null, ['class'=> 'form-control select2','style'=>'width: 100%','id'=>'Status','required'=>'required'])}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group3">
                                {{ Form::label('kode_coa', 'Coa:') }}
                                {{ Form::select('kode_coa',$Coa->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Coa1']) }}
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
            @permission('update-vendor')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editvendor" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-vendor')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusvendor" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.back2Top').show();
            $('.form-group2').hide();
            $('.form-group4').hide();
            $('.form-group3').hide();
            $('.form-group5').hide();
        }

        function getcoa(){
            var type = $('#type1').val();
            $.ajax({
                url: '{!! route('vendor.getcoa') !!}',
                type: 'POST',
                data : {
                    'type': type,
                },
                success: function(results) {
                        $('.form-group2').show();
                        document.getElementById("Coa").required = true;
                }
            });
        }


        function getcoa2(){
            var type = $('#type').val();
            $.ajax({
                url: '{!! route('vendor.getcoa') !!}',
                type: 'POST',
                data : {
                    'type': type,
                },
                success: function(results) {
                        $('.form-group3').show();
                        document.getElementById("Coa1").required = true;
                }
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
            ajax: '{!! route('vendor.data') !!}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'nama_vendor', name: 'nama_vendor' },
                { data: 'type', 
                    render: function( data, type, full ) {
                    return formatType(data); }
                },
                { data: 'alamat', name: 'alamat' },
                { data: 'telp', name: 'telp', "defaultContent": "<i>Not set</i>" },
                { data: 'hp', name: 'hp', "defaultContent": "<i>Not set</i>" },
                { data: 'norek_vendor', name: 'norek_vendor', "defaultContent": "<i>Not set</i>" },
                { data: 'nama_kontak', name: 'nama_kontak', "defaultContent": "<i>Not set</i>" },
                { data: 'npwp', name: 'npwp', "defaultContent": "<i>Not set</i>" },
                { data: 'coa.account', name: 'coa.account', "defaultContent": "<i>Not set</i>", searchable:false },
                { data: 'status', name: 'status' },
            ]
            });
        });

        function formatType(n) {
            if(n == '1'){
                var stat = "<span style='color:#0eab25'><b>Vendor</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#1a80c9'><b>Sopir</b></span>";
            }else{
                var stat = "<span style='color:#1a80c9'><b>Pemilik Mobil</b></span>";
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

            function createMask(string){
                return string.replace(/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/,"$1.$2.$3.$4-$5.$6");
            }

            function destroyMask(string){
                return string.replace(/\D/g,'').substring(0,15);
            }

            function createMask2(string){
                return string.replace(/(\d{4})(\d{4})(\d{4})/,"$1-$2-$3");
            }

            function destroyMask2(string){
                return string.replace(/\D/g,'').substring(0,12);
            }

            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    
                }
            });

            $('#editvendor').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var id = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('vendor.edit_vendor') !!}',
                    type: 'POST',
                    data : {
                        'id': id
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.id);
                        $('#type').val(results.type).trigger('change');
                        $('#Nama').val(results.nama_vendor);
                        $('#Namapo').val(results.nama_vendor_po);
                        $('#Alamat').val(results.alamat);
                        $('#Telp').val(results.telp);
                        $('#Hp').val(results.hp);
                        $('#Norek').val(results.norek_vendor);
                        $('#Npwp').val(results.npwp);
                        $('#Kontak').val(results.nama_kontak);
                        $('#Coa1').val(results.kode_coa).trigger('change');
                        $('#Status').val(results.status).trigger('change');
                        $('#editform').modal('show');
                        }
         
                });
            });

            $('#hapusvendor').click( function () {
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
                            url: '{!! route('vendor.hapus_vendor') !!}',
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

        $('#addform').on('show.bs.modal', function () {
            $('.form-group2').hide();
        })

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
                    url:'{!! route('vendor.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#type1').val('').trigger('change');
                        $('#Nama1').val('');
                        $('#Nama2').val('');
                        $('#Alamat1').val('');
                        $('#Telp1').val('');
                        $('#Hp1').val('');
                        $('#Norek1').val('');
                        $('#Npwp1').val('');
                        $('#Kontak1').val('');
                        $('#Status1').val('').trigger('change');
                        $('#Coa').val('').trigger('change');
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
                    url:'{!! route('vendor.ajaxupdate') !!}',
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