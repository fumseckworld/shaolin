<?php
/**
 * fumseck added Icon.php to imperium
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



class Icon implements IconManagement
{


    /**
     * icon bar
     *
     * @var string
     */
    private $bar = '';

    /**
     * link class
     *
     * @var string
     */
    private $link;

    /**
     * icon class
     *
     * @var string
     */
    private $icon;

    /**
     * start icon bar
     *
     *
     * @return Icon
     */
    public static function start(): Icon
    {
        return new static();
    }


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
    public function add(string $icon, string $id, string $href,string $title,bool $offCanvas = false): Icon
    {
        if (empty($this->icon))
            $this->icon = 'icon';

        if (empty($this->link))
            $this->link = 'link';

        if ($offCanvas)
            $this->bar .= '<li><a   class="'.$this->link.'" data-toggle="offcanvas" data-target="'.$href.'" data-canvas="body" id="'.$id.'" title="'.$title.'"><span class="'.$this->icon.'"> '.$icon.'</span></a></li>';
        else
            $this->bar .= '<li><a href="'.$href.'" class="'.$this->link.'" id="'.$id.'"><span class="'.$this->icon.'"  title="'.$title.'"> '.$icon.'</span></a></li>';

        return $this;
    }

    /**
     * get icon bar
     *
     * @return string
     */
    public function end(): string
    {
        $this->bar .= '</ul>';

        return $this->bar;
    }

    /**
     * define icon class
     *
     * @param string $class
     *
     * @return Icon
     */
    public function setIconClass(string $class): Icon
    {
        $this->icon = $class;

        return $this;
    }

    /**
     * define link class
     *
     * @param string $class
     *
     * @return Icon
     */
    public function setLinkClass(string $class): Icon
    {
        $this->link = $class;

        return $this;
    }

    /**
     * generate ul
     *
     * @param string $class
     *
     * @return Icon
     */
    public function startUl(string $class): Icon
    {
        $this->bar .='<ul class="'.$class.'">';

        return $this;
    }
}