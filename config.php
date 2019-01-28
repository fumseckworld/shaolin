<?php

require_once  'vendor/autoload.php';

\Dotenv\Dotenv::create('.')->load();

try {

    $mysql = app();
} catch (Exception $e) {
    d($e->getMessage());
}

try {

    $pgsql = app();
} catch (Exception $e)
{
    d($e->getMessage());
}

try {
    $sqlite = app();
} catch (Exception $e) {
    d($e->getMessage());
}
