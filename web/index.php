<?php

require '../vendor/autoload.php';

$form = new \Imperium\Html\Form\Generator\FormGenerator();

echo $form
    ->open('/')
    ->add('username', 'text', ['placeholder' => 'your name','value' => "a"])
    ->add('bio', 'textarea', ['placeholder' => 'your bio' ])
    ->close();
