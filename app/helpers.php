<?php
/**
 * fumseck added helpers.php to imperium
 * The 09/09/17 at 15:11
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

use Carbon\Carbon;
use Cz\Git\GitRepository;
use Imperium\Core\Collection;
use Imperium\Databases\Dumper\MySql;
use Imperium\Databases\Dumper\PostgreSql;
use Imperium\Databases\Dumper\Sqlite;
use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\Directory\Dir;
use Imperium\File\File;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Form\Form;
use Imperium\Html\Pagination\Pagination;
use Imperium\Html\Records\Records;
use Imperium\Model\Model;
use Intervention\Image\ImageManager;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


if (!exist('registerForm'))
{
    /**
     * build a register form conform for laravel
     *
     * @param string $action
     * @param string $usernamePlaceholder
     * @param string $emailPlaceholder
     * @param string $passwordPlaceholder
     * @param string $passwordConfirmPlaceholder
     * @param string $submitText
     * @param string $submitId
     * @param bool $multiLang
     * @param array $supportedLang
     * @param string $chooseTimeZoneText
     * @param string $csrfToken
     * @param string $submitClass
     * @param string $passwordIcon
     * @param string $usernameIcon
     * @param string $emailIcon
     * @param string $submitIcon
     * @param string $zonesIcon
     * @param string $langIcon
     *
     * @return string
     *
     * @throws Exception
     */
    function registerForm(string $action,string $usernamePlaceholder,string $emailPlaceholder,string $passwordPlaceholder,string $passwordConfirmPlaceholder,string $submitText,string $submitId,bool $multiLang= false,array $supportedLang =[],string $chooseTimeZoneText ='',string $csrfToken = '',string $submitClass = 'btn btn-outline-primary',string $passwordIcon = '<i class="fas fa-key"></i>',string $usernameIcon = '<i class="fas fa-user"></i>',string $emailIcon = '<i class="fas fa-envelope"></i>',string $submitIcon = '<i class="fas fa-user-plus"></i>',string $zonesIcon = '<i class="fas fa-clock"></i>',string $langIcon = '<i class="fas fa-globe"></i>')
    {
        $form = form($action,'register-form')->csrf($csrfToken);

        if ($multiLang)
            $form->startRow()->select('locale',$supportedLang,$langIcon)->select('zone',zones($chooseTimeZoneText),$zonesIcon)->endRowAndNew();

        $form->input(Form::TEXT,'name',$usernamePlaceholder,post('name'),$usernameIcon,true)->input(Form::EMAIL,'email',$emailPlaceholder,post('email'),$emailIcon,true)->endRowAndNew()
        ->input(Form::PASSWORD,'password',$passwordPlaceholder,post('password'),$passwordIcon,true)->input(Form::PASSWORD,'password_confirmation',$passwordConfirmPlaceholder,post('password_confirmation'),$passwordIcon,true)->endRowAndNew()
        ->submit($submitText,$submitClass,$submitId,$submitIcon)->endRow();

        return $form->get();
    }
}

if(!exist('def'))
{
    /**
     * check if value are defined
     *
     * @param mixed ...$values
     *
     * @return bool
     */
    function def(...$values): bool
    {
        foreach ($values as $value)
        {
            if(!isset($value) || empty($value))
                return false;
        }


        return true;
    }
}

if (!exist('zones'))
{
    /**
     * @param string $text
     * @return array
     */
    function zones(string $text) : array
    {
        $zones = array("/" => $text);

        foreach (DateTimeZone::listIdentifiers() as $x)
            $zones = merge($zones,[$x=> $x ]);

        return $zones;
    }
}


if (!exist('records'))
{
    /**
     *
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
    function records(string $driver, string $class, Table $instance, string $table,$tableIcon, string $changeOfTableText,string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, string $saveText, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $advancedRecordsText, string $simpleRecordsText, string $formPrefixAction,string $managementOfTableText,string $tableUrlPrefix,bool $columnNameAlignCenter, bool $columnNameToUpper,string $csrfToken = '', bool $preferPaginationRight = true, bool $framework = false, bool $preferForm = true,  string $separator = '/',int $textareaRow = 1): string
    {
       return Records::show( $driver, $class,$instance, $table,$tableIcon, $changeOfTableText,$editPrefix,  $deletePrefix,   $orderBy,   $editText,   $deleteText,   $editClass,   $deleteClass,   $editIcon,   $deleteIcon,   $limit,   $current,   $paginationUrl,   $pdo,   $saveText,   $confirmDeleteText,   $startPaginationText,   $endPaginationText,   $advancedRecordsText,   $simpleRecordsText,   $formPrefixAction,  $managementOfTableText,  $tableUrlPrefix,  $columnNameAlignCenter,   $columnNameToUpper,  $csrfToken  ,   $preferPaginationRight ,   $framework,   $preferForm,    $separator , $textareaRow );
    }
}


if (!exist('selectTable'))
{
    /**
     * generate a form to checkout on a new table table
     *
     * @param Table $instance
     * @param string $urlPrefix
     * @param string $currentTable
     * @param string $changeOfTableText
     * @param int $paginationLimit
     * @param string $csrf
     * @param string $separator
     * @param string $icon
     *
     * @return string
     */
    function selectTable(Table $instance,string $urlPrefix,string $currentTable,string $changeOfTableText,int $paginationLimit,string $csrf = '',string $separator = '/',string $icon = '<i class="fas fa-table"></i>'): string
    {
        $tables =  [ '#' =>  $changeOfTableText ];

        foreach ($instance->show() as $x)
        {
            if ($x != $currentTable)
            {
                $tables = merge($tables,["$urlPrefix$separator$x" => $x]);
            }
        }

        return '<div class="row mt-5"> <div class="col">'. form('',uniqid())->csrf($csrf)->redirectSelect('table',$tables,$icon)->end().'</div> <div class="col">  <div class="form-group"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' . $icon . '</div></div><input type="number" class="form-control" min="1" value="'.$paginationLimit.'" onchange="location = this.value"></div></div> </div></div>';
     }
}

