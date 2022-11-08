<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <?php
        use App\Models\PembayaranDetail;
    ?>
    <meta charset="utf-8" />
        <title>LAPORAN TRUCKING VENDOR</title>

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
            <h1>LAPORAN TRUCKING VENDOR</h1>
            <?php
                if ($stat == 'SEMUA') {?>
                    <p>Periode: <?php echo ($tanggal_awal) ?> s.d <?php echo ($tanggal_akhir) ?></p>
            <?php } 
                else{?>
                    <p>Periode: <?php echo ($tanggal_awal) ?> s.d <?php echo ($tanggal_akhir) ?> ; Status : <?php echo ($stat) ?></p>
            <?php } ?>
    </div>


    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>No. Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>No SPB</th>
            <th>Tgl SPB</th>
            <th>Tgl Kembali</th>
            <?php
            if ($kode_pemilik == 'SEMUA') {?>
                <th>Pemilik</th>
            <?php } ?>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>Container</th>
            <th>Gudang</th>
            <th>Tarif</th>
            <th>Uang Jalan</th>
            <th>Sisa</th>
            <?php
            if ($stat == 'SEMUA') {?>
                <th>Status</th>
            <?php } ?>
        </tr>
        </thead>
        
            
            <?php $nomor1 = null; ?>
            <?php $nomor2 = null; ?> 
            <?php 
                $data = array();
                foreach ($byr as $rowdata) { 
                    $nomor = $rowdata->no_pembayaran;
                    $nomorspb = $rowdata->no_spb;
                    $data[] = array(
                        'no_pembayaran'=>$nomor,
                        'no_spb'=>$nomorspb,
                    );
                }

                $i = 0;
                $j = 0;
                for($i = 0; $i < $leng; $i++){ 
                    $byr3 = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->join('u5611458_db_pusat.vendor','pembayaran_pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik','vendor.nama_vendor')->where('pembayaranpemilik_detail.no_pembayaran',$data[$i]['no_pembayaran'])->where('pembayaranpemilik_detail.no_spb',$data[$i]['no_spb'])->first();   
                    ?>

                    <tbody>   
                            <tr class="border">
                                <?php $nomor2 = $byr3->no_pembayaran; ?>
                                <?php if ($nomor1 == null) { ?>
                                    <?php $nomor1 = $byr3->no_pembayaran; ?>
                                    <td class="border" align="left"><?php echo $nomor1 ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tanggal_pembayaran ?></td>
                                    <td class="border" align="left"><?php echo $byr3->no_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_kembali ?></td>
                                    <?php
                                    if ($kode_pemilik == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->nama_vendor ?></td>
                                    <?php } ?>
                                    <td class="border" align="left"><?php echo $byr3->mobil->nopol ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_sopir ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_container ?></td>
                                    <?php
                                    if ($byr3->kode_gudang == '-') {?>
                                        <td class="border" align="left"><?php echo $byr3->kode_gudang ?></td>
                                    <?php } 
                                    else { ?>
                                        <td class="border" align="left"><?php echo $byr3->gudangdetail->nama_gudang ?></td>
                                    <?php } ?> 
                                    <td class="border" align="left"><?php echo number_format($byr3->tarif,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->uang_jalan,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->sisa,'0',',','.') ?></td>
                                    <?php
                                    if ($stat == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->status ?></td>
                                    <?php } ?>
                                <?php }else if ($nomor1 == $nomor2) { ?>
                                    <td class="border" align="left"></td>
                                    <td class="border" align="left"></td>
                                    <td class="border" align="left"><?php echo $byr3->no_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_kembali ?></td>
                                    <?php
                                    if ($kode_pemilik == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->nama_vendor ?></td>
                                    <?php } ?>
                                    <td class="border" align="left"><?php echo $byr3->mobil->nopol ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_sopir ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_container ?></td>
                                    <?php
                                    if ($byr3->kode_gudang == '-') {?>
                                        <td class="border" align="left"><?php echo $byr3->kode_gudang ?></td>
                                    <?php } 
                                    else { ?>
                                        <td class="border" align="left"><?php echo $byr3->gudangdetail->nama_gudang ?></td>
                                    <?php } ?> 
                                    <td class="border" align="left"><?php echo number_format($byr3->tarif,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->uang_jalan,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->sisa,'0',',','.') ?></td>
                                    <?php
                                    if ($stat == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->status ?></td>
                                    <?php } ?>
                                <?php }else { ?>
                                    <?php $nomor1 = $byr3->no_pembayaran; ?>
                                    <td class="border" align="left"><?php echo $nomor2 ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tanggal_pembayaran ?></td>
                                    <td class="border" align="left"><?php echo $byr3->no_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_spb ?></td>
                                    <td class="border" align="left"><?php echo $byr3->tgl_kembali ?></td>
                                    <?php
                                    if ($kode_pemilik == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->nama_vendor ?></td>
                                    <?php } ?>
                                    <td class="border" align="left"><?php echo $byr3->mobil->nopol ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_sopir ?></td>
                                    <td class="border" align="left"><?php echo $byr3->kode_container ?></td>
                                    <?php
                                    if ($byr3->kode_gudang == '-') {?>
                                        <td class="border" align="left"><?php echo $byr3->kode_gudang ?></td>
                                    <?php } 
                                    else { ?>
                                        <td class="border" align="left"><?php echo $byr3->gudangdetail->nama_gudang ?></td>
                                    <?php } ?> 
                                    <td class="border" align="left"><?php echo number_format($byr3->tarif,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->uang_jalan,'0',',','.') ?></td>
                                    <td class="border" align="left"><?php echo number_format($byr3->sisa,'0',',','.') ?></td>
                                    <?php
                                    if ($stat == 'SEMUA') {?>
                                        <td class="border" align="left"><?php echo $byr3->status ?></td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                    </tbody>
                    <?php
                        $j = $i + 1;
                        if($j >= $leng){
                            $j = 0;
                        }

                        if ($data[$i]['no_pembayaran'] != $data[$j]['no_pembayaran']) {
                            $byr4 = PembayaranDetail::with('mobil','gudangdetail')->where('no_pembayaran',$data[$i]['no_pembayaran'])->get();

                            $grandtotaltarif1 = $byr4->sum('tarif');
                            $grandtotaluang_jalan1 = $byr4->sum('uang_jalan');
                            $grandtotalsisa1 = $byr4->sum('sisa');
                            ?>
                            <tfoot>
                                <?php
                                if ($kode_pemilik == 'SEMUA') {
                                    if ($stat == 'SEMUA') {?>
                                        <tr style="background-color: #F5D2D2">
                                            <td colspan="10" style="font-weight: bold; text-align: center">TOTAL</td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa1,'0',',','.');?></td>
                                            <td></td>
                                        </tr>
                                    <?php } else{ ?>
                                        <tr style="background-color: #F5D2D2">
                                            <td colspan="10" style="font-weight: bold; text-align: center">TOTAL</td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa1,'0',',','.');?></td>
                                        </tr>
                                    <?php } 
                                } else{ 
                                    if ($stat == 'SEMUA') {?>
                                        <tr style="background-color: #F5D2D2">
                                            <td colspan="9" style="font-weight: bold; text-align: center">TOTAL</td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa1,'0',',','.');?></td>
                                            <td></td>
                                        </tr>
                                    <?php } else{ ?>
                                        <tr style="background-color: #F5D2D2">
                                            <td colspan="9" style="font-weight: bold; text-align: center">TOTAL</td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan1,'0',',','.');?></td>
                                            <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa1,'0',',','.');?></td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tfoot>
                        <?php }
                } 
                
                $grandtotaltarif = $byr->sum('tarif');
                $grandtotaluang_jalan = $byr->sum('uang_jalan');
                $grandtotalsisa = $byr->sum('sisa');
            ?>
        
    </table>

    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
            <tr style="background-color: #e6f2ff">
                <th colspan="1" style="font-weight: bold; text-align: center"></th>
                <th>GrandTotal Tarif</th>
                <th>GrandTotal Uang Jalan</th>
                <th>GrandTotal Sisa</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr style="background-color: #F5D2D2">
                <td colspan="1" style="font-weight: bold; text-align: center">AMOUNT</td>
                <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif,'0',',','.');?></td>
                <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan,'0',',','.');?></td>
                <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa,'0',',','.');?></td>
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
            <table class="grid1" style="margin-left: auto; margin-right: auto; width: 50%; font-size: 11px;">
                <tfoot>
                    <tr style="background-color: #e6f2ff">
                        <th colspan="1" style="font-weight: bold; text-align: center"></th>
                        <th>GrandTotal Tarif</th>
                        <th>GrandTotal Uang Jalan</th>
                        <th>GrandTotal Sisa</th>
                    </tr>
                    <tr style="background-color: #F5D2D2">
                        <td colspan="1" style="font-weight: bold; text-align: center">AMOUNT</td>
                        <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaltarif,'0',',','.');?></td>
                        <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotaluang_jalan,'0',',','.');?></td>
                        <td style="text-align: left;">&nbsp;<?php echo number_format($grandtotalsisa,'0',',','.');?></td>
                    </tr>
                </tfoot>
            </table>
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