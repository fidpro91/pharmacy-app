<page>
    <table width="100%" border="" cellpadding="2" cellspacing="1" style="font-size: 12px; font-family: Arial;border-collapse: collapse;">
        <tr id="header">
            <td colspan="4" align="left" style="line-height:20px; border-bottom: 1px solid #000;">
                <h3 style="padding:0px;margin:0px"><?php echo "FAKTUR PENJUALAN OBAT " . strtoupper($detailcetak['unit_name']); //."<br>"; print_r($all) ;
                                                    ?></h3>
                <?= ucfirst($detailrs->hsp_name) ?><br />
                <?= ucfirst($detailrs->hsp_address) ?>
            </td>
            <td colspan="2" align="center" style="border-bottom: 1px solid #000;">
                No. Antrian : <br>
                <h2 style="font-size: 30px; padding:0px;margin:0px;font-weight:100pt;"><?php $no_antrian = explode('/', $detailcetak['noresep']);
                                                                                        echo $no_antrian[1];
                                                                                        ?></h2>
            </td>
        </tr>
    </table>
    <!-- <hr width="100%" size="1px" color="black" /> -->
    <table width="100%" border="0" cellpadding="2" cellspacing="1" style="font-size: 10px;  font-family: Arial; padding:0px; margin:0px;" class="header">
        <tr>
            <td width="10%">Nomor Resep</td>
            <td width="2%">:&nbsp;</td>
            <td width="40%"><?php echo $detailcetak['noresep']; ?></td>
            <td width="10%">Tanggal</td>
            <td width="1%">:&nbsp;</td>
            <td width="31%"><?php echo $detailcetak['tanggal']; ?></td>
        </tr>
        <tr>
            <td>Nama Pasien</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;"><?php echo $detailcetak['namapasien']; ?></td>
            <td>Penjamin</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;"><?php echo $detailcetak['kepemilikan']; ?></td>
        </tr>
        <tr>
            <td>Dokter</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;"><?php echo $detailcetak['doctor_name']; ?></td>
            <td>Pelayanan</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;"><?= $detailcetak['layanan'] ?></td>
        </tr>
        <?php
        if ($detailcetak['kepemilikan'] == 'BPJS') {
        ?>
            <tr>
                <td>No. BPJS</td>
                <td>:&nbsp;</td>
                <td style="text-transform:uppercase;"><?php echo $detailcetak['pxsurety_no']; ?></td>
                <td>No. SEP</td>
                <td>:&nbsp;</td>
                <td style="text-transform:uppercase;"><?= $detailcetak['sep_no'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br />
    <table class="table" width="100%" cellpadding="2" border="0" cellspacing="1" style="font-size: 10px;  font-family: Arial;" frame="box">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Komponen Obat</th>
                <th>qty</th>
                <!-- <th>dosis</th> -->
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody class="body-tabel">
            <?php
            $i = 1;
            $total_obat = 0;
            $sale_service = 0;
            $sale_embalase = 0;
            $racikan = "";
            $dosis = "";
            $cols = 1;
            $get_row = 1;
            foreach ($listresep as $row) {
                $sale_service = str_replace('$', '', str_replace(',', '', $row->sale_services));
                $sale_embalase = str_replace('$', '', str_replace(',', '', $row->sale_embalase));
                $total_obat += str_replace('$', '', str_replace(',', '', $row->subtotal));
                $embalase_all = $row->embalase_item_sale;
                if (!$row->racikan_id) {
                    $row->racikan_id = $row->item_name;
                    $row->item_name = '-';
                    $cols = 1;
                    $get_row = 1;
                } else {
                    $get_row = $this->db->where('sale_id', $detailcetak['sale_id'])
                        ->where('racikan_id', $row->racikan_id)
                        ->select("DISTINCT item_id", false)
                        ->get('farmasi.sale_detail')->num_rows();
                }

                if ($racikan != $row->racikan_id) {
                    $racikan = $row->racikan_id;
                    $isi = $racikan;
                    $cols = $get_row;
                } else {
                    $isi = "";
                    $cols = 1;
                }

                if ($dosis != $row->dosis) {
                    $dosis = $row->dosis;
                    $isi2 = $dosis;
                    $cols2 = $get_row;
                } else {
                    $isi2 = "";
                    $cols2 = 1;
                }
            ?>
                <tr>
                    <td style="text-align:center; padding:2px;" width="2%" border="0px"><?= $i ?></td>
                    <?php
                    if ($isi != "") {
                    ?>
                        <td style="padding-left:5px;" rowspan="<?= $cols ?>" width="30%"><?= $isi ?></td>
                    <?php } ?>
                    <td width="20%" style="padding-left:5px;"><?= $row->item_name ?></td>
                    <td align="center" width="5%"><?= $row->sale_qty ?></td>
                    <td align="right" width="5%"><?= str_replace('$', '', $row->sale_price) ?></td>
                    <td align="right" width="15%"><?= str_replace('$', '', $row->subtotal) ?></td>
                </tr>
            <?php
                $i++;
            }
            ?>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" align="right" style="padding:2px;border-top: 1px solid #000;">Total Obat</td>
                <td align="right" style="border-top: 1px solid #000;"><?= number_format($total_obat, 2, ".", ",") ?></td>
            </tr>
            <tr>
                <td colspan="5" align="right" style="padding:2px;">Biaya Racik</td>
                <td align="right"><?= number_format($sale_service, 2, ".", ",") ?></td>
            </tr>
            <tr>
                <td colspan="5" align="right" style="padding:2px;">Embalase Item</td>
                <td align="right"><?= number_format($embalase_all, 2, ".", ",") ?></td>
            </tr>
            <!-- <tr>
            <td colspan="5" align="right">Biaya Pembulatan</td>
            <td align="right"><?= number_format($sale_embalase, 2, ".", ",") ?></td>
          </tr> -->
            <tr>
                <td colspan="5" align="right" style="padding:2px;border-bottom: 1px solid #000;">Grand Total</td>
                <!--<td align="right" style="border-bottom: 1px solid #000;"><?= number_format($sale_embalase + $sale_service + $total_obat + $embalase_all, 2, ".", ",") ?></td>-->
                <td align="right"><?= number_format($detailcetak['sale_total'], 2, ".", ",") ?></td>
            </tr>
        </tfoot>
    </table>
    <br />
    <table width="100%" cellpadding="2" accesskey="" cellspacing="1" style="font:10px arial; ">
        <tr>
            <td align="right">
                <p align='center'>Gresik, <?php echo date('d-m-Y H:i:s'); ?></p>
                <br><br>
                <p align='center'><?php echo $pencetak; ?></p>
            </td>
        </tr>
    </table>
</page>