if (!exist('simpleView'))
{
    /**
     * generate an simply view to manage records
     *
     * @param string $table
     * @param Table $instance
     * @param array $records
     * @param string $selectTable
     * @param string $tableClass
     * @param string $removeText
     * @param string $removeConfirm
     * @param string $removeBtnClass
     * @param string $removeUrl
     * @param string $removeIcon
     * @param string $editText
     * @param string $editUrl
     * @param string $editClass
     * @param string $editIcon
     * @param string $pagination
     * @param bool $columnAlignCenter
     * @param bool $columnToUpper
     * @param bool $preferPaginationRight
     *
     * @return string
     *
     * @throws Exception
     */
    function simpleView(string $table, Table $instance , array $records , string $selectTable,string $tableClass,string $removeText,string $removeConfirm,string $removeBtnClass,string $removeUrl,string $removeIcon,string $editText,string $editUrl,string $editClass,string $editIcon,string $pagination,bool $columnAlignCenter,bool $columnToUpper,bool $preferPaginationRight = true): string
    {
        $instance = $instance->setName($table);

        $columns  = $instance->getColumns();
        $primary  = $instance->primaryKey();

        if(is_null($primary))
            throw new Exception('We have not found a primary key');


        $code = $selectTable.'<div class="table-responsive mt-4"><table class="'.$tableClass.'"><thead><tr>';
        $code .= '<script>function sure(e,text){ if (! confirm(text)) {e.preventDefault()} }</script>';

        foreach ($columns as  $x)
        {
            if ($x != $primary)
            {
                $code.= '<th  class="';
                if ($columnAlignCenter) {  $code .= ' text-center'; }

                if ($columnToUpper) {  $code .= ' text-uppercase'; }

                $code .= '">'.$x.'</th>';
            }
        }
        $code .= '<th  class="';

        if ($columnAlignCenter) {  $code.= ' text-center'; }

        if ($columnToUpper) {  $code.= ' text-uppercase'; }

        $code.= '">'.$editText.'</th>';

        $code .= '<th  class="';

        if ($columnAlignCenter) {  $code .= ' text-center'; }

        if ($columnToUpper) { $code .= ' text-uppercase'; }

        $code.= '">'.$removeText.'</th></tr></thead><tbody>';

        foreach ($records as $record)
        {
            $code .= '<tr>';

            foreach ($columns as $k => $column)
            {

                if (is_null($record->$column))
                    $record->$column = '';

                if($column != $primary)
                {
                    $code .= '<td> '.$record->$column.'</td>';
                }
            }
            $code .= '<td> <a href="'.$editUrl.'/'.$record->$primary.'" class="'.$editClass.'">'.$editIcon.'</a></td><td> <a href="'.$removeUrl.'/'.$record->$primary.'" class="'.$removeBtnClass.'" data-confirm="'.$removeConfirm.'" onclick="sure(event,this.attributes[2].value)">'.$removeIcon.' </a></td></tr>';
        }

        $code.= '</tbody></table></div>';

        if ($preferPaginationRight)
            $code .=    '<div class="ml-auto mt-5 mb-5">'.$pagination.'</div>';
        else
            $code .=     '<div class="mr-auto mt-5 mb-5">'.$pagination.'</div>';

        return $code;

    }
}



