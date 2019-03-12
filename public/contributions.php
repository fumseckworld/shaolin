<?php
use Carbon\Carbon;

header("Content-Type: application/json; charset=UTF-8");

require_once  '../vendor/autoload.php';


$git =     $git = new \Imperium\Versioning\Git\Git(dirname(core_path('app')) .DIRECTORY_SEPARATOR  .'data/symfony');

$json = collection();

$json->add(date('Y'),'year');
$json->add($git->repository(),'repository');

$json->add(post('user'),'author_name');
$json->add($git->contributions(post('user')),'contributions');
$json->add($git->interval(),'months');



echo  $json->json();