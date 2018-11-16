<?php


use Whoops\Run;
use Carbon\Carbon;
use Imperium\Imperium;
use Imperium\File\File;
use Imperium\Json\Json;
use Imperium\Bases\Base;
use Cz\Git\GitRepository;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Users\Users;
use Imperium\Debug\Dumper;
use Imperium\Tables\Table;
use Imperium\Directory\Dir;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Form\Form;
use Imperium\Connexion\Connect;
use Sinergi\BrowserDetector\Os;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Records\Records;
use Imperium\Collection\Collection;
use Sinergi\BrowserDetector\Device;
use Intervention\Image\ImageManager;
use Sinergi\BrowserDetector\Browser;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\Sqlite;
use Whoops\Handler\PrettyPageHandler;
use Imperium\Html\Pagination\Pagination;
use Spatie\DbDumper\Databases\PostgreSql;

if (not_exist('instance'))
{
    /***
     * get all instance
     *
     * @param string $driver
     * @param string $user
     * @param string $base
     * @param string $password
     * @param int $fetch_mode
     * @param string $dump_path
     * @param string $current_table
     * ²
     * @return Imperium
     *
     * @throws Exception
     */
    function instance (string $driver,string $user,string $base,string $password,int $fetch_mode,string $dump_path,string $current_table): Imperium
    {
        $connexion = connect($driver,$base,$user,$password,$fetch_mode,$dump_path);
        return imperium($connexion,$current_table);
    }
}

if (not_exist('assign'))
{
    /**
     * assign value in a variable by a condition
     *
     * @param bool $condition
     * @param $variable
     * @param $value
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
     * Get an instance of query
     *
     * @param Table $table
     * @param Connect $connect
     *
     * @return Query
     */
    function query(Table $table,Connect $connect): Query
    {
        return new Query($table,$connect);
    }
}

if (not_exist('is_pair'))
{
    /**
     *
     * Check if number is divisible by 2
     *
     * @param int $x
     *
     * @return bool
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
     * Check if two variables are equal
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function equal( $parameter, $expected,$run_exception = false,string $message = ''): bool
    {
        $x = strcmp($parameter,$expected) === 0;

        if ($run_exception && $x)
            throw new Exception($message);


        return $x;
    }
}

if (not_exist('is_not_false'))
{
    /**
     *
     * Check if  data is not equal to false
     *
     * @param $data
     *
     * @param bool $run_exception
     * @param string $message
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
     * Check if a data is not equal to false
     *
     * @param $data
     * @param bool $run_exception
     * @param string $message
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
     * Check if a data is not equal to false
     *
     * @param $data
     * @param bool $run_exception
     * @param string $message
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
     * Check if a data is not equal to false
     *
     * @param $data
     * @param bool $run_exception
     * @param string $message
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
     * Check if two variables are different
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
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
     * Debug code filter by a condition
     *
     * @param bool  $condition
     * @param mixed ...$values
     *
     * @return      void
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

if (not_exist('register'))
{

    /**
     * Generate a register form
     *
     * @param string $action
     * @param string $valid_ip
     * @param string $current_ip
     * @param string $username_placeholder
     * @param string $username_success_text
     * @param string $username_error_text
     * @param string $email_placeholder
     * @param string $email_success_text
     * @param $email_error_text
     * @param string $password_placeholder
     * @param string $password_valid_text
     * @param string $password_invalid_text
     * @param string $confirm_password_placeholder
     * @param string $submit_text
     * @param string $submit_id
     * @param bool $multiple_languages
     * @param array $supported_languages
     * @param string $choose_language_text
     * @param string $choose_language_valid_text
     * @param string $choose_language_invalid_text
     * @param string $select_time_zone_text
     * @param string $valid_time_zone_text
     * @param string $time_zone_invalid_text
     * @param string $csrf_token_field
     * @param string $submit_button_class
     * @param string $password_icon
     * @param string $username_icon
     * @param string $email_icon
     * @param string $submit_icon
     * @param string $time_zone_icon
     * @param string $lang_icon
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function register(string $action,string $valid_ip,string $current_ip,string $username_placeholder,string $username_success_text,string $username_error_text,string $email_placeholder,string $email_success_text,$email_error_text,string $password_placeholder,string $password_valid_text,string $password_invalid_text,string $confirm_password_placeholder,string $submit_text,string $submit_id,bool $multiple_languages = false,array $supported_languages =[],string $choose_language_text = '',string $choose_language_valid_text ='',string $choose_language_invalid_text = '',string $select_time_zone_text ='',string $valid_time_zone_text= '',string $time_zone_invalid_text = '',string $csrf_token_field = '',string $submit_button_class = 'btn btn-outline-primary',string $password_icon = '<i class="fas fa-key"></i>',string $username_icon = '<i class="fas fa-user"></i>',string $email_icon = '<i class="fas fa-envelope"></i>',string $submit_icon = '<i class="fas fa-user-plus"></i>',string $time_zone_icon = '<i class="fas fa-clock"></i>',string $lang_icon = '<i class="fas fa-globe"></i>')
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
     * generate a json with all databases
     *
     * @param Base $base
     * @param $filename
     * @param string $key
     *
     * @return bool
     *
     * @throws Exception
     */
    function bases_to_json(Base $base,$filename,string $key =''): bool
    {
        return json($filename)->add($base->show(),$key)->generate();
    }
}

