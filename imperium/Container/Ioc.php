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

namespace Imperium\Container {

     use Closure;
     use Imperium\Exception\Kedavra;

     /**
      *
      * Represent the core of the container of dependencies injection.
      *
      * This package contains all useful methods to manage the container content.
      *
      * It's the parent class of container class.
      *
      * @author Willy Micieli <fumseckworld@fumseck.eu>
      * @package Imperium\Container\Ioc
      * @version 12
      * @property array $make      All initialization information to make always a new instance.
      * @property array $init      All initialization information to make and save the instance.
      * @property array $symbol    All initialization information to make and save the instance based on a word.
      */
    class Ioc
    {

         /**
          * The array key to access at the key of one class inside the container for a specific mode.
          */
        public const KEY = 'key';

         /**
          *
          * The array key to access at the function to initialize one class
          * inside the container for a specific mode.
          *
          */
        public const CALLBACK = 'callable';

         /**
          *
          * The of the array key to access at the function arguments
          * to initialize one class inside the container for a specific mode.
          *
          */
        public const ARGV = 'arguments';

         /**
          *
          * Add information inside the container by a specific type.
          *
          * MAKE   => Save information to remake always a new instance.
          *
          * INIT   => Save information to save the instance.
          *
          * SYMBOL => Save information to save the instance based on a word.
          *
          * Only this types are accepted.
          *
          *
          * @param integer $type
          * @param string $key
          * @param Closure $callback
          * @param array $args
          *
          * @throws Kedavra
          *
          * @return Ioc
          *
          */
        public function add(int $type, string $key, Closure $callback, array $args = []): Ioc
        {
            if (!def($type, $key, $callback)) {
                throw new Kedavra('Parameters type, key, callback cannot be not define');
            }
            switch ($type) {
                case MAKE:
                    if (!class_exists($key)) {
                         throw new Kedavra('Class not found');
                    }
                    $this->make[] = [
                         self::KEY => $key,
                         self::CALLBACK => $callback,
                         self::ARGV => $args
                    ];
                    break;
                case INIT:
                    if (!class_exists($key)) {
                        throw new Kedavra('Class not found');
                    }
                     $this->init[] = [
                          self::KEY => $key,
                          self::CALLBACK => $callback,
                          self::ARGV => $args
                     ];
                    break;
                case SYMBOL:
                    if (!ctype_alpha($key)) {
                         throw new Kedavra('This mode should no take the namespace but a symbol');
                    }

                     $this->symbol[] = [
                          self::KEY => $key,
                          self::CALLBACK => $callback,
                          self::ARGV => $args
                     ];
                    break;
                default:
                    throw new Kedavra('The mode is invalid please use the correct mode');
            }
             return $this;
        }
    }
}
