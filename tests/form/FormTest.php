<?php

namespace Testing\Form {


    use Exception;
    use Testing\DatabaseTest;
    use Imperium\Html\Form\Form;

    class FormTest extends DatabaseTest
    {

        /**
         * @var string
         *
         */
        private $table;

        public function setUp()
        {
            $this->table = 'model';
        }

        public function test_save()
        {
            $this->assertNotEmpty(form('a','a')->save()->input('text','username','username')->get());
        }
        public function test_start()
        {
            $class = 'form-horizontal';

            $form =  form('a','a','','','POST',true)->get();
            $this->assertContains("enctype",$form);
            $this->assertContains('method="post"',$form);
            $this->assertContains('action="a"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertNotContains($class,$form);

            $form =  form('a','a',$class,'','POST',true)->get();
            $this->assertContains("enctype",$form);
            $this->assertContains('method="post"',$form);
            $this->assertContains('action="a"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertContains($class,$form);


            $form =  form('a','a','','','POST',false)->get();
            $this->assertNotContains("enctype",$form);
            $this->assertContains('method="post"',$form);
            $this->assertContains('action="a"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertNotContains($class,$form);

            $form =  form('a','a','',$class,'POST',false)->get();
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
            $form =  form('a','a','','','POST',false)->hide()->input(Form::HIDDEN,'id','')->end_hide()->get();
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
            $form =  form('a','a','','','POST',false)->file('sql','sql file')->get();
            $this->assertContains('name="sql"',$form);
            $this->assertContains('sql file',$form);
            $this->assertNotContains($ico,$form);

            $form =  form('a','a','','','POST',false)->file('sql','sql file',$ico)->get();

            $this->assertContains('name="sql"',$form);
            $this->assertContains('sql file',$form);
            $this->assertContains($ico,$form);
        }

        /**
         * @throws \Exception
         */
        public function test_textarea()
        {
            $form = form('a','a')->textarea('name','value',10,10,'','',false)->get();

            $this->assertNotContains('autofocus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains('10',$form);

            $form = form('a','a')->textarea('name','value',10,10,'','',false)->get();

            $this->assertNotContains('autofocus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains('10',$form);

            $form = form('a','a')->textarea('name','value',10,10,'','',true)->get();

            $this->assertContains('autofocus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains('10',$form);

            $form = form('a','a')->textarea('name','value',10,10,'','',true)->get();

            $this->assertContains('autofocus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains('10',$form);


            $form = form('a','a')->textarea('name','value',10,10,'','',false)->get();

            $this->assertContains('placeholder="value"',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains('10',$form);
        }

        /**
         * @throws \Exception
         */
        public function test_validation()
        {
            $success ="success";
            $fail ="fail";

            $form = new Form();

            $x = $form->validate()->start('a','a','confirm','')->input(Form::TEXT,'name','name','',$success,$fail)->get();

            $this->assertContains('confirm',$x);
            $this->assertContains($success,$x);
            $this->assertContains($fail,$x);

            $form = new Form();
            $x = $form->validate()->start('a','a','confirm','',true)->select(false,'select',['a','a'],$success,$fail)->get();

            $this->assertContains('confirm',$x);
            $this->assertContains($success,$x);
            $this->assertContains($fail,$x);

            $form = new Form();
            $x = $form->validate()->start('a','a','confirm','form-control',true)->textarea('name','name',10,10,$success,$fail)->get();
            $this->assertContains('confirm',$x);

            $this->assertContains($success,$x);
            $this->assertContains($fail,$x);
        }

        public function test_execp()
        {
            $this->expectException(Exception::class);

            $x = new Form();
            $x->validate()->start('a','a')->textarea('a','a',10,10)->get();
        }
        public function test_reset()
        {
            $icon = fa('fas','fa-linux');
            $form = \form('a','a')->reset('reset','btn-danger','')->get();

            $this->assertContains('reset',$form);
            $this->assertContains('btn btn-danger',$form);
            $this->assertNotContains($icon,$form);

            $form = \form('a','a')->reset('reset','btn-danger',$icon)->get();

            $this->assertContains('reset',$form);
            $this->assertContains('btn btn-danger',$form);
            $this->assertContains($icon,$form);
        }

        /**
         * @throws \Exception
         */
        public function test_input()
        {
            $icon = fa('fas','fa-linux');

            $form = form('a','a')->input(Form::TEXT,'name','name')->get();

            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon)->get();

            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,false,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,true,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,false,true)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',false,true,true)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,false)->get();

            $this->assertContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,true)->get();

            $this->assertContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,true,false)->get();

            $this->assertContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,true,true)->get();

            $this->assertContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,false,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,true,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,false,true)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('maximus',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',false,true,true)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,false)->get();

            $this->assertContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertContains($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,false,true)->get();

            $this->assertContains('required',$form);
            $this->assertNotContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,true,false)->get();

            $this->assertContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertNotContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,true,true)->get();

            $this->assertContains('required',$form);
            $this->assertContains('autofocus',$form);
            $this->assertContains('autocomplete="on"',$form);
            $this->assertContains('placeholder="name"',$form);
            $this->assertContains('name="name"',$form);
            $this->assertNotContains($icon,$form);
        }
        /**
         * @throws \Exception
         */
        public function test_size()
        {
            $small = 'btn btn-sm btn-primary';
            $large = 'btn btn-lg btn-primary';
            $submit_class = 'btn-primary';
            $form =  form('a','a')->large()->input(Form::TEXT,'sql','sql file')->submit('a',$submit_class,'submit')->get();
            $this->assertContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);
            $this->assertContains($large,$form);

            $form =  form('a','a')->small()->input(Form::TEXT,'sql','sql file')->submit('a',$submit_class,'a')->get();
            $this->assertContains(Form::SMALL_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertContains($small,$form);
            $this->assertContains($small,$form);

            $form =  form('a','a')->large()->select(true,'table',[1,2,3])->get();
            $this->assertContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small()->select(false,'table',[1,2,3])->get();
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


            $form =  form('a','a')->large(false)->input(Form::TEXT,'sql','sql file')->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->input(Form::TEXT,'sql','sql file')->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);
            $form =  form('a','a')->large(false)->select(true,'table',[1,2,3])->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->select(false,'table',[1,2,3])->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->large(false)->textarea('table','a',10,10)->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->textarea('table','a',10,10)->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->large(false)->file('table','a')->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->file('table','a')->get();
            $this->assertContains(Form::BASIC_CLASS,$form);
            $this->assertNotContains(Form::LARGE_CLASS,$form);
            $this->assertNotContains(Form::SMALL_CLASS,$form);
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

        public function test_button()
        {
            $form = form('a','a')->button(Form::SUBMIT,'submit','btn-primary')->get();

            $this->assertContains('type="submit"',$form);
            $form = form('a','a')->button(Form::RESET,'submit','btn-primary')->get();

            $this->assertContains('type="reset"',$form);
            $form = form('a','a')->button(Form::BUTTON,'submit','btn-primary')->get();

            $this->assertContains('type="button"',$form);
        }



        public function test_select()
        {
            $icon = fa('fas','fa-trash');

            $form =  form('a','a')->select(true,'age',[15,18,19],'','','',false,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains($icon,$form);
            $this->assertNotContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="0">15</option>',$form);
            $this->assertContains('<option value="1">18</option>',$form);
            $this->assertContains('<option value="2">19</option>',$form);

            $form =  form('a','a')->select(false,'age',[15,18,19],'','',$icon,false,false)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains($icon,$form);
            $this->assertNotContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);


            $form =  form('a','a')->select(true,'age',  [15,18,19], '' , '' , '' ,  false, false)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains($icon,$form);
            $this->assertNotContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="0">15</option>',$form);
            $this->assertContains('<option value="1">18</option>',$form);
            $this->assertContains('<option value="2">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], '' , '' , $icon ,  false, false)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains($icon,$form);
            $this->assertNotContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], '' , '' , '' ,  true, false)->get();

            $this->assertNotContains('required',$form);
            $this->assertNotContains($icon,$form);
            $this->assertContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);
            $this->assertContains('<option value="19">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], '' , '' , $icon ,  true, false)->get();

            $this->assertNotContains('required',$form);
            $this->assertContains($icon,$form);
            $this->assertContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], '' , '' , '' ,  true, true)->get();

