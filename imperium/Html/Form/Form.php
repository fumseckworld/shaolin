<?php

namespace Imperium\Html\Form {
    
    use DI\DependencyException;
    use DI\NotFoundException;
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
         * The form to display at the user.
         *
         * @return string
         *
         */
        abstract public function display(): string;
        
        /**
         *
         * Return an instance of the form generator.
         *
         * @return FormGenerator
         *
         */
        protected function builder(): FormGenerator
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
        public function redirect(string $message, bool $success = true): Response
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