if (!exist('alterTable'))
{
    /**
    * generate alter table view
    *
    * @param string $table
    * @param Table $instance
    *
    * @return string
    */
    function alterTableView(string $table, Table $instance): string
    {
        return '';
    }

}
if (!exist('advancedView'))
{


    /**
     * generate an advanced view to manage records
     *
     * @param string $table
     * @param Table $instance
     * @param array $records
     * @param string $action
     * @param string $selectTable
     * @param string $saveText
     * @param string $editText
     * @param string $btnEditClass
     * @param string $removeUrl
     * @param string $removeClassBtn
     * @param string $removeText
     * @param string $confirmRemoveText
     * @param string $pagination
     * @param bool $columnAlignCenter
     * @param bool $columnToUpper
     * @param bool $paginationPreferRight
     * @param string $csrf
     * @param int $textareaRow
     *
     * @return string
     *
     * @throws Exception
     */
    function advancedView(string $table, Table $instance,array $records,string $action,string $selectTable,string $saveText,string $editText,string $btnEditClass,string $removeUrl,string $removeClassBtn,string $removeText,string $confirmRemoveText,string $pagination,bool $columnAlignCenter,bool $columnToUpper,bool $paginationPreferRight,string $csrf ='',int  $textareaRow =  1): string
    {

        $instance = $instance->setName($table);
        $types    = $instance->getColumnsTypes();
        $columns  = $instance->getColumns();
        $primary  = $instance->primaryKey();

        if(is_null($primary))
            throw new Exception('We have not found a primary key');

        $number = array(
            'smallint',
            'integer',
            'bigint',
            'decimal',
            'numeric',
            'real',
            'double',
            'double precision',
            'smallserial',
            'serial',
            'integer',
            'int',
            'bigserial',
            'smallint',
            'float',
        );

        $date = array(
            'date',
            'datetime',
            'timestamp',
            'time',
            'interval',
            'real',
            'float4',
            'timestamp without time zone'
        );



        $code = $selectTable;
        $code.= '<script type="text/javascript">function sure(e,text){ if (!confirm(text)) {e.preventDefault()} }function edit(element)  { const btn = $(element);  const tr = btn.parent().parent();   const id = btn.attr("data-form-id"); if (btn.text() !== btn.attr("data-edit")){  $("#"+id).submit(); }  if (btn.text() === btn.attr("data-edit")){ btn.text(btn.attr("data-save"))}else{btn.text(btn.attr("data-edit"))} tr.find("DIV.td span").each(function(){  $(this).toggleClass("d-none"); });  tr.keypress(function(e) { if(e.which === 13) { $("#"+id).submit();  } }); }</script>';

        $code .= ' <div class="table-responsive"><div class="table"><div class="thead"><div class="tr">';

        foreach ($columns as $column)
        {
            if ($column != $primary)
            {
                $code .= ' <div class="td ';
                if ($columnToUpper)
                    $code .= ' text-uppercase';

                if ($columnAlignCenter)
                    $code.= ' text-center';

                $code.= '">'.$column.'</div>';
            }
        }

        $code.= '<div class="td">'.$editText.'</div><div class="td">'.$removeText.'</div></div></div> <div class="tbody">';


        foreach ($records as $record)
        {
            $id = uniqid().sha1($table.md5($record->$primary));

            $code.= '<form class="tr" id="'.$id.'" method="post" action="'.$action.'">'.$csrf.'';

            foreach ($columns as $k => $column)
            {
                $type = $types[$k];

                if (is_null($record->$column))
                    $record->$column = '';

                if($column != $primary)
                {
                    $type = explode('(',$type);
                    $type = $type[0];

                    switch ($type)
                    {
                        case has($type,$number):
                            $code .= '<div class="td"><span class="d-none td-input"><input type="number" name="'.$column.'" class="form-control form-control-lg" value="'.$record->$column.'"></span> <span class="record"> '.$record->$column.'</span></div>';
                        break;
                        case has($type,$date):
                            $code .= '<div class="td"><span class="d-none td-input"><input type="datetime" name="'.$column.'" class="form-control form-control-lg" value="'.$record->$column.'"></span> <span class="record"> '.$record->$column.'</span></div>';
                        break;
                        default:
                            $code.= '<div class="td"><span class="d-none td-input"><textarea name="'.$column.'" name="'.$column.'"  class="form-control form-control-lg"  rows="'.$textareaRow.'">'.$record->$column.'</textarea></span> <span class="record"> '.$record->$column.'</span></div>';
                        break;
                    }
                } else {
                    $code.= '  <div class="td d-none"><input name="'.$primary.'"  value="'.$record->$primary.'"></div>';
                    $code .= '  <div class="td d-none"><input name="table"  value="'.$table.'"></div>';
                }
            }

            $code .= '<div class="td action btn-group"><button type="button" onclick="edit(this);" class="'.$btnEditClass.'" data-form-id="'.$id.'" data-edit="'.$editText.'" data-save="'.$saveText.'" >'.$editText.'</button> </div><div class="td  remove btn-group"><a href="'.$removeUrl.'/'.  $record->$primary.'" onclick="sure(event,this.attributes[2].value)"  data-confirm="'.$confirmRemoveText.'" class="'.$removeClassBtn.'" data-form-id="'.$id.'">'.$removeText.'</a> </div></form>';
        }


        $code.= '</div></div></div>';

        if ($paginationPreferRight)
            $code .=    '<div class="ml-auto mt-5 mb-5">'.$pagination.'</div>';
        else
            $code .=     '<div class="mr-auto mt-5 mb-5">'.$pagination.'</div>';

        return $code;
    }
}
if (!exist('getRecords'))
{
    /**
     * get records
     *
     * separate record code in multiples methods
     *
     * @param Table $instance
     * @param string $table
     * @param int $current
     * @param int $limit
     * @param PDO $pdo
     * @param bool $framework
     * @param string $orderBy
     *
     * @return array
     *
     * @throws Exception
     */
    function getRecords(Table $instance,string $table,int $current,int $limit,PDO $pdo,bool $framework,string $orderBy = 'DESC')
    {

        $instance = $instance->setName($table);

        $key = $instance->primaryKey();
        if (is_null($key))
            throw new Exception('We have not found a primary key');

        $offset = ($limit * $current) - $limit;
        $driver = $instance->getDriver();
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

        }else
        {


            $like = get('search');
            if (empty($like))
                $records = sql($table)->setPdo($pdo)->limit($limit,$offset)->orderBy($key,$orderBy)->getRecords();
            else
                $records = sql($table)->setDriver($driver)->setPdo($pdo)->like($instance,$like)->orderBy($key,$orderBy)->getRecords();
        }

        return $records;
    }
}


if (!exist('bootstrapJs'))
{
    function bootstrapJs(): string
    {
        return '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
    }
}


if (!exist('_html'))
{

    /***
     * print html code
     *
     * @param bool $secure
     *
     * @param mixed ...$data
     */
    function _html(bool $secure,...$data)
    {
        foreach ($data as $x)
        {
            if (!is_array($x))
            {
                if ($secure)
                {
                    echo htmlspecialchars($x, ENT_QUOTES, 'UTF-8', $secure);
                } else {
                    echo $x;
                }
            }

        }

    }
}
if (!exist('html'))
{
    /**
     * @param string $element
     * @param string $content
     * @param string $class
     * @param string $id
     *
     * @return string
     * @throws Exception
     */
    function html(string $element, string $content ,string $class = '',string $id= ''): string
    {
        switch ($element)
        {
            case 'link':
                    return '<link href="'.$content.'" rel="stylesheet">';
            break;
            case 'meta':
                return '<meta '.$content.'>';
            break;
            case 'img':
                return '<img src="'.$content.'" class="'.$class.'">';
            break;
            case 'title':
            case 'article':
            case 'aside':
            case 'footer':
            case 'header':
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'nav':
            case 'section':
            case 'div':
            case 'p':
            case 'li':
            case 'ol':
            case 'ul':
            case 'pre':

                $html = "<$element";

                if (!empty($class))
                    $html .= ' class="'.$class.'"';

                if (!empty($id))
                    $html .= ' id="'.$id.'"';

                $html .= '>' .$content . '</'.$element.'>';

                return $html;

            break;
            default:
                throw new Exception('Element are not in supported list');
            break;
        }
    }
}


