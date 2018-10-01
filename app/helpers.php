<?php


use Carbon\Carbon;
use Cz\Git\GitRepository;
use Imperium\Bases\Base;
use Imperium\Collection\Collection;
use Imperium\Connexion\Connect;
use Imperium\Debug\Dumper;
use Imperium\Directory\Dir;
use Imperium\Dump\MySql;
use Imperium\Dump\PostgreSql;
use Imperium\Dump\Sqlite;
use Imperium\File\File;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Form\Form;
use Imperium\Html\Pagination\Pagination;
use Imperium\Html\Records\Records;
use Imperium\Imperium;
use Imperium\Json\Json;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Tables\Table;
use Imperium\Users\Users;
use Intervention\Image\ImageManager;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

if (!exist('instance'))
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
        $connexion          = connect($driver,$base,$user,$password,$fetch_mode,$dump_path);
        return imperium($connexion,$current_table);
    }
}

if (!exist(''))
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
if (!exist('query'))
{
    function query(Table $table,Connect $connect): Query
    {
        return new Query($table,$connect);
    }
}

if (!exist('is_pair'))
{
    /**
     * @param int $x
     *
     * @return bool
     */
    function is_pair(int $x): bool
    {
        return $x % 2 === 0;
    }
}
if (!exist('equal'))
{
    /**
     * test if two variables are equal
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function equal( $parameter, $expected,$run_exception = false,string $message = ''): bool
    {
        $x = strcmp($parameter,$expected) === 0;

        if ($run_exception)
            if ($x)
                throw new Exception($message);


        return $x;
    }
}
if (!exist('is_not_false'))
{
    /**
     *
     * check if a data is not equal to false
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
        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);

        }
        return $x;
    }
}

if (!exist('is_not_true'))
{
    /**
     * check if a data is not equal to false
     *
     * @param $data
     *
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function is_not_true($data,bool $run_exception = false,string $message =''): bool
    {
        $x =  $data !== true;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}

if (!exist('is_false'))
{
    /**
     * check if a data is not equal to false
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
    function is_false($data,bool $run_exception = false,string $message =''): bool
    {
        $x = $data === false;
        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}

if (!exist('is_true'))
{
    /**
     *
     * check if a data is not equal to false
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
    function is_true($data,bool $run_exception = false,string $message =''): bool
    {
        $x =  $data === true;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}

if (!exist('different'))
{
    /**
     * test  if two variables are different
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function different($parameter,$expected,$run_exception = false,string $message = ''): bool
    {
        $x = strcmp($parameter,$expected) !== 0;

        if ($run_exception)
            if ($x)
                throw new Exception($message);


        return $x;
    }
}
if (!exist('dd'))
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
    function dd(bool $condition,...$values)
    {
        if ($condition)
        {
           d($values);
        }
    }
}
if (!exist('register'))
{

    /**
     * generate a register form
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
     */
    function register(string $action,string $valid_ip,string $current_ip,string $username_placeholder,string $username_success_text,string $username_error_text,string $email_placeholder,string $email_success_text,$email_error_text,string $password_placeholder,string $password_valid_text,string $password_invalid_text,string $confirm_password_placeholder,string $submit_text,string $submit_id,bool $multiple_languages = false,array $supported_languages =[],string $choose_language_text = '',string $choose_language_valid_text ='',string $choose_language_invalid_text = '',string $select_time_zone_text ='',string $valid_time_zone_text= '',string $time_zone_invalid_text = '',string $csrf_token_field = '',string $submit_button_class = 'btn btn-outline-primary',string $password_icon = '<i class="fas fa-key"></i>',string $username_icon = '<i class="fas fa-user"></i>',string $email_icon = '<i class="fas fa-envelope"></i>',string $submit_icon = '<i class="fas fa-user-plus"></i>',string $time_zone_icon = '<i class="fas fa-clock"></i>',string $lang_icon = '<i class="fas fa-globe"></i>')
    {

        $languages = collection(array('' => $choose_language_text));

        foreach ($supported_languages as $k => $v)
            $languages->merge([$k => $v]);

        if (equal($valid_ip,$current_ip))
        {
            $form = form($action,'register-form','was-validated ')->csrf($csrf_token_field)->validate();

            if ($multiple_languages)
                $form->row()->select('locale',$languages->collection(),$choose_language_valid_text,$choose_language_invalid_text,$lang_icon)->select('zone',zones($select_time_zone_text),$valid_time_zone_text,$time_zone_invalid_text,$time_zone_icon)->end_row();

           return   $form->row()->input(Form::TEXT,'name',$username_placeholder,$username_success_text,$username_error_text,$username_icon,post('name'),true)->input(Form::EMAIL,'email',$email_placeholder,$email_success_text,$email_error_text,$email_icon,post('email'),true)->end_row_and_new()
                ->input(Form::PASSWORD,'password',$password_placeholder,$password_valid_text,$password_invalid_text,$password_icon,post('password'),true)->input(Form::PASSWORD,'password_confirmation',$confirm_password_placeholder,$password_valid_text,$password_invalid_text,  $password_icon,post('password_confirmation'),true)->end_row_and_new()
                ->submit($submit_text,$submit_button_class,$submit_id,$submit_icon)->end_row()->get();

        }
        return '';
    }
}



