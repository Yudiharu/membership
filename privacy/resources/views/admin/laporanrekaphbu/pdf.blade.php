<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php
use App\Models\Sopir;
use App\Models\Hasilbagi;
use App\Models\HasilbagiDetail;
?>
<head>
    <meta charset="utf-8" />
        <title>REKAP HBU SOPIR</title>

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
    <h1>REKAP HBU SOPIR</h1>
    <?php 
        if ($bulan == '1') {
            $bln = 'Januari';
        }else if ($bulan == '2') {
            $bln = 'Februari';
        }else if ($bulan == '3') {
            $bln = 'Maret';
        }else if ($bulan == '4') {
            $bln = 'April';
        }else if ($bulan == '5') {
            $bln = 'Mei';
        }else if ($bulan == '6') {
            $bln = 'Juni';
        }else if ($bulan == '7') {
            $bln = 'Juli';
        }else if ($bulan == '8') {
            $bln = 'Agustus';
        }else if ($bulan == '9') {
            $bln = 'September';
        }else if ($bulan == '10') {
            $bln = 'Oktober';
        }else if ($bulan == '11') {
            $bln = 'November';
        }else if ($bulan == '12') {
            $bln = 'Desember';
        }
    ?>
    <p>Periode: <?php echo ($bln) ?></p>
</div>

    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 100%;">NIS</th>
            <th style="width: 100%;">No Order/HBU</th>
            <th style="width: 100%;">No IAP</th>
            <th style="width: 100%;">Nama</th>
            <th style="width: 100%;">No Rek</th>
            <th style="width: 100%;">Hasil Bersih(12.5%)</th>
            <th style="width: 100%;">Tabungan(10%)</th>
            <th style="width: 100%;">Honor Kenek</th>
            <th style="width: 100%;">Total</th>
            <th style="width: 100%;">GT UJ-BBM</th>
            <th style="width: 100%;">Objek PPH 21</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($hasilbagi as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->nis ?></td>
                    <td class="border" align="left"><?php echo $row->no_hasilbagi ?></td>
                    <td class="border" align="left"><?php echo $row->no_invoice ?></td>
                <?php $sopir = Sopir::find($row->kode_sopir); ?>
                    <td class="border" align="left"><?php echo $sopir->nama_sopir ?></td>
                    <td class="border" align="left"><?php echo $sopir->no_rekening ?></td>
                    <td class="border" align="left"><?php echo number_format($row->nilai_gaji,'0',',','.') ?></td>
                    <td class="border" align="left"><?php echo number_format($row->nilai_tabungan,'0',',','.') ?></td>
                    <td class="border" align="left"><?php echo number_format($row->honor_kenek,'0',',','.') ?></td>
                    <td class="border" align="left"><?php echo number_format($row->gt_hbu,'0',',','.') ?></td>
                <?php $sisaujbbm = HasilbagiDetail::where('no_hasilbagi', $row->no_hasilbagi)->sum('sisa_ujbbm'); ?>
                    <td class="border" align="left"><?php echo number_format($sisaujbbm,'0',',','.') ?></td>
                    <td class="border" align="left"><?php echo number_format($row->gt_hbu + $sisaujbbm,'0',',','.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
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