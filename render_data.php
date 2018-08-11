<?php

return function (string $title, array $labels, array $rows): string {
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
};
