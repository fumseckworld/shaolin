<?php

namespace Eywa\Http\Parameter\Uploaded {


    use Eywa\Exception\Kedavra;

    interface UploadedFileInterface
    {


        /**
         * UploadedFileInterface constructor.
         *
         * @param array<mixed> $file
         *
         * @throws Kedavra
         *
         */
        public function __construct(array $file = []);

        /**
         *
         * Get all uploaded file
         *
         * @return array<string>
         *
         */
        public function all(): array;

        /**
         *
         * Move files to dirs
         *
         * @param string $path
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function move(string $path): bool;


        /**
         *
         * Get all uploaded filename
         *
         * @return array<string>
         *
         */
        public function files(): array;

        /**
         *
         * Get all uploaded files types
         *
         * @return array<string>
         *
         */
        public function types(): array;

        /**
         *
         * Get all uploaded files size
         *
         * @return array<int>
         *
         */
        public function size(): array;

        /**
         *
         * Get all uploaded files errors
         *
         * @return array<int>
         */
        public function errors(): array;

        /**
         *
         * Get all uploaded files tmp name
         *
         * @return array<string>
         *
         */
        public function temporary(): array;

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
