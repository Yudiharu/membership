<!DOCTYPE html>
<html lang="en">
    {!! csrf_field() !!}
<head>
    <?php 
        foreach ($truckingdetail as $rowdata) {
            $no_spb = $rowdata->no_spb;
            $gudang = $rowdata->gudangdetail->nama_gudang;
            $container = $rowdata->kode_container;
            $kode_size = $rowdata->sizecontainer->nama_size;

            $data[] = array(
                'no_spb'=>$no_spb,
                'gudang'=>$gudang,
                'container'=>$container,
                'kode_size'=>$kode_size,
            );
        }

        $count = count($truckingdetail);
        $i = 0;
    ?>
    
    <?php for ($i = 0; $i < $count; $i++) { ?>
    <meta charset="UTF-8">
    <title>Cetak SPB dengan No JO - {{ $get_jo }}</title>
    <style>
        @page {
            border: solid 1px #0b93d5;
            font-family: ArialRoundedMTBold, "Arial Rounded MT Bold", Arial, Helvetica, sans-serif;
            /*font-weight: bold;*/
            margin-right: 2cm;
        }

        .title {
            margin-top: 1.2cm;
        }
        .title h1 {
            text-align: center;
            font-size: 13pt;

        }
        }

        .header {
            margin-left: 0px;
            margin-right: 0px;
            /*font-size: 10pt;*/
            padding-top: 30px;
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
            padding-top: 50px
        }
        .catatan {
            font-size: 10pt;
        }

        /* Table desain*/
        table.grid {
            border-collapse: collapse;
            width: 90%;
            border-bottom: 0.2px solid #000000;
            border-style: dashed;
        }
        table.grid1 {
            border-collapse: collapse;
            width: 90%;
            border-bottom: 0.2px solid #000000;
            border-top: 0.2px solid #000000;
            border-style: dashed;
        }
        table.grid th{
            background: #FFF;
            text-align:left;
            padding-top:3mm;
            padding-bottom:3mm;
        }
        .list-item {
            height: 2.1in;
            margin: 0px;
        }

        .page_break { page-break-after: always; }

    </style>

</head>
<body>

<div class="header">
    <div class="left">
        <table width="71%" style="  font-size: 11pt" border="0">
            <tr >
                <td style="width: 100px">{{ $nama2 }}</td>
            </tr>
            <tr>
                <td>JL. SLAMET RIADY LR. LAWANG KIDUL</td>
            </tr>
            <tr>
                <td>NO. 1977 RT. 022, PALEMBANG - 30114</td>
            </tr>
        </table>
    </div>


    <div class="right">
        <table width="41.5%" style="font-size: 11pt; text-align:right; padding-right:11mm;" border="0">
            <tr>
                <td style="width: 60px">No.</td>
                <td style="text-align:left">{{ $data[$i]['no_spb'] }}</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td style="text-align:left">{{ $data[$i]['gudang'] }}</td>
            </tr>
        </table>
    </div>
</div>

<br><br><br>

<div class="title">
    <h1>Surat Pengantar Barang</h1>
</div>

    <div class="left">
        <table width="71%" style="font-size: 11pt" border="0">
            <tr>
                <td style="width: 90px">Nama Kapal</td>
                <td>{{ $trucking->kapal->nama_kapal }}</td>
            </tr>
            <tr>
                <td>Voyage</td>
                <td>{{ $trucking->voyage }}</td>
            </tr>
        </table>
    </div>

    <div class="right">
        <table width="30.5%" style="font-size: 11pt" border="0">
            <tr>
                <td style="width: 50px">No. JO</td>
                <td>{{ $trucking->no_joborder }}</td>
            </tr>
            <tr>
                <td>Truck</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <table class="grid1" style="font-size: 11pt; width: 19cm;" border="0" >
            <tr >
                <th width="35%" height="10%">No. Container</th>
                <th width="25%" height="10%">Jumlah Colie</th>
                <th width="19.7%" height="10%">Keterangan</th>
            </tr>
        </table>
        <table class="grid" style="font-size: 16pt; width: 19cm;" border="0" >
            <tr>
                <td height="10%" style="font-family: Arial;">{{ $data[$i]['container'] }}<br>({{ $data[$i]['kode_size'] }})</td>
                <td height="10%"></td>
                <td height="10%"></td>
            </tr>
        </table>
    </div>

    <div class="right">
        <table width="30.1%" style="font-size: 11pt" border="0">
            <tr>
                <td style="width: 180px">Palembang,</td>
            </tr>
        </table>
    </div>
<br>
<div style="font-size: 12pt;padding-top: 1.2cm">
    <table width="97%" style="font-size:11pt; text-align:center; border-collapse:collapse; margin: -20px" border="0">
        <tr>
            <td width="25%">Penerima Gudang,</td>
            <td width="25%">Petugas EMKL,</td>
            <td width="25%">Sopir,</td>
            <td width="25%">Pengirim,</td>
        </tr>
        <tr><td colspan="3"><br><br></td></tr>
        <tr>
            <td>(_____________)</td>
            <td>(_____________)</td>
            <td>(_____________)</td>
            <td>(  Ahok  )</td>
        </tr>
    </table>
</div>
<br><br>

<p style="font-size: 11pt">Putih : Sopir&nbsp;&nbsp;&nbsp; Pink : Arsip&nbsp;&nbsp;&nbsp; Kuning : Pos Keluar&nbsp;&nbsp;&nbsp; Hijau : Gudang&nbsp;&nbsp;&nbsp; Biru : Lain-lain</p>
<p style="font-size: 11pt">Jam Masuk Gudang : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jam Keluar Gudang :</p>
<p style="font-size: 11pt">NB : Laporan Kerusakan/Claim Diterima Paling Lambat 1x24 Jam Setelah Barang Diterima</p>

    <?php if ($i != $leng-1) { ?>      
        <div class="page_break"></div>
    <?php }?>
<?php } ?>

</body>
</html>