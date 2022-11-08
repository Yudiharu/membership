<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>LAPORAN REKAP JOB ORDER</title>

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
            <th>No</th>
            <th>No. Job Order</th>
            <th>Type Kegiatan</th>
            <th>Type JO</th>
            <th>Tanggal</th>
            <th>Customer</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <?php
                        if ($row->type_kegiatan == '1') {
                            $kegiatan = 'Non-Transhipment';
                        }else {
                            $kegiatan = 'Transhipment';
                        }
                    ?>
                    <td class="border" align="left"><?php echo $kegiatan; ?></td>
                    <td class="border" align="left">
                    <?php 
                        if ($row->type_jo == '1') {
                                $tipe = 'Bongkar Muat Curah';
                        }else if ($row->type_jo == '2') {
                                $tipe = 'Bongkar Muat Non Curah';
                        }else if ($row->type_jo == '3') {
                                $tipe = 'Rental Alat';
                        }else if ($row->type_jo == '4') {
                                $tipe = 'Trucking';
                        }else if ($row->type_jo == '5') {
                                $tipe = 'Lain-lain';
                        }
                        echo $tipe;
                    ?>
                    </td>
                    <td class="border" align="left"><?php echo $row->tgl_joborder ?></td>
                    <?php 
                        if (stripos($row->customer1->nama_customer, '&') !== FALSE ) { 
                            $ket = stripos($row->customer1->nama_customer, '&');
                            $keterangan = substr_replace($row->customer1->nama_customer, '&amp;', $ket, 1);
                        } else {
                            $keterangan = $row->customer1->nama_customer;
                        }
                    ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>