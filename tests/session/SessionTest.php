<?php


namespace Testing\session {


    use Imperium\Session\Session;
    use PHPUnit\Framework\TestCase;

    class SessionTest extends TestCase
    {
        /**
         * @var Session
         */
        private $session;

        public function setUp(): void
        {
            $this->session = new Session();
        }

        public function test_get()
        {
            $this->session->set('a','a');
            $this->assertEquals('a',$this->session->get('a'));
        }

        public function test_not_exist()
        {
            $this->session->set('alexandra','a');
            $this->assertEquals('',$this->session->get('alex'));
        }

        public function test_remove()
        {
            $this->session->set('alex','a');

            $this->assertContains('alex',$this->session->all());
            $this->session->remove('a');
            $this->assertEquals([],$this->session->all());
        }
    }
}