if (not_exist('users_to_json'))
{
    /**
     *
     * generate a json with all users
     *
     * @param Users $users
     * @param $filename
     * @param string $key
     *
     * @return bool
     *
     * @throws Exception
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
     * generate a json with all users
     *
     * @param Table $table
     * @param $filename
     *
     * @param string $key
     * @return bool
     *
     * @throws Exception
     */
    function tables_to_json(Table $table,$filename,string $key= '') : bool
    {
        return json($filename)->add($table->show(),$key)->generate();
    }
}

if (not_exist('sql_to_json'))
{
    /**
     *
     * generate a json with all result of the query
     *
     * @param Connect $connect
     * @param string $query
     * @param $filename
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function sql_to_json(Connect $connect,string $query,$filename,string $key = '' ) : bool
    {
        return json($filename)->add($connect->request($query),$key)->generate();
    }
}


if (not_exist('query_result'))
{
    /**
     * @param Model $model
     * @param $mode
     * @param array $data
     *
     * @param array $columns
     *
     * @param $success_text
     * @param $result_empty_text
     *
     * @param $table_empty_text
     * @param string $sql
     * @return string
     *
     * @throws Exception
     */
    function query_result(Model $model,$mode,$data,array $columns,$success_text,$result_empty_text,$table_empty_text,string $sql): string
    {
        if (equal($mode,Query::UPDATE))
        {
            $code = '';
            foreach ($data as $datum)
                append($code,$datum);

            return $code;
        }

        if (is_bool($data) && $data)
           return html('code',$sql,'text-center').html('div',$success_text,'alert alert-success mt-5');
        elseif(empty($model->all()))
            return html('code',$sql,'text-center'). html('div',$table_empty_text,'alert alert-danger mt-5');
        else
           return  empty($data) ? html('div',$result_empty_text,'alert alert-danger') : html('code',$sql,'text-center') .collection($data)->print(true,$columns);

    }
}

