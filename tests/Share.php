<?php


namespace Testing;


use Imperium\View\View;

trait Share
{
    /**
     * @var View
     */
    private $view;

    public function __construct()
    {
        $this->view = new View('views');
    }

}