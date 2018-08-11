<?php

const LABELS = ['Time', 'Values'];
const DATA = [
    [1, 18],
    [2, 13],
    [3, 16],
    [4, 11],
    [5, 17],
    [6, 14],
];

// DATA INDEXES
const iTIME = 0;
const iACTUAL = 1;
const iFORECAST = 2;
const iERROR = 3;
const iMAE = 4;
const iMSE = 5;
const iPCTE = 6;
const iAPCTE = 7;
const iMAPE = 8;

// MATH SYMBOLS
const sERROR = 'Y';
const sACTUAL = 'A';
const sFORECAST = 'F';

function formula(string $formula, string ...$placeholders): string {
    return sprintf("\$\${$formula}\$\$", ...$placeholders);
}

$definitionSet = [
    'Forecast Calculation' => [
        [
            'Naive Forecasting',
            'next forecast = current actual',
            formula('%s_{t+1} = %s_{t}', sFORECAST, sACTUAL),
        ],
        [
            'Average of Past Values',
            'next forecast = avg of past n-many actuals',
            formula('%s_{t+1} = \frac{\sum_{t-n}^t %s_{t}}{n}', sFORECAST, sACTUAL),
        ],
    ],
    'Error Calculation' => [
        [
            'Forecast Error',
            'error = actual - forecast',
            formula('%s_{t} = %s_{t} - %s_{t}', sERROR, sACTUAL, sFORECAST),
        ],
        [

            'Mean Absolute Error (MAE)',
            'sum of abs values of forecast errors divided by # of forecasts',
            formula('\frac{\sum_{t-n}^t |%s_{t}|}{n}', sERROR),
        ],
        [

            'Mean Square Error (MSE)',
            'sum of the errors squared divided by # of forecasts',
            formula('\frac{\sum_{t-n}^t %s_{t}^2}{n}', sERROR),
        ],
        [

            'Percentage Error (PCTE)',
            'error divided by actual',
            formula('\frac{%s_{t}}{%s_{t}}', sERROR, sACTUAL),
        ],
        [
            'Absolute Percentage Error (APCTE)',
            'absolute value of error divided by actual',
            formula('\frac{|%s_{t}|}{%s_{t}}', sERROR, sACTUAL),
        ],
    ],
];

function naiveForecast(array $labels, array $timeSeries): array
{
    // Add Naive
    $labels[] = 'Naive';
    foreach ($timeSeries as $i => &$row) {
        $row[iFORECAST] = $timeSeries[$i - 1][iACTUAL] ?? null;
    }
    return [$labels, $timeSeries];
}

function avgPastForecast(array $labels, array $timeSeries): array
{
    // Add Average of Past Values
    $labels[] = 'Avg of Past Val';
    $actual = [];
    foreach ($timeSeries as $i => &$row) {
        $row[iFORECAST] = round(array_sum($actual) / max(1, count($actual)), 2);
        $actual[] = $row[iACTUAL] ?? null;
    }
    return [$labels, $timeSeries];
}

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


[$naiveLabels, $naiveTimeSeries] = deriveError(...naiveForecast(LABELS, DATA));
[$avgPastLabels, $avgPastTimeSeries] = deriveError(...avgPastForecast(LABELS, DATA));

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config=TeX-MML-AM_CHTML' async></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <title>TimeSeries</title>
</head>
<body>
<div class="container">
    <div>
        <?= (require __DIR__ . '/render_data.php')('Naive Implementation', $naiveLabels, $naiveTimeSeries) ?>
    </div>
    <hr>
    <div>
        <?= (require __DIR__ . '/render_data.php')('Past Average Implementation', $avgPastLabels, $avgPastTimeSeries) ?>
    </div>
    <hr>
    <div>
        <h1 class="display-4">Vocabulary</h1>
        <div class="row">
            <?php foreach ($definitionSet as $label => $definitions): ?>
                <div class="col">
                    <h3><?= $label ?></h3>
                    <ul class="list-group">
                        <?php foreach ($definitions as [$name, $description, $equation]): ?>
                            <li class="list-group-item">
                                <h3><?= $name ?></h3>
                                <label><?= $description ?></label>
                                <p><?= $equation ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