if (!exist('submit'))
{
    function submit(string $key,string $method = 'POST')
    {
        if ($method === 'POST')
            return !empty($_POST[$key]) && isset($_POST[$key]);

        return !empty($_GET[$key]) && isset($_GET[$key]);
    }
}
if (!exist('bootswatch'))
{
    /**
     * generate bootswatch css link
     *
     * @param string $theme
     * @param string $version
     *
     * @return string
     */
    function bootswatch(string $theme = 'bootstrap',string $version = '4.0.0'): string
    {
        if ($theme == "bootstrap")
            return '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/'.$version.'/css/bootstrap.min.css">';
    
        return '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/'.$version.'/'.$theme.'/bootstrap.min.css">';

    }
}

if (!exist('push'))
{
    /**
     * push one or more elements onto the end of array
     *
     * @param array $array
     * @param mixed $value
     *
     * @return int
     */
    function push(array &$array,$value): int
    {
        return array_push($array,$value);
    }
}

if (!exist('pop'))
{
    /**
     * pop the element off the end of array
     *
     * @param array $array
     *
     * @return mixed
     */
    function pop(array &$array)
    {
        return array_pop($array);
    }
}

if (!exist('has'))
{
    /**
     * checks if a value exists in an array
     *
     * @param mixed $needle
     * @param array $array
     *
     * @param bool  $mode
     *
     * @return mixed
     */
    function has($needle,array $array,bool $mode = true)
    {
        return in_array($needle,$array,$mode);
    }
}

if (!exist('values'))
{
    /**
     * Return all the values of an array
     *
     * @param array $array
     *
     * @return array
     */
    function values(array &$array): array
    {
        return array_values($array);
    }
}

if (!exist('merge'))
{
    /**
     * merge two array
     *
     * @param array $array
     * @param array $second
     *
     * @return array
     */
    function merge(array $array,array $second): array
    {
        return array_merge($array,$second);
    }
}

if (!exist('session'))
{
    /**
     * get a $_SESSION value
     *
     * @param string $key
     *
     * @return string
     */
    function session(string $key): string
    {
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key]))
            return  htmlspecialchars($_SESSION[$key], ENT_QUOTES, 'UTF-8', true);

        return '';
    }
}

if (!exist('cookie'))
{
    /**
     * get a $_COOKIE value
     *
     * @param string $key
     *
     * @return string
     */
    function cookie(string $key): string
    {
        if (isset($_COOKIE[$key]) && !empty($_COOKIE[$key]))
            return  htmlspecialchars($_COOKIE[$key], ENT_QUOTES, 'UTF-8', true);

        return '';
    }
}
if (!exist('get'))
{
    /**
     * get a $_GET value
     *
     * @param string $key
     *
     * @return string
     */
    function get(string $key): string
    {
        if (isset($_GET[$key]) && !empty($_GET[$key]))
            return  htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8', true);

        return '';
    }
}

if (!exist('files'))
{
    /**
     * get a $_FILE value
     *
     * @param string $key
     *
     * @return mixed
     */
    function files(string $key)
    {
        if (isset($_FILES[$key]) && !empty($_FILES[$key]))
            return $_FILES[$key];

        return '';
    }
}
if (!exist('server'))
{
    /**
     * get a $_SERVER value
     *
     * @param string $key
     *
     * @return string
     */
    function server(string $key): string
    {
        if (isset($_SERVER[$key]) && !empty($_SERVER[$key]))
            return  $_SERVER[$key];

        return '';
    }
}

if (!exist('post'))
{
    /**
     * get a $_POST value
     *
     * @param string $key
     *
     * @return string
     */
    function post(string $key): string
    {
        if (isset($_POST[$key]) && !empty($_POST[$key]))
            return htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8', true);
        return '';
    }
}
if (!exist('generate'))
{
    /**
     * generate a form to edit or create a record
     *
     * @param string $formId
     * @param string $class
     * @param string $action
     * @param string $table
     * @param Table $instance
     * @param string $submitText
     * @param string $submitClass
     * @param string $submitIcon
     * @param string $submitId
     * @param string $csrfToken
     * @param int $mode
     * @param int $id
     *
     * @return string
     * @throws Exception
     */
    function generate(string $formId,string $class,string $action,string $table,Table $instance,string $submitText,string $submitClass,string $submitIcon,string $submitId,string $csrfToken = '',int $mode = Form::CREATE,int $id = 0): string
    {
        return form($action,$formId,$class)->csrf($csrfToken)->generate($table,$instance,$submitText,$submitClass,$submitId,$submitIcon,$mode,$id);
    }
}

if (!exist('root'))
{
    /**
     * get the connexion with all rights
     *
     * @param string $driver
     * @param string $user
     * @param string $password
     * @return null|PDO
     */
    function root(string $driver,string $user,string $password = '')
    {
        switch ($driver)
        {
            case Connexion::MYSQL:
                return connect($driver,'',$user,$password);
            break;
            case Connexion::POSTGRESQL:
                return connect($driver,'',$user,$password);
            break;
            default:
                return null;
            break;
        }


    }
}
if (!exist('collation'))
{
    /**
     * get all collation
     *
     * @param string $driver
     * @param PDO    $connexion
     *
     * @return array
     */
    function collation(string $driver,PDO $connexion): array
    {
        $collation = array();

        switch ($driver)
        {
            case Connexion::MYSQL:
                foreach (req($connexion,"SHOW COLLATION") as $char)
                    push($collation,$char->Collation);
            break;
            case Connexion::POSTGRESQL:
                foreach (req($connexion,"SELECT collname FROM pg_collation") as $char)
                    push($collation,$char->collname);
            break;
            default:
                return $collation;
            break;
        }
        return $collation;
    }
}
if (!exist('charset'))
{
    /**
     * get all charset
     *
     * @param string $driver
     * @param PDO    $connexion
     *
     * @return array
     */
    function charset(string $driver,PDO $connexion): array
    {
        $encoding = array();
        switch ($driver)
        {
            case Connexion::MYSQL:
                foreach (req($connexion,"SHOW CHARACTER SET") as $char)
                    push($encoding,$char->Charset);
            break;

            case Connexion::POSTGRESQL:
                 foreach (req($connexion,"SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1") as $char)
                    push($encoding,$char->pg_encoding_to_char);
            break;
            default:
                return $encoding;
            break;
        }
        return $encoding;
    }
}

