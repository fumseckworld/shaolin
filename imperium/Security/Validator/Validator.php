<?php

namespace Imperium\Security\Validator {

    use Imperium\Http\Parameters\Bag;

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
                                    sprintf('The %s field must be defined', $field)
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
                                        _('The %s field is not between %s and %s'),
                                        $field,
                                        $min,
                                        $max
                                    )
                                );
                            }
                            break;
                        case preg_match("/max:([0-9]+)/", $x->current()) === 1:
                        case preg_match("/maxlength:([0-9]+)/", $x->current()) === 1:
                            $delta = $bag->get($field);
                            $number = is_string($delta) ? strlen(strval($delta)) : intval($delta);
                            $x = collect(explode(':', $x->current()));
                            $value = $x->last();

                            if ($number > $value) {
                                $this->addError(
                                    sprintf(
                                        'The %s field is superior to the limit',
                                        $field
                                    )
                                );
                            }
                            break;
                        case preg_match("/min:([0-9]+)/", $x->current()) === 1:
                        case preg_match("/minlength:([0-9]+)/", $x->current()) === 1:
                            $x = collect(explode(':', $x->current()));
                            $value = $x->last();
                            $delta = $bag->get($field);
                            $number = is_string($delta) ? strlen(strval($delta)) : intval($delta);

                            if ($number < $value) {
                                $this->addError(
                                    sprintf(
                                        'The %s field is inferior to the limit',
                                        $field
                                    )
                                );
                            }
                            break;
                        case 'array':
                            if (!is_array($bag->get($field))) {
                                $this->addError(
                                    sprintf('The %s field is not an array', $field)
                                );
                            }
                            break;
                        case 'snake':
                            if (!is_snake($bag->get($field))) {
                                $this->addError(
                                    sprintf(
                                        'the %s argument is not in the snake case format',
                                        $field
                                    )
                                );
                            }
                            break;
                        case 'camel':
                            if (!is_camel($bag->get($field))) {
                                $this->addError(
                                    sprintf(
                                        'The %s argument is not in the camel case format',
                                        $field
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
                                    sprintf(
                                        'The %s argument is not a valid ipv4 address',
                                        $field
                                    )
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
                                    sprintf(
                                        'The %s argument is not a valid ipv6 address',
                                        $field
                                    )
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
                                    sprintf(
                                        'The %s argument is not a valid domain',
                                        $field
                                    )
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
                                    sprintf(
                                        'The %s argument is not a valid mac address',
                                        $field
                                    )
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
                                    sprintf(
                                        'The %s argument is not a valid boolean',
                                        $field
                                    )
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
                                    sprintf(
                                        'The %s argument is not a valid float number',
                                        $field
                                    )
                                );
                            }
                            break;
                        case 'email':
                            if (filter_var($bag->get($field), FILTER_VALIDATE_EMAIL) === false) {
                                $this->addError(
                                    sprintf(
                                        'The %s field is not a valid email',
                                        $field
                                    )
                                );
                            }
                            break;
                        case 'integer':
                            if (!is_int($bag->get($field))) {
                                $this->addError(
                                    sprintf(
                                        'The %s argument is not an integer',
                                        $field
                                    )
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
