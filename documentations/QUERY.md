# SQL Query builder

```php

    /**
     * sql query builder
     *
     * @param string $table
     *
     * @return Query
     */
     sql(string $table = ''): Query
     
    // SELECT * FROM users 
    $users =  sql('users')->setPdo($pdo)->getRecords(); 
    $users =  sql()->setTable('users')->setPdo($pdo)->getRecords();
    
    // SELECT * FROM users WHERE id = 1
    $user  =   sql('users')->setPdo($pdo)->where('id','=',1)->getRecords(); 
    
    // SELECT COUNT(*) FROM users
    $numberOfUsers = sql('users')->setPdo($pdo)->count();
    
    // SELECT * FROM articles ORDER BY id DESC
    $articles = sql('articles')->setPdo($pdo)->orderBy('id')->getRecords();
    
    // SELECT * FROM articles ORDER BY id ASC
    $articles = sql('articles')->setPdo($pdo)->orderBy('id','ASC')->getRecords(); 
    
    // SELECT * FROM articles ORDER BY id ASC LIMIT 4 OFFSET 2
    $articles = sql('articles')->setPdo($pdo)->orderBy('id','ASC')->limit(4,2)->getRecords(); 
    
    // DELETE FROM articles WHERE id = 200
    $deleted = sql('articles')->setPdo($pdo)->setMode('DELETE' || Query::DELETE )->where('id','=',200)->delete();
    
    // DELETE FROM articles WHERE id > 3
    $deleted = sql('articles')->setPdo($pdo)->setMode('DELETE' || Query::DELETE )->where('id','>',3)->delete();
    
    // SELECT name,email FROM users
    $users = sql('users')->setPdo($pdo)->setColumns(['name','email'])->getRecords();
    
    // SELECT name,email FROM users ORDER BY id DESC
    $users = sql('users')->setPdo($pdo)->setColumns(['name','email'])->orderBy('id')->getRecords();
    
    // Execute a custom query
    
    $created    = sql()->setPdo($pdo)->query("CREATE DATABASE $database');
    $databases  = sql()->setPdo($pdo)->request("SHOW DATABASES');
```

# Methods

| Name                  | Do                                    | Arguments                 | Return        |
|-----------------------|---------------------------------------|---------------------------|---------------|    
| start                 | start query builder                   | void                      | Query         |
| setPdo                | define pdo instance                   | PDO $pdo                  | Query         |
| count                 | count number of records               | void                      | int           |
| getRecords            | get all records                       | void                      | array         |
| setMode               | define the mode                       | string $mode(SELECT)      | Query         |
| delete                | run a delete query                    | void                      | bool          |
| query                 | execute a statement                   | string $statement         | bool          |
| request               | execute a statement                   | string $statement         | array         |
| limit                 | define a limit and an offset          | int $limit int $offset    | Query         |
| setTable              | define the table name                 | string $table             | Query         |
| setPaginationLimit    | define pagination limit               | string $table             | Query         |
| setColumns            | define columns to select              | array $columns            | Query         |
| get                   | get the sql query                     | void                      | string        |
| where                 | define the where clause               | string multiples          | Query         |
| orderBy               | define the order by clause            | string multiples          | Query         |
| join                  | define the join clause                | multiples                 | Query         |
| union                 | define the union clause               | multiples                 | Query         |
| execute               | execute the generated statement       | void                      | bool          |
 
