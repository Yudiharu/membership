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
        <?php if ($type == 'Container') { ?>
            <th>No</th>
            <th>Tgl. Kembali SPB</th>
            <th>No. SPB</th>
            <th>No. JO</th>
            <th>Shipper</th>
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
            <th>No</th>
            <th>Tgl. Kembali SPB</th>
            <th>No. SPB</th>
            <th>No. JO</th>
            <th>Shipper</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>HBU Sopir</th>
            <th>Uang Jalan</th>
            <th>Pemilik Mobil</th>
        <?php } ?>
        </tr>
        </thead>
        
        <tbody>
            <?php if ($type == 'Container') { ?>
                <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->tgl_kembali ?></td>
                    <td class="border" align="left"><?php echo $row->no_spb ?></td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <?php 
                        if (stripos($row->customer->nama_customer, '&') !== FALSE ) { 
                            $ket = stripos($row->customer->nama_customer, '&');
                            $keterangan = substr_replace($row->customer->nama_customer, '&amp;', $ket, 1);
                        } else {
                            $keterangan = $row->customer->nama_customer;
                        }
                    ?>
                    <td class="border" align="left"><?php echo $keterangan ?></td>
                    <td class="border" align="left"><?php echo $row->kode_container ?></td>
                    <?php 
                        if (stripos($row->gudangdetail->nama_gudang, '&') !== FALSE ) { 
                            $ket = stripos($row->gudangdetail->nama_gudang, '&');
                            $keterangan2 = substr_replace($row->gudangdetail->nama_gudang, '&amp;', $ket, 1);
                        } else {
                            $keterangan2 = $row->gudangdetail->nama_gudang;
                        }
                    ?>
                    <td class="border" align="center"><?php echo $keterangan2 ?></td>
                    <td class="border" align="center"><?php echo $row->mobil->nopol ?></td>
                    <?php if (is_numeric($row->kode_sopir) != 1) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <?php 
                            if (stripos($row->sopir->nama_sopir, '&') !== FALSE ) { 
                                $ket = stripos($row->sopir->nama_sopir, '&');
                                $keterangan3 = substr_replace($row->sopir->nama_sopir, '&amp;', $ket, 1);
                            } else {
                                $keterangan3 = $row->sopir->nama_sopir;
                            }
                        ?>
                        <td class="border" align="left"><?php echo $keterangan3?></td>
                    <?php } ?>
                    <td class="border" align="center"><?php echo $row->uang_jalan ?></td>
                    <td class="border" align="center"><?php echo $row->bpa ?></td>
                    <td class="border" align="center"><?php echo $row->honor ?></td>
                    <td class="border" align="center"><?php echo $row->biaya_lain ?></td>
                    <td class="border" align="center"><?php echo $row->trucking ?></td>
                    <td class="border" align="center"><?php echo $row->nama_vendor ?></td>
                </tr>
                <?php endforeach; ?>
            <?php } else { ?>
                <?php foreach ($data2 as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_kembali ?></td>
                    <td class="border" align="left"><?php echo $row->no_spb ?></td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <?php 
                        if (stripos($row->customer->nama_customer, '&') !== FALSE ) { 
                            $ket = stripos($row->customer->nama_customer, '&');
                            $keterangan = substr_replace($row->customer->nama_customer, '&amp;', $ket, 1);
                        } else {
                            $keterangan = $row->customer->nama_customer;
                        }
                    ?>
                    <td class="border" align="left"><?php echo $keterangan ?></td>
                    <td class="border" align="center"><?php echo $row->mobil->nopol ?></td>
                    <?php if (is_numeric($row->kode_sopir) != 1) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <?php 
                            if (stripos($row->sopir->nama_sopir, '&') !== FALSE ) { 
                                $ket = stripos($row->sopir->nama_sopir, '&');
                                $keterangan3 = substr_replace($row->sopir->nama_sopir, '&amp;', $ket, 1);
                            } else {
                                $keterangan3 = $row->sopir->nama_sopir;
                            }
                        ?>
                        <td class="border" align="left"><?php echo $keterangan3?></td>
                    <?php } ?>
                    <td class="border" align="center"><?php echo number_format($row->tarif_gajisopir) ?></td>
                    <td class="border" align="center"><?php echo number_format($row->uang_jalan) ?></td>
                    <td class="border" align="center"><?php echo $row->nama_vendor ?></td>
                </tr>
                <?php endforeach; ?>
            <?php } ?>
        </tbody>
    </table>
<hr>
</body>
</html>