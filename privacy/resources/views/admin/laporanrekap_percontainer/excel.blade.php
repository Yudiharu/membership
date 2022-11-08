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
            <th>No</th>
            <th>No. SPB</th>
            <th>Tgl. SPB</th>
            <th>No. JO</th>
            <th>No. Trucking</th>
            <th>No. Container</th>
            <th>No. Seal</th>
            <th>Size Type</th>
            <th>Status Muatan</th>
            <th>Mobil</th>
            <th>Sopir</th>
            <th>Tujuan</th>
        </tr>
        </thead>
        
        <tbody>
                <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border"><?php echo $key+1 ?></td>
                    <td class="border" align="left"><?php echo $row->no_spb ?></td>
                    <td class="border" align="left"><?php echo $row->tgl_spb ?></td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <td class="border" align="left"><?php echo $row->no_trucking ?></td>
                    <td class="border" align="left"><?php echo $row->kode_container ?></td>
                    <td class="border" align="left"><?php echo $row->no_seal ?></td>
                    <td class="border" align="left"><?php echo $row->sizecontainer->nama_size ?></td>
                    <td class="border" align="left"><?php echo $row->status_muatan ?></td>

                    <?php if ($row->kode_mobil == null) { ?>
                        <td class="border" align="left"><?php echo $row->kode_mobil ?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->mobil->nopol ?></td>
                    <?php } ?>

                    <?php if (substr($row->kode_sopir, 2) == 0) { ?>
                        <td class="border" align="left"><?php echo $row->kode_sopir?></td>
                    <?php }else { ?>
                        <td class="border" align="left"><?php echo $row->sopir->nama_sopir?></td>
                    <?php } ?>

                    <td class="border" align="left"><?php echo $row->tujuan ?></td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
<hr>
</body>
</html>