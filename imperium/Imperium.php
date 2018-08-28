<?php

namespace Imperium;


use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\Model\Model;

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
     * Imperium constructor
     *
     * @param Base $base
     * @param Table $table
     * @param Users $users
     * @param Query $query
     * @param Model $model
     */
    public function __construct(Base $base, Table $table, Users $users,Query $query,Model $model)
    {

        $this->base = $base;
        $this->table = $table;
        $this->users = $users;
        $this->query = $query;
        $this->model = $model;
    }




    /**
     * show all tables
     *
     * @return array
     */
    public function tables(): array
    {
        return $this->table->show();
    }

    /**
     * show all users
     *
     * @return array
     */
    public function users(): array
    {
        return $this->users->show();
    }

    /**
     * show all databases
     *
     * @return array
     *
     * @throws Databases\Exception\IdentifierException
     */
    public function databases(): array
    {
        return $this->base->show();
    }

    // START MODEL

    /**
     * insert data in the table
     *
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     */
    public function insert(array $data,array $ignore = []): bool
    {
        return $this->model->insert($data,$ignore);
    }

    /**
     * get all records
     *
     * @return array
     */
    public function records(): array
    {
        return $this->model->all();
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
        return $this->model->where($param,$condition,$expected);
    }

    /**
     * get records by a where clause
     *
     * @param int $id
     * @param array $data
     * @param array $ignore
     *
     * @return bool
     */
    public function update(int $id,array $data,array $ignore = []): bool
    {
        return $this->model->update($id,$data,$ignore);
    }

    /**
     * remove a record by id
     *
     * @param int $id
     *
     * @return bool
     */
    public function remove(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * get a record by an id
     *
     * @param int $id
     *
     * @return array
     */
    public function find(int $id): array
    {
        return $this->model->find($id);
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
        return $this->model->findOrFail($id);
    }

    /**
     * get all columns in a table
     *
     * @return array
     */
    public function columns(): array
    {
        return $this->model->getColumns();
    }

    // END MODEL

    /**
     * @param $secure
     *
     * @param mixed ...$data
     *
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
}