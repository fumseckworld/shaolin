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

        /**
         * @param string $driver
         * @param string $class
         * @param \Imperium\Databases\Eloquent\Tables\Table $instance
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
         * @param \PDO $pdo
         * @param int $formType
         * @param string $searchPlaceholder
         * @param string $confirmDeleteText
         * @param string $startPaginationText
         * @param string $endPaginationText
         * @param string $updatePaginationPlaceholder
         * @param bool $framework
         *
         * @return string
         * @throws Exception
         */
        public static function show(string $driver,string $class, Table $instance,string $table,string $editPrefix, string $deletePrefix,string $orderBy,string $editText,string $deleteText,string $editClass,string $deleteClass,string $editIcon,string $deleteIcon,int $limit,int $current,string $paginationUrl,PDO $pdo,int $formType,string $searchPlaceholder,string $confirmDeleteText,string $startPaginationText,string $endPaginationText,string $updatePaginationPlaceholder,bool $framework = false): string
        {

            $total = $instance->count($table);

            if ($formType == Form::BOOTSTRAP)
                $pagination = pagination( $limit,$paginationUrl,$current,$total,$startPaginationText,$endPaginationText);
            else
                $pagination = pagination($limit,$paginationUrl,$current,$total,$startPaginationText,$endPaginationText,'','','',$formType);
            $instance = $instance->setName($table);
            $offset = ($limit * $current) - $limit;
            $key = $instance->primaryKey();


            if(is_null($key))
            {
                throw new Exception('We have not found a primary key');
            }

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
                $html =  '<div class="row"> <div class="col-lg-6 col-md-6 form-group col-sm-12"><input data-url="'.$paginationUrl.'" class="form-control"  placeholder="'.$searchPlaceholder.'" onchange="search(this)"   value="'.$like.'" autofocus="autofocus" type="text"></div>  <div class="col-lg-6 form-group col-md-6 col-sm-12"><input type="number" onchange="location = this.attributes[3].value + this.value " value="'.$limit.'" data-url="/" placeholder="'.$updatePaginationPlaceholder.'"  class="form-control"></div></div>';
            else
                $html =  '<div class="row"> <div class="large-6 medium-6 small-12 columns"><input data-url="'.$paginationUrl.'"   placeholder="'.$searchPlaceholder.'" onchange="search(this)"   value="'.$like.'" autofocus="autofocus" type="text"></div>  <div class="large-6 medium-6 small-12 columns"><input type="number" onchange="location = this.attributes[3].value + this.value " value="'.$limit.'" data-url="/" placeholder="'.$updatePaginationPlaceholder.'"></div></div>';

            if ($framework)
                $html .= '<script>function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "/search/" + elem.value;}</script>';
            else
                $html .= '<script>function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "?search=" + elem.value;}</script>';
            if ($formType == Form::BOOTSTRAP)
                $html .= '<div class="table-responsive"><table class="'.$class.'"><thead><tr>';
            else
                $html .= '<div class="table-scroll"><table class="'.$class.'"><thead><tr>';

            $html .= "<th>$editText</th><th>$deleteText</th>";

            foreach ($columns as $column)
                $html .=  "<th> ".strtoupper($column)."</th>";

            $html .= '</tr></thead><tbody>';

            $html .= '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}</script>';



            foreach ($records as $record)
            {
                 $i = $record->$key;
                 $html .= '<tr>';

                 $html .= '<td> <a href="'.$editPrefix.'/'.$i.'" class="'.$editClass.'" id="edit-'.$i.'"> '.$editIcon.'</a></td>';
                 $html .= '<td> <a href="'.$deletePrefix.'/'.$i.'" class="'.$deleteClass.'"  id="delete-'.$i.'" onclick="sure(event,this.attributes[4].value)" data-text="'.$confirmDeleteText.'" >'.$deleteIcon.' </a></td>';
                 foreach ($record as $value)
                 {

                    $html .= '<td> '.htmlentities(substr($value,0,40)).' </td>';
                 }
            }
            $html .= '</tr></tbody></table></div>';

            $html .= '<p><div class="float-right">'.$pagination.'</div></p>';



            return $html;
         }
    }
}
