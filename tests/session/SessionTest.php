<?php


namespace Testing\session {


    use Imperium\Session\ArraySession;
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
            $this->session = new ArraySession();
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
            $this->session->set('a','alex');

            $this->assertContains('alex',$this->session->all());
            $this->session->remove('a');
            $this->assertEquals([],$this->session->all());
        }
    }
}