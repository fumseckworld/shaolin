<?php
/**
 * fumseck added IconManagement.php to imperium
 * The 26/03/18 at 18:05
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


namespace Imperium\Html\Bar;


interface IconManagement
{
    /**
     * start icon bar
     *
     *
     * @return Icon
     */
    public static function start(): Icon;

    /**
     * add a new icon
     *
     * @param string $icon
     * @param string $id
     * @param string $href
     * @param string $title
     * @param bool $offCanvas
     *
     * @return Icon
     */
    public function add(string $icon,string $id,string $href,string $title,bool $offCanvas = false): Icon;

    /**
     * define icon class
     *
     * @param string $class
     *
     * @return Icon
     */
    public function setIconClass(string $class): Icon;

    /**
     * define link class
     *
     * @param string $class
     *
     * @return Icon
     */
    public function setLinkClass(string $class): Icon;

    /**
     * generate ul
     *
     * @param string $class
     *
     * @return Icon
     */
    public function startUl(string $class): Icon;

    /**
     * get icon bar
     *
     * @return string
     */
    public function end(): string;

}