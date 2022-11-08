<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>REKAP TENAGA KERJA</title>

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
            <p id="color" style="font-size: 8pt;" align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REKAP TENAGA KERJA NON PEGAWAI</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>

        <div id="header">
            <p class="page" style="float: right; font-size: 9pt;"><b>Date :</b> <?php echo date_format($dt,"d/m/Y") ?>&nbsp;&nbsp;&nbsp;
            <b>Time :</b> <?php echo date_format($dt,"H:i:s") ?>&nbsp;&nbsp;&nbsp;
            <b>Page :</b> </p>
        </div>
        
        <br><br>
    </div>


    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>NIB</th>
            <th>Nama</th>
            <th>Tgl Masuk</th>
            <th>Lokasi Kerja</th>
            <th>Jabatan</th>
            <th>Gender</th>
            <th>Tempat</th>
            <th>Tgl Lahir</th>
            <th>Umur</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Status Kerja</th>
            <th>No.KTP</th>
            <th>No.NPWP</th>
            <th>No.KK</th>
            <th>Gol Darah</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($member as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $row->nik ?></td>
                    <td class="border" align="left"><?php echo $row->nama ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_masuk; ?></td>
                    <td class="border" align="left"><?php echo $row->lokasi_kerja ?></td>
                    <td class="border" align="left"><?php echo $row->jabatan ?></td>
                    <td class="border" align="left"><?php echo $row->gender ?></td>
                    <td class="border" align="left"><?php echo $row->tempat ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_lahir ?></td>
                    <td class="border" align="left"><?php echo $row->umur ?></td>
                    <td class="border" align="left"><?php echo $row->alamat ?></td>
                    <td class="border" align="left"><?php echo $row->agama ?></td>
                    <td class="border" align="left"><?php echo $row->status_kerja ?></td>
                    <td class="border" align="left"><?php echo $row->no_ktp ?></td>
                    <td class="border" align="left"><?php echo $row->no_npwp ?></td>
                    <td class="border" align="left"><?php echo $row->no_kk ?></td>
                    <td class="border" align="left"><?php echo $row->gol_darah ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        
        <tfoot>
        
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
                    <td></td>
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
                    <td></td>
                </tr>
            </table>
    <?php } ?>
</body>
</html>