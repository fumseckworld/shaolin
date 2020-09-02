<?php

namespace Nol\Html\Form {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Nol\Exception\Kedavra;
    use Nol\Html\Form\Generator\FormGenerator;
    use Nol\Http\Parameters\Bag;
    use Nol\Http\Request\Request;
    use Nol\Http\Response\Response;
    use Nol\Security\Validator\Validator;

    /**
     * Class Form
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Html\Form
     * @version 12
     *
     */
    abstract class Form extends Validator
    {
        /**
         * All input in the form.
         */

        /**
         * The form method
         */
        protected static string $method = 'POST';

        /**
         * The form action
         */
        protected static string $action = '/';

        /**
         * The form submit text
         */
        protected static string $submit = 'submit';

        /**
         *
         * Do something with the validated request.
         *
         * @param Bag $bag The request container.
         *
         * @return Response
         *
         */
        abstract public function success(Bag $bag): Response;

        /**
         *
         * Display the form.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         *
         * @return string
         *
         */
        final public function display(): string
        {
            $form = new FormGenerator();

            $form->open(static::$action, static::$method);

            foreach (static::$fields as $field => $rules) {
                $options = collect();

                $options->put('name', $field);

                $rules = collect(explode('|', $rules))->for('trim')->all();

                foreach ($rules as $rule) {
                    if (def(strstr($rule, ':'))) {
                        $x = collect(explode(':', $rule));
                        $key = $x->first();
                        $value = $x->last();
                        if (def(strstr($value, '%s'))) {
                            $options->put($key, sprintf($value, $field));
                        } else {
                            $options->put($key, $value);
                        }
                    }
                    if (def(strstr($rule, 'required'))) {
                        $options->put('required', 'required');
                    }
                }
                if (is_null($options->get('name'))) {
                    throw new Kedavra('A input in the form has no name');
                }
                if (is_null($options->get('type'))) {
                    throw new Kedavra('A input in the form has no type');
                }
                if (is_null($options->get('label'))) {
                    throw new Kedavra('A input in the form has no label');
                }

                $form->add(
                    $options->get('name'),
                    $options->get('type'),
                    $options->get('label'),
                    $options->del(
                        ['label', 'type', 'name']
                    )->all()
                );
            }
            return $form->close(static::$submit);
        }

        /**
         *
         *  Apply the request if form are valid.
         *
         * @param Request $request The user request.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function apply(Request $request): Response
        {
            if ($request->submitted()) {
                $bag = $request->request();
                if ($this->check($bag) && $request->hasToken()) {
                    return $this->success($bag);
                }
            }
            return $this->redirect();
        }
    }
}
