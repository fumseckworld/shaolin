<?php

namespace Imperium\Html\Form {
    
    use Imperium\Http\Parameters\Bag;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\RedirectResponse;
    use Imperium\Http\Response\Response;
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
         * The action url
         */
        protected static string $action = '';
        
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
         * Analyse the user request and use it data if they are validated.
         *
         * @param Request $request The user request.
         *
         * @return Response
         *
         */
        final public function apply(Request $request): Response
        {
            if ($this->check($request->request)) {
                return $this->success($request->request);
            }
            //TODO add flash message with all errors inside static::$errors
            return (new RedirectResponse(static::$redirect))->send();
        }
    }
}
