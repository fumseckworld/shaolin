<?php
/**
 * fumseck added MySQLTable.php to imperium
 * The 09/09/17 at 13:23
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

    class MySQLTable extends Dumper
    {
        /** @var bool */
        protected $skipComments = true;

        /** @var bool */
        protected $useExtendedInserts = true;

        /** @var bool */
        protected $useSingleTransaction = false;

        public function __construct()
        {
            $this->port = 3306;
        }

        /**
         * @return $this
         */
        public function skipComments()
        {
            $this->skipComments = true;

            return $this;
        }

        /**
         * @return $this
         */
        public function dontSkipComments()
        {
            $this->skipComments = false;

            return $this;
        }

        /**
         * @return $this
         */
        public function useExtendedInserts()
        {
            $this->useExtendedInserts = true;

            return $this;
        }

        /**
         * @return $this
         */
        public function dontUseExtendedInserts()
        {
            $this->useExtendedInserts = false;

            return $this;
        }

        /**
         * @return $this
         */
        public function useSingleTransaction()
        {
            $this->useSingleTransaction = true;

            return $this;
        }

        /**
         * @return $this
         */
        public function dontUseSingleTransaction()
        {
            $this->useSingleTransaction = false;

            return $this;
        }

        /**
         * Dump the contents of the database to the given files.
         *
         * @param string $dumpFile
         * @param $directory
         */
        public function dumpToFile($dumpFile,$directory)
        {
            self::clear($directory);

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

        /**
         * Get the command that should be performed to dump the database.
         *
         * @param string $dumpFile
         * @param string $temporaryCredentialsFile
         *
         * @return string
         */
        public function getDumpCommand($dumpFile, $temporaryCredentialsFile)
        {
            $quote = $this->determineQuote();

            $command = [
                "{$quote}{$this->dumpBinaryPath}mysqldump{$quote}",
                "--defaults-extra-files=\"{$temporaryCredentialsFile}\"",
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

            foreach ($this->extraOptions as $extraOption) {
                $command[] = $extraOption;
            }

            $command[] = "{$this->dbName}";
            $command[] = "{$this->table}";

            $command[] = "> \"{$dumpFile}\"";

            return implode(' ', $command);
        }

        public function getContentsOfCredentialsFile()
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

        protected function determineQuote()
        {
            return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '"' : "'";
        }
    }
}