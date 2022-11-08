@extends('adminlte::page')

@section('title', 'Job Order')

@section('content_header')
    
@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
@include('sweet::alert')
<style>
    #canvasDiv{
        position: relative;
        border: 2px solid grey;
        height:300px;
        width: 550px;
    }
    
    @media only screen and (max-width: 640px) {
      #canvasDiv {
        position: relative;
        border: 2px solid grey;
        height:275px;
        width: 350px;
      }
    }
</style>
<body onLoad="load()">
    <div class="box box-solid">
        <div class="box-body">
            <div class="box ">
                <div class="box-body">
                    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()" >
                            <i class="fa fa-refresh"></i> Refresh</button>
                    @permission('create-jo')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Job Order</button>
                    @endpermission

                    <!--<button type="button" class="btn btn-primary btn-xs" onclick="getkode()">-->
                    <!--    <i class="fa fa-bullhorn"></i> Get New Kode</button>-->

                    <span class="pull-right">  
                    <?php if (stripos($_SERVER['HTTP_USER_AGENT'], 'Windows') === FALSE){ ?>
                        <!--<button type="button" class="btn bg-orange btn-xs preview-button" data-toggle="modal" data-target="#previewpo"><i class="fa fa-print"></i> Preview PO</button>-->
                        <button type="button" class="btn bg-black btn-xs ttdigi-button" id="addttd" data-toggle="modal" data-target="#ttdform"><i class="fa fa-edit"></i> TTD DIGITAL</button>
                    <?php } ?>
                        <font style="font-size: 16px;"><b>JOB ORDER</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-warning">
                        <th>No JO</th>
                        <th>Tgl JO</th>
                        <th>Type JO</th>
                        <th>No SPK</th>
                        <th>Nama Customer</th>
                        <th>Remark</th>
                        <th>Nama Vendor</th>
                        <!--<th>Kapal</th>-->
                        <th>Type Cargo</th>
                        <!--<th>Tgl Muat</th>-->
                        <!--<th>Tgl Selesai</th>-->
                        <th>PIC Foreman</th>
                        <th>No Reff</th>
                        <th>Tgl Reff</th>
                        <th>Type Kegiatan</th>
                        <th>Status Lokasi</th>
                        <th>Order By</th>
                        <!--<th>Tongkang</th>-->
                        <th>Lokasi Kegiatan</th>
                        <th>Mob Demob</th>
                        <th>No Invoice</th>
                        <th>Status</th>
                        <th>Total Item</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

