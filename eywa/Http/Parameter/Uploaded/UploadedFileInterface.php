<?php


namespace Eywa\Http\Parameter\Uploaded {


    use Eywa\Exception\Kedavra;

    interface UploadedFileInterface
    {

        /**
         * UploadedInterface constructor.
         *
         * @param array $file
         *
         * @throws Kedavra
         */
        public function __construct(array $file = []);

        /**
         *
         * Get all uploaded file
         *
         * @return array
         *
         */
        public function all(): array;

        /**
         *
         * Move files to dirs
         *
         * @param string ...$dirs
         *
         * @return bool
         *
         * @throws Kedavra
         *
         *
         */
        public function move(string ...$dirs): bool;


        /**
         *
         * Get all uploaded filename
         *
         * @return array
         *
         */
        public function files():array;

        /**
         *
         * Get all uploaded files types
         *
         * @return array
         *
         */
        public function types():array;

        /**
         *
         * Get all uploaded files size
         *
         * @return array
         *
         */
        public function size():array;

        /**
         *
         * Get all uploaded files errors
         *
         * @return array
         */
        public function errors():array;

        /**
         *
         * Get all uploaded files tmp name
         *
         * @return array
         *
         */
        public function temporary():array;

        /**
         *
         * Check if the upload is valid
         *
         * @return bool
         *
         */
        public function valid(): bool;

    }
}