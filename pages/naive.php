<?php
require_once __DIR__ . '/../forecast.php';

function naiveForecast(array $labels, array $timeSeries): array
{
    // Add Naive
    $labels[] = 'Naive';
    foreach ($timeSeries as $i => &$row) {
        $row[iFORECAST] = $timeSeries[$i - 1][iACTUAL] ?? null;
    }
    return [$labels, $timeSeries];
}

[$naiveLabels, $naiveTimeSeries] = deriveError(...naiveForecast(LABELS, DATA));
echo renderData('Naive Implementation', $naiveLabels, $naiveTimeSeries);
