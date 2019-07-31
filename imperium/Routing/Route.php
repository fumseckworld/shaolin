<?php
	
	namespace Imperium\Routing
	{
		
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\Model\Model;
		use Imperium\Query\Query;
		use Imperium\Request\Request;
		use Imperium\Tables\Table;
		use PDO;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
    use PhpParser\Node\Expr\BinaryOp\Equal;

class Route
		{
			
			/**
			 * @var Route
			 */
			private static $instance;
			
			/**
			 *
			 * The sql query to create the table
			 *
			 * @var string
			 *
			 */
			protected $create_route_table_query = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL,method TEXT(255) NOT NULL);";
			
			/**
			 * @var string
			 *
			 */
			private $table = 'routes';
			
			/**
			 *
			 * Get an instance of model
			 *
			 * @return Model
			 *
			 *
			 */
			private function routes() : Model
			{
				
				return ( new Model($this->routes_connect()))->from($this->table);
			}
			
			
			/**
			 *
			 * Create the routes table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function create_route_table() : bool
			{
				
				return $this->routes_table()->not_exist($this->table) ? $this->routes_connect()->execute($this->create_route_table_query) : true;
			}
			
			/**
			 *
			 * Add a new route
			 *
			 * @method add
			 *
			 * @param  array  $data
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function add(array $data) : bool
			{
				
				return routes_add($this->routes(), $data);
			}
			
			/**
			 *
			 * Delete a route
			 *
			 * @param  string  $name
			 *
			 * @method del
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function del(string $name) : bool
			{
				return $this->routes()->query()->from($this->table)->mode(DELETE)->where('name', EQUAL, $name)->delete();
			}
			
			/**
			 *
			 * Update a route
			 *
			 * @method update
			 *
			 * @param  string  $id
			 * @param  array   $data
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function update(string $id, array $data) : bool
			{
				
				return $this->routes()->update_record($id, $data);
			}
			
			/**
			 *
			 * Get an instance of table
			 *
			 * @return Table
			 *
			 *
			 */
			private function routes_table() : Table
			{
				
				return table($this->routes_connect());
			}
			
			/**
			 *
			 * Get an instance of connect
			 *
			 * @return Connect
			 *
			 *
			 */
			private function routes_connect() : Connect
			{
				
				$base = CORE . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'routes.sqlite3';
				
				return connect(SQLITE, $base, '', '', '', 'dump');
			}
			
			/**
			 *
			 * Get routes names
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function names() : array
			{
				
				$x = collect();
				
				foreach ( $this->get('name') as $actions )
					foreach ( $actions as $action )
						$x->push($action);
				
				return $x->all();
				
			}
			
			/**
			 *
			 * Get all urls registered
			 *
			 * @method urls
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function urls() : array
			{
				
				$x = collect();
				
				foreach ( $this->get('url') as $actions )
					foreach ( $actions as $action )
						$x->push($action);
				
				return $x->all();
				
			}
			
			/**
			 *
			 * Communique to the base and get values
			 *
			 * @method get
			 *
			 * @param  string  $column
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			private function get(string $column = '') : array
			{
				
				return def($column) ?  $this->routes()->query()->from($this->table)->mode(SELECT)->pdo(PDO::FETCH_ASSOC)->only($column)->get() : $this->routes()->query()->from($this->table)->mode(SELECT)->get();
			}
			
			/**
			 *
			 * Get an instance of route
			 *
			 * @method manage
			 *
			 * @return Route
			 *
			 */
			public static function manage() : Route
			{
				
				if ( is_null(self::$instance) )
					self::$instance = new static();
				
				return self::$instance;
			}
			
			
			/**
			 *
			 * Get all routes possible values
			 *
			 * @method all
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function all() : array
			{
				
				return collect()->merge($this->urls(), $this->names(), $this->actions(), controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all())->all();
			}
			
			/**
			 *
			 * Check if a column is already used
			 *
			 * @method check
			 *
			 * @param  string  $column
			 * @param  mixed   $value
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 */
			public function check(string $column, $value) : bool
			{
				
				return def($this->routes()->from($this->table)->by($column, $value));
			}
			
			public function expected(string $method): array
			{

				return $this->routes()->query()->from($this->table)->mode(SELECT)->where('method',EQUAL,$method)->get();

			}
			/**
			 *
			 * Create all routes
			 *
			 * @method create
			 *
			 * @param  array  $routes
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function create(array $routes) : bool
			{
				
				
				$primary = 'id';
				$x = '';
				foreach ( $routes as $k => $v )
				{
				
				
					different($k, $primary) ? append($x, $this->routes()->quote($v) . ', ') : append($x, 'NULL, ');
					
				}
				$data =  '('.trim($x, ', ') .')';
				$data = "INSERT INTO routes ('id','name', 'url','controller', 'action', 'method') VALUES $data";
				
				return  $this->routes()->execute($data);
				
			}
			
			/**
			 *
			 * Get all actions
			 *
			 * @method actions
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function actions() : array
			{
				
				$x = collect();
				
				foreach ( $this->get('action') as $actions )
					foreach ( $actions as $action )
						$x->push($action);
				
				return $x->all();
			}
			
			public function find(string $like)
			{
				
				return $this->routes()->search($like);
			}
			
			public function list(InputInterface $input, OutputInterface $output, array $like = [])
			{
				
				$routes = def($like) ? $like : $this->routes()->from($this->table)->all();
				
				if ( def($routes) )
				{
					foreach ( $routes as $route )
					{
						$method = "\n\t<fg=cyan;options=bold>$route->method</>";
						$name = "\n<fg=blue;options=bold>@$route->name</>";
						
						$url = "\n\t<fg=magenta;options=bold>$route->url</>";
						$controller = "\n\t<fg=green;options=bold>$route->controller</>";
						$action = "\n\t<fg=yellow;options=bold>$route->action</>";
						
						$output->writeln($name);
						$output->writeln($method);
						$output->writeln($url);
						$output->writeln($controller);
						$output->writeln($action);
						$output->writeln('');
						
					}
					
					return 0;
				}
				
				$output->write("<error>We have not found routes</error>\n");
				
				return 1;
				
			}
			
			public function by(string $name)
			{
				
				return $this->routes()->by('name', $name);
			}
			
		}
	}
