<?php


namespace Imperium\Model;



use Exception;
use Imperium\Connexion\Connect;
use Imperium\Imperium;
use Imperium\Query\Query;
use Imperium\Tables\Table;
use PDO;

/**
 *  Table content management
 *
 * Class Model
 *
 * @package Imperium\Model
 *
 */
class Model
{
     private $connexion;

    /**
     * @var Table
     */
    private $table;

    /**
     * current table
     *
     * @var string
     */
    private $current;

    /**
     * the primary key
     *
     * @var string
     */
    private $primary;

    /**
     * @var \Imperium\Query\Query
     */
    private $sql;


    /**
     * Model constructor.
     *
     * @param Connect $connect
     * @param Table $table
     * @param string $current_table_name
     * @param string $oder_by

     * @throws Exception
     */
    public function __construct(Connect $connect,Table $table, string $current_table_name,string $oder_by= 'desc')
    {
        $this->connexion = $connect;

        $this->table = $table->set_current_table($current_table_name);
        $this->primary = $this->table->get_primary_key();
        $this->sql = query($table,$connect)->connect($connect)->set_current_table_name($current_table_name)->order_by($this->primary,$oder_by);
        $this->current  = $current_table_name;
    }

    /**
     *
     * Display all tables in current database
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function show_tables(): array
    {
        return $this->table->show();
    }

    /**
     * Return true if the current connexion is mysql
     *
     * @return bool
     *
     */
    public function is_mysql()
    {
        return $this->connexion->mysql();
    }

    /**
     *
     * Return true if the current connexion is postgresql
     *
     * @return bool
     *
     */
    public function is_postgresql()
    {
        return $this->connexion->postgresql();
    }

    /**
     *
     * Return true if the current connexion is sqlite
     *
     * @return bool
     *
     */
    public function is_sqlite()
    {
        return $this->connexion->sqlite();
    }

    /**
     *
     * Return the sql builder instance
     *
     * @return Query
     *
     */
    public function query(): Query
    {
        return $this->sql;
    }

    /**
     *
     * Get all records in current table
     * with an order by
     *
     * @param string $order
     * @return array
     *
     * @throws Exception
     *
     */
    public function all(string $order = 'desc'): array
    {
       return $this->table->getRecords($order);
    }


    /**
     *
     * Select a record by this id
     *
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function find(int $id): array
    {
        return $this->sql->set_query_mode(Imperium::SELECT)->where($this->primary,'=',$id)->get();
    }

    /**
     *
     * Select a record by this id
     * or throw exception if record was not found
     *
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function findOrFail(int $id): array
    {
        $data = $this->find($id);

        if (not_def($data))
            throw new Exception('Record not found');
        else
            return $data;
    }

    /**
     *
     * Select only one or multiples
     * records with a clause where
     *
     * @param $param
     * @param $condition
     * @param $expected
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function where($param,$condition,$expected): array
    {
        return equal($condition,'LIKE') ?  $this->sql->like($this->table,$expected)->get() : $this->sql->where($param,html_entity_decode($condition),$expected)->get();

    }

    /**
     *
     * Remove a record by this id
     *
     * @param int $id
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function remove(int $id): bool
    {
        return $this->sql->where($this->primary,'=',$id)->delete();
    }

    /**
     *
     * Insert data in the table
     *
     *
     * @param array $data
     * @param string $table
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function insert(array $data,string $table ,array $ignore = []): bool
    {
        return $this->table->insert($data,$ignore,$table);
    }

    /**
     *
     * Return number of record inside the current table
     *
     * @return int
     *
     * @throws Exception
     *
     */
    public function count(): int
    {
        return $this->table->count();
    }

    /**
     *
     * Empty the table
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function truncate(): bool
    {
        return $this->table->truncate();
    }


    /**
     *
     * Update a record by this id
     *
     * @param int $id
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function update(int $id,array $data,array $ignore =[]): bool
    {
        return $this->table->update($id,$data,$ignore);
    }

    /**
     * Display all columns inside the current table
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function columns(): array
    {
        return $this->table->get_columns();
    }

    /**
     * Check if the current table has not record
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function empty(): bool
    {
        return $this->table->is_empty();
    }

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
     *
     * return the results of the custom query in an array
     *
     * @param string $query
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function request(string $query): array
    {
        return $this->connexion->request($query);
    }

    /**
     *
     * Execute a custom query
     *
     * @param string $query
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    public function execute(string $query): bool
    {
        return $this->connexion->execute($query);
    }
}