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

namespace Imperium\Configuration {

    use Imperium\Exception\Kedavra;
    use Symfony\Component\Yaml\Yaml;

    /**
     *
     * Represent a value of a configuration.
     *
     * This package contains all useful method to get the configurations values.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Configuration\Config
     * @version 12
     *
     * @property array $values The config values.
     * @property string $filename The config filename.
     * @property string $key  The config key name.
     *
     */
    final class Config extends Yaml
    {
        /**
         *
         * Check if file and key exists.
         *
         * @param string $file The config file.
         * @param string $key  The config key.
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $file, string $key)
        {
            $x = explode('.', $file);
            $file = $this->path() . DIRECTORY_SEPARATOR . reset($x) . '.yaml';

            if (!file_exists($file)) {
                throw new Kedavra(sprintf(
                    'The %s file has not been found in the %s directory',
                    $file,
                    $this->path()
                ));
            }

            $this->values = self::parseFile($file);

            if (!array_key_exists($key, $this->values)) {
                throw new Kedavra(sprintf(
                    'The %s key has not been found in the %s file in the %s directory',
                    $key,
                    $file,
                    $this->path()
                ));
            }

            $this->filename = $file;

            $this->key = $key;
        }

        /**
         *
         * Get the config directory path
         *
         * @return string
         *
         */
        private function path(): string
        {
            return base(imperium('config-directory', 'config'));
        }

        /**
         *
         * Get the config value
         *
         * @return mixed
         *
         */
        public function get()
        {
            return $this->values[$this->key];
        }
    }
}
