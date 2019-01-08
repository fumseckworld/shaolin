<?php

use Faker\Generator;
use Imperium\Debug\Dumper;
use Imperium\Dump\Dump;
use Imperium\Route\Route;
use Whoops\Run;
use Carbon\Carbon;
use Imperium\Imperium;
use Imperium\File\File;
use Imperium\Json\Json;
use Imperium\Bases\Base;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Users\Users;
use Imperium\Tables\Table;
use Imperium\Html\Form\Form;
use Imperium\Connexion\Connect;
use Sinergi\BrowserDetector\Os;
use Imperium\Collection\Collection;
use Sinergi\BrowserDetector\Device;
use Intervention\Image\ImageManager;
use Sinergi\BrowserDetector\Browser;
use Whoops\Handler\PrettyPageHandler;
use Imperium\Html\Pagination\Pagination;



if (not_exist('sql_file_path'))
{
    /**
     *
     * Get the sql file path
     *
     * @method sql_file_path
     *
     * @param  Connect       $connect The connexion to the base
     *
     * @return string
     *
     */
    function sql_file_path(Connect $connect): string
    {
        return "{$connect->dump_path()}/{$connect->base()}.sql";
    }
}


if (not_exist('true_or_false'))
{
    /**
     *
     * Generate a boolean
     *
     * @method true_or_false
     *
     * @return string
     *
     */
    function true_or_false():string
    {
        $data = rand(0,1) == 1;
        return $data ? 'true' : 'false';
    }
}

if (not_exist('quote'))
{
    /**
     * Secure a string
     *
     * @method quote
     *
     * @param  Connect $connect The connexion
     * @param  string  $value   The value to secure
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function quote(Connect $connect,string $value): string
    {
        return $connect->instance()->quote($value);
    }
}
if (not_exist('apps'))
{
    /**
     *
     * Get all applications
     *
     * @method apps
     *
     * @param  string $driver The pdo driver
     * @param  string $user The username
     * @param  string $base The base name
     * @param  string $password The password
     * @param  string $host The host
     * @param  string $dump_path The dump directory path
     * @param  string $current_table The current table
     * @param string $views_dir
     * @param  array $hidden_tables All hidden tables
     * @param  array $hidden_bases All hidden bases
     *
     * @return Imperium
     *
     * @throws Exception
     *
     */
    function apps(string $driver,string $user,string $base,string $password,string $host,string $dump_path,string $current_table,string $views_dir,array $hidden_tables,array $hidden_bases): Imperium
    {
        $connexion = connect($driver,$base,$user,$password,$host,$dump_path);
        return imperium($connexion,$current_table,$views_dir,$hidden_tables,$hidden_bases);
    }
}

if (not_exist('assign'))
{
    /**
     *
     * Assign a value in a variable by a condition
     *
     * @method assign
     *
     * @param  bool   $condition
     * @param  mixed  $variable
     * @param  mixed  $value
     *
     */
    function assign(bool $condition,&$variable,$value)
    {
        if ($condition)
        {
            $variable = $value;
        }
    }
}

if (not_exist('query'))
{
    /**
     *
     * Get an instance of the query builder
     *
     *
     * @method query
     *
     * @param  Table   $table
     * @param  Connect $connect
     *
     * @return Query
     *
     */
    function query(Table $table,Connect $connect): Query
    {
        return new Query($table,$connect);
    }
}

if (not_exist('is_pair'))
{
    /**
     * Check if number is pair
     *
     * @method is_pair
     *
     * @param  int     $x The number to check
     *
     * @return bool
     *
     */
    function is_pair(int $x): bool
    {
        return $x % 2 === 0;
    }
}

