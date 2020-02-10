<?php

use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Application\App;
use Eywa\Configuration\Config;
use Eywa\Database\Connexion\Connect;
use Eywa\Debug\Dumper;
use Eywa\Exception\Kedavra;
use Eywa\File\File;
use Eywa\Http\Request\Request;
use Eywa\Ioc\Container;
use Eywa\Security\Authentication\Auth;
use Eywa\Security\Crypt\Crypter;
use Eywa\Security\Hashing\Hash;
use Eywa\Session\Session;
use Faker\Factory;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


if( ! function_exists('csrf_field'))
{
    /**
     *
     * Generate a crsf token
     *
     * @method csrf_field
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function csrf_field() : string
    {
        if (php_sapi_name() == 'cli')
        {
            return '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . bin2hex(random_bytes(16)) . '">';
        }
        $session = new Session();

        $server = $session->has('server') ?  $session->get('server') :  $session->set('server',(new Crypter())->encrypt(Request::generate()->server()->get('SERVER_NAME','eywa')))->get('server');

        $x = bin2hex(random_bytes(16));
        $csrf = $session->set('csrf',$x)->get('csrf');
        $token = "$server@$csrf";

        $session->set(CSRF_TOKEN,$token);

        return '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $token.'">';
    }
}

if (!function_exists('form_invalid'))
{
    /**
     *
     * Check if the form was submited in the correct server
     *
     * @return bool
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function form_invalid(): bool
    {
        $session = new Eywa\Session\Session();
        if ($session->has(CSRF_TOKEN))
        {
            return different((new Crypter())->decrypt($session->get('server')),Request::generate()->server()->get('SERVER_NAME','eywa'),true,"Form is not valid") || different($session->get('csrf'),collect(explode('@',$session->get(CSRF_TOKEN)))->last(),true,"Csrf token was not found");
        }
        throw new Kedavra('Csrf token was not found');
    }
}


if( ! function_exists('base'))
{
    /**
     *
     * Get absolute path
     *
     * @param string[] $dirs
     * @return string
     */
    function base(string ...$dirs) : string
    {


        $base = cli() ? realpath('./')  : dirname(realpath('./'));


        if (def($dirs))
        {
            foreach ($dirs as $dir)
                if (def($dir))
                {
                    append($base,DIRECTORY_SEPARATOR,$dir);
                }


        }
        return $base ;
    }
}

if (!function_exists('i18n'))
{
    /**
     *
     * @param string $locale
     *
     */
    function i18n(string $locale)
    {
        $domain = 'messages';

        if (defined('LC_MESSAGES'))
        {
            putenv("LC_COLLATE={$locale}");
            setlocale(LC_COLLATE, $locale);
        } else {
            putenv("LC_ALL={$locale}"); // windows
            setlocale(LC_ALL, $locale);
        }

        bindtextdomain($domain,base('po'));
        textdomain($domain);
  }
}
if( ! function_exists('message'))
{
    /**
     *
     * Load a html email content
     *
     * @param  string  $filename
     *
     * @throws Kedavra
     * @return string
     *
     */
    function message(string $filename) : string
    {
        return (new File(base('app','Email',$filename)))->read();
    }
}


if( ! function_exists('app'))
{
    /**
     *
     * Get the application instance
     *
     * @return App
     *
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     *
     */
    function app(): App
    {
        return ioc(App::class)->get();
    }
}

if (!function_exists('ioc'))
{
    /**
     * @param string $key
     *
     * @return Container
     *
     * @throws Exception
     *
     */
    function ioc(string $key): Container
    {
        return Container::ioc($key);
    }
}