if (!exist('git'))
{
    /**
     * manage git repository
     *
     * @param string $repository
     *
     * @return GitRepository
     * @throws \Cz\Git\GitException
     */
    function git(string $repository): GitRepository
    {
        return new GitRepository($repository);
    }
}

if (!exist('getCurrentBranch'))
{
    /**
     * get the current git branch
     *
     * @param string $repository
     *
     * @return string
     *
     * @throws \Cz\Git\GitException
     */
    function getCurrentBranch(string $repository): string
    {
        return (new GitRepository($repository))->getCurrentBranchName();
    }
}


if (!exist('base'))
{
    /**
     * manage database
     *
     * @param string $driver
     * @param string $base
     * @param string $username
     * @param string $password
     * @param string $dumpPath
     * @param array  $hidden
     *
     * @return Base
     */
    function base(string $driver,string $base,string $username,string $password,string $dumpPath,array $hidden = [])
    {
        return Base::manage()->setName($base)->setDriver($driver)->setUser($username)->setPassword($password)->setDumpDirectory($dumpPath)->setHidden($hidden);
    }
}

if (!exist('user'))
{
    /**
     * manage users
     *
     * @param string $driver
     * @param string $username
     * @param string $password
     * @param array  $hidden
     *
     * @return Users
     */
    function user(string $driver,string $username,string $password,array $hidden = []) : Users
    {
        return Users::manage()->setDriver($driver)->setName($username)->setPassword($password)->setHidden($hidden);
    }
}

if (!exist('connect'))
{
    /**
     * connect to a database
     *
     * @param string $driver
     * @param string $database
     * @param string $username
     * @param string $password
     *
     * @return null|PDO
     */
    function connect(string $driver,string $database = '',string $username = '',$password = '')
    {
        return Connexion::connect()->setDriver($driver)->setDatabase($database)->setUser($username)->setPassword($password)->getConnexion();
    }
}

if (!exist('pass'))
{
    /**
     * update user password
     *
     * @param string $driver
     * @param string $username
     * @param string $current
     * @param string $new
     *
     * @return bool
     */
    function pass(string $driver,string $username,string $current,string $new) : bool
    {

        return user($driver, $username, $current)->updatePassword($username,$new);

    }
}


if (!exist('os'))
{
    /**
     * see os
     *
     * @param bool $name
     *
     * @return Os|string
     */
    function os(bool $name = false)
    {
        if ($name)
            return (new Os())->getName();
        else
            return new Os();
    }
}

if (!exist('device'))
{
    /**
     * see devices
     *
     * @param bool $name
     *
     * @return string|Device
     */
    function device(bool $name = false)
    {
        if ($name)
            return (new Device())->getName();
        else
            return new Device();
    }
}

if (!exist('getDevice'))
{
    /**
     * get device
     *
     * @return string
     */
    function getDevice(): string
    {
        return (new Device())->getName();
    }
}

if (!exist('getOs'))
{
    /**
     * get operating system
     *
     * @return string
     */
    function getOs(): string
    {
        return (new Os())->getName();
    }
}

if (!exist('getBrowser'))
{
    /**
     * get browser name
     *
     * @return string
     */
    function getBrowser(): string
    {
        return (new Browser())->getName();
    }
}

if (!exist('browser'))
{
    /**
     * see browser
     *
     * @param bool $name
     *
     * @return Browser|string
     */
    function browser(bool $name = false)
    {
        if ($name)
            return (new Browser())->getName();
        else
            return new Browser();
    }
}

if (!exist('isBrowser'))
{
    /**
     * check if is name is browser
     *
     * @param string $name
     *
     * @return bool
     */
    function isBrowser(string $name): bool
    {
        return (new Browser())->isBrowser($name);
    }
}

if (!exist('isMobile'))
{
    /**
     * check if device is mobile
     *
     * @return bool
     */
    function isMobile(): bool
    {
        return (new Os())->isMobile();
    }
}

if (!exist('create'))
{
    /**
     * create a new database
     *
     * @param string $driver
     * @param string $database
     * @param string $charset
     * @param string $collation
     * @param PDO    $connexion
     *
     * @return bool
     */
    function create(string $driver,string $database,string $charset,string $collation,PDO $connexion): bool
    {

        switch ($driver)
        {
            case Connexion::MYSQL:
                return execute($connexion,"CREATE DATABASE IF NOT EXISTS $database DEFAULT CHARACTER SET $charset DEFAULT COLLATE $collation");
            break;
            case Connexion::POSTGRESQL:
                return execute($connexion,"CREATE DATABASE $database ENCODING '$charset' LC_COLLATE='$collation' LC_CTYPE='$collation' TEMPLATE=template0");
            break;
            case Connexion::SQLITE:
                 return new PDO("sqlite:$database") instanceof PDO;
            break;
            default:
                return false;
            break;

        }
    }
}

