@extends('adminlte::page')

@section('title', 'Daftar Tenaga kerja Non Pegawai')

@section('content_header')
    
@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
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
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addform">
                        <i class="fa fa-plus"></i> New Data</button>
                        <a href="http://localhost/gui_membership_system/admin/membership/exportexcel?" target="_blank" id="printpembelian"><button type="button" class="btn bg-black btn-xs"><i class="fa fa-print"></i> CETAK LIST</button></a>

                    <span class="pull-right">
                        <button type="button" class="btn bg-red btn-xs img-button" id="addttd" data-toggle="modal" data-target="#ttdform"><i class="fa fa-circle-o"></i> Tanda Tangan Digital</button>
                        <button type="button" class="btn bg-orange btn-xs img-button" id="addimgbutton" data-toggle="modal" data-target="#addimgform"><i class="fa fa-plus"></i> UPLOAD KTP</button>
                        <button type="button" class="btn bg-black btn-xs img2-button" id="addimg2button" data-toggle="modal" data-target="#addimg2form"><i class="fa fa-plus"></i> UPLOAD NPWP</button>
                        <button type="button" class="btn bg-purple btn-xs img3-button" id="addimg3button" data-toggle="modal" data-target="#addimg3form"><i class="fa fa-plus"></i> UPLOAD KK</button>
                        <font style="font-size: 16px;"><b>DAFTAR TENAGA KERJA NON PEGAWAI</b></font>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table" style="font-size: 12px; width: 1200px;">
                    <thead>
                        <tr class="bg-danger">
                            <th>id</th>
                            <th>NIB</th>
                            <th>Nama</th>
                            <th>Tanggal Masuk</th>
                            <th>Lokasi Kerja</th>
                            <th>Jabatan</th>
                            <th>Gender</th>
                            <th>Tempat</th>
                            <th>Tanggal Lahir</th>
                            <th>Umur</th>
                            <th style="width: 250px;">Alamat</th>
                            <th>Agama</th>
                            <th>Status</th>
                            <th>No KTP</th>
                            <th>No KK</th>
                            <th>No NPWP</th>
                            <th>Gol Darah</th>
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
                        <h3>TTD Digital: {{ Form::label('nomoor', null,['id'=>'NomorTTD']) }}</h3>
                        <button type="button" class="btn btn-danger" id="reset-btn">Clear</button>
                        <hr>
                        <div id="canvasDiv"></div>
                        <br>
                        <button type="button" class="btn bg-blue" id="btn-save">Simpan</button>
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
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Nama ', 'NIB :') }}
                            {{ Form::text('nik', null, ['class'=> 'form-control','id'=>'Nik1', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Nama ', 'Nama :') }}
                            {{ Form::text('nama', null, ['class'=> 'form-control','id'=>'Nama1', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal Masuk:') }}
                            {{ Form::date('tanggal_masuk', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'TanggalMasuk1'])}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('alamat', 'Alamat:') }}
                            {{ Form::text('alamat', null, ['class'=> 'form-control','id'=>'Alamat1', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('alamat', 'Lokasi Kerja:') }}
                            {{ Form::text('lokasi_kerja', null, ['class'=> 'form-control','id'=>'Lokasi1', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{ Form::label('kota', 'Perusahaan:') }}
                            {{ Form::select('kode_company', $Company->sort(), null, ['class'=> 'form-control select2','id'=>'Company1', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Jabatan:') }}
                            {{ Form::text('jabatan', null, ['class'=> 'form-control','id'=>'Jabatan1', 'placeholder'=>'','autocomplete'=>'off', 'maxlength'=>'50']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('kota', 'Gender:') }}
                            {{ Form::select('gender', ['L'=>'L','P'=>'P'], null, ['class'=> 'form-control select2','id'=>'Gender1', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Tempat:') }}
                            {{ Form::text('tempat', null, ['class'=> 'form-control','id'=>'Tempat1', 'placeholder'=>'','autocomplete'=>'off', 'maxlength'=>'50']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal Lahir:') }}
                            {{ Form::date('tanggal_lahir', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'TanggalLahir1','onchange'=>"usia();"])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('textt', 'Umur:') }}
                            {{ Form::text('umur', null, ['class'=> 'form-control','id'=>'Umur1', 'placeholder'=>'','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('kota', 'Agama:') }}
                            {{ Form::select('agama', ['BUDDHA'=>'BUDDHA','HINDU'=>'HINDU','ISLAM'=>'ISLAM','KRISTEN'=>'KRISTEN','KATOLIK'=>'KATOLIK'], null, ['class'=> 'form-control select2','id'=>'Agama1', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('status', 'Status:') }}
                            {{Form::select('status', ['Aktif' => 'Aktif', 'NonAktif' => 'NonAktif'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Status1','onchange'=>"stat()"])}}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('textt', 'Keterangan:') }}
                            {{ Form::text('keterangan', null, ['class'=> 'form-control','id'=>'Ket1', 'placeholder'=>'','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('kota', 'Gol.Darah:') }}
                            {{ Form::select('gol_darah', ['A'=>'A','B'=>'B','AB'=>'AB','O'=>'O'], null, ['class'=> 'form-control select2','id'=>'Darah1', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('textt', 'No KTP:') }}
                            {{ Form::text('no_ktp', null, ['class'=> 'form-control','id'=>'Ktp1', 'placeholder'=>'']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('textt', 'No NPWP:') }}
                            <input type="text" name="number" style="display:none;">
                            {{ Form::text('no_npwp', null, ['class'=> 'form-control','id'=>'Npwp1', 'placeholder'=>'','name'=>'npwp']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('textt', 'No KK:') }}
                            {{ Form::text('no_kk', null, ['class'=> 'form-control','id'=>'Kk1', 'placeholder'=>'']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Status Kerja:') }}
                            {{ Form::text('status_kerja', null, ['class'=> 'form-control','id'=>'StatusKerja1', 'placeholder'=>'','autocomplete'=>'off']) }}
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
              <h4 class="modal-title">Edit Data <i>&nbsp;ALT+S = Simpan Update (shortcut)</i></h4>
            </div>
            @include('errors.validation')
            {!! Form::open(['id'=>'EDIT']) !!}
            <div class="modal-body">
                <div class="row">
                {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'Kode','readonly']) }}
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Nama ', 'NIB :') }}
                            {{ Form::text('nik', null, ['class'=> 'form-control','id'=>'Nik2', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)"]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Nama ', 'Nama :') }}
                            {{ Form::text('nama', null, ['class'=> 'form-control','id'=>'Nama2', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal Masuk:') }}
                            {{ Form::date('tanggal_masuk', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'TanggalMasuk2'])}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('alamat', 'Alamat:') }}
                            {{ Form::text('alamat', null, ['class'=> 'form-control','id'=>'Alamat2', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('alamat', 'Lokasi Kerja:') }}
                            {{ Form::text('lokasi_kerja', null, ['class'=> 'form-control','id'=>'Lokasi2', 'placeholder'=>'','autocomplete'=>'off', 'onkeypress'=>"return pulsar(event,this)",'data-toggle'=>"tooltip",'data-placement'=>"bottom"]) }}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{ Form::label('kota', 'Perusahaan:') }}
                            {{ Form::select('kode_company', $Company->sort(), null, ['class'=> 'form-control select2','id'=>'Company2', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Jabatan:') }}
                            {{ Form::text('jabatan', null, ['class'=> 'form-control','id'=>'Jabatan2', 'placeholder'=>'','autocomplete'=>'off', 'maxlength'=>'50']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('kota', 'Gender:') }}
                            {{ Form::select('gender', ['L'=>'L','P'=>'P'], null, ['class'=> 'form-control select2','id'=>'Gender2', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Tempat:') }}
                            {{ Form::text('tempat', null, ['class'=> 'form-control','id'=>'Tempat2', 'placeholder'=>'','autocomplete'=>'off', 'maxlength'=>'50']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tanggal Lahir:') }}
                            {{ Form::date('tanggal_lahir', \Carbon\Carbon::now(),['class'=> 'form-control','id'=>'TanggalLahir2','onchange'=>"usia2();"])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('textt', 'Umur:') }}
                            {{ Form::text('umur', null, ['class'=> 'form-control','id'=>'Umur2', 'placeholder'=>'','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('kota', 'Agama:') }}
                            {{ Form::select('agama', ['BUDDHA'=>'BUDDHA','HINDU'=>'HINDU','ISLAM'=>'ISLAM','KRISTEN'=>'KRISTEN','KATOLIK'=>'KATOLIK'], null, ['class'=> 'form-control select2','id'=>'Agama2', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('status', 'Status:') }}
                            {{Form::select('status', ['Aktif' => 'Aktif', 'NonAktif' => 'NonAktif'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'Status2','onchange'=>"stat2()"])}}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('textt', 'Keterangan:') }}
                            {{ Form::text('keterangan', null, ['class'=> 'form-control','id'=>'Ket2', 'placeholder'=>'','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('kota', 'Gol.Darah:') }}
                            {{ Form::select('gol_darah', ['A'=>'A','B'=>'B','AB'=>'AB','O'=>'O'], null, ['class'=> 'form-control select2','id'=>'Darah2', 'placeholder'=>'','autocomplete'=>'off','style'=>'width: 100%']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('textt', 'No KTP:') }}
                            {{ Form::text('no_ktp', null, ['class'=> 'form-control','id'=>'Ktp2', 'placeholder'=>'']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('textt', 'No NPWP:') }}
                            <input type="text" name="number" style="display:none;">
                            {{ Form::text('no_npwp', null, ['class'=> 'form-control','id'=>'Npwp2', 'placeholder'=>'','name'=>'npwp']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('textt', 'No KK:') }}
                            {{ Form::text('no_kk', null, ['class'=> 'form-control','id'=>'Kk2', 'placeholder'=>'']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('kodepos', 'Status Kerja:') }}
                            {{ Form::text('status_kerja', null, ['class'=> 'form-control','id'=>'StatusKerja2', 'placeholder'=>'','autocomplete'=>'off']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    {{ Form::submit('Update data', ['class' => 'btn btn-success','id'=>'update-button']) }}
                    {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                </div>
            </div>
            {!! Form::close() !!}
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

<div class="modal fade" id="addimgform" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style=" height: 1%; border: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="box-body">
            @include('errors.validation')
            {!! Form::open(['route' => ['membership.uploado'],'method' => 'post','id'=>'form','enctype'=>'multipart/form-data','target'=>"_blank"]) !!}
            {{ Form::hidden('ktp_img', null, ['class'=> 'form-control','readonly','id'=>'ImgKTP']) }}
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'Kodes','readonly']) }}
                        {{ Form::label('noo', 'NIB:') }}
                        {{ Form::text('nib', null, ['class'=> 'form-control','readonly','id'=>'NIB1']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('hrg', 'Upload KTP:') }}
                        <input type="file" name="fileToUpload" id="fileToUpload" onchange="ambil()">
                    </div>
                </div>
                <div class="col-md-12">
                </div>
                <div class="col-md-12">
                    <span class="pull-right">
                        {{ Form::submit('Upload', ['class' => 'btn btn-success crud-submit']) }}
                        <!-- <button type="button" class="btn btn-danger hapusbutton3" id="hapusgambar">
                        HAPUS</button> -->
                    </span>
                </div>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="addimg2form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style=" height: 1%; border: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="box-body">
            @include('errors.validation')
            {!! Form::open(['route' => ['membership.uploadi'],'method' => 'post','id'=>'form','enctype'=>'multipart/form-data','target'=>"_blank"]) !!}
            {{ Form::hidden('npwp_img', null, ['class'=> 'form-control','readonly','id'=>'ImgNPWP']) }}
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'Kodes2','readonly']) }}
                        {{ Form::label('noo', 'NIB:') }}
                        {{ Form::text('nib', null, ['class'=> 'form-control','readonly','id'=>'NIB2']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('hrg', 'Upload NPWP:') }}
                        <input type="file" name="fileToUpload2" id="fileToUpload2" onchange="ambil2()">
                    </div>
                </div>
                <div class="col-md-12">
                </div>
                <div class="col-md-12">
                    <span class="pull-right">
                        {{ Form::submit('Upload', ['class' => 'btn btn-success crud-submit']) }}
                        <!-- <button type="button" class="btn btn-danger hapusbutton3" id="hapusgambar">
                        HAPUS</button> -->
                    </span>
                </div>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="addimg3form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style=" height: 1%; border: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="box-body">
            @include('errors.validation')
            {!! Form::open(['route' => ['membership.uploade'],'method' => 'post','id'=>'form','enctype'=>'multipart/form-data','target'=>"_blank"]) !!}
            {{ Form::hidden('kk_img', null, ['class'=> 'form-control','readonly','id'=>'ImgKK']) }}
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'Kodes3','readonly']) }}
                        {{ Form::label('noo', 'NIB:') }}
                        {{ Form::text('nib', null, ['class'=> 'form-control','readonly','id'=>'NIB3']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('hrg', 'Upload KK:') }}
                        <input type="file" name="fileToUpload3" id="fileToUpload3" onchange="ambil3()">
                    </div>
                </div>
                <div class="col-md-12">
                </div>
                <div class="col-md-12">
                    <span class="pull-right">
                        {{ Form::submit('Upload', ['class' => 'btn btn-success crud-submit']) }}
                        <!-- <button type="button" class="btn btn-danger hapusbutton3" id="hapusgambar">
                        HAPUS</button> -->
                    </span>
                </div>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                background-color: #5E3CF6;
                bottom: 246px;
            }
            
            .print-button {
                background-color: #5E3CF6;
                bottom: 276px;
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
            <a href="#" id="addmember"><button type="button" class="btn btn-xs add-button" data-toggle="modal" data-target="">ADD MEMBER <i class="fa fa-plus"></i></button></a>
            <a href="#" target="_blank" id="printjr"><button type="button" class="btn bg-orange btn-xs print-button" id="button11">PRINT DATA<i class="fa fa-print"></i></button></a>
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
        document.onkeyup = function () {
          var e = e || window.event; // for IE to cover IEs window event-object
          if(e.altKey && e.which == 83) {
            $("#update-button").click();
          }
        }
        
        function ambil()
        {
            var gambar = $('#fileToUpload').val().split('\\').pop();
            $('#ImgKTP').val(gambar);
            console.log(gambar);
        }

        function ambil2()
        {
            var gambar2 = $('#fileToUpload2').val().split('\\').pop();
            $('#ImgNPWP').val(gambar2);
            console.log(gambar2);
        }

        function ambil3()
        {
            var gambar3 = $('#fileToUpload3').val().split('\\').pop();
            $('#ImgKK').val(gambar3);
            console.log(gambar3);
        }
        
        function getAge(dateString) 
        {
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
            {
                age--;
            }
            return age;
        }
        
        function usia()
        {
            var lahir = $('#TanggalLahir1').val();
            $('#Umur1').val(getAge(lahir));
        }
        
        function usia2()
        {
            var lahir = $('#TanggalLahir2').val();
            $('#Umur2').val(getAge(lahir));
        }

        function load(){
            startTime();
            $('.edit-button').hide();
            $('.hapus-button').hide();
            $('.add-button').hide();
            $('.print-button').hide();
            $('.tombol1').hide();
            $('.tombol2').hide();
            $('.img-button').hide();
            $('.img2-button').hide();
            $('.img3-button').hide();
        }

    function stat(){
        var status = $('#Status1').val();
        if (status == 'Aktif'){
            $('#Ket1').val('');
            document.getElementById("Ket1").readOnly = true;
        }else {
            document.getElementById("Ket1").readOnly = false;
        }
    }

    function stat2(){
        var status = $('#Status2').val();
        if (status == 'Aktif'){
            $('#Ket2').val('');
            document.getElementById("Ket2").readOnly = true;
        }else {
            document.getElementById("Ket2").readOnly = false;
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

    var editor;

        $(function() {         
            $('#data-table').DataTable({
                "bPaginate": true,
                "bFilter": true,
                "scrollY": 325,
                "scrollX": 400,
                "pageLength":100,
                processing: true,
                serverSide: true,
                ajax: '{!! route('membership.data') !!}',
                // fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
                //     if (data['jenis_harga'] != '1') {
                //         $('td', row).css('background-color', '#F3E61D');
                //     }
                // },
                columns: [
                    { data: 'id', name: 'id', visible: false },
                    { data: 'nik', name: 'nik' },
                    { data: 'nama', name: 'nama' },
                    { data: 'tanggal_masuk', name: 'tanggal_masuk' },
                    { data: 'lokasi_kerja', name: 'lokasi_kerja' },
                    { data: 'jabatan', name: 'jabatan' },
                    { data: 'gender', name: 'gender' },
                    { data: 'tempat', name: 'tempat' },
                    { data: 'tanggal_lahir', name: 'tanggal_lahir' },
                    { data: 'umur', name: 'umur' },
                    { data: 'alamat', "defaultContent": "<i>-</i>" },
                    { data: 'agama', "defaultContent": "<i>-</i>" },
                    { data: 'status', name: 'status' },
                    { data: 'no_ktp', "defaultContent": "<i>-</i>" },
                    { data: 'no_kk', "defaultContent": "<i>-</i>" },
                    { data: 'no_npwp', "defaultContent": "<i>-</i>" },
                    { data: 'gol_darah', "defaultContent": "<i>-</i>" },
                ]
            });
        });

        $(document).ready(function(){
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
                    $('.img-button').hide();
                    $('.img2-button').hide();
                    $('.img3-button').hide();
                    $('.print-button').hide();
                    $('#NIB1').val('');
                    $('#Kodes').val('');
                    $('#NIB2').val('');
                    $('#Kodes2').val('');
                    $('#NIB3').val('');
                    $('#Kodes3').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data-table').DataTable().row(select).data();
                    var kode_customer = data['id'];
                    var nik = data['nik'];
                    var nama = data['nama'];
                    var jenis = data['jenis_harga'];
                    var print = $("#printjr").attr("href",window.location.href+"/exportpdf?id="+kode_customer);
                    document.getElementById("NomorTTD").innerHTML = nik+" ~ "+nama;
                    // var add = $("#addmember").attr("href","http://localhost/gui_front_pbm_laravel/admin/customer/"+kode_customer+"/detail");
                    $('#NIB1').val(data['nik']);
                    $('#Kodes').val(data['id']);
                    $('#NIB2').val(data['nik']);
                    $('#Kodes2').val(data['id']);
                    $('#NIB3').val(data['nik']);
                    $('#Kodes3').val(data['id']);
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    $('.add-button').hide();
                    $('.img-button').show();
                    $('.img2-button').show();
                    $('.img3-button').show();
                    $('.print-button').show();
                }
            });

            $('#editcustomer').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data-table').DataTable().row(select).data();
                var kode_customer = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('membership.edit_customer') !!}',
                    type: 'POST',
                    data : {
                        'id': kode_customer
                    },
                    success: function(results) {
                        console.log(results);
                        $('#Kode').val(results.id);
                        $('#Nama2').val(results.nama);
                        $('#Nik2').val(results.nik);
                        $('#TanggalMasuk2').val(results.tanggal_masuk);
                        $('#Lokasi2').val(results.lokasi_kerja).trigger('change');
                        $('#Jabatan2').val(results.jabatan);
                        $('#Gender2').val(results.gender).trigger('change');
                        $('#Alamat2').val(results.alamat);
                        $('#Tempat2').val(results.tempat);
                        $('#TanggalLahir2').val(results.tanggal_lahir);
                        $('#Umur2').val(results.umur);
                        $('#Agama2').val(results.agama).trigger('change');
                        $('#Status2').val(results.status).trigger('change');
                        $('#Ktp2').val(results.no_ktp);
                        $('#Npwp2').val(results.no_npwp);
                        $('#Kk2').val(results.no_kk);
                        $('#Darah2').val(results.gol_darah).trigger('change');
                        $('#Ket2').val(results.keterangan);
                        $('#StatusKerja2').val(results.status_kerja);
                        $('#Company2').val(results.kode_company).trigger('change');
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
                            url: '{!! route('membership.hapus_customer') !!}',
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
                var id = data['id'];
                $.ajax({
                    url: '{!! route('membership.ttd_buat') !!}',
                    type: 'POST',
                    data : {
                        'no': id,
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
                    url:'{!! route('membership.store') !!}',
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
                    url:'{!! route('membership.ajaxupdate') !!}',
                    type:'POST',
                    enctype: 'multipart/form-data',
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