<?php

	require '../vendor/autoload.php';

	$response = app()->run();

    echo  $response->time();
