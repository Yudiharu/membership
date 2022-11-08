@extends('adminlte::page')

@section('title', 'Membership Customer')

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
                        <i class="fa fa-plus"></i> New Membership</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>MEMBERSHIP</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>id</th>
                        <th>Nama customer</th>
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
                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('Nama customer', 'Nama Customer:') }}<br>
                            {{ Form::select('kode_customer', $Customer->sort(), null, ['class'=> 'form-control select2', 'style'=>'width: 100%','placeholder'=>'','id'=>'KodeCust1','required'=>'required']) }}
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
                        
                            {{ Form::hidden('kode_customer', null, ['class'=> 'form-control','id'=>'Kode','readonly']) }}

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('Nama customer', 'Nama Customer:') }}
                                    {{ Form::text('nama_customer', null, ['class'=> 'form-control','id'=>'Nama','required'=>'required', 'placeholder'=>'Contoh: NAMA CUSTOMER, CV','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: NAMA CUSTOMER, CV", 'readonly']) }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('Nama Customer', 'Nama Customer Cetak:') }}
                                    {{ Form::text('nama_customer_po', null, ['class'=> 'form-control','id'=>'Namapo','required'=>'required', 'placeholder'=>'Contoh: CV. NAMA CUSTOMER','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Contoh: CV. NAMA CUSTOMER", 'readonly']) }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('alamat', 'Alamat:') }}
                                    {{ Form::text('alamat', null, ['class'=> 'form-control','id'=>'Alamat', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 50 Karakter",'required','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::text('alamat2', null, ['class'=> 'form-control','id'=>'Alamat2a', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 50 Karakter",'autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::text('alamat3', null, ['class'=> 'form-control','id'=>'Alamat2b', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 50 Karakter",'autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::text('alamat4', null, ['class'=> 'form-control','id'=>'Alamat2c', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 50 Karakter",'autocomplete'=>'off']) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('telp', 'Telp:') }}
                                    <input type="text" name="numbertelp" style="display:none;">
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp','required'=>'required', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{ Form::label('kota', 'Kota:') }}
                                    {{ Form::text('kota', null, ['class'=> 'form-control','id'=>'Kota', 'placeholder'=>'Kota','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('kode_pos', 'Kode Pos:') }}
                                    {{ Form::text('kode_pos', null, ['class'=> 'form-control','id'=>'Kodepos','required'=>'required', 'placeholder'=>'Kode Pos','autocomplete'=>'off', 'maxlength'=>'5','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Fax', 'Fax:') }}
                                    <input type="text" name="numberfax" style="display:none;">
                                    {{ Form::text('fax', null, ['class'=> 'form-control','id'=>'Fax','required'=>'required', 'placeholder'=>'No. FAX','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Hp', 'Hp:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp','required'=>'required','onkeypress'=>"return hanyaAngka(event)", 'name'=>'hp','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Npwp', 'Npwp:') }}
                                    <input type="text" name="number" style="display:none;">
                                    {{ Form::text('npwp', null, ['class'=> 'form-control','id'=>'Npwp','autocomplete'=>'off', 'name'=>'npwp','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nama_kontak', 'Nama Kontak:') }}
                                    {{ Form::text('nama_kontak', null, ['class'=> 'form-control','id'=>'Kontak', 'placeholder'=>'Nama Kontak','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('contact_pic', 'Kontak PIC:') }}
                                    <input type="text" name="numberpic" style="display:none;">
                                    {{ Form::text('contact_pic', null, ['class'=> 'form-control','id'=>'contact2','required'=>'required', 'placeholder'=>'No. HP PIC','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)", 'name'=>'contact_pic']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('type_company', 'Type Company:') }}
                                    {{Form::select('type_company', ['1' => 'Swasta', '2' => 'BUMN'], null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'compa','required'=>'required'])}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_kode_pajak', 'No Kode Pajak:') }}
                                    {{ Form::text('no_kode_pajak', null, ['class'=> 'form-control','id'=>'kodepajak','onkeypress'=>"return hanyaAngka(event)", 'placeholder'=>'No Kode Pajak','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status:') }}
                                    {{Form::select('status', ['Aktif' => 'Aktif', 'NonAktif' => 'NonAktif'], null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'Status','required'=>'required'])}}
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

            .add-button {
                background-color: #F77712;
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
            @permission('update-customer')
            <a href="#" id="addmember"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD MEMBER <i class="fa fa-plus"></i></button></a>
            @endpermission

            @permission('update-customer')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editcustomer" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-customer')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuscustomer" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            ajax: '{!! route('membershipcustomer.data') !!}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'customer.nama_customer', name: 'customer.nama_customer' },
            ]
            });
        });

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
                    var id = data['id'];
                    var customer = data['kode_customer'];
                    var add = $("#addmember").attr("href","http://localhost/gui_front_pbm_laravel/admin/membershipcustomer/"+customer+"/detail");
                    $('.hapus-button').show();
                    $('.add-button').show();
                }
            });

            $('#editcustomer').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_customer = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('customer.edit_customer') !!}',
                    type: 'POST',
                    data : {
                        'id': kode_customer
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.kode_customer);
                        $('#Nama').val(results.nama_customer);
                        $('#Namapo').val(results.nama_customer_po);
                        $('#Alamat').val(results.alamat);
                        $('#Alamat2a').val(results.alamat2);
                        $('#Alamat2b').val(results.alamat3);
                        $('#Alamat2c').val(results.alamat4);
                        $('#Telp').val(results.telp);
                        $('#Kota').val(results.kota);
                        $('#Kodepos').val(results.kode_pos);
                        $('#Fax').val(results.fax);
                        $('#Hp').val(results.hp);
                        $('#Npwp').val(results.npwp);
                        $('#Kontak').val(results.nama_kontak);
                        $('#contact2').val(results.contact_pic);
                        $('#compa').val(results.type_company);
                        $('#kodepajak').val(results.no_kode_pajak);
                        $('#Status').val(results.status);
                        $('#editform').modal('show');
                        }
         
                });
            });

            $('#hapuscustomer').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_customer = data['id'];
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
                            url: '{!! route('customer.hapus_customer') !!}',
                            type: 'POST',
                            data : {
                                'id': kode_customer
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
                    url:'{!! route('membershipcustomer.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Nama1').val('');
                        $('#Namapo1').val('');
                        $('#Alamat1').val('');
                        $('#Alamat1a').val('');
                        $('#Alamat1b').val('');
                        $('#Alamat1c').val('');
                        $('#Telp1').val('(    )-');
                        $('#Kota1').val('');
                        $('#Kodepos1').val('0');
                        $('#Fax1').val('(    )-');
                        $('#Hp1').val('(    )-');
                        $('#Npwp1').val('.   .   . -   .');
                        $('#Kontak1').val('');
                        $('#contact1').val('(    )-');
                        $('#kodepajak1').val('');
                        $('#Status1').val('').trigger('change');
                        $('#tipe1').val('').trigger('change');
                        $('#compa1').val('').trigger('change');
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
                    url:'{!! route('customer.ajaxupdate') !!}',
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