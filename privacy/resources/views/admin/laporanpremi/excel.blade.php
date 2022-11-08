<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>LAPORAN REKAP PREMI</title>
</head>
<body>

<div class="header">
    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>NIK</th>
            <th>No.Rek</th>
            <th>Operator/Helper</th>
            <th>Premi</th>
            <th>Type</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border" align="left"><?php echo $row->nik ?></td>
                    <td class="border" align="left"><?php echo "'".$row->no_rekening ?></td>
                    <td class="border" align="left"><?php echo $row->nama ?></td>
                    <td class="border" align="left"><?php echo $row->total ?></td>
                    <td class="border" align="left"><?php echo $row->type ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>