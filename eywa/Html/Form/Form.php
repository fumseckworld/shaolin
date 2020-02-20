<?php

declare(strict_types=1);

namespace Eywa\Html\Form {

    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;


    class Form
    {

        /**
         *
         * The form
         *
         */
        private string $form = '';

        /**
         *
         * All fields
         *
         */
        private Collect $fields;

        /**
         *
         * Form constructor.
         *
         * @param string $route
         * @param string $method
         * @param array $route_args
         * @param array $params
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function __construct(string $route,string $method,$route_args = [],array $params = [])
        {
            not_in(METHOD_SUPPORTED,strtoupper($method),true,"The $method method is not supported");

            $this->fields = collect();

            $this->append('<form action="'.route($route,$route_args).'" method="POST" ');

            foreach ($params as $k => $v)
            {
                $x = $k.'='.'"'.$v.'"';
                $this->append($x);
            }

            $this->append('>');

            $this->append(csrf_field());

            $this->add('_method','hidden','method',['value'=> strtoupper($method)]);

        }


        /**
         *
         * Add an input
         *
         * @param string $name
         * @param string $type
         * @param string $label_text
         * @param array $options
         *
         * @return Form
         *
         * @throws Kedavra
         */
        public function add(string $name,string $type,string $label_text,array $options = []): Form
        {
            if ($this->has($name))
                return  $this;
            else
                $this->fields->push($name);


            switch ($type)
            {
                case 'text':
                case 'button':
                case 'checkbox':
                case 'color':
                case 'date':
                case 'datetime-local':
                case 'email':
                case 'hidden':
                case 'image':
                case 'month':
                case 'number':
                case 'password':
                case 'range':
                case 'search':
                case 'submit':
                case 'tel':
                case 'time':
                case 'url':
                case 'week':
                case 'datetime':

                    $this->append('<div class="'.$this->class('separator','form-group').'">');

                    $options = collect($options)->each(function ($k,$v){
                        return $k.'='.'"'.$v.'"';
                    })->join('');

                    if (equal($type,'hidden'))
                        $input = '<input id="'.$name.'" type="'.$type.'" name="'.$name.'" '.$options.'">';
                    else
                        $input = '<label for="'.$name.'" class="'.$this->class('label','form-label').'">  '.$label_text.'</label><input id="'.$name.'" type="'.$type.'" name="'.$name.'" '.$options.'>';

                    $this->append($input);
                    $this->end();
                break;
                case 'textarea':

                    $this->append('<div class="'.$this->class('separator','form-group').'">');

                    $x = collect($options)->each(function ($k,$v){
                        return $k.'='.'"'.$v.'"';
                    })->join('');

                    $value = array_key_exists('value',$options) ? $options['value'] :  '';

                    if (def($value))
                        $input = '<label for="'.$name.'" class="'.$this->class('label','form-label').'">  '.$label_text.'</label><textarea id="'.$name.'" type="'.$type.'"  class="'.$this->class('base','form-control').'" name="'.$name.'" '.$x.'>'.$value .'</textarea>';
                    else
                        $input = '<label for="'.$name.'" class="'.$this->class('label','form-label').'">  '.$label_text.'</label><textarea id="'.$name.'" type="'.$type.'"  class="'.$this->class('base','form-control').'" name="'.$name.'" '.$x.'></textarea>';

                    $this->append($input);

                    $this->end();
                break;
            }
            return $this;
        }

        /**
         *
         * Check if form has field.
         *
         * @param string $name
         *
         * @return bool
         *
         */
        public function has(string $name): bool
        {
            return $this->fields->exist($name);
        }

        /**
         *
         * Add a field by a condition
         *
         * @param bool $condition
         * @param string $name
         * @param string $type
         * @param string $label_text
         * @param array $options
         *
         * @return Form
         *
         * @throws Kedavra
         */
        public function only(bool $condition,string $name,string $type,string $label_text,array $options =[]): Form
        {
            return $condition ?  $this->add($name,$type,$label_text,$options) : $this;
        }

        /**
         *
         * Generate a row
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function row(): Form
        {
            return $this->append('<div class="'.$this->class('row','row').'">');
        }


        /**
         *
         * Close the div
         *
         * @return Form
         *
         */
        public function end(): Form
        {
            return $this->append('</div>');
        }


        /**
         *
         * Return the form
         *
         * @param string $submit_text
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function get(string $submit_text ='submit'): string
        {

            $this->append('<button type="submit" class="'.$this->class('submit','btn btn-submit').'">'.$submit_text.'</button>');

            return def($this->form) ? $this->form . '</form>' : '</form>';
        }

        /**
         *
         * Append data to the  form
         *
         * @param string $x
         *
         * @return Form
         *
         */
        private function append(string $x): Form
        {
            append($this->form,$x);
            return $this;
        }

        /**
         *
         * Found the class
         *
         * @param string $x
         *
         * @param string $value
         * @return string
         *
         * @throws Kedavra
         */
        private function class(string $x,string $value =''):string
        {
            $x = collect(config('form','class'))->get($x);
            return def($x) ? $x : $value;
        }

        /**
         *
         * Generate a select
         *
         * @param string $name
         * @param array $options
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function select(string $name,array $options): Form
        {

            $this->append( '<div class="' . $this->class('separator','form-group').'"><select class="' . $this->class('base','form-control').'" name="'.$name.'" required="required"><option value=""> ' . config('form','choice_option') . '</option>');

            foreach($options as $k => $option)
                is_integer($k) ? append($this->form,'<option value="' . $option . '"> ' . $option . '</option>'): append($this->form, '<option value="' . $k . '"> ' . $option . '</option>');

            append($this->form, '</select></div>');

            return $this;
        }
    }
}