if (not_exist('length'))
{
    /**
     *
     * Return the length of the data
     *
     * @param $data
     *
     * @return int
     *
     * @throws Exception
     *
     */
    function length($data): int
    {

        if (is_null($data) || is_resource($data) || is_object($data) || is_callable($data) || is_bool($data) || is_numeric($data))
            throw new Exception('The parameter must be a string or an array');

        return is_array($data) ? count($data) : strlen($data);
    }
}
if (not_exist('execute_query'))
{
    /**
     * search a value
     *
     * @param int $form_grid
     * @param Model $model
     * @param Table $table
     * @param $mode
     * @param string $column_name
     * @param string $condition
     * @param $expected
     * @param string $current_table_name
     * @param string $submit_class
     * @param $submit_update_text
     * @param string $form_update_action
     * @param string $key
     * @param string $order
     * @return array|bool
     *
     * @throws Exception
     */
    function execute_query(int $form_grid,Model $model,Table $table,$mode,string $column_name,string $condition,$expected,string $current_table_name,string $submit_class,$submit_update_text,string $form_update_action ,string $key,string $order,&$show_sql_variable)
    {

        switch ($mode)
        {
            case Query::UPDATE:
                $code = collection();
                foreach ( $model->query()->set_query_mode(Query::SELECT)->where($column_name,$condition,$expected)->order_by($key,$order)->get()  as $record)
                {
                    $id = $table->select($current_table_name)->get_primary_key();

                    $code->push(form($form_update_action,id())->generate($form_grid,$current_table_name,$table,$submit_update_text,$submit_class,uniqid($current_table_name),'',Form::EDIT,$record->$id));
                }
                return $code->collection();
            break;
            case Query::DELETE:
            
                $data = $model->where($column_name,$condition,$expected)->get();
                $show_sql_variable = $model->query()->set_query_mode($mode)->where($column_name,$condition,$expected)->sql();
                return empty($data) ? $data :  $model->query()->set_query_mode($mode)->where($column_name, $condition, $expected)->delete() ;
            break;
            default:
                $show_sql_variable = $model->query()->set_query_mode($mode)->where($column_name,$condition,$expected)->order_by($key,$order)->sql();
               return $model->query()->set_query_mode(Query::SELECT)->where($column_name,$condition,$expected)->order_by($key,$order)->get();
            break;
        }
    }
}

if (not_exist('query_view'))
{
    /**
     * @param string $query_action
     * @param Model $model
     * @param Table $instance
     * @param string $create_record_action
     * @param string $update_record_action
     * @param string $create_record_submit_text
     * @param string $update_record_text
     * @param string $current_table_name
     * @param string $expected_placeholder
     * @param string $superior_text
     * @param string $superior_or_equal_text
     * @param string $inferior_text
     * @param string $inferior_or_equal_text
     * @param string $different_text
     * @param string $equal_text
     * @param string $like_text
     * @param string $select_mode_text
     * @param string $remove_mode_text
     * @param string $update_mode_text
     * @param string $submit_query_text
     * @param string $submit_class
     * @param string $remove_success_text
     * @param string $record_not_found_text
     * @param string $table_empty_text
     *
     * @param string $select_where_column_text
     * @param string $select_condition_column_text
     * @param string $select_operation_column_text
     * @param string $select_order_column_text
     * @param $reset_form_text
     * @param string $reset_form_class
     * @param string $icon
     * @param string $csrf
     * @return string
     *
     * @throws Exception
     */
    function query_view(string $query_action,Model $model,Table $instance,string $create_record_action,string $update_record_action,string $create_record_submit_text,string $update_record_text,string $current_table_name,string $expected_placeholder,string $superior_text,string $superior_or_equal_text,string $inferior_text,string $inferior_or_equal_text,string $different_text,string $equal_text,string $like_text,string $select_mode_text,string $remove_mode_text,string $update_mode_text,string $submit_query_text,string $submit_class,string $remove_success_text,string $record_not_found_text,string $table_empty_text,string $select_where_column_text,string $select_condition_column_text,string $select_operation_column_text,string $select_order_column_text,$reset_form_text,$reset_form_class ='btn btn-outline-danger',string $icon  = '<i class="fas fa-heart"></i>',string $csrf = ''): string
    {

        $table = $instance->select($current_table_name);
        $columns = $table->get_columns();


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
                form($query_action,uniqid(),Form::INVALIDATE)->csrf($csrf)->validate()
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
                query_result($model,post('mode'),execute_query($form_grid,$model,$table,post('mode'),post('column'),post('condition'),post('expected'),$current_table_name,$submit_class,$update_record_text,$update_record_action,post('key'),post('order'),$sql),$model->columns(),$remove_success_text,$record_not_found_text,$table_empty_text,$sql)

            :
                form($query_action,uniqid(),Form::INVALIDATE)->csrf($csrf)->validate()
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

                ->end_row()->get() .form($create_record_action,uniqid())->generate($form_grid,$current_table_name,$table,$create_record_submit_text,$submit_class,uniqid()) ;
    }
}

