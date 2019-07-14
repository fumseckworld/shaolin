<?php


namespace Imperium\Testing {


    use DI\DependencyException;
    use DI\NotFoundException;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Cache\Cache;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Imperium\Model\Model;
    use Imperium\Routing\RouteResult;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class Unit extends TestCase
    {

        /**
         *
         * @param string $url
         * @param string $method
         *
         * @return RouteResult|RedirectResponse
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */

        public function visit(string $url,string $method = GET)
        {
           return  app()->router(new ServerRequest($method,$url))->search();
        }


        /**
         *
         * Get an instance of file
         *
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
         *
         * Get an instance of route model
         *
         * @return Model
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function route(): Model
        {
            return app()->routes();
        }

        /**
         *
         * Get the cache instance
         *
         * @return Cache
         *
         */
        public function cache(): Cache
        {
            return new Cache();
        }
    }
}