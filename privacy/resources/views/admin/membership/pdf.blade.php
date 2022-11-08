<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <br>
<?php use Illuminate\Support\Facades\Storage; ?>
    <title>DATA TENAGA KERJA</title>
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
            padding-top: 400px;
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

<div class="right">
    <p id="color" style="font-size: 8pt;" align="left"><b>Waktu Cetak : </b><?php echo ($dt) ?></p>
</div>
<div class="title">
    <h1>DATA TENAGA KERJA</h1>
</div>
<div class="header">
    <div class="left">
        <table width="60%" style="font-size: 10pt;" border="0">
            <tr >
                <td style="width: 180px">NIB</td>
                <td style="width: 10px">:</td>
                <td>{{ $member->nik }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $member->nama }}</td>
            </tr>
            <tr>
                <td>Tanggal Masuk</td>
                <td>:</td>
                <td>{{ $member->tanggal_masuk }}</td>
            </tr>
            <tr>
                <td>Lokasi Kerja</td>
                <td>:</td>
                <td>{{ $member->lokasi_kerja }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $member->jabatan }}</td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>:</td>
                <td>{{ $member->gender }}</td>
            </tr>
            <tr>
                <td>Tempat / Tgl Lahir</td>
                <td>:</td>
                <td>{{ $member->tempat." / ".$member->tanggal_lahir }}</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>:</td>
                <td>{{ $member->umur }} Tahun</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $member->alamat }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $member->agama }}</td>
            </tr>
            <tr>
                <td>Gol.Darah</td>
                <td>:</td>
                <td>{{ $member->gol_darah }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ $member->status_kerja }}</td>
            </tr>
        </table>
    </div>
<?php
    $ktp = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.$request.'/'.'KTP-'.$request.'.jpg';
    $npwp = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.$request.'/'.'NPWP-'.$request.'.jpg';
    $kk = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.$request.'/'.'KK-'.$request.'.jpg';
?>
    <div class="right">
        <table width="40%" border="0">
            <tr style="padding:0px; margin:0px">
            <?php if (file_exists($ktp)) { ?>
                <td><img src="{{ $ktp }}" alt="" height="170px" width="300px" align="center"></td>
            <?php }else { ?>
                <td><br><br><br></td>
            <?php } ?>
            </tr>
            <tr style="padding:0px; margin:0px">
            <?php if (file_exists($npwp)) { ?>
                <td><br><img src="{{ $npwp }}" alt="" height="170px" width="300px" align="center"></td>
            <?php }else { ?>
                <td><br><br><br></td>
            <?php } ?>
            </tr>
        </table>
    </div>
    <div class="content">
        <table width="100%" border="0">
            <tr style="padding:0px; margin:0px">
                <?php if (file_exists($kk)) { ?>
                    <td><img src="{{ $kk }}" alt="" height="420px" width="800px" align="center"></td>
                <?php }else { ?>
                    <td><br><br><br></td>
                <?php } ?>
            </tr>
        </table>
    </div>
</div>
</body>
</html>