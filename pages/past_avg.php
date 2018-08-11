<?php
require_once __DIR__ . '/../forecast.php';

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

[$avgPastLabels, $avgPastTimeSeries] = deriveError(...avgPastForecast(LABELS, DATA));
echo renderData('Past Average Implementation', $avgPastLabels, $avgPastTimeSeries);
