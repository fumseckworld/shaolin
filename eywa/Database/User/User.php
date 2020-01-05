<?php

declare(strict_types=1);
namespace Eywa\Database\User {


    use Eywa\Database\Connexion\Connexion;

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
    }
}