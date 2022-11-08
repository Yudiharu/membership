<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
    <title>CETAK CONTAINER JO <?php echo ($get_jo) ?></title>
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
            <h1>CETAK CONTAINER</h1>
            <p style="font-size: 8pt;"><b>No.Trucking:</b> <?php echo ($trucking->no_trucking) ?> / <b>Tgl.Trucking:</b> <?php echo ($tgl) ?> / <b>Status.Trucking:</b> <?php echo ($trucking->status) ?> / <b>No.JO:</b> <?php echo ($get_jo) ?> / <b>No.JOR:</b> <?php echo ($get_jor) ?> / <br><b>Customer:</b> <?php echo ($customer->nama_customer) ?> / <b>Shipper:</b> <?php echo ($shipper->nama_customer) ?></p>
    </div>


    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>No</th>
            <th>No. SPB</th>
            <th>Container</th>
            <th>Size Container</th>
            <th>No Seal</th>
            <th>Gudang</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>Uang Jalan</th>
        </tr>
        </thead>
        
        <tbody>
                <?php foreach ($truckingdetail as $key => $row) : ?>
                <tr class="border">
                    <td class="border" style="width: 4%"><?php echo $key+1 ?></td>
                    <td class="border" align="left" style="width: 12%"><?php echo $row->no_spb ?></td>
                    <td class="border" align="left"><?php echo $row->kode_container ?></td>
                    <td class="border" align="left"><?php echo $row->sizecontainer->nama_size ?></td>
                    <td class="border" align="left"><?php echo $row->no_seal ?></td>
                    <td class="border" align="left"><?php echo $row->gudangdetail->nama_gudang ?></td>

                    <?php if ($row->kode_mobil == null) { ?>
                        <td class="border" align="left"><?php echo $row->kode_mobil?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                    <?php } ?>
                    
                    <?php if (is_numeric($row->kode_sopir) != 1) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->sopir->nama_sopir?></td>
                    <?php } ?>

                    <?php if ($row->uang_jalan == null) { ?>
                        <td class="border" align="left"></td>
                    <?php }else { ?>
                        <td class="border" align="center"><?php echo number_format($row->uang_jalan,'0',',','.') ?></td>
                    <?php } ?>
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