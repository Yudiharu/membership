<!DOCTYPE html>
<html lang="en">
<?php
use App\Models\Operator;
use App\Models\Helper;
use App\Models\Joborder;
use App\Models\PemakaianAlat;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <br>
    <title>PSA ~ {{ $request }}</title>
    <style>
        @page {
            border: solid 1px #0b93d5;
            width: 24.13cm;
            height: 27.94cm;
            font-family: sans-serif;
            font-weight: normal;
            margin-right: 0.5cm;
            margin-left: 0.5cm;
        }

        .title {
            margin-top: 1cm;
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
            padding-top: 40px;
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
            padding-top:0.1 mm;
            padding-bottom:0.1mm;
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
    <h1>Report Pemakaian Alat</h1>
</div>
<div class="header">
    <div class="left">
        <table width="50%" style="  font-size: 10pt" border="0">
            <tr >
                <td style="width: 180px">No. Pemakaian</td>
                <td style="width: 10px">:</td>
                <td>{{ $request }}</td>
            </tr>
            <tr >
                <td style="width: 180px">Job Order</td>
                <td style="width: 10px">:</td>
                <td>{{ $nojo }}</td>
            </tr>
        </table>
    </div>
    <!-- <div class="right">
        <table width="30%" style="font-size: 10pt" border="0">
            <tr >
                <td style="width: 120px">No. Reff</td>
                <td style="width: 10px">:</td>
                <td>{{ $joborder->no_reff }}</td>
            </tr>
        </table>
    </div> -->
</div>
<div class="content">
<hr>
    <section class="list-item">
        <table class="grid" style="font-size: 9pt; width: 1000px;" border="0" >
            <thead>
            <tr >
                <th style="text-align: left; width: 80px;">Operator</th>
                <th style="width: 60px; text-align: left;">Helper1</th>
                <th style="width: 60px; text-align: left;">Helper2</th>
                <th style="width: 140px; text-align: left;">Pekerjaan</th>
                <th style="width: 70px; text-align: left;">Tgl Pakai</th>
                <th style="width: 50px; text-align: left;">TimeSheet</th>
                <th style="width: 50px; text-align: center;">Libur?</th>
                <th style="width: 50px;">L.Kota?</th>
                <th colspan="2" style="width: 120px;">Jam</th>
                <th style="width: 50px;">Istirahat</th>
                <th style="width: 50px;">StandBy</th>
                <th colspan="2" style="text-align: center; width: 120px;">HourMeter</th>
                <th style="width: 50px; text-align: right;">Tot.HM</th>
                <th style="width: 50px; text-align: right;">Tot.Jam</th>
            </tr>
            </thead>
        </table>
<hr>
<?php
$nama1 = null;
$nama2 = null;
?>
        <table class="grid" style="font-size: 8pt; width: 1000px;" border="0" >
            <thead>
            <?php $subtotal = 0 ; $limit_row = 0?>
            <?php foreach ($jobrequest as $key => $row): ?>
                <?php
                    $opera = Operator::find($row->operator);
                    $help1 = Helper::find($row->helper1);
                    $help2 = Helper::find($row->helper2);
                    if ($help1 == null){
                        $helpo1 = '-';
                    }else {
                        $helpo1 = $help1->nama_helper;
                    }

                    if ($help2 == null){
                        $helpo2 = '-';
                    }else {
                        $helpo2 = $help2->nama_helper;
                    }

                    if ($row->hari_libur == 1){
                        $liburs = 'Y';
                    }else {
                        $liburs = 'T';
                    }

                    $alat = PemakaianAlat::find($row->no_pemakaian);
                    $jo = Joborder::find($alat->no_joborder);

                    if ($jo->status_lokasi == '1'){
                        $luar = 'T';
                    }else {
                        $luar = 'Y';
                    }

                    if ($row->hitungan_pemakaian == '1'){
                        $jamdr = substr($row->jam_dr,0,5);
                        $jamsp = substr($row->jam_sp,0,5);
                        $totjam = $row->total_jam;

                        if ($row->istirahat != null || $row->istirahat != 0){
                            $rest = substr($row->istirahat,0,5);
                        }else {
                            $rest = '00:00';
                        }

                        if ($row->stand_by != null || $row->stand_by != 0){
                            $stand = substr($row->stand_by,0,5);
                        }else {
                            $stand = '00:00';
                        }

                        $hmdr = 0;
                        $hmsp = 0;
                        $tothm = 0;
                    }else {
                        $jamdr = '00:00';
                        $jamsp = '00:00';
                        $totjam = '00:00';
                        $rest = '00:00';
                        $stand = '00:00';
                        $hmdr = $row->hm_dr;
                        $hmsp = $row->hm_sp;
                        $tothm = $row->total_hm;
                    }
                ?>
                <tr>
                    <th style="text-align: left; font-weight: normal; width: 80px; word-spacing: -2px;"><?php echo $opera->nama_operator; ?></th>
                    <th style="text-align: left; font-weight: normal; width: 60px; word-spacing: -2px;"><?php echo $helpo1; ?></th>
                    <th style="text-align: left; font-weight: normal; width: 60px; word-spacing: -2px;"><?php echo $helpo2; ?></th>
                    <th style="font-weight: normal; width: 140px; text-align: left; word-spacing: -1px;"><?php echo $row->pekerjaan; ?></th>
                    <th style="font-weight: normal; width: 70px; text-align: left;"><?php echo $row->tgl_pakai; ?></th>
                    <th style="font-weight: normal; width: 50px; text-align: left;"><?php echo $row->no_timesheet; ?></th>
                    <th style="font-weight: normal; width: 50px; text-align: center; padding-left: 10px;"><?php echo $liburs; ?></th>
                    <th style="font-weight: normal; width: 50px;"><?php echo $luar; ?></th>
                    <th style="font-weight: normal; width: 60px;"><?php echo $jamdr; ?></th>
                    <th style="font-weight: normal; width: 60px; padding-left: -5px;"><?php echo $jamsp; ?></th>
                    <th style="font-weight: normal; width: 50px; padding-left: 5px;"><?php echo $rest; ?></th>
                    <th style="font-weight: normal; width: 50px;"><?php echo $stand; ?></th>
                    <th style="font-weight: normal; text-align: left; width: 60px; padding-left: 15px;"><?php echo number_format($hmdr,'2'); ?></th>
                    <th style="font-weight: normal; text-align: left; width: 60px; padding-left: -5px;"><?php echo number_format($hmsp,'2'); ?></th>
                    <th style="font-weight: normal; text-align: left; width: 50px;"><?php echo number_format($tothm,'2'); ?></th>
                    <th style="font-weight: normal; text-align: left; width: 50px;"><?php echo $totjam; ?></th>
                </tr>
            <?php endforeach ?>
            </thead>
            <br>
        </table>
<hr>
    <table width="100%" style="font-size:10pt; border-collapse:collapse" border="0">
        <tr>
            <td>Palembang, <?php echo date_format($date,'d F Y');?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;<?php echo $user; ?></td>
        </tr>
    </table>
    </section>
</div>

</body>
</html>