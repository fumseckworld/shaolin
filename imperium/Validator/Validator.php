<?php


namespace Imperium\Validator {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Imperium\Collection\Collect;
    use Imperium\Exception\Kedavra;
    use Imperium\Flash\Flash;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class Validator
    {

        /**
         * The request data
         *
         * @var Collect
         *
         */
        private $data;

        /**
         *
         * @var Collect
         *
         */
        private $errors;

        /**
         *
         * The messages
         *
         * @var array
         *
         */
        private $message = [];


        /**
         *
         * Validator constructor.
         *
         * @param Collect $data
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function __construct(Collect $data)
        {
            $file = 'validator';

            $lang = app()->lang();

            $this->message =
            [
                'required' => collect(config($file,$lang))->get('required'),
                'empty' => collect(config($file,$lang))->get('empty'),
                'between' => collect(config($file,$lang))->get('between'),
                'email' => collect(config($file,$lang))->get('email'),
                'digits' => collect(config($file,$lang))->get('digits'),
                'alpha' => collect(config($file,$lang))->get('alpha'),
                'alphanumeric' => collect(config($file,$lang))->get('alphanumeric'),
                'boolean' => collect(config($file,$lang))->get('boolean'),
                'lower' => collect(config($file,$lang))->get('lower'),
                'upper' => collect(config($file,$lang))->get('upper'),
            ];

            $this->data = $data;
            $this->errors = collect();
        }

        /**
         *
         * Check if values exist in data
         *
         * @param string[] $keys
         * @return Validator
         */
        public function required(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!$this->data->has($key))
                    $this->errors->push(sprintf($this->message['required'], $key));
            }

            return $this;
        }

        /**
         *
         * Check if values are alpha
         *
         * @param string[] $keys
         * @return Validator
         */
        public function alpha(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!ctype_alpha($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['alpha'], $key));
            }

            return $this;
        }

        /**
         *
         * Check if values are alpha
         *
         * @param string[] $keys
         * @return Validator
         */
        public function lower(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!ctype_lower($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['lower'], $key));
            }

            return $this;
        }     /**
         *
         * Check if values are alpha
         *
         * @param string[] $keys
         * @return Validator
         */
        public function upper(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!ctype_upper($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['upper'], $key));
            }

            return $this;
        }

        /**
         *
         * Check if values are alphanumeric
         *
         * @param string[] $keys
         * @return Validator
         */
        public function alphanumeric(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!ctype_alnum($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['alphanumeric'], $key));
            }

            return $this;
        }

        /**
         *
         * Check if the emails are valid
         *
         * @param string[] $keys
         * @return Validator
         */
        public function email(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!(new EmailValidator())->isValid($this->data->get($key), new RFCValidation()))
                {
                    $this->errors->push(sprintf($this->message['email'], $key));
                }
            }

            return $this;
        }

        /**
         *
         * @param string[] $keys
         * @return Validator
         */
        public function define(string ...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (not_def($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['empty'], $key));

            }

            return $this;
        }

        /**
         *
         * Check if the key was between the min and the max
         *
         * @param $key
         * @param int $min
         * @param int $max
         *
         * @return Validator
         *
         * @throws Kedavra
         */
        public function between($key, int $min, int $max): Validator
        {
            if (superior(sum($this->data->get($key)), $max) || inferior(sum($this->data->get($key)), $min))
            {
                $this->errors->push(sprintf($this->message['between'], $key,$min,$max));
            }
            return $this;
        }

        /**
         *
         * Check if the key was between the min and the max
         *
         * @param $keys
         * @return Validator
         */
        public function digits(...$keys): Validator
        {
            foreach ($keys as $key)
            {
                if (!ctype_xdigit($this->data->get($key)))
                    $this->errors->push(sprintf($this->message['digits'], $key));
            }
            return $this;
        }

        /**
         *
         * Execute the callable on success
         *
         * @param callable $callable
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function do($callable): RedirectResponse
        {
            return def($this->errors()) ? $this->display() : call_user_func_array($callable, $this->data->all());
        }

        /**
         *
         * Display errors messages
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function display(): RedirectResponse
        {
            $msg = '<ul>';

            foreach ($this->errors() as $error)
                append($msg, "<li>$error</li>");

            append($msg, '</ul>');

            app()->session()->def(Flash::FAILURE_KEY, $msg);

            return back($msg, false);

        }

        /**
         *
         * Get all errors messages
         *
         * @return array
         *
         */
        public function errors(): array
        {
            return $this->errors->all();
        }


    }
}