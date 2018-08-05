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
    use Imperium\Html\Form\Form;
    use PDO;

    class Records implements RecordsManagement
    {

        public static function show(string $driver, string $class, Table $instance, string $table, string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, int $formType, string $searchPlaceholder, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $updatePaginationPlaceholder, string $advancedRecordsText, string $simpleRecordsText, string $formPrefixAction,string $recordText,string $managementOfTableText,string $csrfToken = '', bool $preferPaginationRight = true, bool $framework = false, bool $preferForm = true): string
        {

            if ($framework)
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}};function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "/search/" + elem.value;}</script>';
            else
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "?search=" + elem.value;}</script>';


            $total = $instance->count($table);

            $instance = $instance->setName($table);
            $offset = ($limit * $current) - $limit;
            $key = $instance->primaryKey();


            if(is_null($key))
                throw new Exception('We have not found a primary key');


            $columns = $instance->getColumns();


            if ($framework)
            {
                $parts = explode('/',server('REQUEST_URI'));
                $search = has('search',$parts);
                if ($search)
                    $like = end($parts);
                else
                    $like = '';

                if (empty($like))
                    $records = sql($table)->setPdo($pdo)->limit($limit, $offset)->orderBy($key,$orderBy)->getRecords();
                else
                    $records = sql($table)->setDriver($driver)->setPdo($pdo)->like($instance, $like)->orderBy($key,$orderBy)->getRecords();
            } else {
                $like = get('search');
                if (empty($like))
                    $records = sql($table)->setPdo($pdo)->limit($limit,$offset)->orderBy($key,$orderBy)->getRecords();
                else
                    $records = sql($table)->setDriver($driver)->setPdo($pdo)->like($instance,$like)->orderBy($key,$orderBy)->getRecords();
            }



            if ($formType == Form::BOOTSTRAP)
            {

                $pagination = pagination( $limit,$paginationUrl,$current,$total,$startPaginationText,$endPaginationText);
                $simpleRecords = ' <div class="alert mt-4 alert-success text-center"> '.$recordText.' </div>  ';


                $simpleRecords .= '<div class="row mt-4">
                                       <div class="col">  
                                            <div class="input-group">
                                                
                                                <input type="search" class="form-control form-control-lg" id="search-records" placeholder="'.$searchPlaceholder.'" autofocus="autofocus">
                                            </div>
                                         </div>  
                                          <div class="col">  
                                            <div class="input-group">
                                                <input type="number" min="1" class="form-control form-control-lg"   placeholder="'.$updatePaginationPlaceholder.'" value="'.$limit.'">
                                            </div>
                                         </div>
                                     </div>';

                $simpleRecords .= '<div class="table-responsive mt-4"><table class="'.$class.'"><thead><tr><th>'.$editText.'</th><th>'.$deleteText.'</th>';

                foreach ($columns as $column)
                    $simpleRecords .=  "<th> ". $column."</th>";

                $simpleRecords .= '</tr></thead><tbody>';


                $AdvancedRecords = $simpleRecords;


                $AdvancedRecords .= '</tr></tbody></table></div>';

                if ($preferPaginationRight)
                    $AdvancedRecords .=    '<div class="float-right mt-4 mb-5">'.$pagination.'</div>';
                else
                    $AdvancedRecords .=    '<div class="float-left mt-4 mb-5">'.$pagination.'</div>';
                foreach ($records as $record)
                {
                    $i = $record->$key;
                    $simpleRecords .= '<tr>';

                    $simpleRecords .= '<td> <a href="'.$editPrefix.'/'.$i.'" class="'.$editClass.'" id="edit-'.$i.'"> '.$editIcon.'</a></td>';
                    $simpleRecords .= '<td> <a href="'.$deletePrefix.'/'.$i.'" class="'.$deleteClass.'"  id="delete-'.$i.'" onclick="sure(event,this.attributes[4].value)" data-text="'.$confirmDeleteText.'" >'.$deleteIcon.' </a></td>';
                    foreach ($record as $value)
                    {

                        $simpleRecords .= '<td> '.htmlentities(substr($value,0,40)).' </td>';
                    }
                }
                $simpleRecords .= '</tr></tbody></table></div>';

                if ($preferPaginationRight)
                    $simpleRecords .=    '<div class="float-right mt-4 mb-5">'.$pagination.'</div>';
                else
                    $simpleRecords .=    '<div class="float-left mt-4 mb-5">'.$pagination.'</div>';
            ;









                if ($preferForm)
                {
                    $html .=    '<ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="'.$advancedRecordsText.'-tab" data-toggle="tab" href="#'.$advancedRecordsText.'" role="tab" aria-controls="'.$advancedRecordsText.'" aria-selected="true">'.$advancedRecordsText.'</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="'.$simpleRecordsText.'-tab" data-toggle="tab" href="#'.$simpleRecordsText.'" role="tab" aria-controls="'.$simpleRecordsText.'" aria-selected="false">'.$simpleRecordsText.'</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="'.$managementOfTableText.'-tab" data-toggle="tab" href="#'.$managementOfTableText.'" role="tab" aria-controls="'.$managementOfTableText.'" aria-selected="false">'.$managementOfTableText.'</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="'.$advancedRecordsText.'" role="tabpanel" aria-labelledby="'.$advancedRecordsText.'-tab">'.$AdvancedRecords .'</div>
                          <div class="tab-pane fade" id="'.$simpleRecordsText.'" role="tabpanel" aria-labelledby="'.$simpleRecordsText.'-tab">'.$simpleRecords.'</div>
                          <div class="tab-pane fade" id="'.$managementOfTableText.'" role="tabpanel" aria-labelledby="'.$managementOfTableText.'-tab">management</div>
                        </div>';
                }else{
                    $html .=    '<ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="'.$simpleRecordsText.'-tab" data-toggle="tab" href="#'.$simpleRecordsText.'" role="tab" aria-controls="'.$simpleRecordsText.'" aria-selected="true">'.$simpleRecordsText.'</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="'.$advancedRecordsText.'-tab" data-toggle="tab" href="#'.$advancedRecordsText.'" role="tab" aria-controls="'.$advancedRecordsText.'" aria-selected="false">'.$advancedRecordsText.'</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="'.$managementOfTableText.'-tab" data-toggle="tab" href="#'.$managementOfTableText.'" role="tab" aria-controls="'.$managementOfTableText.'" aria-selected="false">'.$managementOfTableText.'</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="'.$simpleRecordsText.'" role="tabpanel" aria-labelledby="'.$simpleRecordsText.'-tab">'.$simpleRecords.'</div>
                          <div class="tab-pane fade" id="'.$advancedRecordsText.'" role="tabpanel" aria-labelledby="'.$advancedRecordsText.'-tab">'.$AdvancedRecords .'</div>
                          <div class="tab-pane fade" id="'.$managementOfTableText.'" role="tabpanel" aria-labelledby="'.$managementOfTableText.'-tab">management</div>
                        </div>';
                }


            }


            return $html;
        }
    }
}