if (!exist('bases_to_json'))
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
        $json = json($filename);
        return def($key) ? $json->create([$key => $base->show()]) : $json->create($base->show());

    }
}

if (!exist('users_to_json'))
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
        $json = json($filename);

        return def($key) ? $json->create([$key => $users->show()]) : $json->create($users->show());
    }
}

if (!exist('tables_to_json'))
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
        $json = json($filename);

        return def($key) ? $json->create([$key => $table->show()]) : $json->create($table->show());
    }
}

if (!exist('sql_to_json'))
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
    function sql_to_json(Connect $connect,string $query,$filename) : bool
    {
        $json = json($filename);
        return  $json->create($connect->request($query));
    }
}


if (!exist('query_result'))
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
     * @return string
     *
     * @throws Exception
     */
    function query_result(Model $model,$mode,$data,array $columns,$success_text,$result_empty_text,$table_empty_text): string
    {
        if (equal($mode,Imperium::UPDATE))
        {
            $code = '';
            foreach ($data as $datum)
                append($code,$datum);

            return $code;
        }
        if (is_bool($data) && $data)
           return html('div',$success_text,'alert alert-success');
        elseif(empty($model->all()))
            return html('div',$table_empty_text,'alert alert-danger');
        else
           return  empty($data) ? html('div',$result_empty_text,'alert alert-danger') : collection($data)->print(true,$columns);

    }
}

if (!exist('length'))
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
        if (is_numeric($data) || is_null($data) || is_bool($data))
            throw new Exception('The parameter must be a string or an array');

        return is_array($data) ? count($data) : strlen($data);
    }
}
if (!exist('execute_query'))
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
     * @return array|bool
     *
     * @throws Exception
     */
    function execute_query(int $form_grid,Model $model,Table $table,$mode,string $column_name,string $condition,$expected,string $current_table_name,string $submit_class,$submit_update_text,string $form_update_action )
    {

        switch ($mode)
        {
            case Query::UPDATE:
                $code = collection();
                foreach ( $model->query()->set_query_mode(Query::SELECT)->where($column_name,$condition,$expected)->get()  as $record)
                {
                    $id = $table->set_current_table($current_table_name)->get_primary_key();

                    $code->push(form($form_update_action,id())->generate($form_grid,$current_table_name,$table,$submit_update_text,$submit_class,uniqid($current_table_name),'',Form::EDIT,$record->$id));
                }
                return $code->collection();
            break;
            case Query::DELETE:
            
                $data = $model->where($column_name,$condition,$expected);
                return empty($data) ? $data :  $model->query()->set_query_mode($mode)->where($column_name, $condition, $expected)->delete() ;
            break;
            default:
               return $model->query()->set_query_mode(Query::SELECT)->where($column_name,$condition,$expected)->get();
            break;
        }
    }
}

