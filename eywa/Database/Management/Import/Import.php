<?php

declare(strict_types=1);
namespace Eywa\Database\Management\Import;


use Eywa\Database\Management\Management;
use Symfony\Component\Process\Process;

class Import extends Process implements Management
{

    private ?string $base = null;

    private ?string $tables = null;

    private ?string $driver = null;

    /**
     * @inheritDoc
     */
    public function save(string $file): bool
    {
        $table = def($this->tables) ? $this->tables : '';
        $base = def($this->base) ? $this->base : '';
        $driver = def($this->driver) ? $this->base : '';


    }

    /**
     * @inheritDoc
     */
    public function select_table(string ...$tables): Management
    {
       $this->tables = join(',',$tables);
       return $this;
    }

    /**
     * @inheritDoc
     */
    public function select_base(string $base): Management
    {
       $this->base = $base;
       return  $this;
    }

    /**
     * @inheritDoc
     */
    public function quote(string $value): string
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '"'.$value. '"' : "'$value'";
    }

    /**
     * @inheritDoc
     */
    public function for(string $driver): Management
    {
        not_in(DRIVERS,$driver,true,'The drivers is not suportted');

        $this->driver = $driver;
        return $this;
    }
}