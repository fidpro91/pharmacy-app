<style>
    table,
    tr,
    td,
    th {
        border: #000000 solid 2px;
    }
</style>

<body>
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td rowspan="2" width="10%" style="border-right:solid 1px #fff;">logo</td>
            <td rowspan="2" width="40%"><?= $profil["hsp_name"] . '<br />' . $profil["hsp_address"] ?></td>
            <td width="50%">NO : -, Tanggal : -</td>
        </tr>
        <tr>
            <td>Nama Supplier : <?= $isi->supplier_id ?></td>
        </tr>
        <tr>
            <td colspan="3">Kains farmasi :<br /> SIPA</td>
        </tr>
    </table>
    <p style="text-align: center;font-weight: 900;">RETUR PENERIMAAN</p>
    <table width="100%" style="border-collapse:collapse;text-align:center;">
        <tr>
            <th style="width:5%">No</th>
            <th style="width:13%">Tgl Terima</th>
            <th style="width:10%">No. Faktur</th>
            <th style="width:36%">Nama Obat</th>
            <th style="width:12%">Kepemilikan</th>
            <th style="width:12%">Qty Terima</th>
            <th style="width:12%">Qty Retur</th>
        </tr>
        <?php $no = 1;
        foreach ($isi->detail as $detail) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $detail->tgl ?></td>
                <td><?= $detail->faktur ?></td>
                <td style="text-align:left;"><?= $detail->item_name ?></td>
                <td><?= $detail->own_name ?></td>
                <td><?= $detail->qty_unit ?></td>
                <td><?= $detail->rrd_qty ?></td>
            </tr>
        <?php } ?>
    </table>
    <p></p>
    <table width="100%" cellspacing="0" cellpadding="0" style="border:none;">
        <tr>
            <td style="border:none;">Petugas,</td>
            <td style="border:none;">Kains Farmasi,</td>
            <td style="border:none;">Tanggal</td>
        </tr>
        <tr>
            <td style="border:none;">
                <br>
                <br>
                <p></p>..................
            </td>
            <td style="border:none;">
                <br>
                <br>
                <p></p>..................
            </td>
            <td style="border:none;text-align:right;"></td>
        </tr>
    </table>
</body>