<?php
/**
 * fumseck added Share.php to imperium
 * The 11/09/17 at 09:40
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 **/


namespace Imperium\Databases\Eloquent;


trait Share
{
    /**
     * @var string $driver
     */
    private $driver;
    /**
     * @var string $database
     */
    private $database;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $rights
     */
    private $rights;

    /**
     * @var array $hidden
     */
    private $hidden;

    /**
     * @var string $encoding
     */
    private $encoding;

    /**
     * @var string $collation
     */
    private $collation;

    /**
     * @var string $dump
     */
    private $path;
}