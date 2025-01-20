<!DOCTYPE html>
<html>

<head>
    <title>Hasil Produksi <?php echo $StartDate; ?> sd <?php echo $EndDate; ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        @page {
            size: A4 landscape;
            margin: 20mm;
        }

        .footer {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }

        .footer div {
            display: inline-block;
            width: 40%;
            /* Adjust based on preference */
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="title">
        <h2>Hasil Produksi <?= $this->config->item('company_name_full') ?> <?php echo $StartDate; ?> s/d <?php echo $EndDate; ?></h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal/Item</th>
                <?php foreach ($unique_item_codes as $code): ?>
                    <th><?php echo $code; ?></th>
                <?php endforeach; ?>
                <th>Total Per Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_per_item = array_fill_keys($unique_item_codes, 0);  // Total per item across all dates
            foreach ($production_data as $date => $items): ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <?php
                    $total_per_date = 0;  // Total per date across all items
                    foreach ($unique_item_codes as $code): ?>
                        <td>
                            <?php
                            $qty = isset($items[$code]) ? floatval($items[$code]['TotalQty']) : 0;
                            if (empty($qty)) {
                                echo '-';
                            } else {
                                echo $qty;
                            }
                            $total_per_date += $qty;
                            $total_per_item[$code] += $qty;
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td><?php echo $total_per_date; ?></td> <!-- Display total per date -->
                </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total Per Item</th>
                <?php foreach ($total_per_item as $total): ?>
                    <th><?php echo $total; ?></th> <!-- Display total per item -->
                <?php endforeach; ?>
                <th></th> <!-- Empty cell for the corner -->
            </tr>
        </tbody>
    </table>
    <div class="footer">
        <div>
            <strong>Dibuat oleh:</strong><br><br><?= $created_by ?>
            <br> <!-- Spasi untuk signature -->
            __________________________
        </div>
        <div>
            <strong>Disetujui oleh:</strong><br><br><?= $approved_by ?>
            <br> <!-- Spasi untuk signature -->
            __________________________
        </div>
    </div>
</body>

</html>