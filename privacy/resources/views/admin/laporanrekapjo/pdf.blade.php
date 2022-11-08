<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>REKAP JOB ORDER</title>

    <style>
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
            padding: 7px;
            text-align: center;

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
          padding: 4px;
        }

        table.grid1 tr:nth-child(even) {
          background-color: #dddddd;
        }

        body{        
            padding-top: 110px;
            font-family: sans-serif;
        }
        .fixed-header, .fixed-footer{
            width: 100%;
            position: fixed;       
            padding: 10px 0;
            text-align: center;
        }
        .fixed-header{
            top: 0;
        }
        .fixed-footer{
            bottom: 0;
        }

        #header .page:after {
          content: counter(page, decimal);
        }

        .page_break { page-break-after: always; }
    </style>
</head>
<body>

<div class="fixed-header">
        <div style="float: left">
            <img src="{{ asset('css/logo_gui.png') }}" alt="" height="25px" width="25px" align="left">
            <p id="color" style="font-size: 8pt;" align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($nama2) ?></b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lokasi: <?php echo ($nama) ?></p>
        </div>

        <div id="header">
            <p class="page" style="float: right; font-size: 9pt;"><b>Date :</b> <?php echo date_format($dt,"d/m/Y") ?>&nbsp;&nbsp;&nbsp;
            <b>Time :</b> <?php echo date_format($dt,"H:i:s") ?>&nbsp;&nbsp;&nbsp;
            <b>Page :</b> </p>
        </div>

        <br><br>
            <?php
            if ($jenis != 'SEMUA') { ?>
                <?php 
                    if ($jenis == '1') { 
                        $jen = 'Non-Transhipment';
                    } else { 
                        $jen = 'Transhipment';
                    } 
                ?>
                <h1>REKAP JOB ORDER (Type Kegiatan : <?php echo $jen; ?>)</h1>
            <?php } 
                else{?>
                    <h1>REKAP JOB ORDER</h1>
            <?php } ?>

            <p>Periode: <?php echo ($tanggal_awal) ?> s.d <?php echo ($tanggal_akhir) ?></p>
        
    </div>


    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>No</th>
            <th>No. Job Order</th>
            <?php if ($jenis == 'SEMUA') { ?>
                <th>Type Kegiatan</th>
            <?php } ?>
            <th>Type JO</th>
            <th>Tanggal</th>
            <th>Customer</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($jo as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <?php if ($jenis == 'SEMUA') { ?>
                        <?php
                            if ($row->type_kegiatan == '1') {
                                $kegiatan = 'Non-Transhipment';
                            }else {
                                $kegiatan = 'Transhipment';
                            }
                        ?>
                        <td class="border" align="left"><?php echo $kegiatan; ?></td>
                    <?php } ?>
                    <td class="border" align="left"><?php 
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
                    ?></td>
                    <td class="border" align="left"><?php echo $row->tgl_joborder ?></td>
                    <td class="border" align="left"><?php echo $row->customer1->nama_customer ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        
        <tfoot>
        
        </tfoot>
    </table>

    <?php
        if ($format_ttd != 1) {?>
            <br><br>
            <table width="100%" style="font-size:10pt; text-align: center; bottom: 0">
                <tr>
                    <td width="30%">Dibuat,</td>
                </tr>
                <tr><td colspan="3"><br><br><br></td></tr>
                <tr>
                    <td><?php echo $ttd; ?></td>
                </tr>
            </table>
        <?php } 
        else{?>
            <div class="page_break"></div>
            <br><br>
            <table width="100%" style="font-size:10pt; text-align: center; bottom: 0">
                <tr>
                    <td width="30%">Dibuat,</td>
                </tr>
                <tr><td colspan="3"><br><br><br></td></tr>
                <tr>
                    <td><?php echo $ttd; ?></td>
                </tr>
            </table>
    <?php } ?>
</body>
</html>