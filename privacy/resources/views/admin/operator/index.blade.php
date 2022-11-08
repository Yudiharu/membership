@extends('adminlte::page')

@section('title', 'Operator')

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
                    @permission('create-operator')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Operator</button>
                    @endpermission

                    <span class="pull-right">
                        <b>Keterangan Warna:&nbsp;&nbsp;&nbsp;&nbsp;</b>
                        <font style="background-color:#5CB8FB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Status Insentif.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <font style="background-color:#FB5CAE;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Status Tembak.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <font style="background-color:#91FB5C;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Status Insentif & Tembak.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <font style="font-size: 16px;"><b>OPERATOR</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>id</th>
                        <th>Nama Operator</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Hp</th>
                        <th>Kode Pos</th>
                        <th>Bank</th>
                        <th>No Rekening</th>
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('Nama', 'Nama Operator:') }}
                                    {{ Form::text('nama_operator', null, ['class'=> 'form-control','id'=>'Nama1','required'=>'required', 'placeholder'=>'Nama Operator','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <br>
                                    <input type="hidden" name="status_insentif" value="0" />
                                    <input type="checkbox" name="status_insentif" id="insentif1" value="1"/>&nbsp;Status Insentif
                                    <br>
                                    <input type="hidden" name="status_tembak" value="0" />
                                    <input type="checkbox" name="status_tembak" id="tembak1" value="1"/>&nbsp;Status Tembak
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('alamat', 'Alamat:') }}
                                    {{ Form::textArea('alamat', null, ['class'=> 'form-control','rows'=>'2','id'=>'Alamat1','required'=>'required', 'placeholder'=>'Alamat','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nix', 'NIK:') }}
                                    {{ Form::text('nik', null, ['class'=> 'form-control','id'=>'Nik1', 'placeholder'=>'No Induk','autocomplete'=>'off','required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('telp', 'Telp:') }}
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp1','required'=>'required', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kota', 'Kota:') }}
                                    {{ Form::text('kota', null, ['class'=> 'form-control','id'=>'Kota1','required'=>'required', 'placeholder'=>'Kota','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('kodepos', 'Kode Pos:') }}
                                    {{ Form::text('kode_pos', null, ['class'=> 'form-control','id'=>'Kodepos1','required'=>'required', 'placeholder'=>'Kode Pos','autocomplete'=>'off', 'maxlength'=>'5','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Hp', 'Hp:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp1','required'=>'required', 'placeholder'=>'No. HP','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)", 'name'=>'hp']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nix', 'Kontak PIC:') }}
                                    {{ Form::text('kontak_pic', null, ['class'=> 'form-control','id'=>'Kontak1', 'placeholder'=>'','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nis', 'NIS:') }}
                                    {{ Form::text('nis', null, ['class'=> 'form-control','id'=>'Nis1', 'placeholder'=>'NIS','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div> -->
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('gaji', 'Gaji (%):') }}
                                    {{ Form::text('gaji', 12.5, ['class'=> 'form-control','id'=>'Gaji1', 'placeholder'=>'Gaji','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)",'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tabungan', 'Tabungan (%):') }}
                                    {{Form::text('tabungan', 10, ['class'=> 'form-control','id'=>'Tabungan1', 'placeholder'=>'Tabungan','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)",'readonly']) }}
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bankss', 'Bank:') }}
                                    {{ Form::select('kode_bank',$Bank->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Bank1']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_rekening', 'No Rekening:') }}
                                    {{ Form::text('no_rekening', null, ['class'=> 'form-control','id'=>'Rek1', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('nama_sopir', 'Nama Operator:') }}
                                    {{ Form::text('nama_operator', null, ['class'=> 'form-control','id'=>'Nama2','required'=>'required', 'placeholder'=>'Nama Operator','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <br>
                                    <input type="hidden" name="status_insentif" value="0" />
                                    <input type="checkbox" name="status_insentif" id="insentif2" value="1"/>&nbsp;Status Insentif
                                    <br>
                                    <input type="hidden" name="status_tembak" value="0" />
                                    <input type="checkbox" name="status_tembak" id="tembak2" value="1"/>&nbsp;Status Tembak
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('alamat', 'Alamat:') }}
                                    {{ Form::textArea('alamat', null, ['class'=> 'form-control','rows'=>'2','id'=>'Alamat2','required'=>'required', 'placeholder'=>'Alamat','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nix', 'NIK:') }}
                                    {{ Form::text('nik', null, ['class'=> 'form-control','id'=>'Nik2', 'placeholder'=>'No Induk','autocomplete'=>'off','required']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('telp', 'Telp:') }}
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp2','required'=>'required', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kota', 'Kota:') }}
                                    {{ Form::text('kota', null, ['class'=> 'form-control','id'=>'Kota2','required'=>'required', 'placeholder'=>'Kota','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('kodepos', 'Kode Pos:') }}
                                    {{ Form::text('kode_pos', null, ['class'=> 'form-control','id'=>'Kodepos2','required'=>'required', 'placeholder'=>'Kode Pos','autocomplete'=>'off', 'maxlength'=>'5','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Hp', 'Hp:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp2','required'=>'required', 'placeholder'=>'No. HP','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)", 'name'=>'hp']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nix', 'Kontak PIC:') }}
                                    {{ Form::text('kontak_pic', null, ['class'=> 'form-control','id'=>'Kontak2', 'placeholder'=>'','autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nis', 'NIS:') }}
                                    {{ Form::text('nis', null, ['class'=> 'form-control','id'=>'Nis', 'placeholder'=>'NIS','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('gaji', 'Gaji (%):') }}
                                    {{ Form::text('gaji', 12.5, ['class'=> 'form-control','id'=>'Gaji', 'placeholder'=>'Gaji','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)",'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tabungan', 'Tabungan (%):') }}
                                    {{Form::text('tabungan', 10, ['class'=> 'form-control','id'=>'Tabungan', 'placeholder'=>'Tabungan','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)",'readonly']) }}
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('bankss', 'Bank:') }}
                                    {{ Form::select('kode_bank',$Bank->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Bank2']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_rekening', 'No Rekening:') }}
                                    {{ Form::text('no_rekening', null, ['class'=> 'form-control','id'=>'Rek2','required'=>'required', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
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
            @permission('update-operator')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editoperator" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-operator')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusoperator" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.edit-button').hide();
            $('.back2Top').show();
            document.getElementById("Nama1").focus();
        }

    function getcoa(){
        $.ajax({
            url: '{!! route('operator.getcoa') !!}',
            type: 'POST',
            data : {
            },
            success: function(results) {
                $('.form-group4').show();
                $('#Nomorcoa').val(results.kode_coa).trigger('change');
                document.getElementById("Nomorcoa").required = true;
            }
        });
    }

    $('#addform').on('show.bs.modal', function () {
        getcoa();
    });

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
            ajax: '{!! route('operator.data') !!}',
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status_insentif'] == 1 && data['status_tembak'] == 0) {
                    $('td', row).css('background-color', '#5CB8FB');
                }else if (data['status_tembak'] == 1 && data['status_insentif'] == 0) {
                    $('td', row).css('background-color', '#FB5CAE');
                }else if (data['status_insentif'] == 1 && data['status_tembak'] == 1) {
                    $('td', row).css('background-color', '#91FB5C');
                }
            },
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'nama_operator', name: 'nama_operator' },
                { data: 'nik', name: 'nik' },
                { data: 'alamat', name: 'alamat' },
                { data: 'telp', name: 'telp' },
                { data: 'hp', name: 'hp' },
                { data: 'kode_pos', name: 'kode_pos' },
                { data: 'bank.nama_bank', "defaultContent": "<i>Not set</i>" },
                { data: 'no_rekening', name: 'no_rekening' },
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

            $("input[name='hp']").on("keyup change", function(){
            $("input[name='number1']").val(destroyMask2(this.value));
                this.value = createMask2($("input[name='number1']").val());
            })

            function createMask2(string){
                return string.replace(/(\d{4})(\d{4})(\d{4})/,"$1-$2-$3");
            }

            function destroyMask2(string){
                return string.replace(/\D/g,'').substring(0,12);
            }
            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-black') ) {
                    $(this).removeClass('selected bg-black text-bold');
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                }else {
                    table.$('tr.selected').removeClass('selected bg-black text-bold');
                    $(this).addClass('selected bg-black text-bold');
                    var select = $('.selected').closest('tr');
                    $('.hapus-button').show();
                    $('.edit-button').show();
                }
            });

            $('#editoperator').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_sopir = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('operator.edit_operator') !!}',
                    type: 'POST',
                    data : {
                        'id': kode_sopir
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.id);
                        $('#Bank2').val(results.kode_bank).trigger('change');
                        $('#Nama2').val(results.nama_operator);
                        $('#Alamat2').val(results.alamat);
                        $('#Nik2').val(results.nik);
                        $('#Kontak2').val(results.kontak_pic);
                        $('#Telp2').val(results.telp);
                        $('#Kota2').val(results.kota);
                        $('#Kodepos2').val(results.kode_pos);
                        $('#Hp2').val(results.hp);
                        if (results.status_insentif == 1) {
                            document.getElementById("insentif2").checked = true;
                        }else {
                            document.getElementById("insentif2").checked = false;
                        }
                        if (results.status_tembak == 1) {
                            document.getElementById("tembak2").checked = true;
                        }else {
                            document.getElementById("tembak2").checked = false;
                        }
                        $('#Rek2').val(results.no_rekening);
                        $('#editform').modal('show');
                    }
                });
            });

            $('#hapusoperator').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_sopir = data['id'];
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
                            url: '{!! route('operator.hapus_operator') !!}',
                            type: 'POST',
                            data : {
                                'id': kode_sopir
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
                url:'{!! route('operator.store') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#Nama1').val('');
                    $('#Alamat1').val('');
                    $('#Nik1').val('');
                    $('#Kontak1').val('');
                    $('#Telp1').val('');
                    $('#Kota1').val('');
                    $('#Kodepos1').val('');
                    $('#Bank1').val('').trigger('change');
                    $('#Hp1').val('');
                    $('#Nis1').val('');
                    $('#Rek1').val('');
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
                url:'{!! route('operator.ajaxupdate') !!}',
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