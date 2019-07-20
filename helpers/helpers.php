<?php

use DI\DependencyException;
use DI\NotFoundException;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Config\Config;
use Imperium\Container\Container;
use Imperium\Debug\Dumper;
use Imperium\Directory\Dir;
use Imperium\Dump\Dump;
use Imperium\Exception\Kedavra;
use Imperium\Flash\Flash;
use Imperium\Security\Csrf\Csrf;
use Imperium\Security\Hashing\Hash;
use Imperium\Trans\Trans;
use Imperium\Versioning\Git\Git;
use Imperium\View\View;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Whoops\Run;
use Carbon\Carbon;
use Imperium\App;
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


define('GET','GET');
define('POST','POST');

define('BEFORE_ACTION','before_action');
define('AFTER_ACTION','after_action');

define('GIT_PERIOD',['minute','minutes','day','days','week','weeks','month','months','year','years']);
define('GIT_SIZE',[1,2,3,4,5,6,7,8,9,10,11,12]);
define('GIT_ARCHIVE_EXT',['tar','tgz','tar.gz','zip']);
define('LANGUAGES',['1c','abnf','accesslog','actionscript','ada','angelscript','apache','applescript','arcade','arduino',
                    'armasm','asciidoc','aspectj','autohotkey','autoit','avrasm','awk','axapta','bash','basic','bnf','brainfuck',
                    'cal','capnproto','ceylon','clean','clojure-repl','clojure','cmake','coffeescript','coq','cos','cpp','crmsh','c',
                    'crystal','cs','csp','css','d','dart','delphi','diff','django','dns','dockerfile','dos','dsconfig','dts','dust',
                    'ebnf','elixir','elm','erb','erlang-repl','erlang','excel','fix','flix','fortran','fsharp','gams','gauss','gcode',
                    'gherkin','glsl','gml','go','golo','gradle','groovy','haml','handlebars','haskell','haxe','hsp','htmlbars','http',
                    'hy','inform7','ini','irpf90','isbl','java','javascript','jboss-cli','json','julia-repl','julia','kotlin','lasso',
                    'ldif','leaf','less','lisp','list','livecodeserver','livescript','llvm','lsl','lua','makefile','markdown','mathematica','matlab',
                    'maxima','mel','mercury','mipsasm','mizar','mojolicious','monkey','moonscript','n1ql','nginx','nimrod','nix','nsis','objectivec',
                    'ocaml','openscad','oxygene','parser3','perl','pf','pgsql','php','plaintext','pony','powershell','processing','profile',
                    'prolog','properties','protobuf','puppet','purebasic','python','q','qml','r','reasonml','rib','roboconf','routeros','rsl',
                    'ruby','ruleslanguage','rust','sas','scala','scheme','scilab','scss','shell','smali','smalltalk','sml','sqf','sql','stan',
                    'stata','step21','stylus','subunit','swift','taggerscript','tap','tcl','tex','thrift','tp','twig','typescript','vala','vbnet',
                    'vbscript-html','vbscript','verilog','vhdl','vim','x86asm','xl','xml','xquery','yaml','zephir']);



define('DROP_NEW_LINE',1);
define('READ_AHEAD',2);
define('SKIP_EMPTY',4);
define('READ_CSV',8);
define('READ_FILE_MODE','r');
define('READ_AND_WRITE_FILE_MODE','r+');
define('EMPTY_AND_WRITE_FILE_MODE','w');
define('EMPTY_READ_AND_WRITE_FILE_MODE','w+');
define('WRITE_TO_END_FILE_MODE','a');
define('WRITE_AND_READ_TO_END_FILE_MODE','a+');
define('CREATE_TO_WRITE_MODE','x');
define('CREATE_TO_WRITE_AND_READ_MODE','x+');
define('CREATE_WITHOUT_TRUNCATE_ON_WRITE_MODE','c');
define('CREATE_WITHOUT_TRUNCATE_ON_READ_AND_WRITE_MODE','c+');
define('FILES_OPEN_MODE',[READ_FILE_MODE,READ_AND_WRITE_FILE_MODE,EMPTY_AND_WRITE_FILE_MODE,EMPTY_READ_AND_WRITE_FILE_MODE,WRITE_TO_END_FILE_MODE,WRITE_AND_READ_TO_END_FILE_MODE,CREATE_TO_WRITE_MODE,CREATE_TO_WRITE_AND_READ_MODE,CREATE_WITHOUT_TRUNCATE_ON_WRITE_MODE,CREATE_WITHOUT_TRUNCATE_ON_READ_AND_WRITE_MODE]);


define('LOCALHOST','localhost');


define('ASC','ASC');
define('DESC','DESC');

define('ROOT',dirname(__DIR__));
define('WEB',ROOT . DIRECTORY_SEPARATOR  . 'web');
define('DB',ROOT. DIRECTORY_SEPARATOR .'db');
define('CONTROLLERS_NAMESPACE','Shaolin\\Controllers');
define('CORE',ROOT . DIRECTORY_SEPARATOR . 'core');
define('CONTROLLERS',CORE . DIRECTORY_SEPARATOR . 'Controllers');
define('VIEWS',CORE . DIRECTORY_SEPARATOR . 'Views');
define('MODELS',CORE . DIRECTORY_SEPARATOR . 'Models');
define('MIDDLEWARE',CORE . DIRECTORY_SEPARATOR . 'Middleware');
define('CONFIG',CORE. DIRECTORY_SEPARATOR  . 'Config');
define('COMMAND',CORE. DIRECTORY_SEPARATOR  . 'Commands');

define('NUMERIC','([0-9]+)');

define('NOT_NUMERIC','([^0-9]+)');

define('STRING','([a-zA-Z]+)');
define('NOT_STRING','([^A-Za-z]+)');

define('ALPHANUMERIC','([0-9A-Za-z\-]+)');
define('SLUG','([0-9A-Za-z\-]+)');

define('BETWEEN','BETWEEN');
define('EQUAL','=');
define('DIFFERENT','!=');
define('INFERIOR','<');
define('INFERIOR_OR_EQUAL','<=');
define('SUPERIOR','>');
define('SUPERIOR_OR_EQUAL','>=');
define('LIKE','LIKE');

define('MYSQL','mysql');
define('POSTGRESQL','pgsql');
define('SQLITE','sqlite');

define('UNION',12);
define('UNION_ALL',13);
define('INNER_JOIN',14);
define('CROSS_JOIN',15);
define('LEFT_JOIN',16);
define('RIGHT_JOIN',17);
define('FULL_JOIN',18);
define('SELF_JOIN',19);
define('NATURAL_JOIN',20);
define('SELECT',21);
define('DELETE',22);
define('UPDATE',23);
define('INSERT',24);

define('MYSQL_PORT',3306);
define('POSTGRESQL_PORT',5432);

define('DISPLAY_TABLE',25);
define('DISPLAY_ARTICLE',26);
define('DISPLAY_CONTRIBUTORS',27);

define('QUERY_COLUMN','column');
define('QUERY_CONDITION','condition');
define('QUERY_EXPECTED','expected');
define('QUERY_MODE','mode');
define('QUERY_FIRST_TABLE','first_table');
define('QUERY_FIRST_PARAM','first_param');
define('QUERY_SECOND_TABLE','second_table');
define('QUERY_SECOND_PARAM','second_param');
define('QUERY_ORDER_KEY','key');
define('QUERY_ORDER','order');
define('METHOD_SUPPORTED',['GET','POST','PUT','DELETE']);


define('CSRF_TOKEN','csrf_token');

define('DB_DRIVER','driver');
define('DB_NAME','base');
define('DB_USERNAME','username');
define('DB_PASSWORD','password');
define('DB_HIDDEN_TABLES','hidden_tables');


define('DISPLAY_BUGS','debug');
define('ENV','env');



if (not_exist('memory'))
{
    /**
     *
     * Display memory used
     *
     * @return string
     *
     */
    function memory():string
    {
        $size = memory_get_usage(true);

        $unit=array('B','KB','MB','GB','TB','PB');

        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];

    }

}
if (not_exist('admin'))
{
    /**
     *
     * Get the saved admin route
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function admin()
    {
        return route('admin');
    }
}
if (not_exist('db'))
{


    /**
     *
     * get db config value
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws Kedavra
     */
    function db(string $key)
    {
        return config('db',$key);
    }
}
if (not_exist('infos'))
{
    function infos(...$vars)
    {

        foreach ($vars as $var)
            (new Dumper())->dump($var);
    }
}
if (not_exist('redirect'))
{
    /**
     *
     * Redirect to a route
     *
     * @param string $route_name
     * @param string $message
     * @param bool $success
     * @return RedirectResponse
     *
     * @throws Kedavra
     *
     */
    function redirect(string $route_name,string $message ='',bool $success = true): RedirectResponse
    {
        if (def($message))
        {
            $flash = new Flash();
            $success ? $flash->success($message) : $flash->failure($message);
        }
        return (new RedirectResponse(route($route_name)))->send();
    }
}


