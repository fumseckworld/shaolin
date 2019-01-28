<?php


namespace Testing;


use Imperium\Flash\Flash;
use Imperium\Session\Session;
use Imperium\View\View;

trait Share
{
    /**
     * @var View
     */
    private $view;

    /**
     * @var \Imperium\App
     */
    private $app;

    /**
     * @var Session
     */
    private $flash;

    /**
     * @var \Twig_Environment
     */
    private $twig;


    /**
     * Share constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $this->app = app('mysql','root','zen','root','localhost','../dump','..','../views',[],[],[]);
    }

}