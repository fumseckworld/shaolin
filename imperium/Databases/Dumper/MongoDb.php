<?php

namespace Imperium\Databases\Dumper;

use Symfony\Component\Process\Process;

class MongoDb extends DbDumper
{
    protected $port = 27017;

    /** @var null|string */
    protected $collection = null;

    /** @var null|string */
    protected $authenticationDatabase = null;


    public function dumpToFile(string $dumpFile)
    {
        $this->guardAgainstIncompleteCredentials();

        $command = $this->getDumpCommand($dumpFile);

        $process = new Process($command);

        if (! is_null($this->timeout)) {
            $process->setTimeout($this->timeout);
        }

        $process->run();

        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
    }


    protected function guardAgainstIncompleteCredentials()
    {
        foreach (['dbName', 'host'] as $requiredProperty) {
            if (strlen($this->$requiredProperty) === 0) {
                throw CannotStartDump::emptyParameter($requiredProperty);
            }
        }
    }

    public function setCollection(string $collection)
    {
        $this->collection = $collection;

        return $this;
    }


    public function setAuthenticationDatabase(string $authenticationDatabase)
    {
        $this->authenticationDatabase = $authenticationDatabase;

        return $this;
    }


    public function getDumpCommand(string $filename) : string
    {
        $command = [
            "'{$this->dumpBinaryPath}mongodump'",
            "--db {$this->dbName}",
            '--archive',
        ];

        if ($this->userName) {
            $command[] = "--username '{$this->userName}'";
        }

        if ($this->password) {
            $command[] = "--password '{$this->password}'";
        }

        if (isset($this->host)) {
            $command[] = "--host {$this->host}";
        }

        if (isset($this->port)) {
            $command[] = "--port {$this->port}";
        }

        if (isset($this->collection)) {
            $command[] = "--collection {$this->collection}";
        }

        if ($this->authenticationDatabase) {
            $command[] = "--authenticationDatabase {$this->authenticationDatabase}";
        }

        return $this->echoToFile(implode(' ', $command), $filename);
    }
}
