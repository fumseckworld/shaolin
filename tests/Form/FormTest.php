<?php


namespace Testing\Form {

    use Eywa\Exception\Kedavra;
    use Eywa\Html\Form\Form;
    use Eywa\Testing\Unit;
    
    class FormTest extends Unit
    {


        /**
         * @throws Kedavra
         */
        public function test_with_input_form()
        {

            $form = (new Form('/'))->add('name','text','votre nom','entrer votre nom de famille')
                                        ->add('name','text','votre nom','entrer votre nom de famille')->get();


            $this->assertStringContainsString('<input type="hidden" name="_method" id="_method" value="POST">',$form);
            $this->assertStringContainsString('<input type="hidden" name="csrf_token" ',$form);


        }
    }
}
