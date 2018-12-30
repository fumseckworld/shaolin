<?php

require_once 'vendor/autoload.php';


echo form('/',id())->save()->input('text','name','username')->input('number','age','age')->textarea('bio','bio',10,10)->submit('a','a','a','a')->get();
