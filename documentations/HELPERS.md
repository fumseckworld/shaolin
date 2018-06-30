# Helpers

```php
    
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
    connect(string $driver,string $database = '',string $username = '',$password = '') : ?PDO
    
   /**
    * update user password
    *
    * @param string $driver
    * @param string $username
    * @param string $currentPassword
    * @param string $newPassword
    *
    * @return bool
    */
    pass(string $driver,string $username,string $currentPassword,string $newPassword) : bool
    
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
    user(string $driver,string $username,string $password,array $hidden = []) : Users
    
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
    base(string $driver,string $base,string $username,string $password,string $dumpPath,array $hidden = [])
    
   /**
    * show databases, users, tables
    *
    * @param string $driver
    * @param string $database
    * @param string $username
    * @param string $password
    * @param array  $hidden
    * @param int    $mode
    *
    * @return array
    */
    show(string $driver,string $database,string $username,string $password,int $mode = Eloquent::MODE_ALL_DATABASES,array $hidden = []) : array
    
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
    userAdd(string $driver,string $user,string $password,string $rights,PDO $connexion): bool
    
   /**
    * delete an user
    *
    * @param string $driver
    * @param string $user
    * @param PDO    $connexion
    *
    * @return bool
    */
    userDel(string $driver,string $user,PDO $connexion): bool
    
   /**
    * get all charset
    *
    * @param string $driver
    * @param PDO    $connexion
    *
    * @return array
    */
    charset(string $driver,PDO $connexion): array
    
   /**
    * get all collation
    * 
    * @param string $driver
    * @param PDO    $connexion
    *
    * @return array
    */
    collation(string $driver,PDO $connexion): array
    
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
    create(string $driver,string $database,string $charset,string $collation,PDO $connexion): bool 

   /**
    * manage tables
    *
    * @param string $driver
    * @param string $database
    * @param string $username
    * @param string $password
    *
    * @param string $dumpPath
    *
    * @return Table
    */
    table(string $driver,string $database,string $username,string $password,string $dumpPath): Table
    
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
    dumper(string $driver, string $username, string $password, string $database, string $dumpPath, int $mode = Eloquent::MODE_DUMP_DATABASE, string $table ='')
    
   /**
    * build pagination
    *
    * @param int    $perPage
    * @param string $instance
    *
    * @return Paginator
    */
    paginate(int $perPage,string $instance): Paginator
    
   /**
    * @param int $type
    *
    * @return Form|null
    */
    form(int $type)
    
   /**
    * get an instance of faker
    * 
    * @param string $locale
    *
    * @return \Faker\Generator
    */
    faker(string $locale = 'en_US' ): Faker\Generator
    
   /**
    * generate a fa icon
    * 
    * @param string $icon
    * @param string $options
    *
    * @return string
    */
    fa(string $icon, string $options = ''): string
    
   /**
    * generate a link icon group area
    *
    * @param array  $icons
    * @param array  $urls
    * @param array  $text
    * @param array  $options
    * @param array  $class
    *
    * @param string $containerClass
    *
    * @return null|string
    */
    faGroup(array $icons, array $urls ,array $text, array $options, array $class, string $containerClass = 'list-group')
   
   /**
    * generate a iconic icon
    * 
    * @param string $type
    * @param string $icon
    * @param string $viewBox
    *
    * @return string
    */
    iconic(string $type,string $icon,$viewBox = '0 0 8 8'): string
   
   /**
    * generate a glyph icon
    * 
    * @param string $icon
    * @param string $type
    *
    * @return string
    */
    glyph(string $icon,$type = 'svg'): string
    
   /**
    * see os
    *
    * @param bool $name
    *
    * @return Os|string
    */
    os(bool $name = false)
    
   /**
    * get operating system
    *
    * @return string
    */
    getOs(): string
    
   /**
    * see devices
    *
    * @param bool $name
    *
    * @return string|Device
    */
    device(bool $name = false)
    
   /**
    * get device
    *
    * @return string
    */
    getDevice(): string
    
   /**
    * see browser
    *
    * @param bool $name
    *
    * @return Browser|string
    */
    browser(bool $name = false)
    
   /**
    * get browser name
    *
    * @return string
    */
    getBrowser(): string
    
   /**
    * check if is name is browser
    * 
    * @param string $name
    *
    * @return bool
    */
    isBrowser(string $name): bool
    
   /**
    * check if device is mobile
    * 
    * @return bool
    */
    isMobile(): bool
    
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
    joins(int $type,string $firstTable,string $secondTable,string $firstParam,string $secondParam,array $firstColumns = [], string $condition ='='): Query
    
   /**
    * generate a union clause
    *
    * @param int    $mode
    * @param string $firstTable
    * @param string $secondTable
    * @param array  $firstColumns
    * @param array  $secondColumns
    *
    * @return Query
    */
    union(int $mode,string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query
    
   /**
    * sql table builder
    *
    * @param string $table
    *
    * @return Query
    */
    sql(string $table): Query
    
   /**
    * get all lines in filename
    * 
    * @param string $filename
    *
    * @return array
    */
    getLines(string $filename): array 
    
   /**
    * get all keys in filename
    * 
    * @param string $filename
    * @param string $delimiter
    *
    * @return array
    */
    getKeys(string $filename,string $delimiter): array
    
   /**
    * get all values in filename
    *
    * @param string $filename
    * @param string $delimiter
    *
    * @return array
    */
    getValues(string $filename,string $delimiter): array 

   /**
    * get the current git branch
    * 
    * @param string $repository
    *
    * @return string
    */
    getCurrentBranch(string $repository): string 
   
   /**
    * manage git repository
    *
    * @param string $repository
    *
    * @return GitRepository
    */
    git(string $repository): GitRepository
    
   /**
    * check if a code is valid
    *
    * @param string $secret
    * @param string $code
    *
    * @return bool|int
    * @throws OauthExceptions
    */
    checkCode(string $secret,string $code)
    
   /**
    * generate Qr code
    *
    * @param string $company
    * @param string $username
    * @param string $secret
    *
    * @throws OauthExceptions
    * @return string
    */
    generateQrCode(string $company,string $username,string $secret) : string
    
   /**
    * generate secret two factor key
    *
    * @return string
    */
    generateKey() : string
    
   /**
    * load a css file
    * 
    * @param string $url
    *
    * @return string
    */
    cssLoader(string $url): string
     
   /* load a js file
    *
    * @param string $url
    * @param string $type
    *
    * @return string
    */
    jsLoader(string $url,string $type = 'text/javascript'): string
    
   /**
    * manage image
    * 
    * @param string $driver
    *
    * @return ImageManager
    */
    image(string $driver): ImageManager
    
    /**
    * Create a new future date.
    *
    * @param  \DateTimeZone|string|null $tz
    * @param string                     $mode
    * @param int                        $time
    *
    * @return string
    */
    future(string $mode,int $time,$tz = null): string
    
   /**
    * Create a new Carbon instance for the current date.
    *
    * @param  \DateTimeZone|string|null $tz
    * @return Carbon
    */
    now($tz = null): Carbon
    
   /**
    * Create a new Carbon instance for the current date.
    *
    * @param  \DateTimeZone|string|null $tz
    * @return Carbon
    */
    today($tz = null): Carbon
    
   /**
    * return time based on a time
    * 
    * @param string $locale
    * @param string $time
    * @param null   $tz
    *
    * @return string
    */
    ago(string $locale,string $time,$tz = null): string
    
   /**
    * execute a query return a boolean  
    *
    * @param PDO $instance
    * @param string $request
    * @return bool
    */
    execute(PDO $instance,string $request): bool
    
   /**
    * execute a query return an array with results
    *
    * @param PDO $instance
    * @param string $request
    * @param int $fetchStyle
    *
    * @return array
    */
    req(PDO $instance,string $request,int $fetchStyle = PDO::FETCH_OBJ): array
   
   /***
    * generate a table with record pagination and search field
    *
    * @param string $class
    * @param Table $instance
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
    * @param PDO $pdo
    * @param int $formType
    * @param string $placeholder
    * @param string $paginationPositionClass
    *
    * @return string
    */
    records(string $class, Table $instance,string $table,string $editPrefix, string $deletePrefix,string $orderBy,string $editText,string $deleteText,string $editClass,string $deleteClass,string $editIcon,string $deleteIcon,int $limit,int $current,string $paginationUrl,PDO $pdo,int $formType,string $placeholder,string $paginationPositionClass =''): string
   
   /**
    * generate a form to edit or create a record
    *
    * @param int    $type
    * @param string $class
    * @param string $action
    * @param string $table
    * @param Table  $instance
    * @param string $submitText
    * @param string $submitClass
    * @param string $submitIcon
    * @param int    $mode
    * @param int    $id
    *
    * @return string
    */
    generate(int $type,string $class,string $action,string $table,Table $instance,string $submitText,string $submitClass,string $submitIcon,int $mode = Form::CREATE,int $id = 0): string
    
   /**
    * get a $_GET value
    * 
    * @param string $key
    * 
    * @return string
    */
    get(string $key): string
    
   /**
    * get a $_POST value
    *
    * @param string $key
    *
    * @return string
    */
    post(string $key): string
      
   /**
    * get a $_SERVER value
    *
    * @param string $key
    *
    * @return string
    */
    server(string $key): string
   
   /**
    * get a $_FILE value
    * 
    * @param string $key
    *
    * @return mixed
    */
    file(string $key)
   
   /**
    * push one or more elements onto the end of array
    *
    * @param array $array
    * @param mixed $value
    *
    * @return int
    */
    function push(array &$array,mixed $value): int
    
   /**
    * pop the element off the end of array
    *
    * @param array $array
    *
    * @return mixed
    */
    function pop(array &$array)
    
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
    function has(mixed $needle,array &$array,bool $mode = false)
   
   /**
    * Return all the values of an array
    *
    * @param array $array
    *
    * @return array
    */
    function values(array &$array): array
    
   /**
    * Return all the values of an array
    *
    * @param array $array
    * @param null  $search_value
    * @param null  $strict
    *
    * @return array
    */
    function keys(array $array, $search_value = null, $strict = null): array
    
   /**
    * get a $_SESSION value
    *
    * @param string $key
    *
    * @return string
    */
    function session(string $key): string
    
   /**
    * get a $_COOKIE value
    *
    * @param string $key
    *
    * @return string
    */
    function cookie(string $key): string
    
   /**
    * generate bootswatch css link
    * 
    * @param string $theme
    * @param string $version
    *
    * @return string
    */
    bootswatch(string $theme,string $version = '4.0.0'): string
   
   /**
    * restore a database
    * 
    * @param Base $instance
    * @param string $base
    * @param string $sqlFile
    *
    * @return bool
    */
    restore(Base $instance,string $base,string $sqlFile): bool 
```
# Examples