            $this->assertContains('required',$form);
            $this->assertNotContains($icon,$form);
            $this->assertContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);
            $this->assertContains('<option value="19">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], '' , '' , $icon ,  true, true)->get();

            $this->assertContains('required',$form);
            $this->assertContains($icon,$form);
            $this->assertContains('multiple',$form);
            $this->assertContains('name="age"',$form);
            $this->assertContains('<option value="15">15</option>',$form);
            $this->assertContains('<option value="18">18</option>',$form);



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

            $icon = fa('fas','fa-rocket');
            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append','btn-primary',"submit-id");

            $this->assertContains('class="btn btn-primary"',$form);
            $this->assertContains('id="submit-id"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertContains('append',$form);
            $this->assertNotEmpty($form);

            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append','btn-primary',"submit-id",$icon);

            $this->assertContains('class="btn btn-primary"',$form);
            $this->assertContains('id="submit-id"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertContains('append',$form);
            $this->assertContains($icon,$form);
            $this->assertNotEmpty($form);

            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append','btn-primary',"submit-id",$icon,Form::EDIT,1);

            $this->assertContains('class="btn btn-primary"',$form);
            $this->assertContains('id="submit-id"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertContains('append',$form);
            $this->assertContains($icon,$form);
            $this->assertNotEmpty($form);


            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append','',"submit-id",$icon,Form::EDIT,1);

            $this->assertContains('class="btn "',$form);
            $this->assertContains('id="submit-id"',$form);
            $this->assertContains('id="a"',$form);
            $this->assertContains('append',$form);
            $this->assertContains($icon,$form);
            $this->assertNotEmpty($form);

        }

        public function test_redirect()
        {
            $icon = fa('fas','fa-trash');
            $form = \form('a','a')->redirect('a',['a' => 2])->get();
            $this->assertNotContains($icon,$form);
            $this->assertContains('name="a"',$form);
            $this->assertContains('location',$form);

            $form = \form('a','a')->redirect('a',['a' => 2],$icon)->get();
            $this->assertContains($icon,$form);
            $this->assertContains('location',$form);
            $this->assertContains('name="a"',$form);
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

        public function test_padding()
        {

            $marge = 2;
            $form = form('a','a')->padding($marge)->input('text','name','username')->get();
            $this->assertContains("pt-$marge pb-$marge pl-$marge pr-$marge",$form);
        }
        public function test_margin()
        {
            $marge = 2;
            $form = form('a','a')->margin($marge)->input('text','name','username')->get();
            $this->assertContains("mt-$marge mb-$marge ml-$marge mr-$marge",$form);
        }

        public function test_margin_and_padding()
        {
            $marge = 2;
            $padding = 3;
            $form = form('a','a')->margin($marge)->padding($padding)->input('text','name','username')->get();
            $this->assertContains("mt-$marge mb-$marge ml-$marge mr-$marge pt-$padding pb-$padding pl-$padding pr-$padding",$form);
        }
        /**
         * @throws \Exception
         */
        public function test_exception()
        {

            $this->expectException(\Exception::class);
            (new Form())->validate()->padding(20)->get();
            (new Form())->validate()->margin(0)->get();
            form('a','a')->validate()->textarea('name','a',10,10)->get();
            form('a','a')->validate()->input(Form::TEXT,'a','a')->get();
            form('a','a')->validate()->select('a',['1',2,3])->get();
            form('a','adz')->validate()->textarea('a','adza',10,10)->get();
            form('a','adz')->generate(2,$this->table,$this->mysql()->tables(),'submit','btn-primary',id(),'',500,1);
            form('a','adz')->generate(2,$this->table,$this->mysql()->tables(),'submit','btn-primary',id(),'',500);
        }

    }
}
