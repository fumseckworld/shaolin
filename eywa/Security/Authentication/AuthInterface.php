<?php

declare(strict_types=1);
namespace Eywa\Security\Authentication {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Response\Response;
    use Eywa\Session\SessionInterface;
    use stdClass;

    interface AuthInterface
    {

        /**
         *
         * AuthInterface constructor.
         *
         * @param SessionInterface $session
         *
         */
        public function __construct(SessionInterface $session);

        /**
         *
         * Check if the user is connected
         *
         * @return bool
         *
         */
        public function connected(): bool;

        /**
         *
         * Check the role
         *
         * @param string $role
         *
         * @return bool
         *
         */
        public function is(string $role): bool;

        /**
         *
         * Remove auth session information
         *
         * @return bool
         *
         */
        public function clean(): bool;

        /**
         *
         * Get the current connected user instance
         *
         * @return stdClass
         *
         */
        public function current(): stdClass;


        /**
         *
         * Log the user on success
         *
         * @param string $username
         * @param string $password
         *
         * @return Response
         *
         * @throws Kedavra
         *
         *
         */
        public function login(string $username,string $password): Response;

        /**
         *
         * Logout the user
         *
         * @return Response
         *
         * @throws Kedavra
         *
         *
         */
        public function logout(): Response;


        /**
         *
         * Remove the user account
         *
         * @return Response
         *
         * @throws Kedavra
         *
         *
         */
        public function delete_account(): Response;

    }
}