```php

    if(pass('mysql','root','secure','root'))
    {
        // password updated
    } else {
        // password not updated
    }
    
    if(pass('pgsql','postgres','secure','postgres'))
    {
        // password updated
    } else {
        // password not updated
    }
    
    // Show mysql databases
    
    $base =  base('mysql','root','secure','dump');
    
    foreach($base->show() as $item)
    {
        echo $item
    }
     
    
    $pdo = connect('mysql','','root','secure');
    
    // execute a statement and return an array
    $databases = sql()->setPdo($pdo)->request("SHOW DATABASES")
    
    // execute a statement and return a boolean
    $result = sql()->setPdo($pdo)->query("CREATE DATABASE IF NOT EXIST $database");
    
    if($result)
    {
        // database created        
    }
    
    $users = sql('users')->setPdo($pdo)->getRecords();
    
    $totalOfUsers = sql('users')->setPdo($pdo)->count();
    
    $user = sql('users')->where('id','=',1)->setPdo($pdo)->getRecords();
    
    $users = sql('users')->orderBy('id')->setPdo($pdo)->getRecords(); 
     
    if(create('mysql','imperium','utf8','utf8_general_ci',$pdo))
    {
        // database created successfully 
    }
    
    // or more simply
    
    foreach(show('mysql', 'root','secure') as $base)
    {
        echo $base;
    }
    
    // show helper
    
    // get all mysql users
    $users = show('mysql','judo','root','root', Eloquent::MODE_ALL_USERS)
    
    // get all mysql databases
    $databases = show('mysql','judo','root','root',Eloquent::MODE_ALL_DATABASES)
    
    // get all table in a mysql database
    $tables =  show('mysql','judo','root','root',Eloquent::MODE_ALL_TABLES)
    
    $users =  user('mysql','root','secure');
    
    foreach($users->show() as $user)
    {
        echo $user
    }
        
    foreach(show('mysql','judo','root','root', Eloquent::MODE_ALL_USERS) as $user)
    {
        echo $user;
    }
    // dump a mysql database
    
    dump(Connexion::MYSQL,'root','secure','database','dump');
    
    // dump a postgresql database
    
    dump(Connexion::POSTGRESQL,'postgres','secure','database','dump');
     
    // dump a mysql table
    
    dump(Connexion::MYSQL,'root','secure','database','dump',Eloquent::MODE_DUMP_TABLE,'table');
    
    // dump a postgresql table
    
    dump(Connexion::POSTGRESQL,'postgres','secure','database','dump',Eloquent::MODE_DUMP_TABLE,'table');
    
    $instance = "/table/$table";
    
    $pages = paginate($perPage,$instance)->set_pageIdentifierFromGet($current)->set_total($total);
    
    $pagination =  $pages->page_links(null);
    
    $records = sql($table)->setPdo($pdo)->setPaginationLimit($pages)->getRecords();
   
    foreach($records as $record)
    {
        echo $record->property
    }
    
    echo $pagination
``` 

