<?php
/**
 * fumseck added PostgreSQLTable.php to imperium
 * The 09/09/17 at 13:24
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */



namespace  Imperium\Databases\Dumper\Tables {


    use Imperium\Databases\Dumper\Dumper;
    use Imperium\Databases\Dumper\Exceptions\CannotStartDump;
    use Symfony\Component\Process\Process;

    class PostgreSQLTable extends Dumper
    {
        /** @var bool */
        protected $useInserts = false;

        public function __construct()
        {
            $this->port = 5432;
        }

        /**
         * @return $this
         */
        public function useInserts()
        {
            $this->useInserts = true;

            return $this;
        }

        /**
         * Dump the contents of the database to the given files.
         * @param string $dumpFile
         * @param $directory
         */
        public function dumpToFile($dumpFile,$directory)
        {
            self::clear($directory);

            $this->guardAgainstIncompleteCredentials();

            $command = $this->getDumpCommand($dumpFile);

            $tempFileHandle = tmpfile();
            fwrite($tempFileHandle, $this->getContentsOfCredentialsFile());
            $temporaryCredentialsFile = stream_get_meta_data($tempFileHandle)['uri'];

            $process = new Process($command, null, $this->getEnvironmentVariablesForDumpCommand($temporaryCredentialsFile));

            if (! is_null($this->timeout)) {
                $process->setTimeout($this->timeout);
            }

            $process->run();

            $this->checkIfDumpWasSuccessFul($process, $dumpFile);
        }

        /**
         * Get the command that should be performed to dump the database.
         *
         * @param string $dumpFile
         *
         * @return string
         */
        public function getDumpCommand($dumpFile)
        {
            $command = [
                "'{$this->dumpBinaryPath}pg_dump'",
                "-U {$this->userName}",
                "-d {$this->dbName}",
                "-t  {$this->table}",
                "-h {$this->host}",
                "-p {$this->port}",
                "--files=\"{$dumpFile}\"",
            ];


            foreach ($this->extraOptions as $extraOption) {
                $command[] = $extraOption;
            }

            return implode(' ', $command);
        }

        public function getContentsOfCredentialsFile()
        {
            $contents = [
                $this->host,
                $this->port,
                $this->dbName,
                $this->userName,
                $this->password,
            ];

            return implode(':', $contents);
        }

        protected function guardAgainstIncompleteCredentials()
        {
            foreach (['userName', 'dbName', 'host'] as $requiredProperty) {
                if (empty($this->$requiredProperty)) {
                    throw CannotStartDump::emptyParameter($requiredProperty);
                }
            }
        }

        protected function getEnvironmentVariablesForDumpCommand(  $temporaryCredentialsFile)
        {
            return [
                'PGPASSFILE' => $temporaryCredentialsFile,
                'PGDATABASE' => $this->dbName,
            ];
        }
    }
}