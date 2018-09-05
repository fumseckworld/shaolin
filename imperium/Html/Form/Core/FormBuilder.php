<?php
/**
 * fumseck added Create.php to imperium
 * The 09/09/17 at 13:30
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
 */

namespace Imperium\Html\Form\Core {
    use Imperium\Databases\Eloquent\Tables\Table;
    use Imperium\Html\Form\Form;


    /**
     * Interface FormBuilder
     *
     * @package Imperium\Form\Core
     */
    interface FormBuilder
    {
        /**
         * start the form
         *
         * @param string $action
         * @param string $id
         * @param string|null $class
         * @param bool $enctype
         * @param string $method
         * @param string $charset
         *
         * @return Form
         */
        public function start(string $action,string $id,string $class = '', bool $enctype = false, string $method = Form::POST, string $charset = 'utf8'): Form;

        /**
         * generate a form
         *
         * @param int $form_grid
         * @param string $table
         * @param Table $instance
         * @param string $submitText
         * @param string $submitClass
         * @param string $submitId
         * @param string $submitIcon
         * @param int $mode
         * @param int $id
         *
         * @return string
         */
        public function generate(int $form_grid,string $table, Table $instance,string $submitText,string $submitClass,string $submitId,string $submitIcon = '',int $mode = Form::CREATE,int $id = 0): string;

        /**
         * start hidden input
         *
         * @return Form
         */
        public function startHide(): Form;

        /**
         * start an auto column line
         *
         * @return Form
         */
        public function startRow(): Form;

        /**
         * end of line
         *
         * @return Form
         */
        public function endRow(): Form;

        /**
         *  end of line and start a new row
         *
         * @return Form
         */
        public function endRowAndNew(): Form;

        /**
         * close hidden input
         *
         * @return Form
         */
        public function endHide(): Form;

        /**
         * generate a file input
         *
         * @param string $name
         * @param string $text
         * @param string $locale
         * @param string $ico
         *
         * @return Form
         */
        public function file(string $name, string $text, string $locale = 'en',string $ico = ''): Form;

        /**
         * define input size to large
         *
         * @param bool $large
         *
         * @return Form
         */
        public function setLargeInput(bool $large): Form;

        /**
         * define input size to small
         *
         * @param bool small
         *
         * @return Form
         */
        public function setSmallInput(bool $small): Form;

        /**
         * generate an input
         *
         * @param string $type
         * @param string $name
         * @param string $placeholder
         * @param string $success_text
         * @param string $error_text
         * @param string $icon
         * @param string $value
         * @param bool $required
         * @param bool $autofocus
         * @param bool $autoComplete
         * @return Form
         */
        public function input(string $type, string $name, string $placeholder,string $success_text = '',string $error_text ='',string $icon = '', string $value = '', bool $required = true  , bool $autofocus = false, bool $autoComplete = false): Form;



        /**
         * add csrf token in form
         *
         * @param string $csrf
         *
         * @return Form
         */
        public function csrf(string $csrf): Form;

        /**
         * generate a button
         *
         * @param string $type
         * @param string $text
         * @param string $class
         * @param string $icon
         *
         * @return Form
         */
        public function button(string $type,string $text, string $class, string $icon = ''): Form;

        /**
         * generate a button to reset the form
         *
         * @param string      $text
         * @param string      $class
         * @param string|null $icon
         *
         * @return Form
         */
        public function reset(string $text, string $class, string $icon = ''):Form;

        /**
         * generate a textarea
         *
         * @param string $name
         * @param string $placeholder
         * @param int $cols
         * @param int $row
         * @param bool $autofocus
         * @param string $value
         *
         * @return Form
         */
        public function textarea(string $name, string $placeholder, int $cols, int $row,bool $autofocus = false,string $value = ''): Form;

        /**
         * generate a image
         *
         * @param string      $src
         * @param string      $alt
         * @param string      $class
         * @param string      $width
         *
         * @return Form
         */
        public function img(string $src, string $alt, string $class = '', string $width = '100%'): Form;

        /**
         * call form builder
         *
         * @return Form
         */
        public static function create(): Form;

        /**
         * generate a submit button
         *
         * @param string $text
         * @param string $class
         * @param string $id
         * @param string $icon
         *
         * @return Form
         */
        public function submit(string $text, string $class, string $id,string $icon = ''): Form;

        /**
         * generate a link
         *
         * @param string      $url
         * @param string      $class
         * @param string      $text
         * @param string|null $icon
         *
         * @return Form
         */
        public function link(string $url, string $class, string $text, string $icon = ''): Form;

        /**
         * generate a select input
         *
         * @param string $name
         * @param array $options
         * @param string $success_text
         * @param string $error_text
         * @param string|null $icon
         *
         * @param bool $required
         * @param bool $multiple
         * @return Form
         */
        public function select(string $name, array $options,string $success_text = '',string $error_text= '',string $icon = '',bool $required = true,bool $multiple = false): Form;

        /**
         * generate a checkbox input
         *
         * @param string $name
         * @param string $text
         * @param string $class
         * @param bool $checked
         *
         * @return Form
         */
        public function checkbox(string $name, string $text,string $class = '',bool $checked = false): Form;

        /**
         * generate a radio input
         *
         * @param string $name
         * @param string $text
         * @param string $class
         * @param bool   $checked
         *
         * @return Form
         */
        public function radio(string $name, string $text, string $class, bool $checked = false): Form;

        /**
         * return the form
         *
         * @return string
         */
        public function end(): string;

        /**
         * return the form
         *
         * @return string
         */
        public function get(): string;

        /**
         * generate a redirect select
         *
         * @param string $name
         * @param array  $options
         * @param string $icon
         *
         * @return Form
         */
        public function redirectSelect(string $name, array $options, string $icon = ''): Form;


        /**
         * @return Form
         */
        public function validate(): Form;

   }
}