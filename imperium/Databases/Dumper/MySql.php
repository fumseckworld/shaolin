<?php

namespace Imperium\Databases\Dumper;

use Symfony\Component\Process\Process;

class MySql extends DbDumper
{
    /** @var bool */
    protected $skipComments = true;

    /** @var bool */
    protected $useExtendedInserts = true;

    /** @var bool */
    protected $useSingleTransaction = false;

    /** @var string */
    protected $defaultCharacterSet = '';

    /** @var bool */
    protected $dbNameWasSetAsExtraOption = false;

    /** @var string */
    protected $setGtidPurged = 'AUTO';

    public function __construct()
    {
        $this->port = 3306;
    }


    public function skipComments()
    {
        $this->skipComments = true;

        return $this;
    }


    public function dontSkipComments()
    {
        $this->skipComments = false;

        return $this;
    }


    public function useExtendedInserts()
    {
        $this->useExtendedInserts = true;

        return $this;
    }

    public function dontUseExtendedInserts()
    {
        $this->useExtendedInserts = false;

        return $this;
    }

    public function useSingleTransaction()
    {
        $this->useSingleTransaction = true;

        return $this;
    }

    public function dontUseSingleTransaction()
    {
        $this->useSingleTransaction = false;

        return $this;
    }


    public function setDefaultCharacterSet(string $characterSet)
    {
        $this->defaultCharacterSet = $characterSet;

        return $this;
    }


    public function setGtidPurged(string $setGtidPurged)
    {
        $this->setGtidPurged = $setGtidPurged;

        return $this;
    }

     public function dumpToFile(string $dumpFile)
    {
        $this->guardAgainstIncompleteCredentials();

        $tempFileHandle = tmpfile();
        fwrite($tempFileHandle, $this->getContentsOfCredentialsFile());
        $temporaryCredentialsFile = stream_get_meta_data($tempFileHandle)['uri'];

        $command = $this->getDumpCommand($dumpFile, $temporaryCredentialsFile);

        $process = new Process($command);

        if (! is_null($this->timeout)) {
            $process->setTimeout($this->timeout);
        }

        $process->run();

        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
    }

    public function addExtraOption(string $extraOption)
    {
        if (preg_match('/^--databases (\S+)/', $extraOption, $matches) === 1) {
            $this->setDbName($matches[1]);
            $this->dbNameWasSetAsExtraOption = true;
        }

        return parent::addExtraOption($extraOption);
    }

    public function getDumpCommand(string $dumpFile, string $temporaryCredentialsFile): string
    {
        $quote = $this->determineQuote();

        $command = [
            "{$quote}{$this->dumpBinaryPath}mysqldump{$quote}",
            "--defaults-extra-file=\"{$temporaryCredentialsFile}\"",
        ];

        if ($this->skipComments) {
            $command[] = '--skip-comments';
        }

        $command[] = $this->useExtendedInserts ? '--extended-insert' : '--skip-extended-insert';

        if ($this->useSingleTransaction) {
            $command[] = '--single-transaction';
        }

        if ($this->socket !== '') {
            $command[] = "--socket={$this->socket}";
        }

        foreach ($this->excludeTables as $tableName) {
            $command[] = "--ignore-table={$this->dbName}.{$tableName}";
        }

        if (! empty($this->defaultCharacterSet)) {
            $command[] = '--default-character-set='.$this->defaultCharacterSet;
        }

        foreach ($this->extraOptions as $extraOption) {
            $command[] = $extraOption;
        }

        if ($this->setGtidPurged !== 'AUTO') {
            $command[] = '--set-gtid-purged='.$this->setGtidPurged;
        }

        if (! $this->dbNameWasSetAsExtraOption) {
            $command[] = $this->dbName;
        }

        if (! empty($this->includeTables)) {
            $includeTables = implode(' ', $this->includeTables);
            $command[] = "--tables {$includeTables}";
        }

        return $this->echoToFile(implode(' ', $command), $dumpFile);
    }

    public function getContentsOfCredentialsFile(): string
    {
        $contents = [
            '[client]',
            "user = '{$this->userName}'",
            "password = '{$this->password}'",
            "host = '{$this->host}'",
            "port = '{$this->port}'",
        ];

        return implode(PHP_EOL, $contents);
    }

    protected function guardAgainstIncompleteCredentials()
    {
        foreach (['userName', 'dbName', 'host'] as $requiredProperty) {
            if (strlen($this->$requiredProperty) === 0) {
                throw CannotStartDump::emptyParameter($requiredProperty);
            }
        }
    }

    protected function determineQuote(): string
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '"' : "'";
    }
}
