<?php


namespace Testing\Validator {

    use App\Validators\Users\UsersValidator;
    use Eywa\Application\App;
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

            $this->assertEquals('valid',UsersValidator::validate($request)->content());
        }

        /**
         * @throws Kedavra
         */
        public function test_valid_call()
        {

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli@laposte.net',
            ]);

            $this->assertEquals('valid',$request->validate(UsersValidator::class)->content());
        }

        /**
         * @throws Kedavra
         */
        public function test_exception()
        {

            $request = new Request([
                'name' => 'willy',
                'age' => 31,
                'email' => 'micieli@laposte.net',
            ]);

            $this->expectException(Kedavra::class);
            $this->expectExceptionMessage('The class was not a validator');
            $request->validate(App::class);
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

            $this->assertFalse(UsersValidator::validate($request)->success());
            $this->assertTrue(UsersValidator::validate($request)->to(UsersValidator::$redirect_url));
            $this->assertNotEmpty((UsersValidator::message('email')));
        }

        /**
         * @throws Kedavra
         */
        public function test_fail_max()
        {

            $request = new Request([
                'name' => 'aadzazdazdazdazdaazaz',
                'age' => 31,
                'email' => 'micieli',
            ]);

            $this->assertFalse(UsersValidator::validate($request)->success());
            $this->assertTrue(UsersValidator::validate($request)->to(UsersValidator::$redirect_url));
            $this->assertNotEmpty((UsersValidator::message('email')));
            $this->assertNotEmpty((UsersValidator::message('name')));
        }

        /**
         * @throws Kedavra
         */
        public function test_fail_min()
        {

            $request = new Request([
                'name' => 'a',
                'age' => 31,
                'email' => 'micieli',
            ]);

            $this->assertFalse(UsersValidator::validate($request)->success());
            $this->assertTrue(UsersValidator::validate($request)->to(UsersValidator::$redirect_url));
            $this->assertNotEmpty((UsersValidator::message('email')));
            $this->assertNotEmpty((UsersValidator::message('name')));
        }
    }
}
