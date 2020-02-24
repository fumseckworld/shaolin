<?php

declare(strict_types=1);
namespace Eywa\Database\User {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Exception\Kedavra;

    class User
    {

        /**
         *
         * The connection to the base
         *
         */
        private Connexion $connexion;

        public function __construct(Connexion $connexion)
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
            switch ($this->connexion->driver())
            {
                case MYSQL:
                    return collect($this->connexion->set('SELECT User from mysql.user')->get(COLUMNS));
                break;
                case POSTGRESQL:
                    return collect($this->connexion->set('SELECT rolname FROM pg_roles;')->get(COLUMNS));
                break;
                default:
                    return collect();
                break;
            }
        }
    }
}