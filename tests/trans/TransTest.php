<?php


namespace Testing\trans {


    use PHPUnit\Framework\TestCase;

    class TransTest extends TestCase
    {

        public function test()
        {
            $this->assertEquals('Bienvenue Willy',trans('Welcome %s','Willy'));
            $this->assertEquals('Rechercher',trans('Search'));
            $this->assertEquals('Allez à Ibiza le lundi à dix heures',trans('Go at Ibiza at the %s at the %s hour','lundi','dix'));
        }
    }
}