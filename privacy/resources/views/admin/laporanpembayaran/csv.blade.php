<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8" />
        <title>LAPORAN REKAP JOB ORDER</title>

    <style>
        body {
            font-family: sans-serif;
            /*font-family: courier;*/
            /*font-weight: bold;*/
        }
        .header {
            text-align: center;
        },
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
            text-transform: uppercase;
            padding: 7px;
            text-align: center;

        }

        .left {
            float: left;
        }

        .right {
            float: right;
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
          padding: 5px;
        }

        table.grid1 tr:nth-child(even) {
          background-color: #dddddd;
        }
    </style>
</head>
<body>

<div class="header">
    <table class="grid1" style="margin-bottom: 25px; width: 100%; font-size: 9px">
        <thead>
        <tr style="background-color: #e6f2ff">
            <th>Kode Company</th>
            <th>No Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Type JO</th>
            <th>No Reff</th>
            <th>Tgl Reff</th>
            <th>Customer</th>
            <th>Shipper</th>
            <th>Consignee</th>
            <th>Order By</th>
            <th>Kapal</th>
            <th>Voyage</th>
            <th>Port Of Loading</th>
            <th>ETD</th>
            <th>Port Of Transite</th>
            <th>Port Of Destination</th>
            <th>ETA</th>
            <th>Shipping Line</th>
            <th>Customs Clearance</th>
            <th>No Pengajuan</th>
            <th>No PIE/PEB</th>
            <th>Master B/L</th>
            <th>No BC23</th>
            <th>House B/L</th>
            <th>No SI/DO</th>
            <th>Loading Status</th>
            <th>Total Container</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Created By</th>
            <th>Updated By</th>
        </tr>
        </thead>
        
        <tbody>
            <?php foreach ($data as $key => $row) : ?>
                <tr class="border">
                    <td class="border" align="left">03</td>
                    <td class="border" align="left"><?php echo $row->no_joborder ?></td>
                    <td class="border" align="left"><?php echo $row->tanggal_jo ?></td>
                    <td class="border" align="left"><?php echo $row->type ?></td>
                    <td class="border" align="left"><?php echo $row->no_reff ?></td>
                    <td class="border" align="left"><?php echo $row->tgl_reff ?></td>
                    <td class="border" align="left"><?php echo $row->kode_customer ?></td>
                    <td class="border" align="left"><?php echo $row->kode_shipper ?></td>
                    <td class="border" align="left"><?php echo $row->kode_consignee ?></td>
                    <td class="border" align="left"><?php echo $row->order_by ?></td>
                    <td class="border" align="left"><?php echo $row->kode_kapal ?></td>
                    <td class="border" align="left"><?php echo $row->voyage ?></td>
                    <td class="border" align="left"><?php echo $row->port_loading ?></td>
                    <td class="border" align="left"><?php echo $row->etd ?></td>
                    <td class="border" align="left"><?php echo $row->port_transite ?></td>
                    <td class="border" align="left"><?php echo $row->port_destination ?></td>
                    <td class="border" align="left"><?php echo $row->eta ?></td>
                    <td class="border" align="left"><?php echo $row->shipping_line ?></td>
                    <td class="border" align="left"><?php echo $row->customs_clearance ?></td>
                    <td class="border" align="left"><?php echo $row->no_pengajuan ?></td>
                    <td class="border" align="left"><?php echo $row->no_pibpeb ?></td>
                    <td class="border" align="left"><?php echo $row->master_bl ?></td>
                    <td class="border" align="left"><?php echo $row->no_bc ?></td>
                    <td class="border" align="left"><?php echo $row->house_bl ?></td>
                    <td class="border" align="left"><?php echo $row->no_do ?></td>
                    <td class="border" align="left"><?php echo $row->loading_type ?></td>
                    <td class="border" align="left"><?php echo $row->total_item ?></td>
                    <td class="border" align="left"><?php echo $row->status ?></td>
                    <td class="border" align="left"><?php echo $row->created_at ?></td>
                    <td class="border" align="left"><?php echo $row->updated_at ?></td>
                    <td class="border" align="left"><?php echo $row->created_by ?></td>
                    <td class="border" align="left"><?php echo $row->updated_by ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>