<?php
/**
 * fumseck added PaginationManagement.php to imperium
 * The 06/11/17 at 11:53
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
 *
 * @package : imperium
 * @author  : fumseck
 */


namespace Imperium\Html\Pagination;


interface PaginationManagement
{
    /**
     * start pagination
     *
     * @param int    $perPage
     * @param string $instance
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public static function paginate(int $perPage,string $instance): Pagination;

    /**
     * get next and prev meta-links
     *
     * @param string $path
     *
     * @return string
     */
    public   function getNextPrevLinks(string $path = '?') : string;

    /**
     * set pagination type
     *
     * @param int $type
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setType(int $type) : Pagination;


    /**
     * returns the limit for the data source
     *
     * @return string LIMIT-String for an SQL-query
     */
    public function getLimit(): string;

    /**
     * create the starting point for getLimit()
     *
     * @return int
     */
    public function getStart(): int;

    /**
     * create pagination
     *
     * @param string $path
     *
     * @return string
     */
    public function get(string $path = '?'): string;

     /**
      * create links as array
      *
      * @param string $path
      *
      * @return array
      */
    public function linksRaw(string $path = '?'): array;

    /**
     * set the adjacent
     *
     * @param int $adjacent
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setAdjacent(int $adjacent): Pagination;

    /**
     * set current page
     *
     * @param int $current
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setCurrent(int $current): Pagination;


    /**
     * define the end char
     *
     * @param string $endChar
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setEndChar(string $endChar): Pagination;

    /**
     * define the char char
     *
     * @param string $startChar
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setStartChar(string $startChar): Pagination;

    /**
     * define the end css class
     *
     * @param string $endClass
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setEndCssClass(string $endClass): Pagination;

    /**
     * define start css class
     *
     * @param string $startClass
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setLiCssClass(string $startClass): Pagination;

    /**
     * define ul pagination class
     *
     * @param string $ulClass
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setUlCssClass(string $ulClass): Pagination;

    /**
     * define total of records
     *
     * @param int $total
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setTotal(int $total): Pagination;

    /**
     * @param bool $bool
     *
     * @return \Imperium\Html\Pagination\Pagination
     */
    public function setWithLinkInCurrentLi(bool $bool): Pagination;

}