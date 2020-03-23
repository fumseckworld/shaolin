<?php

declare(strict_types=1);


use Eywa\Application\App;
use Eywa\Configuration\Config;
use Eywa\Database\Connexion\Connect;
use Eywa\Database\Query\Sql;
use Eywa\Debug\Dumper;
use Eywa\Exception\Kedavra;
use Eywa\File\File;
use Eywa\Http\Request\Request;
use Eywa\Ioc\Ioc;
use Eywa\Security\Authentication\Auth;
use Eywa\Security\Hashing\Hash;
use Eywa\Session\Session;
use Faker\Factory;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

if (!function_exists('controllers_directory')) {
    /**
     * @return array
     */
    function controllers_directory(): array
    {
        $x =  scandir(base('app', 'Controllers'));
        return is_bool($x) ? [] : collect($x)->del(['.','..'])->all();
    }
}
if (!function_exists('only')) {
    /**
     *
     * check if the request is secure
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function https():bool
    {
        return cli() ? false : Request::make()->secure();
    }
}
if (!function_exists('only')) {

    /**
     *
     * Execute a callback by a condition
     *
     * @param bool $condition
     * @param callable $callback
     * @param array $args
     *
     * @return mixed|null
     *
     */
    function only(bool $condition, callable $callback, array $args = [])
    {
        if ($condition) {
            return call_user_func_array($callback, $args);
        }
        return null;
    }
}
if (!function_exists('csrf_field')) {
    /**
     *
     * Generate a crsf token
     *
     * @return string
     *
     * @throws Exception
     *
     */
    function csrf_field(): string
    {
        if (cli()) {
            return  bin2hex(random_bytes(16));
        }

        return (new Eywa\Security\Csrf\Csrf(new Session()))->token();
    }
}

if (!function_exists('files')) {

    /**
     *
     * Get all files who mathes the pattern
     *
     * @param string $pattern
     *
     * @return array
     *
     */
    function files(string $pattern):array
    {
        $files = glob($pattern);

        return  is_bool($files) ? [] : $files;
    }
}
if (!function_exists('base')) {
    /**
     *
     * Get absolute path
     *
     * @param array<int,string> $dirs
     *
     * @return string
     *
     */
    function base(string ...$dirs): string
    {
        $base = cli() ? strval(realpath('.')) : dirname(strval(realpath('./')));

        if (def($dirs)) {
            foreach ($dirs as $dir) {
                if (def($dir)) {
                    append($base, DIRECTORY_SEPARATOR, $dir);
                }
            }
        }
        return $base;
    }
}

if (!function_exists('i18n')) {
    /**
     *
     * @param string $locale
     *
     * @throws Exception
     *
     */
    function i18n(string $locale): void
    {
        if (app()->detect()->windows()) {
        }

        if (app()->detect()->linux()) {
            putenv("LANG={$locale}");

            bindtextdomain('messages', base('po'));

            bind_textdomain_codeset('messages', 'UTF-8');

            textdomain('messages');
        }
    }
}
if (!function_exists('message')) {
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
        return (new File(base('app', 'Email', $filename)))->read();
    }
}


if (!function_exists('app')) {
    /**
     *
     * Get the application instance
     *
     * @return App
     *
     * @throws Exception
     *
     */
    function app(): App
    {
        return ioc(App::class);
    }
}
if (!function_exists('production')) {
    /**
     * @return Connect
     * @throws Kedavra
     * @throws Exception
     */
    function production(): Connect
    {
        return new Connect(strval(env('DB_DRIVER', 'mysql')), strval(env('DB_NAME', 'eywa')), strval(env('DB_USERNAME', 'eywa')), strval(env('DB_PASSWORD', 'eywa')), strval(env('DB_HOST', 'localhost')));
    }
}

if (!function_exists('development')) {
    /**
     * @return Connect
     * @throws Kedavra
     * @throws Exception
     */
    function development(): Connect
    {
        return new Connect(strval(env('DEVELOP_DB_DRIVER', 'mysql')), strval(env('DEVELOP_DB_NAME', 'ikran')), strval(env('DEVELOP_DB_USERNAME', 'ikran')), strval(env('DEVELOP_DB_PASSWORD', 'ikran')), strval(env('DEVELOP_DB_HOST', 'localhost')));
    }
}
if (!function_exists('sql')) {
    /**
     *
     * Get an instance of sql
     *
     * @param string $table
     *
     * @return Sql
     *
     * @throws Kedavra
     * @throws Exception
     *
     */
    function sql(string $table): Sql
    {
        $connexion = ioc(Connect::class);
        return (new Sql($connexion, $table));
    }
}

