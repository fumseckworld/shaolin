<?php

declare(strict_types=1);

namespace Eywa\Validate {


    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Message\Flash\Flash;

    abstract class Validator
    {


        /**
         *
         * The validator rules
         *
         * @var array<string>
         *
         */
        protected static array $rules = [];


        /**
         *
         * All errors founds
         *
         */
        protected static Collect $errors;


        /**
         *
         * The redirect url on success
         *
         */
        public static string $redirect_success_url = '/';

        /**
         *
         * The redirect url on error
         *
         */
        public static string $redirect_error_url = '/error';

        protected static array $messages = [
            VALIDATOR_EMAIL_NOT_VALID => '',
            VALIDATOR_ARGUMENT_NOT_DEFINED => '',
            VALIDATOR_ARGUMENT_NOT_NUMERIC => '',
            VALIDATOR_ARGUMENT_NOT_UNIQUE => '',
            VALIDATOR_ARGUMENT_NOT_BETWEEN => '',
            VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE => '',
            VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE => '',
        ];
        /**
         * @var Request
         */
        private Request $request;

        /**
         *
         * Success callback
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         *
         */
        abstract public function success(Request $request): Response;

        /**
         *
         * Error callback
         *
         * @param string[] $messages
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        abstract public function error(array $messages): Response;

        /**
         *
         * Redirect user
         *
         * @return Response
         *
         *
         */
        public function call(): Response
        {
            return static::$errors->empty() ? call_user_func_array([$this,'success'], [$this->request]) : call_user_func_array([$this,'error'], [static::$errors->all()]);
        }

        /**
         *
         * Redirect user
         *
         * @param string $url
         * @param string $message
         * @param bool $success
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function redirect(string $url, string $message, bool $success = true):Response
        {
            $success ? (new Flash())->set(SUCCESS, $message) : (new Flash())->set(FAILURE, $message);

            return (new RedirectResponse($url))->send();
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
        public static function has(string $key): bool
        {
            return static::$errors->has($key);
        }

        /**
         *
         * @param Request $request
         *
         * @return Validator
         *
         * @throws Kedavra
         *
         */
        public function validate(Request $request): Validator
        {
            $this->request = $request;
            foreach (static::$rules as $k => $v) {
                $rules = explode('|', $v);

                foreach ($rules as $rule) {
                    switch ($rule) {
                        case 'email':
                            if (!(new EmailValidator())->isValid($request->request()->get($k), new RFCValidation())) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_EMAIL_NOT_VALID], $k, strval($request->request()->get($k))));
                            }
                            break;
                        case 'required':
                            if (not_def($request->request()->get($k))) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_NOT_DEFINED], strval($k)));
                            }
                            break;
                        case 'numeric':

                            $digit = $request->request()->get($k);
                            if (not_int($digit)) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_NOT_NUMERIC], strval($k)));
                            }
                            break;

                        case preg_match('#unique:([a-zA-Z]+)#', $rule) === 1:
                            $x = explode(':', $rule);
                            $table = $x[1];

                            if (sql($table)->where($k, EQUAL, $request->request()->get($k))->exist()) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_NOT_UNIQUE], strval($k), strval($table)));
                            }

                            break;
                        case preg_match('#between:([0-9]+),([0-9]+)#', $rule) === 1:
                            $x = explode(',', $rule);
                            $min = intval(str_replace('between:', '', $x[0]));
                            $max = intval($x[1]);
                            $value = $request->request()->get($k);
                            if ($value < $min && $value > $max || not_def($value)) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_NOT_BETWEEN], strval($k), $min, $max));
                            }
                            break;

                        case preg_match('#max:([0-9]+)#', $rule) === 1:

                            $max = intval(collect(explode(':', $rule))->last());

                            $value = strval($request->request()->get($k));

                            $length = mb_strlen($value);
                            if ($length > $max) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE], strval($k), $max));
                            }
                            break;

                        case preg_match('#min:([0-9]+)#', $rule) === 1:
                            $min = intval(collect(explode(':', $rule))->last());

                            $value = strval($request->request()->get($k));

                            $length = mb_strlen($value);
                            if ($length < $min) {
                                static::$errors->push(sprintf(static::$messages[VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE], strval($k), $min));
                            }
                            break;
                    }
                }
            }
            return $this;
        }
    }
}
