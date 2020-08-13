<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

dd(app('cache')->clear('app'));
