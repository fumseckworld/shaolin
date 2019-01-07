<?php

use Imperium\File\File;
use Imperium\Router\Router;

require_once '../vendor/autoload.php';

whoops();

File::loads('../routes.php');

Router::run(get('url'),'Testing');