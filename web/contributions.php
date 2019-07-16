<?php


use Imperium\Versioning\Git\Git;

require_once '../vendor/autoload.php';

header('content-type','application/json');



$repository = post('repository');


echo json_encode((new Git($repository,''))->commits_by_month(post('author'))->values());