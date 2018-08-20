<?php
/**
 * fumseck added Canvas.php to imperium
 * The 26/03/18 at 19:37
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


class Canvas implements CanvasManagement

{
    /**
     * canvas
     *
     * @var string
     */
    private $canvas = '';

    /**
     * off canvas links class
     *
     * @var string
     */
    private $class;

    /**
     * canvas position
     *
     * @var string
     */
    private $position;

    /**
     * off canvas identifier
     *
     * @var string
     */
    private $id;

    /**
     * the grid class
     *
     * @var string
     */
    private $gridClass;

    
	
    private $rowClass;

    /**
     * start canvas menu
     *
     * @return Canvas
     */
    public static function start(): Canvas
    {
        return new static();
    }

    /**
     * add a link in canvas
     *
     * @param string $text
     * @param string $href
     * @param string $id
     *
     * @return Canvas
     */
    public function add(string $text, string $href, string $id): Canvas
    {
        $this->canvas .= '<li> <a href="'.$href.'" class="'.$this->class.'" id="'.$id.'">'.$text.'</a></li>';

        return $this;
    }

    /**
     * define canvas link class
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function setLinkClass(string $class): Canvas
    {
        $this->class = $class;

        return $this;
    }

    /**
     * generate ul
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function startUl(string $class): Canvas
    {
        $this->canvas = '<ul  id="'.$this->id.'" class="navmenu '.$this->position.' '.$class.' offcanvas" role="navigation">';

        return $this;
    }

    /**
     * get canvas bar
     *
     * @return string
     */
    public function end(): string
    {
        $this->canvas .= '</ul>';

        return $this->canvas;
    }

    /**
     * define canvas position
     *
     * @param string $position
     *
     * @return Canvas
     */
    public function setPosition(string $position): Canvas
    {
        $this->position = $position;

        return $this;
    }

    /**
     * define off canvas id
     *
     * @param string $id
     *
     * @return Canvas
     */
    public function setId(string $id): Canvas
    {
        $this->id = $id;

        return $this;
    }

    /**
     * add form
     *
     * @param string ...$form
     *
     * @return Canvas
     */
    public function addForm(string ...$form): Canvas
    {
	$this->canvas .= '<div class="'.$this->rowClass.'">';

        foreach ($form as $item)
        {
            $this->canvas .= '<div class="'.$this->gridClass.'">';
                $this->canvas .= $item;
            $this->canvas .= '</div>';
        }
    	$this->canvas .= '</div>';

        return $this;
    }

    /***
     * define grid class
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function setGridClass(string $class): Canvas
    {
       $this->gridClass = $class;

       return $this;
    }

    /***
     * define grid class
     *
     * @param string $class
     *
     * @return Canvas
     */
    public function setRowClass(string $class): Canvas
    {
       $this->rowClass = $class;

       return $this;
    }
}