if (!exist('query_view'))
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
     * @return string
     *
     * @throws Exception
     */
    function query_view(string $query_action,Model $model,Table $instance,string $create_record_action,string $update_record_action,string $create_record_submit_text,string $update_record_text,string $current_table_name,string $expected_placeholder,string $superior_text,string $superior_or_equal_text,string $inferior_text,string $inferior_or_equal_text,string $different_text,string $equal_text,string $like_text,string $select_mode_text,string $remove_mode_text,string $update_mode_text,string $submit_query_text,string $submit_class,string $remove_success_text,string $record_not_found_text,string $table_empty_text): string
    {
        $table = $instance->set_current_table($current_table_name);
        $columns = $table->get_columns();
      
        $i = count($columns);

        equal(0,$i % 2) ?  $form_grid =  2 :  $form_grid =  3;

        $condition = array('=' => $equal_text,'!=' => $different_text,'<' => $inferior_text,'>' => $superior_text,'<=' => $inferior_or_equal_text,'>=' =>$superior_or_equal_text,'LIKE' => $like_text);

        return post('mode') ?  form($query_action,uniqid())->row()->select('column',$columns)->select('condition',$condition)->end_row_and_new()->input(Form::TEXT,'expected',$expected_placeholder)->select('mode',[Imperium::SELECT=> $select_mode_text,Imperium::DELETE=> $remove_mode_text,'UPDATE' => $update_mode_text])->end_row_and_new()->submit($submit_query_text,$submit_class,uniqid())->end_row()->get() . query_result($model,post('mode'),execute_query($form_grid,$model,$table,post('mode'),post('column'),post('condition'),post('expected'),$current_table_name,$submit_class,$update_record_text,$update_record_action),$model->columns(),$remove_success_text,$record_not_found_text,$table_empty_text) : form($query_action,uniqid())->row()->select('column',$columns)->select('condition',$condition)->end_row_and_new()->input(Form::TEXT,'expected',$expected_placeholder)->select('mode',[Imperium::SELECT=> $select_mode_text,Imperium::DELETE=> $remove_mode_text,'UPDATE' => $update_mode_text])->end_row_and_new()->submit($submit_query_text,$submit_class,uniqid())->end_row()->get() .form($create_record_action,uniqid())->generate($form_grid,$current_table_name,$table,$create_record_submit_text,$submit_class,uniqid()) ;
    }
}

