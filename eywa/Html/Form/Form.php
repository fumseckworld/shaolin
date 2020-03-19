<?php

declare(strict_types=1);

namespace Eywa\Html\Form {

    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\FormRequest;

    class Form
    {

        /**
         *
         * The html form code
         *
         */
        private string $form = '';

        /**
         *
         * The form inputs
         *
         */
        private Collect $inputs;

        /**
         *
         * The form url
         *
         */
        private string $url;

        /**
         *
         * The form method
         *
         */
        private string $method;


        /**
         *
         * The form request
         *
         */
        private FormRequest $request;

        /**
         * Form constructor.
         *
         * @param FormRequest $request
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function __construct(FormRequest $request)
        {
            $this->form = '<form action="'.$request->url().'" method="POST" '. collect($request->options())->each(function ($k, $v) {
                return $k.'='.'"'.$v.'"';
            })->join('').'>';

            $this->inputs = collect();
            $this->url = $request->url();
            $this->method = $request->method();

            $this->add('_method', 'hidden', '', '', ['value'=> $request->method()]);
            $this->add(CSRF_TOKEN, 'hidden', '', '', ['value'=> csrf_field()]);
            $this->request = $request;
        }

        /**
         *
         * Get all errors
         *
         * @return Collect
         *
         */
        public function errors(): Collect
        {
            return $this->request->errors();
        }

        /**
         *
         * Add an element
         *
         * @param string $name
         * @param string $type
         * @param string $label_text
         * @param string $help_text
         * @param array<string> $options
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function add(string $name, string $type, string $label_text, string $help_text  ='', array $options = []): Form
        {
            if ($this->has($name)) {
                return  $this;
            } else {
                $this->inputs->put($name, $name);
            }


            switch ($type) {
                case 'text':
                case 'button':
                case 'checkbox':
                case 'color':
                case 'date':
                case 'datetime-local':
                case 'email':
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


                    $this->append('<div class="'.$this->class('separator', 'form-group').'">');

                    $x = collect($options)->each(function ($k, $v) {
                        return $k.'='.'"'.$v.'"';
                    })->join(' ');

                    $help_id = def($help_text) ? "help-$name" : '';

                    $help_input = def($help_text) ? '<small id="'.$help_id.'" class="'.$this->class('help', 'form-help').'">'.$help_text.'</small>' : '';

                    $help_atribute = def($help_text) ? ' aria-describedby="'.$help_id.'" ': '';

                    if (equal($type, 'hidden')) {
                        $input = '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" '.$x.'>';
                    } else {
                        $input = '<div class="'.$this->class('separator', 'form-group').'">
                                        '.$help_input.'
                                        <label for="'.$name.'">'.$label_text.'</label>
                                        <input type="'.$type.'" name="'.$name.'"  class="'.$this->class('input', 'form-control').'" id="'.$name.'"  '.$help_atribute.'  '.$x.'>
                                  </div>';
                    }

                    $this->append($input);
                    $this->end();
                    break;
                case 'textarea':

                    $this->append('<div class="'.$this->class('separator', 'form-group').'">');

                    $x = collect($options)->each(function ($k, $v) {
                        return $k.'='.'"'.$v.'"';
                    })->join('');

                    $value = array_key_exists('value', $options) ? $options['value'] :  '';

                    $help_id = def($help_text) ? "help-$name" : '';

                    $help_input = def($help_text) ? '<div class="'.$this->class('help-separator', 'help-separator').'"><small id="'.$help_id.' " class="'.$this->class('help-text', 'help-text').'">'.$help_text.'</small></div>' : '';

                    $help_atribute = '';

                    $input = '<div class="'.$this->class('separator', 'form-group').'">
                                    '.$help_input.'
                                    <label for="'.$name.'">'.$label_text.'</label>
                                    <textarea name="'.$name.'" class="'.$this->class('input', 'form-control').'" id="'.$name.'" '.$help_atribute.' '.$x.' >'.$value.'</textarea>
                                   
                              </div>';

                    $this->append($input);

                    $this->end();
                    break;
                case 'file':
                    $this->append('<div class="'.$this->class('separator', 'form-group').'">');

                    $help_id = def($help_text) ? "help-$name" : '';

                    $help_input = def($help_text) ? '<small id="'.$help_id.' " class="text-muted">'.$help_text.'</small>' : '';

                    $help_atribute = def($help_text) ? ' aria-describedby="'.$help_id.'" ': '';
                    $x = collect($options)->each(function ($k, $v) {
                        return $k.'='.'"'.$v.'"';
                    })->join('');

                    $input = '<div class="'.$this->class('separator', 'form-group').'">
                                    '.$help_input.'
                                    <label for="'.$name.'">'.$label_text.'</label>
                                    <input name="files[]"  class="'.$this->class('input', 'form-control').'" id="'.$name.'" '.$help_atribute.' '.$x.' multiple>
                              </div>';
                    $this->append($input);

                    $this->end();
                    break;
                case 'hidden':
                    $x = '<input name="'.$name.'" class="'.$this->class('hide', 'hide').'" value="'.$options['value'] .'">';
                    $this->append($x);
                    break;
            }
            return $this;
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
            $input = '<div class="'.$this->class('separator', 'form-group').'">
                        <button type="submit" class="'.$this->class('submit', 'btn btn-submit').'">'.$submit_text.'</button>
                      </div>';
            $this->append($input);

            $this->append("</form>");


            return collect(explode("\n", collect(explode('  ', $this->form))->join('')))->join(' ');
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
         * Generate a row
         *
         * @return Form
         *
         * @throws Kedavra
         *
         */
        public function row(): Form
        {
            return $this->append('<div class="'.$this->class('row', 'row').'">');
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
        private function has(string $name): bool
        {
            return $this->inputs->has($name);
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
            append($this->form, $x);
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
         *
         */
        private function class(string $x, string $value =''):string
        {
            $x = collect(config('form', 'class'))->get($x);
            return def($x) ? $x : $value;
        }
    }
}
