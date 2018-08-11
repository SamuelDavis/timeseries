<?php

function formula(string $formula, string ...$placeholders): string
{
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
?>
<h1 class="display-4">Vocabulary</h1>
<div class="row">
    <?php foreach ($definitionSet as $label => $definitions): ?>
        <div class="col-xs-<?= max(3, 12 / count($definitionSet)) ?>">
            <div class="container">
                <h3><?= $label ?></h3>
                <ul class="list-group">
                    <?php foreach ($definitions as [$name, $description, $equation]): ?>
                        <li class="list-group-item">
                            <h5><?= $name ?></h5>
                            <label><?= $description ?></label>
                            <p><?= $equation ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</div>
