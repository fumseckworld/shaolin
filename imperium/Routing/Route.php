<?php


namespace Imperium\Routing {


    use Imperium\Connexion\Connect;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function save_route(array $data): bool
        {
            return routes_add($this->routes(),$data);
        }

        /**
         *
         *
         * @param string $name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove_route(string $name): bool
        {
            return $this->routes()->query()->from('routes')->mode(DELETE)->where('name',EQUAL, $name)->delete();
        }

        /**
         *
         * Update a route
         *
         * @param string $name
         * @param array $data
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function update_route(string $name,array $data): bool
        {
            return $this->routes()->update_record(($this->routes()->by('name',$name))->id,$data);
        }

        /**
         *
         * Get an instance of table
         *
         * @return Table
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        private function routes_connect():Connect
        {
            $base = dirname(config_path()) . DIRECTORY_SEPARATOR .collection(config('app','dir'))->get('db') . DIRECTORY_SEPARATOR . 'routes' ;

            Dir::create($base);
            
            $base .= DIRECTORY_SEPARATOR .'routes.sqlite3';

            return connect(SQLITE,$base,'','','','dump');
        }
    }
}