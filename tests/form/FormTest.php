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

        public function setUp(): void 
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
            $this->assertStringContainsString("enctype",$form);
            $this->assertStringContainsString('method="post"',$form);
            $this->assertStringContainsString('action="a"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringNotContainsString($class,$form);

            $form =  form('a','a',$class,'','POST',true)->get();
            $this->assertStringContainsString("enctype",$form);
            $this->assertStringContainsString('method="post"',$form);
            $this->assertStringContainsString('action="a"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString($class,$form);


            $form =  form('a','a','','','POST',false)->get();
            $this->assertStringNotContainsString("enctype",$form);
            $this->assertStringContainsString('method="post"',$form);
            $this->assertStringContainsString('action="a"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringNotContainsString($class,$form);

            $form =  form('a','a','',$class,'POST',false)->get();
            $this->assertStringNotContainsString("enctype",$form);
            $this->assertStringContainsString('method="post"',$form);
            $this->assertStringContainsString('action="a"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString($class,$form);

        }

        /**
         * @throws \Exception
         */
        public function test_hide()
        {
            $form =  form('a','a','','','POST',false)->hide()->input(Form::HIDDEN,'id','')->end_hide()->get();
            $this->assertStringContainsString(Form::HIDE_CLASS,$form);
            $this->assertStringContainsString(Form::HIDDEN,$form);
            $this->assertStringEndsWith('</div></form>',$form);

        }

        /**
         * @throws \Exception
         */
        public function test_file()
        {
            $ico = fa('fas','fa-file');
            $form =  form('a','a','','','POST',false)->file('sql','sql file')->get();
            $this->assertStringContainsString('name="sql"',$form);
            $this->assertStringContainsString('sql file',$form);
            $this->assertStringNotContainsString($ico,$form);

            $form =  form('a','a','','','POST',false)->file('sql','sql file',$ico)->get();

            $this->assertStringContainsString('name="sql"',$form);
            $this->assertStringContainsString('sql file',$form);
            $this->assertStringContainsString($ico,$form);
        }

        /**
         * @throws \Exception
         */
        public function test_textarea()
        {
            $form = form('a','a')->textarea('name','value','','',false)->get();

            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString('10',$form);

            $form = form('a','a')->textarea('name','value','','',false)->get();

            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString('10',$form);

            $form = form('a','a')->textarea('name','value','','',true)->get();

            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString('10',$form);

            $form = form('a','a')->textarea('name','value','','',true)->get();

            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString('10',$form);


            $form = form('a','a')->textarea('name','value','','',false)->get();

            $this->assertStringContainsString('placeholder="value"',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString('10',$form);
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

            $this->assertStringContainsString('confirm',$x);
            $this->assertStringContainsString($success,$x);
            $this->assertStringContainsString($fail,$x);

            $form = new Form();
            $x = $form->validate()->start('a','a','confirm','',true)->select(false,'select',['a','a'],$success,$fail)->get();

            $this->assertStringContainsString('confirm',$x);
            $this->assertStringContainsString($success,$x);
            $this->assertStringContainsString($fail,$x);

            $form = new Form();
            $x = $form->validate()->start('a','a','confirm','form-control',true)->textarea('name','name',$success,$fail)->get();
            $this->assertStringContainsString('confirm',$x);

            $this->assertStringContainsString($success,$x);
            $this->assertStringContainsString($fail,$x);
        }

        public function test_execp()
        {
            $this->expectException(Exception::class);

            $x = new Form();
            $x->validate()->start('a','a')->textarea('a','a')->get();
        }
        public function test_reset()
        {
            $icon = fa('fas','fa-linux');
            $form = \form('a','a')->reset('reset')->get();

            $this->assertStringContainsString('reset',$form);
            $this->assertStringContainsString('btn btn-lg btn-danger',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = \form('a','a')->reset('reset',$icon)->get();

            $this->assertStringContainsString('reset',$form);
            $this->assertStringContainsString('btn btn-lg btn-danger',$form);
            $this->assertStringContainsString($icon,$form);
        }

        /**
         * @throws \Exception
         */
        public function test_input()
        {
            $icon = fa('fas','fa-linux');

            $form = form('a','a')->input(Form::TEXT,'name','name')->get();

            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon)->get();

            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,false,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,true,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','maximus',false,false,true)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',false,true,true)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,false)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,true,false)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,true,true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,false,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,true,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','maximus',false,false,true)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('maximus',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);


            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',false,true,true)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name',$icon,'','','',true,false,false)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringContainsString($icon,$form);



            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,false,true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringNotContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,true,false)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringNotContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = form('a','a')->input(Form::TEXT,'name','name','','','','',true,true,true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString('autofocus',$form);
            $this->assertStringContainsString('autocomplete="on"',$form);
            $this->assertStringContainsString('placeholder="name"',$form);
            $this->assertStringContainsString('name="name"',$form);
            $this->assertStringNotContainsString($icon,$form);
        }
        /**
         * @throws \Exception
         */
        public function test_size()
        {

            $class = collection(config('form','class'))->get('submit');
            $form =  form('a','a')->large()->input(Form::TEXT,'sql','sql file')->submit('a','submit')->get();
            $this->assertStringContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);
            $this->assertStringContainsString($class,$form);

            $form =  form('a','a')->small()->input(Form::TEXT,'sql','sql file')->submit('a','a')->get();
            $this->assertStringContainsString(Form::SMALL_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringContainsString($class,$form);

            $form =  form('a','a')->large()->select(true,'table',[1,2,3])->get();
            $this->assertStringContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small()->select(false,'table',[1,2,3])->get();
            $this->assertStringContainsString(Form::SMALL_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);

            $form =  form('a','a')->large()->textarea('table','a',10,10)->get();
            $this->assertStringContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small()->textarea('table','a',10,10)->get();
            $this->assertStringContainsString(Form::SMALL_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);

            $form =  form('a','a')->large()->file('table','a')->get();
            $this->assertStringContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small()->file('table','a')->get();
            $this->assertStringContainsString(Form::SMALL_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);


            $form =  form('a','a')->large(false)->input(Form::TEXT,'sql','sql file')->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->input(Form::TEXT,'sql','sql file')->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);
            $form =  form('a','a')->large(false)->select(true,'table',[1,2,3])->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->select(false,'table',[1,2,3])->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->large(false)->textarea('table','a',10,10)->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->textarea('table','a',10,10)->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->large(false)->file('table','a')->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);

            $form =  form('a','a')->small(false)->file('table','a')->get();
            $this->assertStringContainsString(Form::BASIC_CLASS,$form);
            $this->assertStringNotContainsString(Form::LARGE_CLASS,$form);
            $this->assertStringNotContainsString(Form::SMALL_CLASS,$form);
        }

        public function test_checkbox()
        {
            $form = form('a','a')->checkbox('super','check me')->get();
            $this->assertStringContainsString('name="super"',$form);
            $this->assertStringContainsString('check me',$form);
            $this->assertStringNotContainsString('checked',$form);

            $form = form('a','a')->checkbox('super','check me')->get();
            $this->assertStringContainsString('name="super"',$form);
            $this->assertStringContainsString('check me',$form);
            $this->assertStringNotContainsString('checked',$form);

            $form = form('a','a')->checkbox('super','check me',true)->get();
            $this->assertStringContainsString('name="super"',$form);
            $this->assertStringContainsString('check me',$form);
            $this->assertStringContainsString('checked',$form);
        }

        public function test_button()
        {
            $form = form('a','a')->button(Form::SUBMIT,'submit')->get();

            $this->assertStringContainsString('type="submit"',$form);
            $form = form('a','a')->button(Form::RESET,'submit')->get();

            $this->assertStringContainsString('type="reset"',$form);
            $form = form('a','a')->button(Form::BUTTON,'submit')->get();

            $this->assertStringContainsString('type="button"',$form);
        }



        public function test_select()
        {
            $icon = fa('fas','fa-trash');

            $form =  form('a','a')->select(true,'age',[15,18,19],'','','',false,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString($icon,$form);
            $this->assertStringNotContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="0">15</option>',$form);
            $this->assertStringContainsString('<option value="1">18</option>',$form);
            $this->assertStringContainsString('<option value="2">19</option>',$form);

            $form =  form('a','a')->select(false,'age',[15,18,19],$icon,'','',false,false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringNotContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);


            $form =  form('a','a')->select(true,'age',  [15,18,19], '' , '' , '' ,  false, false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringNotContainsString($icon,$form);
            $this->assertStringNotContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="0">15</option>',$form);
            $this->assertStringContainsString('<option value="1">18</option>',$form);
            $this->assertStringContainsString('<option value="2">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], $icon , '' ,   '',false, false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringNotContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], $icon , '' , '' ,  true, false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);
            $this->assertStringContainsString('<option value="19">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], $icon , '' , '',  true, false)->get();

            $this->assertStringNotContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], $icon , '' , '' ,  true, true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);
            $this->assertStringContainsString('<option value="19">19</option>',$form);

            $form =  form('a','a')->select(false,'age',  [15,18,19], $icon , '' , '',  true, true)->get();

            $this->assertStringContainsString('required',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('multiple',$form);
            $this->assertStringContainsString('name="age"',$form);
            $this->assertStringContainsString('<option value="15">15</option>',$form);
            $this->assertStringContainsString('<option value="18">18</option>',$form);



        }

        public function test_radio()
        {

            $form = form('a','a')->radio('super','check me','a',true)->get();

            $this->assertStringContainsString('name="super"',$form);
            $this->assertStringContainsString('check me',$form);
            $this->assertStringContainsString('checked="checked"',$form);

            $form = form('a','a')->radio('super','check me','a')->get();
            $this->assertStringContainsString('name="super"',$form);
            $this->assertStringContainsString('check me',$form);

        }

        /**
         * @throws \Exception
         */
        public function test_generate()
        {

            $icon = fa('fas','fa-rocket');
            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append',"submit-id");

            $this->assertStringContainsString('id="submit-id"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString('append',$form);
            $this->assertNotEmpty($form);

            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append',"submit-id",$icon);

            $this->assertStringContainsString('id="submit-id"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString('append',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertNotEmpty($form);

            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append',"submit-id",$icon,Form::EDIT,1);

            $this->assertStringContainsString('id="submit-id"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString('append',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertNotEmpty($form);


            $form = form('a','a')->generate(2,$this->table,$this->mysql()->table(),'append',"submit-id",$icon,Form::EDIT,1);

            $this->assertStringContainsString('id="submit-id"',$form);
            $this->assertStringContainsString('id="a"',$form);
            $this->assertStringContainsString('append',$form);
            $this->assertStringContainsString($icon,$form);
            $this->assertNotEmpty($form);

        }

        public function test_redirect()
        {
            $icon = fa('fas','fa-trash');
            $form = \form('a','a')->redirect('a',['a' => 2])->get();
            $this->assertStringNotContainsString($icon,$form);
            $this->assertStringContainsString('name="a"',$form);
            $this->assertStringContainsString('location',$form);

            $form = \form('a','a')->redirect('a',['a' => 2],$icon)->get();
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('location',$form);
            $this->assertStringContainsString('name="a"',$form);
        }
        public function test_link()
        {
            $icon = fa('fas','fa-home');
            $form = \form('a','a')->link('/','home')->get();
            $this->assertStringContainsString('home',$form);
            $this->assertStringContainsString('href="/"',$form);
            $this->assertStringNotContainsString($icon,$form);

            $form = \form('a','a')->link('/','home',$icon)->get();
            $this->assertStringContainsString($icon,$form);
            $this->assertStringContainsString('home',$form);
            $this->assertStringContainsString('href="/"',$form);

        }

        public function test_padding()
        {

            $marge = config('form','padding');
            $form = form('a','a')->padding($marge)->input('text','name','username')->get();
            $this->assertStringContainsString("pt-$marge pb-$marge",$form);
        }
        public function test_margin()
        {
            $marge = config('form','margin');
            $form = form('a','a')->margin($marge)->input('text','name','username')->get();
            $this->assertStringContainsString("mt-$marge mb-$marge",$form);
        }

        public function test_margin_and_padding()
        {
            $marge = config('form','margin');
            $padding = config('form','padding');
            $form = form('a','a')->margin()->padding()->input('text','name','username')->get();
            $this->assertStringContainsString("mt-$marge mb-$marge pt-$padding pb-$padding",$form);
        }
        /**
         * @throws \Exception
         */
        public function test_exception()
        {

            $this->expectException(\Exception::class);
            (new Form())->validate()->padding()->get();
            (new Form())->validate()->margin()->get();
            form('a','a')->validate()->textarea('name','a')->get();
            form('a','a')->validate()->input(Form::TEXT,'a','a')->get();
            form('a','a')->validate()->select(true,'a',['1',2,3])->get();
            form('a','adz')->validate()->textarea('a','adza')->get();
            form('a','adz')->generate(2,$this->table,$this->mysql()->table(),'submit',id(),'',500,1);
            form('a','adz')->generate(2,$this->table,$this->mysql()->table(),'submit',id(),'',500);
        }

    }
}
