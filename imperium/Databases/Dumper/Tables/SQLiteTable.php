<?php
/**
 * fumseck added SQLiteTable.php to imperium
 * The 09/09/17 at 13:25
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

namespace  Imperium\Databases\Dumper\Tables{


    use Imperium\Databases\Dumper\Dumper;
    use Symfony\Component\Process\Process;

    class SQLiteTable extends Dumper
    {

        /**
         * Dump the contents of the database to a given files.
         * @param string $dumpFile
         * @param $directory
         */
        public function dumpToFile($dumpFile,$directory)
        {
            self::clear($directory);

            $command = $this->getDumpCommand($dumpFile);

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
         *
         * @return string
         */
        public function getDumpCommand($dumpFile)
        {
            return implode(' ', [
                'echo $\'BEGIN IMMEDIATE;\n.dump\' |',
                "\"{$this->dumpBinaryPath}sqlite3\" --bail",
                "\"{$this->dbName}\" '.dump {$this->table}' >",
                "\"{$dumpFile}\"",
            ]);
        }
    }
}