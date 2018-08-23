<?php

namespace tests;

use Exception;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Html\Form\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{


    public function testGetAndEndMethod()
    {
        $this->assertNotEmpty(form('a','a')->get());
        $this->assertNotEmpty(form('a','a')->end());
    }

    public function testAutoCol()
    {
        $this->assertContains(Form::AUTO_COL,form('a','a')->input(Form::TEXT,'name','name')->input(Form::NUMBER,'age','age')->get());
    }

    public function testFormSeparator()
    {
        $this->assertContains(Form::FORM_SEPARATOR,form('a','a')->input(Form::TEXT,'name','name')->get());
    }

    public function testStartAndEnd()
    {
        $this->assertStringStartsWith('<form ',form('a','a') ->get());
        $this->assertStringEndsWith('</form>',form('a','a')->get());
    }

    public function testRow()
    {
        $this->assertContains(Form::GRID_ROW,form('a','a')->startRow()->input(Form::NUMBER,'name','name')->endRow()->get());
    }

    public function testInstance()
    {
        $form = form('a','a');

        $this->assertInstanceOf(Form::class,$form);

        $this->assertInstanceOf(Form::class,$form->startRow()->input(Form::NUMBER,'age','age')->endRowAndNew());
        $this->assertInstanceOf(Form::class,$form->input(Form::NUMBER,'age','age') );
        $this->assertInstanceOf(Form::class,$form->endRow());
        $this->assertInstanceOf(Form::class,$form->startRow()->select('table',['a','a']));
        $this->assertInstanceOf(Form::class,$form->endRowAndNew());
        $this->assertInstanceOf(Form::class,$form->redirectSelect('sites',['google' => 'google']));
        $this->assertNotEmpty($form->get());
    }
    public function testSelect()
    {
        $data = ['a','b','c'];

        $large = 'form-control-lg';
        $small = 'form-control-sm';

        $icon = fa('fa-user');
        $this->assertContains('<option value="a">a</option>',form('a','a')->select('a',$data)->end());
        $this->assertContains($icon,form('a','a')->select('a',$data,$icon)->end());
        $this->assertContains($icon,form('a','a')->select('a',$data,$icon,true)->end());
        $this->assertContains('<option value="b">b</option>',form('a','a')->select('a',$data)->end());
        $this->assertContains('<option value="c">c</option>',form('a','a')->select('a',$data)->end());
        $this->assertContains('multiple',form('a','a')->select('a',$data,'',true)->end());
        $this->assertNotContains('multiple',form('a','a')->select('a',$data,'',false)->end());
        $this->assertNotContains($large,form('a','a')->select('a',$data,'',false)->end());
        $this->assertNotContains($small,form('a','a')->select('a',$data,'',false)->end());
        $this->assertContains($small,form('a','a')->setSmallInput(true)->select('a',$data,'',false)->end());
        $this->assertContains($large,form('a','a')->setLargeInput(true)->select('a',$data,'',false)->end());
        $this->assertContains(Form::CUSTOM_SELECT_CLASS,form('a','a')->setLargeInput(true)->select('a',$data,'',false)->end());
        $this->assertContains(Form::CUSTOM_SELECT_CLASS,form('a','a')->setSmallInput(true)->select('a',$data,'',false)->end());
        $this->assertContains(Form::CUSTOM_SELECT_CLASS,form('a','a')->select('a',$data,'',false)->end());
    }


    public function testRedirect()
    {
        $data = ['a','b','c'];
        $this->assertContains('onChange="location = ',form('a','a')->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::LARGE_CLASS, form('a','a')->setLargeInput(true)->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::SMALL_CLASS, form('a','a')->setSmallInput(true)->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::BASIC_CLASS, form('a','a')->setSmallInput(true)->setLargeInput(false)->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::BASIC_CLASS, form('a','a')->setLargeInput(true)->setSmallInput(false)->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::LARGE_CLASS, form('a','a')->setSmallInput(true)->setLargeInput(true)->redirectSelect('a',$data)->end()) ;
        $this->assertContains(Form::SMALL_CLASS, form('a','a')->setLargeInput(true)->setSmallInput(true)->redirectSelect('a',$data)->end()) ;
    }

    public function testCustomMethod()
    {
        $data =Form::GET;
        $this->assertContains('method="get"',form('a','a',$data)->end()) ;
        $this->assertContains('method="post"',form('a','a')->end()) ;
    }

    public function testInputData()
    {

        $types = [Form::TEXT,Form::NUMBER,Form::HIDDEN,Form::PASSWORD,Form::PASSWORD];
        foreach ($types as $type)
        {
            $form = form('a','a');
            $placeholder = faker()->text(10);
            $name = faker()->text(10);
            $form->input($type,$name,$placeholder);
            $this->assertContains('name="'.$name.'"',$form->get());
            $this->assertContains('placeholder="'.$placeholder.'"',$form->get());

        }
    }
    public function testStart()
    {
        $id = 'a';
        $method = 'post';
        $action = 'index.php';
        $charset = 'utf8';
        $this->assertNotContains('class="',form($action,$id,'get','',true)->end());
        $this->assertNotContains('class="',form($action,$id)->end());
        $this->assertEquals('<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" id="' . $id .'"></form>',form($action,$id)->end());
        $this->assertEquals('<form action="' . $action . '" method="get" accept-charset="' . $charset . '" id="' . $id .'"></form>',form($action,$id,'get')->end());
        $this->assertEquals('<form action="' . $action . '" method="get" accept-charset="' . $charset . '" class="form-inline" id="' . $id .'"></form>',form($action,$id,'get','form-inline')->end());
        $this->assertEquals('<form action="' . $action . '" method="get" accept-charset="' . $charset . '" class="form-inline" id="' . $id .'" enctype="multipart/form-data"></form>',form($action,$id,'get','form-inline',true)->end());
        $this->assertEquals('<form action="' . $action . '" method="get" accept-charset="ch" class="form-inline" id="' . $id . '" enctype="multipart/form-data"></form>',form($action,$id,'get','form-inline',true,'ch')->end());
    }

    public function testHide()
    {
        $this->assertContains('<div class="'.Form::HIDE_CLASS.'"></div>',form('a','a')->startHide()->endHide()->get());
    }

    public function testFile()
    {
        $icon = fa('fa-user');
        $this->assertNotContains($icon,\form('a','a')->file('a','a')->get());
        $this->assertContains($icon,form('a','a')->file('a','a','fr',$icon)->get());
        $this->assertContains('type="file"',form('a','a')->file('a','a','fr',$icon)->get());
        $this->assertContains('type="file"',form('a','a')->file('a','a')->get());

    }

    public function testInputWithIcon()
    {
        $icon = fa('fa-linux');
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon)->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem')->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',true)->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',false )->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',true,true)->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',true,true,true)->get());

        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',false,false)->get());
        $this->assertContains($icon,form('a','a')->input(Form::TEXT,'a','a',$icon,'lorem',false,false,false)->get());
    }

    public function testGenerateInput()
    {
        $fileClass ='form-control-file';
        $icon = fa('fa-user');
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'')->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'lorme')->get());
        
        $this->assertContains(Form::SMALL_CLASS,form('a','a')->setSmallInput(true)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setSmallInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setSmallInput(false)->setLargeInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::LARGE_CLASS,form('a','a')->setSmallInput(false)->setLargeInput(true)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setSmallInput(true)->setLargeInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::LARGE_CLASS,form('a','a')->setSmallInput(true)->setLargeInput(true)->input('a','a','a',$icon,'lorme')->get());
       
        
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setLargeInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::LARGE_CLASS,form('a','a')->setLargeInput(true)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setLargeInput(false)->setSmallInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::SMALL_CLASS,form('a','a')->setLargeInput(false)->setSmallInput(true)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::BASIC_CLASS,form('a','a')->setLargeInput(true)->setSmallInput(false)->input('a','a','a',$icon,'lorme')->get());
        $this->assertContains(Form::SMALL_CLASS,form('a','a')->setLargeInput(true)->setSmallInput(true)->input('a','a','a',$icon,'lorme')->get());
         

        $this->assertContains($fileClass,form('a','a')->setSmallInput(true)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setSmallInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setSmallInput(false)->setLargeInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setSmallInput(false)->setLargeInput(true)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setSmallInput(true)->setLargeInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setSmallInput(true)->setLargeInput(true)->input('file','a','a',$icon,'lorme')->get());


        $this->assertContains($fileClass,form('a','a')->setLargeInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setLargeInput(true)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setLargeInput(false)->setSmallInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setLargeInput(false)->setSmallInput(true)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setLargeInput(true)->setSmallInput(false)->input('file','a','a',$icon,'lorme')->get());
        $this->assertContains($fileClass,form('a','a')->setLargeInput(true)->setSmallInput(true)->input('file','a','a',$icon,'lorme')->get());



     
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',false,false,false)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',false,false,true)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',false,true,false)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',false,true,true)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',true,false,false)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',true,false,true)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',true,true,false)->get());
        $this->assertNotEmpty(form('a','a')->input('a','a','a',$icon,'',true,true,true)->get());
    }

    public function testButton()
    {
        $icon = fa('fa-linux');
        $class = 'btn btn-outline-primary';
        $text = 'empty';
        $form = form('a','a')->reset($text,$class)->get();
        $this->assertContains(Form::RESET,$form);
        $this->assertContains($class,$form);
        $this->assertContains($text,$form);

        $form = form('a','a')->button(Form::RESET,$text,$class)->get();
        $this->assertContains(Form::RESET, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);

        $form = form('a','a')->button(Form::SUBMIT,$text,$class)->get();
        $this->assertContains(Form::SUBMIT, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);

        $form = form('a','a')->button(Form::BUTTON,$text,$class)->get();
        $this->assertContains(Form::BUTTON, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);


        $form = form('a','a')->button(Form::RESET,$text,$class,$icon)->get();
        $this->assertContains(Form::RESET, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);
        $this->assertContains($icon, $form);

        $form = form('a','a')->button(Form::SUBMIT,$text,$class,$icon)->get();
        $this->assertContains(Form::SUBMIT, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);
        $this->assertContains($icon, $form);

        $form = form('a','a')->button(Form::BUTTON,$text,$class,$icon)->get();
        $this->assertContains(Form::BUTTON, $form);
        $this->assertContains($class, $form);
        $this->assertContains($text, $form);
        $this->assertContains($icon, $form);

    }

    public function testTextarea()
    {
        $form =  form('a','a')->textarea('a','a',10,10)->get();

        $this->assertContains('10',$form);
        $this->assertContains('a',$form);
        $this->assertNotContains('autofocus',$form);
        $form =  form('a','a')->textarea('a','a',10,10,true)->get();
        $this->assertContains('10',$form);
        $this->assertContains('a',$form);
        $this->assertContains('autofocus',$form);
    }

    public function testImg()
    {
        $src= 'imperium.jpg';
        $alt= 'imperium';
        $form =  form('a','a')->img($src,$alt)->get();

        $this->assertContains('a',$form);
        $this->assertContains($src,$form);
        $this->assertContains($src,$form);
        $this->assertNotContains('class="thumbnail"',$form);

        $form =  form('a','a')->img('imperium.jpg','imperium','thumbnail')->get();

        $this->assertContains('a',$form);
        $this->assertContains($src,$form);
        $this->assertContains($src,$form);
        $this->assertContains('class="thumbnail"',$form);

        $form =  form('a','a')->img('imperium.jpg','imperium','thumbnail','80%')->get();

        $this->assertContains('a',$form);
        $this->assertContains($src,$form);
        $this->assertContains($src,$form);
        $this->assertContains('class="thumbnail"',$form);
        $this->assertContains('width="80%"',$form);

    }

    public function testLink()
    {
        $url = 'https://git.fumseck.eu/cgit/imperium';
        $text ='source code';
        $class ='btn btn-primary';
        $form = form('a','a')->link($url,$class,$text)->get();
        $this->assertContains($text,$form);
        $this->assertContains($class,$form);
        $this->assertContains($url,$form);
    }

    public function testRadioAndCheckbox()
    {
        $form = form('a','a')->radio('a','checked','class',true)->get();

        $this->assertContains('checked="checked"',$form);
        $this->assertContains('a',$form);
        $this->assertContains('class',$form);

        $form = form('a','a')->radio('a','checked','class',false)->get();
        $this->assertNotContains('checked="checked"',$form);
        $this->assertContains('a',$form);
        $this->assertContains('class',$form);

        $form = form('a','a')->checkbox('a','checked','class',true)->get();

        $this->assertContains('checked="checked"',$form);
        $this->assertContains('a',$form);
        $this->assertContains('class',$form);

        $form = form('a','a')->checkbox('a','checked','class',false)->get();
        $this->assertNotContains('checked="checked"',$form);
        $this->assertContains('a',$form);
        $this->assertContains('class',$form);


    }

    /**
     * @throws \Exception
     */
    public function testGenerate()
    {
        $table = 'doctors';
        $base = 'zen';
        $user = 'root';
        $class = 'btn btn-primary';
        $icon = fa('fa-user');
        $instance = table(Connexion::MYSQL,$base,$user,$user,'');
        $form = form('a','a')->generate($table,$instance,'submit',$class,'id');

        $this->assertNotEmpty($form);
        $this->assertContains('submit',$form);
        $this->assertNotContains($icon,$form);
        $this->assertContains('id',$form);
        $this->assertContains('a',$form);
        $this->assertContains($class,$form);
        $this->assertContains('id',$form);
        $this->assertStringStartsWith('<form',$form);
        $this->assertStringEndsWith('</form>',$form);

         $form = form('a','a')->generate($table,$instance,'submit',$class,'id',$icon);

        $this->assertNotEmpty($form);
        $this->assertContains('submit',$form);
        $this->assertContains($icon,$form);
        $this->assertContains('id',$form);
        $this->assertContains('a',$form);
        $this->assertContains($class,$form);
        $this->assertContains('id',$form);
        $this->assertStringStartsWith('<form',$form);
        $this->assertStringEndsWith('</form>',$form);

        $form = form('a','a')->generate($table,$instance,'submit',$class,'id','',Form::EDIT,1);

        $this->assertNotEmpty($form);
        $this->assertNotContains($icon,$form);
        $this->assertContains('submit',$form);
        $this->assertContains('id',$form);
        $this->assertContains('a',$form);
        $this->assertContains($class,$form);
        $this->assertContains('id',$form);
        $this->assertStringStartsWith('<form',$form);
        $this->assertStringEndsWith('</form>',$form);

        $form = form('a','a')->generate($table,$instance,'submit',$class,'id',$icon,Form::EDIT,1);

        $this->assertNotEmpty($form);
        $this->assertContains($icon,$form);
        $this->assertContains('submit',$form);
        $this->assertContains('id',$form);
        $this->assertContains('a',$form);
        $this->assertContains($class,$form);
        $this->assertContains('id',$form);
        $this->assertStringStartsWith('<form',$form);
        $this->assertStringEndsWith('</form>',$form);

        $this->expectException(Exception::class);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,Form::EDIT,88888);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,Form::EDIT,854);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,Form::EDIT,356);

    }

    /**
     * @throws Exception
     */
    public function testWithNoValidMode()
    {   $table = 'doctors';
        $base = 'zen';
        $user = 'root';
        $class = 'btn btn-primary';
        $icon = fa('fa-user');
        $instance = table(Connexion::MYSQL,$base,$user,$user,'');
        $this->expectException(Exception::class);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,888,356);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,700,356);
        form('a','a')->generate($table,$instance,'a',$class,'a',$icon,300,356);
    }
}