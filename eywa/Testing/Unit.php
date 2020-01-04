<?php


namespace Eywa\Testing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Http\Routing\Router;
    use Eywa\Message\Email\Write;
    use GuzzleHttp\Psr7\ServerRequest;
    use PHPUnit\Framework\TestCase;

    class Unit extends TestCase
    {
        /**
         *
         * Visit a page
         *
         * @param string $url
         * @param string $method
         *
         * @return Router
         *
         */
        public function visit(string $url,string  $method = GET): Router
        {
            return new Router(new ServerRequest($method,$url));
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
        public function file(string $filename,string $mode = READ_FILE_MODE): File
        {
            return new File($filename,$mode);
        }

        /**
         * @param string $subject
         * @param string $message
         * @param string $author_email
         * @param string $to
         * @return Write
         *
         * @throws Kedavra
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return app()->write($subject,$message,$author_email,$to);
        }

        /**
         *
         * @param array $data
         *
         * @return Collect
         *
         */
        public function collect(array $data = []): Collect
        {
            return new Collect($data);
        }
    }
}