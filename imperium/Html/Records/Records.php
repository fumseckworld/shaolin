<?php
/**
 * fumseck added HtmlTable.php to imperium
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

    use Exception;
    use Imperium\Databases\Eloquent\Tables\Table;
    use PDO;

    class Records implements RecordsManagement
    {
        /**
         * @param string $driver
         * @param string $class
         * @param Table $instance
         * @param string $table
         * @param $tableIcon
         * @param string $changeOfTableText
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
         * @param string $saveText
         * @param string $confirmDeleteText
         * @param string $startPaginationText
         * @param string $endPaginationText
         * @param string $advancedRecordsText
         * @param string $simpleRecordsText
         * @param string $formPrefixAction
         * @param string $managementOfTableText
         * @param string $tableUrlPrefix
         * @param bool $columnNameAlignCenter
         * @param bool $columnNameToUpper
         * @param string $csrfToken
         * @param bool $preferPaginationRight
         * @param bool $framework
         * @param bool $preferForm
         * @param string $separator
         * @param int $textareaRow
         *
         * @return string
         *
         * @throws Exception
         */
        public static function show(string $driver, string $class, Table $instance, string $table,$tableIcon, string $changeOfTableText,string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, int $formType, string $saveText, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $advancedRecordsText, string $simpleRecordsText, string $formPrefixAction,string $managementOfTableText,string $tableUrlPrefix,bool $columnNameAlignCenter, bool $columnNameToUpper,string $csrfToken = '', bool $preferPaginationRight = true, bool $framework = false, bool $preferForm = true,  string $separator = '/',int $textareaRow = 1): string
        {

            if ($framework)
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "/search/" + elem.value;}</script>';
            else
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "?search=" + elem.value;}</script>';

            $records = getRecords($instance,$table,$current,$limit,$pdo,$framework,$orderBy);

            $selectTable = selectTable($instance,$tableUrlPrefix,$table,$changeOfTableText,$formType,$limit,$csrfToken,$separator,$tableIcon);

            $pagination =   pagination( $limit,$paginationUrl,$current,$instance->count($table),$startPaginationText,$endPaginationText);

            if ($preferForm)
            {
                $html .= '<ul class="nav nav-tabs mt-5" role="tablist"><li class="nav-item"><a class="nav-link active" id="'.$advancedRecordsText.'-tab" data-toggle="tab" href="#'.$advancedRecordsText.'" role="tab" aria-controls="'.$advancedRecordsText.'" aria-selected="true">'.$advancedRecordsText.'</a></li><li class="nav-item"><a class="nav-link" id="'.$simpleRecordsText.'-tab" data-toggle="tab" href="#'.$simpleRecordsText.'" role="tab" aria-controls="'.$simpleRecordsText.'" aria-selected="false">'.$simpleRecordsText.'</a></li><li class="nav-item"><a class="nav-link" id="'.$table.'-tab" data-toggle="tab" href="#'.$table.'" role="tab" aria-controls="'.$table.'" aria-selected="false">'.$table.'</a></li></ul>';

                $html .= '<div class="tab-content"><div class="tab-pane fade show active" id="'.$advancedRecordsText.'" role="tabpanel" aria-labelledby="'.$advancedRecordsText.'-tab">'.   advancedView($table,$instance,$records,$formPrefixAction,$selectTable,$saveText,$editText,$editClass,$deletePrefix,$deleteClass,$deleteText,$confirmDeleteText,$pagination,$columnNameAlignCenter,$columnNameToUpper,$preferPaginationRight,$csrfToken,$formType,$textareaRow)       .' </div>  <div class="tab-pane fade" id="'.$simpleRecordsText.'" role="tabpanel" aria-labelledby="'.$simpleRecordsText.'-tab">'. simpleView($table,$instance,$records,$selectTable,$class,$deleteText,$confirmDeleteText,$deleteClass,$deletePrefix,$deleteIcon,$editText,$editPrefix,$editClass,$editIcon,$pagination,$columnNameAlignCenter,$columnNameToUpper,$preferPaginationRight,$formType).'</div><div class="tab-pane fade" id="'.$table.'" role="tabpanel" aria-labelledby="'.$table.'-tab">'. alterTableView($table,$instance,$formType).'</div></div>';

            }
            else
            {
                $html .= '<ul class="nav nav-tabs mt-5" role="tablist"><li class="nav-item"><a class="nav-link active" id="'.$simpleRecordsText.'-tab" data-toggle="tab" href="#'.$simpleRecordsText.'" role="tab" aria-controls="'.$simpleRecordsText.'" aria-selected="true">'.$simpleRecordsText.'</a></li><li class="nav-item"><a class="nav-link" id="'.$advancedRecordsText.'-tab" data-toggle="tab" href="#'.$advancedRecordsText.'" role="tab" aria-controls="'.$advancedRecordsText.'" aria-selected="false">'.$advancedRecordsText.'</a></li><li class="nav-item"><a class="nav-link" id="'.$table.'-tab" data-toggle="tab" href="#'.$table.'" role="tab" aria-controls="'.$table.'" aria-selected="false">'.$table.'</a></li></ul>';

                $html .= '<div class="tab-content"><div class="tab-pane fade show active" id="'.$simpleRecordsText.'" role="tabpanel" aria-labelledby="'.$simpleRecordsText.'-tab">'. simpleView($table,$instance,$records,$selectTable,$class,$deleteText,$confirmDeleteText,$deleteClass,$deletePrefix,$deleteIcon,$editText,$editPrefix,$editClass,$editIcon,$pagination,$columnNameAlignCenter,$columnNameToUpper,$preferPaginationRight,$formType).' </div>  <div class="tab-pane fade" id="'.$advancedRecordsText.'" role="tabpanel" aria-labelledby="'.$advancedRecordsText.'-tab">'. advancedView($table,$instance,$records,$formPrefixAction,$selectTable,$saveText,$editText,$editClass,$deletePrefix,$deleteClass,$deleteText,$confirmDeleteText,$pagination,$columnNameAlignCenter,$columnNameToUpper,$preferPaginationRight,$csrfToken,$formType,$textareaRow).'</div><div class="tab-pane fade" id="'.$table.'" role="tabpanel" aria-labelledby="'.$table.'-tab">'.alterTableView($table,$instance,$formType).'</div></div>';
            }

            return $html;
        }
    }
}
