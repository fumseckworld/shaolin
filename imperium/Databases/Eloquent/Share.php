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


use Imperium\Connexion\Connect;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;

trait Share
{

    /**
     * @var Table
     */
    private $tables;

    /**
     * the primary key
     *
     * @var string
     */
    private $primary;

    /**
     * @var Query
     */
    private $sql;

    /**
     * @var Connect
     */
    private $connexion;

    /**
     * current table
     *
     * @var string
     */
    private $table;


    /**
     * order by
     *
     * @var string
     */
    private $order;


    /**
     * the limit clause
     *
     * @var string
     */
    private $limit;

    /**
     * hidden values
     *
     * @var array
     */
    private $hidden;

    /**
     * the dump path
     *
     * @var string
     */
    private $path;
}