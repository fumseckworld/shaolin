<?php


namespace Testing\Html {

    use Eywa\Http\Request\FormRequest;
    use Eywa\Testing\Unit;

    class FormTest extends Unit
    {
        public function test_success()
        {
            $form = $this->form(new FormRequest('/', GET))->row()->add('username', 'text', 'username', 'must be uniq', ['autofocus' =>'autofocus'])->end()->row()->add('bio', 'textarea', 'bio')->end()->get('submit');
            $this->assertNotEmpty($form);
            $this->assertStringContainsString('/', $form);
            $this->assertStringContainsString('<input name="_method" class="hide" value="GET">', $form);
            $this->assertStringContainsString('<input name="csrf_token" class="hide" ', $form);
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
    }
}
