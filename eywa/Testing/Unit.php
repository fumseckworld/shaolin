<?php

declare(strict_types=1);
namespace Eywa\Testing {

    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Html\Form\Form;
    use Eywa\Http\Request\FormRequest;
    use Eywa\Http\Request\ServerRequest;
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
         * @return Router
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        public function visit(string $url, string  $method = GET): Router
        {
            return new Router(new ServerRequest($url, $method));
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
         *
         * Get an instance of auth
         *
         * @return Auth
         *
         */
        public function auth(): Auth
        {
            return new Auth(new ArraySession());
        }

        /**
         *
         * Initialize a form
         *
         * @param FormRequest $form_request
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function form(FormRequest $form_request): Form
        {
            return new Form($form_request);
        }

        /**
         * @return Crypter
         * @throws Kedavra
         */
        public function crypter(): Crypter
        {
            return new Crypter();
        }
    }
}
