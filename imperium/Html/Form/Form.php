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
    /**
     * to generate a bootstrap form
     */
    const BOOTSTRAP  = 1;

    /**
     * to generate a foundation form
     */
    const FOUNDATION = 2;

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

    /**
     * @var int
     */
    private $type;

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
    public function start(string $action, string $id,string $class = '', bool $enctype = false, string $method = Form::POST, string $charset = 'utf8'): Form
    {
        if ($enctype)
        {
            if (empty($class))
                $this->form .= '<form action="'.$action.'" method="'. $method.'" accept-charset="'. $charset.'" enctype="multipart/form-data" id="'.$id.'">';
            else
                $this->form .= '<form action="'.$action.'" method="'. $method.'" accept-charset="'. $charset.'" class="'.$class.'" enctype="multipart/form-data" id="'.$id.'">';
        }
        else
        {
            if (empty($class))
                $this->form .= '<form action="'.$action.'" method="'. $method.'" accept-charset="'. $charset.'" id="'.$id.'">';
            else
                $this->form .= '<form action="'.$action.'" method="'. $method.'" accept-charset="'. $charset.'" class="'.$class.'"   id="'.$id.'">';
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
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                $this->form .= '<div class="d-none">';
            break;
            case Form::FOUNDATION:
                $this->form .= '<div class="hide">';
            break;

        }
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
     * @param string      $name
     * @param string      $class
     * @param string      $text
     * @param string|null $ico
     *
     * @return Form
     */
    public function file(string $name, string $class, string $text, string $ico = ''): Form
    {

        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                if (empty($ico))
                    $this->form .= '<div class="form-group"><label for="'.$name.'">'.$text.'</label><input type="file" class="form-control-file '.$class.'" id="'.$name.'"></div>';
                else
                    $this->form .= '<div class="form-group"><label for="'.$name.'">'. $ico .' '.$text.'</label><input type="file" class="form-control-file '.$class.'" id="'.$name.'"></div>';
            break;
            case Form::FOUNDATION:
                if (empty($ico))
                    $this->form .= '<label for="'.$name.'" class="'.$class.'">'.$text.'</label><input type="file" id="'.$name.'" class="show-for-sr">';
                else
                    $this->form .= '<label for="'.$name.'" class="'.$class.'">' .$ico .' ' .$text.'</label><input type="file" id="'.$name.'" class="show-for-sr">';
            break;
        }
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
     * @param string $dataFormId
     * @return string
     */
    private function generateInput(string $start, string $end, string $input,string $name, string $placeholder, string $value  ,bool $required , bool $autofocus  , bool $autoComplete,string $dataFormId ='')
    {
        if ($this->type == Form::BOOTSTRAP)
        {
            if(empty($this->inputSize) && $input != Form::FILE)
               $class = 'form-control';
            else
                $class = $this->inputSize;

            if ($input == Form::FILE)
                $class = 'form-control-file';

            if ($required) // WITH REQUIRED
            {

                if ($autofocus)
                {
                    if ($autoComplete)
                    {
                        return ''.$start.' <input type="'.$input.'" class="'.$class.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autofocus="autofocus" autocomplete="on" data-form-id="'.$dataFormId.'"> '.$end.'';
                    }
                    return ''.$start.' <input type="'.$input.'" class="'.$class.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autofocus="autofocus" autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';
                }
                if ($autoComplete)
                    return ''.$start.' <input type="'.$input.'" class="'.$class.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autocomplete="on" data-form-id="'.$dataFormId.'"> '.$end.'';
                else
                    return ''.$start.' <input type="'.$input.'" class="'.$class.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';

            } else
            {

                // WITHOUT REQUIRED
                if ($autofocus) // WITH AUTO FOCUS
                {
                    if ($autoComplete) // AUTO FOCUS , AND AUTO COMPLETE
                    {
                        return ''.$start.' <input type="'.$input.'" class="'.$class.'"  autofocus="autofocus" autocomplete="on" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                    }
                    return ''.$start.' <input type="'.$input.'" class="'.$class.'"  autofocus="autofocus" placeholder="'.$placeholder.'"  autocomplete="off" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                }else
                {   // WITHOUT AUTO FOCUS
                    if ($autoComplete) //   AUTO FOCUS , AND AUTO COMPLETE
                    {
                        return ''.$start.' <input type="'.$input.'" class="'.$class.'" autocomplete="on" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                    }   // AUTO FOCUS , WITHOUT AUTO COMPLETE
                    return ''.$start.' <input type="'.$input.'" class="'.$class.'" autocomplete="off" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                }
            }
        }

        # NOT BOOTSTRAP

        if ($required) // WITH REQUIRED
        {
            if ($autofocus)
            {
                if ($autoComplete)
                {
                    return ''.$start.' <input type="'.$input.'"  required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autofocus="autofocus" autocomplete="on" data-form-id="'.$dataFormId.'"> '.$end.'';
                }
                return ''.$start.' <input type="'.$input.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autofocus="autofocus" autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';
            }
            if ($autoComplete)
                return ''.$start.' <input type="'.$input.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autocomplete="on" data-form-id="'.$dataFormId.'"> '.$end.'';
            else
                return ''.$start.' <input type="'.$input.'" required="required" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';

        }
        else
        {
            // WITHOUT REQUIRED
            if ($autofocus) // WITH AUTO FOCUS
            {
                if ($autoComplete) //  REQUIRED , AUTO FOCUS , AND AUTO COMPLETE
                {
                    return ''.$start.' <input type="'.$input.'"  autofocus="autofocus" autocomplete="on" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                }   //  REQUIRED , AUTO FOCUS , WITHOUT AUTO COMPLETE

                return ''.$start.' <input type="'.$input.'"  autofocus="autofocus" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';
            }else
            {   // WITHOUT AUTO FOCUS
                if ($autoComplete) //     REQUIRED , AUTO FOCUS , AND AUTO COMPLETE
                {
                    return ''.$start.' <input type="'.$input.'" autocomplete="on" placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'" data-form-id="'.$dataFormId.'"> '.$end.'';
                }  //  REQUIRED , AUTO FOCUS , WITHOUT AUTO COMPLETE
                return ''.$start.' <input type="'.$input.'"   placeholder="'.$placeholder.'" name="'.$name.'" value="'.$value.'"  autocomplete="off" data-form-id="'.$dataFormId.'"> '.$end.'';
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
     * @param bool $tableOption
     * @param string $dataFormId
     * @return Form
     */
    public function input(string $type, string $name, string $placeholder, string $icon = '', string $value = '',bool $required = true  , bool $autofocus = false, bool $autoComplete = false,bool $tableOption = false,string $dataFormId =''): Form
    {


         switch ($this->type)
         {
             case Form::BOOTSTRAP:


                 if (empty($icon))
                 {
                     if ($tableOption)
                         $start = '<td><div class="form-group">';
                    else
                        $start = '<div class="form-group">';

                    if ($tableOption)
                         $end = "</div></td>";
                    else
                         $end = "</div>";

                    $this->form .= $this->generateInput($start,$end,$type,$name,$placeholder,$value,$required,$autofocus,$autoComplete,$dataFormId);
                 } else
                 {
                     if ($tableOption)
                         $start = '<td><div class="form-group"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div>';
                     else
                         $start = '<div class="form-group"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ';

                     $end = "</div></div>";
                     $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$dataFormId);
                 }
             break;
             default:
                 if (empty($icon))
                 {
                     if ($tableOption)
                        $start = '<td><div class="input-group">';
                     else
                        $start = '<div class="input-group">';

                     if ($tableOption)
                         $end = "</div></td>";
                     else
                         $end = "</div>";
                     $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$dataFormId);
                 } else
                 {
                     if ($tableOption)
                        $start = '<td><div class="input-group"><span class="input-group-label">' . $icon . '</span>';
                     else
                        $start = '<div class="input-group"><span class="input-group-label">' . $icon . '</span>';

                     if ($tableOption)
                         $end = "</div></td>";
                     else
                         $end = "</div>";

                     $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$dataFormId);
                 }
             break;
         }
        return $this;
    }

    /**
     * set form type
     *
     * @param int $type
     *
     * @return Form
     */
    public function setType(int $type): Form
    {
        $this->type = $type;

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
     * @param bool   $requiredOne
     * @param string $typeTwo
     * @param string $nameTwo
     * @param string $placeholderTwo
     * @param string $valueTwo
     * @param string $iconTwo
     * @param bool   $requiredTwo
     *
     * @return Form
     */
    public function twoInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= '<div class="col">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$valueOne,$iconOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$valueTwo,$iconTwo,$requiredTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneInputOneSelectTwoInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectName,array $selectOptions,string $selectIcon, string $typeThree,string $nameThree, string $placeholderThree,string $valueThree, string $iconThree, bool $requiredThree, string $typeFour, string $nameFour, string $placeholderFour, string $valueFour, string $iconFour, bool $requiredFour): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                       $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeFour,$nameFour,$placeholderFour,$iconFour,$valueFour,$requiredFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeFour,$nameFour,$placeholderFour,$iconFour,$valueFour,$requiredFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneInputOneSelectOneInputOneSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne,array $selectOptionsOne,string $selectIconOne, string $typeThree,string $nameThree, string $placeholderThree,string $valueThree, string $iconThree, bool $requiredThree, string $selectNameFour, array $selectOptionFour, string $selectIconFour): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                       $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameFour,$selectOptionFour,$selectIconFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameFour,$selectOptionFour,$selectIconFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneInputTwoSelectOneInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne,array $selectOptionsOne,string $selectIconOne, string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo, string $typeFour, $nameFour, string $placeholderFour,string $valueFour, string $iconFour, bool $requiredFour): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                       $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeFour,$nameFour,$placeholderFour,$iconFour,$valueFour,$requiredFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeFour,$nameFour,$placeholderFour,$iconFour,$valueFour,$requiredFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneInputThreeSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameOne,array $selectOptionsOne,string $selectIconOne, string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo,string $selectNameThree ,array $selectOptionsThree,string $selectIconThree): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                       $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneSelectThreeInput( string $selectName ,array $selectOptions ,string $selectIcon , string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne,string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo,string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                         $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneSelectTwoInputOneSelect( string $selectName ,array $selectOptions ,string $selectIcon , string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne,string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                         $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneSelectOneInputOneSelectOneInput(string $selectName ,array $selectOptions ,string $selectIcon , string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne,string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                         $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function oneSelectOneInputTwoSelect(string $selectName ,array $selectOptions ,string $selectIcon , string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo, string $selectNameThree,array $selectOptionsThree,string $selectIconThree): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-6 col-lg-6   col-sm-12 col-xl-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                         $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function threeInlineInputAndOneSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree,string $selectName,array $selectOptions,string $selectIcon): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';
                $this->form .= '</div>';
            break;
        }
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
    public function twoSelectTwoInput(string $selectNameOne,array $selectOptionsOne,string $selectIconOne,string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo,string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function twoSelectOneInputOneSelect(string $selectNameOne,array $selectOptionsOne,string $selectIconOne,string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo,string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $selectNameThree,array $selectOptionsThree,string $selectIconThree): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function threeSelectOneInput(string $selectNameOne,array $selectOptionsOne,string $selectIconOne,string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo,string $selectNameThree,array $selectOptionsThree,string $selectIconThree,string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne ): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                    $this->select($selectNameThree,$selectOptionsThree,$selectIconThree);
                $this->form .= '</div>';

                $this->form .= '<div class="columns small-12 large-3 medium-3">';
                $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function twoInputOneSelectOneInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo,string $selectName,array $selectOptions,string $selectIcon, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectName,$selectOptions,$selectIcon);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function twoInputTwoSelect(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo,string $selectNameOne,array $selectOptionsOne,string $selectIconOne,string $selectNameTwo,array $selectOptionsTwo,string $selectIconTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= ' <div class="col-md-3 col-lg-3   col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3  col-sm-12 col-xl-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameOne,$selectOptionsOne,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="columns small-12 large-3 medium-3">';
                        $this->select($selectNameTwo,$selectOptionsTwo,$selectIconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
        return $this;
    }

    /**
     * generate a button
     *
     * @param string      $text
     * @param string      $class
     * @param string      $icon
     * @param string      $type
     *
     * @return Form
     */
    public function button(string $text, string $class, string $icon = '',string $type = Form::BUTTON ): Form
    {
        switch ($type)
        {
            case Form::BUTTON:
                $this->form .='<button class="'.$class.'" type="button">  '.$icon.' ' .$text.'</button>';
            break;
            case Form::RESET:
                $this->form .='<button class="'.$class.'" type="reset">  '.$icon.' ' .$text.'</button>';
            break;
            case Form::SUBMIT:
                $this->form .='<button class="'.$class.'" type="submit">  '.$icon.' ' .$text.'</button>';
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
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                    $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                    $this->form .= '<div class=" col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                        $this->input($typefour,$nameFour,$placeholderFour,$iconFour,$valueFour,$requiredFour);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= ' <div class="columns small-12 large-6 medium-6">';
                        $this->input($typeOne,$nameOne,$placeholderOne,$iconOne,$valueOne,$requiredOne);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                        $this->input($typeTwo,$nameTwo,$placeholderTwo,$iconTwo,$valueTwo,$requiredTwo);
                    $this->form .= '</div>';

                    $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                        $this->input($typeThree,$nameThree,$placeholderThree,$iconThree,$valueThree,$requiredThree);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
        return $this;
    }

    public function fourInlineSelect(string $nameOne,array $optionsOne,string $iconOne,string $nameTwo,array $optionsTwo,string $iconTwo,string $nameThree,array $optionsThree,string $iconThree,string $nameFour,array $optionsFour,string $iconFour): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:

                $this->form .= '<div class="form-row">';

                $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                    $this->select($nameOne,$optionsOne,$iconOne);
                $this->form .= '</div>';

                $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                    $this->select($nameTwo,$optionsTwo,$iconTwo);
                $this->form .= '</div>';

                $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                    $this->select($nameThree,$optionsThree,$iconThree);
                $this->form .= '</div>';

                $this->form .= '<div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">';
                    $this->select($nameFour,$optionsFour,$iconFour);
                $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                $this->select($nameOne,$optionsOne,$iconOne);
                $this->form .= '</div>';

                $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                $this->select($nameTwo,$optionsTwo,$iconTwo);
                $this->form .= '</div>';

                $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                $this->select($nameThree,$optionsThree,$iconThree);
                $this->form .= '</div>';

                $this->form .= ' <div class="columns small-12 large-3 medium-3">';
                $this->select($nameFour,$optionsFour,$iconFour);
                $this->form .= '</div>';

                $this->form .= '</div>';

            break;
        }
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
     * @param string      $text
     * @param string      $class
     * @param string|null $icon
     *
     * @return Form
     */
    public function reset(string $text, string $class, string $icon = ''): Form
    {
        $this->form .= '<button class="'.$class.'" type="reset">  '.$icon.' ' .' '.$text.'</button>';

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
     * @param bool $tableOption
     * @return Form
     */
    public function textarea(string $name, string $placeholder, int $cols, int $row,bool $autofocus = false,string $value = '',bool $tableOption = false,string $dataFormId=''): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                if ($autofocus)
                {
                    if ($tableOption)
                    {
                        $this->form .= ' <td><div class="form-group"><textarea rows="'.$row.'"  cols="'.$cols.'" placeholder="'.$placeholder.'" autofocus="autofocus" class="form-control" required="required" name="'.$name.'" data-form-id="'.$dataFormId.'">'.$value.'</textarea></div></td>';

                    }else{
                        $this->form .= ' <div class="form-group"><textarea rows="'.$row.'"  cols="'.$cols.'" placeholder="'.$placeholder.'" autofocus="autofocus" class="form-control" required="required" name="'.$name.'" data-form-id="'.$dataFormId.'">'.$value.'</textarea></div>';
                    }
                } else {
                    if ($tableOption)
                    {
                        $this->form .= '<td><div class="form-group"><textarea rows="'.$row.'"  cols="'.$cols.'" placeholder="'.$placeholder.'" class="form-control" required="required" name="'.$name.'" data-form-id="'.$dataFormId.'">'.$value.'</textarea></div></td>';

                    }else{
                        $this->form .= '<div class="form-group"><textarea rows="'.$row.'"  cols="'.$cols.'" placeholder="'.$placeholder.'" class="form-control" required="required" name="'.$name.'" data-form-id="'.$dataFormId.'" >'.$value.'</textarea></div>';
                    }
                }
            break;
            case Form::FOUNDATION:
                if ($autofocus)
                {
                    if ($tableOption)
                    {
                        $this->form .= '<td><textarea rows="'.$row.'" cols="'.$cols.'" placeholder="'.$placeholder.'"  autofocus="autofocus" name="'.$name.'" data-form-id="'.$dataFormId.'"> '.$value.'</textarea></td>';
                    }else{
                        $this->form .= '<textarea rows="'.$row.'" cols="'.$cols.'" placeholder="'.$placeholder.'"  autofocus="autofocus" name="'.$name.' data-form-id="'.$dataFormId.'"'.$value.'</textarea>';
                    }
                } else {
                    if ($tableOption)
                    {
                        $this->form .= '<td><textarea rows="'.$row.'" cols="'.$cols.'" placeholder="'.$placeholder.'" name="'.$name.'" data-form-id="'.$dataFormId.'">'.$value.'</textarea></td>';
                    }else{
                        $this->form .= '<textarea rows="'.$row.'" cols="'.$cols.'" placeholder="'.$placeholder.'" name="'.$name.'" data-form-id="'.$dataFormId.'">'.$value.'</textarea>';
                    }

                }
            break;
        }
        return $this;
    }

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
    public function img(string $src, string $alt, string $class = '', string $width = '100%'): Form
    {
        if (empty($class))
            $this->form .= '<img src="'.$src.'" alt="'.$alt.'"  width="'.$width.'">';
        else
            $this->form .= '<img src="'.$src.'" alt="'.$alt.'" class="'.$class.'" width="'.$width.'">';

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
    public function submit(string $text, string $class, string $id,string $icon = ''): Form
    {

        if ($this->type == Form::BOOTSTRAP)
            $this->form .= '<div class="form-group"><button type="submit" class="'.$class.'" id="'.$id.'">'.$icon.' '.$text.'</button></div>';
        else
            $this->form .= '<div><button type="submit" class="'.$class.'" id="'.$id.'">'.$icon.' '.$text.'</button></div>';

        return $this;
    }

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
    public function link(string $url, string $class, string $text, string $icon = ''): Form
    {
        $this->form .= '<a href="'.$url.'" class="'.$class.'">  '. $icon .' '.$text .'</a>';

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
    public function select(string $name, array $options,string $icon = '',bool $multiple = false): Form
    {

        if(empty($this->inputSize))
            $class = 'form-control';
        else
            $class = $this->inputSize;

        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                if (empty($icon))
                {
                    if ($multiple)
                        $this->form .= '<div class="form-group"><select class="'.$class.'"  name="'.$name.'" multiple>';
                    else
                        $this->form .= '<div class="form-group"><select class="'.$class.'"  name="'.$name.'">';
                    foreach ($options as $value)
                    {
                        $this->form .= ' <option value="'.$value.'" class="'.$class.'"> '.$value.'</option>';
                    }
                    $this->form .= '</select></div>';

                } else {

                    if ($multiple)
                        $this->form .= '<div class="form-group"><div class="input-group"><div class="input-group-prepend"> <span class="input-group-text"> '.$icon.' </span></div>  <select class="'.$class.'"  name="'.$name.'" multiple>';
                    else
                        $this->form .= '<div class="form-group"><div class="input-group"><div class="input-group-prepend"> <span class="input-group-text"> '.$icon.' </span></div> <select class="'.$class.'"  name="'.$name.'">';

                    foreach ($options as $value)
                        $this->form .= '<option value="'.$value.'" class="'.$class.'"> '.$value.'</option>';

                    $this->form .= '</select> </div></div>';
                }

            break;
            case Form::FOUNDATION:

                if (empty($icon))
                {
                    if ($multiple)
                        $this->form .= ' <select name="'.$name.'" multiple>';
                    else
                        $this->form .= ' <select name="'.$name.'">';

                    foreach ($options as $value)
                        $this->form .= ' <option value="'.$value.'">'.$value.'</option>';

                    $this->form .= '</select>';
                } else {

                    if ($multiple)
                        $this->form .= '<div class="input-group"><span class="input-group-label">'.$icon.'</span><select class="input-group-field"  name="'.$name.'" multiple>';
                    else
                        $this->form .= '<div class="input-group"><span class="input-group-label">'.$icon.'</span><select class="input-group-field"  name="'.$name.'">';

                    foreach ($options as $value)
                        $this->form .= ' <option class="input-group-field" value="'.$value.'">'.$value.'</option>';

                    $this->form .= '</select></div>';
                }

            break;
        }
        return $this;
    }

    /**
     * @param string $nameOne
     * @param array  $optionsOne
     * @param string $iconOne
     * @param string $nameTwo
     * @param array  $optionsTwo
     * @param string $iconTwo
     *
     * @return Form
     */
    public function twoInlineSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                $this->form .= '<div class="row">';

                    $this->form .= '<div class="col-md-6">';
                        $this->select($nameOne,$optionsOne,$iconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-6">';
                        $this->select($nameTwo,$optionsTwo,$iconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:
                $this->form .= '<div class="row">';
                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->select($nameOne,$optionsOne,$iconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->select($nameTwo,$optionsTwo,$iconTwo);
                    $this->form .= '</div>';
                $this->form .= '</div>';
            break;
        }

        return $this;
    }

    /**
     * generate a checkbox input
     *
     * @param string $name
     * @param string $text
     * @param string $class
     * @param bool   $checked
     *
     * @return Form
     */
    public function checkbox(string $name, string $text, string $class, bool $checked = false): Form
    {
        if ($checked)
        {
            $this->form .=  '<div class="'.$class.'"><input id="'.$name.'" type="checkbox" checked="checked"><label for="'.$name.'"> '.$text.'</label> </div>' ;
        } else  {
            $this->form .=  '<div class="'.$class.'"><input id="'.$name.'" type="checkbox"><label for="'.$name.'"> '.$text.'</label> </div>';
        }
        return $this;
    }

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
    public function radio(string $name, string $text, string $class, bool $checked = false): Form
    {
        if ($checked)
        {
            $this->form .=  '<div class="'.$class.'">
                                      <label>
                                        <input type="radio" name="'.$name.'" checked="checked">
                                        '.$text.'
                                      </label>
                                 </div>' ;
        } else  {
            $this->form .=  '<div class="'.$class.'">
                                      <label>
                                        <input type="radio" name="'.$name.'">
                                        '.$text.'
                                      </label>
                                 </div>' ;
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
     * @param array  $options
     * @param string $icon
     *
     * @throws Exception
     * @return Form
     */
    public function redirectSelect(string $name, array $options, string $icon = ''): Form
    {


        if ($this->type == Form::BOOTSTRAP)
        {

            if (!empty($icon))
                $this->form .= '<div class="form-group"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div>';
            else
                $this->form .= '<div class="form-group">';

            if (!empty($this->inputSize))
                $this->form .= '<select class="form-control ' . $this->inputSize . '" name="' . $name . '" size="1"  onChange="location = this.options[this.selectedIndex].value">';
            else
                $this->form .= '<select class="form-control" name="' . $name . '" size="1"  onChange="location = this.options[this.selectedIndex].value">';

            foreach ($options as $k => $option)
                $this->form .= '<option value="'.$k.'"> '.$option.'</option>';


            if (!empty($icon))
                $this->form .=  '</select></div></div>';
            else
                $this->form .=  '</select></div>';

        }

        if ($this->type == Form::FOUNDATION)
        {
            if (!empty($icon))
                $this->form .= '<div class="input-group"><span class="input-group-label">'.$icon.'</span>';
            else
                $this->form .= '<div class="input-group">';


            $this->form .= '<select  name="'. $name.'" size="1"  onChange="location = this.options[this.selectedIndex].value" class="input-group-field">';

            foreach ($options as $k => $option)
                $this->form .= '<option value="'.$k.'"> '.$option.'</option>';


            $this->form .= '</select></div>';

        }

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
     * @throws Exception
     */
    public function twoRedirectSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                $this->form .= '<div class="row">';

                    $this->form .= '<div class="col-md-6">';
                        $this->redirectSelect($nameOne,$optionsOne,$iconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-6">';
                        $this->redirectSelect($nameTwo,$optionsTwo,$iconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;

            case Form::FOUNDATION:
                $this->form .= '<div class="row">';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->redirectSelect($nameOne,$optionsOne,$iconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->redirectSelect($nameTwo,$optionsTwo,$iconTwo);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
        return $this;
    }

    /**
     * generate one select and one input inline
     *
     * @param string      $selectName
     * @param array       $selectOptions
     * @param string      $selectIconOne
     * @param string      $type
     * @param string      $name
     * @param string      $placeholder
     * @param bool        $required
     * @param string      $iconTwo
     * @param string|null $value
     *
     * @return Form
     */
    public function oneSelectOneInput(string $selectName, array $selectOptions, string $selectIconOne, string $type, string $name, string $placeholder, bool $required, string $iconTwo , string $value): Form
    {
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                $this->form .= '<div class="row">';

                    $this->form .= '<div class="col-md-6">';
                        $this->select($selectName,$selectOptions,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-6">';
                        $this->input($type,$name,$placeholder,$value,$iconTwo,$required);
                    $this->form .= '</div>';

                $this->form .= '</div>';

            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->select($selectName,$selectOptions,$selectIconOne);
                    $this->form .= '</div>';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->input($type,$name,$placeholder,$value,$iconTwo,$required);
                    $this->form .= '</div>';

                $this->form .= '</div>';

            break;
        }
        return $this;
    }

    /**
     * generate one input and one select
     *
     * @param string      $type
     * @param string      $name
     * @param string      $placeholder
     * @param bool        $required
     * @param string      $inputIcon
     * @param string      $value
     * @param string      $selectName
     * @param array       $selectOptions
     * @param string      $selectIconOne
     *
     * @return Form
     */
    public function oneInputOneSelect(string $type, string $name, string $placeholder, bool $required, string $inputIcon, string $value, string $selectName, array $selectOptions, string $selectIconOne): Form
    {

        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                $this->form .= '<div class="row">';

                    $this->form .= '<div class="col-md-6">';
                        $this->input($type,$name,$placeholder,$inputIcon,$value,$required);
                    $this->form .= '</div>';

                    $this->form .= '<div class="col-md-6">';
                        $this->select($selectName,$selectOptions,$selectIconOne);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
            case Form::FOUNDATION:

                $this->form .= '<div class="row">';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->input($type,$name,$placeholder,$value,$inputIcon,$required);
                    $this->form .= '</div>';

                    $this->form .= '<div class="large-6 medium-6 small-12 columns">';
                        $this->select($selectName,$selectOptions,$selectIconOne);
                    $this->form .= '</div>';

                $this->form .= '</div>';
            break;
        }
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
    public function generate(string $table, Table $instance,string $submitText,string $submitClass,string $submitId,string $submitIcon = '',int $mode = Form::CREATE,int $id = 0): string
    {
        $instance = $instance->setName($table);
        $types    = $instance->getColumnsTypes();
        $columns  = $instance->getColumns();
        $primary  = $instance->primaryKey();


        if(is_null($primary))
        {
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

                    if($column != $primary)
                    {
                        $type = explode('(',$type);
                        $type = $type[0];

                        switch ($type)
                        {
                            case has($type,$number):
                                $this->input(Form::NUMBER,$column,$column,'',$record->$column);
                            break;
                            case has($type,$date):
                                $this->input(Form::DATETIME,$column,$column,'',$record->$column);
                            break;
                            default:
                                $this->textarea($column,$column,10,10,false,$record->$column);
                            break;

                        }
                    } else {


                        $this->input(Form::HIDDEN,$column,$column,'',$record->$column);

                    }
                }
            }
            $this->submit($submitText,$submitClass,$submitId,$submitIcon);
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

                    $type = explode('(',$type);

                    $type = $type[0];

                    switch ($type)
                    {
                        case has($type,$number):
                            $this->input(Form::NUMBER,$column,$column);
                        break;
                        case has($type,$date):
                            $this->input(Form::DATE,$column,$column,'',$current);
                        break;
                        default:
                            $this->textarea($column,$column,10,10);
                        break;
                    }
                } else {
                    $this->input(Form::HIDDEN,$column,$column);
                }

            }
            $this->submit( $submitText,$submitClass,$submitId,$submitIcon);
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
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                if ($large)
                    $this->inputSize = 'form-control form-control-lg';
                else
                   $this->inputSize = 'form-control';
            break;

        }
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
        switch ($this->type)
        {
            case Form::BOOTSTRAP:
                if ($small)
                    $this->inputSize = 'form-control form-control-sm';
                else
                    $this->inputSize = 'form-control';
                break;
        }
        return $this;
    }

    /**
     * generate an advanced records view
     *
     * @param string $table
     * @param Table $instance
     * @param array $records
     * @param string $action
     * @param string $tableClass
     * @param string $searchPlaceholder
     * @param string $tableUrlPrefix
     * @param int $limit
     * @param string $removeUrl
     * @param string $removeClassBtn
     * @param string $removeText
     * @param string $confirmRemoveText
     * @param string $removeIcon
     * @param string $csrf
     * @param int $textareaCols
     * @param int $textareaRow
     * @param bool $largeInput
     * @return string
     *
     * @throws Exception
     */
    public function generateAdvancedRecordView(string $table, Table $instance,array $records,string $action,string $tableClass,string $searchPlaceholder,string $tableUrlPrefix,int $limit,string $removeUrl,string $removeClassBtn,string $removeText,string $confirmRemoveText,string $removeIcon,string $csrf ='',int $textareaCols = 25,int  $textareaRow =  1,bool $largeInput = true): string
    {

        $instance = $instance->setName($table);
        $types    = $instance->getColumnsTypes();
        $columns  = $instance->getColumns();
        $primary  = $instance->primaryKey();


        if(is_null($primary))
        {
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


        if ($this->type == Form::BOOTSTRAP)
        {

            $tables =  [ '/' =>  $table ];

            foreach ($instance->show() as $x)
            {
                if(!has($x,$tables))
                    $tables = merge($tables,[  "$tableUrlPrefix$x" => $x]);
            }

            $redirect = $this->start('',uniqid())->setLargeInput($largeInput)->redirectSelect('table',$tables)->end();

            if ($largeInput)
            {
                $this->form = '<div class="row mt-5"><div class="col"> '.$redirect.'</div> <div class="col"><input type="number" class="form-control form-control-lg" min="1" value="'.$limit.'" onchange="location = this.value"></div></div><div class="table-responsive mt-4"><table class="'.$tableClass.'"><thead><tr>';

            }else
            {

                $this->form = '<div class="row mt-5"><div class="col"> '.$redirect.'</div> <div class="col"><input type="number" class="form-control" min="1" value="'.$limit.'" onchange="location = this.value"></div></div><div class="table-responsive mt-4"><table class="'.$tableClass.'"><thead><tr>';
            }
            $this->form .= '<script type="text/javascript">function sure(e,text){ if (!confirm(text)) {e.preventDefault()} }</script>';
             foreach ($columns as  $x)
            {
                if ($x != $primary)
                    $this->form .=  "<th> $x</th>";

            }

            $this->form .=  '<th>'.$removeText.' </th></tr></thead><tbody>';

            foreach ($records as $record)
            {
                $dataFormId = sha1("$table-{$record->$primary}");

                $this->form .= '<tr><form action="'.$action.'" method="get" id="'.$dataFormId.'" class="dynamic">';

                foreach ($columns as $k => $column)
                {


                    $type = $types[$k];

                    if (is_null($record->$column))
                        $record->$column = '';

                    if($column != $primary)
                    {
                        $type = explode('(',$type);
                        $type = $type[0];

                        switch ($type)
                        {
                            case has($type,$number):
                                $this->input(Form::NUMBER,$column,$column,'',$record->$column,true,false,false,true,$dataFormId);
                            break;
                            case has($type,$date):
                                $this->input(Form::DATETIME,$column,$column,'',$record->$column,true,false,false,true,$dataFormId);
                            break;
                            default:
                                $this->textarea($column,$column,$textareaCols,$textareaRow,false,$record->$column,true,$dataFormId);
                            break;
                        }
                    } else {
                        $this->input(Form::HIDDEN,$column,$column,'',$record->$column);

                    }

                }
                $this->form .= '<td><a href="'.$removeUrl.'" class="'.$removeClassBtn.'" data-confirm="'.$confirmRemoveText.'" onclick="sure(event,this.attributes[2].value)">'.$removeIcon.' </a></td></form></tr>';
            }

            $this->form .= '</tbody></table></div>';


        }

        return $this->form;
    }

    /**
     * generate a simply record view
     *
     * @param string $table
     * @param Table $instance
     * @param array $records
     * @param string $action
     * @param string $tableClass
     * @param string $searchPlaceholder
     * @param string $tableUrlPrefix
     * @param int $limit
     * @param bool $largeInput
     *
     * @return string
     */
    public function generateSimplyRecordView(string $table, Table $instance, array $records, string $action, string $tableClass, string $searchPlaceholder, string $tableUrlPrefix, int $limit, bool $largeInput = true): string
    {
        $this->form = '';
        return 'lore200';
    }
}


