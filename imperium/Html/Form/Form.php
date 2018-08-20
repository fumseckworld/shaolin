<?php
/**
 * fumseck added Form.php to imperium
 * The 16/09/17 at 17:22
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


namespace Imperium\Html\Form;

use Exception;
use Imperium\Databases\Eloquent\Tables\Table;
use Imperium\Html\Form\Core\FormBuilder;


class Form implements FormBuilder
{

    const GRID_ROW = 'form-row';

    const AUTO_COL = 'col';

    const BASIC_CLASS = 'form-control';

    const LARGE_CLASS = 'form-control-lg';

    const SMALL_CLASS = 'form-control-sm';

    const FORM_SEPARATOR = 'form-group';

    const CUSTOM_SELECT_CLASS = 'custom-select';

    const HIDE_CLASS = 'd-none';

    /**
     * to create a reset button
     */
    const RESET = 'reset';

    /**
     * to create a submit button
     */
    const SUBMIT = 'submit';

    /**
     * to create a form with post method
     */
    const POST = 'post';

    /**
     * to create a form with get method
     */
    const GET = 'post';

    /**
     * to create a button
     */
    const BUTTON = 'button';

    /**
     * to create a number input
     */
    const NUMBER = 'number';

    /**
     * to create an input hidden
     */
    const HIDDEN = 'hidden';
    /**
     * to create a text input
     */
    const TEXT = 'text';

    /**
     * to create a password input
     */
    const PASSWORD = 'password';

    /**
     * to create an email input
     */
    const EMAIL = 'email';

    /**
     * to create a date input
     */
    const DATE = 'date';

    /**
     * to create a datetime input
     */
    const DATETIME = 'datetime';

    /**
     * to create a phone input
     */
    const TEL = 'tel';

    /**
     * to create a url input
     */
    const URL = 'url';

    /**
     * to create a time input
     */
    const TIME = 'time';

    /**
     * to create a text input
     */
    const RANGE = 'range';

    /**
     * to create a color input
     */
    const COLOR = 'color';

    /**
     * to create a search input
     */
    const SEARCH = 'search';

    /**
     * to create a week input
     */
    const WEEK = 'week';

    /**
     * to create a checkbox input
     */
    const CHECKBOX = 'checkbox';

    /**
     * to create a radio input
     */
    const RADIO = 'radio';
    /**
     * option to get the result
     */
    const GET_TIME = 0;

    /**
     * option to have a datetime input
     */
    const GET_DATETIME = 1;

    /**
     * to create a files input
     */
    const FILE = 'file';


    /**
     * to create a datetime-local input
     */
    const DATETIME_LOCAL = 'datetime-local';

    /**
     * to create a image input
     */
    const IMAGE = 'image';

    /**
     * to create a month input
     */
    const MONTH = 'month';

    const EDIT = 3;

    const CREATE = 4;

    /**
     * @var string
     */
    private $form;


    private $inputSize;

    /**
     * start the form
     *
     * @param string $action
     * @param string $id
     * @param string $class
     * @param bool $enctype
     * @param string $method
     * @param string $charset
     *
     * @return Form
     */
    public function start(string $action, string $id, string $class = '', bool $enctype = false, string $method = Form::POST, string $charset = 'utf8'): Form
    {
        if ($enctype)
        {
            if (empty($class))
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" enctype="multipart/form-data" id="' . $id . '">';
            else
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="' . $class . '" enctype="multipart/form-data" id="' . $id . '">';
        } else {
            if (empty($class))
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" id="' . $id . '">';
            else
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="' . $class . '"   id="' . $id . '">';
        }


        return $this;
    }

    /**
     * start hidden input
     *
     * @return Form
     */
    public function startHide(): Form
    {

        $this->form .= '<div class="'.self::HIDE_CLASS.'>';

        return $this;
    }

    /**
     * close hidden input
     *
     * @return Form
     */
    public function endHide(): Form
    {
        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate a files input
     *
     * @param string $name
     * @param string $class
     * @param string $text
     * @param string|null $ico
     *
     * @param string $locale
     * @return Form
     */
    public function file(string $name, string $class, string $text, string $ico = '', string $locale = 'en'): Form
    {
        if (empty($ico))
            $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><div class="custom-file"><input type="file"  name="' . $name . '" class="custom-file-input"   lang="' . $locale . '"><label class="custom-file-label" for="customFile">' . $text . '</label></div></div>';
        else
            $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"  >' . $ico . '</span></div><div class="custom-file"><input type="file" name="' . $name . '" class="custom-file-input" lang="' . $locale . '"><label class="custom-file-label" for="customFile">' . $text . '</label></div></div></div>';

        return $this;
    }

    /**
     * @param string $start
     * @param string $end
     * @param string $input
     * @param string $name
     * @param string $placeholder
     * @param string $value
     * @param bool $required
     * @param bool $autofocus
     * @param bool $autoComplete
     * @return string
     */
    private function generateInput(string $start, string $end, string $input, string $name, string $placeholder, string $value, bool $required, bool $autofocus, bool $autoComplete)
    {

        if (empty($this->inputSize) && $input != Form::FILE)
            $class = self::BASIC_CLASS;
        else
            $class = $this->inputSize;

        if ($input == Form::FILE)
            $class = 'form-control-file';

        if ($required) // WITH REQUIRED
        {

            if ($autofocus) {
                if ($autoComplete) {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="on" > ' . $end . '';
                }
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="off" > ' . $end . '';
            }
            if ($autoComplete)
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="on" > ' . $end . '';
            else
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="off" > ' . $end . '';

        } else {

            // WITHOUT REQUIRED
            if ($autofocus) // WITH AUTO FOCUS
            {
                if ($autoComplete) // AUTO FOCUS , AND AUTO COMPLETE
                {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus" autocomplete="on" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > ' . $end . '';
                }
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus" placeholder="' . $placeholder . '"  autocomplete="off" name="' . $name . '" value="' . $value . '" > ' . $end . '';
            } else {   // WITHOUT AUTO FOCUS
                if ($autoComplete) //   AUTO FOCUS , AND AUTO COMPLETE
                {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="on" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > ' . $end . '';
                }   // AUTO FOCUS , WITHOUT AUTO COMPLETE
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="off" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > ' . $end . '';
            }
        }
    }

    /**
     * generate an input
     *
     * @param string $type
     * @param string $name
     * @param string $placeholder
     * @param string $icon
     * @param string $value
     * @param bool $required
     * @param bool $autofocus
     * @param bool $autoComplete
     *
     * @return Form
     */
    public function input(string $type, string $name, string $placeholder, string $icon = '', string $value = '', bool $required = true, bool $autofocus = false, bool $autoComplete = false): Form
    {

        if (empty($icon))
        {
            $start = '<div class="' . self::FORM_SEPARATOR . '">';

            $end = "</div>";

            $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete);
        } else {

            $start = '<div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ';

            $end = "</div></div>";

            $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete);
        }


        return $this;
    }


    /**
     * generate two inline input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     *
     * @return Form
     */
    public function twoInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate three inline input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     *
     * @return Form
     */
    public function threeInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';
            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';
        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one input one select and two input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     * @param string $typeFour
     * @param string $nameFour
     * @param string $placeholderFour
     * @param string $valueFour
     * @param string $iconFour
     * @param bool $requiredFour
     *
     * @return Form
     */
    public function oneInputOneSelectTwoInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectName, array $selectOptions, string $selectIcon, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree, string $typeFour, string $nameFour, string $placeholderFour, string $valueFour, string $iconFour, bool $requiredFour): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';
            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeFour, $nameFour, $placeholderFour, $iconFour, $valueFour, $requiredFour);
            $this->form .= '</div>';
        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one input one select one input one select
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     * @param string $selectNameFour
     * @param array $selectOptionFour
     * @param string $selectIconFour
     *
     * @return Form
     */
    public function oneInputOneSelectOneInputOneSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree, string $selectNameFour, array $selectOptionFour, string $selectIconFour): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';
            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameFour, $selectOptionFour, $selectIconFour);
            $this->form .= '</div>';
        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one input two select one input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $typeFour
     * @param $nameFour
     * @param string $placeholderFour
     * @param string $valueFour
     * @param string $iconFour
     * @param bool $requiredFour
     *
     * @return Form
     *
     */
    public function oneInputTwoSelectOneInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $typeFour, $nameFour, string $placeholderFour, string $valueFour, string $iconFour, bool $requiredFour): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';
            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeFour, $nameFour, $placeholderFour, $iconFour, $valueFour, $requiredFour);
            $this->form .= '</div>';
        $this->form .= '</div>';

        return $this;
    }


    /**
     * generate one input three select
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $selectNameThree
     * @param array $selectOptionsThree
     * @param string $selectIconThree
     *
     * @return Form
     */
    public function oneInputThreeSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $selectNameThree, array $selectOptionsThree, string $selectIconThree): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameThree, $selectOptionsThree, $selectIconThree);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one select three input
     *
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     *
     * @return Form
     */
    public function oneSelectThreeInput(string $selectName, array $selectOptions, string $selectIcon, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one select two input one select
     *
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     *
     * @return Form
     */
    public function oneSelectTwoInputOneSelect(string $selectName, array $selectOptions, string $selectIcon, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one select one input one select one input
     *
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     *
     * @return Form
     */
    public function oneSelectOneInputOneSelectOneInput(string $selectName, array $selectOptions, string $selectIcon, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';
            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one select one input two select
     *
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $selectNameThree
     * @param array $selectOptionsThree
     * @param string $selectIconThree
     *
     * @return Form
     */
    public function oneSelectOneInputTwoSelect(string $selectName, array $selectOptions, string $selectIcon, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $selectNameThree, array $selectOptionsThree, string $selectIconThree): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameThree, $selectOptionsThree, $selectIconThree);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate three inline input and one select
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     *
     * @return Form
     */
    public function threeInlineInputAndOneSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree, string $selectName, array $selectOptions, string $selectIcon): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }


    /**
     * generate two select and two input
     *
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     *
     * @return Form
     */
    public function twoSelectTwoInput(string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate two select one input one select
     *
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameThree
     * @param array $selectOptionsThree
     * @param string $selectIconThree
     *
     * @return Form
     */
    public function twoSelectOneInputOneSelect(string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameThree, array $selectOptionsThree, string $selectIconThree): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';

        $this->form .= ' <div class="'.self::AUTO_COL.'>';
            $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
        $this->form .= '</div>';

        $this->form .= ' <div class="' . self::AUTO_COL . '>';
            $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
        $this->form .= '</div>';

        $this->form .= ' <div class="' . self::AUTO_COL . '>';
            $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
        $this->form .= '</div>';

        $this->form .= ' <div class="' . self::AUTO_COL . '>';
            $this->select($selectNameThree, $selectOptionsThree, $selectIconThree);
        $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }


    /**
     * generate three select one input
     *
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $selectNameThree
     * @param array $selectOptionsThree
     * @param string $selectIconThree
     *
     * @return Form
     */
    public function threeSelectOneInput(string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo, string $selectNameThree, array $selectOptionsThree, string $selectIconThree, string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameThree, $selectOptionsThree, $selectIconThree);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate two inline input one select and one input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIcon
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     *
     * @return Form
     */
    public function twoInputOneSelectOneInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectName, array $selectOptions, string $selectIcon, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectName, $selectOptions, $selectIcon);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

        $this->form .= '</div>';


        return $this;
    }

    /**
     * generate two inline input and two select
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $selectNameOne
     * @param array $selectOptionsOne
     * @param string $selectIconOne
     * @param string $selectNameTwo
     * @param array $selectOptionsTwo
     * @param string $selectIconTwo
     *
     * @return Form
     */
    public function twoInputTwoSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectNameOne, array $selectOptionsOne, string $selectIconOne, string $selectNameTwo, array $selectOptionsTwo, string $selectIconTwo): Form
    {

        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameOne, $selectOptionsOne, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="' . self::AUTO_COL . '>';
                $this->select($selectNameTwo, $selectOptionsTwo, $selectIconTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate a button
     *
     * @param string $text
     * @param string $class
     * @param string $icon
     * @param string $type
     *
     * @return Form
     */
    public function button(string $text, string $class, string $icon = '', string $type = Form::BUTTON): Form
    {
        switch ($type) {
            case Form::BUTTON:
                $this->form .= '<button class="' . $class . '" type="button">  ' . $icon . ' ' . $text . '</button>';
            break;
            case Form::RESET:
                $this->form .= '<button class="' . $class . '" type="reset">  ' . $icon . ' ' . $text . '</button>';
            break;
            case Form::SUBMIT:
                $this->form .= '<button class="' . $class . '" type="submit">  ' . $icon . ' ' . $text . '</button>';
            break;

        }

        return $this;

    }

    /**
     * generate four inline input
     *
     * @param string $typeOne
     * @param string $nameOne
     * @param string $placeholderOne
     * @param string $valueOne
     * @param string $iconOne
     * @param bool $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool $requiredTwo
     * @param string $typeThree
     * @param string $nameThree
     * @param string $placeholderThree
     * @param string $valueThree
     * @param string $iconThree
     * @param bool $requiredThree
     * @param string $typefour
     * @param string $nameFour
     * @param string $placeholderFour
     * @param string $valueFour
     * @param string $iconFour
     * @param bool $requiredFour
     *
     * @return Form
     */
    public function fourInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree, string $typefour, string $nameFour, string $placeholderFour, string $valueFour, string $iconFour, bool $requiredFour): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeOne, $nameOne, $placeholderOne, $iconOne, $valueOne, $requiredOne);
            $this->form .= '</div>';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeTwo, $nameTwo, $placeholderTwo, $iconTwo, $valueTwo, $requiredTwo);
            $this->form .= '</div>';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typeThree, $nameThree, $placeholderThree, $iconThree, $valueThree, $requiredThree);
            $this->form .= '</div>';

            $this->form .= ' <div class="'.self::AUTO_COL.'>';
                $this->input($typefour, $nameFour, $placeholderFour, $iconFour, $valueFour, $requiredFour);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    public function fourInlineSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo, string $nameThree, array $optionsThree, string $iconThree, string $nameFour, array $optionsFour, string $iconFour): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

        $this->form .= ' <div class="'.self::AUTO_COL.'>';
            $this->select($nameOne, $optionsOne, $iconOne);
        $this->form .= '</div>';

        $this->form .= ' <div class="'.self::AUTO_COL.'>';
            $this->select($nameTwo, $optionsTwo, $iconTwo);
        $this->form .= '</div>';

        $this->form .= ' <div class="'.self::AUTO_COL.'>';
            $this->select($nameThree, $optionsThree, $iconThree);
        $this->form .= '</div>';

        $this->form .= ' <div class="'.self::AUTO_COL.'>';
            $this->select($nameFour, $optionsFour, $iconFour);
        $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * add csrf token in form
     *
     * @param string $csrf
     *
     * @return Form
     */
    public function csrf(string $csrf): Form
    {
        $this->form .= $csrf;

        return $this;
    }

    /**
     * generate a button to reset the form
     *
     * @param string $text
     * @param string $class
     * @param string|null $icon
     *
     * @return Form
     */
    public function reset(string $text, string $class, string $icon = ''): Form
    {
        $this->form .= '<button class="' . $class . '" type="reset">  ' . $icon . ' ' . ' ' . $text . '</button>';

        return $this;
    }

    /**
     * generate a textarea
     *
     * @param string $name
     * @param string $placeholder
     * @param int $cols
     * @param int $row
     * @param bool $autofocus
     * @param string $value
     * @return Form
     */
    public function textarea(string $name, string $placeholder, int $cols, int $row, bool $autofocus = false, string $value = ''): Form
    {

        if ($autofocus)
            $this->form .= ' <div class="' . self::FORM_SEPARATOR . '"><textarea rows="' . $row . '"  cols="' . $cols . '" placeholder="' . $placeholder . '" autofocus="autofocus" class="'.self::BASIC_CLASS.'" required="required" name="' . $name . '" >' . $value . '</textarea></div>';
        else
            $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><textarea rows="' . $row . '"  cols="' . $cols . '" placeholder="' . $placeholder . '" class="'.self::BASIC_CLASS.'" required="required" name="' . $name . '"  >' . $value . '</textarea></div>';

        return $this;
    }

    /**
     * generate a image
     *
     * @param string $src
     * @param string $alt
     * @param string $class
     * @param string $width
     *
     * @return Form
     */
    public function img(string $src, string $alt, string $class = '', string $width = '100%'): Form
    {
        if (empty($class))
            $this->form .= '<img src="' . $src . '" alt="' . $alt . '"  width="' . $width . '">';
        else
            $this->form .= '<img src="' . $src . '" alt="' . $alt . '" class="' . $class . '" width="' . $width . '">';

        return $this;
    }

    /**
     * call form builder
     *
     * @return Form
     */
    public static function create(): Form
    {
        return new static();
    }

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
    public function submit(string $text, string $class, string $id, string $icon = ''): Form
    {


        $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><button type="submit" class="' . $class . '" id="' . $id . '">' . $icon . ' ' . $text . '</button></div>';


        return $this;
    }

    /**
     * generate a link
     *
     * @param string $url
     * @param string $class
     * @param string $text
     * @param string|null $icon
     *
     * @return Form
     */
    public function link(string $url, string $class, string $text, string $icon = ''): Form
    {
        $this->form .= '<a href="' . $url . '" class="' . $class . '">  ' . $icon . ' ' . $text . '</a>';

        return $this;
    }

    /**
     * generate a select input
     *
     * @param string $name
     * @param array $options
     * @param string|null $icon
     *
     * @param bool $multiple
     * @return Form
     */
    public function select(string $name, array $options, string $icon = '', bool $multiple = false): Form
    {

        if (empty($this->inputSize))
            $class = self::CUSTOM_SELECT_CLASS;
        else
            $class = $this->inputSize;


        if (empty($icon))
        {
            if ($multiple)
                $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><select class="' . $class . '"  name="' . $name . '" multiple>';
            else
                $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><select class="' . $class . '"  name="' . $name . '">';
            foreach ($options as $value)
            {
                $this->form .= ' <option value="' . $value . '" class="' . $class . '"> ' . $value . '</option>';
            }
            $this->form .= '</select></div>';

        } else {

            if ($multiple)
                $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"> <span class="input-group-text"> ' . $icon . ' </span></div>  <select class="' . $class . '"  name="' . $name . '" multiple>';
            else
                $this->form .= '<div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"> <span class="input-group-text"> ' . $icon . ' </span></div> <select class="' . $class . '"  name="' . $name . '">';

            foreach ($options as $value)
                $this->form .= '<option value="' . $value . '" class="' . $class . '"> ' . $value . '</option>';

            $this->form .= '</select> </div></div>';
        }


        return $this;
    }

    /**
     * @param string $nameOne
     * @param array $optionsOne
     * @param string $iconOne
     * @param string $nameTwo
     * @param array $optionsTwo
     * @param string $iconTwo
     *
     * @return Form
     */
    public function twoInlineSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo): Form
    {

        $this->form .= '<div class="'.self::GRID_ROW.'">';

            $this->form .= '<div class="'.self::AUTO_COL.'">';
                $this->select($nameOne, $optionsOne, $iconOne);
            $this->form .= '</div>';

            $this->form .= '<div class="'.self::AUTO_COL.'">';
                $this->select($nameTwo, $optionsTwo, $iconTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';


        return $this;
    }

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
    public function checkbox(string $name, string $text, string $class, bool $checked = false): Form
    {
        if ($checked)

            $this->form .= '<div class="' . self::FORM_SEPARATOR . '"> <div class="custom-control custom-checkbox"><input type="checkbox"  checked="checked" class="custom-control-input " id="' . $name . '"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div> ';
        else
            $this->form .= '<div class="' . self::FORM_SEPARATOR . '"> <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input " id="' . $name . '"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div> ';

        return $this;
    }

    /**
     * generate a radio input
     *
     * @param string $name
     * @param string $text
     * @param string $class
     * @param bool $checked
     *
     * @return Form
     */
    public function radio(string $name, string $text, string $class, bool $checked = false): Form
    {
        if ($checked)
        {
            $this->form .= '<div class="' . $class . '">
                                      <label>
                                        <input type="radio" name="' . $name . '" checked="checked">
                                        ' . $text . '
                                      </label>
                                 </div>';
        } else {
            $this->form .= '<div class="' . $class . '">
                                      <label>
                                        <input type="radio" name="' . $name . '">
                                        ' . $text . '
                                      </label>
                                 </div>';
        }
        return $this;
    }

    /**
     * return the form
     *
     * @return string
     */
    public function end(): string
    {
        $this->form .= '</form>';

        return $this->form;
    }

    /**
     * generate a redirect select
     *
     * @param string $name
     * @param array $options
     * @param string $icon
     * @return Form
     */
    public function redirectSelect(string $name, array $options, string $icon = ''): Form
    {
        if (!empty($icon))
            $this->form .= ' <div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div>';
        else
            $this->form .= '<div class="' . self::FORM_SEPARATOR . '">';

        if (!empty($this->inputSize))
            $this->form .= '<select class="'.self::CUSTOM_SELECT_CLASS .' '. $this->inputSize . '" name="' . $name . '" onChange="location = this.options[this.selectedIndex].value">';
        else
            $this->form .= '<select class="'.self::CUSTOM_SELECT_CLASS .'" name="' . $name . '"   onChange="location = this.options[this.selectedIndex].value">';

        foreach ($options as $k => $option)
            $this->form .= '<option value="' . $k . '"> ' . $option . '</option>';


        if (!empty($icon))
            $this->form .= '</select></div></div>';
        else
            $this->form .= '</select></div>';

        return $this;
    }

    /**
     * generate two inline redirect select
     *
     * @param string $nameOne
     * @param array $optionsOne
     * @param string $iconOne
     * @param string $nameTwo
     * @param array $optionsTwo
     * @param string $iconTwo
     *
     * @return Form
     */
    public function twoRedirectSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->redirectSelect($nameOne, $optionsOne, $iconOne);
            $this->form .= '</div>';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->redirectSelect($nameTwo, $optionsTwo, $iconTwo);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate one select and one input inline
     *
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIconOne
     * @param string $type
     * @param string $name
     * @param string $placeholder
     * @param bool $required
     * @param string $iconTwo
     * @param string|null $value
     *
     * @return Form
     */
    public function oneSelectOneInput(string $selectName, array $selectOptions, string $selectIconOne, string $type, string $name, string $placeholder, bool $required, string $iconTwo, string $value): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->select($selectName, $selectOptions, $selectIconOne);
            $this->form .= '</div>';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->input($type, $name, $placeholder, $value, $iconTwo, $required);
            $this->form .= '</div>';

        $this->form .= '</div>';
        return $this;
    }

    /**
     * generate one input and one select
     *
     * @param string $type
     * @param string $name
     * @param string $placeholder
     * @param bool $required
     * @param string $inputIcon
     * @param string $value
     * @param string $selectName
     * @param array $selectOptions
     * @param string $selectIconOne
     *
     * @return Form
     */
    public function oneInputOneSelect(string $type, string $name, string $placeholder, bool $required, string $inputIcon, string $value, string $selectName, array $selectOptions, string $selectIconOne): Form
    {
        $this->form .= '<div class="' . self::GRID_ROW . '">';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->input($type, $name, $placeholder, $inputIcon, $value, $required);
            $this->form .= '</div>';

            $this->form .= '<div class="' . self::AUTO_COL . '">';
                $this->select($selectName, $selectOptions, $selectIconOne);
            $this->form .= '</div>';

        $this->form .= '</div>';

        return $this;
    }

    /**
     * generate a form
     *
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
     * @throws Exception
     */
    public function generate(string $table, Table $instance, string $submitText, string $submitClass, string $submitId, string $submitIcon = '', int $mode = Form::CREATE, int $id = 0): string
    {
        $instance = $instance->setName($table);
        $types = $instance->getColumnsTypes();
        $columns = $instance->getColumns();
        $primary = $instance->primaryKey();


        if (is_null($primary)) {
            throw new Exception('We have not found a primary key');
        }

        $number = array(
            'smallint',
            'integer',
            'bigint',
            'decimal',
            'numeric',
            'real',
            'double',
            'double precision',
            'smallserial',
            'serial',
            'integer',
            'int',
            'bigserial',
            'smallint',
            'float',
        );

        $date = array(
            'date',
            'datetime',
            'timestamp',
            'time',
            'interval',
            'real',
            'float4',
            'timestamp without time zone'
        );


        if ($mode == Form::EDIT)
        {
            $records = $instance->selectById($id);

            if (count($records) > 1)
                throw new Exception('The primary key are not unique');
            if (empty($records))
                throw  new Exception('Record was not found');

            foreach ($records as $record)
            {
                foreach ($columns as $k => $column)
                {
                    $type = $types[$k];

                    if (is_null($record->$column))
                        $record->$column = '';

                    if ($column != $primary)
                    {
                        $type = explode('(', $type);
                        $type = $type[0];

                        switch ($type)
                        {
                            case has($type, $number):
                                $this->input(Form::NUMBER, $column, $column, '', $record->$column);
                            break;
                            case has($type, $date):
                                $this->input(Form::DATETIME, $column, $column, '', $record->$column);
                            break;
                            default:
                                $this->textarea($column, $column, 10, 10, false, $record->$column);
                            break;

                        }
                    } else {
                        $this->input(Form::HIDDEN, $column, $column, '', $record->$column);
                    }
                }
            }
            $this->submit($submitText, $submitClass, $submitId, $submitIcon);
            return $this->end();
        }

        if ($mode == Form::CREATE)
        {
            foreach ($types as $k => $type)
            {
                $column = $columns[$k];

                if ($column != $primary)
                {

                    $current = date('Y-m-d');

                    $type = explode('(', $type);

                    $type = $type[0];

                    switch ($type)
                    {
                        case has($type, $number):
                            $this->input(Form::NUMBER, $column, $column);
                        break;
                        case has($type, $date):
                            $this->input(Form::DATE, $column, $column, '', $current);
                        break;
                        default:
                            $this->textarea($column, $column, 10, 10);
                        break;
                    }
                } else {
                    $this->input(Form::HIDDEN, $column, $column);
                }

            }
            $this->submit($submitText, $submitClass, $submitId, $submitIcon);
            return $this->end();
        }
        throw new Exception('missing mode edit or create');
    }

    /**
     * define input size to large
     *
     * @param bool $large
     *
     * @return Form
     */
    public function setLargeInput(bool $large): Form
    {
        if ($large)
            $this->inputSize = self::LARGE_CLASS . ' ' . self::BASIC_CLASS;
        else
            $this->inputSize = self::BASIC_CLASS;


        return $this;
    }

    /**
     * define input size to small
     *
     * @param bool small
     *
     * @return Form
     */
    public function setSmallInput(bool $small): Form
    {
        if ($small)
            $this->inputSize = self::SMALL_CLASS . ' ' . self::BASIC_CLASS;
        else
            $this->inputSize = self::BASIC_CLASS;

        return $this;
    }
}