if (!exist('connect'))
{
    /**
     * @param string $driver
     * @param string $base
     * @param string $user
     * @param string $password
     * @param int $fetch_mode
     *
     * @param string $dump_path
     * @return Connect
     */
    function connect(string $driver,string $base,string $user,string $password,int $fetch_mode,string $dump_path): Connect
    {
        return new Connect($driver,$base,$user,$password,$fetch_mode,$dump_path);
    }
}
if (!exist('login'))
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
if (!exist('json'))
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
if(!exist('collection'))
{

    /**
     *
     * Get an instance of collection
     *
     * @param array $data
     *
     * @return Collection
     *
     */
    function collection(array $data = []): Collection
    {
        return new Collection($data);
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

if(!exist('not_def'))
{
    /**
     * check if value are not defined
     *
     * @param mixed ...$values
     *
     * @return bool
     */
    function not_def(...$values): bool
    {

       foreach ($values as $value)
           if (def($value))
               return false;

       return true;
    }
}

if (!exist('zones'))
{
    /**
     * get all time zone
     *
     * @param string $select_time_zone_text
     *
     * @return array
     */
    function zones(string $select_time_zone_text) : array
    {
        $zones = collection(array('' => $select_time_zone_text));

        foreach (DateTimeZone::listIdentifiers() as $x)
            $zones->merge([$x => $x]);

        return $zones->collection();
    }
}

if (!exist('web'))
{
    /**
     * @param string $tab_name_for_manage_table
     * @param string $tab_name_for_manage_users
     * @param string $tab_name_for_manage_database
     * @param string $query_view_html
     * @param string $select_table_view
     * @param string $records_table_view
     * @param string $select_user_view
     * @param string $select_base_view
     * @param string $manage_database_view
     * @return string
     *
     * @throws Exception
     */
    function web(string $tab_name_for_manage_table,string $tab_name_for_manage_users,string $tab_name_for_manage_database,string $query_view_html,string $select_table_view,string $records_table_view,string $select_user_view,string $select_base_view,string $manage_database_view): string
    {
        $tables_management = '';
        $users_management = '';
        $base_management = '';

        $class ='mt-5 mb-5';
        append($tables_management,html('div',$select_table_view,$class,'select_table'),html('div',$query_view_html,$class,'query'),html('div',$records_table_view,$class));

        append($users_management,html('div',$select_user_view,$class));
        append($base_management,html('div',$select_base_view,$class),html('div',$manage_database_view,$class));

        $code = ' <ul class="nav nav-tabs mt-5 mb-5" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="'.$tab_name_for_manage_table.'-tab" data-toggle="tab" href="#'.$tab_name_for_manage_table.'" role="tab" aria-controls="'.$tab_name_for_manage_table.'" aria-selected="false">'.$tab_name_for_manage_table.'</a>
                    </li>   
                     <li class="nav-item">
                        <a class="nav-link" id="'.$tab_name_for_manage_users.'-tab" data-toggle="tab" href="#'.$tab_name_for_manage_users.'" role="tab" aria-controls="'.$tab_name_for_manage_users.'" aria-selected="false">'.$tab_name_for_manage_users.'</a>
                    </li>    
                    <li class="nav-item">
                        <a class="nav-link" id="'.$tab_name_for_manage_database.'-tab" data-toggle="tab" href="#'.$tab_name_for_manage_database.'" role="tab" aria-controls="'.$tab_name_for_manage_database.'" aria-selected="false">'.$tab_name_for_manage_database.'</a>
                    </li>    
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="'.$tab_name_for_manage_table.'" role="tabpanel" aria-labelledby="'.$tab_name_for_manage_table.'-tab">'.$tables_management.'</div>
                  <div class="tab-pane fade" id="'.$tab_name_for_manage_users.'" role="tabpanel" aria-labelledby="'.$tab_name_for_manage_users.'-tab">'.$users_management.'</div>
                  <div class="tab-pane fade" id="'.$tab_name_for_manage_database.'" role="tabpanel" aria-labelledby="'.$tab_name_for_manage_database.'-tab">'.$base_management.'</div>
                </div>';
        append($code,bootstrap_js());
        return $code;
    }
}

if (!exist('records'))
{

    /**
     * @param string $html_table_class
     * @param Table $instance
     * @param string $current_table_name
     * @param string $edit_url_prefix
     * @param string $remove_url_prefix
     * @param string $action_edit_text
     * @param string $action_remove_text
     * @param string $edit_button_class
     * @param string $remove_button_class
     * @param string $edit_icon
     * @param string $remove_icon
     * @param int $limit_records_per_page
     * @param int $current_page
     * @param string $pagination_prefix_url
     * @param Connect $connect
     * @param string $action_save_text
     * @param string $confirm_before_remove_text
     * @param string $start_pagination_text
     * @param string $end_pagination_text
     * @param string $advanced_view_tab_text
     * @param string $simply_view_tab_text
     * @param string $form_prefix_url
     * @param string $table_view_bab_text
     * @param string $table_url_prefix
     * @param string $choose_text
     * @param bool $align_column_center
     * @param bool $column_to_upper
     * @param string $csrf_token_field
     * @param bool $pagination_to_right
     * @param bool $framework
     * @param bool $advanced_view_default
     * @param string $url_separator
     * @param int $textarea_row
     * @param string $table_icon
     * @param string $order_by
     *
     * @return string
     *
     * @throws Exception
     */
    function records(
        string $html_table_class, Table $instance, string $current_table_name,
        string $edit_url_prefix, string $remove_url_prefix, string $action_edit_text,
        string $action_remove_text, string $edit_button_class, string $remove_button_class,
        string $edit_icon, string $remove_icon, int $limit_records_per_page, int $current_page,
        string $pagination_prefix_url, Connect $connect, string $action_save_text,
        string $confirm_before_remove_text, string $start_pagination_text,
        string $end_pagination_text, string $advanced_view_tab_text, string $simply_view_tab_text,
        string $form_prefix_url,string $table_view_bab_text,string $table_url_prefix,string $choose_text,bool $align_column_center,
        bool $column_to_upper,string $csrf_token_field = '', bool $pagination_to_right = true, bool $framework = false,
        bool $advanced_view_default = false,  string $url_separator = '/',int $textarea_row = 1, string $table_icon ='<i class="fas fa-table"></i>',string $order_by = 'desc'): string
    {
       return Records::show( $html_table_class,$instance, $current_table_name,
                            $edit_url_prefix, $remove_url_prefix,$action_edit_text,
                            $action_remove_text , $edit_button_class,   $remove_button_class,
                            $edit_icon,$remove_icon, $limit_records_per_page,   $current_page,
                            $pagination_prefix_url, $connect,   $action_save_text,
                            $confirm_before_remove_text,   $start_pagination_text,
                            $end_pagination_text,   $advanced_view_tab_text,   $simply_view_tab_text,
                            $form_prefix_url,  $table_view_bab_text,  $table_url_prefix, $choose_text,$align_column_center,
                            $column_to_upper,  $csrf_token_field ,$pagination_to_right , $framework,
                            $advanced_view_default ,  $url_separator , $textarea_row ,$table_icon ,  $order_by);
    }
}


if (!exist('tables_select'))
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
    function tables_select(Table $instance, string $url_prefix, string $current_table_name, string $choose_text, bool $use_a_redirect_select, string $csrf_token_field = '', string $separator = '/', string $icon = '<i class="fas fa-table"></i>'): string
    {

        $tables = collection(array('' => $choose_text));

        foreach ($instance->show() as $x)
        {
            if (different($x,$current_table_name))
                $tables->merge(["$url_prefix$separator$x" => $x]);

        }

        return $use_a_redirect_select ? form('',uniqid())->row()->csrf($csrf_token_field)->redirect('table',$tables->collection(),$icon)->end_row()->get() : form('',uniqid())->csrf($csrf_token_field)->row()->select('table',$tables->collection(),$icon)->end_row()->get();
     }
}

if (!exist('users_select'))
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


if (!exist('bases_select'))
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

if (!exist('simply_view'))
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
        $instance = $instance->set_current_table($current_table_name);

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



if (!exist('tables_view'))
{
    /**
    * generate alter table view
    *
    * @param Table $instance
    *
    * @return string
    */
    function tables_view( Table $instance): string
    {
        return '';
    }

}

if (!exist('users_view'))
{
    /**
     * generate a view to manage users
     *
     * @param Users $instance
     *
     * @return string
     */
    function users_view(Users $instance): string
    {
        return '';
    }

}
if (!exist('advanced_view'))
{
    /**
     * @param string $current_table
     * @param Table $instance
     * @param array $records
     * @param string $form_action
     * @param string $select_table_code
     * @param string $action_save_text
     * @param string $action_edit_text
     * @param string $edit_text_class
     * @param string $remove_url_prefix
     * @param string $remove_button_class
     * @param string $remove_text
     * @param string $text_before_remove
     * @param string $pagination
     * @param bool $align_column_center
     * @param bool $column_to_upper
     * @param bool $pagination_to_right
     * @param string $csrf_token_field
     * @param int $textarea_row
     *
     * @return string
     *
     * @throws Exception
     */
    function advanced_view(string $current_table, Table $instance,array $records,string $form_action,string $select_table_code,string $action_save_text,string $action_edit_text,string $edit_text_class,string $remove_url_prefix,string $remove_button_class,string $remove_text,string $text_before_remove,string $pagination,bool $align_column_center,bool $column_to_upper,bool $pagination_to_right,string $csrf_token_field ='',int  $textarea_row =  1): string
    {

        $instance = $instance->set_current_table($current_table);
        $types    = $instance->get_columns_types();
        $columns  = $instance->get_columns();
        $primary  = $instance->get_primary_key();

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




        $code = '';

        append($code,html('div',$select_table_code,'mt-5 mb-5'));

        append($code, '<script type="text/javascript">function sure(e,text){ if (!confirm(text)) {e.preventDefault()} }function edit(element)  { const btn = $(element);  const tr = btn.parent().parent();   const id = btn.attr("data-form-id"); if (btn.text() !== btn.attr("data-edit")){  $("#"+id).submit(); }  if (btn.text() === btn.attr("data-edit")){ btn.text(btn.attr("data-save"))}else{btn.text(btn.attr("data-edit"))} tr.find("DIV.td span").each(function(){  $(this).toggleClass("d-none"); });  tr.keypress(function(e) { if(e.which === 13) { $("#"+id).submit();  } }); }</script>');

        append( $code ,' <div class="table-responsive"><div class="table"><div class="thead"><div class="tr">');

        foreach ($columns as $column)
        {
            if (different($column,$primary))
            {
                append($code,' <div class="td ');
                if ($column_to_upper)
                    append($code,' text-uppercase');

                if ($align_column_center)
                    append($code,' text-center');

                append($code,'">'.$column.'</div>');
            }
        }

        append($code,'<div class="td">'.$action_edit_text.'</div><div class="td">'.$remove_text.'</div></div></div> <div class="tbody">');


        foreach ($records as $record)
        {
            $id = uniqid().sha1($current_table.md5($record->$primary));

           append($code,'<form class="tr" id="'.$id.'" method="post" action="'.$form_action.'">'.$csrf_token_field.'');

            foreach ($columns as $k => $column)
            {
                $type = $types[$k];

                if (is_null($record->$column))
                    $record->$column = '';

                if(different($column,$primary))
                {
                    $type = explode('(',$type);
                    $type = $type[0];

                    switch ($type)
                    {
                        case has($type,$number):
                           append($code , '<div class="td"><span class="d-none td-input"><input type="number" name="'.$column.'" class="form-control form-control-lg" value="'.$record->$column.'"></span> <span class="record"> '.$record->$column.'</span></div>');
                        break;
                        case has($type,$date):
                            append($code ,'<div class="td"><span class="d-none td-input"><input type="datetime" name="'.$column.'" class="form-control form-control-lg" value="'.$record->$column.'"></span> <span class="record"> '.$record->$column.'</span></div>');
                        break;
                        default:
                            append($code,'<div class="td"><span class="d-none td-input"><textarea name="'.$column.'" name="'.$column.'"  class="form-control form-control-lg"  rows="'.$textarea_row.'">'.$record->$column.'</textarea></span> <span class="record"> '.$record->$column.'</span></div>');
                        break;
                    }
                } else {
                   append( $code,'  <div class="td d-none"><input name="'.$primary.'"  value="'.$record->$primary.'"></div>  <div class="td d-none"><input name="table"  value="'.$current_table.'"></div>');

                }
            }

            append($code,'<div class="td action btn-group"><button type="button" onclick="edit(this);" class="'.$edit_text_class.'" data-form-id="'.$id.'" data-edit="'.$action_edit_text.'" data-save="'.$action_save_text.'" >'.$action_edit_text.'</button> </div><div class="td  remove btn-group"><a href="'.$remove_url_prefix.'/'.  $record->$primary.'" onclick="sure(event,this.attributes[2].value)"  data-confirm="'.$text_before_remove.'" class="'.$remove_button_class.'" data-form-id="'.$id.'">'.$remove_text.'</a> </div></form>');
        }

        append($code, '</div></div></div>');
        if ($pagination_to_right)
            append($code ,    '<div class="row"><div class="ml-auto mt-5 mb-5">'.$pagination.'</div></div>');
        else
            append($code ,    '<div class="row"><div class="mr-auto mt-5 mb-5">'.$pagination.'</div></div>');

        return $code;
    }
}
if (!exist('get_records'))
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


        $instance = $instance->set_current_table($current_table_name);

        $key = $instance->get_primary_key();

        $offset = ($limit_per_page * $current_page) - $limit_per_page;


        if ($framework)
        {
            $parts = explode('/',server('REQUEST_URI'));
            $search = has('search',$parts);
            if ($search)
                $like = end($parts);
            else
                $like = '';

            if (empty($like))
                $records = sql($current_table_name,query($instance,$connect))->connect($connect)->limit($limit_per_page, $offset)->order_by($key,$order_by)->get();
            else
                $records = sql($current_table_name,\query($instance,$connect))->connect($connect)->like($instance, $like)->order_by($key,$order_by)->get();

        }else
        {
            $like = get('search');
            if (empty($like))
                $records = sql($current_table_name,query($instance,$connect))->connect($connect)->limit($limit_per_page,$offset)->order_by($key,$order_by)->get();
            else
                $records = sql($current_table_name,query($instance,$connect))->connect($connect)->like($instance,$like)->order_by($key,$order_by)->get();
        }

        return $records;
    }
}


