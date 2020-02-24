<?php

declare(strict_types=1);

namespace Eywa\Html\Form {


    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;

    class FormBuilder
    {

        /**
         *
         * The form
         *
         */
        private string $form = '';


        /**
         *
         * The form fields
         *
         */
        private Collect $fields;


        /**
         * FormBuilder constructor.
         *
         * @param Form $form
         * @param array $route_args
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function __construct(Form $form,array $route_args = [])
        {
            $method  = $form->method();

            not_in(METHOD_SUPPORTED,strtoupper($method),true,"The {$method} method is not supported");

            $this->fields = collect();

            $this->append('<form action="'.route($form->route(),$route_args).'" method="POST" >');

            $this->append(csrf_field());

            $this->add('_method','hidden','method','',['value'=> strtoupper($method)]);

        }


        /**
         *
         * Add an input
         *
         * @param string $name
         * @param string $type
         * @param string $label_text
         * @param array $options
         * @param string $help_text
         *
         * @return FormBuilder
         *
         * @throws Kedavra
         */
        public function add(string $name,string $type,string $label_text,string $help_text  ='',array $options = []): FormBuilder
        {
            if ($this->has($name))
                return  $this;
            else
            {
                $this->fields->push($name);
            }


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

                $help_id = def($help_text) ? "help-$name" : '';

                    $help_input = def($help_text) ? '<small id="'.$help_id.'" class="'.$this->class('help','form-help').'">'.$help_text.'</small>' : '';

                    $help_atribute = def($help_text) ? ' aria-describedby="'.$help_id.'" ': '';

                    if (equal($type,'hidden'))
                        $input = '<input id="'.$name.'" type="'.$type.'" name="'.$name.'" '.$options.'>';
                    else
                        $input = '<div class="'.$this->class('separator','form-group').'">
                                        <label for="'.$name.'">'.$label_text.'</label>
                                        <input type="'.$type.'" class="'.$this->class('input','form-control').'" id="'.$name.'" '.$help_atribute.' '.$options.'>
                                        '.$help_input.'
                                  </div>';

                    $this->append($input);
                    $this->end();
                break;
                case 'textarea':

                    $this->append('<div class="'.$this->class('separator','form-group').'">');

                    $x = collect($options)->each(function ($k,$v){
                        return $k.'='.'"'.$v.'"';
                    })->join('');

                    $value = array_key_exists('value',$options) ? $options['value'] :  '';

                    $help_id = def($help_text) ? "help-$name" : '';

                    $help_input = def($help_text) ? '<small id="'.$help_id.' " class="text-muted">'.$help_text.'</small>' : '';

                    $help_atribute = def($help_text) ? ' aria-describedby="'.$help_id.'" ': '';

                    $input = '<div class="'.$this->class('separator','form-group').'">
                                    <label for="'.$name.'">'.$label_text.'</label>
                                    <textarea name="'.$name.'" '.$options.' class="'.$this->class('input','form-control').'" id="'.$name.'" '.$help_atribute.' '.$x.' >'.$value.'</textarea>
                                     '.$help_input.'
                              </div>';
                    
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
         * @return FormBuilder
         *
         * @throws Kedavra
         */
        public function only(bool $condition,string $name,string $type,string $label_text,array $options =[]): FormBuilder
        {
            return $condition ?  $this->add($name,$type,$label_text,'',$options) : $this;
        }

        /**
         *
         * Generate a row
         *
         * @return FormBuilder
         *
         * @throws Kedavra
         *
         */
        public function row(): FormBuilder
        {
            return $this->append('<div class="'.$this->class('row','row').'">');
        }


        /**
         *
         * Close the div
         *
         * @return FormBuilder
         *
         */
        public function end(): FormBuilder
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

            $input = '<div class="'.$this->class('separator','form-group').'">
                                   <button type="submit" class="'.$this->class('submit','btn btn-submit').'">'.$submit_text.'</button>
                              </div>';
            $this->append($input);

            return def($this->form) ? $this->form . '</form>' : '</form>';
        }

        /**
         *
         * Append data to the  form
         *
         * @param string $x
         *
         * @return FormBuilder
         *
         */
        private function append(string $x): FormBuilder
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
         * @return FormBuilder
         *
         * @throws Kedavra
         *
         */
        public function select(string $name,array $options): FormBuilder
        {

            $this->append( '<div class="' . $this->class('separator','form-group').'"><select class="' . $this->class('base','form-control').'" name="'.$name.'" required="required"><option value=""> ' . config('form','choice_option') . '</option>');

            foreach($options as $k => $option)
                is_integer($k) ? append($this->form,'<option value="' . $option . '"> ' . $option . '</option>'): append($this->form, '<option value="' . $k . '"> ' . $option . '</option>');

            append($this->form, '</select></div>');

            return $this;
        }
    }
}