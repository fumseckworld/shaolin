<?php


namespace Testing\Validator;


use Eywa\Exception\Kedavra;
use Eywa\Security\Validator\Validator;
use Eywa\Testing\Unit;

/**
 * Class ValidatorTest
 * @package Testing\Validator
 */
class ValidatorTest extends Unit
{
    /**
     *
     * The validator
     *
     */
    private Validator $validator;

    /**
     * @throws Kedavra
     */
    public function setUp(): void
    {
        $this->validator = new Validator(collect([]));
    }

    /**
     * @throws Kedavra
     */
    public function test_required()
    {
        $errors = $this->validate(['a'=>'b'])->required('content')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The content field is required',$errors[0]);
    }

    /**
     * @throws Kedavra
     */
    public function test_alpha()
    {
        $errors = $this->validate(['a'=>232145])->alpha('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a argument is not alpha',$errors[0]);
    }

    /**
     * @throws Kedavra
     */
    public function test_alphanumeric()
    {
        $errors = $this->validate(['a'=>true])->alphanumeric('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a argument is not alphanumeric',$errors[0]);

        $errors = $this->validate(['a'=>'a254kOPE'])->alphanumeric('a')->errors();
        $this->assertCount(0,$errors);
    }

    /**
     * @throws Kedavra
     */
    public function test_email()
    {
        $errors = $this->validate(['a'=>'a'])->email('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a email is not valid',$errors[0]);

        $errors = $this->validate(['a'=>'micieli@laposte.net'])->email('a')->errors();
        $this->assertCount(0,$errors);

    }

    /**
     * @throws Kedavra
     */
    public function test_digits()
    {
        $errors = $this->validate(['a'=>'aazazdazada'])->digits('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a argument is not a number',$errors[0]);

        $errors = $this->validate(['a'=>25412])->digits('a')->errors();
        $this->assertCount(0,$errors);

    }

    /**
     * @throws Kedavra
     */
    public function test_between()
    {
        $errors = $this->validate(['a'=>50])->between('a',0,5)->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a field must be between 0 and 5',$errors[0]);

        $errors = $this->validate(['a'=>2])->between('a',0,5)->errors();
        $this->assertCount(0,$errors);

    }

    /**
     * @throws Kedavra
     */
    public function test_lower()
    {
        $errors = $this->validate(['a'=>'AEAZD'])->lower('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a field must be in lowercase',$errors[0]);

        $errors = $this->validate(['a'=>'aedazaze'])->lower('a')->errors();
        $this->assertCount(0,$errors);

    }

    /**
     * @throws Kedavra
     */
    public function test_upper()
    {
        $errors = $this->validate(['a'=>'AEAZD'])->upper('a')->errors();
        $this->assertCount(0,$errors);


        $errors = $this->validate(['a'=>'aedazaze'])->upper('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a field must be in uppercase',$errors[0]);

    }

    /**
     * @throws Kedavra
     */
    public function test_define()
    {
        $errors = $this->validate(['a'=>''])->define('a')->errors();
        $this->assertCount(1,$errors);
        $this->assertEquals('The a field must not be empty',$errors[0]);

        $errors = $this->validate(['a'=>'aedazaze'])->define('a')->errors();
        $this->assertCount(0,$errors);
    }

    /**
     * @throws Kedavra
     */
    public function test_do()
    {
        $response =  $this->validate(['email'=>'micieli@laposte.net','username' => 'will','lastname'=> 'micieli','age'=>30])
            ->email('email')->alphanumeric('username','lastname')->between('age',0,100)
            ->do(function (){
                return app()->to('success');
            })  ;

        $this->assertTrue($response->to('/success'));
    }

    /**
     * @throws Kedavra
     */
    public function test_do_error()
    {
        $response =  $this->validate(['email'=>'micieli.laposte.net','username' => 'will','lastname'=> 'micieli','age'=>30])
            ->email('email')->alphanumeric('username','lastname')->between('age',0,100)
            ->do(function (){
                return app()->to('success');
            })  ;

        $this->assertTrue($response->to('/'));
    }


}