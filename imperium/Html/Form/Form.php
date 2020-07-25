<?php

namespace Imperium\Html\Form {
    
    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Imperium\Exception\Kedavra;
    use Imperium\Html\Form\Generator\FormGenerator;
    use Imperium\Http\Parameters\Bag;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\RedirectResponse;
    use Imperium\Http\Response\Response;
    use Imperium\Messages\Flash\Flash;
    use Imperium\Security\Validator\Validator;
    
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
        abstract protected function success(Bag $bag): Response;
        
        /**
         *
         * Display the form.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         * @throws Kedavra
         *
         * @return string
         *
         */
        final public function display(): string
        {
            $form = $this->builder();
            
            $form->open(static::$action, static::$method);
            
            foreach (static::$fields as $field => $rules) {
                $options = collect();
                
                $options->put('name', $field);
                
                $rules = explode('|', $rules);
                
                foreach ($rules as $rule) {
                    if (def(strstr($rule, ':'))) {
                        $x = collect(explode(':', $rule));
                        $key = $x->first();
                        $value = $x->last();
                        $options->put($key, $value);
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
         * Return an instance of the form generator.
         *
         * @return FormGenerator
         *
         */
        final private function builder(): FormGenerator
        {
            return new FormGenerator();
        }
        
        /**
         *
         * Redirect user to an url with a flash message.
         *
         * @param string $message Redirect user message.
         * @param bool   $success Request has been an successfully executed or not.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function redirect(string $message, bool $success = true): Response
        {
            if (not_cli()) {
                $success
                    ? Flash::set(sprintf('<div class="alert alert-success">%s</div>', $message))
                    : Flash::set($message);
            }
            return (new RedirectResponse(static::$redirect))->send();
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
            if ($this->check($request->request)) {
                return $this->success($request->request);
            }
           
            $message = '<ul class="alert alert-danger">';
            $message .= collect(static::$errors)->for(
                function ($error) {
                    return sprintf('<li>%s</li>', $error);
                }
            )->join('');
            $message .= '</ul>';

            return $this->redirect($message, false);
        }
    }
}
