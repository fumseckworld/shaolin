<?php


namespace Imperium\Action;


use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;
use Imperium\Model\Model;

class Todo extends Model
{
    protected $table = "todo";

    protected $todo = true;

    /**
     *
     * Create the table
     *
     * @return bool
     *
     * @throws Kedavra
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function generate(): bool
    {
        return static::query()->connexion()->execute(static::$create_todo);
    }
}