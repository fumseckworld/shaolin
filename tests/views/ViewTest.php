<?php

namespace Testing\views;


use Imperium\View\View;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ViewTest extends TestCase
{

    /**
     * @var string
     */
    private $cache;

    /**
     * @var View
     */
    private $view;

    /**
     * @var string
     */
    private $name;

    public function setUp()
    {
        $this->cache = 'tmp';
        $this->view = new View('views');
        $this->name  = 'a.twig';
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function test_view()
    {
        $content = $this->view->load($this->name);
        $this->assertNotEmpty($content);
        $this->assertContains('show',$content);

    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function test_view_params()
    {
        $data = ['name' => 'Willy', 'username' => 'fumseck'];
        $content = $this->view->load('trans.twig',$data);
        $this->assertContains('Willy or fumseck',$content);

    }
    /**
     *
     */
    public  function test_instance()
    {
        $this->assertInstanceOf(Twig_Environment::class,$this->view->twig());
        $this->assertInstanceOf(Twig_Loader_Filesystem::class,$this->view->loader());
    }



    public function test_global()
    {
        $array = $this->view->add_global('name','willy')->add_global('family','Micieli')->globals();
        $this->assertContains('willy',$array);
        $this->assertContains('Micieli',$array);
        $this->assertNotEmpty($array);
    }

}