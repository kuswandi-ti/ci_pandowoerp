<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo 'List Of Item Per Warehouse' ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px 20px 20px 20px;
            font-size: 9pt !important;
            font-family: sans-serif;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 20px 20px 20px 20px;
                font-size: 9pt !important;
                font-family: sans-serif;
            }
        }

        html,
        body {
            width: 280mm;
            height: 205mm;
            background: #FFF;
            overflow: visible;
        }

        .table-ttd {
            border-collapse: collapse;
            width: 100%;
            font-size: 9pt !important;
            font-family: sans-serif;
        }

        .table-ttd tr,
        .table-ttd tr td {
            border: 0.5px solid black;
            padding: 4px;
            padding: 4px;
            font-size: 9pt !important;
            font-family: sans-serif;
        }

        input,
        textarea,
        select {
            font-family: inherit;
        }

        .table-ttd {
            border-collapse: collapse;
            width: 100%;
            font-size: 9pt !important;
        }

        .table-ttd tr,
        .table-ttd tr td {
            border: 1px solid black;
            padding: 3px;
            padding: 3px;
            font-size: 9pt !important;
        }

        /* tr {
        page-break-before: always;
        page-break-inside: avoid;
        font-size: 9pt !important;
    } */

        .tablee td,
        .tablee th {
            padding: 5px;
            font-size: 9pt !important;

        }


        ul,
        li {
            list-style-type: none;
            font-size: 9pt !important;
        }

        .tablee tr:nth-child(even) {
            background-color: #f2f2f2;
            font-size: 9pt !important;
        }

        .table-ttd thead tr td,
        #tr-footer {
            font-weight: bold;
            font-family: sans-serif;
        }

        .tablee th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
            font-size: 9pt !important;
        }

        .table-ttd th {
            text-align: left;
            color: black;
            font-weight: bolder;
            font-size: 9pt !important;
        }

        .text-center {
            text-align: center;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
            vertical-align: middle;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .rotate {
            /* FF3.5+ */
            -moz-transform: rotate(-90.0deg);
            /* Opera 10.5 */
            -o-transform: rotate(-90.0deg);
            /* Saf3.1+, Chrome */
            -webkit-transform: rotate(-90.0deg);
            /* IE6,IE7 */
            filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
            /* IE8 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
            /* Standard */
            transform: rotate(-90.0deg);
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="tablee">
            <tr>
                <td class="title" style="text-align: center; border: solid black 2.5px;">
                    List Of Item Per Warehouse <?= $StartDate ?> S/d <?= $EndDate ?>
                </td>
            </tr>
        </table>
        <br />
        <table class="table-ttd">
            <thead>
                <tr>
                    <td class="text-center" rowspan="2">NO</td>
                    <td class="text-center" rowspan="2">ITEM CODE</td>
                    <td class="text-center" rowspan="2">ITEM NAME</td>
                    <td class="text-center" rowspan="2">CATEGORY</td>
                    <td class="text-center" rowspan="2">GROUP</td>
                    <td class="text-center" rowspan="2">UNIT</td>
                    <td class="text-center" rowspan="2">BRAND</td>
                    <td class="text-center" rowspan="2">MODEL</td>
                    <td class="text-center" rowspan="2">COLOR</td>
                    <td class="text-center" rowspan="2">DESCRIPTION</td>
                    <td class="text-center" rowspan="2">DIMENSION</td>
                    <td class="text-center" rowspan="2">UNIT D</td>
                    <td class="text-center" rowspan="2">Warehouse</td>
                    <td class="text-center" rowspan="2">Currency</td>
                    <td class="text-center" rowspan="2">AVG VALUE ITEM</td>
                    <td class="text-center" colspan="2">OPENING BALANCE <?= $StartDate ?></td>
                    <td class="text-center" colspan="2">IN <?= $StartDate ?> ~ <?= $EndDate ?></td>
                    <td class="text-center" colspan="2">OUT <?= $StartDate ?> ~ <?= $EndDate ?></td>
                    <td class="text-center" colspan="2">AVALAIBLE STOCK <?= $EndDate ?></td>
                </tr>
                <tr>
                    <td class="text-center">QTY</td>
                    <td class="text-center">VALUE</td>
                    <!-- delimiter -->
                    <td class="text-center">QTY</td>
                    <td class="text-center">VALUE</td>
                    <!-- delimiter -->
                    <td class="text-center">QTY</td>
                    <td class="text-center">VALUE</td>
                    <!-- delimiter -->
                    <td class="text-center">QTY</td>
                    <td class="text-center">VALUE</td>
                    <!-- delimiter -->
                </tr>
            </thead>
            <?php
            $Sum_Qty_Opening = 0;
            $Sum_Val_Opening = 0;
            $Sum_Qty_In = 0;
            $Sum_Val_In = 0;
            $Sum_Qty_Out = 0;
            $Sum_Val_Out = 0;
            $Sum_Qty_End = 0;
            $Sum_Val_End = 0;
            ?>

            <tbody>
                <?php
                $i = 1;
                ?>

                <?php foreach ($SDatas as $li) : ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $li->Item_Code; ?></td>
                        <td><?= $li->Item_Name; ?></td>
                        <td><?= $li->Item_Category; ?></td>
                        <td><?= $li->Group_Name; ?></td>
                        <td><?= $li->Uom; ?></td>
                        <td><?= $li->Brand; ?></td>
                        <td><?= $li->Model; ?></td>
                        <td><?= $li->Item_Color; ?></td>
                        <td><?= $li->Item_Description; ?></td>
                        <td><?= $li->Item_Dimensions; ?></td>
                        <td><?= $li->LWH_Unit; ?></td>
                        <td><?= $li->Warehouse_Name; ?></td>
                        <td><?= 'IDR' ?></td>
                        <td class="text-center">
                            <?php if ($source_value == 'sales') : ?>
                                <?= $Avg_Price = ($Data_Price = $this->db->query("SELECT shp_dtl.Item_Code, coalesce(SUM(inv_dtl.Base_UnitPrice * shp_dtl.Qty)/SUM(shp_dtl.Qty),0) as Avg
                                from ttrx_dtl_shipping_ins shp_dtl
                                join ttrx_hdr_shipping_ins shp_hdr on shp_dtl.SysId_Hdr = shp_hdr.SysId
                                join ttrx_hdr_sls_invoice inv_hdr on shp_hdr.ShipInst_Number = inv_hdr.SI_Number
                                join ttrx_dtl_sls_invoice inv_dtl on inv_hdr.Invoice_Number = inv_dtl.Invoice_Number and shp_dtl.Item_Code = inv_dtl.Item_Code
                                where shp_dtl.Item_Code = '$li->Item_Code'
                                and shp_hdr.ShipInst_Date <= '$EndDate'
                                    AND shp_hdr.ShipInst_Date>= '$StartDate'
                                    group by shp_dtl.Item_Code")->row()) ? $this->help->roundToTwoDecimals($Data_Price->Avg) : 0;
                                ?>
                                <?php $Avg = (empty($Data_Price->Avg)) ? 0 : floatval($Data_Price->Avg); ?>
                            <?php endif; ?>
                            <?php if ($source_value == 'purchase') : ?>
                                <?php
                                echo "<h1>DIKARENAKAN SEDANG CLOSING 2024 PENGERJAAN dengan nilai Rata-rata Pembelian di hold terlebih dahulu, mohon di ingatkan nantinya !</h1>";
                                die;
                                ?>
                            <?php endif; ?>
                            <?php if ($source_value == 'hpp') : ?>
                                <?= $Avg_Price = ($Data_Price = $this->db->query(
                                    "SELECT Hpp as Avg, Hpp_Date
                                    from thst_hpp
                                    where Item_Code = '$li->Item_Code'
                                    AND Hpp_Date <= '2024-12-31'
                                    AND Hpp_Date >= '2024-12-01'
                                    order by Hpp_Date desc
                                    limit 1"
                                )->row()) ? $this->help->roundToTwoDecimals($Data_Price->Avg) : 0;
                                ?>
                                <?php $Avg = (empty($Data_Price->Avg)) ? 0 : floatval($Data_Price->Avg); ?>
                            <?php endif; ?>
                        </td>
                        <!-- OPENING BALANCE -->
                        <td class="text-center">
                            <?=
                            $Opening_Balance = ($data = $this->db->query("SELECT End_Balance 
                            FROM thst_trx_stok_item 
                            WHERE DocDate <= '$StartDate' 
                            and Item_Code = '$li->Item_Code' 
                            ORDER BY ID desc 
                            LIMIT 1")->row()) ? floatval($data->End_Balance) : 0;
                            ?>
                            <?php $Sum_Qty_Opening += $Opening_Balance; ?>
                        </td>
                        <td class="text-center"><?= $this->help->roundToTwoDecimals($Opening_Balance * $Avg) ?> <?php $Sum_Val_Opening += $Opening_Balance * $Avg; ?></td>
                        <!-- IN -->
                        <td class="text-center">
                            <?= $qty_in = ($data = $this->db->query("SELECT SUM(Qty_Adjust_Plus) as in_qty
                            from thst_trx_stok_item
                            WHERE DocDate <= '$EndDate'
                            AND DocDate>= '$StartDate'
                            and Item_Code = '$li->Item_Code'")->row()) ? floatval($data->in_qty) : 0;
                            ?>
                            <?php $Sum_Qty_In += $qty_in; ?>
                        </td>
                        <td class="text-center"><?= $this->help->roundToTwoDecimals($qty_in * $Avg) ?><?php $Sum_Val_In += $qty_in * $Avg ?></td>
                        <!-- OUT -->
                        <td class="text-center"> <?= $qty_out = ($data = $this->db->query("SELECT SUM(Qty_Adjust_Min) as out_qty
                            from thst_trx_stok_item
                            WHERE DocDate <= '$EndDate'
                            AND DocDate>= '$StartDate'
                            and Item_Code = '$li->Item_Code'")->row()) ? floatval($data->out_qty) : 0; ?>
                            <?php $Sum_Qty_Out += $qty_out; ?>
                        </td>
                        <td class="text-center"><?= $this->help->roundToTwoDecimals($qty_out * $Avg) ?><?php $Sum_Val_Out += $qty_out * $Avg ?></td>
                        <!-- FINAL BALANCE -->
                        <td class="text-center"><?= $End_Balance = ($data = $this->db->query("SELECT End_Balance 
                        FROM thst_trx_stok_item 
                        WHERE DocDate <= '$EndDate' 
                        and Item_Code = '$li->Item_Code' 
                        ORDER BY ID DESC 
                        LIMIT 1
                        ")->row()) ? floatval($data->End_Balance) : 0; ?>
                            <?php $Sum_Qty_End += $End_Balance ?>
                        </td>
                        <td class="text-center"><?= $this->help->roundToTwoDecimals($End_Balance * $Avg) ?><?php $Sum_Val_End += $End_Balance * $Avg ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
                <tr style="background-color: #B8BECC;">
                    <td class="text-center" colspan="15">SUMMARY</td>
                    <!-- delimiter -->
                    <td class="text-center"><?= $this->help->roundToTwoDecimals($Sum_Qty_Opening) ?></td>
                    <td class="text-center"><?= $this->help->roundToFourDecimals($Sum_Val_Opening) ?></td>
                    <!-- delimiter -->
                    <td class="text-center"><?= $this->help->roundToTwoDecimals($Sum_Qty_In) ?></td>
                    <td class="text-center"><?= $this->help->roundToFourDecimals($Sum_Val_In) ?></td>
                    <!-- delimiter -->
                    <td class="text-center"><?= $this->help->roundToTwoDecimals($Sum_Qty_Out) ?></td>
                    <td class="text-center"><?= $this->help->roundToFourDecimals($Sum_Val_Out) ?></td>
                    <!-- delimiter -->
                    <td class="text-center"><?= $this->help->roundToTwoDecimals($Sum_Qty_End) ?></td>
                    <td class="text-center"><?= $this->help->roundToFourDecimals($Sum_Val_End) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

<script>
    // document.getElementById('pcs_top').innerHTML = document.getElementById('pcs_bot').innerHTML;
    // document.getElementById('vol_top').innerHTML = document.getElementById('vol_bot').innerHTML;

    setTimeout(function() {
        window.print()
    }, 1500);
</script>