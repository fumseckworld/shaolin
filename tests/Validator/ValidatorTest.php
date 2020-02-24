<?php


namespace Testing\Validator {

    use App\Validator\UsersValidator;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Testing\Unit;

    class ValidatorTest extends Unit
    {
        /**
         * @throws Kedavra
         */
        public function test_valid()
        {

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli@laposte.net',
            ]);

            $this->assertEquals('valid',UsersValidator::check($request)->content());
        }

        /**
         * @throws Kedavra
         */
        public function test_fail()
        {

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli',
            ]);

            $this->assertFalse(UsersValidator::check($request)->success());
            $this->assertTrue(UsersValidator::check($request)->to(UsersValidator::$redirect_url));
            $this->assertNotEmpty((UsersValidator::message('email')));
        }
    }
}
