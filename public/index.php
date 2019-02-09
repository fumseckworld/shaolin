<?php

require_once '../vendor/autoload.php';


echo bootswatch('lumen');
echo awesome();

_html(false,html('div',
form('/','a')
->row()
->reset('clear')
->end_row_and_new()
->textarea('a','linux')->textarea('b','azd')
->end_row_and_new()
->textarea('c','linux')->textarea('d','azd')
->end_row_and_new()
->group(['home','login'],'/','login')
->end_row_and_new()
->select(false,'base',app()->show_databases(),fa('fas','fa-database'))
->input('text','ame','a')
->end_row_and_new()
->submit('commit','submit')
->end_row()
                        ->get(),'container mt-5'));

