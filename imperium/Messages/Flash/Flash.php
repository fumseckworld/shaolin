<?php

namespace Imperium\Messages\Flash {
    
    use DI\DependencyException;
    use DI\NotFoundException;
    
    /**
     * Class Flash
     *
     * Represent a flash message.
     *
     * @author Willy Micieli <micieli@outlook.fr>
     * @package Imperium\Messages\Flash
     * @version 12
     *
     */
    class Flash
    {
        /**
         *
         * Define the flash message to display.
         *
         * @param string $message The flash message.
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public static function set(string $message): void
        {
            app('session')->set('flash', $message);
        }
    
        /**
         * Check if a message one message has been defined.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return bool
         *
         *
         */
        public static function has(): bool
        {
            return  app('session')->has('flash');
        }
        
        /**
         *
         * Get the session message.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        public static function message(): string
        {
            if (static::has()) {
                $message = app('session')->get('flash');
                app('session')->del('flash');
                return $message;
            }
            return  '';
        }
    }
}
