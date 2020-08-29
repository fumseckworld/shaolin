<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

app('response')->from('global')->get();
