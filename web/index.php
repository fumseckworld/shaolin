<?php

use Imperium\Http\Request\ServerRequest;

require '../vendor/autoload.php';

dump(ServerRequest::generate()->method());
