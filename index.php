<?php

require_once 'vendor/autoload.php';
 include_once 'config.php';
echo fontAwesome();
echo css_loader('lily/lily-dark.css');

$a = new \Imperium\Collection\Collection(['a','b','c']);
d($a->collection());