if (!function_exists('int')) {

    /**
     *
     * @param mixed $digit
     *
     * @return bool
     *
     */
    function int($digit):bool
    {
        if (is_int($digit)) {
            return true;
        } elseif (is_string($digit)) {
            return ctype_digit($digit);
        } else {
            // booleans, floats and others
            return false;
        }
    }
}

if (!function_exists('not_int')) {

    /**
     *
     * @param mixed $digit
     *
     * @return bool
     *
     */
    function not_int($digit):bool
    {
        return ! int($digit);
    }
}
if (!function_exists('ioc')) {
    /**
     *
     * @param string $key
     * @param array $args
     *
     * @return mixed
     *
     * @throws Kedavra
     * @throws ReflectionException
     *
     */
    function ioc(string $key, array $args = [])
    {
        return Ioc::get($key, $args);
    }
}

if (!function_exists('faker')) {
    /**
     *
     * Get an instance of faker
     *
     * @param string $locale
     *
     * @return Faker\Generator
     *
     */
    function faker(string $locale = 'en_US'): Faker\Generator
    {
        return Factory::create($locale);
    }
}
if (!function_exists('config')) {
    /**
     *
     * Get a config value
     *
     *
     * @param string $file
     * @param string $key
     *
     * @return mixed
     *
     * @throws Kedavra
     *
     */
    function config(string $file, string $key)
    {
        return (new Config($file, $key))->value();
    }
}
if (!function_exists('def')) {
    /**
     *
     * Check if all values are define
     *
     *
     * @param array<int, mixed> $values
     *
     * @return bool
     *
     */
    function def(...$values): bool
    {
        foreach ($values as $value) {
            if (!isset($value) || empty($value)) {
                return false;
            }
        }

        return true;
    }
}
if (!function_exists('history')) {
    /**
     *
     * Go to last page
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function history(): string
    {
        return '<button onclick="window.history.back()" class="' . config('history', 'class') . '">' . config('history', 'text') . '</button>';
    }
}

if (!function_exists('not_def')) {
    /**
     *
     * Check if all values are not define
     *
     * @param array<int, mixed> $values
     *
     * @return bool
     *
     */
    function not_def(...$values): bool
    {
        foreach ($values as $value) {
            if (def($value)) {
                return false;
            }
        }

        return true;
    }
}


if (!function_exists('equal')) {
    /**
     *
     * Check if variables are equals
     *
     * @param string $parameter
     * @param string $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function equal(string $parameter, string $expected, bool $run_exception = false, string $message = ''): bool
    {
        $x = strcmp($parameter, $expected) === 0;
        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('is_not_false')) {
    /**
     *
     *
     * Check if data is not equal to false
     *
     * @param mixed $data
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function is_not_false($data, bool $run_exception = false, string $message = ''): bool
    {
        $x = $data !== false;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('is_not_true')) {
    /**
     *
     * Check if data is not equal to true
     *
     * @param mixed $data
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function is_not_true($data, bool $run_exception = false, string $message = ''): bool
    {
        $x = $data !== true;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('is_false')) {
    /**
     *
     * Check if data equal false
     *
     * @param mixed $data
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function is_false($data, bool $run_exception = false, string $message = ''): bool
    {
        $x = $data === false;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('is_true')) {
    /**
     *
     * Check if data equal true
     *
     * @param mixed $data
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function is_true($data, bool $run_exception = false, string $message = ''): bool
    {
        $x = $data === true;

        if ($run_exception && $x) {
            throw new Kedavra($message);
        }

        return $x;
    }
}
if (!function_exists('different')) {
    /**
     *
     * Check if values are different
     *
     * @param string $parameter
     * @param string $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function different(string $parameter, string $expected, $run_exception = false, string $message = ''): bool
    {
        $x = strcmp($parameter, $expected) !== 0;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('server')) {
    /**
     *
     * Get a server key
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function server(string $key, $default = null): ?string
    {
        return array_key_exists($key, $_SERVER) ? htmlspecialchars($_SERVER[$key], ENT_QUOTES) : $default;
    }
}

if (!function_exists('post')) {
    /**
     *
     * Get a post key
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function post(string $key, $default = null): ?string
    {
        return array_key_exists($key, $_POST) ? htmlspecialchars($_POST[$key], ENT_QUOTES) : $default;
    }
}

if (!function_exists('get')) {
    /**
     * Get a $_GET value
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function get(string $key, $default = null): ?string
    {
        return array_key_exists($key, $_GET) ? htmlspecialchars($_GET[$key], ENT_QUOTES) : $default;
    }
}

if (!function_exists('cookie')) {
    /**
     * Get a $_GET value
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     *
     */
    function cookie(string $key, $default = null): ?string
    {
        return array_key_exists($key, $_COOKIE) ? htmlspecialchars($_COOKIE[$key], ENT_QUOTES) : $default;
    }
}

