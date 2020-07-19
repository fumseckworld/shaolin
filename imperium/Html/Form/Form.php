<?php

namespace Imperium\Html\Form {
    
    use DI\DependencyException;
    use DI\NotFoundException;
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
         * The action method.
         */
        protected static string $method = 'POST';
        
        /**
         * The route name
         */
        protected static string $route = '';
        
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
            
            if (not_cli()) {
                $message = '<ul class="alert alert-danger">';
                $message .= collect(static::$errors)->for(function ($error) {
                    return sprintf('<li>%s</li>', $error);
                })->join('');
                $message .= '</ul>';
                Flash::set($message);
            }
            return (new RedirectResponse(static::$redirect))->send();
        }
    }
}
