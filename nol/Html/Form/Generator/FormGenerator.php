<?php

namespace Nol\Html\Form\Generator {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Nol\Collection\Collect;
    use Nol\Database\Query\Sql;
    use PDO;

    /**
     *
     * Class FormGenerator
     *
     * Class useful to generate a form.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Html\Form\Generator
     * @version 12
     *
     * @property Collect $fields All form inputs.
     * @property string  $form   The generated  form.
     *
     */
    class FormGenerator
    {

        /**
         * Form constructor.
         *
         */
        public function __construct()
        {
            $this->fields = collect();
            $this->form = '';
        }

        /**
         *
         * Open the form.
         *
         * @param string $action  The form action.
         * @param string $method  The form method.
         * @param array  $options The form options.
         *
         * @throws Exception
         *
         * @return FormGenerator
         *
         */
        final public function open(string $action, string $method = 'POST', array $options = []): FormGenerator
        {
            return $this->append(
                sprintf(
                    '<form action="%s" method="%s" %s>%s',
                    $action,
                    strtoupper($method),
                    collect($options)->each([$this, 'generateInputOptions'])->join(' '),
                    form_token()
                )
            );
        }

        /**
         * Add a field inside the form.
         *
         * @param string $name    The input name.
         * @param string $type    The input type.
         * @param string $label   The label text.
         * @param array  $options The input options.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return FormGenerator
         *
         */
        final public function add(string $name, string $type, string $label, array $options = []): FormGenerator
        {
            if ($this->has($name)) {
                return $this;
            } else {
                $this->fields->push($name);
            }

            switch ($type) {
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
                    return $this->append(
                        sprintf(
                            '<div class="%s">
                                <label for="%s">%s</label>
                                <input type="%s" id="%s" name="%s" class="%s" %s >
                            </div>',
                            app('form-separator-classname'),
                            $name,
                            $label,
                            $type,
                            $name,
                            $name,
                            app('form-input-classname'),
                            collect($options)->each([$this, 'generateInputOptions'])->join(' ')
                        )
                    );
                case 'textarea':
                    return $this->append(
                        sprintf(
                            '<div class="%s">
                                <label for="%s">%s</label>
                                <textarea id="%s" name="%s" class="%s" %s >%s</textarea>
                            </div>',
                            app('form-separator-classname'),
                            $name,
                            $label,
                            $name,
                            $name,
                            app('form-input-classname'),
                            collect($options)->each([$this, 'generateInputOptions'])->join(' '),
                            $options['value'] ?? ''
                        )
                    );
                default:
                    return $this;
            }
        }

        /**
         *
         * Check if the form has a field.
         *
         * @param string $name The input name to check.
         *
         * @return bool
         *
         */
        final public function has(string $name): bool
        {
            return $this->fields->exist($name);
        }

        /**
         *
         * Generate a row
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return FormGenerator
         */
        final public function row(): FormGenerator
        {
            return $this->append('<div class="' . app('form-row-classname') . '">');
        }

        /**
         *
         * Close the div
         *
         * @return FormGenerator
         *
         */
        final public function end(): FormGenerator
        {
            return $this->append('</div>');
        }

        /**
         *
         * Get the generated form.
         *
         * @param string $submit_text The submit form text.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function close(string $submit_text = 'submit'): string
        {
            $this->append(
                sprintf(
                    '<button type="submit" class="%s">%s</button></form>',
                    app('form-submit-classname'),
                    $submit_text
                )
            );
            return trim($this->form);
        }

        /**
         *
         * Append data to the form
         *
         * @param string $x
         *
         * @return FormGenerator
         *
         */
        final public function append(string $x): FormGenerator
        {
            $this->form .= $x;
            return $this;
        }

        /**
         *
         * Generate a select
         *
         * @param string $name    The select name
         * @param array  $options The select input options
         * @param array  $values  All select values
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return FormGenerator
         *
         */
        final public function select(string $name, array $options, array $values = []): FormGenerator
        {
            if (array_key_exists('sql', $options)) {
                $values = app('connect')->env()->get($options['sql'], $values, PDO::FETCH_COLUMN);
                $options = collect($options)->del(['sql'])->all();
            }

            return $this->append(
                sprintf(
                    '<div class="%s"><select name="%s" class="%s" %s> %s</select></div>',
                    app('form-separator-classname'),
                    $name,
                    app('form-input-classname'),
                    collect($options)->each([$this, 'generateInputOptions'])->join(' '),
                    collect($values)->for(
                        function ($v) {
                            return sprintf('<option value="%s">%s</option>', $v, $v);
                        }
                    )->join(' ')
                )
            );
        }

        /**
         * @param string $k the key.
         * @param string $v The value.
         *
         * @return string
         */
        final public function generateInputOptions(string $k, string $v): string
        {
            return sprintf(' %s="%s" ', $k, $v);
        }
    }
}