if (!exist('bootstrap_js'))
{
    /**
     * @return string
     */
    function bootstrap_js(): string
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
                    echo html_entity_decode($x,ENT_QUOTES,'UTF-8');
                }
            }
        }

    }
}
if (!exist('html'))
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


if (!exist('id'))
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
if (!exist('submit'))
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
        if (equal($theme,"bootstrap"))
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
     * @param  $values
     */
    function push(array &$array,...$values)
    {

        foreach ($values as $value)
            array_push($array,$value);
    }
}

if (!exist('stack'))
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
     * @param array[] $to
     */
    function merge(array &$array,array ...$to)
    {
        foreach ($to as $item)
            $array = array_merge($array,$item);
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
        return form($action,$formId,$class)->csrf($csrfToken)->generate(1,$table,$instance,$submitText,$submitClass,$submitId,$submitIcon,$mode,$id);
    }
}

if (!exist('root'))
{
    /**
     * @param string $driver
     * @param string $user
     * @param string $password
     *
     * @param string $dump_path
     * @param int $pdo_mode
     * @return Connect
     *
     */
    function root(string $driver,string $user,string $password = '',string $dump_path = 'dump',$pdo_mode = PDO::FETCH_OBJ): Connect
    {
        return connect($driver,'',$user,$password,$pdo_mode,$dump_path);
    }
}
if (!exist('collation'))
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
if (!exist('charset'))
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

