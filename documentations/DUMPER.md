# Dumper

```php

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
     
     // dump a database
      
      dumper('mysql', 'username','password', 'database', 'dump');
      
      dumper('pgsql', 'username','password', 'database', 'dump');
      
      dumper('sqlite', '','', 'database', 'dump'); 
      
      // dump a table
      
      dumper('mysql', 'username','password', 'database', 'dump',Eloquent::MODE_DUMP_TABLE,'table);
      
      dumper('pgsql', 'username','password', 'database', 'dump',Eloquent::MODE_DUMP_TABLE,'table');
      
      dumper('sqlite', '','', 'database', 'dump',Eloquent::MODE_DUMP_TABLE,'table');
         
         
    $tables = table(Connexion::MYSQL,'database','username','secure,'dump');
    
    $tables->dump('users'); // dump table users

    

```
