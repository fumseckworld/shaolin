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
use Imperium\Auth\Exceptions\OauthExceptions;
use Imperium\Databases\Dumper\Databases\MySQLDatabase;
use Imperium\Databases\Dumper\Databases\PostgreSQLDatabase;
use Imperium\Databases\Dumper\Databases\SQLiteDatabase;
use Imperium\Databases\Dumper\Tables\MySQLTable;
use Imperium\Databases\Dumper\Tables\PostgreSQLTable;
use Imperium\Databases\Dumper\Tables\SQLiteTable;
use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Query\Query;
use Imperium\Databases\Eloquent\Tables\Table;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\File\File;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Form\Form;
use Imperium\Html\Pagination\Pagination;
use Imperium\Html\Records\Records;
use Intervention\Image\ImageManager;
use PragmaRX\Google2FA\Google2FA;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;

if (!exist('generateKey'))
{
    /**
     * generate secret key
     *
     * @return string
     */
    function generateKey() : string
    {
        return (new Google2FA())->generateSecretKey();
    }
}
if (!exist('records'))
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
     */
    function records(string $driver, string $class, Table $instance, string $table, string $editPrefix, string $deletePrefix, string $orderBy, string $editText, string $deleteText, string $editClass, string $deleteClass, string $editIcon, string $deleteIcon, int $limit, int $current, string $paginationUrl, PDO $pdo, int $formType, string $searchPlaceholder, string $confirmDeleteText, string $startPaginationText, string $endPaginationText, string $updatePaginationPlaceholder,bool $framework = false): string
    {
        return Records::show($driver,$class,$instance,$table,$editPrefix,$deletePrefix,$orderBy,$editText,$deleteText,$editClass,$deleteClass,$editIcon,$deleteIcon,$limit,$current,$paginationUrl,$pdo,$formType,$searchPlaceholder,$confirmDeleteText,$startPaginationText,$endPaginationText,$updatePaginationPlaceholder,$framework);
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

if (!exist('themes'))
{
    /**
     * get all bootswatch themes
     *
     * @return array
     */
    function themes(): array
    {
        return array (
            'cerulean',
            'cosmo',
            'cyborg',
            'darkly',
            'flatly',
            'journal',
            'litera',
            'lumen',
            'lux',
            'materia',
            'minty',
            'pulse',
            'sandstone',
            'simplex',
            'slate',
            'solar',
            'spacelab',
            'superhero',
            'united',
            'yeti',
            'bootstrap',
        );
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
            return $_SESSION[$key];

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
            return $_COOKIE[$key];

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
            return htmlentities($_GET[$key]);

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
            return htmlentities($_POST[$key]);

        return '';
    }
}
if (!exist('generate'))
{
    /**
     * generate a form to edit or create a record
     *
     * @param int $type
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
    function generate(int $type,string $formId,string $class,string $action,string $table,Table $instance,string $submitText,string $submitClass,string $submitIcon,string $submitId,string $csrfToken = '',int $mode = Form::CREATE,int $id = 0): string
    {
        return form($type)->start($action,$formId,$class)->csrf($csrfToken)->generate($table,$instance,$submitText,$submitClass,$submitId,$submitIcon,$mode,$id);
    }
}

if (!exist('root'))
{
    /**
     * get the connexion with all rights
     *
     * @param string $driver
     * @param string $password
     * @return null|PDO
     */
    function root(string $driver,string $password = '')
    {
        switch ($driver)
        {
            case Connexion::MYSQL:
                return connect($driver,'','root',$password);
            break;
            case Connexion::POSTGRESQL:
                return connect($driver,'','postgres',$password);
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
     */
    function getCurrentBranch(string $repository): string
    {
        return (new GitRepository($repository))->getCurrentBranchName();
    }
}
if (!exist('checkCode'))
{
    /**
     * check if a code is valid
     *
     * @param string $secret
     * @param string $code
     *
     * @return bool|int
     * @throws OauthExceptions
     */
    function checkCode(string $secret,string $code)
    {
        if (strlen($code) != 6)
            throw OauthExceptions::codeLengthIncorrect();
        else
            return (new Google2FA())->verifyKey($secret,$code);

    }
}

if (!exist('generateQrCode'))
{
    /**
     * generate Qr code
     *
     * @param string $company
     * @param string $username
     * @param string $secret
     *
     * @param int $size
     * @return string
     */
    function generateQrCode(string $company,string $username,string $secret,int $size = 200) : string
    {
        return  (new Google2FA())->getQRCodeGoogleUrl($company,$username,$secret,$size);
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
        try {
           return user($driver, $username, $current)->updatePassword($username, $new);
        }catch (PDOException $e)
        {
            return false;
        }
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

if (!exist('restore'))
{
    /**
     * restore a database
     *
     * @param Base $instance
     * @param string $base
     * @param string $sqlFile
     *
     * @return bool
     */
    function restore(Base $instance,string $base,string $sqlFile): bool
    {
        return $instance->restore($base,$sqlFile);
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
            case Connexion::ORACLE:
               return execute($connexion,"CREATE DATABASE $database CHARACTER SET $charset");
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
        if ($query->execute())
        {
            $data = $query->fetchAll($fetchStyle);

            if ($query->closeCursor())
            {
                return $data;
            }
        }
        return array();
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
        if (is_null($connexion))
            return false;

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
            case Connexion::ORACLE:
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
     * @param int $type
     *
     * @return Form
     */
    function form(int $type = Form::BOOTSTRAP)
    {
        if (has($type,[Form::BOOTSTRAP,Form::FOUNDATION],true))
            return Form::create()->setType($type);
        else
            return Form::create()->setType(Form::BOOTSTRAP);
    }
}

if (!exist('dumper'))
{
    /**
     * dump a database or a table
     *
     * @param string $driver
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $dumpPath
     * @param int    $mode
     * @param string $table
     *
     * @return bool
     */
    function dumper(string $driver, string $username, string $password, string $database, string $dumpPath, int $mode = Eloquent::MODE_DUMP_DATABASE, string $table ='')
    {
        $filename = $mode == Eloquent::MODE_DUMP_DATABASE ? "$dumpPath/$database.sql" : "$dumpPath/$table.sql";

        switch ($driver)
        {
            case Connexion::MYSQL:
                if ($mode == Eloquent::MODE_DUMP_DATABASE)
                   MySQLDatabase::dump()->setDbName($database)->setUserName($username)->setPassword($password)->dumpToFile("$dumpPath/$database.sql",$dumpPath);
                else
                    MySQLTable::dump()->setTable($table)->setUserName($username)->setPassword($password)->setDbName($database)->dumpToFile("$dumpPath/$table.sql",$dumpPath);
            break;
            case Connexion::POSTGRESQL:
                if ($mode == Eloquent::MODE_DUMP_DATABASE)
                   PostgreSQLDatabase::dump()->setDbName($database)->setUserName($username)->setPassword($password)->dumpToFile("$dumpPath/$database.sql",$dumpPath);
                else
                    PostgreSQLTable::dump()->setTable($table)->setUserName($username)->setPassword($password)->setDbName($database)->dumpToFile("$dumpPath/$table.sql",$dumpPath);
            break;
            case Connexion::SQLITE:
                if ($mode == Eloquent::MODE_DUMP_DATABASE)
                   SQLiteDatabase::dump()->setDbName($database)->dumpToFile("$dumpPath/$database.sql",$dumpPath);
                else
                    SQLiteTable::dump()->setTable($table)->setDbName($database)->dumpToFile("$dumpPath/$table.sql",$dumpPath);
            break;
            default:
                return false;
            break;
        }
       return File::download($filename) != false;
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
     * @param int $type
     *
     * @return string
     */
    function pagination(int $perPage,string $instance,int $current,int $total,string $startChar,string $endChar,string $ulClass = 'pagination',string $startCssClass = 'page-item',string $endCssClass = 'page-item',int $type = 1): string
    {
        return Pagination::paginate($perPage,$instance)->setTotal($total)->setStartChar($startChar)->setEndChar($endChar)->setUlCssClass($ulClass)->setStartCssClass($startCssClass)->setEndCssClass($endCssClass)->setCurrent($current)->setType($type)->get('');
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
        if (is_null($connexion))
            return false;

        switch($driver)
        {
            case Connexion::MYSQL:
                return execute($connexion,"CREATE USER '$user'@'localhost' IDENTIFIED BY '$password' $rights");
            break;
            case Connexion::POSTGRESQL:
                return execute($connexion,"CREATE ROLE $user PASSWORD '$password' $rights");
            break;
            case Connexion::ORACLE:
                return execute($connexion,"CREATE ROLE $user IDENTIFIED BY '$password' $rights");
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

if (!exist('faGroup'))
{
    /**
     * generate a link icon group area
     *
     * @param array $icons
     * @param array $urls
     * @param array $text
     * @param array $class
     * @param string $containerClass
     *
     * @return null|string
     */
    function faGroup(array $icons, array $urls ,array $text, array $class, string $containerClass = 'list-group')
    {
        $total = count($icons);

        if ($total > 0 && count($urls) == $total && count($text) == $total && count($class) == $total)
        {
            $fa = '<div class="'.$containerClass.'">';

            for ($i=0;$i<count($icons);++$i)
                $fa .= '<a class="'.$class[$i].'" href="'.$urls[$i].'">'.$icons[$i] .' '.$text[$i].'</a>';


            $fa .= '</div>';

            return $fa;
        }
        return null;
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
