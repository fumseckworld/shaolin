<?php

namespace Eywa\Http\Parameter\Uploaded {


    use Exception;
    use Eywa\Collection\Collect;
    use SplFileInfo;
    use wapmorgan\FileTypeDetector\Detector;

    class UploadedFile implements UploadedFileInterface
    {


        /**
         * All files uploaded
         *
         * @var array<string>
         */
        private array $files;

        /**
         *
         * All valid files
         *
         */
        private Collect $filename;

        /**
         *
         * All valid types
         *
         */
        private Collect $types;

        /**
         *
         * All valid file errors
         *
         */
        private Collect $errors;

        /**
         *
         * All valid files size
         *
         */
        private Collect $size;

        /**
         *
         * Tempory filename
         *
         */
        private Collect $temporary;

        /**
         *
         * The sum of all uploaded files
         *
         */
        private int $sum = 0;

        /**
         *
         * constructor.
         *
         * @param array<mixed> $files
         *
         *
         */
        public function __construct(array $files = [])
        {
            $this->filename = collect();
            $this->types = collect();
            $this->temporary = collect();
            $this->errors = collect();
            $this->size = collect();
            if (def($files)) {
                $i = 0;
                $this->sum = collect($files['files']['name'])->sum();

                do {
                    $file = $files['files'];

                    $current = $file['name'][$i];

                    $x = new SplFileInfo($current);


                    try {
                        $type = Detector::getMimeType($current);
                    } catch (Exception $exception) {
                        $type = false;
                    }


                    if (
                        $type
                        &&
                        ! in_array(
                            $x->getExtension(),
                            ['exe','php','c','rb','bat','js','perl','bash','sh','py','ts','']
                        )
                        && ! $x->isExecutable()
                        && ! in_array(
                            $type,
                            [
                                'application/javascript',
                                'application/typescript',
                                'application/java-archive',
                                'application/x-msdownload',
                                'text/plain'
                            ]
                        )
                    ) {
                        $this->filename->put($i, $current);

                        $this->types->put($this->filename->get($i), $file['type'][$i]);
                        $this->temporary->put($i, $file['tmp_name'][$i]);

                        $this->errors->put($this->filename->get($i), $file['error'][$i]);
                        $this->size->put($this->filename->get($i), $file['size'][$i]);
                    }
                    $i++;
                } while ($i < $this->sum);
            }
        }

        /**
         * @inheritDoc
         */
        public function move(string $path): bool
        {
            $path = base('web');

            foreach (explode(DIRECTORY_SEPARATOR, $path) as $dir) {
                append($path, DIRECTORY_SEPARATOR . $dir);

                if (!is_dir($path)) {
                    mkdir($path);
                }
            }

            is_false(is_dir($path), true, "The directory has been not found");

            $result = collect();

            $countfiles = $this->filename->sum();

            // Looping all files
            for ($i = 0; $i < $countfiles; $i++) {
                $filename = $this->filename->get($i);

                // Upload file
                $result->push(move_uploaded_file($this->temporary->get($i), $path . DIRECTORY_SEPARATOR . $filename));
            }
            return  $result->ok();
        }

        /**
         * @inheritDoc
         */
        public function valid(): bool
        {
            foreach ($this->errors() as $error) {
                if ($error != UPLOAD_ERR_OK) {
                    return  false;
                }
            }


            return  true;
        }



        /**
         * @inheritDoc
         */
        public function all(): array
        {
            return  $this->files;
        }

        /**
         * @inheritDoc
         */
        public function files(): array
        {
            return  $this->filename->all();
        }

        /**
         * @inheritDoc
         */
        public function types(): array
        {
            return $this->types->all();
        }

        /**
         * @inheritDoc
         */
        public function size(): array
        {
            return $this->size->all();
        }

        /**
         * @inheritDoc
         */
        public function errors(): array
        {
            return $this->errors->all();
        }

        /**
         * @inheritDoc
         */
        public function temporary(): array
        {
            return $this->temporary->all();
        }
    }
}
