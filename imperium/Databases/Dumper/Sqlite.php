<?php


namespace Imperium\Databases\Dumper;

use Symfony\Component\Process\Process;

class Sqlite extends DbDumper
{

    public function dumpToFile(string $dumpFile)
    {
        $command = $this->getDumpCommand($dumpFile);

        $process = new Process($command);

        if (! is_null($this->timeout)) {
            $process->setTimeout($this->timeout);
        }

        $process->run();

        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
    }


    public function getDumpCommand(string $dumpFile): string
    {
        $command = sprintf(
            "sqlite3 %s .dump ",
            $this->dbName
        );

        return $this->echoToFile($command, $dumpFile);
    }
}
