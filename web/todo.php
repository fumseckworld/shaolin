<?php

use Imperium\Versioning\Git\Git;

require_once '../vendor/autoload.php';
header('content-type','application/json');
$repository = post('repository');
$created_at = post('created_at');
$contributor = post('contributor');
$task = post('task');
$todo_limit = post('todo_limit');

$data = ['id'=> 'id' , 'task' => $task,'contributor' => $contributor,'end' => $todo_limit,'created_at' => $created_at];

echo json_encode((new Git($repository,''))->add_todo($data));