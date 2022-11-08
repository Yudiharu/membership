@extends('adminlte::page')

@section('title', 'Premi Helper Detail')

@section('content_header')
   
@stop

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <a href="{{ $list_url }}" class="btn btn-danger btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
    <button type="button" class="btn btn-default btn-xs" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
    <span class="pull-right">
        <button type="button" class="btn btn-danger btn-xs" onclick="hapusall()"><i class="fa fa-warning"></i> Hapus Semua Detail</button>
        <font style="font-size: 16px;"> Premi Helper Detail <b>{{$insentif->no_insentif}}</b></font>
    </span>
@include('sweet::alert')
{{ Form::hidden('Link',request()->getSchemeAndHttpHost(), ['class'=> 'form-control','readonly','id'=>'Link1']) }}
<body onLoad="load()">
    <div class="box box-danger">
        <div class="box-body">
            <div class="addform">
            @include('errors.validation')
            {!! Form::open(['id'=>'ADD_DETAIL']) !!}
            <center><kbd>ADD FORM</kbd></center><br>
                <div class="row">
                    {{ Form::hidden('no_insentif', $no_insentif, ['class'=> 'form-control','id'=>'NoInsentif1','readonly']) }}
                    {{ Form::hidden('kode_helper', $helper, ['class'=> 'form-control','id'=>'KodeHelper1','readonly']) }}
                    {{ Form::hidden('type_helper', $type_helper, ['class'=> 'form-control','id'=>'TypeHelper1','readonly']) }}
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Helper', 'Nama Helper:') }}
                            {{ Form::text('nama_helper', $Helper->nama_helper, ['class'=> 'form-control','id'=>'Helper1','autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Helper', 'Type Helper:') }}
                            {{ Form::text('type_helper', $type, ['class'=> 'form-control','id'=>'Helper1','autocomplete'=>'off','readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tgl Pakai Dari:') }}
                            {{ Form::text('tgl_pakai_dari', $tgldr,['class'=> 'form-control','id'=>'Tanggaldr1' ,'required','readonly'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('Tanggals', 'Tgl Pakai Sampai:') }}
                            {{ Form::text('tgl_pakai_sampai', $tglsp,['class'=> 'form-control','id'=>'Tanggalsp1' ,'required','readonly'])}}
                        </div>
                    </div>
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('NamaAlat', 'Nama Alat:') }}
                            {{ Form::text('nama_alat', null,['class'=> 'form-control','id'=>'NamaAlat1' ,'required','readonly'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{ Form::label('TypeAlat', 'Kode Tagging:') }}
                            {{ Form::text('type', null,['class'=> 'form-control','id'=>'AsetAlat1' ,'required','readonly'])}}
                        </div>
                    </div>
                    <br>
                    
                        <button type="button" class="btn btn-success btn-sm" onclick="getpakai()">Get Detail</button>&nbsp;
                </div>
                {!! Form::close() !!}
            </div>
        
            <div class="editform">
            @include('errors.validation')
            {!! Form::open(['id'=>'UPDATE_DETAIL']) !!}
            <center><kbd>EDIT FORM</kbd></center><br>
                <div class="row">
                    {{ Form::hidden('id', null, ['class'=> 'form-control','id'=>'ID','readonly']) }}
                    {{ Form::hidden('no_pemakaian', null, ['class'=> 'form-control','id'=>'NoPakai2','readonly']) }}
                    
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
                        <th>Id</th>
                        <th>No Insentif</th>
                        <th>No Time Sheet</th>
                        <th>No Pemakaian</th>
                        <th>No Job order</th>
                        <th>Tanggal Kerja</th>
                        <th>Hari Libur</th>
                        <th>Premi D. Kota/Hr</th>
                        <th>Premi L. Kota/Hr</th>
                        <th>Premi Hari Libur</th>
                        <th>Total Insentif</th>
                        <th>Luar Kota</th>
                     </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <button type="button" class="back2Top btn btn-warning btn-xs" id="back2Top"><i class="fa fa-arrow-up" style="color: #fff"></i> <i>{{ $nama_company }}</i> <b>({{ $nama_lokasi }})</b></button>
    
    <div id="mySidenav" class="sidenav">
        <button type="button" class="btn btn-danger btn-xs hapus-button" id="hapusdetail" data-toggle="modal" data-target="">HAPUS <i class="fa fa-times-circle"></i></button>
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
                background-color: #149933;
                bottom: 186px;
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

        function hapusall(){
            swal({
                    title: "Hapus semua DETAIL?",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    reverseButtons: !0
            }).then(function(e){
                if (e.value === true) {
                    swal({
                        title: "<b>Proses Sedang Berlangsung</b>",
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: false
                    })

                    var no_insentif = $('#NoInsentif1').val();
                    $.ajax({
                        url:'{!! route('insentifhelperdetail.hapusall') !!}',
                        type:'POST',
                        data : {
                            'no_insentif': no_insentif
                        },
                        success: function(result) {
                            console.log(result);
                            if (result.success === true) {
                                swal("Berhasil!", result.message, "success");
                            } else {
                                swal("Gagal!", result.message, "error");
                            }
                            // window.location.reload();
                            refreshTable();
                        },
                    });
                }else {
                    e.dismiss;
                }
            },function(dismiss){
                return false;
            })
            
        }

        function getpakai()
        {
            var no_insentif = $('#NoInsentif1').val();
            var kode_helper = $('#KodeHelper1').val();
            var type_helper = $('#TypeHelper1').val();
            var tgl_dr = $('#Tanggaldr1').val();
            var tgl_sp = $('#Tanggalsp1').val();
            
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
                            url: '{!! route('insentifhelperdetail.getpakai') !!}',
                            type: 'POST',
                            data : {
                                'no_insentif': no_insentif,
                                'kode_helper': kode_helper,
                                'type_helper': type_helper,
                                'tgl_pakai_dari' : tgl_dr,
                                'tgl_pakai_sampai' : tgl_sp,
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

        
    $(function(){
        var no_insentif = $('#NoInsentif1').val();
        var link = $('#Link1').val();
        $('#data2-table').DataTable({
            "bPaginate": true,
            "bFilter": true,
            "scrollY": 220,
            "scrollX": 200,
            processing: true,
            serverSide: true,
            ajax:link+'/gui_front_02/admin/insentifhelperdetail/getDatabyID?id='+no_insentif,
            data:{'no_insentif':no_insentif},
            columns: [
                { data: 'id', name: 'id', visible:false},
                { data: 'no_insentif', name: 'no_insentif', visible:false },
                { data: 'no_timesheet', name: 'no_timesheet' },
                { data: 'no_pemakaian', name: 'no_pemakaian' },
                { data: 'no_joborder', name: 'no_joborder' },
                { data: 'tgl_pakai', name: 'tgl_pakai' },
                { data: 'hari_libur',  
                    render: function( data, type, full ) {
                    return liburan(data); }
                },
                { data: 'premi_dalamkota',  
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_luarkota',  
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'premi_libur',  
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'total_insentif',  
                    render: function( data, type, full ) {
                    return formatNumber(data); }
                },
                { data: 'luar_kota', 
                    render: function( data, type, full ) {
                    return luarkota(data); }
                },
            ]
            
        });
    });

    function hitungan_pemakaian(n) {
        if(n == '1'){
            var stat = "<span style='color:#030100'><b>Jam</b></span>";
        }else if(n == '2'){
            var stat = "<span style='color:#0eab25'><b>Hour Meter</b></span>";
        }
        return stat;
    }

    function liburan(n) {
        if(n == '0'){
            var stat = "<span style='color:#030100'><b>Tidak</b></span>";
        }else if(n == '1'){
            var stat = "<span style='color:#0eab25'><b>Ya</b></span>";
        }
        return stat;
    }

    function luarkota(n) {
        if(n == '0'){
            var stat = "<span style='color:#030100'><b>Tidak</b></span>";
        }else if(n == '1'){
            var stat = "<span style='color:#0eab25'><b>Ya</b></span>";
        }
        return stat;
    }

        $(document).ready(function() {
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            var table = $('#data2-table').DataTable();

            $('#data2-table tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected bg-gray') ) {
                    $(this).removeClass('selected bg-gray');
                    $('.add-button').hide();
                    $('.hapus-button').hide();
                    $('.edit-button').hide();
                    $('.view-button').hide();
                    $('#NamaAlat1').val('');
                    $('#AsetAlat1').val('');
                }else{
                    table.$('tr.selected').removeClass('selected bg-gray text-bold');
                    $(this).addClass('selected bg-gray');
                    var select = $('.selected').closest('tr');
                    var data = $('#data2-table').DataTable().row(select).data();

                    closeOpenedRows(table, select);
                    
                    var id = data['id'];
                    $('.hapus-button').show();
                    $('.edit-button').show();
                    $.ajax({
                        url: '{!! route('insentifhelperdetail.show_alat') !!}',
                        type: 'POST',
                        data : {
                            'id': id,
                        },
                        success: function(results) {
                            console.log(results);
                            $('#NamaAlat1').val(results.nama_alat);
                            $('#AsetAlat1').val(results.type);
                        }
                    });
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

            $('#editjoborderdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id = data['id'];
                $.ajax({
                    url: '{!! route('insentifhelperdetail.edit_detail') !!}',
                    type: 'POST',
                    data : {
                        'id': id,
                    },
                    success: function(results) {
                        console.log(results);
                        $('#ID').val(results.id);
                        $('#NoPakai2').val(results.no_pemakaian);
                        $('#Alat2').val(results.kode_alat).trigger('change');
                        $('#Hitungan2').val(results.hitungan_pemakaian).trigger('change');
                        $('#NoTS2').val(results.no_timesheet);
                        $('#Tanggal2').val(results.tgl_pakai);
                        $('#Operator2').val(results.operator).trigger('change');
                        $('#Helper2a').val(results.helper1).trigger('change');
                        $('#Helper2b').val(results.helper2).trigger('change');
                        $('#Pekerjaan2').val(results.pekerjaan);
                        if (results.hari_libur == 1) {
                            document.getElementById("Libur2").checked = true;
                        }else {
                            document.getElementById("Libur2").checked = false;
                        }
                        $('#JamDr2').val(results.jam_dr);
                        $('#JamSp2').val(results.jam_sp);
                        $('#Istirahat2').val(results.istirahat);
                        $('#StandBy2').val(results.stand_by);
                        $('#HmDr2').val(results.hm_dr);
                        $('#HmSp2').val(results.hm_sp);

                        hitungan2();
                        $('.editform').show();
                        $('.addform').hide();
                    }
                });
            });

            $('#hapusdetail').click( function () {
                var select = $('.selected').closest('tr');
                var data = $('#data2-table').DataTable().row(select).data();
                var id = data['id'];
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
                            url: '{!! route('insentifhelperdetail.hapus_detail') !!}',
                            type: 'POST',
                            data : {
                                'id': id,
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

        function formatNumber(n) {
            if(n == 0){
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
                    showConfirmButton: false
            })
            e.preventDefault();
            var registerForm = $("#ADD_DETAIL");
            var formData = registerForm.serialize();
            // Check if empty of not
            $.ajax({
                url:'{!! route('insentifhelperdetail.store') !!}',
                type:'POST',
                data:formData,
                success:function(data) {
                    $('#Alat1').val('').trigger('change');
                    $('#Hitungan1').val('').trigger('change');
                    $('#NoTS1').val('');
                    $('#Tanggal1').val('');
                    $('#Operator1').val('').trigger('change');
                    $('#Helper1a').val('').trigger('change');
                    $('#Helper1b').val('').trigger('change');
                    $('#Pekerjaan1').val('').trigger('change');
                    $('#JamDr1').val('');
                    $('#JamSp1').val('');
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
                    url:'{!! route('insentifhelperdetail.updateajax') !!}',
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

        function cancel_edit(){
            $(".addform").show();
            $(".editform").hide();
        }
    </script>
@endpush