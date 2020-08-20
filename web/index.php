<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

dd((new \Nol\Database\Table\Table(connect('sqlite', base('routes', 'web.db'))))->show());