if(!exist('show'))
{
    /**
     * show databases, users, tables
     *
     * @param string $driver
     * @param string $database
     * @param string $username
     * @param string $password
     * @param array $hidden
     * @param int $mode
     *
     * @return array
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    function show(string $driver,string $database,string $username,string $password,int $mode = Eloquent::MODE_ALL_DATABASES,array $hidden = []) : array
    {
        switch ($mode)
        {
            case Eloquent::MODE_ALL_DATABASES:
                return base($driver,$database,$username,$password,'')->setHidden($hidden)->show();
            break;
            case Eloquent::MODE_ALL_USERS:
                return user($driver,$username,$password)->setHidden($hidden)->show();
            break;
            case Eloquent::MODE_ALL_TABLES:
                 return table($driver,$database,$username,$password,'')->setHidden($hidden)->show();
            break;
            default:
                return array();
            break;
        }

    }
}

if(!exist('whoops'))
{
    /**
     * load whoops
     */
   function whoops()
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }
}
if(!exist('array_prev'))
{
    /**
     * get the prev value of array by the current key
     *
     * @param array $array
     * @param mixed $key
     *
     * @return mixed
     */
    function array_prev(array $array, $key)
    {

        $collection = new  Collection($array);

        return $collection->valueBeforeKey($key);
    }

}
if(!exist('req'))
{
    /**
     * execute a query return an array with results
     *
     * @param PDO $instance
     * @param string $request
     * @param int $fetchStyle
     *
     * @return array
     */
    function req(PDO $instance,string $request,int $fetchStyle = PDO::FETCH_OBJ): array
    {
        $query = $instance->prepare($request);

        $query->execute();

        $data = $query->fetchAll($fetchStyle);

        $query->closeCursor();

        return $data;
    }
}

if(!exist('execute'))
{

    /**
     * execute a query return a boolean
     *
     * @param PDO $instance
     * @param string $request
     * @return bool
     */
    function execute(PDO $instance,string $request): bool
    {
        $query = $instance->prepare($request);
        return $query->execute();
    }
}
if (!exist('db'))
{
    /**
     * create a new database with optional parameters
     *
     * @param Base $instance
     * @param string $base
     * @param string $charset
     * @param string $collation
     *
     * @return bool
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    function db(Base $instance,string $base,string $charset ='',string $collation =''): bool
    {
        return $instance->setCollation($collation)->setEncoding($charset)->create($base);
    }
}

if (!exist('drop'))
{
    /**
     * @param $instance
     * @param string[] ...$to
     *
     * @return bool
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    function drop($instance,string ...$to): bool
    {
        if ($instance instanceof Users)
            foreach ($to as $user)
                if (!$instance->drop($user))
                    return false;

        if ($instance instanceof Base)
            foreach ($to as $base)
                if (!$instance->drop($base))
                    return false;

        if ($instance instanceof Table)
            foreach ($to as $table)
                if (!$instance->drop($table))
                    return false;



        return true;
    }
}


if (!exist('model'))
{

    /**
     * return an instance of the mode class
     *
     * @param PDO $pdo
     * @param Table $instance
     * @param string $table
     * @param int $pdoMode
     * @param string $oderBy
     *
     * @return Model
     */
    function model(PDO $pdo,Table $instance, string $table,int $pdoMode = PDO::FETCH_OBJ,string $oderBy = 'desc'): Model
    {
        return new Model($pdo,$instance,$table,$pdoMode,$oderBy);
    }
}
if (!exist('table'))
{
    /**
     * manage tables
     *
     * @param string $driver
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $dumpPath
     *
     * @return Table
     */
    function table(string $driver,string $database,string $username,string $password,string $dumpPath): Table
    {
        return Table::manage()->setDriver($driver)->setDatabase($database)->setUsername($username)->setPassword($password)->setDumpPath($dumpPath);
    }
}

if (!exist('faker'))
{
    /**
     * get an instance of faker
     *
     * @param string $locale
     *
     * @return \Faker\Generator
     */
    function faker(string $locale = 'en_US' ): Faker\Generator
    {
        return Faker\Factory::create($locale);
    }
}


if (!exist('userDel'))
{
    /**
     * delete an user
     *
     * @param string $driver
     * @param string[] $users
     * @param PDO    $connexion
     *
     * @return bool
     */
    function userDel(string $driver,PDO $connexion,string ...$users): bool
    {
        switch ($driver)
        {
            case Connexion::MYSQL:
                foreach ($users as $user)
                    if (!execute($connexion,"DROP USER '$user'@'localhost'"))
                        return false;
            break;
            case Connexion::POSTGRESQL:
                foreach ($users as $user)
                    if (!execute($connexion,"DROP USER $user"))
                        return false;
            break;
            default:
                return false;
            break;
        }
        return true;
    }
}

if (!exist('form'))
{
    /**
     * start a form
     *
     * @param string $action
     * @param string $id
     * @param string $method
     * @param string $class
     * @param bool $enctype
     * @param string $charset
     *
     * @return Form
     */
    function form(string $action, string $id, string $method = Form::POST,string $class = '', bool $enctype = false,  string $charset = 'utf8')
    {
        return Form::create()->start($action,$id,$class,$enctype,$method,$charset);
    }
}

if (!exist('d'))
{
    function d(...$values)
    {
        $dumper = new \Imperium\Core\Debug\Dumper();

        foreach ($values as $value)
            $dumper->dump($value);

        die();
    }
}
if (!exist('dumper'))
{

    function  dumper(string $driver, string $username, string $password, string $database, string $dumpPath, int $mode = Eloquent::MODE_DUMP_DATABASE, string $table ='')
    {

        Dir::clear($dumpPath);
        $filename = $mode == Eloquent::MODE_DUMP_DATABASE ? "$dumpPath/$database.sql" : "$dumpPath/$table.sql";

        if (!is_null(connect($driver,$database,$username,$password)))
        {
            switch ($driver)
            {
                case Connexion::MYSQL:
                    if ($mode == Eloquent::MODE_DUMP_DATABASE)
                        MySql::create()->setDbName($database)->setPassword($password)->setUserName($username)->dumpToFile($filename);
                    else
                        MySql::create()->setDbName($database)->setPassword($password)->setUserName($username)->includeTables($table)->dumpToFile($filename);
                break;
                case Connexion::POSTGRESQL:
                    if ($mode == Eloquent::MODE_DUMP_DATABASE)
                        PostgreSql::create()->setDbName($database)->setPassword($password)->setUserName($username)->dumpToFile($filename);
                    else
                        PostgreSql::create()->setDbName($database)->setPassword($password)->setUserName($username)->includeTables($table)->dumpToFile($filename);
                break;
                case Connexion::SQLITE:
                    if ($mode == Eloquent::MODE_DUMP_DATABASE)
                        Sqlite::create()->setDbName($database)->dumpToFile($filename);
                    else
                        Sqlite::create()->setDbName($database)->includeTables($table)->dumpToFile($filename);
                break;

            }


            return true;
        }
        return false;
    }
}

