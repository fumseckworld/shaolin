<?php

namespace Testing\Html {

    use App\Forms\UsersForm;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Testing\Unit;

    class FormTest extends Unit
    {
        /**
         * @throws Kedavra
         */
        public function testSuccess()
        {
            $form = (new UsersForm())->make();
            $this->assertNotEmpty($form);
            $this->assertStringContainsString('/', $form);
            $this->assertStringContainsString('<input type="hidden" name="_method"  class="hide" value="GET">', $form);
            $this->assertStringContainsString('<input type="hidden" name="csrf_token"  class="hide" ', $form);
            $this->assertStringContainsString('type="text"', $form);
            $this->assertStringContainsString('textarea', $form);
            $this->assertStringContainsString('name="username"', $form);
            $this->assertStringContainsString('id="username"', $form);
            $this->assertStringContainsString('id="bio"', $form);
            $this->assertStringContainsString('name="bio"', $form);
            $this->assertStringContainsString('must be uniq', $form);
            $this->assertStringContainsString('autofocus="autofocus"', $form);
            $this->assertStringContainsString('submit"', $form);
        }

        /**
         * @throws Kedavra
         */
        public function testValidator()
        {
            $form = new UsersForm();
            $this->assertEmpty($form->validate((new Request(['username' => 'aza']))->request())->errors()->all());
            $this->assertTrue($form->validate((new Request(['username' => 'aza']))->request())->call()->to('/'));
            $this->assertTrue($form->validate((new Request())->request())->call()->to('/error'));
            $this->assertNotEmpty($form->validate((new Request())->request())->errors()->all());
            $this->assertCount(6, $form->validate((new Request())->request())->errors()->all());
        }
    }
}
