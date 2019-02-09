<?php
namespace Testing\oauth {


    use PHPUnit\Framework\TestCase;

    class OauthTest extends TestCase
    {

        /**
         * @var \Imperium\Oauth\Oauth
         */
        private $oauth;

        /**
         * @throws \Exception
         */
        public function setUp()
        {
            $this->oauth = new \Imperium\Oauth\Oauth(app()->connect(),app()->table());
        }


        public function test_user()
        {


            d($this->oauth->user());
        }
    }
}