if (!exist('current_branch'))
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


if (!exist('base'))
{
    /**
     * manage database
     *
     * @param Connect $connect
     *
     * @return Base
     */
    function base(Connect $connect): Base
    {
        return new Base($connect);
    }
}

if (!exist('user'))
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

if (!exist('pass'))
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
        return $name ?  (new Os())->getName() : new Os();
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
        return $name ?  (new Device())->getName() : new Device();
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
        return $name ?  (new Browser())->getName(): new Browser();
    }
}

if (!exist('is_browser'))
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

if (!exist('is_mobile'))
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

if (!exist('create'))
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

if (!exist('superior'))
{

    /**
     *
     * check if the var is superior
     * of the expected value
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function superior($parameter,$expected,bool $run_exception = false,string $message ='') : bool
    {
        if (is_array($parameter))
            $parameter = count($parameter);

        $x = $parameter > $expected;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}

if (!exist('superior_or_equal'))
{

    /**
     *
     * check if the var is superior or equal
     * of the expected value
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function superior_or_equal($parameter,$expected,bool $run_exception = false,string $message ='') : bool
    {
        if (is_array($parameter))
            $parameter = count($parameter);

        $x = $parameter >= $expected;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}
if (!exist('inferior'))
{

    /**
     *
     * check if the var is inferior
     * of the expected value
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function inferior($parameter,$expected,bool $run_exception = false,string $message ='') : bool
    {
        if (is_array($parameter))
            $parameter = count($parameter);

        $x = $parameter < $expected;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
        return $x;
    }
}

if (!exist('inferior_or_equal'))
{

    /**
     *
     * check if the var is inferior or equal
     * of the expected value
     *
     * @param $parameter
     * @param $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Exception
     */
    function inferior_or_equal($parameter,$expected,bool $run_exception = false,string $message ='') : bool
    {
        if (is_array($parameter))
            $parameter = count($parameter);

        $x = $parameter <= $expected;

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);
        }
     return $x;
    }
}
if (!exist('databases_view'))
{
    /**
     * @param Imperium $imperium
     * @param $create_database_action
     * @param string $drop_database_action
     * @param $name_of_database_placeholder
     * @param $create_database_submit
     * @param string $drop_database_submit_text
     *
     * @return string
     *
     * @throws Exception
     */
    function databases_view(Imperium $imperium,$create_database_action,string $drop_database_action,$name_of_database_placeholder,$create_database_submit,string $drop_database_submit_text): string
    {
        $code = '';

        append($code,html('div',form($create_database_action,uniqid())->row()->select('collation',collation($imperium->connect()))->select('charset',charset($imperium->connect()))->end_rowAndNew()->input(Form::TEXT,'name',$name_of_database_placeholder)->end_rowAndNew()->submit($create_database_submit,$imperium->class(),uniqid())->get(),'mt-5 mb-5'));
        append($code,html('div',form($drop_database_action,uniqid())->row()->select('database',$imperium->show_databases())->end_rowAndNew()->submit($drop_database_submit_text,$imperium->class(false),uniqid())->get(),'mt-5 mb-5'));

        return $code;
    }
}

