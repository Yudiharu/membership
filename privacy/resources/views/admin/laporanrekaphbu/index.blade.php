@extends('adminlte::page')

@section('title', 'Rekap HBU')

@section('content_header')
    
@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@include('sweet::alert')
<body onLoad="panggil()">
    <div class="box box-solid">
        <div class="modal fade" id="button4"  role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Rekap<b> Pembayaran Gaji Sopir</b></h4>
                </div>
                @include('errors.validation')
                {!! Form::open(['route' => ['laporanrekaphbu.export'],'method' => 'get','id'=>'form', 'target'=>"_blank"]) !!}
                        <div class="modal-body">
                            <div class="row">
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {{ Form::label('jenis', 'Bulan Periode:') }} 
                                        {{ Form::selectMonth('bulan', null, ['class'=> 'form-control select2','id'=>'namabulan','placeholder'=>'','style'=>'width: 100%','required'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {{ Form::label('jenis', 'Tahun:') }} 
                                        {{ Form::selectYear('tahun', 2020, 2040, null, ['class'=> 'form-control select2','id'=>'namatahun','placeholder'=>'','style'=>'width: 100%','required'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {{ Form::label('pilih', 'Format Laporan:') }}
                                        {{Form::select('jenis_report', ['PDF' => 'PDF', 'excel' => 'Excel'], null, ['class'=> 'form-control select2','style'=>'width: 100%','placeholder' => '','id'=>'report1','required'=>'required'])}}
                                    </div>
                                </div>
                                    <div class="col-sm-6">  
                                        <input type="checkbox" name="ttd" value="1"/>&nbsp;Cetak TTD di halaman baru<br>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                {{ Form::submit('Cetak', ['class' => 'btn btn-success crud-submit']) }}
                                {{ Form::button('Close', ['class' => 'btn btn-danger','data-dismiss'=>'modal']) }}&nbsp;
                            </div>
                        </div>
                    {!! Form::close() !!}            
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

</div>
</body>
@stop

@push('css')

@endpush
@push('js')
  
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2').select2({
            placeholder: "Pilih",
            allowClear: true,
        });

        function load(){
            $('#button4').modal('show');
        }

        function panggil(){
            load();
            startTime();
        }
        
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });

        $('.modal-dialog').resizable({
    
        });
    </script>
@endpush