if (not_exist('connect'))
{
    /**
     *
     * Connect to the database
     *
     * @param string $driver
     * @param string $base
     * @param string $user
     * @param string $password
     * @param int $fetch_mode
     *
     * @param string $dump_path
     * @return Connect
     *
     * @throws Exception
     *
     */
    function connect(string $driver,string $base,string $user,string $password,int $fetch_mode,string $dump_path): Connect
    {
        return new Connect($driver,$base,$user,$password,$fetch_mode,$dump_path);
    }
}
if (not_exist('login'))
{
    /**
     * build a form to login user
     *
     * @param string $action
     * @param string $id
     * @param string $name_placeholder
     * @param string $password_placeholder
     * @param string $submit_text
     * @param string $submit_class
     * @param string $submit_id
     * @param string $csrf
     * @param string $submit_icon
     *
     * @param string $user_icon
     * @param string $password_icon
     * @return string
     * @throws Exception
     */
    function login(string $action,string $id,string $name_placeholder,string  $password_placeholder,string $submit_text,string $submit_class,string $submit_id,string $csrf ='',string $submit_icon ='<i class="fas fa-sign-in-alt"></i>',string $user_icon ='<i class="fas fa-user"></i>',string $password_icon ='<i class="fas fa-key"></i>'): string
    {
        return form($action,$id)->csrf($csrf)->row()->input(Form::TEXT,'name',$name_placeholder,$user_icon)->input(Form::PASSWORD,'password',$password_placeholder,$password_icon)->end_row_and_new()->submit($submit_text,$submit_class,$submit_id,$submit_icon)->end_row()->get();
    }
}
if (not_exist('json'))
{
    /**
     * Return an instance to manage json
     *
     * @param string $filename
     *
     * @return Json
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
     * An instance to manage array
     *
     * @param array $data
     *
     * @return Collection
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
     * check if value are defined
     *
     * @param mixed ...$values
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
     * Check if value are not defined
     *
     * @param mixed ...$values
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
     * Get all time zones
     *
     * @param string $select_time_zone_text
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
     * @param Table $instance
     * @param string $url_prefix
     * @param string $current_table_name
     * @param string $choose_text
     * @param bool $use_a_redirect_select
     * @param string $csrf_token_field
     * @param string $separator
     * @param string $icon
     *
     * @return string
     *
     * @throws Exception
     */
    function tables_select(Table $instance, string $url_prefix,string $separator = '/'): string
    {

        $tables = collection();

        foreach ($instance->show() as $x)
        {
            if (different($x,$instance->get_current_table()))
                $tables->merge(["$url_prefix$separator$x" => $x]);
        }
        return  form('',uniqid())->row()->redirect('table',$tables->collection())->end_row()->get() ;
     }
}