<div class="modal fade" id="ttdform" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h2>TTD Digital: {{ Form::label('nomoor', null,['id'=>'NomorTTD']) }}</h2>
                        <hr>
                        <div id="canvasDiv"></div>
                        <br>
                        <button type="button" class="btn btn-danger" id="reset-btn">Clear</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn bg-blue" id="btn-save">Simpan (Dibuat Oleh)</button>
                        <!-- &nbsp;&nbsp;
                        <button type="button" class="btn bg-green" id="btn-periksa">Simpan (Diperiksa Oleh)</button> -->
                        <!-- <br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn bg-yellow" id="btn-setuju">Simpan (Accounting)</button>
                        &nbsp;&nbsp;
                        <button type="button" class="btn bg-purple" id="btn-tahu">Simpan (Diterima Oleh)</button> -->
                    </div>
                </div>
            </div>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal JO:') }}
                            {{ Form::date('tgl_joborder', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tanggal1' ,'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type JO:') }}
                            {{ Form::select('type_jo', $TypeJo->sort(), null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'TypeJO1','required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'No SPK:') }}
                            {{ Form::text('no_spk', null, ['class'=> 'form-control','id'=>'NoSpk1', 'placeholder'=>'', 'autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('customers', 'Nama Customer:') }}
                            {{ Form::select('kode_customer',$Customer->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Customer1']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('cons', 'Remark:') }}
                            {{ Form::text('kode_consignee',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Consignee1']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('vendors', 'Nama Vendor:') }}
                            {{ Form::select('kode_vendor',$Vendor->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Vendor1']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type Kegiatan:') }}
                            {{ Form::select('type_kegiatan', ['1' => 'Non Transhipment','2' => 'Transhipment'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Kegiatan1','required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type Cargo:') }}
                            {{ Form::select('type_cargo', $Cargo->sort(), null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'TypeCargo1','required'=>'required'])}}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tanggals', 'Tanggal Muat:') }}-->
                    <!--        {{ Form::date('tgl_muat', null,['class'=> 'form-control','id'=>'Muat1'])}}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tanggals', 'Tanggal Selesai:') }}-->
                    <!--        {{ Form::date('tgl_selesai', null,['class'=> 'form-control','id'=>'Selesai1'])}}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'PIC Foreman:') }}
                            {{ Form::text('bongkar_muat_via', null, ['class'=> 'form-control','id'=>'Bongkar1', 'placeholder'=>'', 'autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Kapals', 'Nama Kapal:') }}-->
                    <!--        {{ Form::select('kode_kapal',$Kapal->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Kapal1']) }}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Status Lokasi:') }}
                            {{ Form::select('status_lokasi', ['1' => 'Dalam Kota','2' => 'Luar Kota'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'StatLokasi1','required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('orders', 'Order By:') }}
                            {{ Form::text('order_by', null, ['class'=> 'form-control','id'=>'Order1', 'placeholder'=>'Order By', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tongkangs', 'Tongkang:') }}-->
                    <!--        {{ Form::select('tongkang',$Tongkang->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Tongkang1']) }}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('loks', 'Lokasi Kegiatan:') }}
                            {{ Form::text('lokasi', null, ['class'=> 'form-control','id'=>'Lokasi1', 'placeholder'=>'Lokasi', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('mobs', 'Mob Demob:') }}
                            {{ Form::text('mob_demob', null, ['class'=> 'form-control','id'=>'Mob1', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('periods', 'Periode:') }}
                            {{ Form::text('periode', null, ['class'=> 'form-control','id'=>'Periode1', 'placeholder'=>'Periode', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('reffs', 'No. Kontrak:') }}
                            {{ Form::text('no_reff', null, ['class'=> 'form-control','id'=>'NoReff1', 'placeholder'=>'', 'autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tgl Kontrak:') }}
                            {{ Form::date('tgl_reff', null,['class'=> 'form-control','id'=>'TglReff1'])}}
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
                    {{ Form::hidden('no_joborder',null, ['class'=> 'form-control','id'=>'Joborder2','readonly']) }}
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal JO:') }}
                            {{ Form::date('tgl_joborder', null,['class'=> 'form-control','id'=>'Tanggal2' ,'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type JO:') }}
                            {{ Form::select('type_jo', $TypeJo->sort(), null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'TypeJO2','required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'No SPK:') }}
                            {{ Form::text('no_spk', null, ['class'=> 'form-control','id'=>'NoSpk2', 'placeholder'=>'', 'autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('customers', 'Nama Customer:') }}
                            {{ Form::select('kode_customer',$Customer->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Customer2','required']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('cons', 'Remark:') }}
                            {{ Form::text('kode_consignee',null, ['class'=> 'form-control','style'=>'width: 100%','placeholder' => '','id'=>'Consignee2']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('vendors', 'Nama Vendor:') }}
                            {{ Form::select('kode_vendor',$Vendor->sort(), null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Vendor2']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type Kegiatan:') }}
                            {{ Form::select('type_kegiatan', ['1' => 'Non Transhipment','2' => 'Transhipment'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Kegiatan2','required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Type Cargo:') }}
                            {{ Form::select('type_cargo', $Cargo->sort(), null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'TypeCargo2','required'=>'required'])}}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tanggals', 'Tanggal Muat:') }}-->
                    <!--        {{ Form::date('tgl_muat', null,['class'=> 'form-control','id'=>'Muat2'])}}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tanggals', 'Tanggal Selesai:') }}-->
                    <!--        {{ Form::date('tgl_selesai', null,['class'=> 'form-control','id'=>'Selesai2'])}}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('bongkar', 'PIC Foreman:') }}
                            {{ Form::text('bongkar_muat_via', null, ['class'=> 'form-control','id'=>'Bongkar2', 'placeholder'=>'', 'autocomplete'=>'off','onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Kapals', 'Nama Kapal:') }}-->
                    <!--        {{ Form::select('kode_kapal',$Kapal->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Kapal2']) }}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Types', 'Status Lokasi:') }}
                            {{ Form::select('status_lokasi', ['1' => 'Dalam Kota','2' => 'Luar Kota'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'StatLokasi2','required'=>'required'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('orders', 'Order By:') }}
                            {{ Form::text('order_by', null, ['class'=> 'form-control','id'=>'Order2', 'placeholder'=>'Order By', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        {{ Form::label('Tongkangs', 'Tongkang:') }}-->
                    <!--        {{ Form::select('tongkang',$Tongkang->sort(),null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Tongkang2']) }}-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('loks', 'Lokasi:') }}
                            {{ Form::text('lokasi', null, ['class'=> 'form-control','id'=>'Lokasi2', 'placeholder'=>'Lokasi', 'autocomplete'=>'off','required', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('mobs', 'Mob Demob:') }}
                            {{ Form::text('mob_demob', null, ['class'=> 'form-control','id'=>'Mob2', 'placeholder'=>'', 'autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('periods', 'Periode:') }}
                            {{ Form::text('periode', null, ['class'=> 'form-control','id'=>'Periode2', 'placeholder'=>'Periode', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('reffs', 'No Reff:') }}
                            {{ Form::text('no_reff', null, ['class'=> 'form-control','id'=>'NoReff2', 'placeholder'=>'', 'autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal Reff:') }}
                            {{ Form::date('tgl_reff', null,['class'=> 'form-control','id'=>'TglReff2'])}}
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
            .add2-button {
                background-color: #00E0FF;
                bottom: 66px;
            }

            .add-button {
                background-color: #00E0FF;
                bottom: 96px;
            }

            .hapus-button {
                background-color: #F63F3F;
                bottom: 126px;
            }

            .edit-button {
                background-color: #FDA900;
                bottom: 156px;
            }

            .view-button {
                background-color: #1674c7;
                bottom: 186px;
            }

            .viewjor-button {
                background-color: #00E0FF;
                bottom: 216px;
            }

            .tombol1 {
                background-color: #149933;
                bottom: 246px;
            }

            .tombol2 {
                background-color: #ff9900;
                bottom: 276px;
            }

            .printjr-button {
                background-color: #f44336;
                bottom: 306px;
            }

            .printjrequest-button {
                background-color: #4bb543;
                bottom: 336px;
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

            .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(https://img.pikbest.com/png-images/20190918/cartoon-snail-loading-loading-gif-animation_2734139.png!bw340) center no-repeat #fff;
            }
        </style>

        <div id="mySidenav" class="sidenav">
            @permission('update-jo')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editjoborder" data-toggle="modal" data-target="">EDIT<i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-jo')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusjoborder" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
            @endpermission

            @permission('add-jo')
            <a href="#" id="addjo"><button type="button" class="btn btn-info btn-xs add-button" data-toggle="modal" data-target="">ADD DETAIL <i class="fa fa-plus"></i></button></a>
            <a href="#" id="addjor"><button type="button" class="btn bg-black btn-xs add2-button" data-toggle="modal" data-target="">JOB REQUEST <i class="fa fa-plus"></i></button></a>
            @endpermission
            
            @permission('post-jo')
            <button type="button" class="btn btn-success btn-xs tombol1" id="button1">POST <i class="fa fa-bullhorn"></i></button>
            @endpermission
            
            @permission('unpost-jo')
            <button type="button" class="btn btn-warning btn-xs tombol2" id="button2">UNPOST <i class="fa fa-undo"></i></button>
            @endpermission

            @permission('view-jo')
            <button type="button" class="btn btn-primary btn-xs view-button" id="button5">VIEW DETAIL <i class="fa fa-eye"></i></button>
            <button type="button" class="btn bg-black btn-xs viewjor-button" id="button6">VIEW JOBREQUEST <i class="fa fa-eye"></i></button>
            @endpermission

            @permission('print-jo')
            <a href="#" target="_blank" id="printjr"><button type="button" class="btn btn-danger btn-xs printjr-button" id="button11">PRINT JO<i class="fa fa-print"></i></button></a>
            <a href="#" target="_blank" id="printjrequest"><button type="button" class="btn btn-success btn-xs printjrequest-button" id="button12">PRINT JOB REQUEST<i class="fa fa-print"></i></button></a>
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
            $('.add-button').hide();
            $('.add2-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.ttdigi-button').hide();
            $('.viewjor-button').hide();
            $('.printjr-button').hide();
            $('.printjrequest-button').hide();
            $('.back2Top').show();
        }

        function getkode(){
            swal({
                title: "Get New Kode Customer?",
                text: "Customer",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Ya, Update!",
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
                                
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url:'{!! route('joborder.getkode') !!}',
                        type:'POST',
                        success: function(result) {
                            swal("Berhasil!", result.message, "success");
                            refreshTable();
                        },
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
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
            ajax: '{!! route('joborder.data') !!}',
            fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                if (data['status'] == "1") {
                    $('td', row).css('background-color', '#ffdbd3');
                }
            },
            columns: [
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'tgl_joborder', name: 'tgl_joborder' },
                { data: 'type_jo', 
                    render: function( data, type, full ) {
                    return type_jo(data); }
                },
                { data: 'no_spk', name: 'no_spk' },
                { data: 'customer1.nama_customer', "defaultContent": "<i>Not set</i>" },
                { data: 'customer2.nama_customer', "defaultContent": "<i>Not set</i>" },
                { data: 'vendor.nama_vendor', "defaultContent": "<i>Not set</i>" },
                // { data: 'kapal.nama_kapal', "defaultContent": "<i>Not set</i>" },
                { data: 'type_cargo', 
                    render: function( data, type, full ) {
                    return type_cargo(data); }
                },
                // { data: 'tgl_muat', name: 'tgl_muat' },
                // { data: 'tgl_selesai', "defaultContent": "<i>Not set</i>" },
                { data: 'bongkar_muat_via', name: 'bongkar_muat_via' },
                { data: 'no_reff', "defaultContent": "<i>Not set</i>" },
                { data: 'tgl_reff', "defaultContent": "<i>Not set</i>" },
                { data: 'type_kegiatan', 
                    render: function( data, type, full ) {
                    return type_kegiatan(data); }
                },
                { data: 'status_lokasi',  
                    render: function( data, type, full ) {
                    return status_lokasi(data); }
                },
                { data: 'order_by', name: 'order_by' },
                // { data: 'tongkangs.nama_kapal', "defaultContent": "<i>Not set</i>" },
                { data: 'lokasi', name: 'lokasi' },
                { data: 'mob_demob', "defaultContent": "<i>0</i>" },
                { data: 'no_invoice', "defaultContent": "<i>Not set</i>" },
                { data: 'status', 
                    render: function( data, type, full ) {
                    return formatStatus(data); }
                },
                { data: 'total_item', name: 'total_item', visible: false },
            ]
            });
        });
        
        function type_jo(n) {
            if(n == '1'){
                var stat = "<span style='color:#030100'><b>Bongkar Muat Curah</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Bongkar Muat Non Curah</b></span>";
            }else if(n == '3'){
                var stat = "<span style='color:#c91a1a'><b>Rental Alat</b></span>";
            }else if(n == '4'){
                var stat = "<span style='color:#1a80c9'><b>Trucking</b></span>";
            }else if(n == '5'){
                var stat = "<span style='color:#1a80c9'><b>Lain-lain</b></span>";
            }
            return stat;
        }

        function type_cargo(n) {
            if(n == '1'){
                var stat = "<span style='color:#030100'><b>Batu Bara</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Batu Splite</b></span>";
            }else if(n == '3'){
                var stat = "<span style='color:#c91a1a'><b>Kayu</b></span>";
            }else if(n == '4'){
                var stat = "<span style='color:#1a80c9'><b>Bongkar Muat</b></span>";
            }else if(n == '5'){
                var stat = "<span style='color:#1a80c9'><b>Crane dan Alat</b></span>";
            }else if(n == '6'){
                var stat = "<span style='color:#1a80c9'><b>Lain-lain</b></span>";
            }else if(n == '7'){
                var stat = "<span style='color:#1a80c9'><b>Trucking</b></span>";
            }else if(n == '8'){
                var stat = "<span style='color:#1a80c9'><b>MDF</b></span>";
            }
            return stat;
        }

        function type_kegiatan(n) {
            if(n == '1'){
                var stat = "<span style='color:#c91a1a'><b>Non Transhipment</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Transhipment</b></span>";
            }
            return stat;
        }

        function status_lokasi(n) {
            if(n == '1'){
                var stat = "<span style='color:#1a80c9'><b>Dalam Kota</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>Luar Kota</b></span>";
            }
            return stat;
        }

        function formatStatus(n) {
            if(n == '1'){
                var stat = "<span style='color:#030100'><b>OPEN</b></span>";
            }else if(n == '2'){
                var stat = "<span style='color:#0eab25'><b>POSTED</b></span>";
            }else if(n == '3'){
                var stat = "<span style='color:#c91a1a'><b>INVOICED</b></span>";
            }else if(n == '4'){
                var stat = "<span style='color:#1a80c9'><b>PAID</b></span>";
            }
            return stat;
        }

        function formatNumber(n) {
            if(n == 0){
                return 0;
            }else{
                return n.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function createTable(result){

        var my_table = "";

        $.each( result, function( key, row ) {
                    my_table += "<tr>";
                    my_table += "<td>"+row.deskripsi+"</td>";
                    my_table += "<td>"+parseFloat(row.qty).toFixed(3)+"</td>";
                    my_table += "<td>"+row.satuan+"</td>";
                    my_table += "<td>"+formatNumber(parseFloat(row.harga).toFixed(2))+"</td>";
                    my_table += "<td>"+formatNumber(parseFloat(row.mob_demob).toFixed(2))+"</td>";
                    my_table += "<td>"+formatNumber(parseFloat(row.total_harga).toFixed(2))+"</td>";
                    my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>Deskripsi</th>'+
                                '<th>Qty</th>'+
                                '<th>Satuan</th>'+
                                '<th>Harga</th>'+
                                '<th>Mob Demob</th>'+
                                '<th>Total Harga</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '</table>';

                    // $(document).append(my_table);
            
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        function createTable2(result){

        var my_table = "";

        $.each( result, function( key, row ) {
                my_table += "<tr>";
                    my_table += "<td>"+row.kode_alat+"</td>";
                    my_table += "<td>"+row.tgl_request+"</td>";
                    my_table += "<td>"+row.no_jobrequest+"</td>";
                my_table += "</tr>";
            });

            my_table = '<table id="table-fixed" class="table table-bordered" cellpadding="5" cellspacing="0" border="1" style="padding-left:50px; font-size:12px">'+ 
                        '<thead>'+
                           ' <tr class="bg-info">'+
                                '<th>No. Asset Alat</th>'+
                                '<th>Tgl Request</th>'+
                                '<th>No JOR</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>' + my_table + '</tbody>'+
                        '</table>';
                    // $(document).append(my_table);
            console.log(my_table);
            return my_table;
            // mytable.appendTo("#box");           
        
        }

        function formatRupiah(angka, prefix='Rp'){
           
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
           
        }

        $('#editform').on('show.bs.modal', function () {
            var optionVal = $("#Jenis").val();
            if (optionVal == '1' || optionVal == '2' || optionVal == '3') {
                $('.form-group20').show();
                $('.form-group21').show();
                $('.form-group22').show();
                $('.form-group23').show();
                $('.form-group24').show();
                $('.form-group25').show();
                $('.form-group26').show();
                $('.form-group27').show();
                $('.form-group28').show();
                $('.form-group29').show();
                $('.form-group30').show();
                $('.form-group31').show();
                $('.form-group32').show();
                $('.form-group33').show();
                $('.form-group34').show();
                $('.form-group35').show();
                $('.form-group36').show();
                $('.form-group37').show();
                $('.form-group38').show();
            }else{
                $('.form-group20').show();
                $('.form-group21').show();
                $('.form-group22').show();
                $('.form-group23').show();
                $('.form-group24').show();
                $('.form-group25').show();
                $('.form-group26').hide();
                $('.form-group27').hide();
                $('.form-group28').hide();
                $('.form-group29').hide();
                $('.form-group30').hide();
                $('.form-group31').hide();
                $('.form-group32').hide();
                $('.form-group33').hide();
                $('.form-group34').hide();
                $('.form-group35').hide();
                $('.form-group36').hide();
                $('.form-group37').hide();
                $('.form-group38').hide();
            }
        })

        $(document).ready(function(){   
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            var table = $('#data-table').DataTable();

            $('#data-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray text-bold') ) {
                    $(this).removeClass('selected bg-gray text-bold');
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                    $('.add2-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('.ttdigi-button').hide();
                    $('.viewjor-button').hide();
                    $('.printjr-button').hide();
                    $('.printjrequest-button').hide();
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data-table').DataTable().row(select).data();

                    closeOpenedRows(table, select);
                    
                    $('.tombol1').hide();
                    $('.tombol2').hide();
                    $('.hapus-button').hide();
                    $('.add-button').hide();
                    $('.add2-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('.ttdigi-button').hide();
                    $('.viewjor-button').hide();
                    $('.printjr-button').hide();
                    $('.printjrequest-button').hide();
                    
                    var no_joborder = data['no_joborder'];
                    var status = data['status'];
                    var item = data['total_item'];
                    var selesai = data['tgl_selesai'];
                    var type = data['type_jo'];
                    var add = $("#addjo").attr("href",window.location.href+"/"+no_joborder+"/detail");
                    var add2 = $("#addjor").attr("href",window.location.href+"/"+no_joborder+"/detail2");
                    var print = $("#printjr").attr("href",window.location.href+"/exportpdf?no_joborder="+no_joborder);
                    var printjr = $("#printjrequest").attr("href",window.location.href+"/exportpdf2?no_joborder="+no_joborder);

                    document.getElementById("NomorTTD").innerHTML = no_joborder;

                    if(status == '2' && type != '4'){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.add-button').hide();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.ttdigi-button').show();
                        $('.viewjor-button').hide();
                        $('.printjr-button').show();
                        $('.printjrequest-button').hide();
                    }else if(status =='2' && type == '4'){
                        $('.tombol1').hide();
                        $('.tombol2').show();
                        $('.add-button').hide();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.ttdigi-button').show();
                        $('.viewjor-button').show();
                        $('.printjr-button').show();
                        $('.printjrequest-button').show();
                    }else if(status =='1' && item < 1 && type == '4'){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.add2-button').hide();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').hide();
                        $('.ttdigi-button').hide();
                        $('.viewjor-button').hide();
                        $('.printjr-button').hide();
                        $('.printjrequest-button').hide();
                    }else if (status == '1' && item > 0 && type == '4'){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.ttdigi-button').hide();
                        $('.viewjor-button').show();
                        $('.printjr-button').hide();
                        $('.printjrequest-button').hide();
                    }else if (status =='1' && item < 1){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.add2-button').hide();
                        $('.hapus-button').show();
                        $('.edit-button').show();
                        $('.view-button').hide();
                        $('.viewjor-button').hide();
                        $('.ttdigi-button').hide();
                        $('.printjr-button').hide();
                        $('.printjrequest-button').hide();
                    }else if (status == '1' && item > 0){
                        $('.tombol1').show();
                        $('.tombol2').hide();
                        $('.add-button').show();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.viewjor-button').hide();
                        $('.ttdigi-button').hide();
                        $('.printjr-button').hide();
                        $('.printjrequest-button').hide();
                    }else if (status == '3' && type == '4'){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').hide();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.viewjor-button').show();
                        $('.ttdigi-button').hide();
                        $('.printjr-button').show();
                        $('.printjrequest-button').show();
                    }else if (status == '3' && type != '4'){
                        $('.tombol1').hide();
                        $('.tombol2').hide();
                        $('.add-button').hide();
                        $('.add2-button').hide();
                        $('.hapus-button').hide();
                        $('.edit-button').hide();
                        $('.view-button').show();
                        $('.viewjor-button').hide();
                        $('.ttdigi-button').hide();
                        $('.printjr-button').show();
                        $('.printjrequest-button').hide();
                    }
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

            //TTD DIGITAL
            var canvasDiv = document.getElementById('canvasDiv');
            var canvas = document.createElement('canvas');
            canvas.setAttribute('id', 'canvas');

            canvasDiv.appendChild(canvas);
            $("#canvas").attr('height', $("#canvasDiv").outerHeight());
            $("#canvas").attr('width', $("#canvasDiv").width());
            if (typeof G_vmlCanvasManager != 'undefined') {
                canvas = G_vmlCanvasManager.initElement(canvas);
            }

            context = canvas.getContext("2d");
            $('#canvas').mousedown(function(e) {
                var offset = $(this).offset()
                var mouseX = e.pageX - this.offsetLeft;
                var mouseY = e.pageY - this.offsetTop;

                paint = true;
                addClick(e.pageX - offset.left, e.pageY - offset.top);
                redraw();
            });

            $('#canvas').mousemove(function(e) {
                if (paint) {
                    var offset = $(this).offset()
                    //addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                    addClick(e.pageX - offset.left, e.pageY - offset.top, true);
                    console.log(e.pageX, offset.left, e.pageY, offset.top);
                    redraw();
                }
            });

            $('#canvas').mouseup(function(e) {
                paint = false;
            });

            $('#canvas').mouseleave(function(e) {
                paint = false;
            });

            var clickX = new Array();
            var clickY = new Array();
            var clickDrag = new Array();
            var paint;

            function addClick(x, y, dragging) {
                clickX.push(x);
                clickY.push(y);
                clickDrag.push(dragging);
            }

            $("#reset-btn").click(function() {
                context.clearRect(0, 0, window.innerWidth, window.innerWidth);
                clickX = [];
                clickY = [];
                clickDrag = [];
            });

            $(document).on('click', '#btn-save', function() {
                var mycanvas = document.getElementById('canvas');
                var img = mycanvas.toDataURL("image/png");

                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_joborder = data['no_joborder'];
                $.ajax({
                    url: '{!! route('joborder.ttd_buat') !!}',
                    type: 'POST',
                    data : {
                        'no': no_joborder,
                        'img': img,
                    },
                    success: function(results) {
                        context.clearRect(0, 0, window.innerWidth, window.innerWidth);
                        clickX = [];
                        clickY = [];
                        clickDrag = [];
                        if (results.success == true) {
                            swal("Berhasil!", results.message, "success");
                        }
                    }
                });
            });

            $(document).on('click', '#btn-periksa', function() {
                var mycanvas = document.getElementById('canvas');
                var img = mycanvas.toDataURL("image/png");

                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_joborder = data['no_joborder'];
                $.ajax({
                    url: '{!! route('joborder.ttd_periksa') !!}',
                    type: 'POST',
                    data : {
                        'no': no_joborder,
                        'img': img,
                    },
                    success: function(results) {
                        context.clearRect(0, 0, window.innerWidth, window.innerWidth);
                        clickX = [];
                        clickY = [];
                        clickDrag = [];
                        if (results.success == true) {
                            swal("Berhasil!", results.message, "success");
                        }
                    }
                });
            });

            var drawing = false;
            var mousePos = {
                x: 0,
                y: 0
            };
            var lastPos = mousePos;

            canvas.addEventListener("touchstart", function(e) {
                mousePos = getTouchPos(canvas, e);
                var touch = e.touches[0];
                var mouseEvent = new MouseEvent("mousedown", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            }, false);

            canvas.addEventListener("touchend", function(e) {
                var mouseEvent = new MouseEvent("mouseup", {});
                canvas.dispatchEvent(mouseEvent);
            }, false);

            canvas.addEventListener("touchmove", function(e) {
                var touch = e.touches[0];
                var offset = $('#canvas').offset();
                var mouseEvent = new MouseEvent("mousemove", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            }, false);

            // Get the position of a touch relative to the canvas
            function getTouchPos(canvasDiv, touchEvent) {
                var rect = canvasDiv.getBoundingClientRect();
                return {
                    x: touchEvent.touches[0].clientX - rect.left,
                    y: touchEvent.touches[0].clientY - rect.top
                };
            }

            var elem = document.getElementById("canvas");

            var defaultPrevent = function(e) {
                e.preventDefault();
            }
            elem.addEventListener("touchstart", defaultPrevent);
            elem.addEventListener("touchmove", defaultPrevent);

            function redraw() {
                //
                lastPos = mousePos;
                for (var i = 0; i < clickX.length; i++) {
                    context.beginPath();
                    if (clickDrag[i] && i) {
                        context.moveTo(clickX[i - 1], clickY[i - 1]);
                    } else {
                        context.moveTo(clickX[i] - 1, clickY[i]);
                    }
                    context.lineTo(clickX[i], clickY[i]);
                    context.lineWidth = 5;
                    context.closePath();
                    context.stroke();
                }
            }

            $('#button1').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_joborder = data['no_joborder'];
                console.log(no_joborder);
                swal({
                    title: "Post?",
                    text: no_joborder,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Posting!",
                    cancelButtonText: "Batal",
                    reverseButtons: !0
                    }).then(function (e) {
                        if (e.value === true) {
                            swal({
                            title: "<b>Proses Sedang Berlangsung</b>",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                            })
                            
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // alert( table.rows('.selected').data().length +' row(s) selected' );
                        $.ajax({
                            url: '{!! route('joborder.post') !!}',
                            type: 'POST',
                            data : {
                                'id': no_joborder
                            },
                            success: function(result) {
                                console.log(result);
                                if (result.success === true) {
                                    swal(
                                    'Posted!',
                                    'Your file has been posted.',
                                    'success'
                                    )
                                    refreshTable();
                                }
                                else{
                                  swal({
                                      title: 'Error',
                                      text: result.message,
                                      type: 'error',
                                  })
                                }
                            },
                            error : function () {
                              swal({
                                  title: 'Oops...',
                                  text: 'Gagal',
                                  type: 'error',
                                  timer: '1500'
                              })
                            }
                        });
                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                })
            });

            $('#button2').click( function () {
                var select = $('.selected').closest('tr');
                var colom = select.find('td:eq(0)').text();
                var no_joborder = colom;
                console.log(no_joborder);
                swal({
                    title: "Unpost?",
                    text: colom,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Unpost!",
                    cancelButtonText: "Batal",
                    reverseButtons: !0
                    }).then(function (e) {
                        if (e.value === true) {
                            swal({
                            title: "<b>Proses Sedang Berlangsung</b>",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                            })
                            
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: '{!! route('joborder.unpost') !!}',
                            type: 'POST',
                            data : {
                                'id': no_joborder
                            },
                            success: function(result) {
                                console.log(result);
                                if (result.success === true) {
                                    swal(
                                    'Unposted!',
                                    'Data berhasil di Unpost.',
                                    'success'
                                    )
                                    refreshTable();
                                }
                                else{
                                  swal({
                                      title: 'Error',
                                      text: result.message,
                                      type: 'error',
                                  })
                                }
                            },
                            error : function () {
                              swal({
                                  title: 'Oops...',
                                  text: data.message,
                                  type: 'error',
                                  timer: '1500'
                              })
                            }
                        });
                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                })
            });

            $('#button5').click( function () {
                var select = $('.selected').closest('tr');
                var no_joborder = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('joborder.showdetail') !!}',
                    type: 'POST',
                    data : {
                        'id': no_joborder
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.title == 'Gagal'){
                            $.notify(result.message);
                        }else{
                            if ( row.child.isShown() ) {
                                row.child.hide();
                                select.removeClass('shown');
                            }
                            else {
                                closeOpenedRows(table, select);

                                row.child( createTable(result) ).show();
                                select.addClass('shown');

                                openRows.push(select);
                            }
                        }
                    }
                });
            });

            $('#button6').click( function () {
                var select = $('.selected').closest('tr');
                var no_joborder = select.find('td:eq(0)').text();
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('joborder.showdetailjor') !!}',
                    type: 'POST',
                    data : {
                        'id': no_joborder
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.title == 'Gagal'){
                            $.notify(result.message);
                        }else{
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

            $('#editjoborder').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var no_joborder = data['no_joborder'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('joborder.edit_joborder') !!}',
                    type: 'POST',
                    data : {
                        'no_joborder': no_joborder
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Joborder2').val(results.no_joborder);
                        $('#Tanggal2').val(results.tgl_joborder);
                        $('#NoSpk2').val(results.no_spk);
                        $('#Customer2').val(results.kode_customer).trigger('change');
                        $('#Vendor2').val(results.kode_vendor).trigger('change');
                        $('#Consignee2').val(results.kode_consignee).trigger('change');
                        $('#Order2').val(results.order_by);
                        $('#Kapal2').val(results.kode_kapal).trigger('change');
                        $('#Tongkang2').val(results.tongkang).trigger('change');
                        $('#Muat2').val(results.tgl_muat);
                        $('#Selesai2').val(results.tgl_selesai);
                        $('#NoReff2').val(results.no_reff);
                        $('#TglReff2').val(results.tgl_reff);
                        $('#TypeJO2').val(results.type_jo).trigger('change');
                        $('#TypeCargo2').val(results.type_cargo).trigger('change');
                        $('#StatLokasi2').val(results.status_lokasi).trigger('change');
                        $('#Bongkar2').val(results.bongkar_muat_via);
                        $('#Kegiatan2').val(results.type_kegiatan).trigger('change');
                        $('#Lokasi2').val(results.lokasi);
                        $('#Mob2').val(results.mob_demob);
                        $('#Periode2').val(results.periode);
                        $('#editform').modal('show');
                    }
         
                });
            });

            $('#hapusjoborder').click( function () {
                var select = $('.selected').closest('tr');
                var no_joborder = select.find('td:eq(0)').text();
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
                            url: '{!! route('joborder.hapus_joborder') !!}',
                            type: 'POST',
                            data : {
                                'id': no_joborder
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
            $('.tombol1').hide();
            $('.tombol2').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.view-button').hide();
            $('.viewjor-button').hide();
            $('.viewpenyelesaian-button').hide();
            $('.viewbiaya-button').hide();
            $('.viewkasbon-button').hide();
            $('.viewaju-button').hide();
            $('.ttdigi-button').hide();
            $('.printjr-button').hide();
            $('.printjrequest-button').hide();
            $('.add-button').hide();

            $('.penyelesaian-button').hide();
            $('.biaya-button').hide();
            $('.kasbon-button').hide();
            $('.aju-button').hide();
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
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            console.log(formData);
            $.ajax({
                url:'{!! route('joborder.store') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#Tanggal1').val(today);
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
                    url:'{!! route('joborder.updateajax') !!}',
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