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

namespace Imperium\Session {
    
    /**
     *
     * Manage all session content
     *
     * This packages has useful method to interact with the session.
     *
     * @package Imperium\Session\Session
     * @version 12
     * @author  Willy Micieli <fumseck@fumseck.org>
     *
     */
    class Session
    {
        /**
         * Session constructor.
         */
        public function __construct()
        {
            if (not_cli()) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            }
        }
        
        /**
         *
         * Get a session value by this key.
         *
         * @param string $key
         *
         * @return mixed
         *
         */
        public function get(string $key)
        {
            return $this->has($key) ? $_SESSION[$key] : '';
        }
        
        /**
         *
         * Define a value accessible by the given key inside the session.
         *
         * @param string $key   The session key.
         * @param mixed  $value The session value to store.
         *
         * @return Session
         *
         */
        public function set(string $key, $value): Session
        {
            $_SESSION[$key] = $value;
            return $this;
        }
        
        /**
         *
         * Check if the session has the given key.
         *
         * @param string $key The key to check.
         *
         * @return boolean
         *
         */
        public function has(string $key): bool
        {
            return array_key_exists($key, $_SESSION);
        }
        
        /**
         *
         * Remove all given key inside the session.
         *
         * @param mixed ...$keys All keys to remove.
         *
         * @return boolean
         *
         */
        public function del(...$keys): bool
        {
            $x = collect();
            
            foreach ($keys as $key) {
                if ($this->has($key)) {
                    unset($_SESSION[$key]);
                    $x->push(true);
                } else {
                    $x->push(false);
                }
            }
            return $x->ok();
        }
        
        /**
         * Return the session content.
         *
         * @return array
         */
        public function all(): array
        {
            return $_SESSION;
        }
        
        /**
         *
         * Remove all values inside the session.
         *
         * @return boolean
         *
         */
        public function clear(): bool
        {
            foreach ($this->all() as $value) {
                $this->del($value);
            }
            return empty($this->all());
        }
    }
}
