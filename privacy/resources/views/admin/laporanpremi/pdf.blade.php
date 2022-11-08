<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>REKAP PREMI</title>

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
        <?php if ($jenis == 'BANK') { ?>
            <h1>REKAP PREMI BANK</h1>
        <?php } else{?>
            <h1>REKAP PREMI</h1>
        <?php } ?>

        <p>Bulan: <?php echo ($bulan) ?> Tahun: <?php echo ($tahun) ?></p>
    </div>

    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr >
            <th>NIK</th>
            <th>No.Rek</th>
            <th>Operator/Helper</th>
            <th>Premi</th>
            <th>Type</th>
        </tr>
        </thead>
<?php $gtot = 0; ?>
        <tbody>
            <?php foreach ($jo as $key => $row) : ?>
                <tr class="border">
                    <td class="border" align="left"><?php echo $row->nik ?></td>
                    <td class="border" align="left"><?php echo $row->no_rekening ?></td>
                    <td class="border" align="left"><?php echo $row->nama ?></td>
                    <td class="border" align="left"><?php echo number_format($row->total,'0',',','.') ?></td>
                    <td class="border" align="left"><?php echo $row->type ?></td>
                </tr>
                <?php $gtot += $row->total; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="border" >
                <td colspan="3" style="font-weight: bold; text-align: center"> Grand Total</td>
                <td class="border" align="right">&nbsp;<?php echo number_format($gtot,'0',',','.');?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <?php
        if ($format_ttd != 1) {?>
            <br><br>
            <table width="100%" style="font-size:10pt; text-align: center; bottom: 0">
                <tr>
                    <td width="30%">Palembang, <?php echo date_format($dt,"d/m/Y") ?></td>
                    <td width="30%"></td>
                    <td width="30%"></td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    <td width="30%">Dibuat,</td>
                    <td width="30%">Diperiksa Oleh,</td>
                    <td width="30%">Diketahui Oleh,</td>
                    <td width="10%"></td>
                </tr>
                <tr><td colspan="3"><br><br><br></td></tr>
                <tr>
                    <td><?php echo $ttd; ?></td>
                    <td>Ismanto</td>
                    <td>Rince</td>
                    <td></td>
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