# Laravel

```php

    namespace App\Http\Controller;
    
    class DatabaseController extends Controller
    {
        private $driver;
        
        private $charset;
        
        private $collation;
        
        private $pdo;
       
        private $base;
        
        public function __construct()
        {   
            $this->driver = env('DB_CONNECTION');
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            
            $this->pdo = connect($this->driver,$database,$username,$password);
            $this->base = base($this->driver,$database,$username,public_path('../dump'));
            $this->collation = collation($this->driver,$this->pdo);
            $this->charset = charset($this->driver,$this->pdo);
        }
        
        public function addUser(Request $request)
        {
            $user = $request->get('user');
            $password = $request->get('password');
            $rights = $request->get('rights');
            
            if(userAdd($this->driver,$user,$password,$rights,$this->pdo))
            {
                // user was added 
                return redirect('/')->with('success',"$user was added successfully");
            }
             return redirect('/')->with('error',"Creation of user $user has failed");           
        }
        
        public function delUser(Request $request)
        {
            $user = $request->get('user');
            
            if(userDel($this->driver,$user,$this->pdo))
            {
                // user was removed 
                return redirect('/')->with('success',"$user was removed successfully");
            }
            return redirect('/')->with('error',"Deletion of user $user has failed");           
        }
            
        public function showDatabase()
        {
            $databases = $this->base->show();
            return view('database.show',compact('databases'));
        }
    }
```