<?php

require_once '../vendor/autoload.php';
echo bootswatch('lumen');
echo awesome();
echo  html('div',app()->model()->from('model')->display(DISPLAY_ARTICLE,'/show',1,'id','previous','next'),'container');
