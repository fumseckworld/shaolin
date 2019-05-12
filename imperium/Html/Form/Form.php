<?php

namespace Imperium\Html\Form {

    use Exception;
    use Imperium\App;

   /**
    *
    * Form management
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Form
    {
        /**
         *
         * The class name to represent a new row
         *
         * @var string
         *
         */
        const GRID_ROW = 'row';

        /**
         *
         * The class name to generate auto spaced columns
         *
         * @var string
         *
         */
        const AUTO_COL = 'col';

        /**
         *
         * The basic form class
         *
         * @var string
         *
         */
        const BASIC_CLASS = 'form-control';

        /**
         *
         * The basic large class
         *
         * @var string
         *
         */
        const LARGE_CLASS = 'form-control-lg';

        /**
         *
         * The basic small class
         *
         * @var string
         *
         */
        const SMALL_CLASS = 'form-control-sm';

        /**
         *
         * The basic form separator class
         *
         * @var string
         *
         */
        const FORM_SEPARATOR = 'form-group';

        /**
         *
         * The custom select class
         *
         * @var string
         *
         */
        const CUSTOM_SELECT_CLASS = 'form-control';

        /**
         *
         * The class to hide elements
         *
         * @var string
         *
         */
        const HIDE_CLASS = 'd-none';

        /**
         *
         * To create a reset button
         *
         * @var string
         *
         */
        const RESET = 'reset';

        /**
         *
         * To create a submit button
         *
         * @var string
         *
         */
        const SUBMIT = 'submit';

        /**
         *
         * To create a form with post method
         *
         * @var string
         *
         */
        const POST = 'post';

        /**
         *
         * To create a form with get method
         *
         * @var string
         *
         */
        const GET = 'get';

        /**
         *
         * To create a button
         *
         * @var string
         *
         */
        const BUTTON = 'button';

        /**
         *
         * To create a number input
         *
         * @var string
         *
         */
        const NUMBER = 'number';

        /**
         *
         * To create a hidden input
         *
         * @var string
         *
         */
        const HIDDEN = 'hidden';

        /**
         *
         * To create a text input
         *
         * @var string
         *
         */
        const TEXT = 'text';

        /**
         *
         * To create a password input
         *
         * @var string
         *
         */
        const PASSWORD = 'password';

        /**
         *
         * To create a email input
         *
         * @var string
         *
         */
        const EMAIL = 'email';

        /**
         *
         * To create a date input
         *
         * @var string
         *
         */
        const DATE = 'date';

        /**
         *
         * To create a datetime input
         *
         * @var string
         *
         */
        const DATETIME = 'datetime';

        /**
         *
         * To create a phone input
         *
         * @var string
         *
         */
        const TEL = 'tel';

        /**
         *
         * To create a url input
         *
         * @var string
         *
         */
        const URL = 'url';

        /**
         *
         * To create a time input
         *
         * @var string
         *
         */
        const TIME = 'time';

        /**
         *
         * To create a range input
         *
         * @var string
         *
         */
        const RANGE = 'range';

        /**
         *
         * To create a color input
         *
         * @var string
         *
         */
        const COLOR = 'color';

        /**
         *
         * To create a search input
         *
         * @var string
         *
         */
        const SEARCH = 'search';

        /**
         *
         * To create a week input
         *
         * @var string
         *
         */
        const WEEK = 'week';

        /**
         *
         * To create a checkbox input
         *
         * @var string
         *
         */
        const CHECKBOX = 'checkbox';

        /**
         *
         * To create a radio input
         *
         * @var string
         *
         */
        const RADIO = 'radio';

        /**
         *
         * To create a file input
         *
         * @var string
         *
         */
        const FILE = 'file';

        /**
         *
         * To create a datetime local input
         *
         * @var string
         *
         */
        const DATETIME_LOCAL = 'datetime-local';

        /**
         *
         * To create an image input
         *
         * @var string
         *
         */
        const IMAGE = 'image';

        /**
         *
         * To create a month input
         *
         * @var string
         *
         */
        const MONTH = 'month';

        /**
         *
         * Option to get the datetime result
         *
         * @var int
         *
         */
        const GET_TIME = 0;

        /**
         *
         * Option to get the datetime input
         *
         * @var int
         *
         */
        const GET_DATETIME = 1;

        /**
         *
         * Option to generate a form
         * to edit a record
         *
         * @var int
         *
         */
        const EDIT = 3;

        /**
         *
         * Option to generate a form to create a record
         *
         * @var int
         *
         */
        const CREATE = 4;

