<?php


namespace Testing\Message {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;

    class WriteTest extends Unit
    {
        /**
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         */
        public function test()
        {
            $this->assertTrue($this->write('simple', 'i am simple message', 'git@git.fumseck.eu', 'micieli@gmail.com')->send());
            $this->assertTrue($this->write('simple', 'i am simple message', 'git@git.fumseck.eu', 'micieli@gmail.com')->add_bcc('git@git.fumseck.eu', 'git')->cc('micieli@gmail.com')->bcc('micieli@gmail.com')->add_to('micieli@gmail.com', 'micieli')->add_cc('micieli@gmail.com', 'github')->sign()->send());
            $this->assertTrue($this->write('simple', 'i am simple message', 'git@git.fumseck.eu', 'micieli@gmail.com')->add_bcc('git@git.fumseck.eu', 'git')->cc('micieli@gmail.com')->bcc('micieli@gmail.com')->add_to('micieli@gmail.com', 'micieli')->add_cc('micieli@gmail.com', 'github')->sign()->send());
        }
    }
}
