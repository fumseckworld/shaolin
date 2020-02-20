<?php


namespace Testing\Html {


    use Eywa\Exception\Kedavra;
    use Eywa\Html\Form\Form;
    use Eywa\Testing\Unit;

    class FormTest extends Unit
    {
        /**
         *
         * The form query builder
         *
         */
        private Form $form;

        /**
         * @throws Kedavra
         */
        public function setUp(): void
        {
            $this->form = new Form('send', GET);
        }


        /**
         * @throws Kedavra
         * @throws Kedavra
         */
        public function test_only()
        {
            $this->assertStringNotContainsString('username', $this->form->only(false, 'username', 'text', 'your name')->get());
            $this->assertStringContainsString('username', $this->form->only(true, 'username', 'text', 'your username')->get());
        }

        /**
         * @throws Kedavra
         */
        public function test_add()
        {
            $form = $this->form->add('username', 'text', 'username')->add('username', 'text', 'username')->add('email', 'email', 'email')->get();
            $this->assertStringContainsString('username', $form);
            $this->assertStringContainsString('email', $form);
        }

        /**
         * @throws Kedavra
         */
        public function test_select()
        {
            $this->assertStringContainsString('user', $this->form->row()->select('user', ['user'])->end()->get());
        }

        /**
         * @throws Kedavra
         */
        public function test_add_with_rules()
        {
            $form = $this->form->add('username', 'text', 'username', ['min' => 5])->add('email', 'email', 'email', ['max' => 10])->get();
            $this->assertStringContainsString('max="10"', $form);
            $this->assertStringContainsString('min="5"', $form);
        }

        /**
         * @throws Kedavra
         */
        public function tests_globals()
        {
            $form = $this->form->add('username', 'text', 'username', ['min' => 3])->add('bio', 'textarea', 'bio', ['required' => 'required', 'cols' => 10])->add('phone', 'phone', 'phone')->get('send');
            $this->assertStringContainsString('send', $form);
            $this->assertStringContainsString('submit', $form);
            $this->assertStringContainsString('name="_method"', $form);
            $this->assertStringContainsString('method="POST"', $form);
            $this->assertStringContainsString('value="GET"', $form);
            $this->assertStringContainsString('min="3"', $form);
            $this->assertStringContainsString('required="required"', $form);
            $this->assertStringContainsString('cols="10"', $form);
        }
    }
}