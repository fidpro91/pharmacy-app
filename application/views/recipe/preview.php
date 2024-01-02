<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px; border-bottom: 1px solid black;">
    <tr>
        <td colspan="3" align="left" style="line-height:16px;">
            <h3 style="padding:0px;margin:0px">E RESEP RSUD IBNUSINA GRESIK</h3>
        </td>
        <td colspan="3" align="right" style="line-height:16px;">
            <b> Iterasi : </b><?php
                                if ($pasien->iterasi == null || $pasien->iterasi == 0) {
                                    echo "<b>" . "Tanpa Iterasi" . "</b>";
                                } else {
                                    echo "<b>" . $pasien->iterasi . "x" . "</b>";
                                }
                                ?>
        </td>
    </tr>
</table>
<p style="font-size: 9px; text-align: right;"><i><b>Tanggal Resep : <?php echo $pasien->tgl_resep; ?></b></i></p>
<table width="100%" border="0" class="table_identitas" cellpadding="5" cellspacing="0" style="border-collapse: collapse; line-height: 0.8;">
    <tr>
        <td width="15%">Nomor</td>
        <td width="1%"> : </td>
        <td width="30%"> <?php echo $pasien->rcp_no; ?></td>
        <td>Tgl.lahir : <?php echo $pasien->tgl_lahir; ?></td>

    </tr>
    <tr>
        <td colspan="3" style="vertical-align: top;">Nama/No.RM :
            <?php echo $pasien->px_name . ' (' . $pasien->px_norm . ')'; ?></td>
        <td colspan="3">No.sep : <?php echo $pasien->sep_no; ?></td>
    </tr>
    <tr>
        <td colspan="3">Alamat : <br>
            <?php echo $pasien->px_address; ?>
        </td>
        <td colspan="3">
            No.Penjamin :
            <?= $pasien->pxsurety_no . "($pasien->surety_name)" ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">Pelayanan : <?= $pasien->unit_name ?></td>

        <td colspan="3">Status Resep : <?php
                                        if ($pasien->jenis_resep == "1") {
                                            echo "Pulang";
                                        } elseif ($pasien->jenis_resep == "2") {
                                            echo "Cito";
                                        } elseif ($pasien->jenis_resep == "3") {
                                            echo "Pelayanan";
                                        } else {
                                            echo '';
                                        }
                                        ?></td>

    </tr>
    <tr>
        <td colspan="3">Riwayat Alergi : <?php if ($pasien->alergi == null) {
                                                echo '-';
                                            } else {
                                                echo "<br>" . $pasien->alergi;
                                            } ?>

        </td>
        <td>BB : <?php echo $pasien->bb; ?> Kg</td>

    </tr>

    <tr>
        <td colspan="3">Unit Asal : <br>
            <?php echo $pasien->asal_layanan; ?> </td>

        <td colspan="3">Dokter pembuat : <br> <?php echo ucwords(strtolower($pasien->dokter)); ?></td>
    </tr>
    <tr>
        <td colspan="4">Dokter Dpjp : <?php echo ucwords(strtolower($pasien->dpjp)); ?></td>

    </tr>
</table>

<table style="width: 100%;">
    <tr>
        <td style="vertical-align: top;  font-size : 10pt !important; ">
            <div class="container">
                <div class="left-div">
                    <div class="item"><b>Non Racikan</b></div>
                    <?php
                    $no = 1;
                    foreach ($resep as $key => $res) {
                        if ($res->racikan_qty == null) {
                            echo '<div class="item">' . $res->item_name . ' Jml (' . $res->qty . ')' . '<br>' . '<i> signa(&fnof;) ' . $res->dosis . '</i>' . '</div>
                            <br>';
                        }
                    }
                    ?>
                </div>
                <?php
                $groupedData = array(); // Inisialisasi array groupedData

                foreach ($resep as $key => $res) {
                    if ($res->racikan_qty != null) {
                        $racikanId = $res->racikan_id;
                        if (!isset($groupedData[$racikanId])) {
                            $groupedData[$racikanId] = array();
                        }
                        $groupedData[$racikanId][] = array(
                            "item_name" => $res->item_name,
                            "dosis" => $res->dosis,
                            "racikan_dosis" => $res->racikan_dosis,
                            "racikan_qty" => $res->racikan_qty
                        );
                    }
                }

                // Cek apakah ada data yang sesuai dengan kondisi
                $dataExists = false;
                foreach ($groupedData as $racikanId => $group) {
                    if (!empty($group)) {
                        $dataExists = true;
                        break;
                    }
                }

                if ($dataExists) {
                    echo '<div class="right-div" >';
                    echo '<div class="item"><b>Racikan</b></div>';
                    foreach ($groupedData as $racikanId => $group) {
                        echo '<div class="item">' . '<b>' . $racikanId . '</b>  ' . '  JML = ' . $group[0]['racikan_qty'] . '</div>';
                        echo '<div class="item"><b>Dosis</b> : <i>signa(&fnof;) ' . $group[0]['dosis'] . '<i></div> <br>';

                        foreach ($group as $item) {

                            echo '<div class="item"> ' . $item["item_name"] . ' Jml (' . $item["racikan_dosis"] . ')' . '</div> <br> ';
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </td>
        <td>
            <table class="tabel_telaah" style="text-align: center; border-collapse: collapse; border: 1px solid blac;" border="1">
                <tr>
                    <td colspan="3" style="padding: 5px;"><b>Telaah Penyiapan/Verifikasi*</b></td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><b>Keterangan<b></td>
                    <td>Ya</td>
                    <td>Tidak</td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Tepat Pasien</td>
                    <td></td>
                    <td></td>
                <tr>
                    <td style="padding: 5px;">Tepat Obat</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Tepat Dosis</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Tepat Rute</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Tepat Waktu</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left;">
                        Petugas :<br>
                        <hr style="border-top: 1px solid black; ">
                        TTD/Paraf :
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 5px;"><b>Penerimaan Obat</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left;">
                        Penerima :<br>
                        <hr style="border-top: 1px solid black; ">
                        TTD/Paraf :
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 5px;"><b>DOKTER</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <img src="<?= $ttd ?>" width="20%" /></br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 5px; font-size:5px"><b><?= $pasien->dokter ?></b></td>
                </tr>
            </table>
        </td>


    </tr>
</table>