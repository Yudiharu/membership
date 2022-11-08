@extends('adminlte::page')

@section('title', 'Upload Job Order')

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
                  <h4 class="modal-title">Upload<b> Job Order</b></h4>
                </div>
                @include('errors.validation')
                {!! Form::open(['route' => ['uploadjob.export'],'method' => 'get','id'=>'form', 'target'=>"_blank"]) !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {{ Form::label('tanggal_awal', 'Dari Tanggal:') }}
                                        {{ Form::date('tanggal_awal',\Carbon\Carbon::now(), ['class'=> 'form-control','id'=>'tanggal1']) }}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {{ Form::label('tanggal_akhir', 'Sampai Tanggal:') }}
                                        {{ Form::date('tanggal_akhir',\Carbon\Carbon::now(), ['class'=> 'form-control','id'=>'tanggal2']) }}
                                    </div>
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