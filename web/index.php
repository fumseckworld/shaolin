<?php



require_once '../vendor/autoload.php';

$x = collect(['a','b','c'])->all();
foreach ($x as $v)
    echo $v;
