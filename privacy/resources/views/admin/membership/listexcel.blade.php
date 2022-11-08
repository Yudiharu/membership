<!DOCTYPE html>
<html lang="en">
<head>
	<style> 
        
     @page {
            border: solid 1px #0b93d5;

        }

        .title {
            margin-top: 0.5cm;
        }
        .title h1 {
            text-align: left;
            font-size: 14pt;
        }
        
        .header {
            margin-left: 50px;
            margin-right: 0px;
            /*font-size: 10pt;*/
            padding-top: 10px;
            /*border: solid 1px #0b93d5;*/
        }

        .left {
            float: left;
        }

        .right {
            float: right;
        }

        .clearfix {
            overflow: auto;
        }

        .content {
            margin-left: 10px;
            padding-top: 10px;
        }
        .catatan {
            font-size: 10pt;
        }

        footer {
                position: fixed; 
                top: 19cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }

        /* Table desain*/
        table.grid {
            width: 100%;
        }
</style>
</head>
<body>

	<table rules="rows" class="grid" style="font-size: 10pt; vertical-align: top; width: 27cm" border="1">
    <thead>
        <tr>
            <th>NIB</th>
            <th>Nama</th>
            <th>Tanggal Masuk</th>
            <th>Lokasi Kerja</th>
            <th>Jabatan</th>
            <th>Gender</th>
            <th>Tempat</th>
            <th>Tanggal Lahir</th>
            <th>Umur</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Status Kerja</th>
            <th>No KTP</th>
            <th>No KK</th>
            <th>No NPWP</th>
            <th>Gol Darah</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->tanggal_masuk }}</td>
                <td>{{ $item->lokasi_kerja }}</td>
                <td>{{ $item->jabatan }}</td>
                <td>{{ $item->gender }}</td>
                <td>{{ $item->tempat }}</td>
                <td>{{ $item->tanggal_lahir }}</td>
                <td>{{ $item->umur }}</td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->agama }}</td>
                <td>{{ $item->status_kerja }}</td>
                <td>{{ "`".$item->no_ktp }}</td>
                <td>{{ "`".$item->no_kk }}</td>
                <td>{{ $item->no_npwp }}</td>
                <td>{{ $item->gol_darah }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
<hr>
</body>
</html>