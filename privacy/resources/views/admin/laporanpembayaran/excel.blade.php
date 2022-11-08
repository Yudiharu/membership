<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php
    use App\Models\PembayaranDetail;
?>
<head>
    <meta charset="utf-8" />
        <title>LAPORAN TRUCKING VENDOR</title>

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
    $leng = count($data);
    $datas = array();

    foreach ($data as $rowdata) { 
        $nomor = $rowdata->no_pembayaran;
        $no_spb = $rowdata->no_spb;
        $datas[] = array(
            'no_pembayaran'=>$nomor,
            'no_spb'=>$no_spb,
        );
    }

    $i = 0;
    $j = 0;
for($i = 0; $i < $leng; $i++){
        $row = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->join('u5611458_db_pusat.vendor','pembayaran_pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik','vendor.nama_vendor')->where('pembayaranpemilik_detail.no_pembayaran',$datas[$i]['no_pembayaran'])->where('pembayaranpemilik_detail.no_spb',$datas[$i]['no_spb'])->first();
?>
        
        <tbody>
            <tr class="border">
            <?php $nomor2 = $row->no_pembayaran; ?>
            <?php if ($nomor1 == null) { ?>
                <?php $nomor1 = $row->no_pembayaran; ?>
                <td class="border" align="left"><?php echo $nomor1 ?></td>
                <td class="border" align="left"><?php echo $row->tanggal_pembayaran ?></td>
                <td class="border" align="left"><?php echo $row->no_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_kembali ?></td>
                <?php if ($kode_pemilik == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->nama_vendor ?></td>
                <?php } ?>
                <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                <td class="border" align="left"><?php echo $row->kode_sopir ?></td>
                <td class="border" align="left"><?php echo $row->kode_container ?></td>
                <?php if ($row->kode_gudang == '-') {?>
                    <td class="border" align="left"><?php echo $row->kode_gudang ?></td>
                <?php } else { ?>
                    <td class="border" align="left"><?php echo $row->gudangdetail->nama_gudang ?></td>
                <?php } ?> 
                <td class="border" align="left"><?php echo $row->tarif ?></td>
                <td class="border" align="left"><?php echo $row->uang_jalan ?></td>
                <td class="border" align="left"><?php echo $row->sisa ?></td>
                <?php if ($stat == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->status ?></td>
                <?php } ?>
            <?php }else if ($nomor1 == $nomor2) { ?>
                <td class="border" align="left"></td>
                <td class="border" align="left"></td>
                <td class="border" align="left"><?php echo $row->no_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_kembali ?></td>
                <?php if ($kode_pemilik == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->nama_vendor ?></td>
                <?php } ?>
                <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                <td class="border" align="left"><?php echo $row->kode_sopir ?></td>
                <td class="border" align="left"><?php echo $row->kode_container ?></td>
                <?php if ($row->kode_gudang == '-') {?>
                    <td class="border" align="left"><?php echo $row->kode_gudang ?></td>
                <?php } else { ?>
                    <td class="border" align="left"><?php echo $row->gudangdetail->nama_gudang ?></td>
                <?php } ?> 
                <td class="border" align="left"><?php echo $row->tarif ?></td>
                <td class="border" align="left"><?php echo $row->uang_jalan ?></td>
                <td class="border" align="left"><?php echo $row->sisa ?></td>
                <?php if ($stat == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->status ?></td>
                <?php } ?>
            <?php }else { ?>
                <?php $nomor1 = $row->no_pembayaran; ?>
                <td class="border" align="left"><?php echo $nomor2 ?></td>
                <td class="border" align="left"><?php echo $row->tanggal_pembayaran ?></td>
                <td class="border" align="left"><?php echo $row->no_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_spb ?></td>
                <td class="border" align="left"><?php echo $row->tgl_kembali ?></td>
                <?php if ($kode_pemilik == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->nama_vendor ?></td>
                <?php } ?>
                <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                <td class="border" align="left"><?php echo $row->kode_sopir ?></td>
                <td class="border" align="left"><?php echo $row->kode_container ?></td>
                <?php if ($row->kode_gudang == '-') {?>
                    <td class="border" align="left"><?php echo $row->kode_gudang ?></td>
                <?php } else { ?>
                    <td class="border" align="left"><?php echo $row->gudangdetail->nama_gudang ?></td>
                <?php } ?> 
                <td class="border" align="left"><?php echo $row->tarif ?></td>
                <td class="border" align="left"><?php echo $row->uang_jalan ?></td>
                <td class="border" align="left"><?php echo $row->sisa ?></td>
                <?php if ($stat == 'SEMUA') {?>
                    <td class="border" align="left"><?php echo $row->status ?></td>
                <?php } ?>
            <?php } ?>
            </tr>
        </tbody>
<?php
    $j = $i + 1;
    if($j >= $leng){
        $j = 0;
    }

    if ($datas[$i]['no_pembayaran'] != $datas[$j]['no_pembayaran']) {
        $byr4 = PembayaranDetail::with('mobil','gudangdetail')->where('no_pembayaran',$data[$i]['no_pembayaran'])->get();

        $grandtotaltarif1 = $byr4->sum('tarif');
        $grandtotaluang_jalan1 = $byr4->sum('uang_jalan');
        $grandtotalsisa1 = $byr4->sum('sisa');
?>
<tfoot>
<?php if ($kode_pemilik == 'SEMUA') {
    if ($stat == 'SEMUA') {?>
    <tr style="background-color: #F5D2D2">
        <td colspan="10" style="font-weight: bold; text-align: center">TOTAL</td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaltarif1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaluang_jalan1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotalsisa1; ?></td>
        <td></td>
    </tr>
    <?php } else{ ?>
    <tr style="background-color: #F5D2D2">
        <td colspan="10" style="font-weight: bold; text-align: center">TOTAL</td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaltarif1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaluang_jalan1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotalsisa1; ?></td>
    </tr>
    <?php } 
} else{
    if ($stat == 'SEMUA') {?>
    <tr style="background-color: #F5D2D2">
        <td colspan="9" style="font-weight: bold; text-align: center">TOTAL</td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaltarif1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaluang_jalan1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotalsisa1; ?></td>
        <td></td>
    </tr>
    <?php } else{ ?>
    <tr style="background-color: #F5D2D2">
        <td colspan="9" style="font-weight: bold; text-align: center">TOTAL</td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaltarif1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotaluang_jalan1; ?></td>
        <td style="text-align: left;">&nbsp;<?php echo $grandtotalsisa1; ?></td>
    </tr>
    <?php } 
} ?>
</tfoot>
<?php }
} ?>
    </table>
</body>
</html>