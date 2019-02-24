<?php

namespace Testing\flash {


    use Imperium\Flash\Flash;
    use PHPUnit\Framework\TestCase;

    class FlashTest extends TestCase
    {

        /**
         * @var Flash
         */
        private $flash;

        public function setUp(): void
        {
            $this->flash = new Flash();
        }

        /**
         * @throws \Exception
         */
        public function test_get()
        {
            $this->flash->success('linux was found');
            $this->flash->failure('Windows is bad');
            $this->assertEquals('linux was found',$this->flash->get(Flash::SUCCESS_KEY));
            $this->assertEquals('',$this->flash->get(Flash::SUCCESS_KEY));
            $this->assertEquals('Windows is bad',$this->flash->get(Flash::FAILURE_KEY));
            $this->assertEquals('',$this->flash->get(Flash::FAILURE_KEY));
        }

        /**
         * @throws \Exception
         */
        public function test_display()
        {
            $this->flash->success('linux was found');
            $this->flash->failure('Windows is bad');
            $this->assertStringContainsString('linux was found',$this->flash->display(Flash::SUCCESS_KEY));
            $this->assertEquals('',$this->flash->display(Flash::SUCCESS_KEY));
            $this->assertStringContainsString('Windows is bad',$this->flash->display(Flash::FAILURE_KEY));
            $this->assertEquals('',$this->flash->display(Flash::FAILURE_KEY));
        }


        public function test_has()
        {
            $this->flash->success('a');
            $this->assertTrue($this->flash->has('success'));
            $this->assertFalse($this->flash->has('failure'));
        }
        /**
         * @throws \Exception
         */
        public function test_exception()
        {
            $this->expectException(\Exception::class);

            $this->flash->success('a');
            $this->flash->get('a');
        }
    }
}