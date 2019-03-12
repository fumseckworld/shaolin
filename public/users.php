<?php


header("Content-Type: application/json; charset=UTF-8");

require_once  '../vendor/autoload.php';

echo  app()->model()->from('git')->search(post('user'),true);

