<?php

namespace Imperium\Cache {

    /**
     * Class ZenCache
     *
     * @package Imperium\Cache
     */
    final class ZenCache
    {

        /**
         *
         * Put all php files inside the directory ond subdirectory in the opcache.
         *
         * @param string $directory The directory name.
         *
         * @return boolean
         */
        final public function add(string $directory): bool
        {
            $boolean = collect();
            foreach ($this->scan($directory) as $file) {
                $boolean->push(opcache_compile_file($file));
            }
            return $boolean->ok();
        }


        /**
         *
         * Clean the cache of a directory.
         *
         * @param string $directory
         *
         * @return bool
         */
        final public function clean(string $directory): bool
        {
            $boolean = collect();
            foreach ($this->scan($directory) as $file) {
                $boolean->push(opcache_invalidate($file, true));
            }
            return $boolean->ok();
        }

        /**
         * @return bool
         */
        final public function clearAll(): bool
        {
            return opcache_reset();
        }


        /**
         * @param string $directory
         *
         * @return array
         *
         */
        final private function scan(string $directory): array
        {
            $directory = base($directory);
            $excludeRegex = '~/\.git/~';
            $results = [];
            $ignore = ['.', '..'];

            $scanAll = is_dir($directory) ? scandir($directory) : [];

            if (is_array($scanAll)) {
                sort($scanAll);
            } else {
                $scanAll = [];
            }
            $scanDirs = [];
            $scanFiles = [];
            foreach ($scanAll as $fName) {
                if (!in_array($fName, $ignore)) {
                    $fPath = str_replace(
                        DIRECTORY_SEPARATOR,
                        DIRECTORY_SEPARATOR,
                        strval(realpath($directory . DIRECTORY_SEPARATOR . $fName))
                    );

                    if (preg_match($excludeRegex, $fPath . (is_dir($fPath) ? DIRECTORY_SEPARATOR : ''))) {
                        continue;
                    }

                    if (is_dir($fPath)) {
                        $scanDirs[] = $fPath;
                    } elseif (preg_match("/\.(php|php5)$/i", $fPath) == 1) {
                        $scanFiles[] = $fPath;
                    }
                }

                foreach ($scanDirs as $pDir) {
                    foreach ($this->scan($pDir) as $p) {
                        $results[] = $p;
                    }
                }
                foreach ($scanFiles as $p) {
                    $results[] = $p;
                }
            }
            return $results;
        }
    }
}