if (not_exist('users_select'))
{
    /**
     * build a form to select an user
     *
     * @param Users $instance
     * @param array $hidden
     * @param string $urlPrefix
     * @param string $currentUser
     * @param string $chooseText
     * @param bool $use_a_redirect_select
     * @param string $csrf
     * @param string $separator
     * @param string $icon
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function users_select(Users $instance,array $hidden,string $urlPrefix,string $currentUser,string $chooseText,bool $use_a_redirect_select,string $csrf = '',string $separator = '/',string $icon = '<i class="fas fa-user"></i>'): string
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
     * generate an simply view to manage records
     *
     * @param string $current_table_name
     * @param Table $instance
     * @param array $records
     * @param string $html_table_class
     * @param string $action_remove_text
     * @param string $before_remove_text
     * @param string $remove_button_class
     * @param string $remove_url_prefix
     * @param string $remove_icon
     * @param string $action_edit_text
     * @param string $action_edit_url_prefix
     * @param string $edit_button_class
     * @param string $edit_icon
     * @param string $pagination
     * @param bool $align_column_center
     * @param bool $column_to_upper
     * @param bool $pagination_to_right
     * @return string
     *
     * @throws Exception
     */
    function simply_view(string $current_table_name, Table $instance , array $records  ,string $html_table_class,string $action_remove_text,string $before_remove_text,string $remove_button_class,string $remove_url_prefix,string $remove_icon,string $action_edit_text,string $action_edit_url_prefix,string $edit_button_class,string $edit_icon,string $pagination,bool $align_column_center,bool $column_to_upper,bool $pagination_to_right = true): string
    {
        $instance = $instance->select($current_table_name);

        $columns  = $instance->get_columns();
        $primary  = $instance->get_primary_key();

        $code = '';


        append($code,'<div class="table-responsive mt-4"><table class="'.$html_table_class.'"><thead><tr>');
        append($code,'<script>function sure(e,text){ if (! confirm(text)) {e.preventDefault()} }</script>');

        foreach ($columns as  $x)
        {

            append($code,'<th  class="');
            if ($align_column_center) {  append($code,' text-center'); }

            if ($column_to_upper)   {  append($cpode,' text-uppercase') ; }

            append($code, '">'.$x.'</th>');

        }
        append($code, '<th  class="');

        if ($align_column_center) {  append($code,' text-center'); }

        if ($column_to_upper)   {  append($cpode,' text-uppercase') ; }

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
     * @param Table $instance
     * @param string $current_table_name
     * @param int $current_page
     * @param int $limit_per_page
     * @param Connect $connect
     * @param bool $framework
     * @param string $order_by
     *
     * @return array
     *
     * @throws Exception
     */
    function get_records(Table $instance,string $current_table_name,int $current_page,int $limit_per_page,Connect $connect,bool $framework,string $order_by = 'DESC')
    {


        $instance = $instance->select($current_table_name);

        $key = $instance->get_primary_key();

        $offset = ($limit_per_page * $current_page) - $limit_per_page;


        $sql = sql($current_table_name,query($instance,$connect))->set_query_mode(Query::SELECT);
        if ($framework)
        {
            $parts = explode('/',server('REQUEST_URI'));
            $search = has('search',$parts);
            if ($search)
                $like = end($parts);
            else
                $like = '';

            if (empty($like))
                $records = $sql->limit($limit_per_page, $offset)->order_by($key,$order_by)->get();
            else
                $records = $sql->like($instance, $like)->order_by($key,$order_by)->get();

        }else
        {
            $like = get('search');
            if (empty($like))
                $records = $sql->limit($limit_per_page,$offset)->order_by($key,$order_by)->get();
            else
                $records = $sql->like($instance,$like)->order_by($key,$order_by)->get();
        }

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
                    echo html_entity_decode($x,ENT_QUOTES,'UTF-8');
                }
            }
        }

    }
}
if (not_exist('html'))
{
    /**
     * generate an element between the content
     *
     * @param string $element
     * @param string $content
     * @param string $class
     * @param string $id
     *
     * @return string
     *
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
            case 'code':


                $code = equal($element,'code');

                if ($code)
                    $html = "<pre ";
                else
                    $html = "<$element";

                if (def($class))
                    append($html,' class="'.$class.'"');

                if (def($id))
                    append($html,' id="'.$id.'"');

                if ($code)
                    append($html, '><code>    ' .$content . '</code></pre>');
                else
                    append($html, '>' .$content . '</'.$element.'>');

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
     * generate an id
     *
     * @param string $prefix
     *
     * @return string
     */
    function id(string $prefix =''): string
    {
        return uniqid($prefix);
    }
}
if (not_exist('submit'))
{
    /**
     * verify if a form is submit
     *
     * @param string $key
     * @param bool $post
     * @return bool
     */
    function submit(string $key,bool $post = true): bool
    {
        return $post ?  isset($_POST[$key])   : isset($_GET[$key])  ;
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
     * push one or more elements onto the end of array
     *
     * @param array $array
     * @param  $values
     */
    function push(array &$array,...$values)
    {

        foreach ($values as $value)
            array_push($array,$value);
    }
}

if (not_exist('stack'))
{
    /**
     * push one or more elements onto the end of array
     *
     * @param array $array
     * @param  $values
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

if (not_exist('values'))
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

if (not_exist('merge'))
{
    /**
     * merge two array
     *
     * @param array $array
     * @param array[] $to
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

if (not_exist('cookie'))
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
if (not_exist('get'))
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

if (not_exist('files'))
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
if (not_exist('server'))
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

if (not_exist('post'))
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
if (not_exist('generate'))
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
        return form($action,$formId,$class)->csrf($csrfToken)->generate(1,$table,$instance,$submitText,$submitClass,$submitId,$submitIcon,$mode,$id);
    }
}

if (not_exist('root'))
{
    /**
     * @param string $driver
     * @param string $user
     * @param string $password
     *
     * @param string $dump_path
     * @param int $pdo_mode
     *
     * @return Connect
     *
     * @throws Exception
     */
    function root(string $driver,string $user,string $password = '',string $dump_path = 'dump',$pdo_mode = PDO::FETCH_OBJ): Connect
    {
        return connect($driver,'',$user,$password,$pdo_mode,$dump_path);
    }
}
if (not_exist('collation'))
{
    /**
     * get all collation
     *
     * @param Connect $connexion
     *
     * @return array
     *
     * @throws Exception
     */
    function collation(Connect $connexion): array
    {
        $collation = collection();

        $driver = $connexion->get_driver();
        $request = '';
        equal($driver,Connect::MYSQL) ? assign(true,$request,"SHOW COLLATION") : assign(true,$request,"SELECT collname FROM pg_collation");

        foreach ($connexion->request($request) as $char)
            $collation->push(current($char));

        return $collation->collection();
    }
}
if (not_exist('charset'))
{
    /**
     * get all charset
     *
     * @param Connect $connexion
     *
     * @return array
     *
     * @throws Exception
     */
    function charset(Connect $connexion): array
    {

        $collation = collection();

        $driver = $connexion->get_driver();
        $request = '';
        equal($driver,Connect::MYSQL) ? assign(true,$request,"SHOW CHARACTER SET") : assign(true,$request,"SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1");

        foreach ($connexion->request($request) as $char)
            $collation->push(current($char));

        return $collation->collection();

    }
}

if (not_exist('git'))
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

if (not_exist('current_branch'))
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
    function current_branch(string $repository): string
    {
        return (new GitRepository($repository))->getCurrentBranchName();
    }
}


