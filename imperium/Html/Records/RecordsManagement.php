<?php
/**
 * fumseck added TableGenerationManagement.php to imperium
 * The 28/10/17 at 11
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
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Html\Records {


    use Imperium\Databases\Eloquent\Tables\Table;
    use PDO;

    /**
     * Interface TableGenerationManagement
     *
     * @package Imperium\Html\Table
     */
    interface RecordsManagement
    {

        /**
         * @param string $driver
         * @param string $class
         * @param Table $instance
         * @param string $table
         * @param string $editPrefix
         * @param string $deletePrefix
         * @param string $orderBy
         * @param string $editText
         * @param string $deleteText
         * @param string $editClass
         * @param string $deleteClass
         * @param string $editIcon
         * @param string $deleteIcon
         * @param int $limit
         * @param int $current
         * @param string $paginationUrl
         * @param PDO $pdo
         * @param int $formType
         * @param string $searchPlaceholder
         * @param string $confirmDeleteText
         * @param string $startPaginationText
         * @param string $endPaginationText
         * @param string $updatePaginationPlaceholder
         * @param string $advancedRecordsText
         * @param string $simpleRecordsText
         * @param string $formPrefixAction
         * @param string $recordText
         * @param string $managementOfTableText
         * @param string $tableUrlPrefix
         * @param string $csrfToken
         * @param bool $preferPaginationRight
         * @param bool $framework
         * @param bool $preferForm
         * @param int $textareaCols
         * @param int $textareaRow
         *
         * @return string
         *
         * @throws \Exception
         */
        public static function show(string $driver, string $class, Table $instance, string $table, string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, int $formType, string $searchPlaceholder, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $updatePaginationPlaceholder, string $advancedRecordsText, string $simpleRecordsText, string $formPrefixAction,string $recordText,string $managementOfTableText,string $tableUrlPrefix,string $csrfToken = '', bool $preferPaginationRight = true, bool $framework = false, bool $preferForm = true,int $textareaCols = 25,int $textareaRow = 1): string;

    }
}