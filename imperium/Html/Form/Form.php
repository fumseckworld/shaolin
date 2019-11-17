<?php

namespace Imperium\Html\Form {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\App;
    use Imperium\Collection\Collect;
    use Imperium\Exception\Kedavra;


    class Form
    {

        /**
         * @var string
         */
        private $form;

        /**
         * @var Collect
         */
        private $fields;


        /**
         * Form constructor.
         *
         * @param string $method
         * @param string $db
         * @param string $route
         * @param array $route_args
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function __construct(string $method,string $db, string $route,array $route_args)
        {
            $this->fields = collect();
            $method = strtoupper($method);
            $this->append('<form action="'.route($db,$route,$route_args).'" method="POST">');
            $this->append(csrf_field());

            $this->add('_method','hidden',['value' => $method]);

        }

        /**
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
                default:
                    return $this;
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
         * @param string $table
         * @param int $id
         * @return string
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function edit(string $table,int $id): string
        {

            $x = app()->table()->column()->for($table);

            $primary = $x->primary_key();

            $values = collect(app()->table()->from($table)->select_or_fail($id));

            $columns = collect($x->columns_with_types());


            foreach ($values->all() as $k => $v)
            {
                foreach ($columns->all() as $column =>  $type )
                {
                    if(has($type,App::NUMERIC_TYPES))
                        equal($column,$primary) ? $this->add($column,'hidden',['value'=> $v->$column]) :  $this->add($column,'number',['value'=> $v->$column]);
                    elseif(has($type,App::TEXT_TYPES))
                        $this->add($column,'textarea',['value' => $v->$column,'rows'=> 10]);
                    elseif(has($type,App::DATE_TYPES))
                        $this->add($column,'datetime',['value'=> $v->$column]);
                }
            }

            return $this->get(config('form','update_text'));
        }

        /**
         *
         * Generate a form to create a new record
         *
         * @param string $table
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function generate(string $table)
        {
            $x = app()->table()->column()->for($table);

            $primary = $x->primary_key();

            $columns = collect($x->columns_with_types());

            foreach ($columns->all() as $column =>  $type )
            {
                if(has($type,App::NUMERIC_TYPES))
                    equal($column,$primary) ? app()->connect()->postgresql() ? $this->add($column,'hidden',['value'=> 'DEFAULT']) : $this->add($column,'hidden',['value'=> 'NULL']) :  $this->add($column,'number',['placeholder'=> $column,'required'=>'required']);
                elseif(has($type,App::TEXT_TYPES))
                    $this->add($column,'textarea',['placeholder' => $column,'rows'=> 10,'required'=>'required']);
                elseif(has($type,App::DATE_TYPES))
                    $this->add($column,'datetime',['value'=>  date("Y-m-d H:i:s")]);
               
            }
            return $this->get(config('form','create_text'));
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