if( ! function_exists('faker'))
{
    /**
     * faker
     *
     * @param mixed $locale
     *
     * @return \Faker\Generator
     */
    function faker(string $locale = 'en_US') : \Faker\Generator
    {
       return  Factory::create($locale);
    }
}
if( ! function_exists('db'))
{
    /**
     *
     * Get a db config.yaml value
     *
     * @method db
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    function db(string $key)
    {
        return env($key);
    }
}
if( ! function_exists('config'))
{
    /**
     *
     * Get a config.yaml value
     *
     * @method config.yaml
     *
     * @param  string  $file
     * @param  mixed   $key
     *
     * @throws Kedavra
     *
     * @return mixed
     *
     */
    function config(string $file, $key)
    {
        return (new Config($file, $key))->value();
    }
}
if( ! function_exists('def'))
{
    /**
     *
     * Check if all values are define
     *
     * @method def
     *
     * @param  mixed  $values
     *
     * @return bool
     *
     */
    function def(...$values) : bool
    {
        foreach($values as $value)
        {
            if( ! isset($value) || empty($value))
                return false;
        }

        return true;
    }
}
if( ! function_exists('update_file_values'))
{
    /**
     *
     * Update a value in a file
     *
     * @param  string    $filename
     * @param  string    $delimiter
     * @param  string[]  ...$values
     *
     * @throws Kedavra
     * @return bool
     */
    function update_file_values(string $filename, string $delimiter, string ...$values) : bool
    {

        $keys = (new File($filename))->keys($delimiter);

        return (new File($filename, EMPTY_AND_WRITE_FILE_MODE))->change_values($keys, $values, $delimiter);
    }
}
if( ! function_exists('history'))
{
    /**
     *
     * Go to last page
     *
     * @throws Kedavra
     *
     * @return string
     *
     */
    function history(): string
    {
        return  '<button onclick="window.history.back()" class="'.config('history','class').'">'.config('history','text').'</button>';
    }
}
if( ! function_exists('string_parse'))
{
    /**
     *
     * Split a sing to array
     *
     * @param  string  $data
     *
     * @return array
     *
     */
    function string_parse(string $data) : array
    {

        return preg_split('/\s+/', $data);
    }
}
if( ! function_exists('instance'))
{
    /**
     *
     * @return Connect
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    function instance() : Connect
    {
        return connect(db('driver'), db('base'), db('username'), db('password'), db('host'), db('dump'));
    }

}

if( ! function_exists('assign'))
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
    function assign(bool $condition, &$variable, $value)
    {

        if($condition)
        {
            $variable = $value;
        }
    }
}
if( ! function_exists('not_def'))
{
    /**
     *
     * Check if all values are not define
     *
     * @method not_def
     *
     * @param  mixed  $values
     *
     * @return bool
     *
     */
    function not_def(...$values) : bool
    {

        foreach($values as $value)
            if(def($value))
                return false;

        return true;
    }
}


