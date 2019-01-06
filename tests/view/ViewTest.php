<?php

namespace Testing\view;


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
        $this->name  = 'welcome.twig';
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
        $this->assertEquals('welcome',$content);

    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function test_view_params()
    {
        $data = ['name' => 'Willy', 'username' => 'fumseck'];
        $content = $this->view->load('a.twig',$data);
        $this->assertEquals('Willy or fumseck',$content);

    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function test_enable_cache()
    {
       $this->assertNotEmpty($this->view->cache(true,$this->cache)->load($this->name));
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function test_disable_cache()
    {
        $this->assertNotEmpty($this->view->cache(false)->load($this->name));
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

    /**
     * @throws \Twig_Error_Loader
     */
    public function test_path()
    {
        $this->assertContains('views/a',$this->view->add_path('a')->paths());
    }
}