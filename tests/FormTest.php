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
    /**
     * @var string 
     */
    private $class = 'btn btn-primary';

    /**
     * @var string 
     */
    private $icon = '<i class="fa fa-user">';


    public function testStartAndEnd()
    {

        $html = form()->start('/','demo')->end();
        $this->assertContains('/',$html);
        $this->assertContains('post',$html);
        $this->assertContains('utf8',$html);
        $this->assertStringStartsWith('<form',$html);
        $this->assertStringEndsWith('</form>',$html);
    }

    public function testHide()
    {
        $boot = form()->start('/','demo')->startHide()->endHide()->end();
        $this->assertContains('d-none',$boot);
        $this->assertContains('</div>',$boot);

    }

    public function testFile()
    {
        $bootHtmlWithoutIcon = form()->start('/','demo')->file('avatar',$this->class,'upload')->end();
        $bootHtmlWithIcon = form()->start('/','demo')->file('avatar',$this->class,'upload',$this->icon)->end();

        $this->assertContains('avatar',$bootHtmlWithIcon);
        $this->assertContains('avatar',$bootHtmlWithoutIcon);

        $this->assertContains('upload',$bootHtmlWithIcon);
        $this->assertContains('upload',$bootHtmlWithoutIcon);

        $this->assertContains($this->icon,$bootHtmlWithIcon);
        $this->assertNotContains($this->icon,$bootHtmlWithoutIcon);

    }


    public function testInput()
    {

        $bootHtmlWithoutIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->end();


        $bootHtmlWithIcon   = form()->start('/','demo')->input(Form::TEXT,'username','Username',$this->icon)->end();


        $this->assertContains('username',$bootHtmlWithIcon);
        $this->assertContains('username',$bootHtmlWithoutIcon);

        $this->assertContains('text',$bootHtmlWithIcon);
        $this->assertContains('text',$bootHtmlWithoutIcon);

        $this->assertContains('Username',$bootHtmlWithIcon);
        $this->assertContains('Username',$bootHtmlWithoutIcon);

        $this->assertContains($this->icon,$bootHtmlWithIcon);
        $this->assertNotContains($this->icon,$bootHtmlWithoutIcon);

    }

    public function testSubmit()
    {

        $bootHtmlWithoutIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,'d')->end();

        $bootHtmlWithIcon    = form()->start('/','demo')->input(Form::TEXT,'username','Username')->submit('submit',$this->class,fa('fa-send'))->end();

        $this->assertContains('submit',$bootHtmlWithIcon);
        $this->assertContains('submit',$bootHtmlWithoutIcon);

        $this->assertContains($this->class,$bootHtmlWithIcon);
        $this->assertContains($this->class,$bootHtmlWithoutIcon);

        $this->assertContains(fa('fa-send'),$bootHtmlWithIcon);
        $this->assertNotContains(fa('fa-send'),$bootHtmlWithoutIcon);
    }

    public function testTextarea()
    {
        $boot       = form()->start('/','demo')->textarea('bio','biography',10,15)->end();

        $bootAuto = form()->start('/','demo')->textarea('bio','biography',10,15,true)->end();

        $this->assertContains('bio',$boot);

        $this->assertContains('biography',$boot);

        $this->assertContains('10',$boot);

        $this->assertContains('15',$boot);

        $this->assertContains('autofocus="autofocus"',$bootAuto);
    }

    public function testCsrf()
    {
        $csrf = "<input type='hidden' value='#ffee00'>";
        $boot       = form()->start('/','demo')->csrf($csrf)->textarea('bio','biography',10,15)->end();

        $this->assertContains($csrf,$boot);
    }

    public function testReset()
    {
        $boot       = form()->start('/','demo')->reset('clear',$this->class)->textarea('bio','biography',10,15)->end();

        $this->assertContains('clear',$boot);

        $this->assertContains($this->class,$boot);

        $this->assertNotContains($this->icon,$boot);
    }
    
    public function testLink()
    {
        $boot       = form()->start('/','demo')->link('/register',$this->class,'create an account')->end();

        $this->assertContains('/register',$boot);

        $this->assertContains('create an account',$boot);
        
        $this->assertNotContains($this->icon,$boot);

        $boot       = form()->start('/','demo')->link('/register',$this->class,'create an account',$this->icon)->end();

        $this->assertContains($this->icon,$boot);
    }

    public function testSelect()
    {
        $users = array('marc','antoine','marion','alexandre');

        $boot       = form()->start('/','demo')->select('users',$users)->end();
        $this->assertContains('marc',$boot);
        $this->assertContains('antoine',$boot);
        $this->assertContains('alexandre',$boot);

        $boot       = form()->start('/','demo')->select('users',$users,$this->icon)->end();

        $this->assertContains($this->icon,$boot);
    }
    public function testTwoInput()
    {
        $boot             = form()->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();

        $this->assertContains(Form::TEXT,$boot);
        $this->assertContains(Form::EMAIL,$boot);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$boot);

        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$boot);
    }
    public function testEnctype()
    {
        $this->assertContains('enctype',form()->start('/','','',true)->end());
        $this->assertContains('enctype',form()->start('/','','',true)->end());
    }

    public function testTwoInlineInput()
    {
        $boot             = form()->start('/','demo')->twoInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true)->end();

        $this->assertContains('username',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('email',$boot);

        $this->assertContains('Email',$boot);

        $this->assertContains('required',$boot);
    }

    public function testTwoSelect()
    {
        $users = array('marc','alex','jupiter');
        $capital = array('1111','98956','2325');

        $boot             = form()->start('/','demo')->twoInlineSelect('users',$users,$this->icon,'capitals',$capital,$this->icon)->end();

        foreach ($users as $k => $user)
        {
            $this->assertContains($this->icon,$boot);
            $this->assertContains($user,$boot);
            $this->assertContains($capital[$k],$boot);
        }


    }
    public function OneInputAndSelect()
    {
        $bases = array('marc','alex','jupiter');

        $boot             = form()->start('/','demo')->oneInputOneSelect('text','user','username',true,$this->icon,'','bases',$bases,$this->icon)->end();

        foreach ($bases as $base)
        {
            $this->assertContains($this->icon,$boot);

            $this->assertContains('user',$boot);

            $this->assertContains('username',$boot);

            $this->assertContains('text',$boot);

            $this->assertContains('bases',$boot);

            $this->assertContains($base,$boot);

        }


    }

    public function testThreeInput()
    {
        $boot             = form()->start('/','demo')->threeInlineInput(Form::TEXT,'name','username','','',true,Form::EMAIL,'email','Email','','',true,Form::COLOR,'color','','#FF0000','',true)->end();


        $this->assertContains(Form::TEXT,$boot);
        $this->assertContains(Form::EMAIL,$boot);
        $this->assertContains(Form::COLOR,$boot);

        $this->assertContains('username',$boot);
        $this->assertContains('username',$boot);

        $this->assertContains('email',$boot);
        $this->assertContains('email',$boot);

        $this->assertContains('Email',$boot);
        $this->assertContains('Email',$boot); ;

        $this->assertContains('color',$boot);
        $this->assertContains('color',$boot);

        $this->assertContains('#FF0000',$boot);

    }

    public function testFourInput()
    {
        $boot             = form()->start('/','demo')->fourInlineInput('text','four1','four','','',true,'text','four2','four','supersonic','',true,'text','four3','four','','',true,'text','four4','for','','',true)->end();


         $this->assertContains('four',$boot);

         $this->assertContains('supersonic',$boot);

         $this->assertContains('required',$boot);

    }

    public function testGenerate()
    {

        // DEFAULT

        $boot             = form()->start('/','demo')->setLargeInput(true)->input('text','code','name')->end();

        $this->assertContains('form-control-lg',$boot);

        $boot             = form()->start('/','demo')->setSmallInput(true)->input('text','code','name')->end();

        $this->assertContains('form-control-sm',$boot);

        $boot             = form()->start('/','demo')->setSmallInput(true)->input('file','code','name')->end();

        $this->assertContains('form-control-file',$boot);

        // DEFAULT

        $boot             = form()->start('/','demo')->input('text','code','name')->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertContains('required',$boot);


        // AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,false,true)->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertNotContains('required',$boot);

        //  AUTOFOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,true,false)->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertNotContains('required',$boot);

        //  AUTOFOCUS AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',false,true,true)->end();

        $this->assertNotContains($this->icon,$boot);
        $this->assertContains('autofocus',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertNotContains('required',$boot);


        //  REQUIRE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,false)->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertContains('required',$boot);


        //  REQUIRE AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,true)->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);


        $this->assertContains('required',$boot);




        //  REQUIRE AND AUTOCOMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,false,true)->end();

        $this->assertNotContains($this->icon,$boot); ;

        $this->assertNotContains('autofocus',$boot);
        $this->assertContains('name',$boot);


        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);


        $this->assertContains('required',$boot);



        //  REQUIRE AUTOFOCUS AND AUTOCOMPLETE  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name','','',true,true,true)->end();

        $this->assertNotContains($this->icon,$boot);

        $this->assertContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertContains('required',$boot);


        // ICON  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,false,false)->end();

        $this->assertContains($this->icon,$boot);


        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertNotContains('required',$boot);

        // ICON AUTOCOMPLETE  ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,false,true)->end();

        $this->assertContains($this->icon,$boot);


        $this->assertNotContains('autofocus',$boot);


        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertNotContains('required',$boot);

        // ICON AUTO FOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,true,false)->end();

        $this->assertContains($this->icon,$boot);

        $this->assertContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertNotContains('required',$boot);


        // ICON AUTO FOCUS AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',false,true,true)->end();

        $this->assertContains($this->icon,$boot);

        $this->assertContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertNotContains('required',$boot);


        // ICON REQUIRED ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,false,false)->end();

        $this->assertContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertContains('required',$boot);

        // ICON REQUIRED  AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,false,true)->end();

        $this->assertContains($this->icon,$boot);

        $this->assertNotContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertContains('required',$boot);


        // ICON REQUIRED  AUTO FOCUS ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,true,false)->end();

        $this->assertContains($this->icon,$boot);


        $this->assertContains('autofocus',$boot);
        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="off"',$boot);

        $this->assertContains('required',$boot);


        // ICON REQUIRED  AUTO FOCUS AND AUTO COMPLETE ENABLED

        $boot             = form()->start('/','demo')->input('text','code','name',$this->icon,'',true,true,true)->end();

        $this->assertContains($this->icon,$boot);

        $this->assertContains('autofocus',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('code',$boot);

        $this->assertContains('autocomplete="on"',$boot);

        $this->assertContains('required',$boot);


    }


    public function testSelectWithSize()
    {
        $form = form()->setLargeInput(true)->select('a',['b'])->end();
        $this->assertContains('form-control-lg',$form);

        $form = form()->setSmallInput(true)->select('a',['b'])->end();
        $this->assertContains('form-control-sm',$form);

        $form = form()->select('a',['b'])->end();
        $this->assertNotContains('form-control-sm',$form);
        $this->assertNotContains('form-control-lg',$form);

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

                     form()->start('/','boot')->generate('admin',table($driver,"imperiums",'root','',''),'submit',$this->class,'sid',$this->icon,'azd',3);
                break;
                case Connexion::POSTGRESQL:
                    $this->expectException(Exception::class);
                    form()->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);

                    form()->start('/','boot')->generate('admin',table($driver,"imperiums",'postgres','',''),'submit',$this->class,'sid',$this->icon,'adz',3);
                break;
                default:
                    $this->expectException(Exception::class);
                    form()->start('/','boot')->generate('users',table($driver,"testing",'','',''),'submit',$this->class,'sid',$this->icon,Form::EDIT,99999999999999);
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


            $this->assertContains('alex',$boot);

            $this->assertContains($this->class,$boot);

            $this->assertContains($this->icon,$boot);

            $this->assertContains($type,$boot);

            $boot             = form()->start('/','demo')->button('alex',$this->class,'',$type)->end();


            $this->assertContains('alex',$boot);

            $this->assertContains($this->class,$boot);

            $this->assertNotContains($this->icon,$boot);

            $this->assertContains($type,$boot);
        }
        $boot             = form()->start('/','demo')->button('alex',$this->class,$this->icon)->end();


        $this->assertContains($this->class,$boot);

        $this->assertContains('type="button"',$boot);

        $this->assertContains($this->icon,$boot);

        $this->assertContains('alex',$boot);
    }

    public function testImg()
    {
        $boot             = form()->start('/','demo')->img('imperiums','lion',$this->class)->end();



        $this->assertContains('imperiums',$boot);

        $this->assertContains($this->class,$boot);

        $this->assertContains('lion',$boot);

        $boot             = form()->start('/','demo')->img('imperiums','lion')->end();


        $this->assertContains('imperiums',$boot);

        $this->assertNotContains($this->class,$boot);

        $this->assertNotContains($this->icon,$boot);

        $this->assertContains('lion',$boot);

    }

    public function testOneInputOneSelectTwoInput()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputOneSelectTwoInput('text','name','Username','','',true,'number',$select,'','text','card','card','','',true,'number','age','age','','',true)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('Username',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('card',$boot);
        $this->assertContains('age',$boot);
    }

    public function testOneInputOneSelectOneInputOneSelect()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputOneSelectOneInputOneSelect('text','name','username','','',true,'age',$select,$this->icon,'text','group','name','','',true,'albums',$select,$this->icon)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('group',$boot);

        $this->assertContains('albums',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('group',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('age',$boot);
    }

    public function testOneInputTwoSelectOneInput()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputTwoSelectOneInput('text','name','username','','',true,'age',$select,'','years',$select,'','number','card','card','','',true)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('group',$boot);


        $this->assertContains('years',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('age',$boot) ;

        $this->assertContains('username',$boot);
    }


    public function testOneInputThreeSelect()
    {
        $select = array('1','2','3');
        $boot       = form()->start('/','demo')->oneInputThreeSelect('password','pwd','your password','',$this->icon,true,'years',$select,$this->icon,'age',$select,$this->icon,'party',$select,$this->icon)->end();

        $this->assertContains('password',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('pwd',$boot);

        $this->assertContains('your password',$boot);

        $this->assertContains($this->icon,$boot);

        $this->assertContains('years',$boot);

        $this->assertContains('party',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('required',$boot);

    }

    public function testOneSelectThreeInput()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectThreeInput('id',$select,$this->icon,'password','pwd','your password','','',true,'text','card','card','','',true,'number','age','your age','','',true)->end();

        $this->assertContains('password',$boot);

        $this->assertContains('text',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('id',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('pwd',$boot);

        $this->assertContains('your password',$boot);

        $this->assertContains('your age',$boot);

        $this->assertContains($this->icon,$boot);

        $this->assertContains('card',$boot);

        $this->assertContains('age',$boot);

    }

    public function  testOneSelectTwoInputOneSelect()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectTwoInputOneSelect('age',$select,$this->icon,'text','name','username','','',true,'number','age','your age','','',true,'id',$select,$this->icon)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('id',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('your age',$boot);

        $this->assertContains('age',$boot);
    }

    public function testOneSelectOneInputOneSelectOneInput()
    {

        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectOneInputOneSelectOneInput( 'number',$select,$this->icon,'text','name','username','','',true,'number','id','id','4','',true,'age',$select,'')->end();

        $this->assertContains('text',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('id',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('4',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('age',$boot);
    }

    public function testOneSelectOneInputTwoSelect()
    {

        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->oneSelectOneInputTwoSelect('id',$select,$this->icon,'text','name','age','','',true,'number',$select,$this->icon,'age',$select,$this->icon)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('id',$boot);

        $this->assertContains('1',$boot) ;

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('required',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('age',$boot);
    }

    public function testThreeInlineInputAndOneSelect()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->threeInlineInputAndOneSelect('text','name','username','4',$this->icon,true,'file','filename','','','',true,'text','src','source','','',true,'age',$select,$this->icon)->end();


        $this->assertContains('username',$boot);


        $this->assertContains('age',$boot);

        $this->assertContains('4',$boot);
        $this->assertContains('source',$boot);

        $this->assertContains('src',$boot);

        $this->assertContains('file',$boot);

        $this->assertContains('filename',$boot);

    }

    public function testTwoSelectTwoInput()
    {
        $select = array('1','2','3');

        $boot       = form()->start('/','demo')->twoSelectTwoInput('age',$select,$this->icon,'id',$select,$this->icon,'number','id','id','','',false,'text','name','username','','',false)->end();

        $this->assertContains('age',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);


        $this->assertContains('id',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);


        $this->assertContains($this->icon,$boot);
    }

    public function testTwoSelectOneInputOneSelect()
    {
        $select = array('1', '2', '3');

        $boot = form()->start('/','demo')->twoSelectOneInputOneSelect('id', $select, $this->icon, 'age', $select, $this->icon, 'text', 'name', 'username', '', '', true, 'party', $select, $this->icon)->end();


        $this->assertContains($this->icon, $boot);

        $this->assertContains('id', $boot);

        $this->assertContains('age', $boot);

        $this->assertContains('text', $boot);

        $this->assertContains('required', $boot);


        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('party',$boot);


        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);
        $this->assertContains('3',$boot);

    }

    public function testThreeSelectOneInput()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->threeSelectOneInput('age',$select,$this->icon,'id',$select,$this->icon,'name',$select,$this->icon,'text','username','name','','',true)->end();

        $this->assertContains('age',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('text',$boot);


        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);
    }

    public function testTwoInputOneSelectOneInput()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->twoInputOneSelectOneInput('text','name','username','','',true,'number','age','age','','',true,'id',$select,$this->icon,'text','ip','','127.0.0.1','',true)->end();

        $this->assertContains('text',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('id',$boot);

        $this->assertContains('ip',$boot);

        $this->assertContains('1',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('name',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('127.0.0.1',$boot);

    }
    public function testTwoInputTwoSelect()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->twoInputTwoSelect('text','name','username','','',true,'text','dep','department','','',true,'ip',$select,$this->icon,'age',$select,$this->icon)->end();

        $this->assertContains('ip',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('1',$boot);
        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('dep',$boot);

        $this->assertContains('department',$boot);

        $this->assertContains('required',$boot);

    }

    public function testFoorInlineSelect()
    {
        $select = array('1', '2', '3');

        $boot       = form()->start('/','demo')->fourInlineSelect('age',$select,$this->icon,'ip',$select,$this->icon,'users',$select,$this->icon,'address',$select,$this->icon)->end();

        $this->assertContains('1',$boot);

        $this->assertContains('age',$boot);

        $this->assertContains('ip',$boot);

        $this->assertContains('users',$boot);


        $this->assertContains('address',$boot);

        $this->assertContains('2',$boot);

        $this->assertContains('3',$boot);

    }

    public function testCheckBox()
    {
        $boot       = form()->start('/','demo')->checkbox('a','remember',$this->class,true)->end();


        $this->assertContains('a',$boot);

        $this->assertContains('remember',$boot);



        $this->assertContains('checked="checked"',$boot);

        $boot       = form()->start('/','demo')->checkbox('a','remember',$this->class,false)->end();


        $this->assertContains('a',$boot);

        $this->assertContains('remember',$boot);


        $this->assertNotContains('checked="checked"',$boot);
    }

    public function testRadio()
    {
        $boot       = form()->start('/','demo')->radio('a','remember',$this->class,true)->end();


        $this->assertContains('a',$boot);
        $this->assertContains('remember',$boot);

        $this->assertContains($this->class,$boot);
        $this->assertContains('checked="checked"',$boot);

        $boot       = form()->start('/','demo')->radio('a','remember',$this->class,false)->end();



        $this->assertContains('a',$boot);
        $this->assertContains('remember',$boot);

        $this->assertContains($this->class,$boot);
        $this->assertNotContains('checked="checked"',$boot);
    }

    /**
     * @throws \Exception
     */
    public function testRedirectSelect()
    {
        $select = array( 'https://google.fr' => 'go to google');

        $boot       = form()->start('/','demo')->setSmallInput(true)->redirectSelect('sites',$select)->end();


        $this->assertContains('form-control-sm',$boot);


        $this->assertContains('https://google.fr',$boot);

        $this->assertContains('sites',$boot);

        $this->assertContains('go to google',$boot);

        $select = array(   'https://google.fr'  => 'go to google','https://wikipedia.org' => 'wikipedia');
        $boot       = form()->start('/','demo')->redirectSelect('sites',$select)->end();

        $this->assertContains('https://google.fr',$boot);

        $this->assertContains('https://wikipedia.org',$boot);

        $this->assertContains('wikipedia',$boot);

        $this->assertContains('sites',$boot);

        $this->assertContains('go to google',$boot);


        $boot       = form()->start('/','demo')->setSmallInput(true)->redirectSelect('sites',$select)->end();

        $this->assertContains('form-control-sm',$boot);

        $boot       = form()->start('/','demo')->setLargeInput(true)->redirectSelect('sites',$select)->end();

        $this->assertContains('form-control-lg',$boot);

    }

    /**
     * @throws \Exception
     */
    public function testTwoRedirectSelect()
    {
        $select = array(   'https://google.fr' => 'go to google' , 'https://wikipedia.org'  => 'wikipedia' );
        $boot       = form()->start('/','demo')->twoRedirectSelect('sites',$select,$this->icon,'site',$select,$this->icon)->end();


        $this->assertContains('https://google.fr',$boot);
        $this->assertContains($this->icon,$boot);

        $this->assertContains('https://wikipedia.org',$boot);

        $this->assertContains('wikipedia',$boot);

        $this->assertContains('sites',$boot);

        $this->assertContains('site',$boot);

        $this->assertContains('go to google',$boot);

    }



    public function testOneSelectOneInput()
    {
        $select = array(  'google','wikipedia'   );
        $boot       = form()->oneSelectOneInput('sites',$select,$this->icon,'text','name','username',true,'','')->end();

        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('text',$boot);


        $this->assertContains('google',$boot);

        $this->assertContains($this->icon,$boot);

        $this->assertContains('wikipedia',$boot);

        $this->assertContains('sites',$boot);

    }

    public function testOneInputOneSelect()
    {
        $select = array(  'google','wikipedia'   );
        $boot       = form()->oneInputOneSelect('text','name','username',true,'','','sites',$select,$this->icon)->end();

        $this->assertContains('name',$boot);

        $this->assertContains('username',$boot);

        $this->assertContains('text',$boot) ;

        $this->assertContains('google',$boot);

        $this->assertContains($this->icon,$boot);

        $this->assertContains('wikipedia',$boot);

        $this->assertContains('sites',$boot);

    }


    public function testContainClass()
    {
        $this->assertContains($this->class,form()->start('/','form',$this->class)->end());

    }

    /**
     * @throws Exception
     */
    public function testGenerateForm()
    {

        $table = "doctors";
        $base = 'zen';

        $mysql = table(Connexion::MYSQL,$base,'root','root','');
        $pgsql = table(Connexion::POSTGRESQL,$base,'postgres','','');

        $boot = form()->start('/','demo')->generate($table,$mysql,'add',$this->class,'SUBMIT');


        $generateBoot = generate(1,'demo','','/',$table,$mysql,'add',$this->class,'','submit');


        $this->assertContains('demo',$generateBoot);
        $this->assertContains('form-control',$generateBoot);
        $this->assertContains('/',$generateBoot);
        $this->assertContains('submit',$generateBoot);
        $this->assertStringEndsWith('</form>',$generateBoot);
        $this->assertStringStartsWith('<form ',$generateBoot);




        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);



        $this->assertContains('add',$boot);
        $this->assertContains('textarea',$boot);
        $this->assertContains($this->class,$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('SUBMIT',$boot);

        $boot = form()->start('/','demo')->generate($table,$pgsql,'add',$this->class,'SUBMIT');

        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('number',$boot);
        $this->assertContains('add',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('textarea',$boot);

        $this->assertContains('date',$boot);

        $this->assertContains('SUBMIT',$boot);


        $boot = form()->start('/','demo')->generate($table,$mysql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);


        $this->assertContains('1',$boot);
        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('update',$boot);

        $this->assertContains('number',$boot);

        $this->assertContains('textarea',$boot);

        $this->assertContains('date',$boot);

        $this->assertContains('SUBMIT',$boot);

        $this->assertContains($this->class,$boot);

        $this->assertContains($this->icon,$boot);

        $boot = form()->start('/','demo')->generate($table,$pgsql,'update',$this->class,'SUBMIT',$this->icon,Form::EDIT,1);


        $this->assertContains('1',$boot);
        $this->assertContains('id',$boot);
        $this->assertContains('name',$boot);
        $this->assertContains('age',$boot);
        $this->assertContains('sex',$boot);
        $this->assertContains('status',$boot);
        $this->assertContains('date',$boot);


        $this->assertContains('number',$boot);

        $this->assertContains('update',$boot);
        $this->assertContains('number',$boot);

        $this->assertContains('textarea',$boot);

        $this->assertContains('date',$boot);

        $this->assertContains('SUBMIT',$boot);

        $this->assertContains($this->class,$boot);

        $this->assertContains($this->icon,$boot);

        $this->expectException(Exception::class);
        form()->start('/','a')->generate($table,$mysql,'submit','adz','a','adz',999);
        form()->start('/','a')->generate($table,$mysql,'submit','adz','a','adz',999,5);
    }

    /**
     * @throws Exception
     */
    public function testFormException()
    {
        $i = table('mysql','zen','root','root','')->setName('doctors');

        $this->expectException(Exception::class);

         form()->start('/','')->generate('doctors',$i,'test','','dzaa','a',Form::EDIT,99999999);
         form()->start('/','')->generate('doctors',$i,'test','','dzaa','a',Form::EDIT,800);
         form()->start('/','')->generate('doctors',$i,'test','','dzaa','a',Form::EDIT,500);



    }
    public function testMultiple()
    {
        $this->assertContains('multiple',form()->start('','a')->select('a',['a','b'],'',true)->end());
        $this->assertNotContains('multiple',form()->start('','a')->select('a',['a','b'],'')->end());


        $this->assertContains('multiple',form()->start('','a')->select('a',['a','b'],$this->icon,true)->end());
        $this->assertNotContains('multiple',form()->start('','a')->select('a',['a','b'],$this->icon)->end());

    }

    public function testSize()
    {
        $this->assertContains('form-control',form()->start('/','id')->setLargeInput(false)->input('text','name','username')->end());
        $this->assertContains('form-control',form()->start('/','id')->setSmallInput(false)->input('text','name','username')->end());
        $this->assertContains('form-control-lg',form()->start('/','id')->setLargeInput(true)->input('text','name','username')->end());
        $this->assertContains('form-control-sm',form()->start('/','id')->setSmallInput(true)->input('text','name','username')->end());

    }
    

}