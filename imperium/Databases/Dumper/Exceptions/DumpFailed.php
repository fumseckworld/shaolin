<?php
/**
 * fumseck added DumpFailed.php to imperium
 * The 09/09/17 at 13:21
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


namespace  Imperium\Databases\Dumper\Exceptions {

    use Exception;
    use Symfony\Component\Process\Process;

    class DumpFailed extends Exception
    {
        /**
         * @param \Symfony\Component\Process\Process $process
         *
         * @return DumpFailed
         */
        public static function processDidNotEndSuccessfully(Process $process)
        {
            return new static("The dump process failed with exit code {$process->getExitCode()} : {$process->getExitCodeText()} : {$process->getErrorOutput()}");
        }

        /**
         * @return DumpFailed
         */
        public static function dumpfileWasNotCreated()
        {
            return new static('The dumpfile could not be created');
        }

        /**
         * @return DumpFailed
         */
        public static function dumpfileWasEmpty()
        {
            return new static('The created dumpfile is empty');
        }

    }
}