if (!exist('remove'))
{
    /**
     * remove a database , user or table
     *
     * @param Imperium $imperium
     *
     * @return bool
     */
    function remove( Imperium $imperium): bool
    {

    }
}

if (!exist('remove_view'))
{
    /**
     * create a view to create  database, user or table
     *
     * @param Imperium $imperium
     *
     * @return bool
     */
    function remove_view(Imperium $imperium): bool
    {

    }
}

if(!exist('show'))
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

if(!exist('whoops'))
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

        return $collection->value_before_key($key);
    }

}
if(!exist('req'))
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

if(!exist('execute'))
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
     *
     * @throws Exception
     */
    function db(Base $instance,string $base,string $charset ='',string $collation =''): bool
    {
        return $instance->set_collation($collation)->set_charset($charset)->create($base);
    }
}

if (!exist('drop'))
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
     * @param Connect $connect
     * @param Table $table
     * @param string $current_table_name
     * @param string $order_by
     *
     * @return Model
     *
     * @throws Exception
     */
    function model(Connect $connect,Table $table, string $current_table_name,string $order_by = 'desc'): Model
    {
        return new Model($connect,$table,$current_table_name,$order_by);
    }
}
if (!exist('table'))
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


if (!exist('remove_users'))
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

if (!exist('remove_tables'))
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

