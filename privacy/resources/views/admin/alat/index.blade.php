@extends('adminlte::page')

@section('title', 'Alat')

@section('content_header')

@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="32x32">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@include('sweet::alert')
<body onLoad="load()">
    <div class="box box-solid">
        <div class="box-body">
            <div class="box ">
                <div class="box-body">
                    @permission('create-alat')
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Alat</button>
                    @endpermission

                    <span class="pull-right">  
                        <font style="font-size: 16px;"><b>ALAT</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="alat-table" width="100%" style="font-size: 12px;">
                    <thead>
                    <tr class="bg-blue">
                        <th>Kode Alat</th>
                        <th>Nama Alat</th>
                        <th>Merk</th>
                        <th>Type</th>
                        <th>Kapasitas (TON)</th>
                        <th>Tahun</th>
                        <th>No Asset</th>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('nama_alat', 'Nama Alat:') }}
                                    {{ Form::text('nama_alat', null, ['class'=> 'form-control','id'=>'Nama1', 'placeholder'=>'Nama Alat','required'=>'required', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('merk', 'Merk:') }}
                                    {{ Form::text('merk', null, ['class'=> 'form-control','id'=>'Nama2','required'=>'required', 'placeholder'=>'Merk', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('type', 'Type:') }}
                                    {{ Form::text('type', null, ['class'=> 'form-control','id'=>'Nama3','required'=>'required', 'placeholder'=>'', 'style'=>'width: 100%','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('tahun', 'Tahun:') }}
                                    {{ Form::selectYear('tahun', 2000, 2040, null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Nama4','required'=>'required', 'autocomplete'=>''])}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('kapasitas', 'Kapasitas:') }}
                                    {{ Form::text('kapasitas', null, ['class'=> 'form-control','id'=>'kapasitas1', 'placeholder'=>'Kapasitas (Ton)', 'autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('no_asset', 'No Asset:') }}
                                    {{ Form::text('no_asset_alat', null, ['class'=> 'form-control','id'=>'Asset1', 'placeholder'=>'No. Asset', 'autocomplete'=>'off','data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 15 Karakter", 'maxlength'=>'15','required'=>'required', 'onkeypress'=>"return pulsar(event,this)"]) }}
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


    <div class="modal fade" id="editform" tabindex="-1" role="dialog">
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
                    {{ Form::hidden('kode_alat', null, ['class'=> 'form-control','id'=>'Kode','readonly']) }}
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('nama_alat', 'Nama Alat:') }}
                            {{ Form::text('nama_alat', null, ['class'=> 'form-control','id'=>'Nama','required'=>'required', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('merk', 'Merk:') }}
                            {{ Form::text('merk', null, ['class'=> 'form-control','id'=>'Merk','required'=>'required', 'autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('type', 'Type:') }}
                            {{ Form::text('type', null, ['class'=> 'form-control','id'=>'Type','required'=>'required', 'style'=>'width: 100%','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('kapasitas', 'Kapasitas:') }}
                            {{ Form::text('kapasitas', null, ['class'=> 'form-control','id'=>'kapasitas2', 'placeholder'=>'Kapasitas (Ton)', 'autocomplete'=>'off','onkeypress'=>"return hanyaAngka(event)"]) }}
                            </div>
                        </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('tahun', 'Tahun:') }}
                            {{ Form::selectYear('tahun', 2000, 2040, null, ['class'=> 'form-control','id'=>'Tahun','required'=>'required', 'autocomplete'=>'off'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('no_asset', 'No Asset:') }}
                            {{ Form::text('no_asset_alat', null, ['class'=> 'form-control','id'=>'Asset', 'autocomplete'=>'off','data-toggle'=>"tooltip",'data-placement'=>"bottom",'title'=>"Maksimal 15 Karakter", 'maxlength'=>'15', 'onkeypress'=>"return pulsar(event,this)"]) }}
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

    <div class="modal fade" id="addpremi" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style=" height: 1%; border: none">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="box-body"> 
                    <div class="addform">
                        @include('errors.validation')
                        {!! Form::open(['id'=>'ADD_PREMI']) !!}
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Nama', 'Nama Alat:') }}
                                {{ Form::text('nama_alat', null, ['class'=> 'form-control','id'=>'NamaAlat1','required'=>'required', 'autocomplete'=>'off', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                                {{ Form::date('tgl_berlaku', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tgl1','required'])}}
                            </div>
                        </div>
                        <div class="col-md-12">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Premi Per Jam Non Transhipment:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('premi_jam_nontranshipment',null, ['class'=> 'form-control','id'=>'NonTrans1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Premi Per Jam Transhipment:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('premi_jam_transhipment',null, ['class'=> 'form-control','id'=>'Trans1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Premi Opr Tembak:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('premi_opr_tembak',null, ['class'=> 'form-control','id'=>'Opr1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('libur', 'Tambahan kerja hari libur:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('hari_libur',null, ['class'=> 'form-control','id'=>'Libur1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        {{ Form::hidden('kode_alat', null, ['class'=> 'form-control','readonly','id'=>'KodeAlat1']) }}
                        {{ Form::hidden('id', null, ['class'=> 'form-control','readonly','id'=>'id1']) }}
                        <div class="col-md-12">
                            <span class="pull-right"> 
                                {{ Form::submit('Add Item', ['class' => 'btn btn-primary btn-xs simpanbutton','id'=>'submit']) }}  
                                <button type="button" class="btn btn-info btn-xs editbutton" id="editpremi" data-toggle="modal" data-target="">
                                    <i class="fa fa-edit"></i> EDIT
                                </button>
                                <button type="button" class="btn btn-danger btn-xs hapusbutton" id="hapuspremi">
                                    <i class="fa fa-times-circle"></i> HAPUS
                                </button>
                                <button type="button" class="btn btn-success btn-xs postbutton" id="postpayment">
                                    <i class="fa fa-times-circle"></i> POST
                                </button>
                                <button type="button" class="btn btn-warning btn-xs unpostbutton" id="unpostpayment">
                                    <i class="fa fa-times-circle"></i> UNPOST
                                </button>
                                <button type="button" class="btn btn-info btn-xs zoombutton" id="detailjurnal2" data-toggle="modal" data-target="#addjurnalform2">
                                    <i class="fa fa-eye"></i> ZOOM JURNAL
                                </button>
                                <a href="#" target="_blank" id="printpay"><button type="button" class="btn btn-danger btn-xs printpaybutton" id="button6">PRINT <i class="fa fa-print"></i></button></a>
                            </span>    
                        </div>                    
                    {!! Form::close() !!}
                    </div>
                </div>

                <div class="container-fluid table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="addpremi-table" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr class="bg-warning">
                                <th>id</th>
                                <!-- <th>Tipe Hitungan Premi</th> -->
                                <th>Tgl Berlaku</th>
                                <th>Premi Per jam Non Transhipment</th>
                                <th>Premi Per jam Transhipment</th>
                                <th>Premi Opr Tembak</th>
                                <th>Tambahan Kerja Hari Libur</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="modal-footer">
                            
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="addpremi2" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style=" height: 1%; border: none">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="box-body"> 
                    <div class="addform">
                        @include('errors.validation')
                        {!! Form::open(['id'=>'ADD_PREMI2']) !!}
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Nama', 'Nama Alat:') }}
                                {{ Form::text('nama_alat', null, ['class'=> 'form-control','id'=>'NamaAlat1h','required'=>'required', 'autocomplete'=>'off', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                                {{ Form::date('tgl_berlaku', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tgl1h','required'])}}
                            </div>
                        </div>
                        <div class="col-md-12">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Premi harian dalam kota:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('premi_harian_dk',null, ['class'=> 'form-control','id'=>'DalamKota1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Premi harian luar kota:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('premi_harian_lk',null, ['class'=> 'form-control','id'=>'LuarKota1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('libur', 'Tambahan kerja hari libur:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('hari_libur',null, ['class'=> 'form-control','id'=>'Libur1h','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        {{ Form::hidden('kode_alat', null, ['class'=> 'form-control','readonly','id'=>'KodeAlat1h']) }}
                        {{ Form::hidden('id', null, ['class'=> 'form-control','readonly','id'=>'id1h']) }}
                        <div class="col-md-12">
                            <span class="pull-right"> 
                                {{ Form::submit('Add Item', ['class' => 'btn btn-primary btn-xs simpanbutton2','id'=>'submit']) }}  
                                <button type="button" class="btn btn-info btn-xs editbutton2" id="editpremi2" data-toggle="modal" data-target="">
                                    <i class="fa fa-edit"></i> EDIT
                                </button>
                                <button type="button" class="btn btn-danger btn-xs hapusbutton2" id="hapuspremi2">
                                    <i class="fa fa-times-circle"></i> HAPUS
                                </button>
                            </span>    
                        </div>                    
                    {!! Form::close() !!}
                    </div>
                </div>

                <div class="container-fluid table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="addpremi2-table" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr class="bg-warning">
                                <th>id</th>
                                <th>Tgl Berlaku</th>
                                <th>Premi Harian Dalam Kota</th>
                                <th>Premi Harian Luar Kota</th>
                                <th>Tambahan Kerja Hari Libur</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="modal-footer">
                            
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="addpremi3" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style=" height: 1%; border: none">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="box-body"> 
                    <div class="addform">
                        @include('errors.validation')
                        {!! Form::open(['id'=>'ADD_PREMI3']) !!}
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Nama', 'Nama Alat:') }}
                                {{ Form::text('nama_alat', null, ['class'=> 'form-control','id'=>'NamaAlat1x','required'=>'required', 'autocomplete'=>'off', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                                {{ Form::date('tgl_berlaku', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'Tgl1x','required'])}}
                            </div>
                        </div>
                        <div class="col-md-12">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('premi', 'Tarif Alat:', ['style'=>'font-size: 12px']) }}
                                {{ Form::text('tarif',null, ['class'=> 'form-control','id'=>'Tarif1','style'=>'width: 60%']) }}
                            </div>
                        </div>
                        {{ Form::hidden('kode_alat', null, ['class'=> 'form-control','readonly','id'=>'KodeAlat1x']) }}
                        {{ Form::hidden('id', null, ['class'=> 'form-control','readonly','id'=>'id1x']) }}
                        <div class="col-md-12">
                            <span class="pull-right"> 
                                {{ Form::submit('Add Item', ['class' => 'btn btn-primary btn-xs simpanbutton3','id'=>'submit']) }}  
                                <button type="button" class="btn btn-info btn-xs editbutton3" id="editpremi3" data-toggle="modal" data-target="">
                                    <i class="fa fa-edit"></i> EDIT
                                </button>
                                <button type="button" class="btn btn-danger btn-xs hapusbutton3" id="hapuspremi3">
                                    <i class="fa fa-times-circle"></i> HAPUS
                                </button>
                            </span>    
                        </div>                    
                    {!! Form::close() !!}
                    </div>
                </div>

                <div class="container-fluid table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="addpremi3-table" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr class="bg-warning">
                                <th>id</th>
                                <th>Tgl Berlaku</th>
                                <th>Tarif Alat</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="modal-footer">
                            
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <button type="button" class="back2Top btn btn-warning btn-xs" id="back2Top"><i class="fa fa-arrow-up" style="color: #fff"></i> <i>{{$nama_company}}</i> <b>({{ $nama_lokasi }})</b></button>

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

            .premi-button {
                background-color: #00E0FF;
                bottom: 246px;
            }

            .premi2-button {
                bottom: 276px;
            }

            .premi3-button {
                bottom: 306px;
            }

            .add-button {
                bottom: 366px;
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
              width: 150px;
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
            <a href="#" id="addlokasi"><button type="button" class="btn bg-black btn-xs add-button" data-toggle="modal" data-target="">EDIT LOKASI<i class="fa fa-plus"></i></button></a>

            <button type="button" class="btn bg-black btn-xs premi3-button" id="addpremibutton3" data-toggle="modal" data-target="#addpremi3"><i class="fa fa-plus"></i> TARIF ALAT</button>

            <button type="button" class="btn bg-purple btn-xs premi2-button" id="addpremibutton2" data-toggle="modal" data-target="#addpremi2"><i class="fa fa-plus"></i> PREMI HELPER</button>

            <button type="button" class="btn btn-info btn-xs premi-button" id="addpremibutton" data-toggle="modal" data-target="#addpremi"><i class="fa fa-plus"></i> PREMI OPERATOR</button>

            @permission('update-alat')
            <button type="button" class="btn btn-warning btn-xs edit-button" id="editalat" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
            @endpermission

            @permission('delete-alat')
            <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusalat" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
            $('.add-button').hide();
            $('.hapus-button').hide();
            $('.edit-button').hide();
            $('.premi-button').hide();
            $('.premi2-button').hide();
            $('.premi3-button').hide();
            $('.editbutton2').hide();
            $('.hapusbutton2').hide();
            $('.back2Top').show();
        }

        $(function() {
            $('#alat-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('alat.data') !!}',
            columns: [
                { data: 'kode_alat', name: 'kode_alat', visible: false },
                { data: 'nama_alat', name: 'nama_alat' },
                { data: 'merk', name: 'merk' },
                { data: 'type', name: 'type' },
                { data: 'kapasitas', name: 'kapasitas' },
                { data: 'tahun', name: 'tahun' },
                { data: 'no_asset_alat', name: 'no_asset_alat' },
            ]
            });
        });

        Table2 = $("#addpremi-table").DataTable({
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'tgl_berlaku', name: 'tgl_berlaku' },
                { data: 'premi_jam_nontranshipment', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_jam_transhipment', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_opr_tembak', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'hari_libur', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
            ],
        });

        Table3 = $("#addpremi2-table").DataTable({
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'tgl_berlaku', name: 'tgl_berlaku' },
                { data: 'premi_harian_dk', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_harian_lk', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'hari_libur',
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
            ],
        });

        Table4 = $("#addpremi3-table").DataTable({
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'tgl_berlaku', name: 'tgl_berlaku' },
                { data: 'tarif', 
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
            ],
        });

        function formatNumber(m) {
            if(m == null || m == 0){
                return '0';
            }else{
                return m.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }

        function formatNomor(n) {
            if(n == 'HO'){
                var stat = "<span style='color:#0275d8'><b>HO</b></span>";
                return n.replace(/HO/, stat);
            }else{
                var str = n;
                var result = str.fontcolor("#eb4034");
                return result;
            }
        }

        function format_type(n) {
            if(n == '1'){
                var stat = "<span style='color:#0eab25'><b>Per Jam</b></span>";
            }else if (n == '3'){
                var stat = "<span style='color:#c91a1a'><b>Per Ton</b></span>";
            }
            return stat;
        }

        function tablepremi(kode){
            $.ajax({
                url: '{!! route('alat.getDatapremi') !!}',
                type: 'GET',
                data : {
                    'id': kode
                },
                success: function(result) {
                    Table2.clear().draw();
                    Table2.rows.add(result).draw();
                    
                    $('#addpremi').modal('show');
                    $('.simpanbutton').show();
                    $('.editbutton').hide();
                    $('.hapusbutton').hide();
                    $('.postbutton').hide();
                    $('.unpostbutton').hide();
                    $('.printpaybutton').hide();
                    $('.zoombutton').hide();
                }
            });
        }

        function tablepremi2(kode){
            $.ajax({
                url: '{!! route('alat.getDatapremi2') !!}',
                type: 'GET',
                data : {
                    'id': kode
                },
                success: function(result) {
                    Table3.clear().draw();
                    Table3.rows.add(result).draw();
                    
                    $('#addpremi2').modal('show');
                    $('.simpanbutton').show();
                    $('.editbutton').hide();
                    $('.hapusbutton').hide();
                }
            });
        }

        function tablepremi3(kode){
            $.ajax({
                url: '{!! route('alat.getDatapremi3') !!}',
                type: 'GET',
                data : {
                    'id': kode
                },
                success: function(result) {
                    Table4.clear().draw();
                    Table4.rows.add(result).draw();
                    
                    $('#addpremi3').modal('show');
                    $('.simpanbutton3').show();
                    $('.editbutton3').hide();
                    $('.hapusbutton3').hide();
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

            var table = $('#alat-table').DataTable();
            var table2 = $('#addpremi-table').DataTable();
            var table3 = $('#addpremi2-table').DataTable();
            var table4 = $('#addpremi3-table').DataTable();

            $('#alat-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.premi-button').hide();
                    $('.premi2-button').hide();
                    $('.premi3-button').hide();
                }else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#alat-table').DataTable().row(select).data();
                    var kode_alat = data['kode_alat'];
                    var addmt = $("#addlokasi").attr("href",window.location.href+"/"+kode_alat+"/detaillokasi");
                    $('.add-button').show();
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    $('.premi-button').show();
                    $('.premi2-button').show();
                    $('.premi3-button').show();
                }
            });

            $('#editalat').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#alat-table').DataTable().row(select).data();
                var kode_alat = data['kode_alat'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('alat.edit_alat') !!}',
                    type: 'POST',
                    data : {
                        'id': kode_alat
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.kode_alat);
                        $('#Nama').val(results.nama_alat);
                        $('#Merk').val(results.merk);
                        $('#Type').val(results.type).trigger('change');
                        $('#kapasitas2').val(results.kapasitas);
                        $('#Tahun').val(results.tahun);
                        $('#Asset').val(results.no_asset_alat);
                        $('#Lokasi').val(results.kode_lokasi);
                        $('#editform').modal('show');
                    }
                });
            });

            $('#hapusalat').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#alat-table').DataTable().row(select).data();
                var kode_alat = data['kode_alat'];
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
                            url: '{!! route('alat.hapus_alat') !!}',
                            type: 'POST',
                            data : {
                                'id': kode_alat
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

            $('#addpremi').on('show.bs.modal', function () {
                $('.simpanbutton').show();
                $('.editbutton').hide();
                $('.hapusbutton').hide();
                $('.postbutton').hide();
                $('.unpostbutton').hide();
                $('.printpaybutton').hide();
                $('.zoombutton').hide();
            })

            $('#addpremi2').on('show.bs.modal', function () {
                $('.simpanbutton').show();
                $('.editbutton').hide();
                $('.hapusbutton').hide();
            })

            $('#addpremi3').on('show.bs.modal', function () {
                $('.simpanbutton3').show();
                $('.editbutton3').hide();
                $('.hapusbutton3').hide();
            })

            $('#addpremi-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.editbutton').hide();
                    $('.hapusbutton').hide();
                    $('.simpanbutton').show();

                    $('#id1').val('');
                    // $('#Hitungan1').val('').trigger('change');
                    $('#Tgl1').val('');
                    $('#Trans1').val('');
                    $('#NonTrans1').val('');
                    $('#Opr1').val('');
                    $('#Libur1').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    table2.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#addpremi-table').DataTable().row(select).data();
                    var id = data['id'];
                    var tgl_berlaku = data['tgl_berlaku'];
                    var trans = data['premi_jam_transhipment'];
                    var nontrans = data['premi_jam_nontranshipment'];
                    var opr = data['premi_opr_tembak'];
                    var liburan = data['hari_libur'];

                    $('.editbutton').show();
                    $('.hapusbutton').show();
                    $('.simpanbutton').hide();

                    $('#id1').val(id);
                    // $('#Hitungan1').val(tipe).trigger('change');
                    $('#Tgl1').val(tgl_berlaku);
                    $('#Trans1').val(trans);
                    $('#NonTrans1').val(nontrans);
                    $('#Opr1').val(opr);
                    $('#Libur1').val(liburan);
                }
            });

            $('#addpremi2-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.editbutton2').hide();
                    $('.hapusbutton2').hide();
                    $('.simpanbutton2').show();

                    $('#id1h').val('');
                    // $('#Hitungan1').val('').trigger('change');
                    $('#Tgl1h').val('');
                    $('#DalamKota1').val('');
                    $('#LuarKota1').val('');
                    $('#Libur1h').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    table2.$('tr.selected').removeClass('selected bg-gray');
                    table3.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#addpremi2-table').DataTable().row(select).data();
                    var id = data['id'];
                    var tgl_berlaku = data['tgl_berlaku'];
                    var trans = data['premi_harian_dk'];
                    var nontrans = data['premi_harian_lk'];
                    var liburan = data['hari_libur'];

                    $('.editbutton2').show();
                    $('.hapusbutton2').show();
                    $('.simpanbutton2').hide();

                    $('#id1h').val(id);
                    $('#Tgl1h').val(tgl_berlaku);
                    $('#DalamKota1').val(trans);
                    $('#LuarKota1').val(nontrans);
                    $('#Libur1h').val(liburan);
                }
            });

            $('#addpremi3-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.editbutton3').hide();
                    $('.hapusbutton3').hide();
                    $('.simpanbutton3').show();

                    $('#id1x').val('');
                    $('#Tgl1x').val('');
                    $('#Tarif1').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray');
                    table2.$('tr.selected').removeClass('selected bg-gray');
                    table3.$('tr.selected').removeClass('selected bg-gray');
                    table4.$('tr.selected').removeClass('selected bg-gray');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#addpremi3-table').DataTable().row(select).data();
                    var id = data['id'];
                    var tgl_berlaku = data['tgl_berlaku'];
                    var trans = data['tarif'];

                    $('.editbutton3').show();
                    $('.hapusbutton3').show();
                    $('.simpanbutton3').hide();

                    $('#id1x').val(id);
                    $('#Tgl1x').val(tgl_berlaku);
                    $('#Tarif1').val(trans);
                }
            });

            $('#addpremibutton').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#alat-table').DataTable().row(select).data();
                var kode_alat = data['kode_alat'];
                var no_asset_alat = data['no_asset_alat'];
                var nama_alat = select.find('td:eq(0)').text();
                $.ajax({
                    url: '{!! route('alat.getDatapremi') !!}',
                    type: 'GET',
                    data : {
                        'id': kode_alat
                    },
                    success: function(result) {
                        Table2.clear().draw();
                        Table2.rows.add(result).draw();
                        submit.disabled = false;
                        $('#addpremi').modal('show');
                        $('#KodeAlat1').val(kode_alat);
                        $('#NamaAlat1').val(no_asset_alat);
                    }
                });
            });

            $('#addpremibutton2').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#alat-table').DataTable().row(select).data();
                var kode_alat = data['kode_alat'];
                var no_asset_alat = data['no_asset_alat'];
                var nama_alat = select.find('td:eq(0)').text();
                $.ajax({
                    url: '{!! route('alat.getDatapremi2') !!}',
                    type: 'GET',
                    data : {
                        'id': kode_alat
                    },
                    success: function(result) {
                        Table3.clear().draw();
                        Table3.rows.add(result).draw();
                        submit.disabled = false;
                        $('#addpremi2').modal('show');
                        $('#KodeAlat1h').val(kode_alat);
                        $('#NamaAlat1h').val(no_asset_alat);
                    }
                });
            });

            $('#addpremibutton3').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#alat-table').DataTable().row(select).data();
                var kode_alat = data['kode_alat'];
                var no_asset_alat = data['no_asset_alat'];
                var nama_alat = select.find('td:eq(0)').text();
                $.ajax({
                    url: '{!! route('alat.getDatapremi3') !!}',
                    type: 'GET',
                    data : {
                        'id': kode_alat
                    },
                    success: function(result) {
                        Table4.clear().draw();
                        Table4.rows.add(result).draw();
                        submit.disabled = false;
                        $('#addpremi3').modal('show');
                        $('#KodeAlat1x').val(kode_alat);
                        $('#NamaAlat1x').val(no_asset_alat);
                    }
                });
            });

            $('#editpremi').click( function () {
                table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi-table').DataTable().row(select).data();

                var id = $('#id1').val();
                var kode = $('#KodeAlat1').val();
                var tgl_berlaku = $('#Tgl1').val();
                // var tipe_hitungan = $('#Hitungan1').val();
                var trans = $('#Trans1').val();
                var nontrans = $('#NonTrans1').val();
                var opr = $('#Opr1').val();
                var liburan = $('#Libur1').val();
                var row = table2.row( select );
                
                $.ajax({
                    url: '{!! route('alat.editpremi') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'tgl_berlaku': tgl_berlaku,
                        'kode_alat': kode,
                        'premi_jam_transhipment': trans,
                        'premi_jam_nontranshipment': nontrans,
                        'premi_opr_tembak': opr,
                        'hari_libur': liburan,
                    },
                    success: function (results) {
                        $('#KodeAlat1').val('');
                        $('#Tgl1').val('');
                        $('#Trans1').val('');
                        $('#NonTrans1').val('');
                        $('#Opr1').val('');
                        $('#Libur1').val('');
                        
                        if (results.success === true) {
                            swal("Berhasil!", results.message, "success");
                        } else {
                            swal("Gagal!", results.message, "error");
                        }
                        tablepremi(kode);
                    }
                });
            });

            $('#editpremi2').click( function () {
                table3.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi2-table').DataTable().row(select).data();

                var id = $('#id1h').val();
                var kode = $('#KodeAlat1h').val();
                var tgl_berlaku = $('#Tgl1h').val();
                // var tipe_hitungan = $('#Hitungan1').val();
                var trans = $('#DalamKota1').val();
                var nontrans = $('#LuarKota1').val();
                var liburan = $('#Libur1h').val();
                var row = table3.row( select );
                
                $.ajax({
                    url: '{!! route('alat.editpremi2') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'tgl_berlaku': tgl_berlaku,
                        'premi_harian_dk': trans,
                        'premi_harian_lk': nontrans,
                        'hari_libur': liburan,
                    },
                    success: function (results) {
                        // $('#Hitungan1').val('').trigger('change');
                        $('#Tgl1h').val('');
                        $('#DalamKota1').val('');
                        $('#LuarKota1').val('');
                        $('#Libur1h').val('');
                        
                        if (results.success === true) {
                            swal("Berhasil!", results.message, "success");
                        } else {
                            swal("Gagal!", results.message, "error");
                        }
                        tablepremi2(kode);
                    }
                });
            });

            $('#editpremi3').click( function () {
                table4.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi3-table').DataTable().row(select).data();

                var id = $('#id1x').val();
                var kode = $('#KodeAlat1x').val();
                var tgl_berlaku = $('#Tgl1x').val();
                var trans = $('#Tarif1').val();
                var row = table4.row( select );
                
                $.ajax({
                    url: '{!! route('alat.editpremi3') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'tgl_berlaku': tgl_berlaku,
                        'tarif': trans,
                    },
                    success: function (results) {
                        $('#Tgl1x').val('');
                        $('#Tarif1').val('');
                        
                        if (results.success === true) {
                            swal("Berhasil!", results.message, "success");
                        } else {
                            swal("Gagal!", results.message, "error");
                        }
                        tablepremi3(kode);
                    }
                });
            });

            $('#hapuspremi').click( function () {
                table2.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi-table').DataTable().row(select).data();

                var id = $('#id1').val();
                var kode = $('#KodeAlat1').val();
                var tgl_berlaku = $('#Tgl1').val();
                // var tipe_hitungan = $('#Hitungan1').val();
                var row = table2.row( select );
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
                            url: '{!! route('alat.hapuspremi') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
                                'kode_alat': kode,
                                'tgl_berlaku': tgl_berlaku,
                            },
                            success: function (results) {
                                // $('#Hitungan1').val('').trigger('change');
                                $('#Tgl1').val('');
                                $('#Trans1').val('');
                                $('#NonTrans1').val('');
                                $('#Opr1').val('');
                                $('#Libur1').val('');
                                
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                tablepremi(kode);
                            }
                        });
                    }
                });
            });

            $('#hapuspremi2').click( function () {
                table3.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi2-table').DataTable().row(select).data();

                var id = $('#id1h').val();
                var kode = $('#KodeAlat1h').val();
                var tgl_berlaku = $('#Tgl1h').val();
                // var tipe_hitungan = $('#Hitungan1').val();
                var row = table3.row( select );
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
                            url: '{!! route('alat.hapuspremi2') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
                                'kode_alat': kode,
                                'tgl_berlaku': tgl_berlaku,
                            },
                            success: function (results) {
                                // $('#Hitungan1').val('').trigger('change');
                                $('#Tgl1h').val('');
                                $('#DalamKota1').val('');
                                $('#LuarKota1').val('');
                                $('#Libur1h').val('');
                                
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                tablepremi2(kode);
                            }
                        });
                    }
                });
            });

            $('#hapuspremi3').click( function () {
                table4.$('tr.selected').removeClass('selected bg-gray text-bold');
                $(this).addClass('selected bg-gray text-bold');
                var select = $('.selected').closest('tr');
                var data = $('#addpremi3-table').DataTable().row(select).data();

                var id = $('#id1x').val();
                var kode = $('#KodeAlat1x').val();
                var tgl_berlaku = $('#Tgl1x').val();
                var row = table4.row( select );
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
                            url: '{!! route('alat.hapuspremi3') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
                                'kode_alat': kode,
                                'tgl_berlaku': tgl_berlaku,
                            },
                            success: function (results) {
                                $('#Tgl1x').val('');
                                $('#Tarif1').val('');
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                tablepremi3(kode);
                            }
                        });
                    }
                });
            });

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
            if (decimal || (keychar == ".")) {
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
             $('#alat-table').DataTable().ajax.reload(null,false);
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
                    url:'{!! route('alat.store') !!}',
                    type:'POST',
                    data:formData,
                    success:function(data) {
                        console.log(data);
                        $('#Nama1').val('');
                        $('#Nama2').val('');
                        $('#Nama3').val('');
                        $('#Nama4').val('').trigger('change');
                        $('#Asset1').val('');
                        $('#kapasitas1').val('');
                        $('#Lokasi1').val('').trigger('change');
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

        $('#ADD_PREMI').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_PREMI");
            var formData = registerForm.serialize();
            var kode = $('#KodeAlat1').val();
            $.ajax({
                url:'{!! route('alat.storepremi') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#KodeAlat1').val('');
                    $('#Tgl1').val('');
                    // $('#Hitungan1').val('').trigger('change');
                    $('#Trans1').val('');
                    $('#NonTrans1').val('');
                    $('#Opr1').val('');
                    $('#Libur1').val('');
                    // $('#addpremi').modal('hide');
                    tablepremi(kode);
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal("Gagal!", data.message, "error");
                    }
                },
            });
        });

        $('#ADD_PREMI2').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_PREMI2");
            var formData = registerForm.serialize();
            var kode = $('#KodeAlat1h').val();
            $.ajax({
                url:'{!! route('alat.storepremi2') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#KodeAlat1h').val('');
                    // $('#NamaAlat1h').val('');
                    $('#Tgl1h').val('');
                    $('#DalamKota1').val('');
                    $('#LuarKota1').val('');
                    $('#Libur1h').val('');
                    tablepremi2(kode);
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal("Gagal!", data.message, "error");
                    }
                },
            });
        });

        $('#ADD_PREMI3').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_PREMI3");
            var formData = registerForm.serialize();
            var kode = $('#KodeAlat1x').val();
            $.ajax({
                url:'{!! route('alat.storepremi3') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#KodeAlat1x').val('');
                    $('#Tgl1x').val('');
                    $('#Tarif1').val('');
                    tablepremi3(kode);
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
                url:'{!! route('alat.ajaxupdate') !!}',
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

        function update() {
         e.preventDefault();
         var form_action = $("#editform").find("form").attr("action");
                $.ajax({
                    
                    url: form_action,
                    type: 'POST',
                    data:$('#Update').serialize(),
                    success: function(data) {
                        console.log(data);
                        $('#editform').modal('hide');
                        $.notify(data.message, "success");
                        refreshTable();
                    }
                });
        }
    </script>
@endpush