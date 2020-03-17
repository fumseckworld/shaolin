<?php

declare(strict_types=1);
namespace Eywa\Database\User {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;

    class User
    {

        /**
         *
         * The connection to the base
         *
         */
        private Connect $connexion;

        public function __construct(Connect $connexion)
        {
            $this->connexion = $connexion;
        }

        /**
         *
         * Show users
         *
         * @return Collect
         *
         * @throws Kedavra
         *
         */
        public function show(): Collect
        {
            switch ($this->connexion->driver()) {
                case MYSQL:
                    return collect($this->connexion->set('SELECT User from mysql.user')->get(COLUMNS));
                case POSTGRESQL:
                    return collect($this->connexion->set('SELECT rolname FROM pg_roles;')->get(COLUMNS));
                default:
                    return collect();
            }
        }
    }
}
