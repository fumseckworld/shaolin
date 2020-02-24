<?php

declare(strict_types=1);

namespace Eywa\Validate {


    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;

    abstract class Validator
    {


        /**
         *
         * The validator rules
         *
         */
        protected static array $rules = [];

        /**
         *
         * All errors founds
         *
         */
        private static Collect $errors;

        /**
         *
         * The redirect url on error
         *
         */
        public static string $redirect_url = '';

        /**
         *
         * @param Request $request
         * @return Response
         */
        abstract protected static function do(Request $request): Response;

        /**
         *
         * Check if a error has been defined
         *
         * @param string $key
         *
         * @return bool
         *
         */
        public static function has(string $key): bool
        {
            return  static::$errors->has($key);
        }

        /**
         *
         * Capture the errors
         *
         * @param Request $request
         * @return Response
         *
         * @throws Kedavra
         */
        public static function check(Request $request): Response
        {

            static::$errors = collect();

            foreach (static::$rules as $k => $v)
            {

                $rules = explode('|',$v);

                foreach ($rules as $rule)
                {
                    switch ($rule)
                    {
                        case 'email':
                            if (!(new EmailValidator())->isValid($request->request()->get($k),new RFCValidation()))
                                static::$errors->put($k,'Email not valide');
                        break;
                        case 'required':
                            if (not_def($request->request()->get($k)))
                                static::$errors->put($k,'Is not define');
                        break;
                        case 'numeric':
                            if(!is_numeric($request->request()->get($k)))
                                static::$errors->put($k,'Is not numeric');
                        break;

                        case preg_match('#unique:([a-zA-Z]+)#',$rule) === 1:
                            $x = explode(':',$rule);
                            $table = $x[1];

                            if (sql($table)->where($k,EQUAL,$request->request()->get($k))->exist())
                                static::$errors->put($k,'Is not unique');

                        break;
                        case preg_match('#between:([0-9]+),([0-9]+)#',$rule) === 1:
                            $x = explode(',',$rule);
                            $min = str_replace('between:','',$x[0]);
                            $max = $x[1];
                            $value = $request->request()->get($k);
                            if ($value <  $min || $value > $max)
                                static::$errors->put($k,'Is not between');
                        break;
                    }

                }

            }

            if (static::$errors->sum() === 0)
                return static::do($request)->send();


            return  (new RedirectResponse(static::$redirect_url))->send();
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
        public static function message(string $key): string
        {
            return  static::has($key) ? static::$errors->get($key) : '';
        }

    }
}