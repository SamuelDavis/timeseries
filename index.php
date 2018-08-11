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


const NAV = [
    'Vocabulary' => 'vocabulary',
    'Naive Forecast' => 'naive',
    'Past Average Forecast' => 'past_avg',
];
$page = $_GET['page'] ?? array_values(NAV)[0];
$path = __DIR__ . "/pages/{$page}.php";
if (!file_exists($path)) {
    $path = __DIR__ . '/pages/vocabulary.php';
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config=TeX-MML-AM_CHTML' async></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-expand-sm bg-light">
        <ul class="navbar-nav">
            <?php foreach (NAV as $label => $link): ?>
                <li class="nav-item">
                    <a class="nav-link"
                       href="?page=<?= $link ?>"><?= $label ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>
<div class="container">
    <?php require_once $path ?>
</div>
</body>
</html>
