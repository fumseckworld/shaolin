<?php


namespace Imperium\Model;

use Exception;
use Imperium\Connexion\Connect;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Share;
use Imperium\Databases\Eloquent\Tables\Table;
use PDO;

class Model
{
     use Share;

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

        $this->tables = $table->set_current_table($current_table_name);
        $this->primary = $this->tables->get_primary_key();
        $this->sql = query($table,$connect)->connect($connect)->set_current_table_name($current_table_name)->order_by($this->primary,$oder_by);
        $this->table  = $current_table_name;
    }

    /**
     * show all tables
     *
     * @return array
     *
     * @throws Exception
     */
    public function show_tables():array
    {
        return $this->tables->show();
    }

    public function query()
    {
        return $this->sql;
    }
    /**
     * get all records with an order by
     *
     * @param string $order
     * @return array
     *
     * @throws Exception
     */
    public function all(string $order = 'desc'): array
    {
       return $this->tables->getRecords($order);
    }

    /**
     * find a record by id
     *
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function find(int $id):array
    {
          return $this->sql->set_query_mode(Query::SELECT)->where($this->primary,'=',$id)->get();
    }

    /**
     * find a record or fail
     *
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
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
     * get records by a where clause
     *
     * @param $param
     * @param $condition
     * @param $expected
     *
     * @return array
     *
     * @throws Exception
     */
    public function where($param,$condition,$expected): array
    {
        return equal($condition,'LIKE') ?  $this->sql->like($this->tables,$expected)->get() : $this->sql->where($param,html_entity_decode($condition),$expected)->get();

    }

    /**
     * remove a record
     *
     * @param int $id
     *
     * @return bool
     *
     * @throws Exception
     */
    public function remove(int $id): bool
    {
        return $this->sql->where($this->primary,'=',$id)->delete();
    }

    /**
     * save data in the table
     *
     * @param array $data
     * @param string $table
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     */
    public function insert(array $data,string $table ,array $ignore = []): bool
    {
        return $this->tables->insert($data,$ignore,$table);
    }


    /**
     * count record
     *
     * @return array|int
     *
     * @throws Exception
     */
    public function count()
    {
        return $this->tables->count();
    }

    /**
     * truncate a table
     *
     * @return bool
     *
     * @throws Exception
     */
    public function truncate(): bool
    {
        return $this->tables->truncate();
    }


    /**
     * update a record
     *
     * @param int $id
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     *
     * @throws Exception
     */
    public function update(int $id,array $data,array $ignore =[]): bool
    {
        return $this->tables->update($id,$data,$ignore);
    }

    /**
     * get all table columns
     *
     * @return array
     *
     * @throws Exception
     */
    public function columns(): array
    {
        return $this->tables->get_columns();
    }

    /**
     * check if a table is empty
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isEmpty(): bool
    {
        return $this->tables->is_empty();
    }

    /**
     * get pdo instance
     *
     * @return PDO
     *
     * @throws Exception
     */
    public function getInstance(): PDO
    {
        return $this->connexion->instance();
    }

    /**
     * @param string $query
     *
     * @return array
     *
     * @throws Exception
     */
    public function request(string $query):array
    {
        return $this->connexion->request($query);
    }

    /**
     * @param string $query
     *
     * @return bool
     *
     * @throws Exception
     */
    public function execute(string $query):bool
    {
        return $this->connexion->execute($query);
    }
}