if (not_exist('base'))
{
    /**
     * manage database
     *
     * @param Connect $connect
     *
     * @param Table $table
     * @return Base
     */
    function base(Connect $connect,Table $table): Base
    {
        return new Base($connect,$table);
    }
}

if (not_exist('user'))
{
    /**
     * manage users
     *
     * @param Connect $connect
     *
     * @return Users
     */
    function user(Connect $connect) : Users
    {
        return new Users($connect);
    }
}

if (not_exist('pass'))
{
    /**
     * update user password
     *
     * @param Connect $connect
     * @param string $username
     * @param string $new_password
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
     * see os
     *
     * @param bool $name
     *
     * @return Os|string
     */
    function os(bool $name = false)
    {
        return $name ?  (new Os())->getName() : new Os();
    }
}

if (not_exist('device'))
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
        return $name ?  (new Device())->getName() : new Device();
    }
}


if (not_exist('browser'))
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
        return $name ?  (new Browser())->getName(): new Browser();
    }
}

if (not_exist('is_browser'))
{
    /**
     * check if is name is browser
     *
     * @param string $name
     *
     * @return bool
     */
    function is_browser(string $name): bool
    {
        return (new Browser())->isBrowser($name);
    }
}

if (not_exist('is_mobile'))
{
    /**
     * check if device is mobile
     *
     * @return bool
     */
    function is_mobile(): bool
    {
        return (new Os())->isMobile();
    }
}

if (not_exist('create'))
{
    /**
     * create a new database or user or table
     *
     * @param Imperium $imperium
     *
     * @return bool
     */
    function create(Imperium $imperium): bool
    {

    }
}