if (not_exist('equal'))
{
    /**
     *
     * Check if two values are equal
     *
     * @method equal
     *
     * @param  mixed $parameter     The parameter
     * @param  mixed $expected      The expected value
     * @param  bool  $run_exception To run Exception
     * @param  string $message      The Exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function equal($parameter, $expected,$run_exception = false,string $message = ''): bool
    {
        $x = strcmp($parameter,$expected) === 0;

        is_true($x,$run_exception,$message);

        return $x;
    }
}


if (not_exist('is_not_false'))
{
     /**
      *
      * Check if the data is not equal to false
      *
      * @method is_not_false
      *
      * @param  mixed        $data          The data to check
      * @param  bool         $run_exception To run Exception
      * @param  string       $message       The Exception message
      *
      * @return bool
      *
      * @throws Exception
      *
      */
    function is_not_false($data,bool $run_exception = false,string $message =''): bool
    {
        $x = $data !== false;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('is_not_true'))
{
    /**
     *
     * Check if data is not true
     *
     *
     * @method is_not_true
     *
     * @param  mixed       $data          The data to check
     * @param  bool        $run_exception To run Exception
     * @param  string      $message       The Exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function is_not_true($data,bool $run_exception = false,string $message =''): bool
    {
        $x =  $data !== true;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('is_false'))
{
    /**
     *
     * Check if data is equal to false
     *
     * @method is_false
     *
     * @param  mixed       $data          The data to check
     * @param  bool        $run_exception To run Exception
     * @param  string      $message       The Exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function is_false($data,bool $run_exception = false,string $message =''): bool
    {
        $x = $data === false;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('is_true'))
{
    /**
     *
     * Check if the data is equal to true
     *
     * @method is_true
     *
     * @param  mixed       $data          The data to check
     * @param  bool        $run_exception To run Exception
     * @param  string      $message       The Exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function is_true($data,bool $run_exception = false,string $message =''): bool
    {

        $x =  $data === true;

        if ($run_exception && $x)
            throw new Exception($message);

        return $x;

    }
}

if (not_exist('different'))
{
    /**
     *
     * Check if two data are different
     *
     * @method different
     *
     * @param  mixed    $parameter     The parameter to check
     * @param  mixed    $expected      The expected value
     * @param  bool     $run_exception To run Exception
     * @param  string   $message       The Exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function different($parameter,$expected,$run_exception = false,string $message = ''): bool
    {
        $x = strcmp($parameter,$expected) !== 0;

        is_true($x,$run_exception,$message);

        return $x;
    }
}
if (not_exist('debug'))
{
    /**
     *
     * Debug all values by a condition
     *
     * @method debug
     *
     * @param  bool    $condition The condition
     * @param  mixed[] $values    The values to debug
     *
     */
    function debug(bool $condition,...$values)
    {
        if ($condition)
        {
           d($values);
        }
    }
}

if (not_exist('secure_register_form'))
{
    /**
     *
     * Generate a register form
     *
     * @method secure_register_form
     *
     * @param  string $action
     * @param  string $valid_ip
     * @param  string $current_ip
     * @param  string $username_placeholder
     * @param  string $username_success_text
     * @param  string $username_error_text
     * @param  string $email_placeholder
     * @param  string $email_success_text
     * @param  string $email_error_text
     * @param  string $password_placeholder
     * @param  string $password_valid_text
     * @param  string $password_invalid_text
     * @param  string $confirm_password_placeholder
     * @param  string $submit_text
     * @param  string $submit_id
     * @param  bool $multiple_languages
     * @param  array $supported_languages
     * @param  string $choose_language_text
     * @param  string $choose_language_valid_text
     * @param  string $choose_language_invalid_text
     * @param  string $select_time_zone_text
     * @param  string $valid_time_zone_text
     * @param  string $time_zone_invalid_text
     * @param  string $csrf_token_field
     * @param  string $submit_button_class
     * @param  string $password_icon
     * @param  string $username_icon
     * @param  string $email_icon
     * @param  string $submit_icon
     * @param  string $time_zone_icon
     * @param  string $lang_icon
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function secure_register_form(  string $action,string $valid_ip,string $current_ip,string $username_placeholder,
                                    string $username_success_text,string $username_error_text,string $email_placeholder,
                                    string $email_success_text,string $email_error_text,string $password_placeholder,
                                    string $password_valid_text,string $password_invalid_text,string $confirm_password_placeholder,
                                    string $submit_text,string $submit_id,bool $multiple_languages = false,
                                    array $supported_languages =[],string $choose_language_text = '', string $choose_language_valid_text ='',
                                    string $choose_language_invalid_text = '',string $select_time_zone_text ='',string $valid_time_zone_text= '',
                                    string $time_zone_invalid_text = '',string $csrf_token_field = '',string $submit_button_class = 'btn btn-outline-primary',
                                    string $password_icon = '<i class="fas fa-key"></i>',string $username_icon = '<i class="fas fa-user"></i>',
                                    string $email_icon = '<i class="fas fa-envelope"></i>',string $submit_icon = '<i class="fas fa-user-plus"></i>',
                                    string $time_zone_icon = '<i class="fas fa-clock"></i>',string $lang_icon = '<i class="fas fa-globe"></i>'
                                ): string
    {

        $languages = collection(array('' => $choose_language_text));
        foreach ($supported_languages as $k => $v)
            $languages->merge([$k => $v]);

        if (equal($valid_ip,$current_ip))
        {
            $form = form($action,'register-form','was-validated ')->csrf($csrf_token_field)->validate() ;
            if ($multiple_languages)
                $form->row()->select('locale',$languages->collection(),$choose_language_valid_text,$choose_language_invalid_text,$lang_icon)->select('zone',zones($select_time_zone_text),$valid_time_zone_text,$time_zone_invalid_text,$time_zone_icon)->end_row();

           return   $form->row()->input(Form::TEXT,'name',$username_placeholder,$username_icon,$username_success_text,$username_error_text,post('name'),true)->input(Form::EMAIL,'email',$email_placeholder,$email_icon,$email_success_text,$email_error_text,post('email'),true)->end_row_and_new()
                ->input(Form::PASSWORD,'password',$password_placeholder,$password_icon,$password_valid_text,$password_invalid_text,post('password'),true)->input(Form::PASSWORD,'password_confirmation',$confirm_password_placeholder,$password_icon,$password_valid_text,$password_invalid_text,post('password_confirmation'),true)->end_row_and_new()
                ->submit($submit_text,$submit_button_class,$submit_id,$submit_icon)->end_row()->get();

        }
        return '';
    }
}



if (not_exist('bases_to_json'))
{
    /**
     *
     * Generate a json file with all bases not hidden
     * with an optional key
     *
     * @method bases_to_json
     *
     * @param  Base          $base     An instance of base
     * @param  string        $filename  The filename
     * @param  string        $key      The optional key
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function bases_to_json(Base $base,string $filename,string $key =''): bool
    {
        return json($filename)->add($base->show(),$key)->generate();
    }
}

if (not_exist('users_to_json'))
{
    /**
     *
     * Generate a json file with all users not hidden
     * with an optional key
     *
     * @method users_to_json
     *
     * @param  Users         $users    An instance of user
     * @param  string        $filename  The filename
     * @param  string        $key      The optional key
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function users_to_json(Users $users,$filename,string $key = '') : bool
    {
        return json($filename)->add($users->show(),$key)->generate();
    }
}

if (not_exist('tables_to_json'))
{
    /**
     *
     * Generate a json file with all tables not hidden
     * with an optional key
     *
     * @method tables_to_json
     *
     * @param  Table          $table   An instance of table
     * @param  string         $filename The filename
     * @param  string         $key     The key
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function tables_to_json(Table $table,string $filename,string $key= '') : bool
    {
        return json($filename)->add($table->show(),$key)->generate();
    }
}

if (not_exist('sql_to_json'))
{
    /**
     *
     * Generate a json file with the result of all sql queries
     *
     * @method sql_to_json
     *
     * @param  Connect     $connect  [description]
     * @param  string      $filename [description]
     * @param  array       $query    [description]
     * @param  array       $key      [description]
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function sql_to_json(Connect $connect,string $filename,array $query, array $key) : bool
    {
        $x =  json($filename);

        $keys = collection($key);

        foreach($query as $k => $v)
            $x->add($connect->request($v),$keys->get($k));

        return $x->generate();
    }
}


if (not_exist('query_result'))
{
    /**
     * Print the query result
     *
     * @method query_result
     *
     * @param  Model $model Instance of the model
     * @param  mixed $data The query results
     * @param  string $success_text The success text
     * @param  string $result_empty_text Result empty text
     * @param  string $table_empty_text Table is empty text
     * @param  string $sql The sql query to print
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function query_result(Model $model,$data,string $success_text,string $result_empty_text,string $table_empty_text,string $sql): string
    {

        if (is_bool($data) && $data)
           return html('code',$sql,'text-center').html('div',$success_text,'alert alert-success mt-5');
        elseif(empty($model->all()))
            return html('code',$sql,'text-center'). html('div',$table_empty_text,'alert alert-danger mt-5');
        else
           return  empty($data) ? html('div',$result_empty_text,'alert alert-danger') : html('code',$sql,'text-center') .collection($data)->print(true,$model->columns());

    }
}

if (not_exist('length'))
{
     /**
      *
      * Return the length of data
      *
      * @method length
      *
      * @param  mixed $data An array or a string
      *
      * @return int
      *
      * @throws Exception
      *
      */
    function length($data): int
    {
        if (is_array($data))
            return count($data);
        elseif(is_string($data))
            return strlen($data);
        else
            throw new Exception('The parameter must be a string or an array');
    }
}

if (not_exist('execute_query'))
{
    function twig(string $views_path,string $cache_dir)
    {

    }
}
if (not_exist('execute_query'))
{
    /**
     *
     * Execute a query
     *
     * @method execute_query
     *
     * @param Imperium $imperium
     * @param  int  $mode The query mode
     * @param  string $column_name The where column name
     * @param  string $condition The where condition
     * @param  mixed $expected The where expected
     * @param  string $submit_class The submit button class
     * @param  string $submit_update_text The submit update text
     * @param  string $form_update_action The update action
     * @param  string $key The order by key
     * @param  string $order The order type
     * @param  mixed &$show_sql_variable The variable to store the sql query
     *
     * @return mixed
     *
     * @throws Exception
     */
    function execute_query(Imperium $imperium,int $mode, string $column_name, string $condition, $expected, string $submit_class, $submit_update_text, string $form_update_action, string $key, string $order, &$show_sql_variable)
    {

        $model = $imperium->model();
        $table = $imperium->tables();
        $current_table_name = $table->current();
        $form_grid = 2;

        switch ($mode)
        {
            case Query::UPDATE:
                $code = collection();
                foreach ( $model->query()->mode(Query::SELECT)->where($column_name,$condition,$expected)->order_by($key,$order)->get()  as $record)
                {
                    $id = $table->from($current_table_name)->primary_key();

                    $code->push(form($form_update_action,id())->generate($form_grid,$current_table_name,$table,$submit_update_text,$submit_class,uniqid($current_table_name),'',Form::EDIT,$record->$id));
                }
                return $code->collection();
            break;
            case Query::DELETE:

                $data = $model->where($column_name,$condition,$expected)->get();
                $show_sql_variable = $model->query()->mode($mode)->where($column_name,$condition,$expected)->sql();
                return empty($data) ? $data :  $model->query()->mode($mode)->where($column_name, $condition, $expected)->delete() ;
            break;
            default:
                $show_sql_variable = $model->query()->mode($mode)->where($column_name,$condition,$expected)->order_by($key,$order)->sql();
               return $model->query()->mode(Query::SELECT)->where($column_name,$condition,$expected)->order_by($key,$order)->get();
            break;
        }
    }
}

if (not_exist('query_view'))
{
    /**
     *
     * Display a query form builder
     *
     * @method query_view
     *
     * @param  string $query_action The form action
     * @param  Model $model And instance of model
     * @param  Table $instance An instance of table
     * @param  string $confirm_message The confirm message
     * @param  string $create_record_action The create record action
     * @param  string $update_record_action The update record action
     * @param  string $create_record_submit_text The create submit text
     * @param  string $update_record_text The update submit text
     * @param  string $current_table_name The current table
     * @param  string $expected_placeholder The expected option text
     * @param  string $superior_text The superior option text
     * @param  string $superior_or_equal_text The superior or equal option text
     * @param  string $inferior_text The inferior option text
     * @param  string $inferior_or_equal_text The inferior or equal option text
     * @param  string $different_text The different option text
     * @param  string $equal_text The equal option text
     * @param  string $like_text The like option text
     * @param  string $select_mode_text The select mode text
     * @param  string $remove_mode_text The remove mode option text
     * @param  string $update_mode_text The update mode option text
     * @param  string $submit_query_text The submit button query text
     * @param  string $submit_class The submit button class
     * @param  string $remove_success_text The remove success text
     * @param  string $record_not_found_text The record not found text
     * @param  string $table_empty_text The table is empty text
     * @param  string $select_where_column_text The select where columns text
     * @param  string $select_condition_column_text The select condition text
     * @param  string $select_operation_column_text The select operation text
     * @param  string $select_order_column_text The order column text
     * @param  string $reset_form_text The reset form text
     * @param  string $reset_form_class The reset form class
     * @param  string $icon The form icon
     * @param  string $csrf The csrf token
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function query_view(string $query_action,Model $model,Table $instance,string $confirm_message,string $create_record_action,string $update_record_action,string $create_record_submit_text,string $update_record_text,string $current_table_name,string $expected_placeholder,string $superior_text,string $superior_or_equal_text,string $inferior_text,string $inferior_or_equal_text,string $different_text,string $equal_text,string $like_text,string $select_mode_text,string $remove_mode_text,string $update_mode_text,string $submit_query_text,string $submit_class,string $remove_success_text,string $record_not_found_text,string $table_empty_text,string $select_where_column_text,string $select_condition_column_text,string $select_operation_column_text,string $select_order_column_text,string $reset_form_text,string $reset_form_class ='btn btn-outline-danger',string $icon  = '<i class="fas fa-heart"></i>',string $csrf = ''): string
    {

        $table = $instance->from($current_table_name);
        $columns = $table->columns();


        $x = count($columns);

        $condition = array(Query::EQUAL => $equal_text,Query::DIFFERENT => $different_text,Query::INFERIOR => $inferior_text,Query::SUPERIOR => $superior_text,Query::INFERIOR_OR_EQUAL => $inferior_or_equal_text,Query::SUPERIOR_OR_EQUAL =>$superior_or_equal_text,Query::LIKE => $like_text);

        $columns_order = collection(['' => $select_order_column_text])->merge($columns)->collection();

        $columns = collection(['' => $select_where_column_text])->merge($columns)->collection();



        $condition = collection(['' => $select_condition_column_text])->merge($condition)->collection();

        $operations = collection(['' => $select_operation_column_text])->merge([Query::SELECT=> $select_mode_text,Query::DELETE=> $remove_mode_text,Query::UPDATE => $update_mode_text])->collection();

        is_pair($x) ?  $form_grid =  2 :  $form_grid =  3;

        $sql = '';

        return post('mode')

            ?
               (new Form())->validate()->start($query_action,id(),$confirm_message)->csrf($csrf)
                ->row()
                    ->reset($reset_form_text,$reset_form_class)
                ->end_row_and_new()
                    ->select('column',$columns,'success','error',$icon)
                    ->select('condition',$condition,'success','error',$icon)
                    ->input(Form::TEXT,'expected',$expected_placeholder,$icon,'success','error')
                ->end_row_and_new()
                    ->select('mode',$operations ,'success','error',$icon)
                    ->select('key',$columns_order,'success','error',$icon)
                    ->select('order',['asc','desc'],'success','faillure',$icon)
                ->end_row_and_new()
                    ->submit($submit_query_text,$submit_class,uniqid())
                ->end_row()->get()
                 .
                query_result($model,execute_query($model, $table, post('mode'), post('column'), post('condition'), post('expected'), $current_table_name, $submit_class, $update_record_text, $update_record_action, post('key'), post('order'), $sql),$remove_success_text,$record_not_found_text,$table_empty_text,$sql)

            :
                (new Form())->validate()->start($query_action,id(),$confirm_message)->csrf($csrf)
                ->row()
                    ->reset($reset_form_text,$reset_form_class)
                ->end_row_and_new()
                    ->select('column',$columns,'success','error',$icon)
                    ->select('condition',$condition,'success','error',$icon)
                    ->input(Form::TEXT,'expected',$expected_placeholder,$icon,'success','error')
                ->end_row_and_new()
                    ->select('mode',$operations ,'success','failure',$icon)
                    ->select('key',$columns_order,'success','failure',$icon)
                    ->select('order',['asc','desc'],'success','failure',$icon)
                ->end_row_and_new()
                    ->submit($submit_query_text,$submit_class,uniqid())

                ->end_row()->get() .form($create_record_action,uniqid())->generate($form_grid,$current_table_name,$table,$create_record_submit_text,$submit_class,uniqid()) ;
    }
}

if (not_exist('connect'))
{
    /**
     *
     * Connect to the base
     *
     * @method connect
     *
     * @param  string $driver The base driver
     * @param  string $base The base name
     * @param  string $user The username
     * @param  string $password The password
     * @param  string $host The host
     * @param  string $dump_path The dump directory path
     *
     * @return Connect
     *
     * @throws Exception
     */
    function connect(string $driver,string $base,string $user,string $password,string $host,string $dump_path): Connect
    {
        return new Connect($driver,$base,$user,$password,$host,$dump_path);
    }
}

if (not_exist('login'))
{
    /**
     *
     * Generate a form to login user
     *
     * @method login
     *
     * @param  string $action The login action
     * @param  string $id The form id
     * @param  string $name_placeholder The username placeholder
     * @param  string $password_placeholder The password placeholder
     * @param  string $submit_text The submit button text
     * @param  string $submit_class The submit button class
     * @param  string $submit_id The submit button id
     * @param  string $csrf The csrf token field
     * @param  string $submit_icon The submit icon
     * @param  string $user_icon The user icon
     * @param  string $password_icon The password icon
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function login(string $action,string $id,string $name_placeholder,string  $password_placeholder,string $submit_text,string $submit_class,string $submit_id,string $csrf ='',string $submit_icon ='<i class="fas fa-sign-in-alt"></i>',string $user_icon ='<i class="fas fa-user"></i>',string $password_icon ='<i class="fas fa-key"></i>'): string
    {
        return form($action,$id)->csrf($csrf)->row()->input(Form::TEXT,'name',$name_placeholder,$user_icon)->input(Form::PASSWORD,'password',$password_placeholder,$password_icon)->end_row_and_new()->submit($submit_text,$submit_class,$submit_id,$submit_icon)->end_row()->get();
    }
}
if (not_exist('json'))
{
    /**
     *
     * Return an instance of json
     *
     * @method json
     *
     * @param  string $filename The json filename
     *
     * @return Json
     *
     */
    function json(string $filename): Json
    {
        return new Json($filename);
    }
}

if(not_exist('collection'))
{
    /**
     *
     * Return an instance of collection
     *
     * @method collection
     *
     * @param  array      $data The started array
     *
     * @return Collection
     *
     */
    function collection(array $data = []): Collection
    {
        return new Collection($data);
    }
}

if(not_exist('def'))
{

    /**
     *
     * Check if all values are define
     *
     * @method def
     *
     * @param  mixed $values The values to check
     *
     * @return bool
     *
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

if(not_exist('not_def'))
{

    /**
     *
     * Check if all values are not define
     *
     * @method not_def
     *
     * @param mixed  $values The values to check
     *
     * @return bool
     *
     */
    function not_def(...$values): bool
    {

       foreach ($values as $value)
           if (def($value))
               return false;

       return true;
    }
}

if (not_exist('zones'))
{
    /**
     *
     * List all time zones
     *
     * @method zones
     *
     * @param  string $select_time_zone_text The select zone text
     *
     * @return array
     *
     */
    function zones(string $select_time_zone_text) : array
    {
        $zones = collection(array('' => $select_time_zone_text));

        foreach (DateTimeZone::listIdentifiers() as $x)
            $zones->merge([$x => $x]);

        return $zones->collection();
    }
}

if (not_exist('tables_select'))
{
    /**
     *
     * Generate a table select
     *
     * @method tables_select
     *
     * @param string $current
     * @param  Table $instance An instance of table
     * @param  array $hidden The hidden tables
     * @param  string $url_prefix The url prefix
     * @param  string $csrf The csrf field
     * @param  string $separator The url separtor
     *
     * @return string
     *
     * @throws Exception
     */
    function tables_select(string $current,Table $instance,array $hidden, string $url_prefix,string $csrf,string $separator): string
    {
        $tables = collection(["$url_prefix$separator$current" => $current]);

        foreach ($instance->hidden($hidden)->show() as $x)
        {
            if (different($x,$current))
                $tables->add($x,"$url_prefix$separator$x");
        }
        return  form('',id())->csrf($csrf)->row()->redirect('table',$tables->collection())->end_row()->get() ;
     }
}

if (not_exist('users_select'))
{
    /**
     * Generate an user select
     *
     * @method users_select
     *
     * @param  Users $instance The user instance
     * @param  array $hidden   All hidden users
     * @param  string $urlPrefix The url prefix
     * @param  string $currentUser The current username
     * @param  string $chooseText The select text
     * @param  bool $use_a_redirect_select To use a redirect select
     * @param  string $csrf The csrf token
     * @param  string $separator The url separator
     * @param  string $icon The select user icon
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function users_select(Users $instance,array $hidden,string $urlPrefix,string $currentUser,string $chooseText,bool $use_a_redirect_select,string $csrf = '',string $separator = '/',string $icon = ''): string
    {
        $users = collection(array('' => $chooseText));

        foreach ($instance->hidden($hidden)->show() as $x)
        {
            if (different($x,$currentUser))
                $users->merge(["$urlPrefix$separator$x" => $x]);
        }

        return $use_a_redirect_select ?  form('',uniqid())->csrf($csrf)->row()->redirect('users',$users->collection(),$icon)->end_row()->get() : form('',uniqid())->csrf($csrf)->row()->select('users',$users->collection(),$icon)->end_row()->get();
     }
}


if (not_exist('bases_select'))
{
    /**
     * build a form to select a base
     *
     * @param Base $instance
     * @param array $hidden
     * @param string $urlPrefix
     * @param string $currentBase
     * @param string $chooseText
     * @param bool $use_a_redirect_select
     * @param string $csrf
     * @param string $separator
     * @param string $icon
     *
     * @return string
     *
     * @throws Exception
     */
    function bases_select(Base $instance,array $hidden,string $urlPrefix,string $currentBase,string $chooseText,bool $use_a_redirect_select,string $csrf = '',string $separator = '/',string $icon = '<i class="fas fa-database"></i>'): string
    {
        $bases = collection(array('' => $chooseText));

        foreach ($instance->hidden($hidden)->show() as $x)
        {
            if (different($x, $currentBase))
                $bases->merge(["$urlPrefix$separator$x" => $x]);

        }

        return $use_a_redirect_select ?  form('',uniqid())->row()->csrf($csrf)->redirect('bases',$bases->collection(),$icon)->end_row()->get() : form('',uniqid())->csrf($csrf)->row()->select('bases',$bases->collection(),$icon)->end_row()->get();
     }
}

if (not_exist('simply_view'))
{
    /**
     *
     * To see records
     *
     * @method simply_view
     *
     * @param  string $current_table_name The current table
     * @param  Table $instance The table instance
     * @param  array $records All records
     * @param  string $html_table_class The html table class
     * @param  string $action_remove_text The action remove text
     * @param  string $before_remove_text The confirm text
     * @param  string $remove_button_class The remove button class
     * @param  string $remove_url_prefix The remove user prefix
     * @param  string $remove_icon The remove icon
     * @param  string $action_edit_text The action edit text
     * @param  string $action_edit_url_prefix The edit url prefix
     * @param  string $edit_button_class The edit button class
     * @param  string $edit_icon The edit icon
     * @param  string $pagination The pagination
     * @param  bool $align_column_center To align column to the center
     * @param  bool $column_to_upper To display column in uppercase
     * @param  bool $pagination_to_right To display the pagination to right
     *
     * @return string
     *
     * @throws Exception
     */
    function simply_view(string $current_table_name, Table $instance , array $records  ,string $html_table_class,string $action_remove_text,string $before_remove_text,string $remove_button_class,string $remove_url_prefix,string $remove_icon,string $action_edit_text,string $action_edit_url_prefix,string $edit_button_class,string $edit_icon,string $pagination,bool $align_column_center,bool $column_to_upper,bool $pagination_to_right = true): string
    {
        $instance = $instance->from($current_table_name);

        $columns  = $instance->columns();
        $primary  = $instance->primary_key();

        $code = '';


        append($code,'<div class="table-responsive mt-4"><table class="'.$html_table_class.'"><thead><tr>');
        append($code,'<script>function sure(e,text){ if (! confirm(text)) {e.preventDefault()} }</script>');

        foreach ($columns as  $x)
        {

            append($code,'<th  class="');
            if ($align_column_center) {  append($code,' text-center'); }

            if ($column_to_upper)    {  append($code,' text-uppercase') ; }

            append($code, '">'.$x.'</th>');

        }
        append($code, '<th  class="');

        if ($align_column_center) {  append($code,' text-center'); }

        if ($column_to_upper)   {  append($code,' text-uppercase') ; }

        append($code, '">'.$action_edit_text.'</th>');

        append( $code,'<th  class="');

        if ($align_column_center) {  append($code,' text-center'); }

        if ($column_to_upper)   {  append($cpode,' text-uppercase') ; }

        append($code,'">'.$action_remove_text.'</th></tr></thead><tbody>');

        foreach ($records as $record)
        {
            append($code, '<tr>');

            foreach ($columns as $k => $column)
            {
                if (is_null($record->$column))
                    $record->$column = '';

                append($code, '<td> '.$record->$column.'</td>');

            }
            append($code ,'<td> <a href="'.$action_edit_url_prefix.'/'.$record->$primary.'" class="'.$edit_button_class.'">'.$edit_icon.'</a></td><td> <a href="'.$remove_url_prefix.'/'.$record->$primary.'" class="'.$remove_button_class.'" data-confirm="'.$before_remove_text.'" onclick="sure(event,this.attributes[2].value)">'.$remove_icon.' </a></td></tr>');
        }

        append($code, '</tbody></table></div>');

        if ($pagination_to_right)
            append($code ,    '<div class="row"><div class="ml-auto mt-5 mb-5">'.$pagination.'</div></div>');
        else
            append($code ,    '<div class="row"><div class="mr-auto mt-5 mb-5">'.$pagination.'</div></div>');

        return $code;

    }
}
if (not_exist('get_records'))
{
    /**
     *
     * Get limited records
     *
     * @method get_records
     *
     * @param  Table $instance The table instance
     * @param  string $current_table_name The current table name
     * @param  int $current_page The current page
     * @param  int $limit_per_page The limit
     * @param  Connect $connect The connexion to the base
     * @param  bool $framework To change url search generation
     * @param  string $key The key
     * @param  string $order_by The order by
     *
     * @return array
     *
     * @throws Exception
     *
     */
    function get_records(Table $instance,string $current_table_name,int $current_page,int $limit_per_page,Connect $connect,string $key = '',string $order_by = Table::DESC): array
    {
        $instance = $instance->from($current_table_name);

        $key = def($key) ? $key : $instance->primary_key();

        $offset = ($limit_per_page * $current_page) - $limit_per_page;

        $sql = sql(query($instance,$connect),$current_table_name)->mode(Query::SELECT);

        $like = get('q');
        $records = def($like) ? $sql->like($like)->order_by($key,$order_by)->get() : $sql->limit($limit_per_page, $offset)->order_by($key,$order_by)->get();

        return $records;
    }
}


if (not_exist('bootstrap_js'))
{
    /**
     * @return string
     */
    function bootstrap_js(): string
    {
        return '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
    }
}


if (not_exist('_html'))
{

    /**
     *
     * Print html code
     *
     * @method _html
     *
     * @param  bool   $secure Option to not execute code
     * @param  mixed  $data   The code
     *
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
                    echo html_entity_decode($x,ENT_QUOTES,'UTF-8');
                }
            }
        }

    }
}
if (not_exist('html'))
{
    /**
     *
     * Generate html element and put content inside
     *
     * @method html
     *
     * @param  string $element The html tag
     * @param  string $content The content
     * @param  string $class   The html tag class
     * @param  string $id      The html tag id
     *
     * @return string
     *
     * @throws Exception
     *
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
                return def($class) ? '<img src="'.$content.'" class="'.$class.'">' : '<img src="'.$content.'">' ;
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
            case 'code':


                $code = equal($element,'code');

                $html = $code ? "<pre" : "<$element";

                if (def($class))
                    append($html,' class="'.$class.'"');

                if (def($id))
                    append($html,' id="'.$id.'"');

                $code ?  append($html, '><code>' .$content . '</code></pre>') :   append($html, '>' .$content . '</'.$element.'>');

                return $html;

            break;
            default:
                throw new Exception('Element are not in supported list');
            break;
        }
    }
}


if (not_exist('id'))
{
    /**
     *
     * Generate an uniqid
     *
     *
     * @method id
     *
     * @param  string $prefix The prefix
     *
     * @return string
     *
     */
    function id(string $prefix =''): string
    {
        return uniqid($prefix);
    }
}
if (not_exist('submit'))
{
    /**
     *
     * Check if a form was submit
     *
     * @method submit
     *
     * @param  string $key  The form key
     * @param  bool   $post To check form with the post method
     *
     * @return bool
     *
     */
    function submit(string $key,bool $post = true): bool
    {
        return $post ? def(post($key)) : def(get($key)) ;
    }
}
if (not_exist('bootswatch'))
{
    /**
     * generate bootswatch css link
     *
     * @param string $theme
     * @param string $version
     *
     * @return string
     *
     * @throws Exception
     */
    function bootswatch(string $theme = 'bootstrap',string $version = '4.0.0'): string
    {
        if (equal($theme,"bootstrap"))
            return '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/'.$version.'/css/bootstrap.min.css">';

        return '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/'.$version.'/'.$theme.'/bootstrap.min.css">';

    }
}

if (not_exist('push'))
{
    /**
     *
     * Add elements to the end of the array
     *
     * @method push
     *
     * @param  array &$array  The array
     * @param  mixed $values  The values to add
     *
     **/
    function push(array &$array,...$values)
    {
        foreach ($values as $value)
            array_push($array,$value);
    }
}

if (not_exist('stack'))
{
    /**
     *
     * Add elements to the beginning of the array
     *
     * @method stack
     *
     * @param  array  &$array The array
     * @param  mixed  $values The values to add
     *
     */
    function stack(array &$array,...$values)
    {
        foreach ($values as $value)
           array_unshift($array,$value);
    }
}



if (not_exist('has'))
{
    /**
     *
     * Check if needle exist in the array
     *
     * @method has
     *
     * @param  mixed  $needle The data to check
     * @param  array  $array  The array
     * @param  bool   $mode   To set strict mode
     *
     * @return bool
     *
     */
    function has($needle,array $array,bool $mode = true)
    {
        return in_array($needle,$array,$mode);
    }
}

if (not_exist('values'))
{
    /**
     *
     * Return all values inside the array
     *
     * @method values
     *
     * @param  array $array The array
     *
     * @return array
     *
     */
    function values(array $array): array
    {
        return array_values($array);
    }
}

if(not_exist('keys'))
{
    /**
     *
     * Return all keys inside the array
     *
     * @method keys
     *
     * @param  array $array [description]
     *
     * @return array
     */
    function keys(array $array): array
    {
        return array_keys($array);
    }
}

if (not_exist('merge'))
{
    /**
     *
     * Merge array inside the array
     *
     * @method merge
     *
     * @param  array    &$array The array
     * @param  array[]  $to     The array to merge
     *
     */
    function merge(array &$array,array ...$to)
    {
        foreach ($to as $item)
            $array = array_merge($array,$item);
    }
}

if (not_exist('session'))
{
    /**
     *
     * Get a session value
     *
     * @method session
     *
     * @param  string  $key The session key
     *
     * @return string
     *
     */
    function session(string $key): string
    {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]) ?  htmlspecialchars($_SESSION[$key], ENT_QUOTES, 'UTF-8', true) : '';
    }
}

