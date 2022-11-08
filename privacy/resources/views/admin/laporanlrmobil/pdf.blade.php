<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php
use App\Models\Sopir;
use App\Models\Hasilbagi;
use App\Models\HasilbagiDetail;
use App\Models\Spb;
use App\Models\TruckingnonDetail;
use App\Models\Pemakaian;
use App\Models\PemakaianDetail;
use App\Models\Pemakaianban;
use App\Models\PemakaianbanDetail;
use App\Models\Mobil;
?>
<head>
    <meta charset="utf-8" />
        <title>LAPORAN LABA RUGI MOBIL</title>

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
    <h1>LAPORAN LABA RUGI MOBIL</h1>
    <p>Periode: <?php echo ($tgl_awal) ?> s/d <?php echo ($tgl_akhir) ?></p>
</div>

<?php
$hasilbruto = 0;
$gaji = 0;
$honorkenek = 0;
?>

<table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
    <thead>
        <tr>
            <th style="width: 100%;">Nopol</th>
            <th style="width: 100%;">Hasil Bruto</th>
            <th style="width: 100%;">Gaji</th>
            <th style="width: 100%;">Honor Kenek</th>
            <th style="width: 100%;">BAN</th>
            <th style="width: 100%;">OLI</th>
            <th style="width: 100%;">SPRT</th>
            <th style="width: 100%;">BBM</th>
            <th style="width: 100%;">Laba/rugi</th>
        </tr>
    </thead>
    <tbody>   
    <?php foreach ($mobil as $key => $row) : ?>
        <tr class="border">
            <td class="border" align="left"><?php echo $row->nopol ?></td>
<?php 
// $container = Spb::where('kode_mobil', $row->kode_mobil)->whereBetween('tgl_spb', array($tgl_awal,$tgl_akhir))->sum(\DB::raw('trucking - uang_jalan'));
$container = 0;
$noncontainer = TruckingnonDetail::where('kode_mobil', $row->kode_mobil)->whereBetween('tanggal_spb', array($tgl_awal,$tgl_akhir))->sum(\DB::raw('tarif_gajisopir - uang_jalan'));

$hasilbruto = $container + $noncontainer;
$gaji = $hasilbruto * 0.125;

$sopir = TruckingnonDetail::where('kode_mobil', $row->kode_mobil)->whereBetween('tanggal_spb', array($tgl_awal,$tgl_akhir))->first();
if ($sopir != null) {
    $honorkenek = Hasilbagi::where('kode_sopir', $sopir->kode_sopir)->whereBetween('spb_dari', array($tgl_awal,$tgl_akhir))->sum(\DB::raw('honor_kenek'));
}else {
    $honorkenek = 0;
}

$totalban = Pemakaianban::join('pemakaianban_detail','pemakaianban.no_pemakaianban','=','pemakaianban_detail.no_pemakaianban')->where('pemakaianban.no_asset_mobil', $row->no_asset_mobil)->whereBetween('pemakaianban.tanggal_pemakaianban', array($tgl_awal,$tgl_akhir))->sum(\DB::raw('qty*harga'));

$totaloli = Pemakaian::join('pemakaian_detail','pemakaian.no_pemakaian','=','pemakaian_detail.no_pemakaian')->join('produk','pemakaian_detail.kode_produk','=','produk.id')->where('pemakaian.no_asset_mobil', $row->no_asset_mobil)->whereBetween('pemakaian.tanggal_pemakaian', array($tgl_awal,$tgl_akhir))->where('produk.kode_kategori','OLI')->sum(\DB::raw('pemakaian_detail.qty*pemakaian_detail.harga'));  
$totalsprt = Pemakaian::join('pemakaian_detail','pemakaian.no_pemakaian','=','pemakaian_detail.no_pemakaian')->join('produk','pemakaian_detail.kode_produk','=','produk.id')->where('pemakaian.no_asset_mobil', $row->no_asset_mobil)->whereBetween('pemakaian.tanggal_pemakaian', array($tgl_awal,$tgl_akhir))->where('produk.kode_kategori','SPRT')->sum(\DB::raw('pemakaian_detail.qty*pemakaian_detail.harga'));
$totalbbm = Pemakaian::join('pemakaian_detail','pemakaian.no_pemakaian','=','pemakaian_detail.no_pemakaian')->join('produk','pemakaian_detail.kode_produk','=','produk.id')->where('pemakaian.no_asset_mobil', $row->no_asset_mobil)->whereBetween('pemakaian.tanggal_pemakaian', array($tgl_awal,$tgl_akhir))->where('produk.kode_kategori','BBM')->sum(\DB::raw('pemakaian_detail.qty*pemakaian_detail.harga'));

$labarugi = $hasilbruto - $gaji - $honorkenek - $totalban - $totaloli - $totalsprt - $totalbbm;

?>
                <td class="border" align="left"><?php echo number_format($hasilbruto) ?></td>
                <td class="border" align="left"><?php echo number_format($gaji) ?></td>
                <td class="border" align="left"><?php echo number_format($honorkenek) ?></td>
                <td class="border" align="left"><?php echo number_format($totalban) ?></td>
                <td class="border" align="left"><?php echo number_format($totaloli) ?></td>
                <td class="border" align="left"><?php echo number_format($totalsprt) ?></td>
                <td class="border" align="left"><?php echo number_format($totalbbm) ?></td>
                <td class="border" align="left"><?php echo number_format($labarugi) ?></td>
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
                    <td width="30%">Dilaporkan Oleh,</td>
                    <td width="30%">Diperiksa Oleh,</td>
                    <td width="30%">Disetujui Oleh,</td>
                    <td width="30%">Diketahui Oleh,</td>
                </tr>
                <tr><td colspan="3"><br><br><br></td></tr>
                <tr>
                    <td><?php echo $ttd; ?></td>
                    <td><?php echo 'Marchendro / Dhanu'; ?></td>
                    <td><?php echo 'Vita'; ?></td>
                    <td><?php echo 'Rince'; ?></td>
                    <td><?php echo 'Susanto W'; ?></td>
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