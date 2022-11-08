<?php

use App\Models\PemakaianAlat;
use App\Models\PemakaianAlatDetail;
use App\Models\Alat;
use App\Models\Signature;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <br>
    <title>Report Premi Helper ~ {{ $request }}</title>
    <style>
        @page {
            border: solid 1px #0b93d5;
            width: 24.13cm;
            height: 27.94cm;
            font-family: 'sans-serif';
            margin-right: 1.2cm; 
            
        }

        .title {
            margin-top: 1.2cm;
        }
        .title h1 {
            text-align: center;
            font-size: 14pt;
        }

        .header {
            margin-left: 0px;
            margin-right: 0px;
            /*font-size: 10pt;*/
            padding-top: 5px;
            /*border: solid 1px #0b93d5;*/
        }

        .left {
            float: left;
        }

        .right {
            float: right;
        }

        .clearfix {
            overflow: auto;
        }

        .content {
            padding-top: 20px
        }
        .catatan {
            font-size: 10pt;
        }

        /* Table desain*/
        table.grid {
            width: 100%;
        }
        table.grid th{
            background: #FFF;
            text-align:center;
            /*padding-left:0.2cm;*/
            /*padding-right:0.2cm;*/
            /*border:1px solid #fff;*/
            padding-top:2px;
            padding-bottom:2px;
        }

        table.grid tr td{
            /*padding-top:0.5mm;*/
            /*padding-bottom:0.5mm;*/
            padding-left:2mm;
            padding-right:2mm;
            /*border:1px solid #fff;*/
        }
        .list-item {
            height: 2.1in;
            margin: 0px;
        }

    </style>

</head>
<body>

<div class="left">
    <p id="color" style="font-size: 8pt;" align="left"><b><?php echo ($nama2) ?></b></p>
</div>
<div class="right">
    <p id="color" style="font-size: 8pt;" align="left"><b>Waktu Cetak : </b><?php echo ($dt) ?></p>
</div>
<div class="title">
    <h1>Report Premi Helper</h1>
    <p style="font-size: 10pt;text-align: center">Periode :{{ $header->tgl_pakai_dari }} s/d {{ $header->tgl_pakai_sampai }}</p>
</div>
<div class="header">
    <div class="left">
        <table width="50%" style="  font-size: 10pt" border="0">
            <tr>
                <td style="width: 100px">Nama Helper</td>
                <td style="width: 10px">:</td>
                <td>{{ $header->helper->nama_helper }}&nbsp;&nbsp;&nbsp;</td>
                <td>NIK :{{ $header->helper->nik }}&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
    <div class="right">
        <table width="10%" style="  font-size: 10pt" border="0">
            <tr>
                <td style="width: 120px">No. Premi</td>
                <td style="width: 10px">:</td>
                <td>{{ $header->no_insentif }}&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
<div class="content">
<hr>
    <section class="list-item">
        <table class="grid" style="font-size:10pt; width: 100%;" border="0" >
            <thead>
            <tr >
                <th width="64px" style="text-align: left;">Tanggal</th>
                <th width="78px" style="text-align: left;">Type Alat</th>
                <th width="83px" style="text-align: left;">No. Time Sheet</th>
                <th width="134px"style="text-align: left;">Pekerjaan</th>
                <th width="50px" style="text-align: right;">L. Kota</th>
                <th width="50px" style="text-align: right;">H. Libur</th>
            </tr>
            </thead>
        </table>
<hr>
<?php

$tanggal1 = null;
$tanggal2 = null; 

