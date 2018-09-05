<?php

require_once 'vendor/autoload.php';

echo fontAwesome();
echo css_loader('lily/lily-dark.css');

try{
    echo html('div',register('register.php','','','username','username will be used','username cannot be empty','email address','email will be used','email address incorrect','password','password will be used','The password must not be empty ','confirm password','create account','btn btn-outline-primary',true,['fr' => 'Francais','es' => 'Spanish'],'select a language','language will be used','please select a language','choose a time zone','time zone will be used','please select a timezone'),'container mt-5');
}catch (Exception $e)
{
    d($e->getMessage());
}