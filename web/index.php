<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

display_error();

app('response')->from('global')->get();