if (!exist('remove_bases'))
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
    function form(string $action, string $id, string $class = '',string $method = Form::POST, bool $enctype = false,  string $charset = 'utf8')
    {
        return Form::create()->start($action,$id,$class,$enctype,strtolower($method),$charset);
    }
}

if (!exist('d'))
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

if (!exist('not_in'))
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

        if ($run_exception)
        {
            if ($x)
                throw new Exception($message);

        }
        return $x;
    }
}


if (!exist('dumper'))
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

        return true;

    }
}

if (!exist('sql'))
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
        return $query->set_current_table_name($table);
    }
}

if (!exist('lines'))
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
         return File::getLines($filename);
     }
}
if (!exist('file_keys'))
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
        return File::getKeys($filename,$delimiter);
    }

}

if (!exist('file_values'))
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
        return File::getValues($filename,$delimiter);
    }

}


if (!exist('pagination'))
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
if (!exist('add_user'))
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

if (!exist('add_base'))
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

if (!exist('imperium'))
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


if(!exist('fa'))
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

if (!exist('cssLoader'))
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

if (!exist('append'))
{
    function append(&$variable,...$contents)
    {
        foreach ($contents as $content)
            $variable .= $content;

    }
}


if (!exist('js_loader'))
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
        return equal($type ,'svg') ? '<svg-icon><src href="'.$icon.'"/></svg-icon>' :  '<img src="'.$icon.'"/>';
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
     *
     * @return Carbon
     *
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

if (!exist('postgresql_loaded'))
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