<?php


namespace Imperium\String {

    use Stringy\Stringy;

    class Str
    {
        /**
         *
         * @var Stringy
         *
         */
        private $string;

        public function __construct(string $data)
        {
            $this->string = Stringy::create($data);
        }
    }
}