?>
        <table class="grid" style="font-size: 10pt; padding-top:-5px; padding-bottom: -15px; width: 100%;"  >
            <tbody>
            <?php foreach ($detailexport as $key => $row): ?>
                <tr>
                <?php 
                    $detailalat = PemakaianAlatDetail::where('no_timesheet',$row->no_timesheet)->first();
                    $detailpemakaian = PemakaianAlatDetail::where('no_timesheet',$row->no_timesheet)->first();
                    $alat = Alat::where('kode_alat',$detailpemakaian->kode_alat)->first();

                    if($row->luar_kota == '0')
                    {
                        $luarkota = 'T';
                    }
                    else
                    {
                        $luarkota = 'Y';
                    }
                    if($row->hari_libur == '0')
                    {
                        $harilibur = 'T';
                    }
                    else
                    {
                        $harilibur = 'Y';
                    }

                    ?>

                    <?php $tanggal2 = $row->tgl_pakai; ?>
                    <?php if ($tanggal1 == null) { ?>
                        <?php $tanggal1 = $row->tgl_pakai; ?>
                        <th style="text-align: left; width: 11%; font-weight: normal; word-spacing: -7px;"><?php echo $tanggal1; ?></th>
                    <?php }else if ($tanggal1 == $tanggal2) { ?>
                        <th style="text-align: left; width: 11%; font-weight: normal; word-spacing: -7px;"></th>
                    <?php }else { ?>
                        <?php $tanggal1 = $row->tgl_pakai; ?>
                        <th style="text-align: left; width: 11%; font-weight: normal; word-spacing: -7px;"><?php echo $tanggal2; ?></th>
                    <?php } ?>
                    <th style="text-align: left; width:17%; font-weight: normal; word-spacing: -2px; padding-left: 28px"><?php echo $alat->type; ?></th>
                    <th style="text-align: left; width:17%; font-weight: normal; padding-left:26px"><?php echo $row->no_timesheet; ?></th>
                    <th style="text-align: left; width:35%; font-weight: normal; word-spacing: -3px; padding-left:35px"><?php echo $detailalat->pekerjaan; ?></th>
                    <th style="text-align: center; width:10%; font-weight: normal; padding-left:5px"><?php echo $luarkota; ?></th>
                    <th style="text-align: center; width:10%; font-weight: normal; padding-left:20px"><?php echo $harilibur; ?></th>
                </tr>
            <?php endforeach ?>
            </tbody>
            <br>
        </table>
<hr>
<div class="header">
    <div class="left">
        <table width="50%" style="  font-size: 11pt" border="0">
            <tr>
                <td style="width: 50px">Total Hari Kerja Dlm Kota</td>
                <td style="width: 10px"></td>
                <td>{{ $header->total_dalamkota }} Hari&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 250px">Total Hari Kerja Luar Kota</td>
                <td style="width: 10px"></td>
                <td>{{ $header->total_luarkota }} Hari&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 250px">Total Kerja Hari Libur</td>
                <td style="width: 10px"></td>
                <td>{{ $header->total_libur }} Hari&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
    <div class="right">
        <table width="50%" style="font-size: 11pt; margin-right: -5px;" border="0">
            <tr>
                <td style="width: 270px;">Total Premi Dlm Kota &nbsp;(Harian)</td>
                <td style="width: 5px; padding-right: 50px;"></td>
                <td style="text-align: right; padding-left: 30px;">{{ number_format($header->total_premi_dalamkota,'2') }}&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 270px">Total Premi Luar Kota (Harian)</td>
                <td style="width: 10px"></td>
                <td style="text-align: right">{{ number_format($header->total_premi_luarkota,'2') }}&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 150px"> Total Premi Hari Libur &nbsp;</td>
                <td style="width: 40px"></td>
                <td style="text-align: right">{{ number_format($header->total_premi_libur,'2') }}&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
<br><br><br><br><br>

<?php
    $ttd1 = Signature::find('006'); //dhanu
    $ttd2 = Signature::find('007'); //marchendro
    $ttd3 = Signature::find('010'); //vita
    $ttd4 = Signature::find('001'); //susanto wijaya
?>
    <div class="footer" style="font-size: 10pt;padding-top: 2cm; padding-left: 12px">
        <div class="tgl">
            Palembang, <?php echo date_format($date,'d F Y');?>
        </div>
        <br>
        <table width="100%" style="font-size:10pt; text-align:center;padding:0px; margin:0px; border-collapse:collapse" border="0">
            <tr style="padding:0px; margin:0px">
                <td width="20%">Dibuat oleh,</td>
                <td width="20%">Diperiksa oleh,</td>
                <td width="20%">Diperiksa Oleh,</td>
                <td width="20%">Disetujui Oleh,</td>
                <td width="40%">Diterima,</td>
            </tr>
            <tr style="padding:0px; margin:0px"><td colspan="4"><br><br><br></td></tr>
            <tr style="padding:0px; margin:0px">
                <td><?php echo $user; ?></td>
                <td><?php echo $ttd2->mengetahui; ?></td>
                <td><?php echo $ttd3->mengetahui; ?></td>
                <td><?php echo $ttd4->mengetahui ?></td>
                <td>{{ $header->helper->nama_helper}}</td>
            </tr>
            <tr style="padding:0px; margin:0px">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    </section>
</div>

</body>
</html>