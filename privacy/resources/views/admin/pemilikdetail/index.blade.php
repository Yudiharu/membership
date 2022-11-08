@extends('adminlte::page')

@section('title', 'Pemilik Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Pemilik <b>{{$vendor->nama_vendor}}</b></font>
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
                                {{ Form::hidden('kode_pemilik',$vendor->id, ['class'=> 'form-control','readonly','id'=>'kodepemilik']) }}
                                   
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('nama_pemilik', 'Nama Pemilik:') }}
                                        {{ Form::text('nama_pemilik',$vendor->nama_vendor, ['class'=> 'form-control','readonly','id'=>'namapemilik']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('kode_mobil', 'Mobil:') }}
                                        {{ Form::select('kode_mobil',$Mobil->sort(),null,
                                         ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','required'=>'required','id'=>'kode_mobil','onchange'=>'getjenis();']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_jenis_mobil', 'Jenis Mobil:') }}
                                        {{ Form::text('kode_jenis_mobil',null, ['class'=> 'form-control','readonly','id'=>'jenis']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kir', 'KIR:') }}
                                        {{ Form::date('kir',null,['class'=> 'form-control','id'=>'kir' ,'required'=>'required','readonly'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('masa_stnk', 'Masa STNK:') }}
                                        {{ Form::date('masa_stnk', null,['class'=> 'form-control','id'=>'stnk' ,'required'=>'required','readonly'])}}
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::hidden('id',null, ['class'=> 'form-control','readonly','id'=>'ID']) }}
                                        {{ Form::label('kode_pemilik', 'Kode Pemilik:') }}
                                        {{ Form::text('kode_pemilik',$pemilik->kode_pemilik, ['class'=> 'form-control','readonly','id'=>'kodepemilik2']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('kode_mobil', 'Mobil:') }}
                                        {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control','required','id'=>'kode_mobil2','onchange'=>'getjenis2();']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_jenis_mobil', 'Jenis Mobil:') }}
                                        {{ Form::text('kode_jenis_mobil',null, ['class'=> 'form-control','readonly','id'=>'jenis2']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kir', 'KIR:') }}
                                        {{ Form::date('kir',null,['class'=> 'form-control','id'=>'kir2' ,'required'=>'required','readonly'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('masa_stnk', 'Masa STNK:') }}
                                        {{ Form::date('masa_stnk', null,['class'=> 'form-control','id'=>'stnk2' ,'required'=>'required','readonly'])}}
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
                    <tr class="bg-danger">
                        <th>Kode Pemilik</th>
                        <th>Kode Mobil</th>
                        <th>Jenis Mobil</th>
                        <th>KIR</th>
                        <th>Masa STNK</th>
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
        </style>
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
        }
        
        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        function getjenis(){
            var kode_mobil= $('#kode_mobil').val();
            $.ajax({
                url:'{!! route('pemilikdetail.getjenis') !!}',
                type:'POST',
                data : {
                        'id': kode_mobil,
                    },
                success: function(result) {
                        console.log(result);
                        $('#jenis').val(result.kode_jenis_mobil);
                        $('#kir').val(result.kir);
                        $('#stnk').val(result.masa_stnk);
                    },
            });
        }

        function getjenis2(){
            var kode_mobil= $('#kode_mobil2').val();
            $.ajax({
                url:'{!! route('pemilikdetail.getjenis2') !!}',
                type:'POST',
                data : {
                        'id': kode_mobil,
                    },
                success: function(result) {
                        console.log(result);
                        $('#jenis2').val(result.kode_jenis_mobil);
                        $('#kir2').val(result.kir);
                        $('#stnk2').val(result.masa_stnk);
                    },
            });
        }
        
    $(function(){
        var kode_pemilik = $('#kodepemilik').val();
        var namapemilik = $('#namapemilik').val();  
        
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:'http://localhost/gui_front_pbm_laravel/admin/pemilikdetail/getDatabyID?id='+kode_pemilik,
            data:{'kode_pemilik':kode_pemilik},

            columns: [
                { data: 'kode_pemilik', name: 'kode_pemilik', visible: false },
                { data: 'mobil.nopol', name: 'mobil.nopol' },
                { data: 'jenismobil.nama_jenis_mobil', "defaultContent": "<i>Not set</i>" },
                { data: 'mobil.kir', "defaultContent": "<i>Not set</i>" },
                { data: 'mobil.masa_stnk', "defaultContent": "<i>Not set</i>" },
            ]
            
        });
        
    });

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
                    url:'{!! route('pemilikdetail.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#kode_mobil').val('').trigger('change');
                        $('#jenis').val('');
                        $('#kir').val('');
                        $('#stnk').val('');
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
                    url:'{!! route('pemilikdetail.updateajax') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        $('#QTY2').val('');
                        $('#QTY').val('');

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
               
        function edit(id, url) {
           
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

               $.ajax({
                    type: 'GET',
                    url: url,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        $(".editform").show();
                        $('#kodepemilik2').val(results.kode_pemilik);
                        $('#kode_mobil2').val(results.kode_mobil);
                        $('#jenis2').val(results.kode_jenis_mobil);
                        $('#kir2').val(results.kir);
                        $('#stnk2').val(results.masa_stnk);
                        $('#ID').val(results.id);
                        $(".addform").hide();
                       },
                        error : function() {
                        alert("Nothing Data");
                    }
                });
                     
        }

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
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
    </script>
@endpush