if (!function_exists('connect')) {
    /**
     *
     * @param string $driver
     * @param string $base
     * @param string $user
     * @param string $password
     * @param string $host
     *
     * @return Connect
     *
     * @throws Kedavra
     */
    function connect(string $driver, string $base, string $user = '', string $password = '', string $host = 'localhost'): Connect
    {
        return new Connect($driver, $base, $user, $password, $host);
    }
}
if (!function_exists('superior')) {
    /**
     *
     *
     * Chek if parmeter is superior to expected
     *
     * @param mixed $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function superior($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
    {
        $x = is_array($parameter) ? count($parameter) > $expected : $parameter > $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('superior_or_equal')) {
    /**
     *
     *
     * Check if parameter is superior or equal to expected
     *
     * @param mixed $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function superior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
    {
        $x = is_array($parameter) ? count($parameter) >= $expected : $parameter >= $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('inferior')) {
    /**
     *
     * check if parameter is inferior to expected
     *
     * @param mixed $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function inferior($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
    {
        $x = is_array($parameter) ? count($parameter) < $expected : $parameter < $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('inferior_or_equal')) {
    /**
     *
     * Check if parameter is inferior or equal to expected
     *
     * @param mixed $parameter
     * @param int $expected
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     * @throws Kedavra
     *
     */
    function inferior_or_equal($parameter, int $expected, bool $run_exception = false, string $message = ''): bool
    {
        $x = is_array($parameter) ? count($parameter) <= $expected : $parameter <= $expected;

        is_true($x, $run_exception, $message);

        return $x;
    }
}
if (!function_exists('whoops')) {
    /**
     *
     * @return Run
     */
    function whoops(): Run
    {
        return (new Run())->appendHandler(new PrettyPageHandler)->register();
    }
}

if (!function_exists('commands')) {
    /**
     *
     * Display all comand
     *
     * @return array
     *
     */
    function commands(): array
    {
        $commands = base('app', 'Console', '*.php');
        $namespace = 'App\Console';
        $data = files($commands);
        $commands = collect();
        foreach ($data as $c) {
            $command = collect(explode(DIRECTORY_SEPARATOR, $c))->last();
            $command = collect(explode('.', $command))->first();
            $command = "$namespace\\$command";
            $commands->set(new $command());
        }

        return $commands->all();
    }
}

if (!function_exists('controllers')) {
    /**
     *
     * Display all controller
     *
     * @param string $directory
     * @return array
     */
    function controllers(string $directory): array
    {
        if ($directory !== 'Controllers') {
            $controllers =files(base('app', 'Controllers', $directory, '*.php'));
        } else {
            $controllers = files(base('app', 'Controllers', '*.php'));
        }
        $data = collect();
        if ($controllers) {
            foreach ($controllers as $controller) {
                $data->push(collect(explode('.', collect(explode(DIRECTORY_SEPARATOR, $controller))->last()))->first());
            }
        }

        return $data->all();
    }
}
if (!function_exists('d')) {
    /**
     *
     * Debug values and die
     *
     * @param array<int, mixed> $values
     *
     */
    function d(...$values): void
    {
        $dumper = new Dumper();
        foreach ($values as $value) {
            $dumper->dump($value);
        }
        die();
    }
}
if (!function_exists('debug')) {
    /**
     *
     * Debug values only if condition match
     *
     * @param bool $condition
     * @param array<int, mixed> $values
     *
     * @return void
     *
     */
    function debug(bool $condition, ...$values)
    {
        if ($condition) {
            d($values);
        }
    }
}
if (!function_exists('secure_password')) {
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
    function secure_password(string $value): string
    {
        return (new Hash($value))->generate();
    }
}
if (!function_exists('logged')) {
    /**
     * @return bool
     */
    function logged(): bool
    {
        return cli() ? false : (new Auth(new Session()))->connected();
    }
}

if (!function_exists('guest')) {
    /**
     * @return bool
     */
    function guest(): bool
    {
        return !logged();
    }
}
if (!function_exists('check')) {
    /**
     *
     * Check the password
     *
     * @param string $plain_text_password
     * @param string $hash_value
     *
     * @return bool
     *
     * @throws Kedavra
     */
    function check(string $plain_text_password, string $hash_value): bool
    {
        return (new Hash($plain_text_password))->valid($hash_value);
    }
}
