<?php

namespace Testing\Html;

use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Nol\Html\Form\Generator\FormGenerator;
use Nol\Testing\Unit;

class FormTest extends Unit
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function testForm()
    {
        $form = (new FormGenerator())->open('/')->close('envoyer')->get();
        $this->def($form)
            ->contains(
                $form,
                'action="/"',
                'method="POST"',
                'form_token',
                '<button type="submit" class="form-button form-submit">envoyer</button>'
            );
    }
}
