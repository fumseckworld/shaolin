<?php

declare(strict_types=1);

namespace Eywa\Validate {


    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Exception;
    use Eywa\Application\App;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Parameter\Bag;
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


        protected static array $messages =
        [
            VALIDATOR_EMAIL_NOT_VALID => '',
            VALIDATOR_ARGUMENT_NOT_DEFINED => '',
            VALIDATOR_ARGUMENT_NOT_NUMERIC => '',
            VALIDATOR_ARGUMENT_NOT_UNIQUE => '',
            VALIDATOR_ARGUMENT_NOT_BETWEEN => '',
            VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE => '',
            VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE => '',
            VALIDATOR_ARGUMENT_SLUG => '',
            VALIDATOR_ARGUMENT_SNAKE => '',
            VALIDATOR_ARGUMENT_CAMEL_CASE => '',
            VALIDATOR_ARGUMENT_ARRAY => '',
            VALIDATOR_ARGUMENT_BOOLEAN => '',
            VALIDATOR_ARGUMENT_IMAGE => '',
            VALIDATOR_ARGUMENT_JSON => '',
            VALIDATOR_ARGUMENT_STRING => '',
            VALIDATOR_ARGUMENT_URL => '',
            VALIDATOR_ARGUMENT_FLOAT => '',
            VALIDATOR_ARGUMENT_INT => '',
            VALIDATOR_ARGUMENT_MAC => '',
            VALIDATOR_ARGUMENT_IPV4 => '',
            VALIDATOR_ARGUMENT_IPV6 => '',
            VALIDATOR_ARGUMENT_DOMAIN => '',
        ];


        /**
         *
         * Render a view
         *
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function view(string $view, string $title, string $description, array $args = []): Response
        {
            return $this->app()->view($view, $title, $description, $args);
        }

        /**
         *
         * Get an instance of the application
         *
         * @return App
         *
         * @throws Exception
         *
         */
        public function app(): App
        {
            return app();
        }

        /**
         *
         *
         * Init the validator message
         *
         */
        public function __construct()
        {
            static::$errors = collect();

            static::$messages = validator_messages();
        }

        private Bag $bag;

        /**
         *
         * Success callback
         *
         * @param Bag $bag
         *
         * @return Response
         *
         */
        abstract public function success(Bag $bag): Response;

        /**
         *
         * Error callback
         *
         * @param string[] $messages
         *
         * @return Response
         *
         * @throws Exception
         *
         */
        private function error(array $messages): Response
        {
            return $this->redirect(static::$redirect_error_url, $messages, false);
        }

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
            return static::$errors->empty()
                ? call_user_func_array(
                    [$this, 'success'],
                    [$this->bag]
                )
                : call_user_func_array(
                    [$this, 'error'],
                    [static::$errors->all()]
                );
        }

        /**
         *
         * Redirect user
         *
         * @param string $url
         * @param array $messages
         * @param bool $success
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function redirect(string $url, array $messages, bool $success = true): Response
        {
            $success ? (new Flash())->set(SUCCESS, alert($messages)) : (new Flash())->set(FAILURE, alert($messages));

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
         * Validate a bag
         *
         * @param Bag $bag
         *
         * @return Response
         *
         * @throws Kedavra
         *
         *
         */
        public function handle(Bag $bag): Response
        {
            return $this->validate($bag)->call()->send();
        }

        /**
         * @param Bag $bag
         *
         * @return Validator
         *
         * @throws Kedavra
         *
         */
        public function validate(Bag $bag): Validator
        {
            $this->bag = $bag;
            foreach (static::$rules as $k => $v) {
                $rules = explode('|', $v);

                foreach ($rules as $rule) {
                    switch ($rule) {
                        case 'email':
                            if (!(new EmailValidator())->isValid($bag->get($k), new RFCValidation())) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_EMAIL_NOT_VALID],
                                        $k,
                                        strval($bag->get($k))
                                    )
                                );
                            }
                            break;
                        case 'array':
                            if (!is_array($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_ARRAY],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'string':
                            if (!is_string($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_STRING],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'url':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_URL
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_URL],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'ipv4':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_IP,
                                        FILTER_FLAG_IPV4
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_IPV4],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'ipv6':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_IP,
                                        FILTER_FLAG_IPV6
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_IPV6],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'boolean':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_BOOLEAN
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_BOOLEAN],
                                        strval($k)
                                    )
                                );
                            }
                            break;

                        case 'domain':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_DOMAIN,
                                        FILTER_FLAG_HOSTNAME
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_DOMAIN],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'mac':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_MAC
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_MAC],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'float':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_FLOAT
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_FLOAT],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'int':
                            if (
                                is_false(
                                    filter_var(
                                        $bag->get($k),
                                        FILTER_VALIDATE_INT
                                    )
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_INT],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'image':
                            if (
                                !in_array(
                                    strval(
                                        pathinfo(
                                            $bag->get($k),
                                            PATHINFO_EXTENSION
                                        )
                                    ),
                                    ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'svg', 'webp']
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_IMAGE],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'json':
                            if (
                                !in_array(
                                    strval(
                                        pathinfo(
                                            $bag->get($k),
                                            PATHINFO_EXTENSION
                                        )
                                    ),
                                    ['json']
                                )
                            ) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_JSON],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'slug':
                            if (!is_slug($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_SLUG],
                                        strval($k)
                                    )
                                );
                            }
                            break;

                        case 'snake':
                            if (!is_snake($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_SNAKE],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'camel':
                            if (!is_camel($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_CAMEL_CASE],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'required':
                            if (not_def($bag->get($k))) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_NOT_DEFINED],
                                        strval($k)
                                    )
                                );
                            }
                            break;
                        case 'numeric':
                            $digit = $bag->get($k);
                            if (not_int($digit)) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_NOT_NUMERIC],
                                        strval($k)
                                    )
                                );
                            }
                            break;

                        case preg_match('#unique:([a-zA-Z]+)#', $rule) === 1:
                            $x = explode(':', $rule);
                            $table = $x[1];

                            if (sql($table)->where($k, EQUAL, $bag->get($k))->exist()) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_NOT_UNIQUE],
                                        strval($k),
                                        strval($table)
                                    )
                                );
                            }

                            break;
                        case preg_match('#between:([0-9]+),([0-9]+)#', $rule) === 1:
                            $x = explode(',', $rule);
                            $min = intval(str_replace('between:', '', $x[0]));
                            $max = intval($x[1]);
                            $value = $bag->get($k);
                            if ($value < $min && $value > $max || not_def($value)) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_NOT_BETWEEN],
                                        strval($k),
                                        $min,
                                        $max
                                    )
                                );
                            }
                            break;

                        case preg_match('#max:([0-9]+)#', $rule) === 1:
                            $max = intval(collect(explode(':', $rule))->last());

                            $value = strval($bag->get($k));

                            $length = mb_strlen($value);
                            if ($length > $max) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE],
                                        strval($k),
                                        $max
                                    )
                                );
                            }
                            break;

                        case preg_match('#min:([0-9]+)#', $rule) === 1:
                            $min = intval(collect(explode(':', $rule))->last());

                            $value = strval($bag->get($k));

                            $length = mb_strlen($value);
                            if ($length < $min) {
                                static::$errors->push(
                                    sprintf(
                                        static::$messages[VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE],
                                        strval($k),
                                        $min
                                    )
                                );
                            }
                            break;
                    }
                }
            }
            return $this;
        }
    }
}
