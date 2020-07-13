<?php

namespace Imperium\Html\Form {
    /**
     * Class Form
     *
     * @package Imperium\Html\Form
     * @property string $form The form generated.
     */
    class Form
    {
        /**
         * Form constructor.
         */
        public function __construct()
        {
            $this->form = '';
        }
        
        /**
         *
         * Get the generated form.
         *
         * @return string
         *
         */
        public function getForm(): string
        {
            return $this->form;
        }
    }
}
