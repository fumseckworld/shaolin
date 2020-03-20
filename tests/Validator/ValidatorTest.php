<?php


namespace Testing\Validator {

    use App\Validators\Users\UsersValidator;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Testing\Unit;

    class ValidatorTest extends Unit
    {
        public function test()
        {
            $validator = new UsersValidator();
            $request = new Request();
            $this->assertTrue($request->validate($validator)->to('/error'));
        }

        /**
         * @throws Kedavra
         */
        public function test_success()
        {
            $validator = new UsersValidator();
            $request = new Request([
                'email' => 'micieli@laposte.net',
                'username' => 'james bond',
                'age' => '23'
            ]);
            $this->assertEquals('valid', $request->validate($validator)->content());
        }
    }
}
