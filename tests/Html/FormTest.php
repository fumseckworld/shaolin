<?php

namespace Testing\Html;

use Imperium\Html\Form\Generator\FormGenerator;
use Imperium\Testing\Unit;

class FormTest extends Unit
{
    public function testForm()
    {
        $this->def((new FormGenerator())->open('/')->close());
    }
}
