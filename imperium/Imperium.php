<?php

namespace Imperium;


use Exception;
use Imperium\Connexion\Connect;
use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\Model\Model;
use PDO;

class Imperium
{
    /**
     * @var Base
     */
    private $base;

    /**
     * @var Table
     */
    private $table;

    /**
     * @var Users
     */
    private $users;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var Connect
     */
    private $connexion;
    /**
     * @var string
     */
    protected $btn_default_class;

    /**
     * @var string
     */
    protected $btn_danger_class;

    /**
     * Imperium constructor
     *
     * @param Base $base
     * @param Table $table
     * @param Users $users
     * @param Query $query
     * @param Model $model
     * @param Connect $connect
     *
     * @param string $btn_class
     * @param string $btn_danger_class
     */
    public function __construct(Base $base, Table $table, Users $users,Query $query,Model $model,Connect $connect,string $btn_class,string $btn_danger_class)
    {
        $this->connexion = $connect;

        $this->base = $base;
        $this->table = $table;
        $this->users = $users;
        $this->query = $query->connect($connect);
        $this->model = $model;

        $this->query = $query;
        $this->btn_default_class = $btn_class;
        $this->btn_danger_class = $btn_danger_class;
    }

    /**
     * get default btn class
     *
     * @param bool $default
     * @return string
     */
    public function class(bool $default = true): string
    {
        return $default ? $this->btn_default_class : $this->btn_danger_class;
    }

    /**
     * show all users
     *
     * @param array $hidden
     * @return array
     *
     * @throws Exception
     */
    public function show_users(array $hidden = []): array
    {
        return $this->user()->hidden($hidden)->show();
    }

    /**
     * show all databases
     *
     * @param array $hidden
     * @return array
     *
     * @throws Exception
     */
    public function show_databases(array $hidden = []): array
    {
        return $this->base()->hidden($hidden)->show();
    }

    /**
     * show all tables
     *
     * @param array $hidden
     * @return array
     *
     * @throws Exception
     */
    public function show_tables(array $hidden = []): array
    {
        return $this->table()->hidden($hidden)->show();
    }

    // START MODEL

    /**
     * insert data in the table
     *
     * @param array $data
     * @param string $table
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     */
    public function insert(array $data,string $table,array $ignore = []): bool
    {
        return $this->model()->insert($data,$table,$ignore);
    }

    /**
     * get all records
     *
     * @param string $order
     * @return array
     *
     * @throws Exception
     */
    public function records(string $order = 'desc'): array
    {
        return $this->model()->all($order);
    }

    /**
     * get records by a where clause
     *
     * @param string $param
     * @param string $condition
     * @param $expected
     *
     * @return array
     *
     * @throws Exception
     */
    public function where(string $param,string $condition,$expected): array
    {
        return $this->model()->where($param,$condition,$expected);
    }

    /**
     * update a records
     *
     * @param int $id
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     */
    public function update(int $id,array $data,array $ignore = []): bool
    {
        return $this->model()->update($id,$data,$ignore);
    }

    /**
     * remove a record by id
     *
     * @param int $id
     *
     * @return bool
     *
     * @throws Exception
     */
    public function remove(int $id): bool
    {
        return $this->model()->remove($id);
    }

    /**
     * get a record by an id
     *
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function find(int $id): array
    {
        return $this->model()->find($id);
    }

    /**
     * get a record by an id
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function findOrFail(int $id): array
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * get all columns in a table
     *
     * @return array
     *
     * @throws Exception
     */
    public function columns(): array
    {
        return $this->model()->columns();
    }

    /**
     * get the column types
     *
     * @return array
     *
     * @throws Exception
     */
    public function column_types(): array
    {
        return $this->table()->get_columns_types();
    }

    // END MODEL

    /**
     * @param mixed ...$data
     */
    public function print(...$data)
    {
        foreach ($data as $x)
        {
            if (!is_array($x))
            {
                echo $x;
            }else {
                d($x);
            }

        }
    }

    // BASE

    /**
     * verify if a database exist
     *
     * @param string $base
     *
     * @return bool
     *
     * @throws Exception
     */
    public function database_exist(string $base): bool
    {
        return $this->base()->exist($base);
    }

    /**
     * verify if an user exist
     *
     * @param string $user
     *
     * @return bool
     *
     * @throws Exception
     */
    public function user_exist(string $user): bool
    {
        return $this->user()->exist($user);
    }

    /**
     * verify if a database exist
     *
     * @param string $table
     *
     * @return bool
     *
     * @throws Exception
     */
    public function table_exist(string $table): bool
    {
        return $this->table()->exist($table);
    }

    /**
     * get database charsets possibilities
     *
     * @return array
     *
     * @throws Exception
     */
    public function charset(): array
    {
        return $this->base()->charsets();
    }

    /**
     * get database collations possibilities
     *
     * @return array
     *
     * @throws Exception
     */
    public function collation(): array
    {
        return $this->base()->collations();
    }

    /**
     * create a database
     *
     * @param string $name
     * @param string $collation
     * @param string $charset
     * @return bool
     *
     * @throws Exception
     */
    public function createDatabase(string $name,string $collation = '',string $charset= ''): bool
    {
        return $this->base()->exist($name) ? false : $this->base()->set_collation($collation)->set_charset($charset)->create($name);
    }

    /**
     * Drop a database
     *
     * @param string $base
     *
     * @return bool
     *
     * @throws Exception
     */
    public function dropDatabase(string $base): bool
    {
        return $this->base()->drop($base);
    }


    // END BASE


    // QUERY

    /**
     * create union query
     *
     * @param int $mode
     * @param string $firstTable
     * @param string $secondTable
     * @param array $firstColumns
     * @param array $secondColumns
     *
     * @return Query
     */
    public function union(int $mode,string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query
    {
        return $this->query()->union($mode,$firstTable,$secondTable,$firstColumns,$secondColumns);
    }

    /**
     *  create a join query
     *
     * @param int $type
     * @param string $firstTable
     * @param string $secondTable
     * @param string $firstParam
     * @param string $secondParam
     * @param array $firstColumns
     * @param string $condition
     *
     * @return Query
     */
    public function join(int $type,string $firstTable,string $secondTable,string $firstParam,string $secondParam,array $firstColumns = [], string $condition ='='): Query

    {
        return $this->query()->join($type,$firstTable,$secondTable,$firstParam,$secondParam,$firstColumns,$condition);
    }

    // END QUERY

    // INSTANCE

    /**
     * get pdo instance
     *
     * @return PDO
     *
     * @throws Exception
     */
    public function pdo(): PDO
    {
        return $this->connexion->instance();
    }

    /**
     * get pdo instance
     *
     * @return Connect
     *
     * @throws Exception
     */
    public function connect(): Connect
    {
        return $this->connexion;
    }

    /**
     * get a table instance
     *
     * @return Table
     */
    public function table(): Table
    {
        return $this->table;
    }

    /**
     * get model instance
     *
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     * get base instance
     *
     * @return Base
     */
    public function base(): Base
    {
        return $this->base;
    }

     /**
     * get user instance
     *
     * @return Users
     */
    public function user(): Users
    {
        return $this->users;
    }

    /**
     * get query instance
     *
     * @return Query
     */
    public function query(): Query
    {
        return $this->query;
    }

    // END INSTANCE
}