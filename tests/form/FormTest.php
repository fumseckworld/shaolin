<?php

namespace tests\form;


use Imperium\Html\Form\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{

    public function test_start()
    {
        $class = 'form-horizontal';

        $form =  form('a','a','','POST',true)->get();
        $this->assertContains("enctype",$form);
        $this->assertContains('method="post"',$form);
        $this->assertContains('action="a"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertNotContains($class,$form);

        $form =  form('a','a',$class,'POST',true)->get();
        $this->assertContains("enctype",$form);
        $this->assertContains('method="post"',$form);
        $this->assertContains('action="a"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains($class,$form);


        $form =  form('a','a','','POST',false)->get();
        $this->assertNotContains("enctype",$form);
        $this->assertContains('method="post"',$form);
        $this->assertContains('action="a"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertNotContains($class,$form);

        $form =  form('a','a',$class,'POST',false)->get();
        $this->assertNotContains("enctype",$form);
        $this->assertContains('method="post"',$form);
        $this->assertContains('action="a"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains($class,$form);

    }

    /**
     * @throws \Exception
     */
    public function test_hide()
    {
        $form =  form('a','a','','POST',false)->startHide()->input(Form::HIDDEN,'id','')->endHide()->get();
        $this->assertContains(Form::HIDE_CLASS,$form);
        $this->assertContains(Form::HIDDEN,$form);
        $this->assertStringEndsWith('</div></form>',$form);

    }

    /**
     * @throws \Exception
     */
    public function test_file()
    {
        $ico = fa('fas','fa-file');
        $form =  form('a','a','','POST',false)->file('sql','sql file')->get();
        $this->assertContains('name="sql"',$form);
        $this->assertContains('sql file',$form);
        $this->assertNotContains($ico,$form);

        $form =  form('a','a','','POST',false)->file('sql','sql file',$ico)->get();

        $this->assertContains('name="sql"',$form);
        $this->assertContains('sql file',$form);
        $this->assertContains($ico,$form);
    }

    /**
     * @throws \Exception
     */
    public function test_size()
    {
        $form =  form('a','a')->setLargeInput(true)->input(Form::TEXT,'sql','sql file')->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->setSmallInput(true)->input(Form::TEXT,'sql','sql file')->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->setLargeInput(true)->select('table',[1,2,3])->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->setSmallInput(true)->select('table',[1,2,3])->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->setLargeInput(true)->textarea('table','a',10,10)->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->setSmallInput(true)->textarea('table','a',10,10)->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->setLargeInput(true)->file('table','a')->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->setSmallInput(true)->file('table','a')->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

    }
}