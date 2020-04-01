<?php

declare(strict_types=1);

namespace Eywa\Testing {

    use Eywa\Collection\Collect;
    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;
    use Eywa\Http\Routing\Router;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Session\ArraySession;
    use PHPUnit\Framework\TestCase;
    use ReflectionException;

    class Unit extends TestCase
    {
        /**
         *
         * Visit a page
         *
         * @param string $url
         * @param string $method
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        public function visit(string $url, string $method = GET): Response
        {
            return (new Router(new ServerRequest($url, $method)))->run();
        }

        /**
         * @param string $filename
         * @param string $mode
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function file(string $filename, string $mode = READ_FILE_MODE): File
        {
            return new File($filename, $mode);
        }

        /**
         *
         *
         * @param string $model
         *
         * @return Auth
         *
         * @throws Kedavra
         *
         */
        public function auth(string $model): Auth
        {
            return new Auth(new ArraySession(), $model);
        }

        /**
         * @param string $subject
         * @param string $message
         * @param string $author_email
         * @param string $to
         * @return Write
         *
         * @throws Kedavra
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return new Write($subject, $message, $author_email, $to);
        }

        /**
         *
         * @param array<mixed> $data
         *
         * @return Collect
         *
         */
        public function collect(array $data = []): Collect
        {
            return new Collect($data);
        }



        /**
         * @return Crypter
         */
        public function crypter(): Crypter
        {
            return new Crypter();
        }
    }
}
