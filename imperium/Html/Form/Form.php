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
use Imperium\Tables\Table;

class Form
{

    const GRID_ROW = 'row';

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
    const GET = 'get';

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
     * @var bool
     */
    private $validate;

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
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '"  id="' . $id . '" enctype="multipart/form-data">';
            else
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class .'" id="' . $id . '" enctype="multipart/form-data">';
        } else {
            if (empty($class))
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" id="' . $id . '">';
            else
                $this->form .= '<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class .'" id="' . $id . '">';
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

        $this->form .= '<div class="'.self::HIDE_CLASS.'">';

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
     * generate a file input
     *
     * @param string $name
     * @param string $text
     * @param string $locale
     * @param string $ico
     *
     * @return Form
     */
    public function file(string $name, string $text, string $locale = 'en',string $ico = ''): Form
    {
        if (empty($ico))
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"><div class="custom-file"><input type="file"  name="' . $name . '" class="custom-file-input"   lang="' . $locale . '"><label class="custom-file-label" for="customFile">' . $text . '</label></div></div></div>';
        else
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"  >' . $ico . '</span></div><div class="custom-file"><input type="file" name="' . $name . '" class="custom-file-input" lang="' . $locale . '"><label class="custom-file-label" for="customFile">' . $text . '</label></div></div></div></div>';

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
     * @param string $success_text
     * @param string $error_text
     *
     * @return string
     *
     * @throws Exception
     */
    private function generateInput(string $start, string $end, string $input, string $name, string $placeholder, string $value, bool $required, bool $autofocus, bool $autoComplete,string $success_text = '',string $error_text = '')
    {

        $size = $this->inputSize;

        if (def( $size))
            $class = $size;
        else
            $class = self::BASIC_CLASS;


        if ($input === Form::FILE)
            $class =  $class .' form-control-file';


        if ($this->validate)
        {
            if (not_def($error_text,$success_text))
                throw new Exception('missing validation text');
            else
                $validation =  $this->valid($success_text,$error_text);

 
        }
        else{
            $validation = '';
        }
        if ($required) // WITH REQUIRED
        {

            if ($autofocus)
            {
                if ($autoComplete)
                {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="on" > ' . $validation . $end .'';
                }
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="off" > '  . $validation . $end .'';
            }
            if ($autoComplete)
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="on" > ' .    $end . $validation . '';
            else
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="off" > ' . $validation . $end .'';

        } else {

            // WITHOUT REQUIRED
            if ($autofocus) // WITH AUTO FOCUS
            {
                if ($autoComplete) // AUTO FOCUS , AND AUTO COMPLETE
                {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus" autocomplete="on" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                }
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus" placeholder="' . $placeholder . '"  autocomplete="off" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
            } else {   // WITHOUT AUTO FOCUS
                if ($autoComplete) //   AUTO FOCUS , AND AUTO COMPLETE
                {
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="on" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                }   // AUTO FOCUS , WITHOUT AUTO COMPLETE
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="off" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
            }
        }
    }

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
     *
     * @return Form
     * @throws Exception
     */
    public function input(string $type, string $name, string $placeholder,string $success_text = '',string $error_text ='', string $icon = '', string $value = '', bool $required = true, bool $autofocus = false, bool $autoComplete = false): Form
    {
        if (empty($icon))
        {

            $start = '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR.'">';

            $end = "</div></div>";

            $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$success_text,$error_text);
        } else {

            $start = '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ';

            $end = "</div></div></div> ";

            $this->form .= $this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$success_text,$error_text);
        }


        return $this;
    }



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
    public function button(string $type,string $text, string $class, string $icon = ''): Form
    {
        switch ($type)
        {
            case Form::BUTTON:
                $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><button class="' . $class . '" type="button">  ' . $icon . ' ' . $text . '</button></div></div>';
            break;
            case Form::RESET:
                $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><button class="' . $class . '" type="reset">  ' . $icon . ' ' . $text . '</button></div></div>';
            break;
            case Form::SUBMIT:
                $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><button class="' . $class . '" type="submit">  ' . $icon . ' ' . $text . '</button></div></div>';
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
     * @param string $text
     * @param string $class
     * @param string|null $icon
     *
     * @return Form
     */
    public function reset(string $text, string $class, string $icon = ''): Form
    {
        $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><button class="' . $class . '" type="reset">  ' . $icon . ' ' . ' ' . $text . '</button></div></div>';

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
            $this->form .= ' <div class="'.self::AUTO_COL.'">   <div class="' . self::FORM_SEPARATOR  .'"><textarea rows="' . $row . '"  cols="' . $cols . '" placeholder="' . $placeholder . '" autofocus="autofocus" class="'.self::BASIC_CLASS.'" required="required" name="' . $name . '" >' . $value . '</textarea></div></div>';
        else
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR.'"><textarea rows="' . $row . '"  cols="' . $cols . '" placeholder="' . $placeholder . '" class="'.self::BASIC_CLASS.'" required="required" name="' . $name . '"  >' . $value . '</textarea></div></div>';

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

        if (!empty($class))
            $this->form .= '<div class="'.self::FORM_SEPARATOR.'"><div class="'.self::AUTO_COL.'"><img src="' . $src . '" alt="' . $alt . '"  width="' . $width . '" class="'.$class.'"></div></div>';
        else
            $this->form .= '<div class="'.self::FORM_SEPARATOR.'"><div class="'.self::AUTO_COL.'"><img src="' . $src . '" alt="' . $alt . '"  width="' . $width . '"></div></div>';

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

        $this->form .= '<div class="'.self::AUTO_COL.'">  <div class="' . self::FORM_SEPARATOR . '"><button type="submit" class="' . $class . '" id="' . $id . '" name="'.$id.'">' . $icon . ' ' . $text . '</button></div></div>';


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
        $this->form .= '<div class="'.self::AUTO_COL.'"> <div class="'.self::FORM_SEPARATOR.'"><a href="' . $url . '" class="' . $class . '">  ' . $icon . ' ' . $text . '</a></div></div>';

        return $this;
    }

    private function valid(string $success,string $error)
    {
        return '<div class="valid-feedback"> ' .$success.'</div><div class="invalid-feedback">'.$error.'</div>' ;
    }

    /**
     * generate a select input
     *
     * @param string      $name
     * @param array       $options
     * @param string      $success_text
     * @param string      $error_text
     * @param string|null $icon
     *
     * @param bool        $required
     * @param bool        $multiple
     *
     * @return Form
     */
    public function select(string $name, array $options,string $success_text = '',string $error_text= '',string $icon = '',bool $required = true, bool $multiple = false): Form
    {
        $size = $this->inputSize;
        if (def($size))
            $class = $size . ' ' . self::CUSTOM_SELECT_CLASS;
        else
            $class = self::CUSTOM_SELECT_CLASS;


        if ($this->validate)
            $validation = $this->valid($success_text,$error_text);
        else
            $validation = '';

        if ($required)
        {
            if (empty($icon))
            {
                if ($multiple)
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR.'"><select class="' . $class . '"  name="' . $name . '" multiple required="required">';
                else
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR .'"><select class="' . $class . '"  name="' . $name . '" required="required">';
                foreach ($options as $k=> $v)
                {
                    if (is_string($k) || is_numeric($k))
                        $this->form .= '<option value="'.$k.'"> '.$v.'</option>';
                    else
                        $this->form .= '<option value="'.$v.'"> '.$v.'</option>';

                }
                $this->form .= '</select>'.$validation.'</div></div>';

            } else {

                if ($multiple)
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.self::CUSTOM_SELECT_CLASS.'" multiple required="required">';
                else
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.self::CUSTOM_SELECT_CLASS.'" required="required">';

                foreach ($options as $k => $v)
                {
                    if (is_string($k) || is_numeric($k))
                        $this->form .= '<option value="'.$k.'"> '.$v.'</option>';
                    else
                        $this->form .= '<option value="'.$v.'"> '.$v.'</option>';
                }

                $this->form .= '</select>'.$validation.'</div></div></div>';
            }

        }else{
            if (empty($icon))
            {
                if ($multiple)
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR.'"><select class="' . $class . '"  name="' . $name . '" multiple>';
                else
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR .'"><select class="' . $class . '"  name="' . $name . '">';
                foreach ($options as $k=> $v)
                {
                    if (is_string($k) || is_numeric($k))
                        $this->form .= '<option value="'.$k.'"> '.$v.'</option>';
                    else
                        $this->form .= '<option value="'.$v.'"> '.$v.'</option>';

                }
                $this->form .= '</select>'.$validation.'</div></div>';

            } else {

                if ($multiple)
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.self::CUSTOM_SELECT_CLASS.'" multiple>';
                else
                    $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.self::CUSTOM_SELECT_CLASS.'">';

                foreach ($options as $k => $v)
                {
                    if (is_string($k) || is_numeric($k))
                        $this->form .= '<option value="'.$k.'"> '.$v.'</option>';
                    else
                        $this->form .= '<option value="'.$v.'"> '.$v.'</option>';
                }
                $this->form .= '</select>'.$validation.'</div></div></div>';
            }

        }



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
    public function checkbox(string $name, string $text,string $class = '',bool $checked = false): Form
    {
        if ($checked)

            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"> <div class="custom-control custom-checkbox"><input type="checkbox"  checked="checked" class="custom-control-input '.$class.'" id="' . $name . '"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div></div> ';
        else
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"> <div class="custom-control custom-checkbox"><input type="checkbox"  class="custom-control-input '.$class.'" id="' . $name . '"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div> </div> ';

        return $this;
    }

    /**
     * generate a radio input
     *
     * @param string $name
     * @param string $text
     * @param bool $checked
     *
     * @return Form
     *
     */
    public function radio(string $name, string $text,bool $checked = false): Form
    {
        if ($checked)
        {
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"> <div class="custom-control custom-radio">
                          <input type="radio" id="'.$name.'" name="'.$name.'" class="custom-control-input" checked="checked">
                          <label class="custom-control-label" for="'.$name.'">'.$text.'</label>
                        </div></div></div>';
        } else {
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="'.self::FORM_SEPARATOR.'"><div class="custom-control custom-radio">
                          <input type="radio" id="'.$name.'" name="'.$name.'" class="custom-control-input">
                          <label class="custom-control-label" for="'.$name.'">'.$text.'</label>
                        </div></div></div>';
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
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div>';
        else
            $this->form .= '<div class="'.self::AUTO_COL.'"><div class="' . self::FORM_SEPARATOR . '">';

        if (empty($this->inputSize))
            $this->form .= '<select class="'.self::CUSTOM_SELECT_CLASS. '" name="' . $name . '" onChange="location = this.options[this.selectedIndex].value">';
        else
            $this->form .= '<select class="'.self::CUSTOM_SELECT_CLASS  .' '. $this->inputSize . '" name="' . $name . '"   onChange="location = this.options[this.selectedIndex].value">';

        foreach ($options as $k => $option)
            $this->form .= '<option value="' . $k . '"> ' . $option . '</option>';


        if (!empty($icon))
            $this->form .= '</select></div></div></div>';
        else
            $this->form .= '</select></div></div>';

        return $this;
    }


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
     * @throws Exception
     */
    public function generate(int $form_grid,string $table, Table $instance, string $submitText, string $submitClass, string $submitId, string $submitIcon = '', int $mode = Form::CREATE, int $id = 0): string
    {
        $instance = $instance->set_current_table($table);
        $types = $instance->get_columns_types();
        $columns = $instance->get_columns();
        $primary = $instance->get_primary_key();

        $i = count($columns);

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


        if (equal($mode,Form::EDIT))
        {
            $records = $instance->select_by_id($id);

            if (count($records) > 1)
                throw new Exception('The primary key are not unique');
            if (empty($records))
                throw  new Exception('Record was not found');


            foreach ($records as $record)
            {
                foreach ($columns as $k => $column)
                {
                    if (is_null($record->$column))

                        $record->$column = '';

                    if (different($column,$primary))
                    {

                        if ($i % $form_grid === 0)
                        {
                            $this->textarea($column, $column, 10, 10, false, $record->$column);
                            $this->endRowAndNew();
                        }else{
                            $this->textarea($column, $column, 10, 10, false, $record->$column);
                        }

                    } else {
                        $this->input(Form::HIDDEN, $column, $column,'',$record->$column)->startRow();

                    }
                    $i--;
                }

            }
            $this->endRowAndNew()->submit($submitText, $submitClass, $submitId, $submitIcon);
            return $this->endRow()->get();
        }

        if (equal($mode,Form::CREATE))
        {
            foreach ($types as $k => $type)
            {
                $column = $columns[$k];

                if (different($column,$primary))
                {
                    $current = date('Y-m-d');

                    $type = explode('(', $type);

                    $type = $type[0];

                    if ($i % $form_grid === 0)
                    {
                        if (has($type,$date))
                            $this->textarea($column, $column, 10, 10,false,$current);
                        else
                            $this->textarea($column, $column, 10, 10);

                        $this->endRowAndNew();
                    }else
                    {
                        if (has($type,$date))
                            $this->textarea($column, $column, 10, 10,false,$current);
                        else

                            $this->textarea($column, $column, 10, 10);
                    }

                } else {

                    $this->input(Form::HIDDEN, $column, $column)->startRow();
                }
                $i--;
            }
            $this->endRowAndNew()->submit($submitText, $submitClass, $submitId, $submitIcon);
            return $this->endRow()->get();
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

    /**
     * start an auto column
     *
     * @return Form
     */
    public function startRow(): Form
    {
       $this->form .= '<div class="'.self::GRID_ROW.'">';

       return $this;
    }

    /**
     * end of auto
     *
     * @return Form
     */
    public function endRow(): Form
    {
        $this->form .= '</div>';

        return $this;
    }

    /**
     *  end of line and start a new row
     *
     * @return Form
     */
    public function endRowAndNew(): Form
    {
         $this->endRow();
         $this->startRow();

       return $this;
    }

    /**
     * return the form
     *
     * @return string
     */
    public function get(): string
    {
            return $this->end();
    }


    /**
     * @return Form
     */
    public function validate(): Form
    {
        $this->validate = true;

        return $this;
    }
}
