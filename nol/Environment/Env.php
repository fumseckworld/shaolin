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

namespace Nol\Environment {

    use Dotenv\Dotenv;
    use Dotenv\Repository\Adapter\EnvConstAdapter;
    use Dotenv\Repository\Adapter\PutenvAdapter;
    use Dotenv\Repository\RepositoryBuilder;

    /**
     *
     * Get multiples environment infos.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Environment\Env
     * @version 12
     *
     */
    class Env
    {


        public function __construct()
        {

            $repository = RepositoryBuilder::createWithNoAdapters()
                ->addAdapter(EnvConstAdapter::class)
                ->addWriter(PutenvAdapter::class)
                ->immutable()
                ->make();


            $dotenv = Dotenv::create($repository, base(), '.env');


            $dotenv->load();
        }

        /**
         *
         *
         * Get a environment value.
         *
         * Use the .env file, locate at the root directory.
         *
         * @param string $key The key to analyse.
         * @param mixed $default The default value if not found.
         *
         * @return string|array|false|null
         *
         */
        public function get(string $key, $default = null)
        {
            $x = getenv($key);
            return def($x) ? $x : $default;
        }
    }
}
