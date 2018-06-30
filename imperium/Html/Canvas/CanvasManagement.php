<?php
/**
 * fumseck added CanvasManagement.php to imperium
 * The 26/03/18 at 19:38
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
 */


namespace Imperium\Html\Canvas;


interface CanvasManagement
{
    /**
     * start canvas menu
     *
     *
     * @return Canvas
     */
    public static function start(): Canvas;

    /**
     * add a link in canvas
     *
     * @param string $text
     * @param string $href
     * @param string $id
     *
     * @return Canvas
     */
    public function add(string $text,string $href,string $id): Canvas;

    /**
     * add form
     *
     * @param string ...$form
     *
     * @return Canvas
     */
    public function addForm(string ...$form): Canvas;

    /***
     * define grid class
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function setGridClass(string $class): Canvas;

    /**
     * define canvas link class
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function setLinkClass(string $class): Canvas;

    /**
     * generate ul
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function startUl(string $class): Canvas;

    /**
     * define canvas position
     *
     * @param string $position
     *
     * @return Canvas
     */
    public function setPosition(string $position): Canvas;

    /**
     * define off canvas id
     *
     * @param string $id
     *
     * @return Canvas
     */
    public function setId(string $id): Canvas;


    /**
     * get canvas bar
     *
     * @return string
     */
    public function end(): string;
}