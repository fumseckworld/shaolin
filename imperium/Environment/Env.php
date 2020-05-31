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

namespace Imperium\Environment {

    use Dotenv\Dotenv;
    use Dotenv\Repository\Adapter\EnvConstAdapter;
    use Dotenv\Repository\Adapter\PutenvAdapter;
    use Dotenv\Repository\RepositoryBuilder;

    /**
     *
     * Get multiples environment infos.
     *
     * @author Willy Micieli <fumseckworld@fumseck.eu>
     * @package Imperium\Environment\Env
     * @version 12
     *
     */
    class Env
    {


        /**
         *
         *
         * Get a environment value.
         *
         * Use the .env file, locate at the root directory.
         *
         * @param string $key
         *
         * @return string|array|false
         *
         */
        public static function get(string $key)
        {
            
            $repository = RepositoryBuilder::create()
            ->withReaders([
                new EnvConstAdapter(),
            ])
            ->withWriters([
                new EnvConstAdapter(),
                new PutenvAdapter(),
            ])
            ->immutable()
            ->make();

            $env =   Dotenv::create($repository, base(), '.env');
            $env->load();
            $env->required(
                [
                    'CACHE_TIME_DIVIDER', 'CACHE_DIRECTORY', 'CACHE_TTL',
                    'DEBUG', 'DB_DRIVER', 'DB_HOST', 'DB_NAME', 'DB_USERNAME',
                    'DB_PASSWORD', 'DEVELOP_DB_DRIVER', 'DEVELOP_DB_HOST',
                    'DEVELOP_DB_NAME', 'DEVELOP_DB_USERNAME', 'DEVELOP_DB_PASSWORD',
                    'TESTS_DB_NAME', 'TESTS_DB_USERNAME', 'TESTS_DB_PASSWORD', 'TESTS_DB_DRIVER', 'TESTS_DB_HOST',
                    'APP_NAME', 'APP_KEY', 'CIPHER', 'TRANSLATOR_EMAIL'
                ]
            );
            return getenv($key);
        }
    }
}
