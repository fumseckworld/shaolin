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

        public static function show(string $driver, string $class, Table $instance, string $table, string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, int $formType, string $searchPlaceholder, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $updatePaginationPlaceholder, string $advancedRecordsText, string $simpleRecordsText, string $formPrefixAction,string $recordText,string $managementOfTableText,string $csrfToken = '', bool $framework = false, bool $preferForm = true): string
        {
            $html = '';

            if ($formType == Form::BOOTSTRAP)
            {

                $simpleRecords = ' <div class="alert alert-success text-center"> '.$recordText.' </div>  ';


                $simpleRecords .= '<div class="row">
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



                $AdvancedRecords = $simpleRecords;

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
