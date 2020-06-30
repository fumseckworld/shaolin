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

namespace Imperium\Configuration\Personalization {

    use Symfony\Component\Yaml\Yaml;

    /**
     *
     * Represent all user's personalization values.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Configuration\Personalization\Imperium
     * @version 12
     *
     * @property string  $key The personalization key.
     *
     */
    class Imperium extends Yaml
    {

        /**
         *
         * @param string $key The personalization key.
         *
         */
        public function __construct(string $key)
        {
            $this->key = $key;
        }

        /**
         *
         * Get an user's configuration value.
         *
         * @param string $default The default value if not exist.
         *
         * @return mixed
         */
        public function get(string $default = '')
        {
            $values = self::parseFile($this->file());

            if (empty($values)) {
                return $default;
            }
            return !array_key_exists($this->key, $values) ? $default : $values[$this->key];
        }

        /**
         *
         * Create teh file if not exist and return this path.
         *
         * @return string
         *
         */
        public function file(): string
        {
            return base('imperium.yaml');
        }
    }
}
