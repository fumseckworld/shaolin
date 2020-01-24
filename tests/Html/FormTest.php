<?php


namespace Testing\Html {


    use DI\DependencyException;
    use DI\NotFoundException;
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
         * @throws NotFoundException
         * @throws DependencyException
         */
    public function setUp(): void
    {
        $this->form = new Form('send');
    }


        /**
         * @throws Kedavra
         * @throws Kedavra
         */
    public function test_only()
    {
        $this->assertStringNotContainsString('username',$this->form->only(false,'username','text')->get());
        $this->assertStringContainsString('username',$this->form->only(true,'username','text')->get());
    }

    /**
     * @throws Kedavra
     */
    public function test_add()
    {
        $form = $this->form->add('username','text')->add('username','text')->add('email','email')->get();
        $this->assertStringContainsString('username',$form);
        $this->assertStringContainsString('email',$form);
    }

        /**
         * @throws Kedavra
         */
    public function test_select()
    {
        $this->assertStringContainsString('user',$this->form->row()->select('user',['user'])->end()->get());
    }

    /**
     * @throws Kedavra
     */
    public function test_add_with_rules()
    {
        $form = $this->form->add('username','text',['min'=> 5])->add('email','email',['max'=> 10])->get();
        $this->assertStringContainsString('max="10"',$form);
        $this->assertStringContainsString('min="5"',$form);
    }

    /**
     * @throws Kedavra
     */
    public function tests_globals()
    {
        $form = $this->form->add('username','text',['min'=> 3])->add('bio','textarea',['required' =>'required','cols'=> 10])->add('phone','phone')->get('send');
        $this->assertStringContainsString('send',$form);
        $this->assertStringContainsString('submit',$form);
        $this->assertStringContainsString('name="_method"',$form);
        $this->assertStringContainsString('value="POST"',$form);
        $this->assertStringContainsString('min="3"',$form);
        $this->assertStringContainsString('required="required"',$form);
        $this->assertStringContainsString('cols="10"',$form);
    }
}
}