<?php


namespace Imperium\Model;


use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;

class Task extends Model
{
    protected $table  = "routes";

    protected $task = true;

    /**
     *
     * @return bool
     * @throws Kedavra
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function generate(): bool
    {
        return static::query()->connexion()->execute(static::$create_route_table_query);
    }

}