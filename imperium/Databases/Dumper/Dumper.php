<?php
/**
 * fumseck added Dumper.php to imperium
 * The 09/09/17 at 13:15
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

namespace  Imperium\Databases\Dumper {

    use Imperium\Databases\Dumper\Exceptions\CannotSetParameter;
    use Imperium\Databases\Dumper\Exceptions\DumpFailed;
    use Imperium\Directory\Dir;
    use Symfony\Component\Process\Process;


    /**
     * Class Dumper
     * @package Pegasus\Dump
     */
    abstract class Dumper
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

        /** @var string */
        protected $table;

        /**
         * @return static
         */
        public static function dump()
        {
            return new static();
        }

        /**
         * clear a directory
         *
         * @param $directory
         */
        public static function clear($directory)
        {
            switch (is_dir($directory))
            {
                case true:
                    Dir::clear($directory);
                break;
                default:
                    mkdir($directory);
                break;
            }
        }
        /**
         * get database name
         *
         * @return string
         */
        public function getDbName()
        {
            return $this->dbName;
        }

        /**
         * define database name
         *
         * @param string $dbName
         * @return $this
         */
        public function setDbName($dbName)
        {
            $this->dbName = $dbName;

            return $this;
        }

        /**
         * define user name
         *
         * @param string $userName
         * @return $this
         */
        public function setUserName($userName)
        {
            $this->userName = $userName;

            return $this;
        }

        /**
         * define database password
         *
         * @param string $password
         * @return $this
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * define host
         *
         * @param string $host
         * @return $this
         */
        public function setHost($host)
        {
            $this->host = $host;

            return $this;
        }

        /**
         * get host
         *
         * @return string
         */
        public function getHost()
        {
            return $this->host;
        }

        /**
         * define database port
         *
         * @param int $port
         * @return $this
         */
        public function setPort($port)
        {
            $this->port = $port;

            return $this;
        }

        /**
         * define socket
         *
         * @param string $socket
         * @return $this
         */
        public function setSocket($socket)
        {
            $this->socket = $socket;

            return $this;
        }

        /**
         * define time out
         *
         * @param int $timeout
         * @return $this
         */
        public function setTimeout($timeout)
        {
            $this->timeout = $timeout;

            return $this;
        }

        /**
         * define table name
         *
         * @param string $table
         * @return $this
         */
        public function setTable($table)
        {
            $this->table = $table;

            return $this;
        }


        /**
         * get table name
         *
         * @return string
         */
        public function getTable()
        {
            return $this->table;

        }

        /**
         * set path
         *
         * @param string $dumpBinaryPath
         * @return $this
         */
        public function setDumpBinaryPath($dumpBinaryPath)
        {
            if ($dumpBinaryPath !== '' && substr($dumpBinaryPath, -1) !== '/') {
                $dumpBinaryPath .= '/';
            }

            $this->dumpBinaryPath = $dumpBinaryPath;

            return $this;
        }

        /**
         * @param string|array $includeTables
         * @return $this
         * @throws CannotSetParameter
         */
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

        /**
         * @param string|array $excludeTables
         * @return $this
         * @throws  CannotSetParameter
         */
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

        /**
         * @param string $extraOption
         * @return $this
         */
        public function addExtraOption($extraOption)
        {
            if (! empty($extraOption)) {
                $this->extraOptions[] = $extraOption;
            }

            return $this;
        }

        /**
         * @param $dumpFile
         * @param $directory
         * @return
         */
        abstract public function dumpToFile($dumpFile,$directory);

        /**
         * @param Process $process
         * @param $outputFile
         * @throws DumpFailed
         */
        protected function checkIfDumpWasSuccessFul(Process $process, $outputFile)
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
    }
}