        /**
         *
         * Basic button class
         *
         * @var string
         *
         */
        const BTN_BASIC_CLASS = 'btn';

        /**
         *
         * Button large class
         *
         * @var string
         *
         */
        const BTN_LARGE_CLASS = 'btn-lg';

        /**
         *
         * Button small class
         *
         * @var string
         *
         */
        const BTN_SMALL_CLASS = 'btn-sm';

        /**
         *
         * The class to see validation
         *
         * @var string
         *
         */
        const VALIDATE = 'was-validated';

        /**
         *
         * The form
         *
         * @var string
         *
         */
        private $form;

        /**
         *
         * Option to activate validation
         *
         * @var bool
         *
         */
        private $validate = false;

        /**
         *
         * Option to save data after submit
         *
         * @var bool
         *
         */
        private $save = false;

        /**
         *
         * The button size class
         *
         * @var string
         *
         */
        private $btn_size;

        /**
         *
         * The input size class
         *
         * @var string
         *
         */
        private $input_size;

        /**
         *
         * The margin class between elements
         *
         * @var string
         *
         */
        private $margin = '';

        /**
         *
         * The padding class between elements
         *
         * @var string
         *
         */
        private $padding = '';

        /**
         *
         * The form method
         *
         * @var string
         *
         */
        private $method;

        /**
         *
         * Form config
         *
         * @var string
         */
        private $file = 'form';

        /**
         *
         * Define the padding between elements
         *
         * @method padding
         *
         *
         * @return Form
         *
         * @throws Exception
         */
        public function padding(): Form
        {

            $length = config($this->file,'padding');

            not_in([1,2,3,4,5],$length,true,"The padding number must be an integer between 1 and 5");

            $this->padding = "pt-$length pb-$length";

            return $this;
        }

        /**
         *
         * Define the margin between elements
         *
         * @method margin
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function margin(): Form
        {
            $length = config($this->file,'margin');

            not_in([1,2,3,4,5],$length,true,"The margin number must be an integer between 1 and 5");

            $this->margin = "mt-$length mb-$length";

            return $this;
        }

        /**
         *
         * Return the defined margin class
         *
         * @method get_margin
         *
         * @return string
         *
         */
        public function get_margin() : string
        {
            return def($this->margin) ? $this->margin : '';
        }

        /**
         *
         * Return the defined padding class
         *
         * @method get_padding
         *
         * @return string
         *
         */
        public function get_padding(): string
        {
            return def($this->padding) ? $this->padding : '';
        }

        /**
         *
         * Return the complete separator class
         *
         * @method separator
         *
         * @return string
         *
         */
        private function separator(): string
        {
            return self::FORM_SEPARATOR .' ' . $this->get_margin() .' '. $this->get_padding();
        }

