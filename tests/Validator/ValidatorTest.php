<?php


namespace Testing\Validator {

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Testing\Unit;
    use Eywa\Validate\Validator;

    class ValidatorTest extends Unit
    {
        /**
         * @throws Kedavra
         */
        public function test_valid()
        {
            $rules = [
               'age' => 'required|between:1,100' ,
               'name' => 'required|unique:auth' ,
               'email' => 'required|email' ,
            ];

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli@laposte.net',
            ]);

            $this->assertTrue((new Validator($rules,$request))->capture()->valid());
        }

        /**
         * @throws Kedavra
         */
        public function test_fail()
        {
            $rules = [
               'age' => 'required|between:1,100' ,
               'name' => 'required|unique:auth' ,
               'email' => 'required|email' ,
            ];

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli',
            ]);

            $this->assertFalse((new Validator($rules,$request))->capture()->valid());
            $this->assertTrue((new Validator($rules,$request))->capture()->has('email'));
            $this->assertNotEmpty((new Validator($rules,$request))->capture()->message('email'));
        }
    }
}
