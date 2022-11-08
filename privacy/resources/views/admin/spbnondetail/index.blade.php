@extends('adminlte::page')

@section('title', 'Spb Non-Container Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="tablefresh()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Spb Non-Container<b> {{$spbnon->no_spbnon}}</b></font>
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('no_spbnon', 'No SPB Non Container:') }}
                                        {{ Form::text('no_spbnon',$spbnon->no_spbnon, ['class'=> 'form-control','readonly','id'=>'spbnon']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_item', 'Item:') }}
                                        {{ Form::text('kode_item',null, ['class'=> 'form-control','required','id'=>'item']) }}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        {{ Form::label('qty', 'QTY:') }}
                                        {{ Form::text('qty',null, ['class'=> 'form-control','required','id'=>'qty','onkeyup'=>'gettotal()']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('berat_satuan', 'Berat Satuan:') }}
                                        {{ Form::text('berat_satuan',null, ['class'=> 'form-control','required','id'=>'berat','onkeyup'=>'gettotal()']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('total_berat', 'Total Berat:') }}
                                        {{ Form::text('total_berat',null, ['class'=> 'form-control','required','id'=>'total', 'readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('keterangan', 'Keterangan:') }}
                                        {{ Form::textarea('keterangan', null, ['class'=> 'form-control','rows'=>'2','id'=>'keterangan', 'placeholder'=>'Keterangan', 'autocomplete'=>'off','required']) }}
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
                                        {{ Form::label('no_spbnon', 'No SPB Non Container:') }}
                                        {{ Form::text('no_spbnon',$spbnon->no_spbnon, ['class'=> 'form-control','readonly','id'=>'spbnon2']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_item', 'Item:') }}
                                        {{ Form::text('kode_item',null, ['class'=> 'form-control','required','id'=>'item2']) }}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        {{ Form::label('qty', 'QTY:') }}
                                        {{ Form::text('qty',null, ['class'=> 'form-control','required','id'=>'qty2','onkeyup'=>'gettotal2()']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('berat_satuan', 'Berat Satuan:') }}
                                        {{ Form::text('berat_satuan',null, ['class'=> 'form-control','required','id'=>'berat2','onkeyup'=>'gettotal2()']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('total_berat', 'Total Berat:') }}
                                        {{ Form::text('total_berat',null, ['class'=> 'form-control','required','id'=>'total2', 'readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('keterangan', 'Keterangan:') }}
                                        {{ Form::textarea('keterangan', null, ['class'=> 'form-control','rows'=>'2','id'=>'keterangan2', 'placeholder'=>'Keterangan', 'autocomplete'=>'off','required']) }}
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
                        <th>No SPB Non Container</th>
                        <th>Item</th>
                        <th>QTY</th>
                        <th>Berat Satuan</th>
                        <th>Total Berat</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                     </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-warning">
                            <th class="text-center" colspan="4">Total</th>
                            <th id="grandtotal">-</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
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

        function gettotal(){
            var qty= $('#qty').val();
            var berat= $('#berat').val();

            var total = qty * berat;
            $('#total').val(total);
        }

        function gettotal2(){
            var qty2= $('#qty2').val();
            var berat2= $('#berat2').val();

            var total2 = qty2 * berat2;
            $('#total2').val(total2);
        }
        
    $(function(){
        var no_spbnon = $('#spbnon').val();
        
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:'http://localhost/gui_front_pbm_laravel/admin/spbnondetail/getDatabyID?id='+no_spbnon,
            data:{'no_spbnon':no_spbnon},
            footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
        
                    // Total over this page
                    grandTotal = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        
                    // Update footer
                    $( api.column( 4 ).footer() ).html(
                        formatRupiah(grandTotal)
                    );
                },

            columns: [
                { data: 'no_spbnon', name: 'no_spbnon' },
                { data: 'kode_item', name: 'kode_item' },
                { data: 'qty', name: 'qty' },
                { data: 'berat_satuan', name: 'berat_satuan' },
                { data: 'total_berat', name: 'total_berat' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action' },
            ]
            
        });
        
    });
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
                    url:'{!! route('spbnondetail.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#item').val('');
                        $('#qty').val('');
                        $('#berat').val('');
                        $('#total').val('');
                        $('#keterangan').val('');
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
                    url:'{!! route('spbnondetail.updateajax') !!}',
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
               
        function edit(id, url) {
           
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

               $.ajax({
                    type: 'GET',
                    url: url,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        $(".editform").show();
                        $('#spbnon2').val(results.no_spbnon);
                        $('#item2').val(results.kode_item);
                        $('#qty2').val(results.qty);
                        $('#berat2').val(results.berat_satuan);
                        $('#total2').val(results.total_berat);
                        $('#keterangan2').val(results.keterangan);
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