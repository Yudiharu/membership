@extends('adminlte::page')

@section('title', 'Detail Tarif Kegiatan')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <font style="font-size: 16px;">Jenis Kegiatan <b>{{$tarif->kegiatan->description}}</b></font>
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
                {{ Form::hidden('id_tarif',$tarif->id, ['class'=> 'form-control','readonly','id'=>'kodekegiatan']) }}
                {{ Form::hidden('type_kegiatan',$tarif->type_kegiatan, ['class'=> 'form-control','readonly','id'=>'typekegiatan']) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('Tanggal', 'Tanggal Berlaku:') }}
                        {{ Form::date('tgl_berlaku', null,['class'=> 'form-control','id'=>'Tgl1','required','onchange'=>'ambiltgl();'])}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('tipe', 'Jenis Tarif:') }}
                        {{Form::select('jenis_harga', $Jenis, null, ['class'=> 'form-control select2', 'style'=>'width: 100%', 'placeholder' => '', 'id'=>'Jenis1', 'required','onchange'=>'ambiltarif();'])}}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="col-md-6">
   <div class="box box-danger">
        <div class="box-body">
            <div class="tambahpallet">
                @include('errors.validation')
                {!! Form::open(['id'=>'ADD_PALLET']) !!}
                <div class="row">
                    {{ Form::hidden('id_tarif', $tarif->id, ['class'=> 'form-control','readonly','id'=>'idtarif1']) }}
                    {{ Form::hidden('tgl_berlaku', null, ['class'=> 'form-control','readonly','id'=>'TglPallet1','required']) }}
                    {{ Form::hidden('jenis_tarif', null, ['class'=> 'form-control','readonly','id'=>'TarifPallet1','required']) }}
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('tipe', 'Type Pallet:') }}
                            {{Form::select('type_pallet', ['1'=>'SW','2'=>'MB'], null, ['class'=> 'form-control select2', 'style'=>'width: 100%', 'placeholder' => '', 'id'=>'tipe1', 'required'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Biaya', 'Biaya Storage:') }}
                            {{ Form::text('biaya_storage', null,['class'=> 'form-control','id'=>'Biaya1'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Biaya', 'Biaya Receiving:') }}
                            {{ Form::text('biaya_receiving', null,['class'=> 'form-control','id'=>'Biaya2'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Biaya', 'Biaya Delivery:') }}
                            {{ Form::text('biaya_delivery', null,['class'=> 'form-control','id'=>'Biaya3'])}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="pull-right">
                            {{ Form::submit('➕ TAMBAH', ['class' => 'btn btn-success btn-xs addpallet','id'=>'submit1']) }}
                            <button type="button" class="btn btn-info btn-xs editbutton2" id="editpallet" data-toggle="modal" data-target="">
                                <i class="fa fa-edit"></i> EDIT
                            </button>
                            <button type="button" class="btn btn-danger btn-xs hapusbutton2" id="hapuspallet">
                                <i class="fa fa-times-circle"></i> HAPUS
                            </button>
                        </span>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data2-table" width="100%" style="font-size: 12px;">
                    <thead>
                        <tr class="bg-danger">
                            <th>id</th>
                            <th>id tarif</th>
                            <th>Type Pallet</th>
                            <th>Tgl Berlaku</th>
                            <th>Biaya Storage</th>
                            <th>Biaya Receiving</th>
                            <th>Biaya Delivery</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <button type="button" class="back2Top btn btn-warning btn-xs" id="back2Top"><i class="fa fa-arrow-up" style="color: #fff"></i> <i>{{ $nama_company }}</i> <b>({{ $nama_lokasi }})</b></button>
</div>
<div class="col-md-6" id="div-alat">
    <div class="box box-danger">
        <div class="box-body">
            <div class="tambahalat">
                @include('errors.validation')
                {!! Form::open(['id'=>'ADD_ALAT']) !!}
                <div class="row">
                    {{ Form::hidden('id_tarif', $tarif->id, ['class'=> 'form-control','readonly','id'=>'idtarif2']) }}
                    {{ Form::hidden('tgl_berlaku', null, ['class'=> 'form-control','readonly','id'=>'TglAlat1']) }}
                    {{ Form::hidden('jenis_tarif', null, ['class'=> 'form-control','readonly','id'=>'TarifAlat1']) }}
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tipe', 'Type Alat:') }}
                            {{Form::select('kode_alat', $Alat, null, ['class'=> 'form-control select2', 'style'=>'width: 100%', 'placeholder' => '', 'id'=>'TypeAlat1', 'required'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Biaya', 'Biaya Per Jam:') }}
                            {{ Form::text('per_jam', null,['class'=> 'form-control','id'=>'PerJam1'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Biaya', 'Biaya Per Ton:') }}
                            {{ Form::text('per_ton', null,['class'=> 'form-control','id'=>'PerTon1'])}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="pull-right">
                            {{ Form::submit('➕ TAMBAH', ['class' => 'btn btn-success btn-xs addalat','id'=>'submit2']) }}
                            <button type="button" class="btn btn-info btn-xs editbutton3" id="editalat" data-toggle="modal" data-target="">
                                <i class="fa fa-edit"></i> EDIT
                            </button>
                            <button type="button" class="btn btn-danger btn-xs hapusbutton3" id="hapusalat">
                                <i class="fa fa-times-circle"></i> HAPUS
                            </button>
                        </span>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data3-table" width="100%" style="font-size: 12px;">
                    <thead>
                        <tr class="bg-warning">
                            <th>id</th>
                            <th>id tarif</th>
                            <th>Type Alat</th>
                            <th>Biaya PerJam</th>
                            <th>Biaya PerTon</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

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
        bottom: 120px;
    }

    .add-button {
        background-color: #00E0FF;
        bottom: 126px;
    }

    .edit-button {
        background-color: #FDA900;
        bottom: 150px;
    }

    .view-button {
        background-color: #149933;
        bottom: 156px;
    }

    #mySidenav button {
          position: fixed;
          left: 180px;
          transition: 0.3s;
          padding: 4px 8px;
          width: 120px;
          text-decoration: blink;
          font-size: 12px;
          color: white;
          border-radius: 5px 30px 5px 0;
          opacity: 0.7;
          cursor: pointer;
          text-align: right;
    }

    #mySidenav button:hover {
        left: 220px;
        opacity: 1.0;
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
    <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapuscfs" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>

    <button type="button" class="btn btn-warning btn-xs edit-button" id="editcfs" data-toggle="modal" data-target="">EDIT <i class="fa fa-edit"></i></button>
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
            $('.edit-button').hide();
            $('.hapus-button').hide();
            $('.tambahsize').hide();
            $('.editbutton').hide();
            $('.hapusbutton').hide();
            $('.editbutton2').hide();
            $('.hapusbutton2').hide();
            $('.editbutton3').hide();
            $('.hapusbutton3').hide();
        }
        
        function formatRupiah(angka, prefix='Rp'){
            var rupiah = angka.toLocaleString(
                undefined, // leave undefined to use the browser's locale,
                // or use a string like 'en-US' to override it.
                { minimumFractionDigits: 0 }
            );
            return rupiah;
        }

        function ambiltgl()
        {
            var tgl = $('#Tgl1').val();
            var id = $('#kodekegiatan').val();
            var tarif = $('#Jenis1').val();

            $('#TglPallet1').val(tgl);
            $('#TglAlat1').val(tgl);

            tabel_pallet2(tgl,tarif,id);
            tabel_alat2(tgl,tarif,id);
        }

        function ambiltarif()
        {
            var tarif = $('#Jenis1').val();
            var id = $('#kodekegiatan').val();
            var tgl = $('#TglPallet1').val();
            var tgl2 = $('#TglAlat1').val();

            $('#TarifPallet1').val(tarif);
            $('#TarifAlat1').val(tarif);

            tabel_pallet2(tgl,tarif,id);
            tabel_alat2(tgl2,tarif,id);
        }

        function tabel_pallet2(tgl,tarif,id){
            $.ajax({
                url: '{!! route('tarifkegiatan.GetDataPallet') !!}',
                type: 'GET',
                data : {
                    'tgl_berlaku': tgl,
                    'jenis_tarif': tarif,
                    'id': id
                },
                success: function(result) {
                    TablePallet.clear().draw();
                    TablePallet.rows.add(result).draw();
                }
            });
        }

        function tabel_alat2(tgl,tarif,id){
            $.ajax({
                url: '{!! route('tarifkegiatan.GetDataAlat') !!}',
                type: 'GET',
                data : {
                    'tgl_berlaku': tgl,
                    'jenis_tarif': tarif,
                    'id': id
                },
                success: function(result) {
                    TableAlat.clear().draw();
                    TableAlat.rows.add(result).draw();
                }
            });
        }
        
    $(function(){
        var id_kegiatan = $('#kodekegiatan').val();
        $('#container-table').DataTable({
            processing: true,
            serverSide: true,
            "lengthChange": false,
            "bFilter": false,
            "bPaginate": false,
            "scrollY": 160,
            ajax:'http://localhost/gui_front_pbm_laravel/admin/tarifkegiatan/getDatabyID2?id='+id_kegiatan,
            data:{'id_kegiatan':id_kegiatan},
            columns: [
                { data: 'id', name: 'id', visible: false},
                { data: 'id_tarif', name: 'id_tarif', visible: false},
                { data: 'tgl_berlaku', name: 'tgl_berlaku' },
            ]
        });
    });

    TablePallet = $('#data2-table').DataTable({
            "lengthChange": false,
            "bFilter": false,
            "bPaginate": false,
            "scrollY": 140,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false},
                { data: 'id_tarif', name: 'id_tarif', visible: false},
                { data: 'type_pallet', 
                    render: function( data, type, full ) {
                    return format_type(data); }, sortable: false 
                },
                { data: 'tgl_berlaku', name: 'tgl_berlaku', visible: false },
                { data: 'biaya_storage', name: 'biaya_storage' },
                { data: 'biaya_receiving', name: 'biaya_receiving' },
                { data: 'biaya_delivery', name: 'biaya_delivery' },
            ]
        });

    TableAlat = $("#data3-table").DataTable({
            "lengthChange": false,
            "bFilter": false,
            "bPaginate": false,
            "scrollY": 140,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'id_tarif', name: 'id_tarif', visible: false },
                { data: 'alat.nama_alat', name: 'alat.nama_alat' },
                { data: 'per_jam', name: 'per_jam' },
                { data: 'per_ton', name: 'per_ton' },
            ],
        });

    TableSize = $("#size-table").DataTable({
            "lengthChange": false,
            "bFilter": false,
            "bPaginate": false,
            "scrollY": 140,
            data:[],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'id_tarif_container', name: 'id_tarif_container', visible: false },
                { data: 'size.nama_size', name: 'size.nama_size' },
                { data: 'harga_empty', name: 'harga_empty' },
                { data: 'harga_loaded', name: 'harga_loaded' },
            ],
        });

    function format_type(n) {
        if(n == '1'){
                var stat = "<span style='color:#0eab25'><b>SW</b></span>";
        }else if (n == '2'){
                var stat = "<span style='color:#c91a1a'><b>MB</b></span>";
        }
        return stat;
    }

    function tabel_pallet(id){
        $.ajax({
            url: '{!! route('tarifkegiatan.getDatabyID') !!}',
            type: 'GET',
            data : {
                'id': id
            },
            success: function(result) {
                TablePallet.clear().draw();
                TablePallet.rows.add(result).draw();
            }
        });
    }

    function tabel_alat(id){
        $.ajax({
            url: '{!! route('tarifkegiatan.GetDataAlat') !!}',
            type: 'GET',
            data : {
                'id': id
            },
            success: function(result) {
                TableAlat.clear().draw();
                TableAlat.rows.add(result).draw();
            }
        });
    }

    function tabel_size(id){
        $.ajax({
            url: '{!! route('tarifkegiatan.getDataSize') !!}',
            type: 'GET',
            data : {
                'id': id
            },
            success: function(result) {
                TableSize.clear().draw();
                TableSize.rows.add(result).draw();
            }
        });
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

        function refreshTable2() {
             $('#container-table').DataTable().ajax.reload(null,false);;
        }

        $(document).ready(function() {
            var table = $('#data2-table').DataTable();
            var table2 = $('#data3-table').DataTable();
            var table_container = $('#container-table').DataTable();
            var table_size = $('#size-table').DataTable();

            $('#data2-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-black text-bold') ) {
                    $(this).removeClass('selected bg-black text-bold');
                    $('.addpallet').show();
                    $('.editbutton2').hide();
                    $('.hapusbutton2').hide();
                    $('#tipe1').val('').trigger('change');
                    $('#Biaya1').val('');
                    $('#Biaya2').val('');
                    $('#Biaya3').val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected bg-black text-bold');
                    $(this).addClass('selected bg-black text-bold');
                    var select = $('.selected').closest('tr');
                    var data = $('#data2-table').DataTable().row(select).data();
                    closeOpenedRows(table, select);
                    var id = data['id'];
                    var id_tarif = data['id_tarif'];
                    var pallet = data['type_pallet'];
                    var storage = data['biaya_storage'];
                    var recv = data['biaya_receiving'];
                    var delivery = data['biaya_delivery'];

                    $('.editbutton2').show();
                    $('.hapusbutton2').show();
                    $('.addpallet').hide();

                    $('#idtarif1').val(id_tarif);
                    $('#tipe1').val(pallet).trigger('change');
                    $('#Biaya1').val(storage);
                    $('#Biaya2').val(recv);
                    $('#Biaya3').val(delivery);
                }
            });

            $('#data3-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected2 bg-black text-bold') ) {
                    $(this).removeClass('selected2 bg-black text-bold');
                    $('#TypeAlat1').val('').trigger('change');
                    $('#PerJam1').val('');
                    $('#PerTon1').val('');
                    $('.editbutton3').hide();
                    $('.hapusbutton3').hide();
                    $('.addalat').show();
                }
                else {
                    // table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    table2.$('tr.selected2').removeClass('selected2 bg-black text-bold');
                    $(this).addClass('selected2 bg-black text-bold');
                    var select2 = $('.selected2').closest('tr');
                    var data2 = $('#data3-table').DataTable().row(select2).data();
                    closeOpenedRows(table2, select2);

                    var id2 = data2['id'];
                    var id_tarif = data2['id_tarif'];
                    var kode_alat = data2['kode_alat'];
                    var jam = data2['per_jam'];
                    var ton = data2['per_ton'];

                    $('#idtarif2').val(id_tarif);
                    $('#TypeAlat1').val(kode_alat).trigger('change');
                    $('#PerJam1').val(jam);
                    $('#PerTon1').val(ton);
                    $('.editbutton3').show();
                    $('.hapusbutton3').show();
                    $('.addalat').hide();
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

            $('#editpallet').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id_tarif = data['id_tarif'];
                var id = data['id'];


                var tipe = $('#tipe1').val();
                var tgl = $('#TglPallet1').val();
                var tarif = $('#TarifPallet1').val();
                var storage = $('#Biaya1').val();
                var recv = $('#Biaya2').val();
                var delivery = $('#Biaya3').val();

                $.ajax({
                    url: '{!! route('tarifkegiatan.edit_pallet') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'id_tarif': id_tarif,
                        'type_pallet': tipe,
                        'biaya_storage': storage,
                        'biaya_receiving': recv,
                        'biaya_delivery': delivery,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#tipe1').val('').trigger('change');
                        $('#Biaya1').val('');
                        $('#Biaya2').val('');
                        $('#Biaya3').val('');
                        $('.editbutton2').hide();
                        $('.hapusbutton2').hide();
                        $('.addbutton').show();
                        tabel_pallet2(tgl,tarif,id_tarif);
                        if (results.success === true) {
                            
                        }else {
                            swal("💢", results.message);
                        }
                    }
                });
            });


            $('#editcfs').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id_tarif = data['id_tarif'];
                var id = data['id'];
                var row = table.row( select );
                $.ajax({
                    url: '{!! route('tarifkegiatan.edit_detail_cfs') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'id_tarif': id_tarif
                    },
                    success: function(results) {
                        console.log(results);
                        $('#id').val(results.id);
                        $('#kodekegiatan2').val(results.id_tarif);

                        if (results.type_pallet == 1) {
                            $('#tipe2').val('SW');
                        }else {
                            $('#tipe2').val('MB');
                        }
                        
                        $('#tipe2h').val(results.type_pallet);
                        $('#Tgl2').val(results.tgl_berlaku);
                        $('#Biaya1e').val(results.biaya_storage);
                        $('#Biaya2e').val(results.biaya_receiving);
                        $('#Biaya3e').val(results.biaya_delivery);
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapuscfs').click( function () {
                var select = $('.selected').closest('tr');
                var datacfs = $('#data2-table').DataTable().row(select).data();
                var datacontainer = $('#container-table').DataTable().row(select).data();

                if (datacfs != null) {
                    var id = datacfs['id'];
                    var id_tarif = datacfs['id_tarif'];
                }

                if (datacontainer != null) {
                    var id = datacontainer['id'];
                    var id_tarif = datacontainer['id_tarif'];
                }
                
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
                            url: '{!! route('tarifkegiatan.hapus_detail_tarif') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
                                'id_tarif': id_tarif,
                            },
                            success: function (results) {
                                if (results.success === true) {
                                    swal("Berhasil!", results.message, "success");
                                } else {
                                    swal("Gagal!", results.message, "error");
                                }
                                refreshTable();
                                refreshTable2();
                                $('.hapus-button').hide();
                                $('.edit-button').hide();
                            }
                        });
                    }
                });
            });

            $('#editalat').click( function () {
                var select = $('.selected2').closest('tr');
                var data = $('#data3-table').DataTable().row(select).data();
                var id_tarif_cfs = data['id_tarif'];
                var id = data['id'];

                var kode_alat = $('#TypeAlat1').val();
                var perjam = $('#PerJam1').val();
                var perton = $('#PerTon1').val();
                var row = table2.row( select );

                $.ajax({
                    url: '{!! route('tarifkegiatan.edit_alat') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'id_tarif_cfs': id_tarif_cfs,
                        'kode_alat': kode_alat,
                        'perjam': perjam,
                        'perton': perton,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#TypeAlat1').val('').trigger('change');
                        $('#PerJam1').val('');
                        $('#PerTon1').val('');
                        $('.editbutton').hide();
                        $('.hapusbutton').hide();
                        $('.addbutton').show();
                        tabel_alat(id_tarif);
                        if (results.success === true) {
                            
                        }else {
                            swal("💢", results.message);
                        }
                    }
                });
            });

            $('#editsize').click( function () {
                var select = $('.selected2').closest('tr');
                var data = $('#size-table').DataTable().row(select).data();
                var id_tarif_container = data['id_tarif_container'];
                var id = data['id'];

                var kode_size = $('#KodeSize1').val();
                var empty = $('#Empty1').val();
                var loaded = $('#Loaded1').val();
                var row = table_size.row( select );

                $.ajax({
                    url: '{!! route('tarifkegiatan.edit_size') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'id_tarif_container': id_tarif_container,
                        'kode_size': kode_size,
                        'harga_empty': empty,
                        'harga_loaded': loaded,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#KodeSize1').val('').trigger('change');
                        $('#Empty1').val('');
                        $('#Loaded1').val('');
                        $('.editbutton').hide();
                        $('.hapusbutton').hide();
                        $('.addbutton').show();
                        tabel_size(id_tarif_container);
                        if (results.success === true) {
                            
                        }else {
                            swal("💢", results.message);
                        }
                    }
                });
            });

            $('#hapusalat').click( function () {
                var select = $('.selected2').closest('tr');
                var data = $('#data3-table').DataTable().row(select).data();
                var id_tarif_cfs = data['id_tarif'];
                var id = data['id'];

                var kode_alat = $('#TypeAlat1').val();
                var perjam = $('#PerJam1').val();
                var perton = $('#PerTon1').val();
                var row = table2.row( select );

                $.ajax({
                    url: '{!! route('tarifkegiatan.hapus_alat') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                        'id_tarif': id_tarif,
                        'kode_alat': kode_alat,
                        'perjam': perjam,
                        'perton': perton,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#TypeAlat1').val('').trigger('change');
                        $('#PerJam1').val('');
                        $('#PerTon1').val('');
                        $('.editbutton').hide();
                        $('.hapusbutton').hide();
                        $('.addbutton').show();
                        tabel_alat(id_tarif_cfs);
                        if (results.success === true) {
                            
                        }else {
                            swal("Gagal!", results.message, "error");
                        }
                    }
                });
            });

        });

        $('#ADD_PALLET').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_PALLET");
            var formData = registerForm.serialize();
            var id = $('#idtarif1').val();
            var tgl = $('#TglPallet1').val();
            var tarif = $('#TarifPallet1').val();
            // Check if empty of not
            $.ajax({
                url:'{!! route('tarifkegiatan.store_detail_cfs') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#tipe1').val('').trigger('change');
                    $('#Biaya1').val('');
                    $('#Biaya2').val('');
                    $('#Biaya3').val('');
                    tabel_pallet2(tgl,tarif,id);
                    if (data.success === true) {
                            swal("Berhasil!", data.message, "success");
                    } else {
                            swal(data.title, data.message, "error");
                    }
                },
            });
        });

        $('#UPDATE_DETAIL').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#UPDATE_DETAIL");
            var formData = registerForm.serialize();
            $.ajax({
                url:'{!! route('tarifkegiatan.ajaxupdate_detail') !!}',
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
                    refreshTable2();
                    $(".addform").show();
                    $(".editform").hide();
                },
            });
        });

        $('#ADD_ALAT').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_ALAT");
            var formData = registerForm.serialize();
            var id = $('#idtarif2').val();
            var tgl = $('#TglAlat1').val();
            var tarif = $('#TarifAlat1').val();
            // Check if empty of not
            $.ajax({
                url:'{!! route('tarifkegiatan.store_detail_alat') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#TypeAlat1').val('').trigger('change');
                    $('#PerJam1').val('');
                    $('#PerTon1').val('');
                    tabel_alat2(tgl,tarif,id);
                    if (data.success === true) {
                        swal("Berhasil!", data.message, "success");
                    } else {
                        swal(data.title, data.message, "error");
                    }
                },
            });
        });

        $('#ADD_SIZE').submit(function (e) {
            e.preventDefault();
            var registerForm = $("#ADD_SIZE");
            var formData = registerForm.serialize();
            var id = $('#idtarif1').val();
            // Check if empty of not
            $.ajax({
                url:'{!! route('tarifkegiatan.store_size') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    console.log(data);
                    $('#KodeSize1').val('').trigger('change');
                    $('#Empty1').val('');
                    $('#Loaded1').val('');
                    tabel_size(id);
                    if (data.success === true) {
                        
                    } else {
                        swal(data.title, data.message, "error");
                    }
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