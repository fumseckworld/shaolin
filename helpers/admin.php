<?php


use Imperium\Security\Csrf\Csrf;
use Whoops\Run;
use Imperium\App;
use Faker\Factory;
use Faker\Generator;
use Imperium\File\File;
use DI\NotFoundException;
use Imperium\Debug\Dumper;
use DI\DependencyException;
use Imperium\Config\Config;
use Imperium\Connexion\Connect;
use Imperium\Exception\Kedavra;
use Sinergi\BrowserDetector\Os;
use Imperium\Container\Container;
use Imperium\Security\Hashing\Hash;
use Whoops\Handler\PrettyPageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Imperium\Flash\Flash;
if (!function_exists('csrf_field'))
{
	
	/**
	 * 
	 * Generate a crsf token
	 * 
	 * @method csrf_field
	 *
	 * @return string
	 * 
	 */
	function csrf_field(): string
	{
		$value = (new Csrf(app()->session()))->token();

		return '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $value . '">';
	}
}

if (!function_exists('to'))
{
	
	/**
	 * 
	 * Redirect to a url
	 * 
	 * @method to
	 *
	 * @param  string 	$url
	 * @param  string 	$message
	 * @param  bool 	$success
	 *
	 * @return RedirectResponse
	 * 
	 */
	function to(string $url, string $message = '', bool $success = true): RedirectResponse
	{
		if (def($message))
		{
			$flash = new Flash(app()->session());

			$success ? $flash->success($message) : $flash->failure($message);
		}
		return (new RedirectResponse($url))->send();
	}
}

if (!function_exists('message'))
{
	/**
	 *
	 * Load a html email content
	 *
	 * @param string $filename
	 *
	 * @throws Kedavra
	 * @return string
	 *
	 */
	function message(string $filename): string
	{
		return (new File(EMAIL . DIRECTORY_SEPARATOR . $filename))->read();
	}
}

if (! function_exists('app'))
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

if (!function_exists('faker'))
{
	
	/**
	 * faker
	 *
	 * @param  mixed $locale
	 *
	 * @return Generator
	 */
	function faker(string $locale = 'en_US'): Generator
	{
		return Factory::create($locale);
	}
}

if (! function_exists('db'))
{

/**
 *
 * Get a db config value
 *
 * @method db
 * 
 * @param string $key
 *
 * @throws Kedavra
 * 
 * @return mixed
 *
 */
function db(string $key)
{
	return config('db', $key);
}
}

if (! function_exists('config'))
{
/**
 *
 * Get a config value
 *
 * @method config
 * 
 * @param string $file
 * @param mixed  $key
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


if (! function_exists('def'))
{

/**
 *
 * Check if all values are define
 *
 * @method def
 *
 * @param mixed $values
 * 
 * @return bool
 *
 */
function def(...$values): bool
{
	foreach ($values as $value)
	{
		if (!isset($value) || empty($value))
			return false;
	}


	return true;
}
}

if (!function_exists('update_file_values'))
{

	/**
	 *
	 * Update a value in a file
	 *
	 * @param string   $filename
	 * @param string   $delimiter
	 * @param string[] $values
	 *
	 * @throws Kedavra
	 *
	 * @return bool
	 *
	 */
	function update_file_values(string $filename, string $delimiter, string ...$values): bool
	{
		$keys = (new File($filename))->keys($delimiter);

		return (new File($filename, EMPTY_AND_WRITE_FILE_MODE))->change_values($keys, $values, $delimiter);
	}
}


if (!function_exists('string_parse'))
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


if (!function_exists('insert_into'))
{
	/**
	 *
	 * @param   $model
	 * @param string $table
	 * @param mixed  ...$values
	 *
	 * @throws Kedavra
	 * @return string
	 *
	 */
	function insert_into($model, string $table, array $values): string
	{
		$instance = $model->from($table);

		$x = collect($instance->columns())->join(',');

		$data = "INSERT INTO $table ($x) VALUES (";

		$primary = $instance->primary();

		foreach ($values as $k => $v)
		{
			if (different($v, $primary))
			{
				if (is_numeric($v))
					append($data, $v . ' ,'); else
					append($data, $model->pdo()->quote($v) . ', ');
			} else
			{
				if ($instance->check(MYSQL) || $instance->check(SQLITE))
					append($data, 'NULL, '); 
				else
					append($data, "DEFAULT, ");

			}
		}

		$data = trim($data, ', ');

		append($data, ')');

		return $data;

	}
}

if (!function_exists('assign'))
{
	/**
	 *
	 * Assign a value in a variable by a condition
	 *
	 * @method assign
	 *
	 * @param bool  $condition
	 * @param mixed $variable
	 * @param mixed $value
	 *
	 */
	function assign(bool $condition, &$variable, $value)
	{
		if ($condition)
		{
			$variable = $value;
		}
	}
}

