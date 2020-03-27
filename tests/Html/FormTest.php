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
        public function test_success()
        {
            $form = (new UsersForm())->make();
            $this->assertNotEmpty($form);
            $this->assertStringContainsString('/', $form);
            $this->assertStringContainsString('<input type="hidden" name="_method" class="hide" value="GET">', $form);
            $this->assertStringContainsString('<input type="hidden" name="csrf_token" class="hide" ', $form);
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
        public function test_validator()
        {
            $form = new UsersForm();

            $this->assertEmpty($form->check(new Request(['username' => 'aza']))->errors()->all());
            $this->assertTrue($form->check(new Request([]))->redirect()->to('/error'));
            $this->assertNotEmpty($form->check(new Request([]))->errors()->all());
            $this->assertCount(6, $form->check(new Request())->errors()->all());
        }
    }
}
