@extends('adminlte::page')

@section('title', 'Trucking Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;"> Detail Trucking <b>{{$trucking->no_trucking}}</b></font>
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
                                        {{ Form::label('no_trucking', 'No Trucking:') }}
                                        {{ Form::text('no_trucking',$trucking->no_trucking, ['class'=> 'form-control','readonly','id'=>'notrucking']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('no_req_jo', 'No Request JO:') }}
                                        <button type="button" class="btn btn-primary btn-xs" title="Detail JOR" onclick="getjor()" id='submit3'><i class="fa fa-plus"></i> Detail Job Request</button>
                                        {{ Form::text('no_req_jo',$trucking->no_req_jo, ['class'=> 'form-control','readonly','id'=>'noreqjo']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <b>Keterangan Warna:</b><br>
                                        <font style="background-color:#ffdbd3;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Belum input container dan tanggal keluar.<br>
                                        <font style="background-color:#DCFFBF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;:&nbsp;Belum input tanggal kembali.
                                    </div>
                                </div>
                            </div>                     
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
                                        {{ Form::label('no_trucking', 'No Trucking:') }}
                                        {{ Form::text('no_trucking',$trucking->no_trucking, ['class'=> 'form-control','readonly','id'=>'notrucking2']) }}
                                    </div>
                                </div>

                                {{ Form::hidden('kode_shipper',$trucking->kode_shipper, ['class'=> 'form-control','readonly','id'=>'shipper2']) }}

                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('nama_shipper', 'Nama Shipper:') }}
                                        {{ Form::text('nama_shipper',null, ['class'=> 'form-control','readonly','id'=>'namashipper2']) }}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('kode_gudang', 'Gudang:') }}
                                        {{ Form::select('kode_gudang',$Gudang->sort(),null,
                                         ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','required'=>'required','id'=>'kode_gudang2','onchange'=>"gettarif();"]) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('kode_container', 'Container:') }}
                                        {{ Form::text('kode_container',null, ['class'=> 'form-control','readonly','id'=>'container2']) }}
                                    </div>
                                </div>

                                {{ Form::hidden('kode_size',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','required','readonly','id'=>'kode_size2']) }}

                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('nama_size', 'Nama Size:') }}
                                        {{ Form::text('nama_size',null, ['class'=> 'form-control','readonly','id'=>'namasize2']) }}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('no_seal', 'No Seal:') }}
                                        {{ Form::text('no_seal',null, ['class'=> 'form-control','id'=>'seal2','required','autocomplete'=>'off']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('no_spb', 'No SPB:') }}
                                        {{ Form::text('no_spb',null, ['class'=> 'form-control','readonly','id'=>'spb2']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group6">
                                        {{ Form::label('tgl_spb', 'Tanggal SPB:') }}
                                        {{ Form::date('tgl_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required','data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Tanggal SPB tidak boleh lebih dari tanggal kembali.",'onchange'=>"gettariftgl();"])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('muatan', 'Muatan:') }}
                                        {{ Form::text('muatan',null, ['class'=> 'form-control','id'=>'muatan2','required','autocomplete'=>'off']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('colie', 'Colie:') }}
                                        {{ Form::text('colie',null, ['class'=> 'form-control','id'=>'colie2','required','autocomplete'=>'off']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('tarif_trucking', 'Tarif Trucking:') }}
                                        {{ Form::text('tarif_trucking',null, ['class'=> 'form-control','readonly','id'=>'tarif2','required']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('dari', 'Dari:') }}
                                        {{ Form::text('dari',null, ['class'=> 'form-control','id'=>'dari2','required','autocomplete'=>'off']) }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('tujuan', 'Tujuan:') }}
                                        {{ Form::text('tujuan',null, ['class'=> 'form-control','id'=>'tujuan2','required','autocomplete'=>'off']) }}
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

            <div class="modal fade" id="editform2" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Detail SPB</h4>
                    </div>
                    @include('errors.validation')
                    {!! Form::open(['id'=>'ADD']) !!}
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_trucking', 'No Trucking:') }}
                                            {{ Form::text('no_trucking', null, ['class'=> 'form-control','id'=>'Truck1', 'placeholder'=>'No Trucking', 'autocomplete'=>'off','required', 'readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_spb', 'No SPB:') }}
                                            {{ Form::text('no_spb', null, ['class'=> 'form-control','id'=>'Spb1', 'placeholder'=>'No SPB', 'autocomplete'=>'off','required', 'readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_spb_manual', 'No SPB Manual:') }}
                                            {{ Form::text('no_spb_manual', null, ['class'=> 'form-control','id'=>'Manual1', 'placeholder'=>'No SPB Manual', 'autocomplete'=>'off','required', 'readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('tgl_spb', 'Tanggal SPB:') }}
                                            {{ Form::date('tgl_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('tgl_kembali', 'Tanggal Kembali SPB:') }}
                                            {{ Form::date('tgl_kembali', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Kembali1' ,'required'=>'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('kode_mobil', 'Mobil:') }}
                                            {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Mobil1','required'=>'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('kode_sopir', 'Sopir:') }}
                                            {{ Form::select('kode_sopir',$Sopir->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Sopir1','required'=>'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('kode_pemilik', 'Pemilik:') }}
                                            {{ Form::select('kode_pemilik',$Pemilik->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Pemilik1','required'=>'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('uang_jalan', 'Uang Jalan:') }}
                                            {{ Form::text('uang_jalan', null, ['class'=> 'form-control','id'=>'Uang1', 'placeholder'=>'Uang Jalan', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('bbm', 'BBM:') }}
                                            {{ Form::text('bbm', null, ['class'=> 'form-control','id'=>'Bbm1', 'placeholder'=>'BBM', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('bpa', 'B/P/A:') }}
                                            {{ Form::text('bpa', null, ['class'=> 'form-control','id'=>'Bpa1', 'placeholder'=>'BPA', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('honor', 'Honor:') }}
                                            {{ Form::text('honor', null, ['class'=> 'form-control','id'=>'Honor1', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('biaya_lain', 'Biaya Lain:') }}
                                            {{ Form::text('biaya_lain', null, ['class'=> 'form-control','id'=>'Biaya1', 'placeholder'=>'Honor', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('trucking', 'Trucking:') }}
                                            {{ Form::text('trucking', null, ['class'=> 'form-control','id'=>'Trucking1', 'placeholder'=>'Trucking', 'autocomplete'=>'off','required']) }}
                                         </div>
                                    </div> 
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="row">
                                {{ Form::submit('Simpan', ['class' => 'btn btn-success']) }}
                                {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                            </div>
                        </div>
                    {!! Form::close() !!}
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    </div>
</div>

            <div class="modal fade" id="editform3" role="dialog">
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
                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_trucking', 'No Trucking:') }}
                                            {{ Form::text('no_trucking',$trucking->no_trucking, ['class'=> 'form-control','style'=>'width: 100%','id'=>'notruck','required','readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_spb', 'No SPB Container:') }}
                                            {{ Form::text('no_spb',null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'nospb','required','readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_spb_manual', 'No SPB Manual:') }}
                                            {{ Form::text('no_spb_manual',null, ['class'=> 'form-control','required','id'=>'Manual']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('tgl_spb', 'Tanggal SPB:') }}
                                            {{ Form::date('tgl_spb', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal' ,'required'=>'required','onchange'=>"tgl2();",'readonly'])}}
                                         </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('tgl_kembali', 'Tanggal Kembali SPB:') }}
                                            {{ Form::date('tgl_kembali', null,['class'=> 'form-control','id'=>'Kembali' ,'required'=>'required','onchange'=>"tgl2();"])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('kode_mobil', 'Mobil:') }}
                                            {{ Form::select('kode_mobil',$Mobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Mobil','required'=>'required','onchange'=>"pemilik2();"]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group1">
                                            <br>
                                            {{ Form::label('no_asset_mobil', 'No Asset:') }}
                                            {{ Form::select('no_asset_mobil',$Asmobil->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'asset_mobil']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group2">
                                            <br>
                                            {{ Form::label('kode_sopir', 'Sopir:') }}
                                            {{ Form::select('kode_sopir',$Sopir->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Sopir']) }}
                                        </div>
                                        <div class="form-group3">
                                            <br>
                                            {{ Form::label('sopir', 'Sopir:') }}
                                            {{ Form::text('sopir',null, ['class'=> 'form-control','id'=>'Namasopir','autocomplete'=>'off']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('nama_pemilik', 'Pemilik:') }}
                                            {{ Form::text('nama_pemilik', null, ['class'=> 'form-control','readonly','id'=>'Pemilik2']) }}
                                            {{ Form::hidden('kode_pemilik', null, ['class'=> 'form-control','style'=>'width: 100%','id'=>'kode_pemilik2']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('trucking', 'Trucking:') }}
                                            {{ Form::text('trucking', null, ['class'=> 'form-control','id'=>'Trucking', 'placeholder'=>'Trucking', 'autocomplete'=>'off','required','readonly']) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('uang_jalan', 'Uang Jalan:') }}
                                            {{ Form::text('uang_jalan', null, ['class'=> 'form-control','id'=>'Uang', 'placeholder'=>'Uang Jalan', 'autocomplete'=>'off','required','onkeyup'=>"cekuang2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Uang Jalan tidak boleh lebih besar dari tarif trucking dan tidak lebih kecil dari BBM"]) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('bbm', 'BBM:') }}
                                            {{ Form::text('bbm', null, ['class'=> 'form-control','id'=>'Bbm', 'placeholder'=>'BBM', 'autocomplete'=>'off','required','onkeyup'=>"cekbbm2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"BBM tidak boleh lebih besar dari tarif trucking dan uang jalan"]) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('bpa', 'B/P/A:') }}
                                            {{ Form::text('bpa', null, ['class'=> 'form-control','id'=>'Bpa', 'placeholder'=>'BPA', 'autocomplete'=>'off','required','onkeyup'=>"cekbpa2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"B/P/A tidak boleh lebih besar dari tarif trucking dan uang jalan"]) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('honor', 'Honor:') }}
                                            {{ Form::text('honor', null, ['class'=> 'form-control','id'=>'Honor', 'placeholder'=>'Honor', 'autocomplete'=>'off','required','onkeyup'=>"cekhonor2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Honor tidak boleh lebih besar dari tarif trucking dan uang jalan"]) }}
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            {{ Form::label('biaya_lain', 'Biaya Lain:') }}
                                            {{ Form::text('biaya_lain', null, ['class'=> 'form-control','id'=>'Biaya', 'placeholder'=>'Biaya', 'autocomplete'=>'off','required','onkeyup'=>"cekbiaya2();",'data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Biaya lain tidak boleh lebih besar dari tarif trucking dan uang jalan"]) }}
                                         </div>
                                    </div> 
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="row">
                                {{ Form::submit('Update data', ['class' => 'btn btn-success','id'=>'submit4']) }}
                                {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                            </div>
                        </div>
                    {!! Form::close() !!}
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


   <div class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No Trucking</th>
                        <th>Gudang</th>
                        <th>Container</th>
                        <th>Size</th>
                        <th>No Seal</th>
                        <th>No SPB</th>
                        <th>Tgl SPB</th>
                        <th>Muatan</th>
                        <th>Colie</th>
                        <th>Tarif</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                     </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-warning">
                            <th class="text-center" colspan="9">Total</th>
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

            /* Button used to open the contact form - fixed at the bottom of the page */
            .hapus-button {
                background-color: #F63F3F;
                bottom: 66px;
            }

            .add-button {
                background-color: #00E0FF;
                bottom: 96px;
            }

            .edit-button {
                background-color: #FDA900;
                bottom: 126px;
            }

            .view-button {
                background-color: #149933;
                bottom: 156px;
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
            @permission('delete-truckingdetail')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapustrucking" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('update-trucking')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="edittrucking" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('add-trucking')
            <button type="button" class="btn btn-success btn-xs view-button" id="viewspb">VIEW DETAIL <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('add-trucking')
            <a href="#" id="editspbkembali"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">EDIT SPB KEMBALI <i class="fa fa-edit"></i></button></a>
            @endpermission
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
            submit.disabled = true;
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

        function gettarif(){
            var kode_gudang= $('#kode_gudang2').val();
            var tgl_spb= $('#Tanggal1').val();

            var submit = document.getElementById("submit");
            $.ajax({
                url:'{!! route('truckingdetail.gettarif') !!}',
                type:'POST',
                data : {
                        'kode_gudang': kode_gudang,
                        'tgl_spb': tgl_spb,
                    },
                success: function(result) {
                        $('#tarif2').val(result.tarif_trucking);
                    },
            });
        }

        function gettariftgl(){
            var kode_gudang= $('#kode_gudang2').val();
            var tgl_spb= $('#Tanggal1').val();

            var submit = document.getElementById("submit");
            $.ajax({
                url:'{!! route('truckingdetail.gettariftgl') !!}',
                type:'POST',
                data : {
                        'kode_gudang': kode_gudang,
                        'tgl_spb': tgl_spb,
                    },
                success: function(result) {
                        $('#tarif2').val(result.tarif_trucking);
                    },
            });
        }

        function tgl2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            console.log(tgl_kembali);
            if (tgl_kembali < tgl_pergi){
                submit4.disabled = true;
            }else{
                submit4.disabled = false;
            }
        }

        function cekuang2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            var tarif_trucking = parseInt($('#Trucking').val());
            var uang_jalan = parseInt($('#Uang').val());
            var bbm = parseInt($('#Bbm').val());

            if(uang_jalan > tarif_trucking || uang_jalan < bbm){
                submit4.disabled = true;
            }else{
                if (tgl_kembali < tgl_pergi){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekbbm2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            var tarif_trucking = parseInt($('#Trucking').val());
            var uang_jalan = parseInt($('#Uang').val());
            var bbm = parseInt($('#Bbm').val());

            if(bbm > tarif_trucking || bbm > uang_jalan){
                submit4.disabled = true;
            }else{
                if (tgl_kembali < tgl_pergi){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekbpa2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            var tarif_trucking = parseInt($('#Trucking').val());
            var uang_jalan = parseInt($('#Uang').val());
            var bpa = parseInt($('#Bpa').val());

            if(bpa > tarif_trucking || bpa > uang_jalan){
                submit4.disabled = true;
            }else{
                if (tgl_kembali < tgl_pergi){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekhonor2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            var tarif_trucking = parseInt($('#Trucking').val());
            var uang_jalan = parseInt($('#Uang').val());
            var honor = parseInt($('#Honor').val());

            if(honor > tarif_trucking || honor > uang_jalan){
                submit4.disabled = true;
            }else{
                if (tgl_kembali < tgl_pergi){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function cekbiaya2(){
            var tgl_pergi = $('#Tanggal').val();
            var tgl_kembali = $('#Kembali').val();
            var tarif_trucking = parseInt($('#Trucking').val());
            var uang_jalan = parseInt($('#Uang').val());
            var biaya = parseInt($('#Biaya').val());

            if(biaya > tarif_trucking || biaya > uang_jalan){
                submit4.disabled = true;
            }else{
                if (tgl_kembali < tgl_pergi){
                    submit4.disabled = true;
                }else{
                    submit4.disabled = false;
                }
            }
        }

        function createTable2(result){

        var my_table = "";


        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.no_spb_manual+"</td>";
                    my_table += "<td>"+row.tgl_kembali+"</td>";
                    my_table += "<td>"+row.kode_mobil+"</td>";
                    my_table += "<td>"+row.kode_sopir+"</td>";
                    my_table += "<td>"+row.kode_pemilik+"</td>";
                    my_table += "<td>"+formatRupiah(row.trucking)+"</td>";
                    my_table += "<td>"+formatRupiah(row.uang_jalan)+"</td>";
                    my_table += "<td>"+formatRupiah(row.bbm)+"</td>";
                    my_table += "<td>"+formatRupiah(row.bpa)+"</td>";
                    my_table += "<td>"+formatRupiah(row.honor)+"</td>";
                    my_table += "<td>"+formatRupiah(row.biaya_lain)+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered table-hover" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No SPB Manual</th>'+
                                '<th>Tanggal SPB Kembali</th>'+
                                '<th>Mobil</th>'+
                                '<th>Sopir</th>'+
                                '<th>Pemilik</th>'+
                                '<th>Tarif Trucking</th>'+
                                '<th>Uang Jalan</th>'+
                                '<th>BBM</th>'+
                                '<th>B/P/A</th>'+
                                '<th>Honor</th>'+
                                '<th>Biaya Lain</th>'+
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
        var no_trucking = $('#notrucking').val();
        var shipper = $('#shipper').val();  
        
        $('#data2-table').DataTable({
                
            processing: true,
            serverSide: true,
            ajax:'http://localhost/gui_front_pbm_laravel/admin/truckingdetail/getDatabyID?id='+no_trucking,
            data:{'no_trucking':no_trucking},
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                console.log(data);
                if (data['tgl_spb'] == null) {
                    $('td', row).css('background-color', '#ffdbd3');
                }else if (data['tgl_kembali'] == null) {
                    $('td', row).css('background-color', '#DCFFBF');
                }
            },
            
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
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        
                    // Update footer
                    $( api.column( 9 ).footer() ).html(
                        'Rp. '+formatRupiah(grandTotal)
                    );
                },

            columns: [
                { data: 'no_trucking', name: 'no_trucking' },
                { data: 'gudangdetail.nama_gudang', "defaultContent": "<i>Not set</i>" },
                { data: 'kode_container', name: 'kode_container' },
                { data: 'sizecontainer.nama_size', "defaultContent": "<i>Not set</i>" },
                { data: 'no_seal', "defaultContent": "<i>Not set</i>" },
                { data: 'no_spb', "defaultContent": "<i>Not set</i>" },
                { data: 'spb.tgl_spb', "defaultContent": "<i>Not set</i>" },
                { data: 'muatan', "defaultContent": "<i>Not set</i>" },
                { data: 'colie', "defaultContent": "<i>Not set</i>" },
                { data: 'tarif_trucking',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'dari', name: 'dari', "searchable": false },
                { data: 'tujuan', name: 'tujuan', "searchable": false },
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
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');

                    closeOpenedRows(table, select);

                    var no_trucking = select.find('td:eq(0)').text();
                    var gudang = select.find('td:eq(1)').text();
                    var seal = select.find('td:eq(4)').text();
                    var muatan = select.find('td:eq(6)').text();
                    var colie = select.find('td:eq(7)').text();
                    var no_spb = select.find('td:eq(5)').text();

                    if(gudang == 'Not set' || seal == 'Not set' || muatan == 'Not set' || colie == 'Not set'){
                        $('.add-button').hide();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').hide();
                    }else{
                        $('.add-button').show();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').show();
                    }
                    
                }
            });

            $('#editspbkembali').click( function () {
                var select = $('.selected').closest('tr');
                var no_trucking = select.find('td:eq(0)').text();
                var no_spb = select.find('td:eq(5)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingdetail.edit_spb') !!}',
                    type: 'POST',
                    data : {
                        'no_trucking': no_trucking,
                        'no_spb': no_spb,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#nospb').val(results.no_spb);
                        $('#Manual').val(results.no_spb_manual);
                        $('#Tanggal').val(results.tgl_spb);
                        $('#Kembali').val(results.tgl_kembali);
                        $('#Namasopir').val(results.kode_sopir);
                        $('#Mobil').val(results.kode_mobil).trigger('change');
                        $('#asset_mobil').val(results.no_asset_mobil).trigger('change');
                        $('#Sopir').val(results.kode_sopir).trigger('change');
                        $('#Pemilik2').val(results.nama_pemilik);
                        $('#kode_pemilik2').val(results.kode_pemilik);
                        $('#Uang').val(results.uang_jalan);
                        $('#Bbm').val(results.bbm);
                        $('#Bpa').val(results.bpa);
                        $('#Honor').val(results.honor);
                        $('#Biaya').val(results.biaya_lain);
                        $('#Trucking').val(results.trucking);
                        $('#editform3').modal('show');
                    }
                });
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

            $('#viewspb').click( function () {
                var select = $('.selected').closest('tr');
                var no_spb = select.find('td:eq(5)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingdetail.showdetailspb') !!}',
                    type: 'POST',
                    data : {
                        'id': no_spb
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.title == 'Gagal'){
                            $.notify(result.message);
                        }
                        else {
                            if ( row.child.isShown() ) {
                                row.child.hide();
                                select.removeClass('shown');
                            }
                            else {
                                closeOpenedRows(table, select);

                                row.child( createTable2(result) ).show();
                                select.addClass('shown');

                                openRows.push(select);
                            }
                        }
                    }
                });
            });

            $('#addspb').click( function () {
                var select = $('.selected').closest('tr');
                var no_trucking = select.find('td:eq(0)').text();
                var no_spb = select.find('td:eq(5)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingdetail.add_spb') !!}',
                    type: 'POST',
                    data : {
                        'no_trucking': no_trucking,
                        'no_spb': no_spb,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Truck1').val(results.no_trucking);
                        $('#Spb1').val(results.no_spb);
                        $('#Manual1').val(results.no_spb_manual);
                        $('#Tanggal1').val(results.tgl_spb);
                        $('#Kembali1').val(results.tgl_kembali);
                        $('#Mobil1').val(results.kode_mobil);
                        $('#Sopir1').val(results.kode_sopir);
                        $('#Pemilik1').val(results.kode_pemilik);
                        $('#Uang1').val(results.uang_jalan);
                        $('#Bbm1').val(results.bbm);
                        $('#Bpa1').val(results.bpa);
                        $('#Honor1').val(results.honor);
                        $('#Biaya1').val(results.biaya_lain);
                        $('#Trucking1').val(results.trucking);
                        $('#editform2').modal('show');
                    }
                });
            });

            $('#edittrucking').click( function () {
                var select = $('.selected').closest('tr');
                var no_trucking = select.find('td:eq(0)').text();
                var no_spb = select.find('td:eq(5)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('truckingdetail.edit_trucking') !!}',
                    type: 'POST',
                    data : {
                        'no_trucking': no_trucking,
                        'no_spb': no_spb
                    },
                    success: function(results) {
                        console.log(results);
                        $('#ID').val(results.id);
                        $('#notrucking2').val(results.no_trucking);
                        $('#namashipper2').val(results.nama_shipper);
                        $('#kode_gudang2').val(results.kode_gudang).trigger('change');
                        $('#container2').val(results.kode_container);
                        $('#kode_size2').val(results.kode_size);
                        $('#namasize2').val(results.nama_size);
                        $('#seal2').val(results.no_seal);
                        $('#spb2').val(results.no_spb);
                        $('#Tanggal1').val(results.tgl_spb);
                        $('#muatan2').val(results.muatan);
                        $('#colie2').val(results.colie);
                        $('#tarif2').val(results.tarif_trucking);
                        $('#dari2').val(results.dari);
                        $('#tujuan2').val(results.tujuan);
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapustrucking').click( function () {
                var select = $('.selected').closest('tr');
                var no_trucking = select.find('td:eq(0)').text();
                var kode_container = select.find('td:eq(2)').text();
                var no_spb = select.find('td:eq(5)').text();
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
                            url: '{!! route('truckingdetail.hapus_trucking') !!}',
                            type: 'POST',
                            data : {
                                'no_trucking': no_trucking,
                                'no_spb': no_spb,
                                'kode_container': kode_container,
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

        function getjor()
        {
            var no_trucking = $('#notrucking').val();
            var no_req_jo = $('#noreqjo').val();
            
                swal({
                    title: "Anda yakin data sudah benar?",
                    text: "Pastikan dahulu No Job Request yang dipilih",
                    type: "question",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, benar!",
                    cancelButtonText: "Batal!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{!! route('truckingdetail.getjor') !!}',
                            type: 'POST',
                            data : {
                                'no_trucking': no_trucking,
                                'no_req_jo': no_req_jo
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
        }

        function pemilik2(){
            var mobil = $('#Mobil').val();
            var no_spb = $('#nospb').val();
            
            $.ajax({
                    url: '{!! route('truckingdetail.pemilik2') !!}',
                    type: 'POST',
                    data : {
                        'kode_mobil': mobil,
                        'no_spb': no_spb,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Pemilik2').val(results.pemilik);
                        $('#Namasopir').val(results.kode_sopir);
                        if(results.pemilik != 'GEMILANG UTAMA INTERNASIONAL, PT'){
                            $('#asset_mobil').val('').trigger('change');
                            $('.form-group2').hide();
                            $('.form-group3').show();
                            $('.form-group1').hide();
                            document.getElementById("Namasopir").required = true;
                            document.getElementById("Sopir").required = false;
                        }else{
                            $('.form-group2').show();
                            $('.form-group3').hide();
                            $('.form-group1').show();
                            $('#asset_mobil').val(results.no_asset_mobil).trigger('change');
                            document.getElementById("Namasopir").required = false;
                            document.getElementById("Sopir").required = true;
                        }
                        $('#kode_pemilik2').val(results.kode_pemilik);
                        console.log($('#kode_pemilik2').val());
                    }
                });
        }

        function formatNumber(n) {
            if(n == 0){
                return 0;
            }else if(n == null){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
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

        function refreshTable() {
             $('#data2-table').DataTable().ajax.reload(null,false);;
        }

        $('#ADD_DETAIL').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
            })
            e.preventDefault();
            var registerForm = $("#ADD_DETAIL");
            var formData = registerForm.serialize();

            // Check if empty of not
            $.ajax({
                    url:'{!! route('truckingdetail.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#kode_gudang').val('').trigger('change');
                        $('#container').val('');
                        $('#kode_size').val('').trigger('change');
                        $('#seal').val('');
                        $('#spb').val('').trigger('change');
                        $('#muatan').val('');
                        $('#colie').val('');
                        $('#tarif').val('');
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
                    url:'{!! route('truckingdetail.updateajax') !!}',
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

        $('#EDIT').submit(function (e) {
            swal({
                    title: "<b>Proses Sedang Berlangsung</b>",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
            })
            e.preventDefault();
            
            var registerForm = $("#EDIT");
            var formData = registerForm.serialize();
                $.ajax({
                    url:'{!! route('truckingdetail.updateajax2') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        $('#editform3').modal('hide');
                        refreshTable();
                        if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                        } else {
                            swal("Gagal!", data.message, "error");
                        }   
                    },
                });
            
        });

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
        }
    </script>
@endpush