if (! function_exists('not_def'))
{

	/**
	 *
	 * Check if all values are not define
	 *
	 * @method not_def
	 *
	 * @param mixed $values
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

if (! function_exists('request'))
{
	/**
	 * 
	 * Return an instance of the request
	 * 
	 * @return Request
	 * 
	 */
	function request(): Request
	{
		return Request::createFromGlobals();
	}
}

if (! function_exists('https'))
{
	
	/**
	 * 
	 * Check if the request is secure
	 * 
	 * @method https
	 *
	 * @return bool
	 * 
	 */
	function https(): bool
	{
		return request()->isSecure();
	}
}


if (! function_exists('equal'))
{

	/**
	 * 
	 * Check if variables are equals
	 * 
	 * @method equal
	 *
	 * @param  mixed $parameter
	 * @param  mixed $expected
	 * @param  bool $run_exception
	 * @param  string $message
	 * 
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function equal($parameter, $expected,bool $run_exception = false, string $message = ''): bool
	{
		$x = strcmp($parameter, $expected) === 0;

		is_true($x, $run_exception, $message);

		return $x;
	}
}


if (! function_exists('is_not_false'))
{
	
	/**
	 * 
	 * 
	 * Check if data is not equal to false
	 * 
	 * @method is_not_false
	 *
	 * @param  mixed $data
	 * @param  bool $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra	
	 * 
	 * @return bool
	 * 
	 */
	function is_not_false($data, bool $run_exception = false, string $message = ''): bool
	{
		$x = $data !== false;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (! function_exists('is_not_true'))
{
	
	/**
	 * 
	 * Check if data is not equal to true
	 * 
	 * @method is_not_true
	 *
	 * @param  mixed  $data
	 * @param  bool   $run_exception
	 * @param  string $message
	 * 
	 * @throws Kedavra	
	 * 
	 * @return bool
	 * 
	 */
	function is_not_true($data, bool $run_exception = false, string $message = ''): bool
	{
		$x = $data !== true;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (! function_exists('is_false'))
{
	
	/**
	 * 
	 * Check if data equal false
	 * 
	 * @method is_false
	 *
	 * @param  mixed $data
	 * @param  bool   $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function is_false($data, bool $run_exception = false, string $message = ''): bool
	{
		$x = $data === false;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (! function_exists('is_true'))
{
	
	/**
	 * 
	 * Check if data equal true
	 * 
	 * @method is_true
	 *
	 * @param  mixed  $data
	 * @param  bool	  $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function is_true($data, bool $run_exception = false, string $message = ''): bool
	{

		$x = $data === true;

		if ($run_exception && $x)
			throw new Kedavra($message);

		return $x;

	}
}

if (! function_exists('different'))
{
	
	/**
	 * 
	 * Check if values are different
	 * 
	 * @method different
	 *
	 * @param  mixed $parameter
	 * @param  mixed $expected
	 * @param  bool   $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function different($parameter, $expected, $run_exception = false, string $message = ''): bool
	{
		$x = strcmp($parameter, $expected) !== 0;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (!function_exists('server'))
{
	/**
	 *
	 * Get a server key
	 *
	 * @method server
	 *
	 * @param string $key
	 *
	 * @param string $value
	 *
	 * @return string
	 * 
	 */
	function server(string $key, string $value = ''): string
	{
		return isset($_SERVER[$key]) && !empty($_SERVER[$key]) ? $_SERVER[$key] : $value;
	}
}

if (!function_exists('post'))
{
	/**
	 *
	 * Get a post key
	 *
	 * @method post
	 *
	 * @param string $key
	 *
	 * @param string $value
	 *
	 * @return string
	 * 
	 */
	function post(string $key, string $value = ''): string
	{
		return isset($_POST[$key]) && !empty($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8', true) : $value;
	}
}


if (!function_exists('get'))
{
	/**
	 * 
	 * Get a get value
	 *
	 * @method get
	 *
	 * @param string $key
	 *
	 * @param string $value
	 *
	 * @return string
	 * 
	 */
	function get(string $key, string $value = ''): string
	{
		return isset($_GET[$key]) && !empty($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8', true) : $value;
	}
}


if (!function_exists('connect'))
{

	/**
	 * 
	 * Connect to the database
	 * 
	 * @method connect
	 *
	 * @param  mixed $driver
	 * @param  mixed $base
	 * @param  mixed $user
	 * @param  mixed $password
	 * @param  mixed $host
	 * @param  mixed $dump_path
	 *
	 * @return Connect
	 * 
	 */
	function connect(string $driver, string $base, string $user, string $password, string $host, string $dump_path): Connect
	{
		return new Connect($driver, $base, $user, $password, $host, $dump_path);
	}
}


if (!function_exists('superior'))
{
	
	/**
	 * 
	 * 
	 * Chek if parmeter is superior to expected 
	 * 
	 * @method superior
	 *
	 * @param  mixed  $parameter
	 * @param  mixed  $expected
	 * @param  bool   $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function superior($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
	{

		$x = is_array($parameter) ? count($parameter) > $expected : $parameter > $expected;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (!function_exists('superior_or_equal'))
{
	/**
	 * 
	 * 
	 * Check if parameter is superior or equal to expected
	 * 
	 * @method superior_or_equal
	 *
	 * @param  mixed  $parameter
	 * @param  mixed  $expected
	 * @param  bool   $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function superior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
	{

		$x = is_array($parameter) ? count($parameter) >= $expected : $parameter >= $expected;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (!function_exists('inferior'))
{
	
	/**
	 * 
	 * check if parameter is inferior to expected
	 * 
	 * @method inferior
	 *
	 * @param  mixed  $parameter
	 * @param  mixed  $expected
	 * @param  bool   $run_exception
	 * @param  string $message
	 *
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function inferior($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
	{

		$x = is_array($parameter) ? count($parameter) < $expected : $parameter < $expected;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (!function_exists('inferior_or_equal'))
{
	
	/**
	 * 
	 * Check if parameter is inferior or equal to expected
	 * 
	 * @method inferior_or_equal
	 *
	 * @param  mixed  $parameter
	 * @param  mixed  $expected
	 * @param  bool   $run_exception
	 * @param  string $message
	 * 
	 * @throws Kedavra
	 * 
	 * @return bool
	 * 
	 */
	function inferior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
	{
		$x = is_array($parameter) ? count($parameter) <= $expected : $parameter <= $expected;

		is_true($x, $run_exception, $message);

		return $x;
	}
}

if (!function_exists('whoops'))
{
	/**
	 *
	 * @return Run
	 */
	function whoops(): Run
	{
		return (new Run())->appendHandler(new PrettyPageHandler)->register();
	}
}

if (!function_exists('os'))
{
	/**
	 *
	 * Return an instance of Os or os name
	 *
	 * @method os
	 *
	 * @param bool $get_name
	 *
	 * @return Os|string
	 *
	 */
	function os(bool $get_name = false)
	{
		return $get_name ? (new Os())->getName() : new Os();
	}
}


if (!function_exists('commands'))
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
	function commands(): array
	{

		$commands = COMMAND;

		$namespace = 'Shaolin\\' . 'Commands';

		$data = glob($commands . DIRECTORY_SEPARATOR . '*.php');

		$commands = collect();

		foreach ($data as $c)
		{
			$command = collect(explode('/', $c))->last();

			$command = collect(explode('.', $command))->first();

			$command = "$namespace\\$command";

			$commands->push(new $command());
		}

		return $commands->all();
	}
}

if (!function_exists('controllers'))
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
	function controllers(): array
	{
		$dir = CONTROLLERS;

		$controllers = collect(File::search("$dir" . DIRECTORY_SEPARATOR . '*.php'));

		$data = collect();

		if ($controllers)
		{
			foreach ($controllers as $controller)
				$data->push(collect(explode('.', collect(explode(DIRECTORY_SEPARATOR, $controller))->last()))->first());
		}
		return $data->all();
	}
}

if (!function_exists('d'))
{
	/**
	 *
	 * Debug values and die
	 *
	 * @method d
	 *
	 * @param mixed $values The values to debug
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
if (!function_exists('debug'))
{
	
	/**
	 * 
	 * Debug values only if condition match
	 * 
	 * @method debug
	 *
	 * @param  bool $condition
	 * @param  mixed $values
	 *
	 * @return void
	 * 
	 */
	function debug(bool $condition, ...$values)
	{
		if ($condition)
		{
			d($values);
		}
	}
}

if (!function_exists('bcrypt'))
{
	/**
	 *
	 * Hash a value
	 *
	 * @param string $value
	 *
	 * @throws Kedavra
	 *
	 * @return string
	 *
	 */
	function bcrypt(string $value): string
	{
		return (new Hash($value))->generate();
	}
}

if (!function_exists('check'))
{
	
	/** 
	 *
	 * Check the password
	 * 
	 * @method check
	 *
	 * @param  mixed $plain_text_password
	 * @param  mixed $hash_value
	 *
	 * @return bool
	 * 
	 */
	function check(string $plain_text_password, string $hash_value): bool
	{
		return (new Hash($plain_text_password))->valid($hash_value);
	}
}