<?php


require_once '../vendor/autoload.php';
require_once '../config.php';
whoops();



echo css_loader('https://bootswatch.com/4/lumen/bootstrap.min.css','https://use.fontawesome.com/releases/v5.6.3/css/all.css');

echo html('div',query_view('are you sure ?' ,'/',$mysql,'/','/','create record','update record','imperium','expected','commit','btn btn-primary','remove success',
'record not found','table empty','reset'),'container mt-5 mb-5');