if (not_exist('superior'))
{

    /**
     *
     * check if the var is superior
     * of the expected value
     *
     * @param $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
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
     * check if the var is superior or equal
     * of the expected value
     *
     * @param $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
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
     * check if the var is inferior
     * of the expected value
     *
     * @param $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
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
     * check if the var is inferior or equal
     * of the expected value
     *
     * @param $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function inferior_or_equal($parameter,int $expected,bool $run_exception = false,string $message ='') : bool
    {

        $x = is_array($parameter) ? count($parameter) <= $expected : $parameter <= $expected;

        is_true($x,$run_exception,$message);

        return $x;
    }
}

if(not_exist('show'))
{
    /**
     * show databases, users, tables
     *
     * @param Imperium $imperium
     * @param int $mode
     *
     * @param array $hidden
     * @return array
     *
     * @throws Exception
     */
    function show(Imperium $imperium,int $mode,array $hidden = []) : array
    {
        switch ($mode)
        {
            case Imperium::MODE_ALL_DATABASES:
                return $imperium->show_databases($hidden);
            break;
            case Imperium::MODE_ALL_USERS:
                return $imperium->show_users($hidden);
            break;
            case Imperium::MODE_ALL_TABLES:
                 return $imperium->show_tables($hidden);
            break;
            default:
                return array();
            break;
        }

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
if(not_exist('array_prev'))
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
        return collection($array)->value_before_key($key);
    }

}
if(not_exist('req'))
{
    /**
     * execute a query return an array with results
     *
     * @param Connect $instance
     * @param string $request
     * @return array
     *
     * @throws Exception
     */
    function req(Connect $instance,string $request): array
    {
        return $instance->request($request);
    }
}

if(not_exist('execute'))
{
    /**
     * execute a query return a boolean
     *
     * @param Connect $instance
     * @param string $request
     *
     * @return bool
     *
     * @throws Exception
     */
    function execute(Connect $instance,string $request): bool
    {
        return $instance->execute($request);
    }
}
if (not_exist('db'))
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
     *
     * @throws Exception
     */
    function db(Base $instance,string $base,string $charset ='',string $collation =''): bool
    {
        return $instance->set_collation($collation)->set_charset($charset)->create($base);
    }
}

if (not_exist('drop'))
{
    /**
     * @param $instance
     * @param string ...$to
     *
     * @return bool
     *
     * @throws Exception
     */
    function drop($instance,string ...$to): bool
    {

        $data = collection();

        foreach ($to as $x)
            $data->add($instance->drop($x));

       return $data->not_exist(false);
    }
}


if (not_exist('model'))
{

    /**
     * return an instance of the mode class
     *
     * @param Connect $connect
     * @param Table $table
     * @param string $current_table_name
     *
     * @return Model
     *
     * @throws Exception
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
     * @param Connect $connect
     *
     * @return Table
     *
     * @throws Exception
     */
    function table(Connect $connect): Table
    {
        return new Table($connect);
    }
}

if (not_exist('faker'))
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


