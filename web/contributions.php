<?php

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

header('content-type','application/json');



$repository = $_POST['repository'];


echo json_encode(app()->git("repositories/$repository",'')->commits_by_month($_POST['name'])->collection());