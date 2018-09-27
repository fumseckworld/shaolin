<?php

namespace Testing;


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
}