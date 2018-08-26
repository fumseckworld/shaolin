<?php


namespace Imperium\Model;



use Exception;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;
use PDO;

class Model
{


    /**
     * @var Query
     */
    private $sql;

    /**
     * @var string
     */
    private $primary;

    /**
     * @var string
     */
    private $table;

    /**
     * @var Table
     */
    private $instance;


    public function __construct(PDO $pdo,Table $instance, string $table,int $pdoMode = PDO::FETCH_OBJ,string $oderBy = 'desc')
    {
        $this->instance = $instance->setName($table);
        $this->primary = $this->instance->primaryKey();
        $this->sql = sql($table)->setPdo($pdo)->orderBy($this->primary,$oderBy)->setPdoMode($pdoMode);

        $this->table = $table;

    }

    /**
     * show all tables
     *
     * @return array
     */
    public function showTables():array
    {
        return $this->instance->show();
    }

    /**
     * get all records with an order by
     *
     *
     * @return array
     */
    public function all(): array
    {
        return $this->sql->getRecords();
    }

    /**
     * find a record by id
     *
     * @param int $id
     *
     * @return array
     */
    public function find(int $id):array
    {
        return $this->sql->where($this->primary,'=',$id)->getRecords();
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

        if (empty($data))
            throw new Exception('Record not found');
        else
            return $data;
    }

    /**
     * get records by a where clause
     *
     * @param string $param
     * @param string $condition
     * @param $expected
     *
     * @return array
     */
    public function where(string $param,string $condition,$expected): array
    {
        return $this->sql->where($param,$condition,$expected)->getRecords();
    }


    /**
     * remove a record
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->sql->where($this->primary,'=',$id)->delete();
    }

    /**
     * save data in the table
     *
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     */
    public function save(array $data,array $ignore = []): bool
    {
        return $this->instance->insert($data,$ignore);
    }


    /**
     * count record
     *
     * @return array|int
     */
    public function count()
    {
        return $this->instance->count();
    }

    /**
     * truncate a table
     *
     * @return bool
     */
    public function truncate(): bool
    {
        return $this->instance->truncate();
    }


    /**
     * update a record
     *
     * @param int $id
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     */
    public function update(int $id,array $data,array $ignore =[]): bool
    {
        return $this->instance->update($id,$data,$ignore);
    }

    /**
     * get all table columns
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->instance->getColumns();
    }

    /**
     * check if a table is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->instance->isEmpty();
    }
}