if (not_exist('detect_method'))
{
    /**
     *
     * Detect a route method by this name
     *
     * @param string $route_name
     *
     * @return string
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function detect_method(string $route_name): string
    {
        return (app()->routes()->by_or_fail('name',$route_name,"The $route_name route was not found"))->method;
    }
}

if (not_exist('current_user'))
{
    /**
     *
     * Get the user if is logged
     *
     * @return  object
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function current_user()
    {
        return app()->auth()->current();
    }
}

if (not_exist('logged_user'))
{
    /**
     *
     * Get the user if is logged
     *
     * @return  string
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function logged_user():string
    {
        $x = collection(config('auth','columns'))->get('auth');
        return app()->auth()->current()->$x;
    }
}

if (not_exist('route'))
{
    /**
     * @param string $name
     * @param array $args
     * @return string
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function route(string $name,array $args =[]): string
    {
        $x = (app()->routes()->by_or_fail('name',$name,"The $name route  was not found"));

        if (def($args))
        {

            $url = '';
            $data = explode('/',$x->url);
            $i = 0;
            foreach ($data as $k =>$v)
            {
                if (def($v))
                {
                    if (strpos($v,':') === 0)
                    {
                        if (collection($args)->has_key($i))
                        {
                            append($url,'/'.$args[$i]);
                            $i++;
                        }

                    }else{
                        append($url,"/$v");
                    }
                }
            }

            return base_url(trim($url,'/'));
        }

        return base_url(trim($x->url,'/'));
    }
}

if (not_exist('exist'))
{
    /**
     *
     * Return data if exist and define
     *
     * @param $data
     * @param bool $run_exception
     * @param string $message
     *
     * @return mixed
     *
     * @throws Kedavra
     *
     */
    function exist($data,bool $run_exception = false,string $message ='')
    {
        is_true(not_def($data),$run_exception,$message);
        return $data;
    }
}


if (not_exist('numb'))
{
    #    Output easy-to-read numbers
    #    by james at bandit.co.nz
    function numb(int $x)
    {
        // first strip any formatting;
        $n = (0+str_replace(",","",$x));

        // is this a number?
        if(!is_numeric($n))
            return false;

        // now filter it;
        if($n>1000000000000)
            return round(($n/1000000000000),2).' T';
        else if($n>1000000000)
            return round(($n/1000000000),2).' B';
        else if($n>1000000)
            return round(($n/1000000),2).' M';
        else if($n>1000)
            return round(($n/1000),2).' K';

        return number_format($n);
    }
}
if (not_exist('string_parse'))
{
    /**
     *
     * Split a sing to array
     *
     * @param string $data
     *
     * @return array
     *
     */
    function string_parse(string $data): array
    {
        return preg_split('/\s+/', $data);
    }

}
if (not_exist('display_repositories'))
{
    /**
     *
     * Display repositories
     *
     * @param string $owner
     * @return string
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function display_repositories(string $owner = ''): string
    {

        if (app()->cache()->has('repositories'))
            return app()->cache()->get('repositories');


        $username = not_def($owner) ? def(get('owner')) ? get('owner') : '*' : $owner;

        $data = [];
        $owners = collection();

        if (different($username, '*'))
        {

            foreach (Dir::scan('depots') as $owner)
            {
                if ($owner == $username)
                {
                    foreach (Dir::scan('depots' . DIRECTORY_SEPARATOR . $owner) as $repository)
                    {
                        $data[$owner][] = realpath("depots/$owner/$repository");
                    }
                } else
                {
                    if ($owners->not_exist($owner))
                        $owners->add($owner);
                }
            }
        }

        if (equal($username, '*'))
        {
            if (app()->auth()->connected())
            {
                foreach (Dir::scan('depots') as $owner)
                {
                    if ($owners->not_exist($owner) && different($owner,logged_user()))
                    {
                        $owners->add($owner);

                        foreach (Dir::scan('depots' . DIRECTORY_SEPARATOR . $owner) as $repository)
                        {

                            $data[$owner][] = realpath("depots/$owner/$repository");
                        }
                    }
                }
            }
           else
           {
               foreach (Dir::scan('depots') as $owner)
               {
                   if ($owners->not_exist($owner))
                       $owners->add($owner);

                   foreach (Dir::scan('depots' . DIRECTORY_SEPARATOR . $owner) as $repository)
                   {

                       $data[$owner][] = realpath("depots/$owner/$repository");
                   }
               }
           }
        }


        $data = collection($data);
        $request = ServerRequest::fromGlobals();

        if (app()->auth()->connected())
        {
            if (equal($request->getUri()->getPath(), '/home'))
                $code = '<div class="mt-5"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4"  href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow" href="' . route('logout') . '"><i class="material-icons">power_settings_new</i></a></div></div></div></div></div></div>';
            else
                $code = '<div class="mt-5"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow mr-4" href="' . route('home') . '"><i class="material-icons">person</i></a><a class="btn-hollow" href="' . route('logout') . '"><i class="material-icons">power_settings_new</i></a></div></div></div></div></div></div>';
        }else
        {
            $code = '<div class="mt-5"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow" href="' . route('connexion') . '"><i class="material-icons">person</i></a></div></div></div></div></div></div>';
        }


        $end_code ='';

        if ( def($request->getUri()->getQuery()) || equal($request->getUri()->getPath(),'/home'))
        {
            append($code,'<div class="row"><div class="column"><input class="input" type="text" placeholder="Search a project"  onkeyup="search_project()" id="search_project"  autofocus="autofocus"> </div></div><div id="projects"><div class="row">');

        }
        else
        {
            append($code,'<div class="row"><div class="column"><input class="input" type="text" placeholder="Search a project"  onkeyup="search_project()" id="search_project"  autofocus="autofocus"> </div><div class="column"><select onChange="location = this.options[this.selectedIndex].value"><option value="Select an user" >Select an user</option>');
            foreach ($data->keys() as $x)
                append($code,'<option value="?owner='.$x.'">'.$x.'</option>');

            append($code,'</select></div></div><div id="projects"><div class="row">');
        }



        $k = 0;


        foreach ($data->collection() as $user => $repositories)
        {

            foreach ($repositories as $repository)
            {
                $g = new Git($repository,$user);
                append($code,'
                    
                    <section class="column repository" id="'.$g->repository().'">  
                      
                        <h2 class="title">'.$g->repository().'</h2>
                        <hr>
                        <article class="text-center">
                            <h3>'.$g->description().'</h3>
                            <div class="inline-flex mt-4 mb-4">
                                <a class="btn-hollow mr-4" href="'.app()->url('repository',$g->owner(),$g->repository(),'master').'">
                                    <i class="material-icons">code</i> code
                                </a> ');
                                if (!is_mobile())
                                append($code,'<a class="btn-hollow mr-4" href="'.app()->url('download',$g->repository(),$g->owner()).'"> <i class="material-icons">get_app</i>download</a>');

                                append($code,' 
                                <a class="btn-hollow" href="?owner='.$g->owner().'">
                                     <i class="material-icons">  person</i>  '.$g->owner().'
                                </a>
                            </div>
                            <div class="border-t bg-teal-900 border-teal-300 p-4">
                                '.$g->last_update().'
                            </div>
                        </article>             
                    </section>');


                    if (!is_pair($k))
                         append($code,'</div><div class="row">');


                        $k++;
            }
            append($code,'</div><div class="row">');

        }


        append($code,$end_code,'</div></div><script> function search_project()
        { 
            let input, filter, ul, li, i;
            input = document.getElementById("search_project");
            filter = input.value;
            ul = document.getElementById("projects");
            li = ul.getElementsByClassName("repository");
           
            for (i = 0; i < li.length; i++)
            {
                let x = li[i].getAttribute("id");
             
                if (x.indexOf(filter) > -1)
                {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }   
            }
        }     
</script>');

        app()->cache()->set('repositories',$code,3600);
        return $code;

    }
}
if (not_exist('article'))
{
    /**
     *
     * Generate a card list with pagination
     *
     * @param array $records
     * @param string $pagination
     * @param string $icon
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function article(array $records,string  $pagination,$icon =  '<i class="fas fa-newspaper"></i>'): string
    {

        $data = collection($records);
        $file = 'article';
        $code = '';
        $parts =  collection(config($file,'columns'));

        $img = $parts->get('image');
        $title = $parts->get('title');
        $content = $parts->get('content');
        $created = $parts->get('created_at');
        $slug = $parts->get('slug');

        append($code,'<div class="row mt-5 mb-5">');

        $data->rewind();

        while ($data->valid())
        {
            $values = $data->current();

            append($code,'<div class="col-lg-6 col-sm-6"><div class="card mb-3 mt-3"><h4 class="card-header bg-white text-uppercase text-center">'.$values->$title.'</h4><a href="'.config($file,'prefix').'/'.$values->$slug.'"><img src="'.$values->$img.'" alt="'.$values->$title.'" class="card-img-top"></a><div class="card-body"><div class="card-text">'.substr($values->$content,0,\config($file,'limit')).'</div><p class="card-text mt-2"><a href="'.config($file,'prefix').'/'.$values->$slug.'" class="'.\config($file,'read_class').'"> '.$icon.' '.config($file,'read').'</a></p></div><div class="card-footer"><small class="text-muted">'.ago(\config('locales','locale'),$values->$created).'</small></div></div></div>');
            $data->next();
        }
        append($code,'</div>');
        append($code,'<div class="row ml-0 mb-5">');
        append($code,$pagination);
        append($code,'</div>');
        return $code;

    }
}

if (not_exist('not_pdo_instance'))
{
    /**
     * @param $variable
     * @return bool
     */
    function not_pdo_instance(&$variable): bool
    {
        return  $variable instanceof  PDO !== true;
    }
}
if (not_exist('login_page'))
{
    /**
     *
     * Display a page to login an user
     *
     * @param string $welcome_text
     * @param string $login_route_name
     * @param string $send_reset_email_action_name
     * @param string $password_text
     * @param string $identifier_text
     * @param string $forgot_password_email_text
     * @param string $forgot_password_send_email_text
     * @param string $sign_in_text
     * @param string $logo_path
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function login_page(string $welcome_text,string $login_route_name,string $send_reset_email_action_name,string $password_text,string $identifier_text,string $forgot_password_email_text,string $forgot_password_send_email_text,string $sign_in_text,string $logo_path =''): string
    {
        $column   = collection(config('auth','columns'))->get('auth');
        $username = equal($column,'username');

        $class = collection(config('form','class'))->get('submit');

        $logo = def($logo_path) ? '<div class="mb-3"><img src="'.$logo_path.'" alt="logo"></div>' : '';

        $html =  '<div class="container-fluid">
                    <div class="row no-gutter">
                        <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
                        <div class="col-md-8 col-lg-6">
                            <div class="login d-flex align-items-center py-5">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-9 col-lg-8 mx-auto">
                                            <header class="text-center">
                                                '.$logo.'
                                                <h3 class="login-heading text-uppercase text-center mb-4">'.$welcome_text.'</h3>
                                            </header>';

                    if ($username)
                    {
                        $html .= ' <form action="'.route($login_route_name).'" method="post">'.csrf_field().'
                        <div class="form-label-group">
                          <input type="text" id="'.$column.'" name="'.$column.'" class="form-control" placeholder="'.$identifier_text.'" required autofocus>
                          <label for="'.$column.'">'.$identifier_text.'</label>
                        </div>';

                    }else
                        {
                            $html .= ' <form action="'.route($login_route_name).'" method="post">'.csrf_field().'
                        <div class="form-label-group">
                          <input type="email" id="'.$column.'" name="'.$column.'" class="form-control" placeholder="'.$identifier_text.'" required autofocus>
                          <label for="'.$column.'">'.$identifier_text.'</label>
                        </div>';

                    }
                    $html .='
                    <div class="form-label-group">
                      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="'.$password_text.'" required>
                      <label for="inputPassword">'.$password_text.'</label>
                    </div>
                    <input type="hidden" name="method" value="post">
                    <button class="'.$class.'" type="submit">'.$sign_in_text.'</button>
                </form>
                <form action="'.route($send_reset_email_action_name).'" method="post">'.csrf_field().'
                     <div class="form-label-group">
                      <input type="text" id="inputEmail" name="email" class="form-control" placeholder="'.$identifier_text.'" required autofocus>
                      <label for="inputEmail">'.$forgot_password_email_text.'</label>
                    </div>
                    <button class="'.$class.'" type="submit">'.$forgot_password_send_email_text.'</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';
                return $html;

        }
}
if (not_exist('register_page'))
{

    /**
     *
     * Display a page to create a new user
     *
     * @param string $welcome_text
     * @param string $register_route_name
     * @param string $username_text
     * @param string $lastname_text
     * @param string $email_address_text
     * @param string $password_text
     * @param string $confirm_password_text
     * @param string $create_account_text
     * @param string $logo_path
     *
     * @return string
     *
     */
    function register_page(string $welcome_text,string $register_route_name,string $username_text,string $lastname_text,string $email_address_text,string $password_text,string $confirm_password_text,string $create_account_text,string $logo_path =''): string
    {
        return '';
    }
}

