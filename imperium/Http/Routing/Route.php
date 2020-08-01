<?php

namespace Imperium\Http\Routing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Exception\Kedavra;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\Response;

    /**
     * Class Route
     *
     * @package Imperium\Http\Routing\Route
     *
     * @property string $controller The controller to call.
     * @property string $action     The controller action to execute.
     * @property array  $args       The controller actions arguments
     */
    class Route
    {
        /**
         *
         * @param string $controller The controller name.
         * @param string $action     The controller action name.
         * @param array  $args       The action arguments.
         *
         * @throws Kedavra
         */
        public function __construct(string $controller, string $action, array $args = [])
        {
            $this->controller = $controller;
            $this->action = $action;
            $this->args = $args;

            if (!class_exists($this->controller)) {
                throw new Kedavra(_('The controller has not been found'));
            }

            if (!method_exists($this->controller, $this->action)) {
                throw new Kedavra(
                    sprintf(
                        _('The %s method has not been found in the %s controller'),
                        $this->action(),
                        $this->controller()
                    )
                );
            }
        }

        /**
         *
         * Get the action name.
         *
         * @return string
         *
         */
        final public function action(): string
        {
            return $this->action;
        }

        /**
         *
         * Get the controller name.
         *
         * @return string
         *
         */
        final public function controller(): string
        {
            return $this->controller;
        }

        /**
         *
         * Get the action args.
         *
         * @throws Kedavra
         *
         * @return Request
         *
         */
        final public function args(): Request
        {
            return cli() ? (new Request())->with('args', $this->args) : Request::make($this->args);
        }

        /**
         *
         * Execute the callback.
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function exec(): Response
        {
            /** @phpstan-ignore-next-line */
            return call_user_func_array([app($this->controller()), $this->action()], [$this->args()])->send();
        }
    }
}