if( ! function_exists('equal'))
{
    /**
     *
     * Check if variables are equals
     *
     * @method equal
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function equal($parameter, $expected, bool $run_exception = false, string $message = '') : bool
    {

        $x = strcmp($parameter, $expected) === 0;
        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('is_not_false'))
{
    /**
     *
     *
     * Check if data is not equal to false
     *
     * @method is_not_false
     *
     * @param  mixed   $data
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function is_not_false($data, bool $run_exception = false, string $message = '') : bool
    {

        $x = $data !== false;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('is_not_true'))
{
    /**
     *
     * Check if data is not equal to true
     *
     * @method is_not_true
     *
     * @param  mixed   $data
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function is_not_true($data, bool $run_exception = false, string $message = '') : bool
    {

        $x = $data !== true;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('is_false'))
{
    /**
     *
     * Check if data equal false
     *
     * @method is_false
     *
     * @param  mixed   $data
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function is_false($data, bool $run_exception = false, string $message = '') : bool
    {

        $x = $data === false;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('is_true'))
{
    /**
     *
     * Check if data equal true
     *
     * @method is_true
     *
     * @param  mixed   $data
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function is_true($data, bool $run_exception = false, string $message = '') : bool
    {

        $x = $data === true;

        if($run_exception && $x)
            throw new Kedavra($message);

        return $x;
    }
}
if( ! function_exists('different'))
{
    /**
     *
     * Check if values are different
     *
     * @method different
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function different($parameter, $expected, $run_exception = false, string $message = '') : bool
    {

        $x = strcmp($parameter, $expected) !== 0;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('server'))
{
    /**
     *
     * Get a server key
     *
     * @method server
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function server(string $key, $default = null): ?string
    {
        return array_key_exists($key,$_SERVER) ? htmlspecialchars($_SERVER[$key],ENT_QUOTES) : $default;
    }
}

if (!function_exists('obj'))
{
    function obj($obj): array
    {
        $x = collect();
        foreach ($obj as $k => $v)
        {
            $x->put($k,$v);
        }
        return $x->all();
    }
}
if( ! function_exists('post'))
{
    /**
     *
     * Get a post key
     *
     * @method post
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function post(string $key, $default = null): ?string
    {
       return  array_key_exists($key,$_POST) ? htmlspecialchars($_POST[$key],ENT_QUOTES) : $default;
    }
}

if( ! function_exists('get'))
{
    /**
     * Get a $_GET value
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function get(string $key, $default = null):?string
    {
        return  array_key_exists($key,$_GET) ? htmlspecialchars($_GET[$key],ENT_QUOTES) : $default;

    }
}

if( ! function_exists('cookie'))
{
    /**
     * Get a $_GET value
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function cookie(string $key, $default = null):?string
    {
        return  array_key_exists($key,$_COOKIE) ? htmlspecialchars($_COOKIE[$key],ENT_QUOTES) : $default;
    }
}

if( ! function_exists('file'))
{
    /**
     * Get a $_GET value
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function file(string $key, $default = null):?string
    {

        return  array_key_exists($key,$_FILES) ? htmlspecialchars($_FILES[$key],ENT_QUOTES) : $default;
    }
}
if( ! function_exists('connect'))
{
    /**
     *
     * @param string $driver
     * @param string $base
     * @param string $user
     * @param string $password
     * @param int $port
     * @param array $options
     * @param string $host
     *
     * @return Connect
     *
     * @throws Kedavra
     *
     */
    function connect(string $driver, string $base, string $user = '', string $password = '', int $port= 3306,array $options = [], string $host = 'localhost') : Connect
    {
        return new Connect($driver,$base,$user,$password,$port,$options,$host);
    }
}
if( ! function_exists('superior'))
{
    /**
     *
     *
     * Chek if parmeter is superior to expected
     *
     * @method superior
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function superior($parameter, int $expected, bool $run_exception = false, string $message = '') : bool
    {

        $x = is_array($parameter) ? count($parameter) > $expected : $parameter > $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('superior_or_equal'))
{
    /**
     *
     *
     * Check if parameter is superior or equal to expected
     *
     * @method superior_or_equal
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function superior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = '') : bool
    {

        $x = is_array($parameter) ? count($parameter) >= $expected : $parameter >= $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('inferior'))
{
    /**
     *
     * check if parameter is inferior to expected
     *
     * @method inferior
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function inferior($parameter, int $expected, bool $run_exception = false, string $message = '') : bool
    {

        $x = is_array($parameter) ? count($parameter) < $expected : $parameter < $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('inferior_or_equal'))
{
    /**
     *
     * Check if parameter is inferior or equal to expected
     *
     * @method inferior_or_equal
     *
     * @param  mixed   $parameter
     * @param  mixed   $expected
     * @param  bool    $run_exception
     * @param  string  $message
     *
     * @throws Kedavra
     *
     * @return bool
     *
     */
    function inferior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = '') : bool
    {

        $x = is_array($parameter) ? count($parameter) <= $expected : $parameter <= $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if( ! function_exists('whoops'))
{
    /**
     *
     * @return Run
     */
    function whoops() : Run
    {

        return (new Run())->appendHandler(new PrettyPageHandler)->register();
    }
}

if( ! function_exists('commands'))
{
    /**
     *
     * Display all comand
     *
     * @method command
     *
     * @return array
     *
     */
    function commands() : array
    {

        $commands = 'app' . DIRECTORY_SEPARATOR . 'Console';
        $namespace = 'App\Console';
        $data = glob($commands . DIRECTORY_SEPARATOR . '*.php');
        $commands = collect();
        foreach($data as $c)
        {
            $command = collect(explode(DIRECTORY_SEPARATOR, $c))->last();
            $command = collect(explode('.', $command))->first();
            $command = "$namespace\\$command";
            $commands->set(new $command());
        }

        return $commands->all();
    }
}
if( ! function_exists('controllers'))
{
    /**
     *
     * Display all controller
     *
     * @method controller
     *
     * @return array
     *
     */
    function controllers() : array
    {

        $dir = 'app' . DIRECTORY_SEPARATOR . 'Controllers';
        $controllers = collect(File::search("$dir" . DIRECTORY_SEPARATOR . '*.php'));
        $data = collect();
        if($controllers)
        {
            foreach($controllers as $controller)
                $data->push(collect(explode('.', collect(explode(DIRECTORY_SEPARATOR, $controller))->last()))->first());
        }

        return $data->all();
    }
}
if( ! function_exists('d'))
{
    /**
     *
     * Debug values and die
     *
     * @method d
     *
     * @param  mixed  $values  The values to debug
     *
     */
    function d(...$values)
    {

        $dumper = new Dumper();
        foreach($values as $value)
            $dumper->dump($value);
        die();
    }
}
if( ! function_exists('debug'))
{
    /**
     *
     * Debug values only if condition match
     *
     * @method debug
     *
     * @param  bool   $condition
     * @param  mixed  $values
     *
     * @return void
     *
     */
    function debug(bool $condition, ...$values)
    {

        if($condition)
        {
            d($values);
        }
    }
}
if( ! function_exists('bcrypt'))
{
    /**
     *
     * Hash a value
     *
     * @param  string  $value
     *
     * @throws Kedavra
     *
     * @return string
     *
     */
    function bcrypt(string $value) : string
    {
        return (new Hash($value))->generate();
    }
}
if( ! function_exists('logged'))
{
    /**
     * @return bool
     */
    function logged(): bool
    {
        return  cli() ? false: (new Auth(new Session()))->connected();
    }

}

if( ! function_exists('guest'))
{
    /**
     * @return string
     */
    function guest(): string
    {
        return  ! logged();
    }

}
if( ! function_exists('check'))
{
    /**
     *
     * Check the password
     *
     * @method check
     *
     * @param  mixed  $plain_text_password
     * @param  mixed  $hash_value
     *
     * @throws Kedavra
     * @return bool
     *
     */
    function check(string $plain_text_password, string $hash_value) : bool
    {
        return (new Hash($plain_text_password))->valid($hash_value);
    }
}
