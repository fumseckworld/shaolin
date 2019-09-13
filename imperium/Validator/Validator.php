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
         * @throws Kedavra
         *
         */
        public function __construct(Collect $data)
        {
            $file = 'validator';

            $this->message =
            [
                'required' => config($file,'required'),
                'empty' => config($file,'empty'),
                'between' => config($file,'between'),
                'email' => config($file,'email'),
                'digits' => config($file,'digits'),
            ];

            $this->data = $data;
            $this->errors = collect();
        }

        /**
         *
         * Check if values exist in data
         *
         * @param string ...$values
         *
         * @return Validator
         */
        public function required(string ...$values): Validator
        {
            foreach ($values as $key)
            {
                if ($this->data->has($key))
                    $this->errors->push(sprintf($this->message['required'], $key));
            }

            return $this;
        }

        /**
         *
         * Check if the emails are valid
         *
         * @param string ...$values
         *
         * @return Validator
         *
         */
        public function email(string ...$values): Validator
        {
            foreach ($values as $key)
            {
                if (!(new EmailValidator())->isValid($key, new RFCValidation()))
                {
                    $this->errors->push(sprintf($this->message['email'], $key));
                }
            }

            return $this;
        }

        /**
         *
         * @param string ...$values
         *
         * @return Validator
         */
        public function define(string ...$values): Validator
        {
            foreach ($values as $key)
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
         * @param mixed $value
         * @param int $min
         * @param int $max
         *
         * @return Validator
         *
         * @throws Kedavra
         *
         */
        public function between($value, int $min, int $max): Validator
        {
            if (superior(sum($value), $max) || inferior(sum($value), $min))
            {
                $this->errors->push(sprintf($this->message['between'], $value));
            }
            return $this;
        }

        /**
         *
         * Check if the key was between the min and the max
         *
         * @param mixed $values
         *
         * @return Validator
         *
         */
        public function digits($values): Validator
        {
            foreach ($values as $key)
            {
                if (!preg_match('[0-9]+',$key))
                    $this->errors->push(sprintf($this->message['digits'], $key));
            }
            return $this;
        }

        /**
         *
         * Execute the callable on success
         *
         * @param callable $callable
         * @param array $args
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function do($callable, $args = []): RedirectResponse
        {
            return def($this->errors()) ? $this->display() : call_user_func_array($callable, $args);
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