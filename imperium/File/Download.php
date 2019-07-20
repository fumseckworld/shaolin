<?php


namespace Imperium\File {


    use Imperium\Exception\Kedavra;

    use Symfony\Component\HttpFoundation\Response;

    class Download
    {

        /**
         * @var File
         */
        private $filename;

        /**
         *
         * Download constructor.
         *
         * @param string $filename
         *
         * @throws Kedavra
         */
        public function __construct(string $filename)
        {
            $this->filename = new File($filename);
        }

        /**
         *
         * Download  a file
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function download(): Response
        {
            return $this->filename->download();
        }
    }
}