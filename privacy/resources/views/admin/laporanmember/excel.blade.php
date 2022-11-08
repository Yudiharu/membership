<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>LAPORAN REKAP TENAGA KERJA</title>

    <style>
        body {
            font-family: sans-serif;
            /*font-family: courier;*/
            /*font-weight: bold;*/
        }
        .header {
            text-align: center;
        },
        .header, h1 {
            font-size: 11pt;
            margin-bottom: 0px;
        }

        .header, p {
            font-size: 10pt;
            margin-top: 0px;
        }
        .table_content {
            color: #232323;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 15px;
        }

        .table_content, .border {
            border: 1px solid black;
            padding: 4px;
        }
        .table_content, thead, th {
            text-transform: uppercase;
            padding: 7px;
            text-align: center;

        }

        .left {
            float: left;
        }

        .right {
            float: right;
        }

        ul li {
            display:inline;
            list-style-type:none;
        }

        table.grid1 {
          font-family: sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        table.grid1 td, table.grid1 th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 5px;
        }

        table.grid1 tr:nth-child(even) {
          background-color: #dddddd;
        }
    </style>
</head>
<body>

<div class="header">
    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>NIB</th>
            <th>Nama</th>
            <th>Tgl Masuk</th>
            <th>Lokasi Kerja</th>
            <th>Jabatan</th>
            <th>Gender</th>
            <th>Tempat</th>
            <th>Tgl Lahir</th>
            <th>Umur</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Status Kerja</th>
            <th>No.KTP</th>
            <th>No.NPWP</th>
            <th>No.KK</th>
            <th>Gol Darah</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $row->nik ?></td>
                    <td class="border" align="left"><?php echo $row->nama ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_masuk; ?></td>
                    <td class="border" align="left"><?php echo $row->lokasi_kerja ?></td>
                    <td class="border" align="left"><?php echo $row->jabatan ?></td>
                    <td class="border" align="left"><?php echo $row->gender ?></td>
                    <td class="border" align="left"><?php echo $row->tempat ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_lahir ?></td>
                    <td class="border" align="left"><?php echo $row->umur ?></td>
                    <td class="border" align="left"><?php echo $row->alamat ?></td>
                    <td class="border" align="left"><?php echo $row->agama ?></td>
                    <td class="border" align="left"><?php echo $row->status_kerja ?></td>
                    <td class="border" align="left"><?php echo $row->no_ktp ?></td>
                    <td class="border" align="left"><?php echo $row->no_npwp ?></td>
                    <td class="border" align="left"><?php echo $row->no_kk ?></td>
                    <td class="border" align="left"><?php echo $row->gol_darah ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>