if (not_exist('cookie'))
{
    /**
     *
     * Get a cookie value
     *
     * @method cookie
     *
     * @param  string $key The cookie key
     *
     * @return string
     *
     */
    function cookie(string $key): string
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? htmlspecialchars($_COOKIE[$key], ENT_QUOTES, 'UTF-8', true) : '';
    }
}
if (not_exist('get'))
{
    /**
     * Get a get value
     *
     * @method get
     *
     * @param  string $key The get key
     *
     * @return string
     *
     */
    function get(string $key): string
    {
        return isset($_GET[$key]) && !empty($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8', true) : '';
    }
}

if (not_exist('callback'))
{
    /**
     *
     * Return the route callable
     *
     * @param string $name
     *
     * @return callable
     *
     * @throws Exception
     *
     */
    function callback(string $name): callable
    {
        return Route::callback($name);
    }
}

if (not_exist('url'))
{
    /**
     *
     * Return a route url by use it's name
     *
     * @param string $name The route name
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function url(string $name): string
    {
        return Route::url($name);
    }
}

if (not_exist(''))
{

}
if (not_exist('files'))
{
    /**
     *
     * Get a file key
     *
     * @method files
     *
     * @param  string $key The file key
     *
     * @return string
     *
     */
    function files(string $key): string
    {
        return isset($_FILES[$key]) && !empty($_FILES[$key]) ? $_FILES[$key] :  '';
    }
}
if (not_exist('server'))
{
    /**
     *
     * Get a server key
     *
     * @method server
     *
     * @param  string $key The server key
     *
     * @return string
     *
     */
    function server(string $key): string
    {
        return isset($_SERVER[$key]) && !empty($_SERVER[$key]) ?  $_SERVER[$key] : '';
    }
}

if (not_exist('post'))
{
    /**
     *
     * Get a post key
     *
     * @method post
     *
     * @param  string $key The post key
     *
     * @return string
     *
     */
    function post(string $key): string
    {
        return isset($_POST[$key]) && !empty($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8', true) : '';
    }
}
if (not_exist('generate'))
{
    /**
     *
     * Generate a form to edit or update a record
     *
     * @method generate
     *
     * @param  string $formId
     * @param  string $class
     * @param  string $action
     * @param  string $table
     * @param  Table $instance
     * @param  string $submitText
     * @param  string $submitClass
     * @param  string $submitIcon
     * @param  string $submitId
     * @param  string $csrfToken
     * @param  int $mode
     * @param  int $id
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function generate(string $formId,string $class,string $action,string $table,Table $instance,string $submitText,string $submitClass,string $submitIcon,string $submitId,string $csrfToken = '',int $mode = Form::CREATE,int $id = 0): string
    {
        return form($action,$formId,$class)->csrf($csrfToken)->generate(2,$table,$instance,$submitText,$submitClass,$submitId,$submitIcon,$mode,$id);
    }
}

if (not_exist('collation'))
{
    /**
     *
     * Display all available collations
     *
     * @method collation
     *
     * @param  Connect   $connexion The connection to the base
     *
     * @return array
     *
     * @throws Exception
     *
     */
    function collation(Connect $connexion): array
    {
        $collation = collection();

        if ($connexion->sqlite())
            return $collation->collection();

        $request = '';

        assign(equal($connexion->driver(),Connect::MYSQL),$request,'SHOW COLLATION');
        assign(equal($connexion->driver(),Connect::POSTGRESQL),$request,'SELECT collname FROM pg_collation');

        foreach ($connexion->request($request) as $char)
            $collation->push(current($char));

        return $collation->collection();
    }
}
if (not_exist('charset'))
{
    /**
     *
     * Display all available charsets
     *
     * @method charset
     *
     * @param  Connect  $connexion The connection to the base
     *
     * @return array
     *
     * @throws Exception
     *
     */
    function charset(Connect $connexion): array
    {
        $collation = collection();

        if ($connexion->sqlite())
            return $collation->collection();

        $request = '';

        assign(equal($connexion->driver(),Connect::MYSQL),$request,'SHOW CHARACTER SET');
        assign(equal($connexion->driver(),Connect::POSTGRESQL),$request,'SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1');

        foreach ($connexion->request($request) as $char)
            $collation->push(current($char));

        return $collation->collection();
    }
}

if (not_exist('base'))
{
    /**
     *
     * Get an instance of base
     *
     * @method base
     *
     * @param  Connect $connect
     * @param  Table   $table
     *
     * @return Base
     *
     * @throws Exception
     *
     */
    function base(Connect $connect,Table $table): Base
    {
        return new Base($connect,$table);
    }
}

if (not_exist('user'))
{
    /**
     *
     * Return an instance of user
     *
     * @method user
     *
     * @param  Connect $connect
     *
     * @return Users
     *
     * @throws Exception
     *
     */
    function user(Connect $connect) : Users
    {
        return new Users($connect);
    }
}

if (not_exist('pass'))
{
    /**
     *
     * Update user password
     *
     * @method pass
     *
     * @param  Connect $connect
     * @param  string $username
     * @param  string $new_password
     *
     * @return bool
     *
     * @throws Exception
     */
    function pass(Connect $connect,string $username ,string $new_password) : bool
    {
        return user($connect)->update_password($username,$new_password);
    }
}

if (not_exist('os'))
{
    /**
     *
     * Return an instance of Os or os name
     *
     * @method os
     *
     * @param  bool $get_name To get name
     *
     * @return Os|string
     *
     */
    function os(bool $get_name = false)
    {
        return $get_name ?  (new Os())->getName() : new Os();
    }
}

if (not_exist('device'))
{
    /**
     *
     * Return an instance of device or device name
     *
     * @method device
     *
     * @param  bool   $get_name To get name
     *
     * @return Device|string
     *
     */
    function device(bool $get_name = false)
    {
        return $get_name ?  (new Device())->getName() : new Device();
    }
}


if (not_exist('browser'))
{
    /**
     *
     * Return an instance of browser or browser name
     *
     * @method browser
     *
     * @param  bool   $get_name To get browser name
     *
     * @return Browser|string
     *
     */
    function browser(bool $get_name = false)
    {
        return $get_name ?  (new Browser())->getName(): new Browser();
    }
}

if (not_exist('is_browser'))
{
    /**
     *
     * Check if browser is equal to expected
     *
     * @method is_browser
     *
     * @param  string     $expected The expected browser
     *
     * @return bool
     *
     */
    function is_browser(string $expected): bool
    {
        return (new Browser())->isBrowser($expected);
    }
}

if (not_exist('is_mobile'))
{
    /**
     *
     * Check if device is mobile
     *
     * @method is_mobile
     *
     * @return bool
     *
     */
    function is_mobile(): bool
    {
        return (new Os())->isMobile();
    }
}


if (not_exist('superior'))
{
    /**
     *
     * Check if a value is superior
     *
     * @method superior
     *
     * @param  mixed $parameter The data to test
     * @param  int $expected The expected value
     * @param  bool $run_exception To run exception
     * @param  string $message The exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function superior($parameter,int $expected,bool $run_exception = false,string $message ='') : bool
    {

        $x = is_array($parameter) ? count($parameter) > $expected : $parameter > $expected;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('superior_or_equal'))
{

    /**
     *
     * Check if the value is superior or equal
     *
     * @method superior_or_equal
     *
     * @param  mixed $parameter The data to test
     * @param  int $expected The expected value
     * @param  bool $run_exception To run exception
     * @param  string $message The exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function superior_or_equal($parameter,int $expected,bool $run_exception = false,string $message ='') : bool
    {

        $x = is_array($parameter) ? count($parameter) >= $expected : $parameter >= $expected;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('inferior'))
{
    /**
     *
     * To check if the value is inferior
     *
     * @method inferior
     *
     * @param  mixed $parameter The data to test
     * @param  int $expected The expected value
     * @param  bool $run_exception To run exception
     * @param  string $message The exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function inferior($parameter,int $expected,bool $run_exception = false,string $message ='') : bool
    {

        $x = is_array($parameter) ? count($parameter) < $expected : $parameter < $expected;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if (not_exist('inferior_or_equal'))
{
    /**
     *
     * To check if a value is inferior or equal
     *
     * @method inferior_or_equal
     *
     * @param  mixed $parameter The data to check
     * @param  int $expected The expected value
     * @param  bool $run_exception To run exception
     * @param  string $message The exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function inferior_or_equal($parameter,int $expected,bool $run_exception = false,string $message ='') : bool
    {
        $x = is_array($parameter) ? count($parameter) <= $expected : $parameter <= $expected;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if(not_exist('whoops'))
{
    /**
     *
     * @return Run
     */
   function whoops(): Run
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        return $whoops->register();
    }
}
if(not_exist('before_key'))
{
    /**
     *
     * Return the before value of a key
     *
     * @method array_prev
     *
     * @param  array      $array The array
     * @param  mixed      $key   The after key
     *
     * @return mixed
     *
     */
    function before_key(array $array, $key)
    {
        return collection($array)->value_before_key($key);
    }

}
if(not_exist('req'))
{
    /**
     *
     * Execute all queries and save result in an array
     *
     * @method req
     *
     * @param  Connect $instance The connexion to the base
     * @param  string[] $queries The sql queries
     *
     * @return array
     *
     * @throws Exception
     *
     */
    function req(Connect $instance,string ...$queries): array
    {
        $data = collection();

        foreach($queries as $k => $query)
            $data->add($instance->request($query),$k);

        return $data->collection();

    }
}

if(not_exist('execute'))
{
    /**
     *
     * Execute all queries and save result in an array
     *
     * @method execute
     *
     * @param  Connect $instance The connexion to the base
     * @param  string[] $queries The sql queries
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function execute(Connect $instance,string ...$queries): bool
    {
        $data = collection();

        foreach($queries as $k => $query)
            $data->add($instance->execute($query),$k);

        return $data->not_exist(false);
    }
}

if (not_exist('model'))
{
    /**
     *
     * Return an instance of model
     *
     * @method model
     *
     * @param  Connect $connect The connection
     * @param  Table $table The table instance
     * @param  string $current_table_name The current table
     *
     * @return Model
     *
     * @throws Exception
     *
     */
    function model(Connect $connect,Table $table, string $current_table_name): Model
    {
        return new Model($connect,$table,$current_table_name);
    }
}

if (not_exist('table'))
{
    /**
     *
     * Return an instance of table
     *
     * @method table
     *
     * @param  Connect $connect The connection
     *
     * @param string $current_table
     * @param array $hidden
     *
     * @return Table
     *
     * @throws Exception
     *
     */
    function table(Connect $connect,string $current_table,array $hidden = []): Table
    {
        return new Table($connect,$current_table,$hidden);
    }
}

if (not_exist('faker'))
{
    /**
     *
     * Return an instance of faker
     *
     * @method faker
     *
     * @param  string $locale The local to use
     *
     * @return Generator
     *
     */
    function faker(string $locale = 'en_US' ): Generator
    {
        return Faker\Factory::create($locale);
    }
}


if (not_exist('remove_users'))
{
    /**
     *
     * Remove users
     *
     * @method remove_users
     *
     * @param  Users        $user  The instance of user
     * @param  string[]     $users The users to remove
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function remove_users(Users $user,string ...$users): bool
    {
        foreach ($users as $x)
            is_false($user->drop($x),true,"Failed to remove the user : $x");

        return true;
    }
}

if (not_exist('remove_tables'))
{
    /**
     *
     * Remove tables
     *
     * @method remove_tables
     *
     * @param  Table         $table  The instance of table
     * @param  string[]      $tables The tables to remove
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function remove_tables(Table $table,string ...$tables): bool
    {
        foreach ($tables as $x)
            is_false($table->drop($x),true,"Failed to remove the table : $x");


        return true;
    }
}

if (not_exist('remove_bases'))
{
    /**
     *
     * Remove the bases
     *
     * @method remove_bases
     *
     * @param  Base         $base      The instance of base
     * @param  string[]       $databases The bases to remove
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function remove_bases(Base $base,string ...$databases): bool
    {
        foreach ($databases as $x)
            is_not_true($base->drop($x),true,"Failed to remove the database : $x");

        return true;
    }
}

if (not_exist('form'))
{
    /**
     *
     * Return and instance of form
     *
     * @method form
     *
     * @param  string $action The form action
     * @param  string $id The form id
     * @param  string $class The form class
     * @param  string $confirm To enable confirm
     * @param  string $method  The form method
     * @param  bool $enctype To manage file
     * @param  string $charset The charset
     *
     * @return Form
     *
     * @throws Exception
     *
     */
    function form(string $action, string $id, string $class = '',string $confirm = '',string $method = Form::POST, bool $enctype = false,  string $charset = 'utf8'): Form
    {
        return def($confirm) ? (new Form())->validate()->start($action,$id,$confirm,$class,$enctype,strtolower($method),$charset) : (new Form())->start($action,$id,$confirm,$class,$enctype,strtolower($method),$charset);
    }
}

if(not_exist('slug'))
{
    /**
     *
     * Generate a slug
     *
     * @method slug
     *
     * @param  string $data      The string to manage
     * @param  string $delimiter The delimiter
     *
     * @return string
     *
     */
    function slug(string $data,string $delimiter = " "): string
    {
        return collection(explode($delimiter,$data))->each('strtolower')->join('-');
    }
}

if (not_exist('d'))
{
    /**
     *
     * Debug values and die
     *
     * @method d
     *
     * @param  mixed $values The values to debug
     *
     */
    function d(...$values)
    {
        $dumper = new Dumper();

        foreach ($values as $value)
            $dumper->dump($value);

        die();
    }
}

if (not_exist('not_in'))
{
    /**
     *
     * Check if value not exist in array
     *
     * @method not_in
     *
     * @param  array  $array         The array
     * @param  mixed  $value         The value
     * @param  bool   $run_exception To run exception
     * @param  string $message       The exception message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function not_in(array $array, $value, bool $run_exception = false, string $message = ''): bool
    {
        $x =  ! in_array($value,$array,true);

        is_true($x,$run_exception,$message);

        return $x;
    }
}



if (not_exist('dumper'))
{
    /**
     *
     * Dump a base or table
     *
     * @method dumper
     *
     * @param  Connect $connect The connexion
     * @param  bool $base To dump a base
     * @param  string[] $tables The tables to dump
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function dumper(Connect $connect,bool $base, string ...$tables): bool
    {
        return (new Dump($connect,$base,$tables))->dump();
    }
}


if (not_exist('sql'))
{
    /**
     *
     * Return an instance of sql query builder
     *
     * @method sql
     *
     * @param  Query  $query The query instance
     * @param  string $table The table name
     *
     * @return Query
     *
     */
    function sql(Query $query, string $table): Query
    {
        return $query->from($table);
    }
}

if (not_exist('lines'))
{
    /**
     * Get all lines in a file
     *
     * @method lines
     *
     * @param  string $filename The filename path
     *
     * @return array
     *
     */
     function lines(string $filename): array
     {
         return File::lines($filename);
     }
}
if (not_exist('file_keys'))
{
    /**
     * Return all key in a file
     *
     * @method file_keys
     *
     * @param  string    $filename   The filename path
     * @param  string    $delimiter The delimiter
     *
     * @return array
     *
     */
    function file_keys(string $filename,string $delimiter): array
    {
        return File::keys($filename,$delimiter);
    }

}

if (not_exist('file_values'))
{
    /**
     *
     * Return all values in a file
     *
     * @method file_values
     *
     * @param  string      $filename  The file path
     * @param  string      $delimiter The delimiter
     *
     * @return array
     *
     */
    function file_values(string $filename,string $delimiter): array
    {
        return File::values($filename,$delimiter);
    }

}


if (not_exist('pagination'))
{
    /**
     * create a pagination
     *
     * @param int $limit_per_page
     * @param string $pagination_prefix_url
     * @param int $current_page
     * @param int $total_of_records
     * @param string $start_pagination_text
     * @param string $end_pagination_text
     * @param string $ul_class
     * @param string $li_class
     *
     * @return string
     */
    function pagination(int $limit_per_page,string $pagination_prefix_url,int $current_page,int $total_of_records,string $start_pagination_text,string $end_pagination_text,string $ul_class = 'pagination',string $li_class = 'page-item'): string
    {
        return Pagination::paginate($limit_per_page,$pagination_prefix_url)->setTotal($total_of_records)->setStartChar($start_pagination_text)->setEndChar($end_pagination_text)->setUlCssClass($ul_class)->setLiCssClass($li_class)->setEndCssClass($li_class)->setCurrent($current_page)->get('');
    }
}
if (not_exist('add_user'))
{
    /**
     *
     * Create a new user
     *
     * @method add_user
     *
     * @param  Users $users The users instance
     * @param  string $user The username
     * @param  string $password The user password
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function add_user(Users $users,string $user,string $password): bool
    {
        return $users->set_name($user)->set_password($password)->create();
    }
}

if (not_exist('add_base'))
{
    /**
     *
     * Create a new base
     *
     * @method add_bases
     *
     * @param  Base $base The base instance
     * @param  string $collation The base collation
     * @param  string $charset The base charset
     * @param  string[] $bases The bases names
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function add_bases(Base $base,string $collation,string $charset,string ...$bases): bool
    {
        foreach ($bases as $x)
             is_false($base->set_collation($collation)->set_charset($charset)->create($x),true,"Failed to create database");

        return true;
    }
}
if (not_exist('jasnyCss'))
{
    function jasnyCss(string $version = '3.1.3')
    {
        return '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/'.$version.'/css/jasny-bootstrap.min.css">';
    }
}

if (not_exist('foundation'))
{
    function foundation(string $version = '6.4.3')
    {
        return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/'.$version.'/css/foundation.min.css"/>';
    }
}
if (not_exist('loadFontAwesome'))
{
    function fontAwesome(string $version = 'v5.0.8')
    {
        return '<link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/fontawesome.css"><link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/solid.css">';
    }
}

if (not_exist('jasnyJs'))
{
    function jasnyJs(string $version ='3.1.3')
    {
        return '<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/'.$version.'/js/jasny-bootstrap.min.js"></script>';
    }
}

if (not_exist('imperium'))
{
    /**
     *
     *
     * @method imperium
     *
     * @param  Connect $connect [description]
     * @param  string $current_table [description]
     * @param string $views_dir
     * @param  array $hidden_tables [description]
     * @param  array $hidden_bases [description]
     *
     * @return Imperium [description]
     * @throws Exception
     */
    function imperium(Connect $connect,string $current_table,string $views_dir,array $hidden_tables, array $hidden_bases): Imperium
    {
        return new Imperium($connect,$current_table,$views_dir,$hidden_tables,$hidden_bases );
    }
}

if (not_exist('retry'))
{
    /**
     * Retry an operation a given number of times.
     *
     * @param  int  $times
     * @param  callable  $callback
     * @param  int  $sleep
     * @return mixed
     *
     * @throws Exception
     *
     */
    function retry($times, callable $callback, $sleep = 0)
    {
        $times--;
        beginning:

        try {
            return $callback();
        } catch (Exception $e)
        {
            if (! $times)
            {

                throw $e;
            }
            $times--;
            if ($sleep)
            {
                usleep($sleep * 1000);
            }
            goto beginning;
        }
    }
}

if (not_exist('icon'))
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

if (not_exist('canvas'))
{
    /**
     * start canvas
     *
     * @param string $id
     * @param string $gridClass
     * @param string $rowClass
     * @param string $position
     * @param string $ulClass
     * @param string $linkClass
     *
     * @return Canvas
     */
    function canvas(string $id,string $gridClass = 'col',string $rowClass = 'row',string $position = 'navmenu-fixed-right',string $ulClass = 'list-inline offCanvasLinkBackground',string $linkClass = 'offCanvasLink'): Canvas
    {
        return Canvas::start()->setGridClass($gridClass)->setRowClass($rowClass)->setPosition($position)->setId($id)->setLinkClass($linkClass)->startUl($ulClass);
    }
}


if(not_exist('fa'))
{
    /**
     *
     * Generate a fa icon
     *
     * @method fa
     *
     * @param  string $prefix    The fa prefix
     * @param  string $icon     The fa icon name
     * @param  string $options  The fa options
     *
     * @return string
     *
     */
    function fa(string $prefix,string $icon, string $options = ''): string
    {
        return '<i class="'.$prefix.'  '.$icon.' '.$options.'" ></i>';
    }
}

if (not_exist('cssLoader'))
{
    /**
     *
     * Create all html link tag with urls
     *
     * @method css_loader
     *
     * @param  string[]     $urls The css file path
     *
     * @return string
     *
     */
    function css_loader(string ...$urls): string
    {
        $code = '';
        foreach ($urls as $url)
            append($code ,'<link href="'.$url.'" rel="stylesheet">');

        return $code;
    }
}

if (not_exist('append'))
{
    /**
     * Append content to a variable
     *
     * @method append
     *
     * @param  mixed   $variable The variable
     * @param  mixed[] $contents The contents
     *
     */
    function append(&$variable,...$contents)
    {
        foreach ($contents as $content)
            $variable .= $content;

    }
}


if (not_exist('js_loader'))
{
    /**
     * Create all js link tag with urls
     *
     * @method js_loader
     *
     * @param  string[]    $urls The js path
     *
     * @return string
     *
     */
    function js_loader(string ...$urls): string
    {

        $code = '';
        foreach ($urls as $url)
            append($code,'<script src="'.$url.'"></script>');

        return $code;
    }
}


if (not_exist('iconic'))
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


if (not_exist('insert_into'))
{
    /**
     *
     * @param Model $instance
     * @param string $table
     * @param mixed ...$values
     *
     * @return string
     *
     * @throws Exception
     */
    function insert_into(Model $instance,string $table,...$values): string
    {

        $x = collection($instance->columns())->join(',');

        $data = "INSERT INTO $table ($x) VALUES (";
        $primary = $instance->from($table)->primary();

        foreach ($values as $value)
        {
            if(different($value,$primary))
            {
                is_string($value) ? append($data,$instance->quote($value) .', ') : append($data,$value.', ');
            }
            else
            {
                if ($instance->is_mysql() | $instance->is_sqlite())
                    append($data,'NULL, ');
                else
                    append($data,"DEFAULT, ");

            }
        }


        $data = trim($data,', ');
        append($data, ')');
        return $data;

    }
}
if (not_exist('glyph'))
{
    /**
     *
     * Generate a glyph icon
     *
     * @param string $icon
     * @param string $type
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function glyph(string $icon,$type = 'svg'): string
    {
        return equal($type ,'svg') ? '<svg-icon><src href="'.$icon.'"/></svg-icon>' :  '<img src="'.$icon.'"/>';
    }
}

if (not_exist('image'))
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

if (not_exist('today'))
{

    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null $tz
     *
     * @return Carbon
     *
     */
    function today($tz = null): Carbon
    {
        return Carbon::today($tz);
    }
}

if (not_exist('now'))
{

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

if (not_exist('future'))
{

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

if (not_exist('ago'))
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

if (not_exist('mysql_loaded'))
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

if (not_exist('pgsql_loaded'))
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

if (not_exist('sqlite_loaded'))
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
function not_exist(string $name) : bool
{
    return ! function_exists($name);
}
