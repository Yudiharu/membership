@extends('adminlte::page')

@section('title', 'Tarif Kegiatan')

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
                        <i class="fa fa-plus"></i> New Tarif</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>TARIF</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-danger">
                        <th>id</th>
                        <th>Kegiatan</th>
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
                            {{ Form::hidden('type_kegiatan', null, ['class'=> 'form-control','id'=>'Type1','readonly']) }}
                            <br>
                            {{ Form::label('Nama customer', 'Jenis Kegiatan:') }}
                            {{ Form::select('id_kegiatan', $Kegiatan, null, ['class'=> 'form-control select2','id'=>'Kegiatan1','required'=>'required', 'placeholder'=>'','style'=>'width: 100%','onchange'=>'tipe();']) }}
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
                        <div class="col-md-12">
                            <div class="form-group5">
                                {{ Form::label('item', 'Tipe Tarif:') }}
                                <br>
                                <input type="radio" name="type_kegiatan" id="Kegiatan2a" value="CONTAINER" onclick="getcontainer2()"> Container&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="type_kegiatan" id="Kegiatan2b" value="NON-CONTAINER" onclick="getnoncontainer2()"> Non Container&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="type_kegiatan" id="Kegiatan2c" value="CFS" onclick="getcfs2()"> CFS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('Nama customer', 'Jenis Kegiatan:') }}
                                {{ Form::select('id_kegiatan', [], null, ['class'=> 'form-control select2','id'=>'Kegiatan2','required', 'placeholder'=>'','style'=>'width: 100%']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('Nama customer', 'Jenis Harga:') }}
                                {{ Form::select('jenis_harga', $Harga, null, ['class'=> 'form-control select2','id'=>'Harga2','required', 'placeholder'=>'','style'=>'width: 100%']) }}
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
                background-color: #4E8E02;
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
            <a href="#" id="addtarif"><button type="button" class="btn btn-xs add-button" data-toggle="modal" data-target="">ADD TARIF <i class="fa fa-plus"></i></button></a>

            <!-- @permission('update-customer') -->
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editkegiatan" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            <!-- @endpermission -->

            <!-- @permission('delete-customer') -->
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuskegiatan" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            <!-- @endpermission -->
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

    // function getnoncontainer2(){
    //     $('#Kegiatan2').prop("disabled", false);
    //     var token = $("input[name='_token']").val();
    //     $.ajax({
    //         url: "{!! route('tarifkegiatan.selectnoncontainer') !!}",
    //         method: 'POST',
    //         data: { _token:token},
    //         success: function(data) {
    //         $("#Kegiatan2").html('');
    //         console.log(data);
    //             // $("select[name='kode_satuan'").html(data.options);
    //             $.each(data.options, function(key, value){
    //                 $('#Kegiatan2').val('');
    //                 $('#Kegiatan2').append('<option value="'+ key +'">' + value + '</option>');
    //                 $('#Kegiatan2').val('');
    //             });
    //         }
    //     });
    // }

        $(function() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('tarifkegiatan.data') !!}',
                columns: [
                    { data: 'id', name: 'id', visible: false },
                    { data: 'kegiatan.description', name: 'kegiatan.description' },
                    // { data: 'no_kode_pajak', "defaultContent": "<i>Not set</i>" },
                ]
            });
        });

        function format_type(n) {
            if(n == '1'){
                var stat = "<span style='color:#0eab25'><b>UMUM</b></span>";
            }else{
                var stat = "<span style='color:#c91a1a'><b>ASOSIASI</b></span>";
            }
            return stat;
        }

        function tipe(){
            var id = $('#Kegiatan1').val();
            $.ajax({
                url: '{!! route('tarifkegiatan.tipe') !!}',
                type: 'POST',
                data : {
                        'id': id
                },
                success: function(results) {
                    console.log(results);
                    $('#Type1').val(results.type_kegiatan);
                }
            });
        }

        $(document).ready(function(){
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });
            
            $('[data-toggle="tooltip"]').tooltip();   

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
                    var add = $("#addtarif").attr("href","http://localhost/gui_front_pbm_laravel/admin/tarifkegiatan/"+id+"/detail");
                    $('.hapus-button').show();
                    $('.edit-button').hide();
                    $('.add-button').show();
                }
            });

            $('#editkegiatan').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var id = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('tarifkegiatan.edit_detail') !!}',
                    type: 'POST',
                    data : {
                        'id': id
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.id);
                        $('#Kegiatan2').val(results.id_kegiatan).trigger('change');
                        $('#Harga2').val(results.jenis_harga).trigger('change');
                        if (results.type_kegiatan == 'CFS') {
                            getcfs2();
                            document.getElementById('Kegiatan2c').checked = true;
                        }else if (results.type_kegiatan == 'CONTAINER') {
                            document.getElementById('Kegiatan2a').checked = true;
                        }else if (results.type_kegiatan == 'NON-CONTAINER') {
                            document.getElementById('Kegiatan2b').checked = true;
                        }
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
                            url: '{!! route('tarifkegiatan.hapus_detail') !!}',
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
                url:'{!! route('tarifkegiatan.store_detail') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#Kegiatan1').val('').trigger('change');
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
                    url:'{!! route('tarifkegiatan.ajaxupdate') !!}',
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