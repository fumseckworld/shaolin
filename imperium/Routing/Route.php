<?php


namespace Imperium\Routing {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Model\Model;
    use Imperium\Tables\Table;

    trait Route
    {


        /**
         *
         * The sql query to create the table
         *
         * @var string
         *
         */
        protected $sql = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL,method TEXT(255) NOT NULL);";


        /**
         *
         * Get an instance of model
         *
         * @return Model
         *
         * @throws Exception
         *
         */
        public function routes(): Model
        {
            return (new Model($this->routes_connect(),$this->routes_table()))->from('routes');
        }


        /**
         *
         * Create the routes table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        protected function create_route_table(): bool
        {
            return $this->routes_table()->not_exist('routes') ?  $this->routes_connect()->execute($this->sql) : true;
        }


        /**
         *
         * Add a new route
         *
         * @param array $data
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function save_route(array $data): bool
        {
            return routes_add($this->routes(),$data);
        }


        /**
         *
         * Update a route
         *
         * @param int $id
         * @param array $data
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update_route(int $id,array $data): bool
        {
            return $this->routes()->update_record($id,$data);
        }

        /**
         *
         * Get an instance of table
         *
         * @return Table
         *
         * @throws Exception
         *
         */
        private function routes_table(): Table
        {
            return table($this->routes_connect());
        }

        /**
         *
         * Get an instance of connect
         *
         * @return Connect
         *
         * @throws Exception
         *
         */
        private function routes_connect():Connect
        {
            return connect(SQLITE,'routes.sqlite3','','','','dump');
        }
    }
}