if (!exist('sql'))
{
    /**
     * sql table builder
     *
     * @param string $table
     *
     * @return Query
     */
    function sql(string $table): Query
    {
        return Query::start()->setTable($table);
    }
}

if (!exist('union'))
{
    /**
     * @param int    $mode
     * @param string $firstTable
     * @param string $secondTable
     * @param array  $firstColumns
     * @param array  $secondColumns
     *
     * @return Query
     */
    function union(int $mode,string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query
    {
        return Query::start()->union($mode,$firstTable,$secondTable,$firstColumns,$secondColumns);
    }
}

if (!exist('getLines'))
{
    /**
     * get all lines in filename
     *
     * @param string $filename
     *
     * @return array
     */
     function getLines(string $filename): array
     {
         return File::getLines($filename);
     }
}
if (!exist('getKeys'))
{
    /**
     * get all keys in filename
     *
     * @param string $filename
     * @param string $delimiter
     *
     * @return array
     */
    function getKeys(string $filename,string $delimiter): array
    {
        return File::getKeys($filename,$delimiter);
    }

}

if (!exist('getValues'))
{
    /**
     * get all values in filename
     *
     * @param string $filename
     * @param string $delimiter
     *
     * @return array
     */
    function getValues(string $filename,string $delimiter): array
    {
        return File::getValues($filename,$delimiter);
    }

}

if (!exist('joins'))
{
    /**
     * generate a join clause
     *
     * @param int    $type
     * @param string $firstTable
     * @param string $secondTable
     * @param string $firstParam
     * @param string $secondParam
     * @param array  $firstColumns
     * @param string $condition
     *
     * @return Query
     */
    function joins(int $type,string $firstTable,string $secondTable,string $firstParam,string $secondParam,array $firstColumns = [], string $condition ='='): Query
    {
        return Query::start()->join($type,$firstTable,$secondTable,$firstParam,$secondParam,$firstColumns,$condition);
    }
}

if (!exist('pagination'))
{
    /**
     * create a pagination
     *
     * @param int $perPage
     * @param string $instance
     * @param int $current
     * @param int $total
     * @param string $startChar
     * @param string $endChar
     *
     * @param string $ulClass
     * @param string $startCssClass
     * @param string $endCssClass
     *
     * @return string
     */
    function pagination(int $perPage,string $instance,int $current,int $total,string $startChar,string $endChar,string $ulClass = 'pagination',string $startCssClass = 'page-item',string $endCssClass = 'page-item'): string
    {
        return Pagination::paginate($perPage,$instance)->setTotal($total)->setStartChar($startChar)->setEndChar($endChar)->setUlCssClass($ulClass)->setStartCssClass($startCssClass)->setEndCssClass($endCssClass)->setCurrent($current)->get('');
    }
}
if (!exist('userAdd'))
{
    /**
     * create a new user
     *
     * @param string $driver
     * @param string $user
     * @param string $password
     * @param string $rights
     * @param PDO    $connexion
     *
     * @return bool
     */
    function userAdd(string $driver,string $user,string $password,string $rights,PDO $connexion): bool
    {
        switch($driver)
        {
            case Connexion::MYSQL:
                return execute($connexion,"CREATE USER '$user'@'localhost' IDENTIFIED BY '$password' $rights");
            break;
            case Connexion::POSTGRESQL:
                return execute($connexion,"CREATE ROLE $user PASSWORD '$password' $rights");
            break;
            default:
                return false;
            break;
        }
    }
}
if (!exist('jasnyCss'))
{
    function jasnyCss(string $version = '3.1.3')
    {
        return '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/'.$version.'/css/jasny-bootstrap.min.css">';
    }
}

if (!exist('foundation'))
{
    function foundation(string $version = '6.4.3')
    {
        return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/'.$version.'/css/foundation.min.css"/>';
    }
}
if (!exist('loadFontAwesome'))
{
    function fontAwesome(string $version = 'v5.0.8')
    {
        return '<link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/fontawesome.css"><link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/solid.css">';
    }
}

if (!exist('jasnyJs'))
{
    function jasnyJs(string $version ='3.1.3')
    {
        return '<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/'.$version.'/js/jasny-bootstrap.min.js"></script>';
    }
}

if (!exist('icon'))
{
    /**
     * build and icon bar
     *
     * @param string $ulClass
     * @param string $linkClass
     * @param string $iconClass
     * @return Icon
     */
    function icon(string $ulClass = 'list-inline',string $linkClass = 'link',string $iconClass = 'icon'): Icon
    {
        return Icon::start()->setIconClass($iconClass)->setLinkClass($linkClass)->startUl($ulClass);
    }
}

if (!exist('canvas'))
{
    /**
     * start canvas
     *
     * @param string $id
     * @param string $position
     * @param string $ulClass
     * @param string $linkClass
     *
     * @return Canvas
     */
    function canvas(string $id,string $position = 'navmenu-fixed-right',string $ulClass = 'list-inline offCanvasLinkBackground',string $linkClass = 'offCanvasLink'): Canvas
    {
        return Canvas::start()->setPosition($position)->setId($id)->startUl($ulClass)->setLinkClass($linkClass);
    }
}


if(!exist('fa'))
{
    /**
     * generate a fa icon
     *
     * @param string $icon
     * @param string $options
     *
     * @return string
     */
    function fa(string $icon, string $options = ''): string
    {
        return '<i class="fas '.$icon.' '.$options.'" ></i>';
    }
}

if (!exist('cssLoader'))
{
    /**
     * load a css files
     *
     * @param string $url
     *
     * @return string
     */
    function cssLoader(string $url): string
    {
        return '<link href="'.$url.'" rel="stylesheet">';
    }
}

if (!exist('jsLoader'))
{
    /**
     * load a js files
     *
     * @param string $url
     * @param string $type
     *
     * @return string
     */
    function jsLoader(string $url,string $type = 'text/javascript'): string
    {
        return '<script src="'.$url.'" type="'.$type.'"></script>';
    }
}


if (!exist('iconic'))
{
    /**
     * generate a iconic icon
     *
     * @param string $type
     * @param string $icon
     * @param string $viewBox
     *
     * @return string
     */
    function iconic(string $type,string $icon,$viewBox = '0 0 8 8'): string
    {
        switch ($type)
        {
            case 'svg':
                return'<svg viewBox="'.$viewBox.'"><use xlink:href="'.$icon.'"></use></svg>';
            break;
            case 'img':
                return '<img src="'.$icon.'">';
            break;
            case 'icon':
                return '<span class="oi" data-glyph="'.$icon.'"></span>';
            break;
            case 'bootstrap':
                return '<span class="oi '.$icon.'"></span>';
            break;
            case 'foundation':
                return '<span class="'.$icon.'"></span>';
            break;
            default:
                return '';
            break;
        }
    }
}

if (!exist('glyph'))
{
    /**
     * generate a glyph icon
     *
     * @param string $icon
     * @param string $type
     *
     * @return string
     */
    function glyph(string $icon,$type = 'svg'): string
    {
        if ($type == 'svg')
            return '<svg-icon><src href="'.$icon.'"/></svg-icon>';
        else
            return '<img src="'.$icon.'"/>';
    }
}

if (!exist('image'))
{
    /**
     * manage image
     *
     * @param string $driver
     *
     * @return ImageManager
     */
    function image(string $driver): ImageManager
    {
        $config = array('driver' => $driver);
        return new ImageManager($config);
    }
}

if (!exist('today')) {

    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null $tz
     * @return Carbon
     */
    function today($tz = null): Carbon
    {
        return Carbon::today($tz);
    }
}
if (!exist('now')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null $tz
     * @return Carbon
     */
    function now($tz = null): Carbon
    {
        return Carbon::now($tz);
    }
}

if (!exist('future')) {

    /**
     * Create a new future date.
     *
     * @param  \DateTimeZone|string|null $tz
     * @param string                     $mode
     * @param int                        $time
     *
     * @return string
     */
    function future(string $mode,int $time,$tz = null): string
    {
        switch ($mode)
        {
            case 'second':
                return Carbon::now($tz)->addSecond($time)->toDateString();
            break;
            case 'seconds':
                return Carbon::now($tz)->addSeconds($time)->toDateString();
            break;
            case 'minute':
                return Carbon::now($tz)->addMinute($time)->toDateString();
            break;
            case 'minutes':
                return Carbon::now($tz)->addMinutes($time)->toDateString();
            break;
            case 'hour':
                return Carbon::now($tz)->addHour($time)->toDateString();
            break;
            case 'hours':
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
            case 'day':
                return Carbon::now($tz)->addDay($time)->toDateString();
            break;
            case 'days':
                return Carbon::now($tz)->addDays($time)->toDateString();
            break;
            case 'week':
                return Carbon::now($tz)->addWeek($time)->toDateString();
            break;
            case 'weeks':
                return Carbon::now($tz)->addWeeks($time)->toDateString();
            break;
            case 'month':
                return Carbon::now($tz)->addMonth($time)->toDateString();
            break;
            case 'months':
                return Carbon::now($tz)->addMonths($time)->toDateString();
            break;
            case 'year':
                return Carbon::now($tz)->addYear($time)->toDateString();
            break;
            case 'years':
                return Carbon::now($tz)->addYears($time)->toDateString();
            break;
            case 'century':
                return Carbon::now($tz)->addCentury($time)->toDateString();
            break;
            case 'centuries':
                return Carbon::now($tz)->addCenturies($time)->toDateString();
            break;
            default:
                return Carbon::now($tz)->addHour($time)->toDateString();
            break;
        }

    }
}

if (!exist('ago'))
{
    /**
     * return time based on a time
     *
     * @param string $locale
     * @param string $time
     * @param null   $tz
     *
     * @return string
     */
    function ago(string $locale,string $time,$tz = null): string
    {
        Carbon::setLocale($locale);

        return Carbon::parse($time,$tz)->diffForHumans();
    }
}

if (!exist('mysql_loaded'))
{
    /**
     * check if mysql is loaded
     *
     * @return bool
     */
    function mysql_loaded(): bool
    {
        return extension_loaded('pdo_mysql');
    }
}

if (!exist('pgsql_loaded'))
{
    /**
     * check if mysql is loaded
     *
     * @return bool
     */
    function pgsql_loaded(): bool
    {
        return extension_loaded('pdo_pgsql');
    }
}

if (!exist('sqlite_loaded'))
{
    /**
     * check if mysql is loaded
     *
     * @return bool
     */
    function sqlite_loaded(): bool
    {
        return extension_loaded('pdo_sqlite');
    }
}

/**
 * check if a function exist
 *
 * @param string $name
 *
 * @return bool
 */
function exist(string $name) : bool
{
    return function_exists($name);
}
