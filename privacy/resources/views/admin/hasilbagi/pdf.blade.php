<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <?php include 'fungsi/date.php'?>

    <title>Hasil Bagi Usaha Sopir - {{ $request }}</title>
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
            <h1>Hasil Bagi Usaha Sopir</h1>
            <p>Periode: <?php echo format_tgl($hasilbagi->spb_dari) ?> s.d <?php echo format_tgl($hasilbagi->spb_sampai) ?> <br>
                <b>Sopir :</b> {{ $sopir->nama_sopir }} / <b>NIS :</b> {{ $sopir->nis }} / <b>No. Transaksi :</b> {{ $request }} / <b>Tanggal :</b> {{ format_tgl($hasilbagi->tanggal_hasilbagi) }}</p>
    </div>
    
    <table class="grid1" style="margin-bottom: 25px;width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>No</th>
            <th>Tgl. SPB</th>
            <th>Tgl. Kembali SPB</th>
            <th>No. SPB</th>
            <th>No. SPB Manual</th>
            <th>Container</th>
            <th>No. Polisi</th>
            <th>Muatan</th>
            <th>Tujuan</th>
            <th>Tarif HBU</th>
            <th>Uang Jalan</th>
            <th>Sisa</th>
            <th>BBM</th>
            <th>UJ-BBM</th>
        </tr>
        </thead>

        <tbody>
            @foreach($hasilbagidetail as $key => $row)

            <tr>
                <td><?php echo $key+1 ?></td>
                <td><?php echo format_tgl($row->tanggal_spb)?></td>
                <td><?php echo format_tgl($row->tanggal_kembali)?></td>
                <td><?php echo $row->no_spb?></td>
                <td><?php echo $row->no_spb_manual?></td>
                <td><?php echo $row->kode_container?></td>
                <td><?php echo $row->mobil->nopol?></td>
                <td><?php echo $row->muatan?></td>
                <td><?php echo $row->tujuan?></td>
                <td><?php echo number_format($row->tarif,'0',',','.')?></td>
                <td><?php echo number_format($row->uang_jalan,'0',',','.')?></td>
                <td><?php echo number_format($row->sisa,'0',',','.')?></td>
                <td><?php echo number_format($row->bbm,'0',',','.')?></td>
                <td><?php echo number_format($row->sisa_ujbbm,'0',',','.')?></td>
            </tr>

            <?php
                $grandtotaltarif = $hasilbagidetail->sum('tarif');
                $grandtotaluang_jalan = $hasilbagidetail->sum('uang_jalan');
                $grandtotalbbm = $hasilbagidetail->sum('bbm');
                $grandtotalsisa = $hasilbagidetail->sum('sisa');
                $grandtotalsisaujbbm = $hasilbagidetail->sum('sisa_ujbbm');
            ?>

            @endforeach
        </tbody>

        <tfoot>
        <tr style="background-color: #F5D2D2">
            <td colspan="9" style="font-weight: bold; text-align: center">Total</td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalbbm,'0',',','.');?></td>
            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisaujbbm,'0',',','.');?></td>
        </tr>
        </tfoot>

    </table>

    <?php
        if ($format_ttd != 1) {?>
            <br><br>
            <div class="footer" style="font-size: 10pt;">
                <table width="60%" style="  font-size: 9pt" border="0">
                    <tr>
                        <td style="width: 120px">Hasil Bersih ({{$hasilbagi->gaji}}%)</td>
                        <td style="width: 10px">:</td>
                        <td>{{ number_format($hasilbagi->nilai_gaji,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Tabungan ({{$hasilbagi->tabungan}}%)</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->nilai_tabungan,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Honor Kenek</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->honor_kenek,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Total</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->gt_hbu,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >GT UJ-BBM</td>
                        <td>:</td>
                        <td>{{ number_format($grandtotalsisaujbbm,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Objek PPH21</td>
                        <td>:</td>
                        <td>{{ number_format($grandtotalsisaujbbm + $hasilbagi->gt_hbu,'0',',','.') }}</td>
                    </tr>
                </table>

                <br><br>
                <div class="tgl">
                    &nbsp;Palembang, <?php echo date_format($date,'d F Y');?>
                </div>

                <table width="100%" style="font-size:10pt; text-align:center;padding:0px; margin:0px; border-collapse:collapse" border="0">
                    <tr style="padding:0px; margin:0px">
                        <td width="30%">Dibuat,</td>
                        <td width="30%">Diperiksa,</td>
                        <td width="30%">Disetujui,</td>
                        <td width="40%">Diketahui,</td>
                        <td width="40%">Diterima,</td>
                    </tr>
                    <tr style="padding:0px; margin:0px"><td colspan="3"><br><br><br></td></tr>
                    <tr style="padding:0px; margin:0px">
                        <td><?php echo $ttd; ?></td>
                        <td><?php echo $diperiksa1->mengetahui.' / '.$diperiksa2->mengetahui; ?></td>
                        <td><?php echo $disetujui->mengetahui; ?></td>
                        <td><?php echo $diketahui->mengetahui; ?></td>
                        <td></td>
                    </tr>
                    <tr style="padding:0px; margin:0px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        <?php } 
        else{?>
            <div class="page_break"></div>
            <br><br>
            <div class="footer" style="font-size: 10pt;">
                <table width="60%" style="  font-size: 9pt" border="0">
                    <tr>
                        <td style="width: 120px">Hasil Bersih ({{$hasilbagi->gaji}}%)</td>
                        <td style="width: 10px">:</td>
                        <td>{{ number_format($hasilbagi->nilai_gaji,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Tabungan ({{$hasilbagi->tabungan}}%)</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->nilai_tabungan,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Honor Kenek</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->honor_kenek,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Total</td>
                        <td>:</td>
                        <td>{{ number_format($hasilbagi->gt_hbu,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >GT UJ-BBM</td>
                        <td>:</td>
                        <td>{{ number_format($grandtotalsisaujbbm,'0',',','.') }}</td>
                    </tr>
                    <tr>
                        <td >Objek PPH21</td>
                        <td>:</td>
                        <td>{{ number_format($grandtotalsisaujbbm + $hasilbagi->gt_hbu,'0',',','.') }}</td>
                    </tr>
                </table>

                <br><br>
                <div class="tgl">
                    &nbsp;Palembang, <?php echo date_format($date,'d F Y');?>
                </div>

                <table width="100%" style="font-size:10pt; text-align:center;padding:0px; margin:0px; border-collapse:collapse" border="0">
                    <tr style="padding:0px; margin:0px">
                        <td width="30%">Dibuat,</td>
                        <td width="30%">Diperiksa,</td>
                        <td width="30%">Disetujui,</td>
                        <td width="40%">Diketahui,</td>
                        <td width="40%">Diterima,</td>
                    </tr>
                    <tr style="padding:0px; margin:0px"><td colspan="3"><br><br><br></td></tr>
                    <tr style="padding:0px; margin:0px">
                        <td><?php echo $ttd; ?></td>
                        <td><?php echo $diperiksa1->mengetahui.' / '.$diperiksa2->mengetahui; ?></td>
                        <td><?php echo $disetujui->mengetahui; ?></td>
                        <td><?php echo $diketahui->mengetahui; ?></td>
                        <td></td>
                    </tr>
                    <tr style="padding:0px; margin:0px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
    <?php } ?>
</body>
</html>