if (not_exist('create_repository'))
{
    function  create_repository(): string
    {
        return '<form action="'.route('add-repository').'" method="POST">'.csrf_field().' <input type="hidden" name="method" value="POST"><div class="row"><div class="column"><input type="text" name="repository" minlength="3" maxlength="50" autofocus="autofocus" placeholder="The project name" autocomplete="off" required="required"></div><div class="column"><input type="email"  minlength="3" maxlength="255" autocomplete="off"  name="email" placeholder="The bugs report email" required="required"></div></div><div class="row"><div class="column"><textarea name="description"  rows="10" placeholder="The repository description" autocomplete="off" required="required"></textarea></div></div><div class="row"><div class="column"><button type="submit"><i class="material-icons">add</i> Create the repository</button></div></div></form>';
    }
}
if (not_exist('connexion'))
{

    function connexion($register_route_name,$login_route_name,$username_text = 'Username',$lastname_text = 'Lastname',$email_address_text= 'Your Email adrress',$password_text ='Password',$confirm_password_text='Confirm the password',$create_account_text= 'Create account',$connexion_text= 'Log in')
    {
        return  ' 
                        <div class="mb-10">
               
                    <a class="btn-hollow mr-4"  href="#" id="register">
                        <i class="material-icons">person_add</i> '.$create_account_text.'
                    </a>  
                     <a href="#" class="btn-hollow mr-4" id="login">
                        <i class="material-icons">person</i>   '.$connexion_text.'</a>
                   
                    <a class="btn-hollow"  href="'.root().'">
                        <i class="material-icons">apps</i> apps
                   </a>                            
                </div>
                 <div id="register-form" class="hidden">
                    <form action="'.route($register_route_name).'" method="POST">
                        '.csrf_field().'
                        <input name="method" value="POST" class="hidden">
                        <div class="row">
                            <div class="column">
                                <input type="text" autocomplete="off" minlength="3" maxlength="200" name="firstname" placeholder="'.$username_text.'" required="required">
                            </div>
                            <div class="column">
                                <input type="text" autocomplete="off"  minlength="3" maxlength="200" name="lastname" placeholder="'.$lastname_text.'" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <input type="email" autocomplete="off" name="email" placeholder="'.$email_address_text.'" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <input type="password" autocomplete="off"  minlength="8" name="password" placeholder="'.$password_text.'" required="required">
                            </div>
                            <div class="column">
                                <input type="password" autocomplete="off"  minlength="8" name="confirm_password" placeholder="'.$confirm_password_text.'" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <button type="submit"><i class="material-icons">person_add</i> '.$create_account_text.'</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div id="login-form" class="hidden">
                    <form action="'.route($login_route_name).'" method="POST">
                        '.csrf_field().'
                        <input name="method" value="POST" class="hidden">
                        <div class="row">
                            <div class="column">
                                <input type="text" autocomplete="off" minlength="3" maxlength="200" name="firstname" placeholder="'.$username_text.'" required="required">
                            </div>
                            <div class="column">
                                <input type="password" autocomplete="off" minlength="8" maxlength="200" name="password" placeholder="'.$password_text.'" required="required">
                            </div>
                        </div>
                       <div class="row">
                            <div class="column">
                                <button type="submit"><i class="material-icons">person</i> '.$connexion_text.'</button>
                            </div>
                        </div>
                    </form>
                </div>';


    }
}
if(not_exist('copyright'))
{
    /**
     *
     * Display copyright
     *
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function copyright(): string
    {
        return  '© ' .config('copyright','owner') .' ' .\config('copyright','creation')  .' '. now()->format('Y') . \config('copyright','text');
    }
}

if (not_exist('to'))
{
    /**
     *
     * @param string $url
     * @param string $message
     *
     * @param bool $success
     *
     * @return RedirectResponse
     * @throws Kedavra
     */
    function to(string $url,string $message = '',bool $success =  true)
    {
        if (def($message))
        {
            $flash = new Flash(app()->session());

            $success ? $flash->success($message) : $flash->failure($message);
        }
        return (new RedirectResponse($url))->send();
    }
}


if (not_exist('config'))
{
    /**
     *
     * Get a config value
     *
     * @param string $file
     * @param $key
     *
     * @return mixed
     *
     * @throws Kedavra
     *
     */
    function config(string $file,$key)
    {
        return (new Config($file,$key))->value();
    }
}

if (not_exist('core_path'))
{
    /**
     *
     * The app core path
     *
     * @param string $dir
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function core_path(string $dir): string
    {
        if (def(request()->server->get('PWD')) && Dir::is('vendor'))
            return request()->server->get('PWD') . DIRECTORY_SEPARATOR . $dir;

        if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
            return dirname(request()->server->get('SCRIPT_FILENAME'),3) .DIRECTORY_SEPARATOR .$dir;
        else
            return dirname(request()->server->get('DOCUMENT_ROOT')) .DIRECTORY_SEPARATOR . $dir;

    }
}

if (not_exist('dump_path'))
{
    /**
     *
     * The dump dir
     *
     * @param string $dir
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function dump_path(string $dir): string
    {
        if (def(request()->server->get('PWD')) && Dir::is('vendor'))
            return request()->server->get('PWD') . DIRECTORY_SEPARATOR . $dir;

        if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
            return dirname(request()->server->get('SCRIPT_FILENAME'),3) .DIRECTORY_SEPARATOR .$dir;
        else
            return dirname(request()->server->get('DOCUMENT_ROOT')) .DIRECTORY_SEPARATOR . $dir;

    }
}
if (not_exist('locales'))
{
    /**
     *
     * @return array
     *
     * @throws Kedavra
     *
     */
    function locales(): array
    {
        return Config::init()->get('app','locales');
    }
}

if (not_exist('trans'))
{

    /**
     *
     * Translate the message by using a file
     *
     * @param string $message
     * @param mixed ...$args
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function trans(string $message,...$args): string
    {
        $keys    = array_keys($args);

        $keysmap = array_flip($keys);

        $values  = array_values($args);

        $x = Trans::init()->get(config('locales','locale'),$message);

        if (not_def($x))
            return $message;

        $message = $x;

        while (preg_match('/%\(([a-zA-Z0-9_ -]+)\)/', $message, $m))
        {
            if (not_def($keysmap[$m[1]]))
                return '';

            $message = str_replace($m[0], '%' . ($keysmap[$m[1]] + 1) . '$', $message);
        }

        array_unshift($values, $message);

        return call_user_func_array('sprintf', $values);
    }
}
if (not_exist('root'))
{
    function root(): string
    {
        if (php_sapi_name() !== 'cli')
            return https() ? 'https://' . request()->server->get('HTTP_HOST') :  'http://' . request()->server->get('HTTP_HOST');

        return '/';
    }
}
if (not_exist('config_path'))
{
    /**
     *
     * Return the config path
     *
     * @return string
     *
     *
     */
    function config_path(): string
    {
        return CONFIG;
    }
}

