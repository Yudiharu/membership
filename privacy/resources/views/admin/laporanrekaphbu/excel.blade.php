<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php
    use App\Models\HasilbagiDetail;
    use App\Models\Sopir;
?>
<head>
    <meta charset="utf-8" />
        <title>LAPORAN REKAP HBU</title>
</head>
<body>

<div class="header">
    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 100%;">NIS</th>
            <th style="width: 100%;">No Order/HBU</th>
            <th style="width: 100%;">No IAP</th>
            <th style="width: 100%;">Nama</th>
            <th style="width: 100%;">No Rek</th>
            <th style="width: 100%;">Hasil Bersih(12.5%)</th>
            <th style="width: 100%;">Tabungan(10%)</th>
            <th style="width: 100%;">Honor Kenek</th>
            <th style="width: 100%;">Total</th>
            <th style="width: 100%;">GT UJ-BBM</th>
            <th style="width: 100%;">Objek PPH 21</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($hasilbagi as $key => $row) : ?>
                <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $row->nis ?></td>
                    <td><?php echo $row->no_hasilbagi ?></td>
                    <td><?php echo $row->no_invoice ?></td>
                <?php $sopir = Sopir::find($row->kode_sopir); ?>
                    <td><?php echo $sopir->nama_sopir ?></td>
                    <td><?php echo $sopir->no_rekening ?></td>
                    <td><?php echo $row->nilai_gaji ?></td>
                    <td><?php echo $row->nilai_tabungan ?></td>
                    <td><?php echo $row->honor_kenek ?></td>
                    <td><?php echo $row->gt_hbu ?></td>
                <?php $sisaujbbm = HasilbagiDetail::where('no_hasilbagi', $row->no_hasilbagi)->sum('sisa_ujbbm'); ?>
                    <td><?php echo $sisaujbbm ?></td>
                    <td><?php echo $row->gt_hbu + $sisaujbbm ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>