if (not_exist('remove_users'))
{
    /**
     * remove users
     *
     * @param Users $user
     * @param string[] $users
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function remove_users(Users $user,string ...$users): bool
    {
        foreach ($users as $x)
            is_not_true($user->drop($x),true,"Failed to remove the user : $x");



        return true;
    }
}

if (not_exist('remove_tables'))
{
    /**
     *
     * Remove tables
     *
     * @param Table $table
     * @param string[] $tables
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function remove_tables(Table $table,string ...$tables): bool
    {
        foreach ($tables as $x)
            is_not_true($table->drop($x),true,"Failed to remove the table : $x");


        return true;
    }
}

if (not_exist('remove_bases'))
{
    /**
     *
     * Remove databases
     *
     * @param Base $base
     * @param string[] $databases
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
    function form(string $action, string $id, string $class = '',string $method = Form::POST, bool $enctype = false,  string $charset = 'utf8')
    {
        return Form::create()->start($action,$id,$class,$enctype,strtolower($method),$charset);
    }
}

if (not_exist('d'))
{
    /**
     * debug values
     *
     * @param mixed ...$values
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
     * Check if the value is not inside an array
     *
     * @param array $array
     * @param $value
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function not_in(array $array, $value, bool $run_exception = false, string $message = '')
    {
        $x =  !in_array($value,$array,true);

        is_true($x,$run_exception,$message);

        return $x;
    }
}


if (not_exist('dumper'))
{

    /**
     *
     * dump a table or a database
     *
     * @param Connect $connect
     * @param bool $base
     * @param string $table
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function dumper(Connect $connect,bool $base = true,string $table =''): bool
    {

        $database = $connect->get_database();
        $driver = $connect->get_driver();
        $password = $connect->get_password();
        $username = $connect->get_username();
        $dump_path = $connect->get_dump_path();

        Dir::clear($dump_path);

        $filename = $base  ? "$dump_path/$database.sql" : "$dump_path/$table.sql";


        switch ($driver)
        {
            case Connect::MYSQL:
                if ($base)
                    MySql::create()->setDbName($database)->setPassword($password)->setUserName($username)->dumpToFile($filename);
                else
                    MySql::create()->setDbName($database)->setPassword($password)->setUserName($username)->includeTables($table)->dumpToFile($filename);
            break;
            case Connect::POSTGRESQL:
                if ($base)
                    PostgreSql::create()->setDbName($database)->setPassword($password)->setUserName($username)->dumpToFile($filename);
                else
                    PostgreSql::create()->setDbName($database)->setPassword($password)->setUserName($username)->includeTables($table)->dumpToFile($filename);
            break;
            case Connect::SQLITE:
                if ($base)
                    Sqlite::create()->setDbName($database)->dumpToFile($filename);
                else
                    Sqlite::create()->setDbName($database)->includeTables($table)->dumpToFile($filename);
            break;
            default:
                return false;
            break;

        }

        return File::exist($filename);

    }
}

if (not_exist('sql'))
{
    /**
     * sql table builder
     *
     * @param string $table
     *
     * @param Query $query
     * @return Query
     */
    function sql(string $table,Query $query): Query
    {
        return $query->from($table);
    }
}

if (not_exist('lines'))
{
    /**
     * get all lines in filename
     *
     * @param string $filename
     *
     * @return array
     */
     function lines(string $filename): array
     {
         return File::lines($filename);
     }
}
if (not_exist('file_keys'))
{
    /**
     * get all keys in filename
     *
     * @param string $filename
     * @param string $delimiter
     *
     * @return array
     */
    function file_keys(string $filename,string $delimiter): array
    {
        return File::keys($filename,$delimiter);
    }

}

if (not_exist('file_values'))
{
    /**
     * get all values in filename
     *
     * @param string $filename
     * @param string $delimiter
     *
     * @return array
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
     * Add a new user
     *
     * @param Users $users
     * @param string $user
     * @param string $password
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
     * Add new bases
     *
     * @param Base $base
     * @param string $collation
     * @param string $charset
     * @param string[] $bases
     *
     * @return bool
     *
     * @throws Exception
     *
     */
    function add_base(Base $base,string $collation,string $charset,string ...$bases): bool
    {
        foreach ($bases as $x)
             is_not_true($base->set_collation($collation)->set_charset($charset)->create($x),true,"Failed to create database");

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
     * all possibilities of management
     *
     * @param Connect $connect
     * @param string $current_table
     *
     * @return Imperium
     *
     * @throws Exception
     */
    function imperium(Connect $connect,string $current_table): Imperium
    {
        return new  Imperium($connect,$current_table );
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
     * generate a fa icon
     *
     * @param string $prefix
     * @param string $icon
     * @param string $options
     *
     * @return string
     */
    function fa(string $prefix,string $icon, string $options = ''): string
    {
        return '<i class="'.$prefix.'  '.$icon.' '.$options.'" ></i>';
    }
}

if (not_exist('cssLoader'))
{
    /**
     * load a css files
     *
     * @param string[] $urls
     *
     * @return string
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
     *
     * Append to variable contents
     *
     * @param $variable
     * @param mixed ...$contents
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
     * load a js files
     *
     * @param string[] $urls
     *
     * @return string
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

if (not_exist('postgresql_loaded'))
{
    /**
     * check if mysql is loaded
     *
     * @return bool
     */
    function postgresql_loaded(): bool
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