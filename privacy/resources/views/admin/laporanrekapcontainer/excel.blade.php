<!DOCTYPE html>
<html lang="en">
<head>
	<style> 
        
     @page {
            border: solid 1px #0b93d5;

        }

        .title {
            margin-top: 0.5cm;
        }
        .title h1 {
            text-align: left;
            font-size: 14pt;
            
        }
        

        .header {
            margin-left: 50px;
            margin-right: 0px;
            /*font-size: 10pt;*/
            padding-top: 10px;
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
            margin-left: 10px;
            padding-top: 10px;
        }
        .catatan {
            font-size: 10pt;
        }

        footer {
                position: fixed; 
                top: 19cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }

        /* Table desain*/
        table.grid {
            width: 100%;
        }
</style>
</head>
<body>

	<table class="table_content" style="margin-bottom: 25px;width: 100%;">
        <thead>
        <tr class="border" style="background-color: #e6f2ff">
        <?php if ($cekdetail != 'null') { ?>
            <th>NO</th>
            <th>Tgl Balik SPB</th>
            <th>No SPB</th>
            <th>Container</th>
            <th>Gudang</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>Uang Jalan</th>
            <th>B/P/A</th>
            <th>Honor</th>
            <th>Biaya Lain</th>
            <th>Trucking</th>
            <th>Pemilik Mobil</th>
        <?php }else { ?>
            <th>NO</th>
            <th>Tgl Balik SPB</th>
            <th>No SPB</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>HBU</th>
            <th>Uang Jalan</th>
            <th>BBM</th>
            <th>Dari</th>
            <th>Tujuan</th>
            <th>Pemilik Mobil</th>
        <?php } ?>
        </tr>
        </thead>
        
        <tbody>
            <?php if ($cekdetail != 'null') { ?>
                <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->tgl_kembali ?></td>
                    <td class="border" align="left"><?php echo $row->no_spb ?></td>
                    <td class="border" align="left"><?php echo $row->kode_container ?></td>
                    <td class="border" align="left"><?php echo $row->gudangdetail->nama_gudang ?></td>
                    <?php if ($row->kode_mobil == null) { ?>
                        <td class="border" align="left"><?php echo $row->kode_mobil?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                    <?php } ?>
                    <?php if (is_numeric($row->kode_sopir) != 1) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->sopir->nama_sopir?></td>
                    <?php } ?>
                    <td class="border" align="center"><?php echo $row->uang_jalan ?></td>
                    <td class="border" align="center"><?php echo $row->bpa ?></td>
                    <td class="border" align="right"><?php echo $row->honor ?></td>
                    <td class="border" align="right"><?php echo $row->biaya_lain ?></td>
                    <td class="border" align="right"><?php echo $row->trucking ?></td>
                    <td class="border" align="left"><?php echo $row->nama_vendor ?></td>
                </tr>
                <?php endforeach; ?>
            <?php } else { ?>
                <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_kembali ?></td>
                    <td class="border" align="left"><?php echo $row->no_spb ?></td>
                    <?php if ($row->kode_mobil == null) { ?>
                        <td class="border" align="left"><?php echo $row->kode_mobil?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                    <?php } ?>
                    <?php if (is_numeric($row->kode_sopir) != 1) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->sopir->nama_sopir?></td>
                    <?php } ?>
                    <td class="border" align="center"><?php echo $row->tarif_gajisopir ?></td>
                    <td class="border" align="center"><?php echo $row->uang_jalan ?></td>
                    <td class="border" align="right"><?php echo $row->bbm ?></td>
                    <td class="border" align="left"><?php echo $row->dari ?></td>
                    <td class="border" align="left"><?php echo $row->tujuan ?></td>
                    <td class="border" align="left"><?php echo $row->nama_vendor ?></td>
                </tr>
                <?php endforeach; ?>
            <?php } ?>
        </tbody>
    </table>
<hr>
</body>
</html>