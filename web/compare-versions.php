<?php

use Imperium\Versioning\Git\Git;

require_once '../vendor/autoload.php';

header('Content-Type: application/json');

$first = post('first');
$second = post('second');
$repository = post('repository');


echo json_encode((new Git($repository,''))->change($first,$second));