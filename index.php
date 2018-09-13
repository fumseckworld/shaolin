<?php

require_once 'vendor/autoload.php';
 include_once 'config.php';
echo fontAwesome();
echo css_loader('lily/lily-dark.css');
$app = instance_mysql();

d($app->model()->all());