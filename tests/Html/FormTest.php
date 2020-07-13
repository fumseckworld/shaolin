<?php

namespace Testing\Html;

use Imperium\Html\Form\Form;
use Imperium\Testing\Unit;

class FormTest extends Unit
{
    public function testForm()
    {
        $this->empty((new Form())->getForm());
    }
}
