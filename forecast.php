<?php

function deriveError(array $labels, array $timeSeries): array
{
    // Add Forecast Error
    $labels[] = 'Forecast Error';
    foreach ($timeSeries as $i => &$row) {
        $row[iERROR] = $row[iFORECAST] == null
            ? null
            : $row[iACTUAL] - $row[iFORECAST];
    }

    // Add Mean Average Error
    $labels[] = 'MAE';
    $mae = [];
    foreach ($timeSeries as $i => &$row) {
        if ($row[iERROR] === null) {
            $row[iMAE] = null;
        } else {
            $mae[] = abs($row[iERROR]);
            $row[iMAE] = round(array_sum($mae) / max(1, count($mae)), 2);
        }
    }

    // Add Mean Square Error
    $labels[] = 'MSE';
    $mse = [];
    foreach ($timeSeries as $i => &$row) {
        if ($row[iERROR] === null) {
            $row[iMSE] = null;
        } else {
            $mse[] = pow($row[iERROR], 2);
            $row[iMSE] = round(array_sum($mse) / max(1, count($mse)), 2);
        }
    }

    // Add Percent Error
    $labels[] = 'PCTE';
    foreach ($timeSeries as $i => &$row) {
        if ($row[iERROR] === null) {
            $row[iPCTE] = null;
        } else {
            $row[iPCTE] = round($row[iERROR] / $row[iACTUAL] * 100, 2);
        }
    }

    // Add Absolute Percent Error
    $labels[] = 'APCTE';
    foreach ($timeSeries as $i => &$row) {
        if ($row[iPCTE] === null) {
            $row[iAPCTE] = null;
        } else {
            $row[iAPCTE] = abs($row[iPCTE]);
        }
    }

    // Add Mean Absolute Percent Error (MAPE)
    $labels[] = 'MAPE';
    $mape = [];
    foreach ($timeSeries as $i => &$row) {
        if ($row[iAPCTE] === null) {
            $row[iMAPE] = null;
        } else {
            $mape[] = $row[iAPCTE];
            $row[iMAPE] = array_sum($mape) / max(1, count($mape));
        }
    }

    return [$labels, $timeSeries];
}

function renderData(string $title, array $labels, array $rows): string
{
    $chartId = uniqid();
    $colors = [
        'pink',
        'teal',
        'cyan',
        'orange',
        'magenta',
        'tan',
        'mediumpurple',
        'lightslategray',
    ];
    $config = [
        'type' => 'line',
        'data' => [
            'labels' => array_map(function (array $row) use ($labels) {
                return $labels[iTIME] . " {$row[iTIME]}";
            }, $rows),
            'datasets' => array_map(function (int $index) use ($labels, $rows, &$colors) {
                return [
                    'label' => $labels[$index],
                    'hidden' => $index > iFORECAST,
                    'data' => array_map(function (array $row) use ($index) {
                        return $row[$index];
                    }, $rows),
                    'borderColor' => array_shift($colors),
                    'fill' => false,
                ];
            }, array_slice(array_keys($labels), 1)),
        ],
        'options' => [
        ],
    ];
    ob_start();
    ?>
    <h1 class="display-4"><?= $title ?></h1>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <?php foreach ($labels as $label): ?>
                    <th><?= $label ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $data): ?>
                <tr>
                    <?php foreach ($data as $datum): ?>
                        <td><?= $datum ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <canvas id="<?= $chartId ?>"></canvas>
    <script type="text/javascript">
        new Chart(document.getElementById("<?= $chartId ?>").getContext('2d'), <?= json_encode($config) ?>);
    </script>
    <?php
    return ob_get_clean();
}
