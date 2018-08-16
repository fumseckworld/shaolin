<?php
namespace Imperium\Databases\Dumper;


use Symfony\Component\Process\Process;

abstract class DbDumper
{
    /** @var string */
    protected $dbName;

    /** @var string */
    protected $userName;

    /** @var string */
    protected $password;

    /** @var string */
    protected $host = 'localhost';

    /** @var int */
    protected $port = 5432;

    /** @var string */
    protected $socket = '';

    /** @var int */
    protected $timeout = 0;

    /** @var string */
    protected $dumpBinaryPath = '';

    /** @var array */
    protected $includeTables = [];

    /** @var array */
    protected $excludeTables = [];

    /** @var array */
    protected $extraOptions = [];

    /** @var bool */
    protected $enableCompression = false;

    public static function create()
    {
        return new static();
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }


    public function setDbName(string $dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }


    public function setUserName(string $userName)
    {
        $this->userName = $userName;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }


    public function setHost(string $host)
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setPort(int $port)
    {
        $this->port = $port;

        return $this;
    }


    public function setSocket(string $socket)
    {
        $this->socket = $socket;

        return $this;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setDumpBinaryPath(string $dumpBinaryPath)
    {
        if ($dumpBinaryPath !== '' && substr($dumpBinaryPath, -1) !== '/') {
            $dumpBinaryPath .= '/';
        }

        $this->dumpBinaryPath = $dumpBinaryPath;

        return $this;
    }


    public function includeTables($includeTables)
    {
        if (! empty($this->excludeTables)) {
            throw CannotSetParameter::conflictingParameters('includeTables', 'excludeTables');
        }

        if (! is_array($includeTables)) {
            $includeTables = explode(', ', $includeTables);
        }

        $this->includeTables = $includeTables;

        return $this;
    }


    public function excludeTables($excludeTables)
    {
        if (! empty($this->includeTables)) {
            throw CannotSetParameter::conflictingParameters('excludeTables', 'includeTables');
        }

        if (! is_array($excludeTables)) {
            $excludeTables = explode(', ', $excludeTables);
        }

        $this->excludeTables = $excludeTables;

        return $this;
    }


    public function addExtraOption(string $extraOption)
    {
        if (! empty($extraOption)) {
            $this->extraOptions[] = $extraOption;
        }

        return $this;
    }


    public function enableCompression()
    {
        $this->enableCompression = true;

        return $this;
    }

    abstract public function dumpToFile(string $dumpFile);

    protected function checkIfDumpWasSuccessFul(Process $process, string $outputFile)
    {
        if (! $process->isSuccessful()) {
            throw DumpFailed::processDidNotEndSuccessfully($process);
        }

        if (! file_exists($outputFile)) {
            throw DumpFailed::dumpfileWasNotCreated();
        }

        if (filesize($outputFile) === 0) {
            throw DumpFailed::dumpfileWasEmpty();
        }
    }

    protected function echoToFile(string $command, string $dumpFile): string
    {
        $compression = $this->enableCompression ? ' | gzip' : '';

        return $command.$compression.' > '.$dumpFile;
    }
}
