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
         * Form constructor.
         *
         * @param string $route
         * @param array $route_args
         * @param array $params
         * @param string $method
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function __construct(string $route,string $method,array $params = [],array $route_args = [])
        {
            $this->fields = collect();

            $this->append('<form action="'.route('web',$route,$route_args).'" method="POST" ');

            foreach ($params as $k => $v)
            {
                $x = $k.'='.'"'.$v.'"';
                $this->append($x);
            }

            $this->append('>');

            $this->append(csrf_field());

            $this->add('_method','hidden',['value'=> strtoupper($method)]);

        }


        /**
         *
         * Add an input
         *
         * @param string $name
         * @param string $type
         * @param array $options
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function add(string $name,string $type,array $options = []): Form
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

                    $this->append('<div class="'.$this->class('separator').'">');

                    $input = '<input type="'.$type.'" name="'.$name.'" class="'.$this->class('base').'" ';

                    foreach ($options as $k => $v)
                        append($input,$k,'=','"'.$v.'" ');

                    append($input,'>');

                    $this->append($input);
                    $this->end();
                break;
                case 'textarea':

                    $this->append('<div class="'.$this->class('separator').'">');

                    $input = '<textarea  name="'.$name.'" class="'.$this->class('base').'" ';

                    foreach ($options as $k => $v)
                        if (different($k,'value'))
                            append($input,$k,'=','"'.$v.'" ');

                    collect($options)->has('value') ? append($input,'>'.$options["value"].'</textarea>') : append($input,'></textarea>') ;

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
         * @param array $options
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function only(bool $condition,string $name,string $type,array $options =[]): Form
        {
            return $condition ?  $this->add($name,$type,$options) : $this;
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
            return $this->append('<div class="'.$this->class('row').'">');
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

            $this->append('<button type="submit" class="'.$this->class('submit').'">'.$submit_text.'</button>');

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
         * @return string
         *
         * @throws Kedavra
         *
         */
        private function class(string $x):string
        {
            return  collect(config('form','class'))->get($x);
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

            $this->append( '<div class="' . $this->class('separator').'"><select class="' . $this->class('base').'" name="'.$name.'" required="required"><option value=""> ' . config('form','choice_option') . '</option>');

            foreach($options as $k => $option)
                is_integer($k) ? append($this->form,'<option value="' . $option . '"> ' . $option . '</option>'): append($this->form, '<option value="' . $k . '"> ' . $option . '</option>');

            append($this->form, '</select></div>');

            return $this;
        }
    }
}