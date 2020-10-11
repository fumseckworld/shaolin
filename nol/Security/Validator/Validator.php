<?php

namespace Nol\Security\Validator {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Http\Parameters\Bag;
    use Nol\Http\Response\RedirectResponse;
    use Nol\Http\Response\Response;
    use Nol\Messages\Flash\Flash;

    abstract class Validator
    {
        /**
         * All validators rules
         */
        protected static array $rules = [];

        protected static array $fields = [];

        protected static array $errors = [];

        protected static array $messages = [];

        protected static string $redirect = '';

        protected static string $message = '';

        /**
         *
         * Analyse a container content.
         *
         * @param Bag $bag The bag to analyse.
         *
         * @return bool
         */
        final public function check(Bag $bag): bool
        {
            return $this->analyse($bag);
        }

        /**
         *
         * Get the errors messages.
         *
         * @return string
         */
        final public function alert(): string
        {
            $message = '<ul class="alert alert-danger">';
            $message .= collect(static::$errors)->for(
                function ($error) {
                    return sprintf('<li>%s</li>', $error);
                }
            )->join('');
            $message .= '</ul>';
            return $message;
        }

        /**
         *
         * Redirect the user to the correct url with the correct message.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function redirect(): Response
        {
            if (not_cli()) {
                not_def(static::$errors)
                    ? Flash::set(sprintf('<div class="alert alert-success">%s</div>', static::$message))
                    : Flash::set($this->alert());
            }
            return (new RedirectResponse(static::$redirect))->send();
        }


        /**
         *
         * Check the content of the container
         *
         * @param Bag $bag The container to analyse.
         *
         * @return bool
         *
         */
        final private function analyse(Bag $bag): bool
        {
            $rules = def(static::$rules) ? static::$rules : static::$fields;

            foreach ($rules as $field => $rule) {
                $x = collect(explode('|', $rule));
                $x->rewind();
                while ($x->valid()) {
                    switch (trim($x->current())) {
                        case 'required':
                            if (not_def($bag->get($field))) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s field must be defined')
                                );
                            }
                            break;
                        case def(strstr($x->current(), 'between')):
                            $part = collect(explode(':', $x->current()))->last();
                            $part = collect(explode(',', $part));
                            $min = str_replace('between,', '', $part->first());
                            $max = $part->last();

                            $value = $bag->get($field);

                            if ($value < $min || $value > $max) {
                                $this->addError(
                                    sprintf(
                                        config(
                                            'validator',
                                            'between',
                                            'The %s field is not between %s and %s'
                                        ),
                                        $field,
                                        $min,
                                        $max
                                    )
                                );
                            }
                            break;
                        case def(strstr($x->current(), 'max')):
                            $delta = $bag->get($field);
                            $number = is_string($delta) ? strlen(strval($delta)) : intval($delta);
                            $x = collect(explode(':', $x->current()));
                            $value = $x->last();

                            if ($number > $value) {
                                $this->addError(
                                    $this->trans('max', $field, 'The %s field is superior to the limit')
                                );
                            }
                            break;

                        case def(strstr($x->current(), 'min')):
                            $x = collect(explode(':', $x->current()));
                            $value = $x->last();
                            $delta = $bag->get($field);
                            $number = is_string($delta) ? strlen(strval($delta)) : intval($delta);

                            if ($number < $value) {
                                $this->addError(
                                    $this->trans('min', $field, 'The %s field is inferior to the limit')
                                );
                            }
                            break;
                        case 'array':
                            if (!is_array($bag->get($field))) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s field is not an array')
                                );
                            }
                            break;
                        case 'snake':
                            if (!is_snake($bag->get($field))) {
                                $this->addError(
                                    $this->trans(
                                        $x->current(),
                                        $field,
                                        'The %s argument is not in the snake case format'
                                    )
                                );
                            }
                            break;
                        case 'camel':
                            if (!is_camel($bag->get($field))) {
                                $this->addError(
                                    $this->trans(
                                        $x->current(),
                                        $field,
                                        'The %s argument is not in the camel case format'
                                    )
                                );
                            }
                            break;
                        case 'ipv4':
                            if (
                                filter_var(
                                    $bag->get($field),
                                    FILTER_VALIDATE_IP,
                                    FILTER_FLAG_IPV4
                                )
                                === false
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid ipv4 address')
                                );
                            }
                            break;
                        case 'ipv6':
                            if (
                                filter_var(
                                    $bag->get($field),
                                    FILTER_VALIDATE_IP,
                                    FILTER_FLAG_IPV6
                                )
                                === false
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid ipv6 address')
                                );
                            }
                            break;
                        case 'domain':
                            if (
                                filter_var(
                                    $bag->get($field),
                                    FILTER_VALIDATE_DOMAIN,
                                    FILTER_FLAG_HOSTNAME
                                )
                                === false
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid domain')
                                );
                            }
                            break;
                        case 'mac':
                            if (
                                filter_var(
                                    $bag->get($field),
                                    FILTER_VALIDATE_MAC
                                )
                                === false
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid mac address')
                                );
                            }
                            break;
                        case 'boolean':
                            if (
                                filter_var(
                                    $bag->get($field),
                                    FILTER_VALIDATE_BOOLEAN
                                ) === false
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid boolean')
                                );
                            }
                            break;
                        case 'float':
                            if (
                                !is_float(
                                    filter_var(
                                        $bag->get($field),
                                        FILTER_VALIDATE_FLOAT,
                                        FILTER_FLAG_ALLOW_THOUSAND
                                    )
                                )
                            ) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not a valid float number')
                                );
                            }
                            break;
                        case 'email':
                            if (filter_var($bag->get($field), FILTER_VALIDATE_EMAIL) === false) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s field is not a valid email')
                                );
                            }
                            break;
                        case 'integer':
                            if (!is_int($bag->get($field))) {
                                $this->addError(
                                    $this->trans($x->current(), $field, 'The %s argument is not an integer')
                                );
                            }
                            break;
                    }
                    $x->next();
                }
            }
            return not_def(static::$errors);
        }

        /**
         *
         * Translate a validator message from user config or default.
         *
         * @param string $key     The current error key
         * @param string $field   The current field not valid
         * @param string $message The default message
         *
         * @return string
         *
         */
        private function trans(string $key, string $field, string $message): string
        {
            return sprintf(
                config('validator', trim($key), $message),
                $field
            );
        }

        /**
         * @param string $message The error message.
         */
        private function addError(string $message): void
        {
            array_push(
                static::$errors,
                $message
            );
        }
    }
}
