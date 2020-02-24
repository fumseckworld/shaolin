<?php

declare(strict_types=1);

namespace Eywa\Validate {


    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Eywa\Collection\Collect;
    use Eywa\Http\Request\Request;

    class Validator
    {

        /**
         *
         * All errors
         *
         */
        private Collect $errors;
        /**
         *
         * All validations requirement
         *
         */
        private array $rules;


        /**
         * @var Request
         */
        private Request $request;

        /**
         * Validator constructor.
         * @param array $rules
         * @param Request $request
         */
        public function __construct(array $rules,Request $request)
        {

            $this->rules = $rules;
            $this->errors = collect();

            $this->request = $request;
        }

        /**
         *
         * Add an error
         *
         * @param string $key
         * @param string $message
         *
         * @return Validator
         *
         */
        public function add(string $key,string $message): Validator
        {
            $this->errors->put($key,$message);
            return $this;
        }

        /**
         *
         * Check if a error has been defined
         *
         * @param string $key
         *
         * @return bool
         *
         */
        public function has(string $key): bool
        {
            return  $this->errors->has($key);
        }

        /**
         *
         * Check if is valid
         *
         * @return bool
         *
         */
        public function valid()
        {
            return $this->errors->sum() === 0;
        }

        /**
         *
         * Capture the errors
         *
         * @return Validator
         *
         */
        public function capture(): Validator
        {
            $request = $this->request->request();
            foreach ($this->rules as $k => $v)
            {

                $rules = explode('|',$v);

                foreach ($rules as $rule)
                {
                    switch ($rule)
                    {
                        case 'email':
                            if (!(new EmailValidator())->isValid($request->get($k),new RFCValidation()))
                                $this->errors->put($k,'Email not valide');
                        break;
                        case 'required':
                            if (not_def($request->get($k)))
                                $this->errors->put($k,'Is not define');
                        break;
                        case 'numeric':
                            if(!is_numeric($request->get($k)))
                                $this->errors->put($k,'Is not numeric');
                        break;

                        case preg_match('#unique:([a-zA-Z]+)#',$rule) === 1:
                            $x = explode(':',$rule);
                            $table = $x[1];

                            if (sql($table)->where($k,EQUAL,$request->get($k))->exist())
                                $this->errors->put($k,'Is not unique');

                        break;
                        case preg_match('#between:([0-9]+),([0-9]+)#',$rule) === 1:
                            $x = explode(',',$rule);
                            $min = str_replace('between:','',$x[0]);
                            $max = $x[1];
                            $value = $request->get($k);
                            if ($value <  $min || $value > $max)
                                $this->errors->put($k,'Is not between');
                        break;
                    }

                }

            }
            return $this;
        }
        /**
         *
         * Get the error message for a key
         *
         * @param string $key
         *
         * @return string
         *
         */
        public function message(string $key): string
        {
            return  $this->has($key) ? $this->errors->get($key) : '';
        }
    }
}