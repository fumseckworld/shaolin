<?php

declare(strict_types=1);

namespace Eywa\Database\User {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use PDO;

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
         * @return array|array[]|Collect
         *
         * @throws Kedavra
         *
         */
        public function show()
        {
            switch ($this->connexion->driver()) {
                case MYSQL:
                    return $this->connexion->set('SELECT User from mysql.user')->get(PDO::FETCH_ASSOC);
                case POSTGRESQL:
                    return $this->connexion->set('SELECT rolname FROM pg_roles;')->get(PDO::FETCH_ASSOC);
                default:
                    return collect();
            }
        }
    }
}
