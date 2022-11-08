@extends('adminlte::page')

@section('title', 'Mobil')

@section('content_header')

@stop

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@include('sweet::alert')
<body onLoad="load()">
    <div class="box box-solid">
        <div class="box-body">
             <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" style="font-size: 12px;">
                <thead>
                <tr class="bg-blue">
                    <th>Kode Mobil</th>
                    <th>Nopol</th>
                    <th>Jenis Mobil</th>
                    <th>Tahun</th>
                    <th>No Asset</th>
                    <th>Lokasi</th>
                 </tr>
                </thead>
            </table>

        </div>
    </div>
</body>
@stop

@push('css')

@endpush
@push('js')
  
    <script type="text/javascript">
        $(function() {
            $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('mobil.data') !!}',
            columns: [
                { data: 'kode_mobil', name: 'kode_mobil' },
                { data: 'nopol', name: 'nopol' },
                { data: 'jenismobil.nama_jenis_mobil', name: 'jenismobil.nama_jenis_mobil' },
                { data: 'tahun', name: 'tahun' },
                { data: 'no_asset_mobil', name: 'no_asset_mobil' },
                { data: 'masterlokasi.nama_lokasi', name: 'masterlokasi.nama_lokasi' },
            ]
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        function refreshTable() {
             $('#data-table').DataTable().ajax.reload(null,false);;
        }

    </script>
@endpush