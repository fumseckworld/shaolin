<?php
/**
 * Created by PhpStorm.
 * User: fumseck
 * Date: 12/03/18
 * Time: 12:03
 */

namespace tests;


use Exception;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Html\Form\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
 
    
    private $class = 'btn btn-primary';

    private $icon = '<i class="fa fa-user">';

 

    public function testStartAndEnd()
    {

        $html = form()->start('/','demo')->end();
        $this->assertContains('/',$html);
        $this->assertContains('post',$html);
        $this->assertContains('utf8',$html);
        $this->assertStringStartsWith('<form',$html);
        $this->assertStringEndsWith('</form>',$html);

        $html = form(2)->start('/','demo')->end();
        $this->assertContains('/',$html);
        $this->assertContains('post',$html);
        $this->assertContains('utf8',$html);
        $this->assertStringStartsWith('<form',$html);
        $this->assertStringEndsWith('</form>',$html);
    }

    public function testHide()
    {
        $boot = form()->start('/','demo')->startHide()->endHide()->end();
        $foundation = form(2)->start('/','demo')->startHide()->endHide()->end();
        $this->assertContains('d-none',$boot);
        $this->assertContains('hide',$foundation);
        $this->assertContains('</div>',$boot);
        $this->assertContains('</div>',$foundation);

    }

    public function testFile()
    {
        $bootHtmlWithoutIcon = form()->start('/','demo')->file('avatar',$this->class,'upload')->end();
        $bootHtmlWithIcon = form()->start('/','demo')->file('avatar',$this->class,'upload',$this->icon)->end();

        $foundationWithoutIcon  = form(2)->start('/','demo')->file('avatar',$this->class,'upload')->end();
        $foundationWithIcon     = form(2)->start('/','demo')->file('avatar',$this->class,'upload',$this->icon)->end();

        $this->assertContains('avatar',$bootHtmlWithIcon);
        $this->assertContains('avatar',$bootHtmlWithoutIcon);
        $this->assertContains('avatar',$foundationWithIcon);
        $this->assertContains('avatar',$foundationWithoutIcon);

        $this->assertContains($this->class,$bootHtmlWithIcon);
        $this->assertContains($this->class,$bootHtmlWithoutIcon);
        $this->assertContains($this->class,$foundationWithIcon);
        $this->assertContains($this->class,$foundationWithoutIcon);

        $this->assertContains('upload',$bootHtmlWithIcon);
        $this->assertContains('upload',$bootHtmlWithoutIcon);
        $this->assertContains('upload',$foundationWithIcon);
        $this->assertContains('upload',$foundationWithoutIcon);

        $this->assertContains($this->icon,$bootHtmlWithIcon);
        $this->assertNotContains($this->icon,$bootHtmlWithoutIcon);

        $this->assertContains($this->icon,$foundationWithIcon);
        $this->assertNotContains($this->icon,$foundationWithoutIcon);

    }


    public function testInput()
    {

        $bootHtmlWithoutIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->end();
        $foundationWithoutIcon  = form(2)->start('/','demo')->input(Form::TEXT,'username','Username')->end();

        $bootHtmlWithIcon   = form()->start('/','demo')->input(Form::TEXT,'username','Username',$this->icon)->end();
        $foundationWithIcon = form(2)->start('/','demo')->input(Form::TEXT,'username','Username',$this->icon)->end();

        $this->assertContains('username',$bootHtmlWithIcon);
        $this->assertContains('username',$bootHtmlWithoutIcon);
        $this->assertContains('username',$foundationWithIcon);
        $this->assertContains('username',$foundationWithoutIcon);

        $this->assertContains('text',$bootHtmlWithIcon);
        $this->assertContains('text',$bootHtmlWithoutIcon);
        $this->assertContains('text',$foundationWithIcon);
        $this->assertContains('text',$foundationWithoutIcon);

        $this->assertContains('Username',$bootHtmlWithIcon);
        $this->assertContains('Username',$bootHtmlWithoutIcon);
        $this->assertContains('Username',$foundationWithIcon);
        $this->assertContains('Username',$foundationWithoutIcon);

        $this->assertContains($this->icon,$bootHtmlWithIcon);
        $this->assertNotContains($this->icon,$bootHtmlWithoutIcon);

        $this->assertContains($this->icon,$foundationWithIcon);
        $this->assertNotContains($this->icon,$foundationWithoutIcon);

    }

    public function testSubmit()
    {

        $bootHtmlWithoutIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,'d')->end();
        $foundationWithoutIcon    = form(2)->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,'d')->end();

        $bootHtmlWithIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,fa('fa-send'))->end();
        $foundationWithIcon  = form(2)->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,fa('fa-send'))->end();

        $this->assertContains('submit',$bootHtmlWithIcon);
        $this->assertContains('submit',$bootHtmlWithoutIcon);
        $this->assertContains('submit',$foundationWithIcon);
        $this->assertContains('submit',$foundationWithoutIcon);

        $this->assertContains($this->class,$bootHtmlWithIcon);
        $this->assertContains($this->class,$bootHtmlWithoutIcon);
        $this->assertContains($this->class,$foundationWithIcon);
        $this->assertContains($this->class,$foundationWithoutIcon);

        $this->assertContains(fa('fa-send'),$bootHtmlWithIcon);
        $this->assertNotContains(fa('fa-send'),$bootHtmlWithoutIcon);
        $this->assertContains(fa('fa-send'),$foundationWithIcon);
        $this->assertNotContains(fa('fa-send'),$foundationWithoutIcon);
    }

    public function testTextarea()
    {
        $boot       = form()->start('/','demo')->textarea('bio','biography',10,15)->end();
        $foundation = form(2)->start('/','demo')->textarea('bio','biography',10,15)->end();

        $bootAuto = form()->start('/','demo')->textarea('bio','biography',10,15,true)->end();
        $foundationAuto = form(2)->start('/','demo')->textarea('bio','biography',10,15,true)->end();

        $this->assertContains('bio',$boot);
        $this->assertContains('bio',$foundation);

        $this->assertContains('biography',$boot);
        $this->assertContains('biography',$foundation);

        $this->assertContains('10',$boot);
        $this->assertContains('10',$foundation);

        $this->assertContains('15',$boot);
        $this->assertContains('15',$foundation);

        $this->assertContains('autofocus="autofocus"',$bootAuto);
        $this->assertContains('autofocus="autofocus"',$foundationAuto);
    }

    public function testCsrf()
    {
        $csrf = "<input type='hidden' value='#ffee00'>";
        $boot       = form()->start('/','demo')->csrf($csrf)->textarea('bio','biography',10,15)->end();
        $foundation = form(2)->start('/','demo')->csrf($csrf)->textarea('bio','biography',10,15)->end();

        $this->assertContains($csrf,$boot);
        $this->assertContains($csrf,$foundation);
    }

    public function testReset()
    {
        $boot       = form()->start('/','demo')->reset('clear',$this->class)->textarea('bio','biography',10,15)->end();
        $foundation = form(2)->start('/','demo')->reset('clear',$this->class)->textarea('bio','biography',10,15)->end();

        $this->assertContains('clear',$boot);
        $this->assertContains('clear',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);
    }
    
    public function testLink()
    {
        $boot       = form()->start('/','demo')->link('/register',$this->class,'create an account')->end();
        $foundation = form(2)->start('/','demo')->link('/register',$this->class,'create an account')->end();
        
        $this->assertContains('/register',$boot);
        $this->assertContains('/register',$foundation);

        $this->assertContains('create an account',$boot);
        $this->assertContains('create an account',$foundation);
        
        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $boot       = form()->start('/','demo')->link('/register',$this->class,'create an account',$this->icon)->end();
        $foundation = form(2)->start('/','demo')->link('/register',$this->class,'create an account',$this->icon)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);
    }

    public function testSelect()
    {
        $users = array('marc','antoine','marion','alexandre');

        $boot       = form()->start('/','demo')->select('users',$users)->end();
        $foundation = form(2)->start('/','demo')->select('users',$users)->end();

        $this->assertContains('marc',$boot);
        $this->assertContains('antoine',$boot);
        $this->assertContains('alexandre',$boot);
        $this->assertContains('marc',$foundation);
        $this->assertContains('antoine',$foundation);
        $this->assertContains('alexandre',$foundation);

        $boot       = form()->start('/','demo')->select('users',$users,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->select('users',$users,$this->icon)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);
    }
    public function testTwoInput()
    {
        $boot             = form()->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();
        $foundation       = form(2)->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();

        $this->assertContains(Form::TEXT,$boot);
        $this->assertContains(Form::EMAIL,$boot);
        $this->assertContains(Form::TEXT,$foundation);
        $this->assertContains(Form::EMAIL,$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);
        $this->assertContains('username',$foundation);

        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$foundation);
        $this->assertContains('Email',$foundation);
    }
    public function testEnctype()
    {
        $this->assertContains('enctype',form(Form::BOOTSTRAP)->start('/','','',true)->end());
        $this->assertContains('enctype',form(Form::FOUNDATION)->start('/','','',true)->end());
    }

    public function testTwoInlineInput()
    {
        $boot             = form()->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();
        $foundation       = form(2)->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('email',$boot);
        $this->assertContains('email',$foundation);

        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);
    }

    public function testTwoSelect()
    {
        $users = array('marc','alex','jupiter');
        $capital = array('1111','98956','2325');

        $boot             = form()->start('/','demo')->twoInlineSelect('users',$users,$this->icon,'capitals',$capital,$this->icon)->end();
        $foundation       = form(2)->start('/','demo')->twoInlineSelect('users',$users,$this->icon,'capitals',$capital,$this->icon)->end();

        foreach ($users as $k => $user)
        {
            $this->assertContains($this->icon,$boot);
            $this->assertContains($this->icon,$foundation);
            $this->assertContains($user,$boot);
            $this->assertContains($capital[$k],$boot);
            $this->assertContains($user,$foundation);
            $this->assertContains($capital[$k],$foundation);
        }


    }
    public function OneInputAndSelect()
    {
        $bases = array('marc','alex','jupiter');

        $boot             = form()->start('/','demo')->oneInputOneSelect('text','user','username',true,$this->icon,'','bases',$bases,$this->icon)->end();
        $foundation      = form(2)->start('/','demo')->oneInputOneSelect('text','user','username',true,$this->icon,'','bases',$bases,$this->icon)->end();


        foreach ($bases as $base)
        {
            $this->assertContains($this->icon,$boot);
            $this->assertContains($this->icon,$foundation);

            $this->assertContains('user',$boot);
            $this->assertContains('user',$foundation);

            $this->assertContains('username',$boot);
            $this->assertContains('username',$foundation);

            $this->assertContains('text',$boot);
            $this->assertContains('text',$foundation);

            $this->assertContains('bases',$boot);
            $this->assertContains('bases',$foundation);

            $this->assertContains($base,$boot);
            $this->assertContains($base,$foundation);

        }


    }

    public function testThreeInput()
    {
        $boot             = form()->start('/','demo')->threeInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true,Form::COLOR,'color','','#FF0000','',true)->end();
        $foundation       = form(2)->start('/','demo')->threeInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true,Form::COLOR,'color','','#FF0000','',true)->end();


        $this->assertContains(Form::TEXT,$boot);
        $this->assertContains(Form::EMAIL,$boot);
        $this->assertContains(Form::COLOR,$boot);
        $this->assertContains(Form::COLOR,$foundation);
        $this->assertContains(Form::TEXT,$foundation);
        $this->assertContains(Form::EMAIL,$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);
        $this->assertContains('username',$foundation);

        $this->assertContains('email',$boot);
        $this->assertContains('email',$boot);
        $this->assertContains('email',$foundation);
        $this->assertContains('email',$foundation);

        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$foundation);
        $this->assertContains('Email',$foundation);

        $this->assertContains('color',$boot);
        $this->assertContains('color',$boot);
        $this->assertContains('color',$foundation);
        $this->assertContains('color',$foundation);

        $this->assertContains('#FF0000',$boot);
        $this->assertContains('#FF0000',$foundation);

    }

    public function testFourInput()
    {
        $boot             = form()->start('/','demo')->fourInlineInput('text','four1','four','','',true,'text','four2','four','supersonic','',true,'text','four3','four','','',true,'text','four4','for','','',true)->end();
        $foundation       = form(2)->start('/','demo')->fourInlineInput('text','four1','four','','',true,'text','four2','four','supersonic','',true,'text','four3','four','','',true,'text','four4','for','','',true)->end();


         $this->assertContains('four',$boot);
         $this->assertContains('four',$foundation);

         $this->assertContains('supersonic',$boot);
         $this->assertContains('supersonic',$foundation);

         $this->assertContains('required',$boot);
         $this->assertContains('required',$foundation);

    }

    public function testGenerate()
    {

        // DEFAULT

        $boot             = form()->start('/','demo')->setLargeInput(true)->input('text','code','name')->end();

        $this->assertContains('form-control form-control-lg',$boot);

        $boot             = form()->start('/','demo')->setSmallInput(true)->input('text','code','name')->end();

        $this->assertContains('form-control form-control-sm',$boot);

        $boot             = form()->start('/','demo')->setSmallInput(true)->input('file','code','name')->end();

        $this->assertContains('form-control-file',$boot);

        // DEFAULT

        $boot             = form()->start('/','demo')->input('text','code','name')->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name')->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        // AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,false,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',false,false,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);

        //  AUTOFOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,true,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',false,true,false)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);

        //  AUTOFOCUS AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,true,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',false,true,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);


        //  REQUIRE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',true,false,false)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        //  REQUIRE AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',true,false,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);



        //  REQUIRE AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',true,false,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        //  REQUIRE AUTOFOCUS AND AUTOCOMPLETE  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,true,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name','','',true,true,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        // ICON  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,false,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',false,false,false)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);

        // ICON AUTOCOMPLETE  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,false,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',false,false,true)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);

        // ICON AUTO FOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,true,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',false,true,false)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);


        // ICON AUTO FOCUS AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,true,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',false,true,true)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertNotContains('required',$boot);
        $this->assertNotContains('required',$foundation);


        // ICON REQUIRED ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,false,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',true,false,false)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        // ICON REQUIRED  AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,false,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',true,false,true)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertNotContains('autofocus',$boot);
        $this->assertNotContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        // ICON REQUIRED  AUTO FOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,true,false)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',true,true,false)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="off"',$boot);
        $this->assertContains('autocomplete="off"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


        // ICON REQUIRED  AUTO FOCUS AND AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,true,true)->end();
        $foundation       = form(2)->start('/','demo')->input('text','code','name',$this->icon,'',true,true,true)->end();

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('autofocus',$boot);
        $this->assertContains('autofocus',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('code',$boot);
        $this->assertContains('code',$foundation);

        $this->assertContains('autocomplete="on"',$boot);
        $this->assertContains('autocomplete="on"',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);


    }

    /**
     * @throws Exception
     */
    public function testGenerateWithoutRecord()
    {

        $drivers = array(Connexion::MYSQL,Connexion::POSTGRESQL,Connexion::SQLITE);
        foreach ($drivers as $driver)
        {
            switch ($driver)
            {
                case Connexion::MYSQL:
                     $this->expectException(Exception::class);
                     form()->start('/','boot')->generate('admin',table($driver,"imperiums",'root','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
                     form(2)->start('/','boot')->generate('admin',table($driver,"imperiums",'root','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
                     form(2)->start('/','boot')->generate('admin',table($driver,"imperiums",'root','',''),'submit',$this->class,'sid',$this->icon,'azd',3);
                     form()->start('/','boot')->generate('admin',table($driver,"imperiums",'root','',''),'submit',$this->class,'sid',$this->icon,'azd',3);

                break;
                case Connexion::POSTGRESQL:
                    $this->expectException(Exception::class);
                    form()->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
                    form(2)->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);

                    form()->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,'adz',3);
                    form(2)->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,'adz',3);
                break;
                default:
                    $this->expectException(Exception::class);
                    form()->start('/','boot')->generate('users',table($driver,"testing",'','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
                    form(2)->start('/','boot')->generate('users',table($driver,"testing",'','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
                break;
            }

        }



    }
    public function testButton()
    {
        $types = array(Form::BUTTON,Form::RESET,FORM::SUBMIT);
        foreach ($types as $type)
        {
            $boot             = form()->start('/','demo')->button('alex',$this->class,$this->icon,$type)->end();
            $foundation       = form(2)->start('/','demo')->button('alex',$this->class,$this->icon,$type)->end();

            $this->assertContains('alex',$boot);
            $this->assertContains('alex',$foundation);

            $this->assertContains($this->class,$boot);
            $this->assertContains($this->class,$foundation);

            $this->assertContains($this->icon,$boot);
            $this->assertContains($this->icon,$foundation);

            $this->assertContains($type,$boot);
            $this->assertContains($type,$foundation);

            $boot             = form()->start('/','demo')->button('alex',$this->class,'',$type)->end();
            $foundation       = form(2)->start('/','demo')->button('alex',$this->class,'',$type)->end();

            $this->assertContains('alex',$boot);
            $this->assertContains('alex',$foundation);

            $this->assertContains($this->class,$boot);
            $this->assertContains($this->class,$foundation);

            $this->assertNotContains($this->icon,$boot);
            $this->assertNotContains($this->icon,$foundation);

            $this->assertContains($type,$boot);
            $this->assertContains($type,$foundation);
        }
        $boot             = form()->start('/','demo')->button('alex',$this->class,$this->icon)->end();
        $foundation       = form(2)->start('/','demo')->button('alex',$this->class,$this->icon)->end();

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains('type="button"',$boot);
        $this->assertContains('type="button"',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('alex',$boot);
        $this->assertContains('alex',$foundation);
    }

    public function testImg()
    {
        $boot             = form()->start('/','demo')->img('imperiums','lion',$this->class)->end();
        $foundation       = form(2)->start('/','demo')->img('imperiums','lion',$this->class)->end();


        $this->assertContains('imperiums',$boot);
        $this->assertContains('imperiums',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains('lion',$boot);
        $this->assertContains('lion',$foundation);

        $boot             = form()->start('/','demo')->img('imperiums','lion')->end();
        $foundation       = form(2)->start('/','demo')->img('imperiums','lion')->end();


        $this->assertContains('imperiums',$boot);
        $this->assertContains('imperiums',$foundation);

        $this->assertNotContains($this->class,$boot);
        $this->assertNotContains($this->class,$foundation);

        $this->assertNotContains($this->icon,$boot);
        $this->assertNotContains($this->icon,$foundation);

        $this->assertContains('lion',$boot);
        $this->assertContains('lion',$foundation);

    }

    public function testOneInputOneSelectTwoInput()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputOneSelectTwoInput('text','name','Username','','',true,'number',$select,'','text','card','card','','',true,'number','age','age','','',true)->end();
        $foundation = form(2)->start('/','demo')->oneInputOneSelectTwoInput('text','name','Username','','',true,'number',$select,'','text','card','card','','',true,'number','age','age','','',true)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('Username',$boot);
        $this->assertContains('Username',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('card',$boot);
        $this->assertContains('card',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);
    }

    public function testOneInputOneSelectOneInputOneSelect()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputOneSelectOneInputOneSelect('text','name','username','','',true,'age',$select,$this->icon,'text','group','name','','',true,'albums',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->oneInputOneSelectOneInputOneSelect('text','name','username','','',true,'age',$select,$this->icon,'text','group','name','','',true,'albums',$select,$this->icon)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('group',$boot);
        $this->assertContains('group',$foundation);

        $this->assertContains('albums',$boot);
        $this->assertContains('albums',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('group',$boot);
        $this->assertContains('group',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);
    }

    public function testOneInputTwoSelectOneInput()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputTwoSelectOneInput('text','name','username','','',true,'age',$select,'','years',$select,'','number','card','card','','',true)->end();
        $foundation = form(2)->start('/','demo')->oneInputTwoSelectOneInput('text','name','username','','',true,'age',$select,'','years',$select,'','number','card','card','','',true)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('group',$boot);


        $this->assertContains('years',$boot);
        $this->assertContains('years',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);
    }


    public function testOneInputThreeSelect()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputThreeSelect('password','pwd','your password','',$this->icon,true,'years',$select,$this->icon,'age',$select,$this->icon,'party',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->oneInputThreeSelect('password','pwd','your password','',$this->icon,true,'years',$select,$this->icon,'age',$select,$this->icon,'party',$select,$this->icon)->end();

        $this->assertContains('password',$boot);
        $this->assertContains('password',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('pwd',$boot);
        $this->assertContains('pwd',$foundation);

        $this->assertContains('your password',$boot);
        $this->assertContains('your password',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('years',$boot);
        $this->assertContains('years',$foundation);

        $this->assertContains('party',$boot);
        $this->assertContains('party',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

    }

    public function testOneSelectThreeInput()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectThreeInput('id',$select,$this->icon,'password','pwd','your password','','',true,'text','card','card','','',true,'number','age','your age','','',true)->end();
        $foundation = form(2)->start('/','demo')->oneSelectThreeInput('id',$select,$this->icon,'password','pwd','your password','','',true,'text','card','card','','',true,'number','age','your age','','',true)->end();

        $this->assertContains('password',$boot);
        $this->assertContains('password',$foundation);

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('pwd',$boot);
        $this->assertContains('pwd',$foundation);

        $this->assertContains('your password',$boot);
        $this->assertContains('your password',$foundation);

        $this->assertContains('your age',$boot);
        $this->assertContains('your age',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('card',$boot);
        $this->assertContains('card',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

    }

    public function  testOneSelectTwoInputOneSelect()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectTwoInputOneSelect('age',$select,$this->icon,'text','name','username','','',true,'number','age','your age','','',true,'id',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->oneSelectTwoInputOneSelect('age',$select,$this->icon,'text','name','username','','',true,'number','age','your age','','',true,'id',$select,$this->icon)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('your age',$boot);
        $this->assertContains('your age',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);
    }

    public function testOneSelectOneInputOneSelectOneInput()
    {

        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectOneInputOneSelectOneInput( 'number',$select,$this->icon,'text','name','username','','',true,'number','id','id','4','',true,'age',$select,'')->end();
        $foundation = form(2)->start('/','demo')->oneSelectOneInputOneSelectOneInput( 'number',$select,$this->icon,'text','name','username','','',true,'number','id','id','4','',true,'age',$select,'')->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('4',$boot);
        $this->assertContains('4',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);
    }

    public function testOneSelectOneInputTwoSelect()
    {

        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectOneInputTwoSelect('id',$select,$this->icon,'text','name','age','','',true,'number',$select,$this->icon,'age',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->oneSelectOneInputTwoSelect('id',$select,$this->icon,'text','name','age','','',true,'number',$select,$this->icon,'age',$select,$this->icon)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);
    }

    public function testThreeInlineInputAndOneSelect()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->threeInlineInputAndOneSelect('text','name','username','4',$this->icon,true,'file','filename','','','',true,'text','src','source','','',true,'age',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->threeInlineInputAndOneSelect('text','name','username','4',$this->icon,true,'file','filename','','','',true,'text','src','source','','',true,'age',$select,$this->icon)->end();


        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);


        $this->assertContains('4',$boot);
        $this->assertContains('4',$foundation);

        $this->assertContains('source',$boot);
        $this->assertContains('source',$foundation);

        $this->assertContains('src',$boot);
        $this->assertContains('src',$foundation);

        $this->assertContains('file',$boot);
        $this->assertContains('file',$foundation);

        $this->assertContains('filename',$boot);
        $this->assertContains('filename',$foundation);

    }

    public function testTwoSelectTwoInput()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->twoSelectTwoInput('age',$select,$this->icon,'id',$select,$this->icon,'number','id','id','','',false,'text','name','username','','',false)->end();
        $foundation = form(2)->start('/','demo')->twoSelectTwoInput('age',$select,$this->icon,'id',$select,$this->icon,'number','id','id','','',false,'text','name','username','','',false)->end();

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);


        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);


        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);


        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);
    }

    public function testTwoSelectOneInputOneSelect()
    {
        $select = array('1', '2', '3');

        $boot = form()->start('/','demo')->twoSelectOneInputOneSelect('id', $select, $this->icon, 'age', $select, $this->icon, 'text', 'name', 'username', '', '', true, 'party', $select, $this->icon)->end();
        $foundation = form(2)->start('/','demo')->twoSelectOneInputOneSelect('id', $select, $this->icon, 'age', $select, $this->icon, 'text', 'name', 'username', '', '', true, 'party', $select, $this->icon)->end();


        $this->assertContains($this->icon, $boot);
        $this->assertContains($this->icon, $foundation);

        $this->assertContains('id', $boot);
        $this->assertContains('id', $foundation);

        $this->assertContains('age', $boot);
        $this->assertContains('age', $foundation);

        $this->assertContains('text', $boot);
        $this->assertContains('text', $foundation);

        $this->assertContains('required', $boot);
        $this->assertContains('required', $foundation);


        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('party',$boot);
        $this->assertContains('party',$foundation);


        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

    }

    public function testThreeSelectOneInput()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->threeSelectOneInput('age',$select,$this->icon,'id',$select,$this->icon,'name',$select,$this->icon,'text','username','name','','',true)->end();
        $foundation = form(2)->start('/','demo')->threeSelectOneInput('age',$select,$this->icon,'id',$select,$this->icon,'name',$select,$this->icon,'text','username','name','','',true)->end();

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);


        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);
    }

    public function testTwoInputOneSelectOneInput()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->twoInputOneSelectOneInput('text','name','username','','',true,'number','age','age','','',true,'id',$select,$this->icon,'text','ip','','127.0.0.1','',true)->end();
        $foundation = form(2)->start('/','demo')->twoInputOneSelectOneInput('text','name','username','','',true,'number','age','age','','',true,'id',$select,$this->icon,'text','ip','','127.0.0.1','',true)->end();

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('id',$boot);
        $this->assertContains('id',$foundation);

        $this->assertContains('ip',$boot);
        $this->assertContains('ip',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('127.0.0.1',$boot);
        $this->assertContains('127.0.0.1',$foundation);

    }
    public function testTwoInputTwoSelect()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->twoInputTwoSelect('text','name','username','','',true,'text','dep','department','','',true,'ip',$select,$this->icon,'age',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->twoInputTwoSelect('text','name','username','','',true,'text','dep','department','','',true,'ip',$select,$this->icon,'age',$select,$this->icon)->end();

        $this->assertContains('ip',$boot);
        $this->assertContains('ip',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('dep',$boot);
        $this->assertContains('dep',$foundation);

        $this->assertContains('department',$boot);
        $this->assertContains('department',$foundation);

        $this->assertContains('required',$boot);
        $this->assertContains('required',$foundation);

    }

    public function testFoorInlineSelect()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->fourInlineSelect('age',$select,$this->icon,'ip',$select,$this->icon,'users',$select,$this->icon,'address',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->fourInlineSelect('age',$select,$this->icon,'ip',$select,$this->icon,'users',$select,$this->icon,'address',$select,$this->icon)->end();

        $this->assertContains('1',$boot);
        $this->assertContains('1',$foundation);

        $this->assertContains('age',$boot);
        $this->assertContains('age',$foundation);

        $this->assertContains('ip',$boot);
        $this->assertContains('ip',$foundation);

        $this->assertContains('users',$boot);
        $this->assertContains('users',$foundation);


        $this->assertContains('address',$boot);
        $this->assertContains('address',$foundation);

        $this->assertContains('2',$boot);
        $this->assertContains('2',$foundation);

        $this->assertContains('3',$boot);
        $this->assertContains('3',$foundation);

    }

    public function testCheckBox()
    {
        $boot       = form()->start('/','demo')->checkbox('a','remember',$this->class,true)->end();
        $foundation = form(2)->start('/','demo')->checkbox('a','remember',$this->class,true)->end();

        $this->assertContains('a',$boot);
        $this->assertContains('a',$foundation);

        $this->assertContains('remember',$boot);
        $this->assertContains('remember',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains('checked="checked"',$boot);
        $this->assertContains('checked="checked"',$foundation);

        $boot       = form()->start('/','demo')->checkbox('a','remember',$this->class,false)->end();
        $foundation = form(2)->start('/','demo')->checkbox('a','remember',$this->class,false)->end();


        $this->assertContains('a',$boot);
        $this->assertContains('a',$foundation);

        $this->assertContains('remember',$boot);
        $this->assertContains('remember',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertNotContains('checked="checked"',$boot);
        $this->assertNotContains('checked="checked"',$foundation);
    }

    public function testRadio()
    {
        $boot       = form()->start('/','demo')->radio('a','remember',$this->class,true)->end();
        $foundation = form(2)->start('/','demo')->radio('a','remember',$this->class,true)->end();

        $this->assertContains('a',$boot);
        $this->assertContains('a',$foundation);

        $this->assertContains('remember',$boot);
        $this->assertContains('remember',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains('checked="checked"',$boot);
        $this->assertContains('checked="checked"',$foundation);

        $boot       = form()->start('/','demo')->radio('a','remember',$this->class,false)->end();
        $foundation = form(2)->start('/','demo')->radio('a','remember',$this->class,false)->end();


        $this->assertContains('a',$boot);
        $this->assertContains('a',$foundation);

        $this->assertContains('remember',$boot);
        $this->assertContains('remember',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertNotContains('checked="checked"',$boot);
        $this->assertNotContains('checked="checked"',$foundation);
    }

    /**
     * @throws \Exception
     */
    public function testRedirectSelect()
    {
        $select = array( 'https://google.fr' => 'go to google');

        $boot       = form()->start('/','demo')->setSmallInput(true)->redirectSelect('sites',$select)->end();


        $this->assertContains('form-control form-control-sm',$boot);


        $this->assertContains('https://google.fr',$boot);

        $this->assertContains('sites',$boot);

        $this->assertContains('go to google',$boot);

        $select = array(   'https://google.fr'  => 'go to google','https://wikipedia.org' => 'wikipedia');
        $boot       = form()->start('/','demo')->redirectSelect('sites',$select)->end();
        $foundation = form(2)->start('/','demo')->redirectSelect('sites',$select)->end();

        $this->assertContains('https://google.fr',$boot);
        $this->assertContains('https://google.fr',$foundation);

        $this->assertContains('https://wikipedia.org',$boot);
        $this->assertContains('https://wikipedia.org',$foundation);

        $this->assertContains('wikipedia',$boot);
        $this->assertContains('wikipedia',$foundation);

        $this->assertContains('sites',$boot);
        $this->assertContains('sites',$foundation);

        $this->assertContains('go to google',$boot);
        $this->assertContains('go to google',$foundation);


        $boot       = form()->start('/','demo')->setSmallInput(true)->redirectSelect('sites',$select)->end();
        $foundation = form(2)->start('/','demo')->setSmallInput(true)->redirectSelect('sites',$select)->end();

        $this->assertContains('form-control form-control-sm',$boot);
        $this->assertNotContains('form-control form-control-sm',$foundation);

        $boot       = form()->start('/','demo')->setLargeInput(true)->redirectSelect('sites',$select)->end();
        $foundation = form(2)->start('/','demo')->setLargeInput(true)->redirectSelect('sites',$select)->end();

        $this->assertContains('form-control form-control-lg',$boot);
        $this->assertNotContains('form-control form-control-lg',$foundation);

    }

    /**
     * @throws \Exception
     */
    public function testTwoRedirectSelect()
    {
        $select = array(   'https://google.fr' => 'go to google' , 'https://wikipedia.org'  => 'wikipedia' );
        $boot       = form()->start('/','demo')->twoRedirectSelect('sites',$select,$this->icon,'site',$select,$this->icon)->end();
        $foundation = form(2)->start('/','demo')->twoRedirectSelect('sites',$select,$this->icon,'site',$select,$this->icon)->end();


        $this->assertContains('https://google.fr',$boot);
        $this->assertContains('https://google.fr',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('https://wikipedia.org',$boot);
        $this->assertContains('https://wikipedia.org',$foundation);

        $this->assertContains('wikipedia',$boot);
        $this->assertContains('wikipedia',$foundation);

        $this->assertContains('sites',$boot);
        $this->assertContains('sites',$foundation);

        $this->assertContains('site',$boot);
        $this->assertContains('site',$foundation);

        $this->assertContains('go to google',$boot);
        $this->assertContains('go to google',$foundation);

    }



    public function testOneSelectOneInput()
    {
        $select = array(  'google','wikipedia'   );
        $boot       = form()->oneSelectOneInput('sites',$select,$this->icon,'text','name','username',true,'','')->end();
        $foundation = form(2)->oneSelectOneInput('sites',$select,$this->icon,'text','name','username',true,'','')->end();

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);


        $this->assertContains('google',$boot);
        $this->assertContains('google',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('wikipedia',$boot);
        $this->assertContains('wikipedia',$foundation);

        $this->assertContains('sites',$boot);
        $this->assertContains('sites',$foundation);

    }

    public function testOneInputOneSelect()
    {
        $select = array(  'google','wikipedia'   );
        $boot       = form()->oneInputOneSelect('text','name','username',true,'','','sites',$select,$this->icon)->end();
        $foundation = form(2)->oneInputOneSelect('text','name','username',true,'','','sites',$select,$this->icon)->end();

        $this->assertContains('name',$boot);
        $this->assertContains('name',$foundation);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$foundation);

        $this->assertContains('text',$boot);
        $this->assertContains('text',$foundation);


        $this->assertContains('google',$boot);
        $this->assertContains('google',$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->assertContains('wikipedia',$boot);
        $this->assertContains('wikipedia',$foundation);

        $this->assertContains('sites',$boot);
        $this->assertContains('sites',$foundation);

    }

    public function testContainClass()
    {
        $this->assertContains($this->class,form(1)->start('/','form',$this->class)->end());
        $this->assertContains($this->class,form(2)->start('/','form',$this->class)->end());
    }

    /**
     * @throws Exception
     */
    public function testGenerateForm()
    {

        $table = "doctors";
        $base = 'imperiums';

        $mysql = table(Connexion::MYSQL,$base,'root','','');
        $pgsql = table(Connexion::POSTGRESQL,$base,'postgres','','');

        $boot = form()->start('/','demo')->generate($table,$mysql,'add',$this->class,'SUBMIT');
        $foundation = form(2)->start('/','demo')->generate($table,$mysql,'add','','SUBMIT');

        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('id',$foundation);
        $this->assertContains('name',$foundation);
        $this->assertContains('age',$foundation);
        $this->assertContains('sex',$foundation);
        $this->assertContains('status',$foundation);
        $this->assertContains('date',$foundation);

        $this->assertContains('add',$boot);
        $this->assertContains('textarea',$boot);
        $this->assertContains($this->class,$boot);
        $this->assertContains('id',$foundation);
        $this->assertContains('add',$foundation);
        $this->assertContains('textarea',$foundation);


        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('SUBMIT',$boot);
        $this->assertContains('SUBMIT',$foundation);

        $boot = form()->start('/','demo')->generate($table,$pgsql,'add',$this->class,'SUBMIT');
        $foundation = form(2)->start('/','demo')->generate($table,$pgsql,'add','','SUBMIT');


        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('id',$foundation);
        $this->assertContains('name',$foundation);
        $this->assertContains('age',$foundation);
        $this->assertContains('sex',$foundation);
        $this->assertContains('status',$foundation);
        $this->assertContains('date',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('add',$boot);
        $this->assertContains('add',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('textarea',$boot);
        $this->assertContains('textarea',$foundation);

        $this->assertContains('date',$boot);
        $this->assertContains('date',$foundation);

        $this->assertContains('SUBMIT',$boot);
        $this->assertContains('SUBMIT',$foundation);


        $boot = form()->start('/','demo')->generate($table,$mysql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);
        $foundation = form(2)->start('/','demo')->generate($table,$mysql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);


        $this->assertContains('1',$boot);
        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('1',$foundation);
        $this->assertContains('id',$foundation);
        $this->assertContains('name',$foundation);
        $this->assertContains('age',$foundation);
        $this->assertContains('sex',$foundation);
        $this->assertContains('status',$foundation);
        $this->assertContains('date',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('update',$boot);
        $this->assertContains('update',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('textarea',$boot);
        $this->assertContains('textarea',$foundation);

        $this->assertContains('date',$boot);
        $this->assertContains('date',$foundation);

        $this->assertContains('SUBMIT',$boot);
        $this->assertContains('SUBMIT',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $boot = form()->start('/','demo')->generate($table,$pgsql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);
        $foundation = form(2)->start('/','demo')->generate($table,$pgsql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);


        $this->assertContains('1',$boot);
        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('1',$foundation);
        $this->assertContains('id',$foundation);
        $this->assertContains('name',$foundation);
        $this->assertContains('age',$foundation);
        $this->assertContains('sex',$foundation);
        $this->assertContains('status',$foundation);
        $this->assertContains('date',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);


        $this->assertContains('update',$boot);
        $this->assertContains('update',$foundation);

        $this->assertContains('number',$boot);
        $this->assertContains('number',$foundation);

        $this->assertContains('textarea',$boot);
        $this->assertContains('textarea',$foundation);

        $this->assertContains('date',$boot);
        $this->assertContains('date',$foundation);

        $this->assertContains('SUBMIT',$boot);
        $this->assertContains('SUBMIT',$foundation);

        $this->assertContains($this->class,$boot);
        $this->assertContains($this->class,$foundation);

        $this->assertContains($this->icon,$boot);
        $this->assertContains($this->icon,$foundation);

        $this->expectException(Exception::class);
        form()->start('/','a')->generate($table,$mysql,'submit','adz','a','adz',999);
        form()->start('/','a')->generate($table,$mysql,'submit','adz','a','adz',999,5);
    }

    public function testMultiple()
    {
        $this->assertContains('multiple',form()->start('','a')->select('a',['a','b'],'',true)->end());
        $this->assertNotContains('multiple',form()->start('','a')->select('a',['a','b'],'')->end());
        $this->assertContains('multiple',form(2)->start('','a')->select('a',['a','b'],'',true)->end());
        $this->assertNotContains('multiple',form(2)->start('','a')->select('a',['a','b'],'')->end());

        $this->assertContains('multiple',form()->start('','a')->select('a',['a','b'],$this->icon,true)->end());
        $this->assertNotContains('multiple',form()->start('','a')->select('a',['a','b'],$this->icon)->end());
        $this->assertContains('multiple',form(2)->start('','a')->select('a',['a','b'],$this->icon,true)->end());
        $this->assertNotContains('multiple',form(2)->start('','a')->select('a',['a','b'],$this->icon)->end());
    }

    public function testSize()
    {
        $this->assertContains('form-control',form()->start('/','id')->setLargeInput(false)->input('text','name','username')->end());
        $this->assertContains('form-control',form()->start('/','id')->setSmallInput(false)->input('text','name','username')->end());
        $this->assertContains('form-control form-control-lg',form()->start('/','id')->setLargeInput(true)->input('text','name','username')->end());
        $this->assertContains('form-control form-control-sm',form()->start('/','id')->setSmallInput(true)->input('text','name','username')->end());

    }
}