if (not_exist('request'))
{
    /**
     * @return Request
     */
    function request(): Request
    {
        return Request::createFromGlobals();
    }
}

if (not_exist('views_path'))
{
    /**
     *
     * Get the views dir path
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function views_path(): string
    {
        return  ROOT .DIRECTORY_SEPARATOR .  collection(config('app','dir'))->get('app') . DIRECTORY_SEPARATOR . collection(config('app','dir'))->get('view');
    }
}

if (not_exist('view'))
{

    /**
     * Load a view
     *
     * @param string $class
     * @param string $name
     * @param array $args
     *
     * @return string
     * @throws Kedavra
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function view(string $class,string $name,array $args =[]) : string
    {
        if (def($class))
        {
            $dir = str_replace('Controller','',collection(explode("\\",$class))->last());

            $file = collection(explode('.',$name))->begin();

            append($file,'.twig');

            $file = $dir .DIRECTORY_SEPARATOR .$file;

            return (new View())->load($file,$args);
        }
        $file = collection(explode('.',$name))->begin();
        append($file,'.twig');
        return (new View())->load($file,$args);
    }
}

if (not_exist('current_table'))
{
    /**
     *
     * Return the current table or the first found
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function current_table():string
    {
        return  def(get('table')) ? get('table') : collection(app()->table()->show())->begin();
    }
}

if (not_exist('session_loaded'))
{
    /**
     *
     * Check if the session is active
     *
     * @return bool
     *
     */
    function session_loaded(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
if (not_exist('csrf_field'))
{
    /**
     *
     * Generate a token
     *
     * @return string
     *
     * @throws Kedavra
     */
    function csrf_field(): string
    {
        $value = (new Csrf(app()->session()))->token();

        return '<input type="hidden" name="'. CSRF_TOKEN.'" value="'.$value.'">';
    }
}

if (not_exist('message'))
{
    /**
     *
     * Load a html email content
     *
     * @param string $filename
     *
     * @return string
     *
     * @throws Kedavra
     */
    function message(string $filename): string
    {
        return (new File(realpath(core_path(collection(config('app', 'dir'))->get('app'))) .DIRECTORY_SEPARATOR . 'Mailers' . DIRECTORY_SEPARATOR .'Emails' .DIRECTORY_SEPARATOR .  $filename))->read();
    }
}

if (not_exist('sql_file'))
{
    /**
     *
     * Get the sql file path
     *
     * @method sql_file_path
     *
     * @param string $table
     * @return string
     *
     * @throws Kedavra
     */
    function sql_file(string $table  = ''): string
    {
        if (def($table) && different(\app()->connect()->driver(),SQLITE))
            return def($table) ? app()->connect()->dump_path() .DIRECTORY_SEPARATOR ."$table.sql" : app()->connect()->dump_path() . DIRECTORY_SEPARATOR . app()->connect()->base() .'.sql';
        else
            return \app()->connect()->dump_path() .DIRECTORY_SEPARATOR . \collection(explode('.',collection(explode(DIRECTORY_SEPARATOR,app()->connect()->base()))->last()))->begin() .'.sql';
    }
}

if (not_exist('clear_terminal'))
{
    function clear_terminal(): void
    {
        os(true) ==  Os::WINDOWS ? system('cls') : system('clear');
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
     * @param string $driver
     *
     * @return string
     */
    function true_or_false(string $driver)
    {
        switch ($driver)
        {
            case MYSQL:
                return rand(0,1);
            break;
            case POSTGRESQL:
            case SQLITE:
                return rand(0,1) === 1 ? 'TRUE' : 'FALSE';
            break;
            default:
                return '';
            break;
        }

    }
}

if (not_exist('quote'))
{
    /**
     * Secure a string
     *
     * @method quote
     *
     * @param  string  $value   The value to secure
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function quote(string $value): string
    {
        return app()->connect()->instance()->quote($value);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $value
     * @param bool $unserialize
     * @return mixed
     * @throws Kedavra
     */
    function decrypt($value, $unserialize = true)
    {
        return app()->decrypt($value, $unserialize);
    }
}

if (! not_exist('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool $serialize
     * @return string
     * @throws Kedavra
     */
    function encrypt($value, $serialize = true)
    {
        return app()->encrypt($value, $serialize);
    }
}

if (not_exist('app'))
{
    /**
     *
     * Get all applications
     *
     * @method app
     *
     * @return App
     *
     */
    function app(): App
    {
       return Container::get();
    }
}



if (not_exist('env'))
{
    /**
     *
     * @param $variable
     *
     * @return array|false|string
     *
     */
    function env($variable)
    {
        return getenv($variable);
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
     *
     */
    function is_true($data,bool $run_exception = false,string $message =''): bool
    {

        $x =  $data === true;

        if ($run_exception && $x)
            throw new Kedavra($message);

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
     * @throws Kedavra
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
     * @param  bool $multiple_languages
     * @param  array $supported_languages
     * @param  string $choose_language_text
     * @param  string $choose_language_valid_text
     * @param  string $choose_language_invalid_text
     * @param  string $select_time_zone_text
     * @param  string $valid_time_zone_text
     * @param  string $time_zone_invalid_text
     * @param  string $password_icon
     * @param  string $username_icon
     * @param  string $email_icon
     * @param  string $submit_icon
     * @param  string $time_zone_icon
     * @param  string $lang_icon
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function secure_register_form(  string $action,string $valid_ip,string $current_ip,string $username_placeholder,
                                    string $username_success_text,string $username_error_text,string $email_placeholder,
                                    string $email_success_text,string $email_error_text,string $password_placeholder,
                                    string $password_valid_text,string $password_invalid_text,string $confirm_password_placeholder,
                                    string $submit_text,bool $multiple_languages = false,
                                    array $supported_languages =[],string $choose_language_text = '', string $choose_language_valid_text ='',
                                    string $choose_language_invalid_text = '',string $select_time_zone_text ='',string $valid_time_zone_text= '',
                                    string $time_zone_invalid_text = '',
                                    string $password_icon = '<i class="fas fa-key"></i>',string $username_icon = '<i class="fas fa-user"></i>',
                                    string $email_icon = '<i class="fas fa-envelope"></i>',string $submit_icon = '<i class="fas fa-user-plus"></i>',
                                    string $time_zone_icon = '<i class="fas fa-clock"></i>',string $lang_icon = '<i class="fas fa-globe"></i>'
    ): string
    {

        $languages = collection(['' => $choose_language_text]);
        foreach ($supported_languages as $k => $v)
            $languages->merge([$k => $v]);

        if (equal($valid_ip,$current_ip))
        {
            $form = form($action,'register-form','was-validated ')->validate() ;
            if ($multiple_languages)
                $form->row()->select(true,'locale',$languages->collection(),$choose_language_valid_text,$choose_language_invalid_text,$lang_icon)->select(true,'zone',zones($select_time_zone_text),$valid_time_zone_text,$time_zone_invalid_text,$time_zone_icon)->end_row();

            return   $form->row()->input(Form::TEXT,'name',$username_placeholder,$username_icon,$username_success_text,$username_error_text,post('name'),true)->input(Form::EMAIL,'email',$email_placeholder,$email_icon,$email_success_text,$email_error_text,post('email'),true)->end_row_and_new()
                ->input(Form::PASSWORD,'password',$password_placeholder,$password_icon,$password_valid_text,$password_invalid_text,post('password'),true)->input(Form::PASSWORD,'password_confirmation',$confirm_password_placeholder,$password_icon,$password_valid_text,$password_invalid_text,post('password_confirmation'),true)->end_row_and_new()
                ->submit($submit_text,$submit_icon)->end_row()->get();

        }
        return '';
    }
}


if (not_exist('bcrypt'))
{
    /**
     *
     * Hash a value
     *
     * @param string $value
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function bcrypt(string $value): string
    {
        return (new Hash($value))->generate();
    }
}

if (not_exist('check'))
{
    /**
     *
     * Check the password
     *
     * @param string $valid
     * @param string $value
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function check(string $valid,string $value): bool
    {
        return (new Hash($valid))->valid($value);
    }
}

if (not_exist('edit'))
{

    /**
     *
     * Generate a form to edit  a record
     *
     * @param string $table
     * @param int $id
     * @param string $action
     * @param string $submit_text
     * @param string $icon
     *
     * @return string
     *
     * @throws Kedavra
     */
    function edit(string $table, int $id, string $action,string $submit_text, string $icon): string
    {
        return app()->model()->edit_form($table,$id,$action,$submit_text,$icon);
    }
}

if (not_exist('route_name'))
{
    /**
     *
     * Display a route name
     *
     * @param string $name
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function route_name(string $name): string
    {
        $x = app()->route()->query()->mode(SELECT)->from('routes')->where('name',EQUAL,$name)->use_fetch()->get();

        return $x->name;
    }
}
if(not_exist('navbar'))
{
    /**
     * @param string $app_name
     * @param array $routes
     * @return string
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function navbar(string $app_name,array $routes =[]): string
    {
        $html = '<nav class="flex items-center fixed top-0 w-full z-10 justify-between flex-wrap bg-teal-500 p-5">
                        <div class="flex items-center flex-shrink-0 text-white mr-6">
                            <span class="font-semibold text-xl tracking-tight"><a href="'.root().'">'.$app_name.'</a></span>
                        </div>
                        <div class="block lg:hidden">
                            <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
                                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                            </button>
                        </div>
                        <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
                            <div class="text-sm lg:flex-grow">';
                                if (def($routes))
                                {
                                    foreach ($routes as $route)
                                        $html.='<a class="block mt-4  text-teal-200 hover:text-white"  href="'.route($route).'">'.strtoupper(route_name($route)).'</a>';
                                }

                                if (app()->auth()->connected())
                                {


                                    if (equal(current_user()->id,1))
                                    {


                                        $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('admin').'">'.strtoupper(route_name('admin')).'</a>';
                                        $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('home').'">'.strtoupper(route_name('home')).'</a>';
                                        $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('logout').'">'.strtoupper(route_name('logout')).'</a>';


                                    }else{

                                        $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('home').'">'.strtoupper(route_name('home')).'</a>';
                                        $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('logout').'">'.strtoupper(route_name('logout')).'</a>';

                                    }

                                }else{

                                    $html.='<a class="block mt-4 lg:inline-block lg:mt-0 mr-2 text-teal-200 hover:text-white"  href="'.route('connexion').'">'.strtoupper(route_name('connexion')).'</a>';

                                }
        $html .= '</div></div></nav>';

        return $html;
    }
}

if (not_exist('display_article'))
{
    /**
     *
     * Display an article and others
     *
     * @param string $slug
     * @param string $others_articles_text
     * @param string $previous
     * @param string $next
     * @param string $table
     *
     * @return string
     *
     * @throws Kedavra
     */
    function display_article(string $slug,string $others_articles_text,string $previous,string $next,string $table ='articles'): string
    {
        $article = app()->model()->from($table)->by('slug',$slug);
        $others  = app()->model()->from($table)->display(DISPLAY_ARTICLE,$previous,$next,'slug',$slug);
        $html = '';
        foreach ($article as $item)
        {
            $html .= '<section>
            <header>
                <div class="mt-3 mb-3">
                    <img src="'.$item->img.'" alt="'.$item->title.'"  width="100%">
                </div>
                <h1 class="subheading text-center">'.$item->title.'</h1>
                <hr>
            </header>
            <article>
             '. nl2br($item->content).'
            </article>
            <div class="mt-2">
               '.url().'
            </div>
        </section>';
        }
        $html .= '<section> <header class="mt-5"><h2 class="text-uppercase text-center">'.$others_articles_text.'</h2><hr></header><article>'.$others.'</article></section>';
    return $html;
    }
}

if (not_exist('back'))
{
    /**
     * @param string $message
     * @param bool $success
     *
     * @return RedirectResponse
     *
     */
    function back(string $message = '', bool $success = true): RedirectResponse
    {
        $back = request()->server->get('HTTP_REFERER');

        if (is_null($back))
            $back = '/';

        return to($back,$message,$success);
    }
}

if (not_exist('url'))
{
    /**
     * @return string
     * @throws Kedavra
     */
    function url()
    {
        return '<a href="javascript:history.go(-1)" class="'.config('back','class').'">'.config('back','message').'</a>';
    }
}

if(not_exist('create'))
{
    /**
     *
     * Generate a form to create a record
     *
     * @param $table
     * @param $action
     * @param $submit_text
     * @param $icon
     *
     * @return string
     *
     * @throws Kedavra
     */
    function create($table, $action, $submit_text, $icon)
    {
        return app()->model()->create_form($table,$action,$submit_text,$icon);
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
     * @param  string $filename The filename
     * @param  string $key The optional key
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function bases_to_json(string $filename,string $key ='bases'): bool
    {
        return json($filename)->add(app()->bases()->show(),$key)->generate();
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
     * @param  string        $filename  The filename
     * @param  string        $key      The optional key
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function users_to_json($filename,string $key = 'users') : bool
    {
        return json($filename)->add(app()->users()->show(),$key)->generate();
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
     * @param  string         $filename The filename
     * @param  string         $key     The key
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function tables_to_json(string $filename,string $key = 'tables') : bool
    {
        return json($filename)->add(app()->table()->show(),$key)->generate();
    }
}

if (not_exist('sql_to_json'))
{

    /**
     *
     * @param string $filename
     * @param array $query
     * @param array $key
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function sql_to_json(string $filename,array $query, array $key) : bool
    {
        $x =  json($filename);

        $keys = collection($key);

        foreach($query as $k => $v)
            $x->add(app()->connect()->request($v),$keys->get($k));

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
     * @param string $table
     * @param int $mode
     * @param  mixed $data The query results
     * @param  string $success_text The success text
     * @param  string $result_empty_text Result empty text
     * @param  string $table_empty_text Table is empty text
     * @param  string $sql The sql query to print
     *
     * @return string
     *
     * @throws Kedavra
     */
    function query_result(string $table,int $mode,$data,string $success_text,string $result_empty_text,string $table_empty_text,string $sql): string
    {

        if (equal($mode,Query::UPDATE))
        {
            $x ='';

            foreach ($data as $datum)
                append($x,$datum);

            return $x;
        }
        if (is_bool($data) && $data)
            return html('code',$sql,'text-center').html('div',$success_text,'alert alert-success mt-5');
        elseif(empty(app()->model()->from($table)->all()))
            return html('code',$sql,'text-center'). html('div',$table_empty_text,'alert alert-danger mt-5');
        else
            return  empty($data) ? html('div',$result_empty_text,'alert alert-danger') : html('code',$sql,'text-center') .collection($data)->print(true,\app()->model()->columns());

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
     * @throws Kedavra
     *
     */
    function length($data): int
    {
        if (is_array($data))
            return count($data);
        elseif(is_string($data))
            return strlen($data);

        throw new Kedavra('The parameter must be a string or an array');
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
     * @param $sql_variable
     *
     * @return mixed
     *
     * @throws Kedavra
     */
    function execute_query(&$sql_variable)
    {


        $data = collection(\Imperium\Request\Request::all());
        $column_name = $data->get('column');
        $condition   = $data->get('condition');
        $expected = $data->get('expected');
        $mode = intval($data->get('mode'));
        $first_table = $data->get('first_table');
        $first_param  = $data->get('first_param');
        $second_table = $data->get('second_table');
        $second_param = $data->get('second_param');
        $order_column = $data->get('key');
        $order = $data->get('order');
        $model = app()->model();

        switch ($mode)
        {
            case DELETE:

                $sql_variable = $model->query()->from($first_table)->mode($mode)->where($column_name,$condition,$expected)->sql();

                $data = $model->from($first_table)->where($column_name,$condition,$expected)->get();

                return empty($data) ? $data :  $model->query()->from($first_table)->mode($mode)->where($column_name, $condition, $expected)->delete() ;
                break;
            case has($mode,Query::JOIN_MODE):
                $sql_variable = $model->query()->mode($mode)->join($condition,$first_table,$second_table,$first_param,$second_param)->order_by($order_column,$order)->sql();
                return $model->query()->mode($mode)->join($condition,$first_table,$second_table,$first_param,$second_param)->order_by($order_column,$order)->get();
                break;
            case  has($mode,Query::UNION_MODE);
                $sql_variable = $model->query()->mode($mode)->union($first_table,$second_table,$first_param,$second_param)->order_by($order_column,$order)->where($column_name,$condition,$expected)->sql();
                return  $model->query()->mode($mode)->union($first_table,$second_table,$first_param,$second_param)->where($column_name,$condition,$expected)->order_by($order_column,$order)->get();
                break;
            default:
                $sql_variable = $model->query()->from($first_table)->mode($mode)->where($column_name,$condition,$expected)->order_by($order_column,$order)->sql();
                return $model->query()->from($first_table)->mode($mode)->where($column_name,$condition,$expected)->order_by($order_column,$order)->get();
                break;
        }
    }
}

if (not_exist('query_view'))
{


    /**
     * @param string $confirm_message
     * @param string $query_action
     * @param string $create_record_action
     * @param string $create_record_submit_text
     * @param string $current_table_name
     * @param string $expected_placeholder
     * @param string $submit_query_text
     * @param string $reset_form_text
     * @param string $validation_success_text
     * @param string $validation_error_text
     * @param string $icon
     * @return string
     * @throws Kedavra
     */
    function query_view(string $confirm_message, string $query_action, string $create_record_action,  string $create_record_submit_text,  string $current_table_name, string $expected_placeholder, string $submit_query_text, string $reset_form_text, string $validation_success_text = 'success' , $validation_error_text= 'must not be empty', string $icon  = '<i class="fas fa-heart"></i>') : string
    {

        $table = app()->table();

        $columns = $table->column()->for($current_table_name)->show();

        $primary = $table->column()->for($current_table_name)->primary_key();
        $tables = $table->show();

        $x = count($columns);

        $condition = Query::CONDITION;

        $operations =  Query::QUERY_VIEW_MODE;

        is_pair($x) ?  $form_grid =  2 :  $form_grid =  3;


        return  (new Form())->validate()->start($query_action,id(),$confirm_message)
                ->row()
                ->reset($reset_form_text)
                ->input(Form::HIDDEN,'primary','','',$validation_success_text,$validation_error_text,$primary)
                ->input(Form::HIDDEN,'__table__','','',$validation_success_text,$validation_error_text,$current_table_name)
                ->end_row_and_new()
                ->select(false,QUERY_COLUMN,$columns,$icon,$validation_success_text,$validation_error_text)
                ->select(true,QUERY_CONDITION,$condition,$icon,$validation_success_text,$validation_error_text)
                ->input(Form::TEXT,QUERY_EXPECTED,$expected_placeholder,$icon,$validation_success_text,$validation_error_text)
                ->end_row_and_new()
                ->select(true,QUERY_MODE,$operations ,$icon,$validation_success_text,$validation_error_text)
                ->end_row_and_new()
                ->select(false,QUERY_FIRST_TABLE,$tables,$icon,$validation_success_text,$validation_error_text)
                ->select(false,QUERY_FIRST_PARAM,$columns,$icon,$validation_success_text,$validation_error_text)
                ->select(false,QUERY_SECOND_TABLE,$tables,$icon,$validation_success_text,$validation_error_text)
                ->select(false,QUERY_SECOND_PARAM,$columns,$icon,$validation_success_text,$validation_error_text)
                ->end_row_and_new()
                ->select(false,QUERY_ORDER_KEY,$columns,$icon,$validation_success_text,$validation_error_text)
                ->select(false,QUERY_ORDER,[ASC,DESC],$icon,$validation_success_text,$validation_error_text)
                ->end_row_and_new()
                ->submit($submit_query_text,id())
                ->end_row()->get() .form($create_record_action,id())->generate($form_grid,$current_table_name,$create_record_submit_text,uniqid())
            ;
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
     * @throws Kedavra
     */
    function connect(string $driver,string $base,string $user,string $password,string $host,string $dump_path): Connect
    {
        return new Connect($driver,$base,$user,$password,$host,$dump_path);
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
     * @param string $filename The json filename
     *
     * @return Json
     *
     * @throws Kedavra
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
    function collection($data = []): Collection
    {
        if (is_object($data))
        {
            $x = [];
            foreach ($data as $k => $v)
                $x[$k] = $v;

            return new Collection($x);
        }
        return is_array($data) ? new Collection($data) : new Collection();
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
        $zones = collection(['' => $select_time_zone_text]);

        foreach (DateTimeZone::listIdentifiers() as $x)
            $zones->merge([$x => $x]);

        return $zones->collection();
    }
}
if (not_exist('https'))
{
    function https(): bool
    {
        return request()->isSecure();
    }
}

if (not_exist('https_or_fail'))
{
    /**
     *
     * Check if the protocol is https or run exception if not found
     *
     * @throws Kedavra
     *
     */
    function https_or_fail()
    {
        if (!https())
        {
            throw new Kedavra('The https protocol was not found');
        }
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
     * @param string $url_prefix The url prefix
     * @param string $separator The url separator
     *
     * @return string
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function tables_select(string $current, string $url_prefix,string $separator): string
    {

        $tables = collection(["" => $current]);

        foreach (app()->table()->show() as $x)
        {
            if (different($x,$current))
                $tables->add($x,"$url_prefix$separator$x");
        }
        return  form('',id())->large(true)->row()->redirect('table',$tables->collection())->end_row()->get() ;
    }
}

if (not_exist('users_select'))
{
    /**
     * Generate an user select
     *
     * @method users_select
     *
     * @param  string $urlPrefix The url prefix
     * @param  string $currentUser The current username
     * @param  string $chooseText The select text
     * @param  bool $use_a_redirect_select To use a redirect select
     * @param  string $separator The url separator
     * @param  string $icon The select user icon
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function users_select(string $urlPrefix,string $currentUser,string $chooseText,bool $use_a_redirect_select,string $separator = '/',string $icon = ''): string
    {
        $users = collection(array('' => $chooseText));

        foreach (app()->users()->show() as $x)
        {
            if (different($x,$currentUser))
                $users->merge(["$urlPrefix$separator$x" => $x]);
        }

        return $use_a_redirect_select ?  form('',uniqid())->large(true)->row()->redirect('users',$users->collection(),$icon)->end_row()->get() : form('',uniqid())->large(true)->row()->select(true,'users',$users->collection(),$icon)->end_row()->get();
    }
}


if (not_exist('bases_select'))
{
    /**
     * build a form to select a base
     *
     * @param string $urlPrefix
     * @param string $currentBase
     * @param string $chooseText
     * @param bool $use_a_redirect_select
     * @param string $separator
     * @param string $icon
     *
     * @return string
     *
     * @throws Kedavra
     */
    function bases_select(string $urlPrefix,string $currentBase,string $chooseText,bool $use_a_redirect_select,string $separator = '/',string $icon = '<i class="fas fa-database"></i>'): string
    {
        $bases = collection(array('' => $chooseText));

        foreach (app()->bases()->show() as $x)
        {
            if (different($x, $currentBase))
                $bases->merge(["$urlPrefix$separator$x" => $x]);

        }

        return $use_a_redirect_select ?  form('',uniqid())->large(true)->row()->redirect('bases',$bases->collection(),$icon)->end_row()->get() : form('',uniqid())->large(true)->row()->select(true,'bases',$bases->collection(),$icon)->end_row()->get();
    }
}

if (not_exist('commands'))
{
    /**
     *
     * Return all available command
     *
     * @return array
     *
     */
    function commands(): array
    {

        $commands  = COMMAND;

        $namespace =  'App\\'.  'Commands';

        $data =   glob($commands.DIRECTORY_SEPARATOR . '*.php');

        $commands = collection();

        foreach ($data as $c)
        {
            $command = collection(explode('/',$c))->last();

            $command = collection(explode('.',$command))->begin();

            $command = "$namespace\\$command";

            $commands->add(new $command());
        }

        return $commands->collection();
    }
}


if (not_exist('extensions'))
{
    /**
     *
     * Return all available command
     *
     * @param string $expected
     * @return array
     *
     * @throws Kedavra
     */
    function extensions(string $expected): array
    {
        $core_path =  ROOT .DIRECTORY_SEPARATOR . collection(config('app','dir'))->get('app');


        $dir  =  $core_path . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR. $expected;

        is_false(Dir::is($core_path),true,"The directory $core_path was not found");

        is_false(Dir::is($dir),true,"The directory $dir was not found");

        $namespace =  config('app','namespace') . "\\Extentions\\$expected";

        $path = realpath($dir);

        $data =   glob($path .DIRECTORY_SEPARATOR . '*.php');

        $x = collection();

        foreach ($data as $c)
        {
            $code = collection(explode(DIRECTORY_SEPARATOR,$c))->last();

            $class = collection(explode('.',$code))->begin();

            $code = "$namespace\\$class";

            $x->add(new $code(),strtolower($class));
        }

        return $x->collection();
    }
}
if(not_exist('routes'))
{
    /**
     *
     * List routes
     *
     * @param OutputInterface $output
     * @param array $routes
     *
     * @throws Kedavra
     *
     */
    function routes(OutputInterface $output,array $routes): void
    {

        if ( def(request()->server->get('TMUX')))
        {
            if (def($routes))
            {
                $output->write("+----------+--------------------+-----------------------+-----------------------+-----------------------+\n");

                foreach ($routes as $route)
                {

                    $name =  "<fg=blue;options=bold>$route->name</>";

                    $url =  "<fg=magenta;options=bold>$route->url</>";
                    $controller =  "<fg=green;options=bold>$route->controller</>";
                    $action =  "<fg=yellow;options=bold>$route->action</>";
                    $method =  "<fg=cyan;options=bold>$route->method</>";

                    if (length($route->method) == 6)
                        $output->write("|  $method  ");
                    elseif(length($route->method) == 4)
                        $output->write("|  $method    ");
                    elseif(length($route->method) == 3)
                        $output->write("|  $method     ");



                    if (length($route->name) < 3)
                        $output->write("|  $name\t\t\t|");

                    elseif(length($route->name) > 10)
                        $output->write("|  $name\t|");
                    else
                        $output->write("|  $name\t\t|");


                    if (length($route->url) < 5)
                        $output->write("  $url\t\t\t|");
                    elseif(length($route->url) < 13)
                        $output->write("  $url\t\t|");
                    elseif(length($route->url)> 18 )
                        $output->write("  $url\t|");
                    else
                        $output->write("  $url\t|");


                    if (length($route->controller) < 7)
                        $output->write("  $controller\t\t|");
                    elseif (length($route->controller) < 10)
                        $output->write("  $controller\t|");
                    elseif (length($route->controller) > 10 && length($route->controller) < 15)
                        $output->write("  $controller\t|");
                    elseif (length($route->controller)> 15)
                        $output->write("  $controller\t|");
                    else
                        $output->write("  $controller\t|");

                    if (length($route->action) < 5)
                        $output->write("  $action\t\t\t|\n");
                    elseif(length($route->action) < 10)
                        $output->write("  $action\t\t|\n");
                    elseif(length($route->action) > 12)
                        $output->write("  $action\t|\n");
                    else
                        $output->write("  $action\t|\n");
                    $output->write("+----------+--------------------+-----------------------+-----------------------+-----------------------+\n");
                }
            }else{
                $output->write("<error>We have not found routes</error>\n");
            }
        }else{
            if (def($routes))
            {
                $output->write("+---------------+-------------------------------+---------------------------------------+---------------------------------------+-------------------------------+\n");

                foreach ($routes as $route)
                {

                    $name =  "<fg=blue;options=bold>$route->name</>";

                    $url =  "<fg=magenta;options=bold>$route->url</>";
                    $controller =  "<fg=green;options=bold>$route->controller</>";
                    $action =  "<fg=yellow;options=bold>$route->action</>";
                    $method =  "<fg=cyan;options=bold>$route->method</>";



                    if (length($route->method) >4 )
                        $output->write("|  $method\t");
                    else
                        $output->write("|  $method\t\t");

                    if (length($route->name) < 5)
                        $output->write("|  $name\t\t\t\t|");

                    elseif(length($route->name) > 10)
                        $output->write("|  $name\t\t|");
                    else
                        $output->write("|  $name\t\t\t|");


                    if (length($route->url) < 5)
                        $output->write("  $url\t\t\t\t\t|");
                    elseif(length($route->url) < 13)
                        $output->write("  $url\t\t\t\t|");
                    elseif(length($route->url)> 18 )
                        $output->write("  $url\t\t|");
                    else
                        $output->write("  $url\t\t\t|");

                    if (length($route->controller) < 5)
                        $output->write("  $controller\t\t\t\t\t|");
                    elseif (length($route->controller) < 8)
                        $output->write("  $controller\t\t\t\t|");
                    elseif (length($route->controller) > 8 && length($route->controller) < 15)
                        $output->write("  $controller\t\t\t|");
                    elseif (length($route->controller)> 15)
                        $output->write("  $controller\t\t\t|");
                    else
                        $output->write("  $controller\t\t\t|");

                    if (length($route->action) < 5)
                        $output->write("  $action\t\t\t\t|\n");
                    elseif(length($route->action) < 10)
                        $output->write("  $action\t\t\t|\n");
                    elseif(length($route->action) > 12)
                        $output->write("  $action\t\t|\n");
                    else
                        $output->write("  $action\t\t\t|\n");

                    $output->write("+---------------+-------------------------------+---------------------------------------+---------------------------------------+-------------------------------+\n");
                }
            }else{
                $output->write("<error>We have not found routes</error>\n");
            }
        }

    }
}

if (not_exist('simply_view'))
{

    /**
     * @param string $before_all_class
     * @param string $thead_class
     * @param string $current_table_name
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
     * @param bool $pagination_to_right
     * @return string
     * @throws Kedavra
     */
    function simply_view(string $before_all_class,string $thead_class,string $current_table_name, array $records  ,string $html_table_class,string $action_remove_text,string $before_remove_text,string $remove_button_class,string $remove_url_prefix,string $remove_icon,string $action_edit_text,string $action_edit_url_prefix,string $edit_button_class,string $edit_icon,string $pagination,bool $pagination_to_right = true): string
    {
        $instance =  app()->table()->from($current_table_name);

        $columns  = $instance->column()->for($current_table_name)->show();
        $primary  = $instance->column()->for($current_table_name)->primary_key();

        $before_content = '<script>function sure(e,text){ if (! confirm(text)) {e.preventDefault()} }</script>';
        $after_content = '<div class="container">';

        if ($pagination_to_right)
            append($after_content ,    '<div class="row"><div class="ml-auto mt-5 mb-5">'.$pagination.'</div></div></div>');
        else
            append($after_content ,    '<div class="row"><div class="mr-auto mt-5 mb-5">'.$pagination.'</div></div></div>');

        return \Imperium\Html\Table\Table::table($columns,$records,$before_all_class,$thead_class,$before_content,$after_content)->set_action($action_edit_text,$action_remove_text,$before_remove_text,$action_edit_url_prefix,$edit_button_class,$edit_icon,$remove_url_prefix,$remove_button_class,$remove_icon,$primary)->limit(\config('admin','limit_table_char'))->generate($html_table_class);

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
     * @param string $current_table_name The current table name
     * @param string $column
     * @param string $expected
     * @param string $condition
     * @param string $order_by The order by
     *
     * @return array
     *
     * @throws Kedavra
     */
    function get_records(string $current_table_name,string $column ='',string $expected = '',string $condition = DIFFERENT,string $order_by = DESC): array
    {
        $base = app()->connect()->base();

        $session = app()->session();

        is_false(app()->table()->has(),true,"We have not found a table in the $base base");

        is_false(app()->table()->exist($current_table_name),true,"We have not found the $current_table_name table in the $base base");

        $limit_per_page = $session->has('limit') ? $session->get('limit') : $session->def('limit',100);

        $offset = ($limit_per_page * get('current',1)) -  $limit_per_page;

        $sql = sql($current_table_name)->mode(SELECT);

        $like = get('q');

        $order_column = app()->model()->from($current_table_name)->primary();

        if (not_def($column))
        {
            if (def($limit_per_page && def($like)))
                $records =  $sql->like($like)->limit($limit_per_page,0)->order_by($order_column,$order_by)->get();
            else
                $records = def($like) ? $sql->like($like)->order_by($order_column,$order_by)->get() : $sql->limit($limit_per_page, $offset)->order_by($order_column,$order_by)->get();
        }else
        {
            if (def($limit_per_page && def($like)))
                $records = $sql->like($like)->limit($limit_per_page,0)->where($column,$condition,$expected)->order_by($order_column,$order_by)->get();
            else
                $records =  def($like) ? $sql->like($like)->where($column,$condition,$expected)->order_by($order_column,$order_by)->get() : $sql->limit($limit_per_page, $offset)->where($column,$condition,$expected)->order_by($order_column,$order_by)->get();
        }
        return $records;
    }
}

if (not_exist('seo'))
{
    /**
     * prepares a string optimized for SEO
     *
     * @param string $x
     *
     * @return string
     */
    function seo(string $x): string
    {
        $sString = preg_replace('/[^\\pL\d_]+/u', '-', $x);
        $sString = trim($sString, "-");
        $sString = iconv('utf-8', "us-ascii//TRANSLIT", $sString);
        $sString = strtolower($sString);
        $sString = preg_replace('/[^-a-z0-9_]+/', '', $sString);

        return $sString;
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
     * @throws Kedavra
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
                throw new Kedavra('Element are not in supported list');
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
     */
    function bootswatch(string $theme = 'bootstrap',string $version = '4.3.1'): string
    {
        return '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/'.$version.'/'.$theme.'/bootstrap.min.css">';

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
     * @param  string $key The session key
     * @param string $value
     *
     * @return string
     *
     */
    function session(string $key,$value = ''): string
    {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]) ?  htmlspecialchars($_SESSION[$key], ENT_QUOTES, 'UTF-8', true) : $value;
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
     * @param string $value
     *
     * @return string
     *
     */
    function cookie(string $key,string $value = ''): string
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? htmlspecialchars($_COOKIE[$key], ENT_QUOTES, 'UTF-8', true) : $value;
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
     * @param string $value
     * @return string
     */
    function get(string $key,string $value = ''): string
    {
        return isset($_GET[$key]) && !empty($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8', true) : $value;
    }
}


if (not_exist('base_url'))
{
    /**
     * @param mixed ...$params
     *
     * @return string
     *
     */
function base_url(...$params): string
{

    if (php_sapi_name() !=='cli')
    {
        $url = https() ? 'https://'.request()->getHost() : 'http://' . \request()->getHost() .'/' ;


        append($url,collection($params)->join('/'));

        return  $url;
    }
    return '/'. collection($params)->join('/');

    }
}

if (not_exist('is_admin'))
{
    /**
     * @return bool
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function is_admin(): bool
    {
        $request = ServerRequest::fromGlobals();
        $prefix = \config('auth','admin_prefix');
        $connected = \app()->session()->has('__connected__');

        if (equal($prefix,'/') && $connected)
            return true;

        if(is_not_false(strstr($request->getUri()->getPath(),$prefix)) && $connected)
            return true;

        return false;
    }
}

if (not_exist('css'))
{
    /**
     *
     * Generate a css link
     *
     * @param string $filename
     *
     * @return string
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function css(string $filename): string
    {
        return app()->assets($filename)->css();
    }
}

if (not_exist('img'))
{
    /**
     *
     * Generate a image link
     *
     * @param string $filename
     * @param string $alt
     *
     * @return string
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function img(string $filename,string $alt): string
    {
        return app()->assets($filename)->img($alt);
    }
}

if (not_exist('js'))
{
    /**
     *
     * Generate a js link
     *
     * @param string $filename
     *
     * @param string $type
     * @return string
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function js(string  $filename,string $type= ''): string
    {
        return app()->assets($filename)->js($type);

    }
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
     * @param string $key The server key
     *
     * @param string $value
     * @return string
     */
    function server(string $key,string $value = ''): string
    {
        return isset($_SERVER[$key]) && !empty($_SERVER[$key]) ?  $_SERVER[$key] : $value;
    }
}

if (not_exist('phinx'))
{
    function phinx()
    {

        d(\request()->server->all());
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
     * @param string $value
     * @return string
     */
    function post(string $key,string $value = ''): string
    {
        return isset($_POST[$key]) && !empty($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8', true) : $value;
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
     * @param  string $submitText
     * @param  string $submitIcon
     * @param  int $mode
     * @param  int $id
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function generate(string $formId,string $class,string $action,string $table,string $submitText,string $submitIcon,int $mode = Form::CREATE,int $id = 0): string
    {
        return form($action,$formId,$class)->generate(2,$table,$submitText,$submitIcon,$mode,$id);
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
     *
     * @param Connect $connect
     * @return array
     *
     * @throws Kedavra
     */
    function collation(Connect $connect): array
    {
        $collation = collection();

        $connexion = $connect;

        if($connexion->sqlite())
            return $collation->collection();


        assign($connexion->mysql(),$request,'SHOW COLLATION');

        assign($connexion->postgresql(),$request,'SELECT collname FROM pg_collation');

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
     *
     * @param Connect $connect
     * @return array
     *
     * @throws Kedavra
     *
     */
    function charset(Connect $connect): array
    {
        $collation = collection();

        $connexion = $connect;

        if ($connexion->sqlite())
            return $collation->collection();

        $request = '';

        assign($connexion->mysql(),$request,'SHOW CHARACTER SET');

        assign($connexion->postgresql(),$request,'SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1');

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
     * @throws Kedavra
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
     * @param  string $username
     * @param  string $new_password
     *
     * @return bool
     *
     * @throws Kedavra
     */
    function pass(string $username ,string $new_password) : bool
    {
        return user(app()->connect())->update_password($username,$new_password);
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
     * @throws Kedavra
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
     * @throws Kedavra

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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @param  array $array The array
     * @param  mixed $key The after key
     *
     * @return mixed
     *
     * @throws Kedavra
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
     * @param  string[] $queries The sql queries
     *
     * @return array
     *
     * @throws Kedavra
     *
     */
    function req(string ...$queries): array
    {
        $data = collection();

        $instance = app()->connect();

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
     * @param  string[] $queries The sql queries
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function execute(string ...$queries): bool
    {

        $data = collection();

        $instance = app()->connect();

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
     *
     * @return Model
     *
     */
    function model(Connect $connect,Table $table): Model
    {
        return new Model($connect,$table);
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
     *
     * @return Table
     *
     * @throws Kedavra
     *
     */
    function table(Connect $connect): Table
    {
        return new Table($connect);
    }
}

if (not_exist('faker'))
{
    /**
     * @param string $locale
     *
     * @return Generator
     */
    function faker(string $locale = 'en_US' ): Generator
    {
        return Factory::create($locale);
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
     * @param  string[]     $users The users to remove
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function remove_users(string ...$users): bool
    {
        $user = app()->users();

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
     * @param  string[]      $tables The tables to remove
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function remove_tables(string ...$tables): bool
    {
        $table = app()->table();

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
     * @param string[] $bases
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function remove_bases(string ...$bases): bool
    {
        return app()->bases()->remove($bases);
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
     * @throws Kedavra
     *
     */
    function form(string $action, string $id, string $class = '',string $confirm = '',string $method = Form::POST, bool $enctype = false,  string $charset = 'utf-8'): Form
    {
        return def($confirm) ? (new Form())->validate()->start($action,$method,$confirm,$id,$class,$enctype,$charset) : (new Form())->start($action,$method,$confirm,$id,$class,$enctype,$charset);
    }
}

if (not_exist('update_file_values'))
{

    /**
     *
     * Update a value in a file
     *
     * @param string $filename
     * @param string $delimiter
     * @param string[] $values
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function update_file_values(string $filename,string $delimiter,string ...$values): bool
    {
        $keys = (new File($filename))->keys($delimiter);

        return (new File($filename,EMPTY_AND_WRITE_FILE_MODE))->change_values($keys,$values,$delimiter);
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
     * @param array $array The array
     * @param mixed $value The value
     * @param bool $run_exception To run exception
     * @param string $message The exception message
     *
     * @return bool
     *
     * @throws Kedavra
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
     * @param  bool $base To dump a base
     * @param  array $tables The tables to dump
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function dumper(bool $base, array $tables=[]): bool
    {
        return (new Dump($base,$tables))->dump();
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
     * @param  string $table The table name
     *
     * @return Query
     *
     * @throws Kedavra
     *
     */
    function sql(string $table): Query
    {
        return app()->query()->from($table);
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
if (not_exist('months'))
{

    function months()
    {
        $data = collection();

        $format = new IntlDateFormatter(\config('locales','locale'),IntlDateFormatter::FULL, IntlDateFormatter::FULL, null, null, "MMM");

        for($i=1;$i!=12;$i++)
            $data->add(ucfirst($format->format(mktime(0, 0, 0, $i))));

        return $data->collection();
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
     * @param  string $user The username
     * @param  string $password The user password
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function add_user(string $user,string $password): bool
    {
        return app()->users()->set_name($user)->set_password($password)->create();
    }
}




if (not_exist('foundation'))
{
    function foundation(string $version = '6.4.3')
    {
        return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/'.$version.'/css/foundation.min.css"/>';
    }
}
if (not_exist('awesome'))
{
    function awesome(string $version = 'v5.0.8')
    {
        return '<link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/fontawesome.css"><link rel="stylesheet" href="https://use.fontawesome.com/releases/'.$version.'/css/solid.css">';
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
        $x = "$prefix $icon $options";
        return '<i class="'.$x.'"></i>';
    }
}

if (not_exist('css_loader'))
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
     * @param Model $model
     * @param string $table
     * @param mixed ...$values
     *
     * @return string
     *
     * @throws Kedavra
     */
    function insert_into(Model $model,string $table,array  $values): string
    {
        $instance = $model->from($table);

        $x = collection($instance->columns())->join(',');

        $data = "INSERT INTO $table ($x) VALUES (";

        $primary = $instance->primary();

        foreach ($values as $k=> $v)
        {
            if(different($v,$primary))
            {
                if (is_numeric($v))
                    append($data, $v .' ,');
                else
                    append($data,$model->pdo()->quote($v) .', ') ;
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

if (not_exist('routes_add'))
{
    /**
     *
     * @param Model $model
     * @param mixed ...$values
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function routes_add(Model $model,array  $values): bool
    {
        $instance = $model;


        $x = collection($instance->columns())->join(',');

        $data = "INSERT INTO routes ($x) VALUES (";

        $primary = $instance->primary();

        foreach ($values as $k=> $v)
        {
            if(different($v,$primary))
            {
                if (is_numeric($v))
                    append($data,quote($v). ', ');
                else
                    append($data,$instance->quote($v) .', ') ;
            }
            else
            {
                if ($instance->is_mysql() ||  $instance->is_sqlite())
                    append($data,'NULL, ');
                else
                    append($data,"DEFAULT, ");

            }
        }

        $data = trim($data,', ');

        append($data, ')');


         return $instance->execute($data);

    }
}
if (not_exist('controllers'))
{
    /**
     * @return array
     *
     */
    function controllers(): array
    {
        $dir = CONTROLLERS;

        $controllers  = collection(File::search("$dir" .DIRECTORY_SEPARATOR. '*.php'));

        $data = collection();

        if ($controllers)
        {
            foreach ($controllers as $controller)
                $data->add(collection(explode('.',collection(explode(DIRECTORY_SEPARATOR,$controller))->last()))->begin());
        }
        return $data->collection();
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
     * @throws Kedavra
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
    function image(string $driver = 'gd'): ImageManager
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
     * @param DateTimeZone|string|null $tz
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
     * @param DateTimeZone|string|null $tz
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
     * @param DateTimeZone|string|null $tz
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
                return Carbon::now($tz)->addSeconds($time)->toDateString();
            break;
            case 'seconds':
                return Carbon::now($tz)->addSeconds($time)->toDateString();
            break;
            case 'minute':
                return Carbon::now($tz)->addMinutes($time)->toDateString();
            break;
            case 'minutes':
                return Carbon::now($tz)->addMinutes($time)->toDateString();
            break;
            case 'hour':
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
            case 'hours':
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
            case 'day':
                return Carbon::now($tz)->addDays($time)->toDateString();
            break;
            case 'days':
                return Carbon::now($tz)->addDays($time)->toDateString();
            break;
            case 'week':
                return Carbon::now($tz)->addWeeks($time)->toDateString();
            break;
            case 'weeks':
                return Carbon::now($tz)->addWeeks($time)->toDateString();
            break;
            case 'month':
                return Carbon::now($tz)->addMonths($time)->toDateString();
            break;
            case 'months':
                return Carbon::now($tz)->addMonths($time)->toDateString();
            break;
            case 'year':
                return Carbon::now($tz)->addYears($time)->toDateString();
            break;
            case 'years':
                return Carbon::now($tz)->addYears($time)->toDateString();
            break;
            case 'century':
                return Carbon::now($tz)->addCenturies($time)->toDateString();
            break;
            case 'centuries':
                return Carbon::now($tz)->addCenturies($time)->toDateString();
            break;
            default:
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
        }

    }
}


if (not_exist('past'))
{

    /**
     * Create a new future date.
     *
     * @param DateTimeZone|string|null $tz
     * @param string                     $mode
     * @param int                        $time
     *
     * @return string
     */
    function past(string $mode,int $time,$tz = null): string
    {
        $time = - $time;

        switch ($mode)
        {
            case 'second':
                return Carbon::now($tz)->addSeconds($time)->toDateString();
            break;
            case 'seconds':
                return Carbon::now($tz)->addSeconds($time)->toDateString();
            break;
            case 'minute':
                return Carbon::now($tz)->addMinutes($time)->toDateString();
            break;
            case 'minutes':
                return Carbon::now($tz)->addMinutes($time)->toDateString();
            break;
            case 'hour':
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
            case 'hours':
                return Carbon::now($tz)->addHours($time)->toDateString();
            break;
            case 'day':
                return Carbon::now($tz)->addDays($time)->toDateString();
            break;
            case 'days':
                return Carbon::now($tz)->addDays($time)->toDateString();
            break;
            case 'week':
                return Carbon::now($tz)->addWeeks($time)->toDateString();
            break;
            case 'weeks':
                return Carbon::now($tz)->addWeeks($time)->toDateString();
            break;
            case 'month':
                return Carbon::now($tz)->addMonths($time)->toDateString();
            break;
            case 'months':
                return Carbon::now($tz)->addMonths($time)->day(1)->toDateString();
            break;
            case 'year':
                return Carbon::now($tz)->addYears($time)->toDateString();
            break;
            case 'years':
                return Carbon::now($tz)->addYears($time)->toDateString();
            break;
            case 'century':
                return Carbon::now($tz)->addCenturies($time)->toDateString();
            break;
            case 'centuries':
                return Carbon::now($tz)->addCenturies($time)->toDateString();
            break;
            default:
                return Carbon::now($tz)->addHours($time)->toDateString();
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
