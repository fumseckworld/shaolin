<?php

namespace tests\form;


use Imperium\Html\Form\Form;
use Testing\DatabaseTest;

class FormTest extends DatabaseTest
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
        $form =  form('a','a','','POST',false)->hide()->input(Form::HIDDEN,'id','')->end_hide()->get();
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
        $form =  form('a','a')->large()->input(Form::TEXT,'sql','sql file')->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->small()->input(Form::TEXT,'sql','sql file')->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->large()->select('table',[1,2,3])->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->small()->select('table',[1,2,3])->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->large()->textarea('table','a',10,10)->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->small()->textarea('table','a',10,10)->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

        $form =  form('a','a')->large()->file('table','a')->get();
        $this->assertContains(Form::LARGE_CLASS,$form);
        $this->assertNotContains(Form::SMALL_CLASS,$form);

        $form =  form('a','a')->small()->file('table','a')->get();
        $this->assertContains(Form::SMALL_CLASS,$form);
        $this->assertNotContains(Form::LARGE_CLASS,$form);

    }

    public function test_checkbox()
    {
        $class = 'form-control';
        $form = form('a','a')->checkbox('super','check me')->get();
        $this->assertContains('name="super"',$form);
        $this->assertContains('check me',$form);
        $this->assertNotContains($class,$form);
        $this->assertNotContains('checked',$form);

        $form = form('a','a')->checkbox('super','check me',$class)->get();
        $this->assertContains('name="super"',$form);
        $this->assertContains('check me',$form);
        $this->assertContains($class,$form);
        $this->assertNotContains('checked',$form);

        $form = form('a','a')->checkbox('super','check me',$class,true)->get();
        $this->assertContains('name="super"',$form);
        $this->assertContains('check me',$form);
        $this->assertContains($class,$form);
        $this->assertContains('checked',$form);
    }


    public function test_radio()
    {

        $form = form('a','a')->radio('super','check me')->get();
        $this->assertContains('name="super"',$form);
        $this->assertContains('check me',$form);
        $this->assertNotContains('checked',$form);

        $form = form('a','a')->radio('super','check me',true)->get();
        $this->assertContains('name="super"',$form);
        $this->assertContains('check me',$form);
        $this->assertContains('checked',$form);
    }

    /**
     * @throws \Exception
     */
    public function test_generate()
    {
        $form = form('a','a')->generate(2,$this->table,$this->mysql()->tables(),'append','btn-primary',"submit-id");

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertNotEmpty($form);

        $icon = fa('fas','fa-rocket');
        $form = form('a','a')->generate(2,$this->table,$this->mysql()->tables(),'append','btn-primary',"submit-id",$icon);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->mysql()->tables(),'append','btn-primary',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->postgresql()->tables(),'append','btn-primary',"submit-id");

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertNotEmpty($form);

        $icon = fa('fas','fa-rocket');
        $form = form('a','a')->generate(2,$this->table,$this->postgresql()->tables(),'append','btn-primary',"submit-id",$icon);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->postgresql()->tables(),'append','btn-primary',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->sqlite()->tables(),'append','btn-primary',"submit-id");

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertNotEmpty($form);

        $icon = fa('fas','fa-rocket');
        $form = form('a','a')->generate(2,$this->table,$this->sqlite()->tables(),'append','btn-primary',"submit-id",$icon);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->sqlite()->tables(),'append','btn-primary',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);


        $form = form('a','a')->generate(2,$this->table,$this->mysql()->tables(),'append','',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn "',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->postgresql()->tables(),'append','',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn "',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);

        $form = form('a','a')->generate(2,$this->table,$this->sqlite()->tables(),'append','',"submit-id",$icon,Form::EDIT,1);

        $this->assertContains('class="btn "',$form);
        $this->assertContains('id="submit-id"',$form);
        $this->assertContains('id="a"',$form);
        $this->assertContains('append',$form);
        $this->assertContains($icon,$form);
        $this->assertNotEmpty($form);
    }

    public function test_link()
    {
        $icon = fa('fas','fa-home');
        $form = \form('a','a')->link('/','btn-primary','home')->get();
        $this->assertContains('class="btn btn-primary"',$form);
        $this->assertContains('home',$form);
        $this->assertContains('href="/"',$form);
        $this->assertNotContains($icon,$form);
        $form = \form('a','a')->link('/','','home')->get();
        $this->assertNotContains($icon,$form);
        $this->assertContains('home',$form);
        $this->assertContains('href="/"',$form);
        $form = \form('a','a')->link('/','','home',$icon)->get();
        $this->assertContains($icon,$form);
        $this->assertContains('home',$form);
        $this->assertContains('href="/"',$form);
    }
    /**
     * @throws \Exception
     */
    public function test_validation_exception()
    {
        $this->expectException(\Exception::class);

        form('a','a')->validate()->input(Form::TEXT,'a','a')->get();
        form('a','a')->validate()->select('a',['1',2,3])->get();
        form('a','adz')->validate()->textarea('a','adza',10,10)->get();
    }   
}