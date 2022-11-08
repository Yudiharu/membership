<?php 

use\App\Models\Alat;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <br>
    <title>JOB REQUEST ~ {{ $request }}</title>
    <style>
        @page {
            border: solid 1px #0b93d5;
            width: 24.13cm;
            height: 27.94cm;
            font-family: 'Courier';
            font-weight: bold;
            margin-right: 2cm;
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
            padding-top: 155px
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
            padding-top:3mm;
            padding-bottom:3mm;
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
<?php
    if ($joborder->type_jo == '1') {
        $type = 'Bongkar Muat Curah';
    }else if ($joborder->type_jo == '2') {
        $type = 'Bongkar Muat Non Curah';
    }else if ($joborder->type_jo == '3') {
        $type = 'Rental Alat';
    }else if ($joborder->type_jo == '4') {
        $type = 'Trucking';
    }else if ($joborder->type_jo == '5') {
        $type = 'Lain-lain';
    }

    if ($joborder->type_cargo == '1') {
        $cargo = 'Batu Bara';
    }else if ($joborder->type_cargo == '2') {
        $cargo = 'Batu Splite';
    }else if ($joborder->type_cargo == '3') {
        $cargo = 'Kayu';
    }else if ($joborder->type_cargo == '4') {
        $cargo = 'Bongkar Muat';
    }else if ($joborder->type_cargo == '5') {
        $cargo = 'Crane dan Alat';
    }else if ($joborder->type_cargo == '6') {
        $cargo = 'Lain-lain';
    }else if ($joborder->type_cargo == '7') {
        $cargo = 'Trucking';
    }

    switch($joborder->type_kegiatan)
    {
        case 1:
        {
            $type_kegiatan = "Non Transhipment";
            break;
        }
        case 2:
        {
            $type_kegiatan = "Transhipment";
            break;
        }
    }
    
?>

<div class="left">
    <p id="color" style="font-size: 8pt;" align="left"><b><?php echo ($nama2) ?></b></p>
</div>
<div class="right">
    <p id="color" style="font-size: 8pt;" align="left"><b>Waktu Cetak : </b><?php echo ($dt) ?></p>
</div>
<div class="title">
    <h1>JOB REQUEST</h1>
</div>
<div class="header">
    <div class="left">
        <table width="50%" style="  font-size: 10pt" border="0">
            <tr >
                <td style="width: 180px">No. Job Order</td>
                <td style="width: 10px">:</td>
                <td>{{ $request }}&nbsp;&nbsp;&nbsp;{{ $type }}</td>
            </tr>
            <tr>
                <td>Tgl Job Order</td>
                <td>:</td>
                <td>{{ $tgl }}</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>:</td>
                <td>{{ $joborder->customer1->nama_customer }}</td>
            </tr>
            <tr>
                <td>Remark</td>
                <td>:</td>
                <td>{{ $joborder->kode_consignee }}</td>
            </tr>
            <?php if ($joborder->kode_kapal != null) { ?>
                <tr>
                    <td>Kapal</td>
                    <td>:</td>
                    <td>{{ $joborder->kapal->nama_kapal }}</td>
                </tr>
            <?php }else { ?>
                <tr>
                    <td>Kapal</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
            <?php } ?>
            <?php if ($joborder->tongkang != null) { ?>
                <tr>
                    <td>Tongkang</td>
                    <td>:</td>
                    <td>{{ $joborder->tongkangs->nama_kapal }}</td>
                </tr>
            <?php }else { ?>
                <tr>
                    <td>Tongkang</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
            <?php } ?>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $joborder->periode }}</td>
            </tr>
        </table>
    </div>
    <div class="right">
        <table width="40%" style="font-size: 10pt" border="0">
            <tr >
                <td style="width: 120px">No. Reff</td>
                <td style="width: 10px">:</td>
                <td>{{ $joborder->no_reff }}</td>
            </tr>
            <tr>
                <td>Tgl Reff</td>
                <td>:</td>
                <td>{{ $joborder->tgl_reff }}</td>
            </tr>
            <tr>
                <td>Order By</td>
                <td>:</td>
                <td>{{ $joborder->order_by }}</td>
            </tr>
            <tr>
                <td>Type Cargo</td>
                <td>:</td>
                <td>{{ $cargo }}</td>
            </tr>
            <tr>
                <td>Type Kegiatan</td>
                <td>:</td>
                <td>{{ $type_kegiatan }}</td>
            </tr>
            <tr>
                <td>Lokasi Kegiatan</td>
                <td>:</td>
                <td>{{ $joborder->lokasi }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="content">
<hr>
    <section class="list-item">
        <table class="grid" style="font-size: 10pt; width: 21cm;" border="0" >
            <thead>
            <tr >
                <th width="25%" style="text-align: left;">No Aset Alat</th>
                <th width="25%" style="text-align: center;">Hour</th>
                <th width="25%" style="text-align: center;">Unit Rate</th>
                <th width="25%" style="text-align: right;">Total Harga</th>
            </tr>
            </thead>
        </table>
<hr>
        <table class="grid" style="font-size: 9pt; width: 21cm;" border="0" >
            <tbody>
            <?php $subtotal = 0 ; $limit_row = 0?>
            <?php foreach ($jobrequest as $key => $row): ?>
                <tr>
                    <?php 
                    $alat = Alat::where('kode_alat',$row->kode_alat)->first();
                    $total_harga =  $row->harga * $row->qty;
                    ?>

                    <td width="25%" style="text-align: left;word-spacing: -5px;"><?php echo $alat->no_asset_alat; ?></td>
                    <td width="25%" style="text-align: center;"><?php echo $row->qty; ?></td>
                    <td width="25%" style="text-align: center;"><?php echo number_format($row->harga,'2'); ?></td>
                    <td width="25%" style="text-align: right;"><?php echo number_format($total_harga,'2'); ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
<hr>
<?php
    $dibuat = $request.'-dibuat'.'.png';
    $cekdibuat = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/'.$dibuat;
?>
    <table width="100%" style="font-size:10pt; border-collapse:collapse" border="0">
        <tr>
            <td>Palembang, <?php echo date_format($date,'d F Y');?></td>
        </tr>
        <tr style="padding:0px; margin:0px">
        <?php if (file_exists($cekdibuat)) { ?>
            <td><img src="{{ $cekdibuat }}" alt="" height="70px" width="90px" align="center"></td>
        <?php }else { ?>
            <td><br><br><br></td>
        <?php } ?>
        </tr>
        <tr>
            <td>&nbsp;<?php echo $user; ?></td>
        </tr>
    </table>
    </section>
</div>

</body>
</html>