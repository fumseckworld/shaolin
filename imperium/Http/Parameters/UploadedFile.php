<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace Imperium\Http\Parameters {

    use Exception;
    use Imperium\Exception\Kedavra;
    use SplFileInfo;
    use wapmorgan\FileTypeDetector\Detector;

    /**
     *
     * Represent all uploaded files.
     *
     * This package contains all methods useful to manage $_FILES contents.
     *
     * @author Willy Micieli <fumseckworld@fumseck.eu>
     * @package Imperium\Http\Parameters\UploadedFile
     * @version 12
     *
     * @property array $data    The uploaded files values.
     * @property array $buffer  The buffer values
     * @property int   $sum     The sum of all uploaded files
     * @property array $files   All validate uploaded files.
     * @property array $types   All validate uploaded files type.
     * @property array $sizes   All validate uploaded files size.
     * @property array $errors  All validate uploaded files errors.
     *
     */
    class UploadedFile
    {

        /**
         *
         * Init and check all values.
         *
         * @param array $data All uploaded files values.
         *
         */
        public function __construct(array $data)
        {

            $this->data = $data;
            $this->buffer = [];
            $this->files = [];

            if (def($this->data)) {
                $this->files = $this->data['files']['name'];
                $this->types = $this->data['files']['type'];
                $this->buffer = $this->data['files']['tmp_name'];
                $this->errors = $this->data['files']['error'];
                $this->sizes = $this->data['files']['size'];
            }
        }

        /**
         *
         * Move the all valid uploaded file at the path
         *
         * @param string $path The directory to save uploaded files.
         *
         * @return boolean
         *
         */
        public function move(string $path): bool
        {
            $path = base($path);

            if (!is_dir($path)) {
                throw new Kedavra('The directory has been not found');
            }

            $result = [];

            $number_of_files = count($this->files);

            for ($i = 0; $i < $number_of_files; $i++) {
                $filename = $this->files[$i];

                $result[$i] = move_uploaded_file($this->buffer[$i], $path . DIRECTORY_SEPARATOR . $filename);
            }
            return  !in_array(false, $result);
        }

        /**
         *
         * Check the uploaded files status.
         *
         * Return true on success or false on failure.
         *
         * @return boolean
         */
        public function ok(): bool
        {
            foreach ($this->errors() as $error) {
                if ($error != UPLOAD_ERR_OK) {
                    return  false;
                }
            }
            return true;
        }

        /**
         *
         * Get all uploaded files errors values
         *
         * @return array
         *
         */
        public function errors(): array
        {
            return $this->errors;
        }

        /**
         *
         * Return all valid uploaded files.
         *
         * @return array
         *
         */
        public function all(): array
        {
            return $this->files;
        }

        /**
         *
         * Return all valid uploaded files type.
         *
         * @return array
         */
        public function types(): array
        {
            return $this->types;
        }

        /**
         *
         * Return all valid uploaded files sizes.
         *
         * @return array
         */
        public function sizes(): array
        {
            return $this->sizes;
        }
    }
}
