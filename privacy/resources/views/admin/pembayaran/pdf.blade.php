<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trucking Vendor - {{ $request }}</title>
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
            padding-top: 120px;
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
            <h1>Trucking Vendor</h1>
                <p><b>Periode: </b><?php echo ($pembayaran->tanggalkembali_dari) ?> s.d <?php echo ($pembayaran->tanggalkembali_sampai) ?> <br>
                    <b>Pemilik Mobil :</b> {{ $pemilik->nama_vendor }} / <b>No. Transaksi :</b> {{ $request }} / <b>Tanggal :</b> {{ $pembayaran->tanggal_pembayaran }} / <b>Status :</b> {{ $pembayaran->status }}</p>
    </div>

    <table class="grid1" style="margin-bottom: 25px;width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>No</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>Tgl. SPB</th>
            <th>Tgl. Kembali SPB</th>
            <th>No. SPB</th>
            <th>Gudang</th>
            <th>Tujuan</th>
            <th>Container</th>
            <th>Tarif</th>
            <th>Uang Jalan</th>
            <th>Sisa</th>
            <th>No. JO</th>
        </tr>
        </thead>

        <tbody>
            @foreach($pembayarandetail as $key => $row)

            <tr>
                <td><?php echo $key+1 ?></td>
                <td><?php echo $row->mobil->nopol?></td>
                <td><?php echo $row->kode_sopir?></td>
                <td><?php echo $row->tgl_spb?></td>
                <td><?php echo $row->tgl_kembali?></td>
                <td><?php echo $row->no_spb?></td>
                <?php if ($row->kode_gudang != '-') { ?>
                    <td><?php echo $row->gudangdetail->nama_gudang?></td>
                <?php }else { ?>
                    <td><?php echo $row->kode_gudang?></td>
                <?php } ?>
                <td><?php echo $row->tujuan?></td>
                <td><?php echo $row->kode_container?></td>
                <td><?php echo number_format($row->tarif,'0',',','.')?></td>
                <td><?php echo number_format($row->uang_jalan,'0',',','.')?></td>
                <td><?php echo number_format($row->sisa,'0',',','.')?></td>
                <td><?php echo $row->no_joborder?></td>
            </tr>

            <?php
                $grandtotaltarif = $pembayarandetail->sum('tarif');
                $grandtotaluang_jalan = $pembayarandetail->sum('uang_jalan');
                $grandtotalsisa = $pembayarandetail->sum('sisa');
            ?>

            @endforeach
        </tbody>

        <tfoot>
        <tr style="background-color: #F5D2D2">
            <td colspan="9" style="font-weight: bold; text-align: center">Total</td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa,'0',',','.');?></td>
            <td></td>
        </tr>
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