        /**
         *
         * Open the form
         *
         * @method start
         *
         * @param string $action The form action
         * @param string $method The form method
         * @param string $confirm The confirm text
         * @param string $id
         * @param string $class The form class
         * @param bool $enctype Configuration to support upload
         * @param string $charset The form charset
         *
         * @return Form
         *
         * @throws Exception
         */
        public function start(string $action,string $method,string $confirm ='', $id = '',string $class = '',  bool $enctype = false,string $charset = 'utf-8'): Form
        {

            $this->method = $method;

            $method = POST;

            if($this->validate)
            {
                equal($confirm,'',true,"The confirm message must not be empty");

                if ($enctype)
                {
                    if (not_def($class))
                        append($this->form ,'<form action="' . $action . '" method="' . $method . '" class="'.self::VALIDATE.'" accept-charset="' . $charset . '"  id="' . $id . '" enctype="multipart/form-data" onsubmit="return confirm('."'".$confirm."'".')" >');
                    else
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class . ' '. self::VALIDATE.'" id="' . $id . '" enctype="multipart/form-data" onsubmit="return confirm('."'".$confirm."'".')">');
                }else
                {
                    if (not_def($class))
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" class="'.self::VALIDATE.'" accept-charset="' . $charset . '" id="' . $id . '" onsubmit="return confirm('."'".$confirm."'".')">');
                    else
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class . ' '. self::VALIDATE.'" id="' . $id . '" onsubmit="return confirm('."'".$confirm."'".')" >');
                }
            }else
            {
                if ($enctype)
                {
                    if (not_def($class))
                        append($this->form ,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '"  id="' . $id . '" enctype="multipart/form-data">');
                    else
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class .'" id="' . $id . '" enctype="multipart/form-data">');
                } else
                {
                    if (not_def($class))
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" id="' . $id . '">');
                    else
                        append($this->form,'<form action="' . $action . '" method="' . $method . '" accept-charset="' . $charset . '" class="'. $class .'" id="' . $id . '">');
                }

            }

            if (config($this->file,'large'))
                $this->large();

            if (config($this->file,'small'))
                $this->small();

            if (config($this->file,'save'))
                $this->save();

            if (config($this->file,'space'))
                $this->margin()->padding();

            append($this->form , '<input type="hidden" value="'.$this->method.'" name="method">');

            return $this->csrf(csrf_field());


        }

        /**
         *
         * Open a div to hide contents
         *
         * @method hide
         *
         * @return Form
         *
         */
        public function hide(): Form
        {
            append($this->form,'<div class="'.self::HIDE_CLASS.'">');

            return $this;
        }

        /**
         *
         * Close the div created to hide contents
         *
         * @method end_hide
         *
         * @return Form
         *
         */
        public function end_hide(): Form
        {
            append($this->form ,'</div>');

            return $this;
        }

        /**
         *
         * Get the input size
         *
         * @method get_input_class
         *
         * @return string          [description]
         */
        private function get_input_class(): string
        {
            return def($this->input_size) ? $this->input_size : self::BASIC_CLASS;
        }

        /**
         *
         * Get input complete input size
         *
         * @method get_input_complete_class
         *
         * @return string
         *
         * @throws Exception
         *
         */
        private function get_input_complete_class(): string
        {
            $x = $this->get_input_class();

            return different($x,self::BASIC_CLASS) ?  $x. ' ' . self::BASIC_CLASS : $x;
        }

        /**
         *
         * Get the complete button class
         *
         * @method get_btn_class
         *
         * @return string
         *
         */
        private function get_btn_class(): string
        {
            return def($this->btn_size) ? self::BTN_BASIC_CLASS .  ' ' . $this->btn_size : self::BTN_BASIC_CLASS;
        }

        /**
         *
         * Generate a file input
         *
         * @method file
         *
         * @param  string $name   The name attribute
         * @param  string $text   The text to explain
         * @param  string $ico    The input icon
         * @param  string $locale The locale to use
         *
         * @return Form
         *
         */
        public function file(string $name, string $text,string $ico = '', string $locale = 'en'): Form
        {
            if (not_def($ico))
                append(  $this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="custom-file"><input type="file"  name="' . $name . '" class="custom-file-input '.$this->get_input_class().'"   lang="' . $locale . '"><label class="custom-file-label" for="'.$name.'">' . $text . '</label></div></div></div>');
            else
                append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"  >' . $ico . '</span></div><div class="custom-file"><input type="file" name="' . $name . '" class="custom-file-input '.$this->get_input_class().' " lang="' . $locale . '"><label class="custom-file-label" for="'.$name.'">' . $text . '</label></div></div></div></div>');

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

            $class = equal($input,Form::FILE) ?  $this->get_input_complete_class() .' form-control-file' : $this->get_input_complete_class();

            if ($this->validate)
            {
                if (not_def($error_text,$success_text))
                    throw new Exception('missing validation text');
                else
                    $validation =  $this->valid($success_text,$error_text);
            }
            else
            {
                $validation = '';
            }

            if($this->save)
            {
                $val = equal($this->method, self::POST) ? post($name) : get($name);

                $value = def($val) ? $val : $value;
            }


            if ($required) // WITH REQUIRED
            {
                if ($autofocus)
                {
                    if ($autoComplete)
                    {
                        return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required"   placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="on" > ' . $validation . $end .'';
                    }
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required"   placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autofocus="autofocus" autocomplete="off" > '  . $validation . $end .'';
                }
                if ($autoComplete)
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required"  placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="on" > ' .    $end . $validation . '';
                else
                return '' . $start . ' <input type="' . $input . '" class="' . $class . '" required="required"  placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" autocomplete="off" > ' . $validation . $end .'';

            } else {

                // WITHOUT REQUIRED
                if ($autofocus) // WITH AUTO FOCUS
                {
                    if ($autoComplete) // AUTO FOCUS , AND AUTO COMPLETE
                    {
                        return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus"  autocomplete="on" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                    }
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '"  autofocus="autofocus" placeholder="' . $placeholder . '"  autocomplete="off" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                } else {   // WITHOUT AUTO FOCUS
                    if ($autoComplete) //   AUTO FOCUS , AND AUTO COMPLETE
                    {
                        return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="on"  placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                    }   // AUTO FOCUS , WITHOUT AUTO COMPLETE
                    return '' . $start . ' <input type="' . $input . '" class="' . $class . '" autocomplete="off"  placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '" > '  . $validation . $end .'';
                }
            }
        }

        /**
         *
         * Generate an input
         *
         * @method input
         *
         * @param  string $type         The input type
         * @param  string $name         The input attr name
         * @param  string $placeholder  The placeholder
         * @param  string $icon         The input icon
         * @param  string $success_text The success validation text
         * @param  string $error_text   The error validation text
         * @param  string $value        The default value
         * @param  bool   $required     To generate a required input
         * @param  bool   $autofocus    To add autofocus for the input
         * @param  bool   $autoComplete To configure autocomplete
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function input(string $type, string $name, string $placeholder,string $icon= '',string $success_text = '',string $error_text ='', string $value = '', bool $required = true, bool $autofocus = false, bool $autoComplete = false): Form
        {
            if (not_def($icon))
            {
                $start = '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'">';

                $end = "</div></div>";

                append($this->form,$this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$success_text,$error_text));
            } else
            {

                $start = '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ';
                $end = "</div></div></div> ";
                append($this->form,$this->generateInput($start, $end, $type, $name, $placeholder, $value, $required, $autofocus, $autoComplete,$success_text,$error_text));
            }


            return $this;
        }

        /**
         *
         * To generate a button
         *
         * @method button
         *
         * @param  string $type The button type
         * @param  string $text The button text
         * @param  string $icon The button icon
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function button(string $type,string $text,string $icon = ''): Form
        {

            $class = collection(config($this->file,'class'))->get($type);

            switch ($type)
            {
                case Form::BUTTON:
                    append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><button class="' .$class .' ' . $this->get_btn_class().' " type="button">  ' . $icon . ' ' . $text . '</button></div></div>');
                break;
                case Form::RESET:
                    append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><button  class="' . $class .' '. $this->get_btn_class().' "  type="reset">  ' . $icon . ' ' . $text . '</button></div></div>');
                break;
                case Form::SUBMIT:
                    append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><button class="' . $class.' '. $this->get_btn_class().'" type="submit">  ' . $icon . ' ' . $text . '</button></div></div>');
                break;

            }

            return $this;

        }

        /**
         *
         * Append to the form the csrf token
         *
         * @method csrf
         *
         * @param  string $csrf The csrf token input
         *
         * @return Form
         *
         */
        private function csrf(string $csrf): Form
        {
            append($this->form,$csrf);

            return $this;
        }

        /**
         *
         * Generate a reset button
         *
         * @method reset
         *
         * @param  string $text The reset button text
         * @param  string $icon The reset button icon
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function reset(string $text, string $icon = ''): Form
        {
            $class = collection(config($this->file,'class'))->get('reset');

            append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><button class="' . $this->get_btn_class(). ' ' .$class.'" type="reset">  ' . $icon . ' ' . ' ' . $text . '</button></div></div>');

            return $this;
        }

        /**
         *
         * Generate a textarea
         *
         * @method textarea
         *
         * @param string $name The input name
         * @param string $placeholder The placeholder
         * @param string $validation_success_text The success validation text
         * @param string $validation_error_text The error validation text
         * @param bool $autofocus Option to add autofocus
         * @param string $value The value
         *
         * @return Form
         *
         * @throws Exception
         */
        public function textarea(string $name, string $placeholder,string $validation_success_text = '',string $validation_error_text ='',bool $autofocus = false,string $value = ''): Form
        {
            $row  = collection(config($this->file,'textarea'))->get('row');

            $col  = collection(config($this->file,'textarea'))->get('col');

            if ($this->validate)
            {
                if (not_def($validation_success_text,$validation_error_text))
                    throw new Exception('missing validation text');
                else
                    $validation =  $this->valid($validation_success_text,$validation_error_text);
            }else
            {
                $validation = '';
            }

            $class = $this->get_input_complete_class();

            if ($autofocus)
                append($this->form, ' <div class="'.self::AUTO_COL.'">   <div class="'. $this->separator().'"><textarea rows="' . $row . '"  cols="' . $col . '" placeholder="' . $placeholder . '" autofocus="autofocus" class="'.$class.'" required="required" name="' . $name . '" >'.$value. '</textarea>'.$validation.'</div></div>');
            else
                append($this->form,'<div class="'.self::AUTO_COL.'"><div  class="'. $this->separator().'"><textarea rows="' . $row . '"  cols="' . $col . '" placeholder="' . $placeholder . '" class="'.$class.'" required="required" name="' . $name . '"  >'.$value .'</textarea> '.$validation.'</div></div>');

            return $this;
        }

        /**
         *
         * Enable save data after submit
         *
         * @method save
         *
         * @return Form
         *
         */
        public function save(): Form
        {
            $this->save = true;

            return $this;
        }

        /**
         *
         * Generate a submit button
         *
         * @method submit
         *
         * @param  string $text The submit button text
         * @param  string $icon The submit button id
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function submit(string $text, string $icon = ''): Form
        {
            $class  = collection(config($this->file,'class'))->get('submit');

            append($this->form,'<div class="'.self::AUTO_COL.'">  <div class="'. $this->separator().'"><button type="submit" class="' . $this->get_btn_class() . ' ' .$class.'">' . $icon . ' ' . $text . '</button></div></div>');

            return $this;
        }

        /**
         *
         * Generate a group
         *
         * @param array $text
         * @param string ...$href
         *
         * @return Form
         *
         * @throws Exception
         */
        public function group(array $text,string ...$href): Form
        {

            $class  = collection(config($this->file,'class'))->get('group');

            append($this->form,'<div class="'.self::AUTO_COL.'">  <div class="'. $this->separator().'"> <div class="btn-group " role="group">');

            foreach ($href as $k => $value)
                append($this->form,'<a href="'.$value.'" class="'.$class.  ' ' .$this->get_btn_class().'"> '.collection($text)->get($k).'</a> ');

            append($this->form,'<div></div></div>');

            return $this;
        }

        /**
         *
         * Generate a link
         *
         * @method link
         *
         * @param  string $url The url link
         * @param  string $text The button text
         * @param  string $icon The button icon
         *
         * @return Form
         *
         * @throws Exception
         *
         */
        public function link(string $url, string $text, string $icon = ''): Form
        {
            $class  = collection(config($this->file,'class'))->get('link');

            append($this->form ,'<div class="'.self::AUTO_COL.'"> <div class="'. $this->separator().'"><a href="' . $url . '" class="' . $this->get_btn_class() . ' '.$class.'">  ' . $icon . ' ' . $text . '</a></div></div>');

            return $this;
        }

        /**
         *
         * Generate validation text
         *
         * @method valid
         *
         * @param  string $success The success text
         * @param  string $error   The error text
         *
         * @return string
         *
         */
        private function valid(string $success,string $error): string
        {
            return '<div class="valid-feedback text-right"> ' .$success.'</div><div class="invalid-feedback text-center">'.$error.'</div>' ;
        }

        /**
         * Generate a select input
         *
         * @method select
         *
         * @param bool $use_index
         * @param  string $name The select name
         * @param  array $options The select options
         * @param  string $success_text The validation success text
         * @param  string $error_text The validation error text
         * @param  string $icon The select icon
         * @param  bool $multiple The option to create a multiple select
         * @param  bool $required The option to add require
         *
         * @return Form
         *
         * @throws Exception
         */
        public function select(bool $use_index,string $name, array $options,string $icon = '',string $success_text = '',string $error_text= '',bool $multiple = false,bool $required = true): Form
        {
            $class = $this->get_input_complete_class();

            if ($this->validate)
                $validation = $this->valid($success_text,$error_text);
            else
                $validation = '';


            if ($required)
            {

                if (not_def($icon))
                {

                    if ($multiple)
                        append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><select class="' . $class . '"  name="' . $name . '" multiple required="required">');
                    else
                        append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><select class="' . $class . '"  name="' . $name . '" required="required">');
                    foreach ($options as $k => $v)
                            $use_index ?  append($this->form, '<option value="'.$k.'">'.$v.'</option>') :  append($this->form, '<option value="'.$v.'">'.$v.'</option>') ;
                    append($this->form,'</select>'.$validation.'</div></div>');
                } else {

                    if ($multiple)
                        append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.$class.'" multiple required="required">');
                    else
                        append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.$class.'" required="required">');

                    foreach ($options as $k => $v)
                    {
                            $use_index ?  append($this->form, '<option value="'.$k.'">'.$v.'</option>') :  append($this->form, '<option value="'.$v.'">'.$v.'</option>') ;
                    }

                    append($this->form,'</select>'.$validation.'</div></div></div>');
                }

            }else
            {
                if (not_def($icon))
                {
                    if ($multiple)
                        append($this->form, '<div class="'.self::AUTO_COL.'"><div  class="'. $this->separator().'"><select class="' . $class . '"  name="' . $name . '" multiple>');
                    else
                        append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><select class="' . $class . '"  name="' . $name . '">');
                    foreach ($options as $k => $v)
                    {
                            $use_index ?  append($this->form, '<option value="'.$k.'">'.$v.'</option>') :  append($this->form, '<option value="'.$v.'">'.$v.'</option>') ;
                    }
                    $this->form .= '</select>'.$validation.'</div></div>';

                } else
                {

                    if ($multiple)
                        append($this->form ,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.$class.'" multiple>');
                    else
                        append($this->form ,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.$icon.'</span></div> <select name="'.$name.'" class="'.$class.'">');
                    foreach ($options as $k => $v)
                    {
                            $use_index ?  append($this->form, '<option value="'.$k.'">'.$v.'</option>') :  append($this->form, '<option value="'.$v.'">'.$v.'</option>') ;
                    }
                    append($this->form,'</select>'.$validation.'</div></div></div>');
                }
            }
            return $this;
        }

        /**
         *
         * Generate a checkbox
         *
         * @method checkbox
         *
         * @param  string $name The checkbox name
         * @param  string $text The checkbox text
         * @param  bool $checked To add checked by default
         *
         * @return Form
         *
         *
         * @throws Exception
         */
        public function checkbox(string $name, string $text,bool $checked = false): Form
        {

            $class  = collection(config($this->file,'class'))->get('checkbox');

            if ($checked)
                append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"> <div class="custom-control custom-checkbox"><input type="checkbox"  checked="checked" class="custom-control-input '.$class.'" id="' . $name . '" name="'.$name.'"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div></div> ');
            else
                append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"> <div class="custom-control custom-checkbox"><input type="checkbox"  class="custom-control-input '.$class.'" id="' . $name . '" name="'.$name.'"><label class="custom-control-label" for="' . $name . '">' . $text . '</label></div> </div> </div> ');

            return $this;
        }


        /**
         *
         * Add a search input
         *
         * @param $search_placeholder
         * @param string $icon
         * @param string $id
         *
         * @return Form
         *
         * @throws Exception
         */
        public function search($search_placeholder,string $icon, $id = 'search'): Form
        {

            $url = request()->getRequestUri();
            $x = strstr($url,'&q=');
            $url = str_replace($x, "", $url);

            $class = $this->get_input_complete_class();

            append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ');
            append(
                $this->form,'<input placeholder="'.$search_placeholder.'"  class="'.$class.'"  id="'.$id.'" onchange="location = this.attributes[4].value + this.value"  data-url="'.$url.'&q=" value="'.get('q').'" autofocus="autofocus" type="search"></div></div></div>');



            return $this;
        }

        /**
         * @param string $icon
         * @param string $url
         * @return Form
         *
         * @throws Exception
         */
        public function pagination(string $icon,string $url): Form
        {
            $step = config('form','pagination_step');

            $class = $this->get_input_complete_class();

            append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div> ');
            append(
                $this->form,'<input  class="'.$class.'"   onchange="location = this.attributes[2].value + this.value"  data-url="'.$url.'" value="'.session('limit',10).'"  step="'.$step.'" min="1" type="number"></div></div></div>');



            return $this;
        }
        /**
         *
         * Generate a radio
         *
         * @method radio
         *
         * @param  string $name The radio name
         * @param  string $text The radio text
         * @param string $id
         * @param  bool $checked To defined checked by default
         *
         * @return Form
         */
        public function radio(string $name, string $text,string $id,bool $checked = false): Form
        {

            if ($checked)
            {
                append($this->form, '<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'""> <div class="custom-control custom-radio">
                <input type="radio" id="'.$id.'" name="'.$name.'" class="custom-control-input" checked="checked">
                <label class="custom-control-label" for="'.$name.'">'.$text.'</label>
                </div></div></div>');
            } else
            {
                append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="custom-control custom-radio">
                <input type="radio" id="'.$id.'" name="'.$name.'" class="custom-control-input">
                <label class="custom-control-label" for="'.$name.'">'.$text.'</label>
                </div></div></div>');
            }
            return $this;
        }

        /**
         * Close the form and return it
         *
         * @method end
         *
         * @return string
         *
         */
        public function end(): string
        {
            append($this->form ,'</form>');

            return $this->form;
        }

        /**
         * Generate a redirect select
         *
         * @method redirect
         *
         * @param  string   $name    The select name
         * @param  array    $options The select options
         * @param  string   $icon    The select icon
         *
         * @return Form
         *
         */
        public function redirect(string $name, array $options, string $icon = ''): Form
        {
            if (def($icon))
                append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div>');
            else
                append($this->form,'<div class="'.self::AUTO_COL.'"><div class="'. $this->separator().'">');

            append($this->form, '<select class="'.self::CUSTOM_SELECT_CLASS  .' '. $this->get_input_class() . '" name="' . $name . '"   onChange="location = this.options[this.selectedIndex].value">');

            foreach ($options as $k => $option)
                append($this->form ,'<option value="' . $k . '"> ' . $option . '</option>');


            if (def($icon))
                append($this->form ,'</select></div></div></div>');
            else
                append($this->form ,'</select></div></div>');

            return $this;
        }


        /**
         * @param string $column
         * @param string $value
         * @param string $table
         *
         * @return Form
         *
         */
        public function primary(string $column,string $value,string $table) : Form
        {
            append($this->form,'<input type="hidden" name="'.$column.'" value="'.$value.'"> <input type="hidden" name="__table__" value="'.$table.'"> ');

            return $this;
        }

        /**
         *
         * Generate a form to edit or create a record
         *
         *
         * @method generate
         *
         * @param int $form_grid The number to modify the form generation output
         * @param string $table The current table
         * @param string $submit_text The submit button text
         * @param string $submit_icon The submit icon
         * @param int $mode Define the mode edit or create
         * @param int $id The record id
         *
         * @return string
         *
         * @throws Exception
         */
        public function generate(int $form_grid,string $table, string $submit_text, string $submit_icon = '', int $mode = Form::CREATE, int $id = 0): string
        {
            $instance   = \app()->table()->column()->for($table);

            $types      = collection($instance->columns_with_types());

            $primary    = $instance->primary_key();

            equal($form_grid,0,true,"Zero is not a valid number");

            not_in([Form::EDIT,Form::CREATE],$mode,true,"The mode used is not a valid mode");

            if (equal($mode,Form::EDIT))
            {

                $data = \app()->table()->from($table)->select_or_fail($id);
                $numeric = collection();
                $date = collection();
                $text = collection();

                $columns = collection($instance->columns_with_types());

                $values = collection();

                foreach ($data as $x)
                {
                    foreach ($columns->collection() as $k => $v)
                    {
                        $type = $v;

                        if (has($type,App::NUMERIC_TYPES) && different($primary,$k))
                            $numeric->add($v,$k);

                        if (has($type,App::TEXT_TYPES))
                            $text->add($v,$k);

                        if (has($type,App::DATE_TYPES))
                            $date->add($v,$k);

                        $values->add($x->$k,$k) ;
                    }
                }

                $all_num = $numeric->length();
                $all_date = $date->length();
                $all_text = $text->length();

                $this->row();

                foreach ($text->collection() as $k =>  $t)
                {
                    $value = $values->get($k);

                    if (is_pair($all_text))
                    {
                        if (is_pair($all_text))
                            $this->textarea($k,$k,'','',false,$value);
                        else
                            $this->textarea($k,$k,'','',false,$value)->end_row_and_new();
                    }
                    else
                    {
                        if (equal($all_text % 3,0))
                            $this->textarea($k,$k,'','',false,$value);
                        else
                            $this->textarea($k,$k,'','','',$value)->end_row_and_new();
                    }
                }

                $this->end_row_and_new();

                foreach ($numeric->collection() as $k =>  $n)
                {

                    $value = $values->get($k);

                    if (is_pair($all_num))
                    {
                        if (is_pair($all_num))
                            $this->input(Form::NUMBER,$k,$k,'','','',$value);
                        else
                            $this->input(Form::NUMBER,$k,$k,'','','',$value)->end_row_and_new();
                    }
                    else
                    {
                        if (equal($all_num % 3,0))
                            $this->input(Form::NUMBER,$k,$k,'','','',$value);
                        else
                            $this->input(Form::NUMBER,$k,$k,'','','',$value)->end_row_and_new();
                    }
                }


                $this->end_row_and_new();

                foreach ($date->collection() as $k =>  $n)
                {
                    $value = $values->get($k);

                    if (is_pair($all_date))
                    {
                        if (is_pair($all_date))
                            $this->input(Form::DATETIME,$k,$k,'','','',$value);
                        else
                            $this->input(Form::DATETIME,$k,$k,'','','',$value)->end_row_and_new();
                    }
                    else
                    {
                        if (equal($all_date % 3,0))
                            $this->input(Form::DATETIME,$k,$k,'','','',$value);
                        else
                            $this->input(Form::DATETIME,$k,$k,'','','',$value)->end_row_and_new();
                    }
                }

                return $this->end_row_and_new()->primary($primary,$id,$table)->submit($submit_text, $submit_icon)->end_row()->get();

            }else
            {
                $current = date('Y-m-d');

                $numeric = $types->data(App::NUMERIC_TYPES);
                $date    = $types->data(App::DATE_TYPES);
                $text    = $types->data(App::TEXT_TYPES);


                $all_num = collection($numeric)->length();
                $all_date = collection($date)->length();
                $all_text = collection($text)->length();


                $this->row();

                foreach ($text as $k =>  $t)
                {
                    if (is_pair($all_text))
                    {
                        if (is_pair($all_text))
                            $this->textarea($t,$t);
                        else
                            $this->textarea($t,$t)->end_row_and_new();
                    }
                    else
                    {
                        if (equal($k % 3,0))
                            $this->textarea($t,$t)->end_row_and_new();
                        else
                            $this->textarea($t,$t);
                    }
                }

                $this->end_row_and_new();


                foreach ($numeric as $k => $n)
                {
                    if (different($n,$primary))
                    {
                        if (is_pair($all_num))
                        {
                            if (is_pair($all_num))
                                $this->input(Form::NUMBER,$n,$n);
                            else
                                $this->input(Form::NUMBER,$n,$n)->end_row_and_new();
                        }else
                        {
                            $this->input(Form::NUMBER,$n,$n);
                        }
                    }
                }

                $this->end_row_and_new();

                foreach ($date as $k =>  $d)
                {

                    if (is_pair($all_date))
                    {
                        if (is_pair($all_date))
                            $this->input(Form::DATE,$d,$d,'','','',$current);
                        else
                            $this->input(Form::DATE,$d,$d,'','','',$current)->end_row_and_new();
                    }else
                    {
                        if (equal($k % 3,0))
                            $this->input(Form::DATE,$d,$d,'','','',$current)->end_row_and_new();
                        else
                            $this->input(Form::DATE,$d,$d,'','','',$current);

                    }

                }

                $this->end_row_and_new();

                $id = app()->connect()->postgresql() ? 'DEFAULT' : 'NULL';

               return $this->primary($primary,$id,$table)->submit($submit_text,  $submit_icon)->end_row()->get();

            }
        }

        /**
         *
         * To display large input
         *
         * @method large
         *
         * @param  bool  $large
         *
         * @return Form
         *
         */
        public function large(bool $large = true): Form
        {
            if ($large)
            {
                $this->input_size = self::LARGE_CLASS ;
                $this->btn_size = self::BTN_LARGE_CLASS ;
            }
            else
            {
                $this->input_size = self::BASIC_CLASS;
                $this->btn_size = self::BTN_BASIC_CLASS;
            }

            return $this;
        }

        /**
         *
         * To display small input
         *
         * @method small
         *
         * @param  bool  $small
         *
         * @return Form
         *
         */
        public function small(bool $small = true): Form
        {
            if ($small)
            {
                $this->input_size = self::SMALL_CLASS;
                $this->btn_size = self::BTN_SMALL_CLASS;
            }else {
                $this->input_size = self::BASIC_CLASS;
                $this->btn_size = self::BTN_BASIC_CLASS;
            }

            return $this;
        }

        /**
         *
         * Start a new row
         *
         * @method row
         *
         * @return Form
         *
         */
        public function row(): Form
        {
            $this->form .= '<div class="'.self::GRID_ROW.'">';

            return $this;
        }

        /**
         *
         * Close the row
         *
         * @method end_row
         *
         * @return Form
         *
         */
        public function end_row(): Form
        {
            $this->form .= '</div>';

            return $this;
        }

        /**
         *
         * Close the row and open a new row
         *
         * @method end_row_and_new
         *
         * @return Form
         *
         */
        public function end_row_and_new(): Form
        {
            $this->end_row();

            $this->row();

            return $this;
        }

        /**
         *
         * Close the form and return the form
         *
         * Alias to end
         *
         * @method get
         *
         * @return string
         *
         */
        public function get(): string
        {
            return $this->end();
        }

        /**
         *
         * Add validation
         *
         * @method validate
         *
         * @return Form
         *
         */
        public function validate(): Form
        {
            $this->validate = true;

            return $this;
        }
    }}
