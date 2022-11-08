@extends('adminlte::page')

@section('title', 'Sopir')

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
                    @permission('create-sopir')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Sopir</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>SOPIR</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>Kode Sopir</th>
                        <th>Nama Sopir</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Hp</th>
                        <th>NIS</th>
                        <th>Gaji (%)</th>
                        <th>Tabungan (%)</th>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('nama_sopir', 'Nama Sopir:') }}
                                    {{ Form::text('nama_sopir', null, ['class'=> 'form-control','id'=>'Nama1','required'=>'required', 'placeholder'=>'Nama Sopir','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
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
                                    {{ Form::label('telp', 'Telp:') }}
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp1','required'=>'required', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-5">
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
                                    {{ Form::label('nis', 'NIS:') }}
                                    {{ Form::text('nis', null, ['class'=> 'form-control','id'=>'Nis1', 'placeholder'=>'NIS','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_rekening', 'No Rekening:') }}
                                    {{ Form::text('no_rekening', null, ['class'=> 'form-control','id'=>'Rek1','required'=>'required', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group4">
                                    {{ Form::label('kode_coa', 'Coa:') }}
                                    {{ Form::select('coa',$Coa->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Nomorcoa']) }}
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
                        
                            {{ Form::hidden('kode_sopir', null, ['class'=> 'form-control','id'=>'Kode','readonly']) }}

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('nama_sopir', 'Nama Sopir:') }}
                                    {{ Form::text('nama_sopir', null, ['class'=> 'form-control','id'=>'Nama','required'=>'required', 'placeholder'=>'Nama Sopir','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('alamat', 'Alamat:') }}
                                    {{ Form::textArea('alamat', null, ['class'=> 'form-control','rows'=>'2','id'=>'Alamat','required'=>'required', 'placeholder'=>'Alamat','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('telp', 'Telp:') }}
                                    {{ Form::text('telp', null, ['class'=> 'form-control','id'=>'Telp','required'=>'required', 'placeholder'=>'No. Telepon','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{ Form::label('kota', 'Kota:') }}
                                    {{ Form::text('kota', null, ['class'=> 'form-control','id'=>'Kota','required'=>'required', 'placeholder'=>'Kota','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('kodepos', 'Kode Pos:') }}
                                    {{ Form::text('kode_pos', null, ['class'=> 'form-control','id'=>'Kodepos','required'=>'required', 'placeholder'=>'Kode Pos','autocomplete'=>'off', 'maxlength'=>'5','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Hp', 'Hp:') }}
                                    <input type="text" name="number1" style="display:none;">
                                    {{ Form::text('hp', null, ['class'=> 'form-control','id'=>'Hp','required'=>'required', 'placeholder'=>'No. HP','autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)", 'name'=>'hp']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{ Form::label('no_rekening', 'No Rekening:') }}
                                    {{ Form::text('no_rekening', null, ['class'=> 'form-control','id'=>'Rek','required'=>'required', 'placeholder'=>'No. Rekening','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
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
            @permission('update-sopir')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editsopir" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-sopir')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapussopir" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.back2Top').show();
        }

    function getcoa(){
        $.ajax({
            url: '{!! route('sopir.getcoa') !!}',
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
            ajax: '{!! route('sopir.data') !!}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'nama_sopir', name: 'nama_sopir' },
                { data: 'alamat', name: 'alamat' },
                { data: 'telp', name: 'telp' },
                { data: 'hp', name: 'hp' },
                { data: 'nis', name: 'nis' },
                { data: 'gaji', name: 'gaji' },
                { data: 'tabungan', name: 'tabungan' },
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
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    
                }
            });

            $('#editsopir').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_sopir = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('sopir.edit_sopir') !!}',
                    type: 'POST',
                    data : {
                        'id': kode_sopir
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.kode_sopir);
                        $('#Nama').val(results.nama_sopir);
                        $('#Alamat').val(results.alamat);
                        $('#Telp').val(results.telp);
                        $('#Kota').val(results.kota);
                        $('#Kodepos').val(results.kode_pos);
                        $('#Hp').val(results.hp);
                        $('#Nis').val(results.nis);
                        $('#Gaji').val(results.gaji);
                        $('#Tabungan').val(results.tabungan);
                        $('#Rek').val(results.no_rekening);
                        $('#editform').modal('show');
                        }
         
                });
            });

            $('#hapussopir').click( function () {
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
                            url: '{!! route('sopir.hapus_sopir') !!}',
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
                    url:'{!! route('sopir.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Nama1').val('');
                        $('#Alamat1').val('');
                        $('#Telp1').val('');
                        $('#Kota1').val('');
                        $('#Kodepos1').val('');
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
                    url:'{!! route('sopir.ajaxupdate') !!}',
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