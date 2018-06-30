# A table builder

```php

   /**
    * manage tables
    * 
    * @param string $driver
    * @param string $database
    * @param string $username
    * @param string $password
    *
    * @return Table
    */
    table(string $driver,string $database,string $username,string $password): Table
    
    $tables =  table('mysql','database','username','password'): Table
    
    //check if database as a table
    
    if($tables->has())
    {
        // has table
        // show table
        foreach ($tables->show() as $table)
        {
            echo $table
        }    
    }
```

# Methods

| Name              | Do                                        | Arguments             | Return        |
|-------------------|-------------------------------------------|-----------------------|---------------|    
| manage            | start query builder                       | void                  | Table         |
| setHidden         | define table to ignore                    | array $tables         | Table         |
| setName           | define current table name                 | string $name          | Table         |
| setNewName        | define new table name                     | string $name          | Table         |
| rename            | rename a table                            | string $name          | bool          |
| has               | check if database has table               | void                  | bool          |
| hasColumn         | check if column exist in table            | string $column        | bool          |
| getColumnsTypes   | get types of columns in a table           | void                  | array         |
| getColumns        | get all columns in a table                | void                  | array         |   
| drop              | Delete a table                            | void                  | bool          |
| truncate          | Truncate a table or all tables            | int $mode             | bool          |
| addField          | add a field in table creation             | multiples             | Table         |
| addColumn         | add a column in an existing table         | multiples             | Table         |
| create            | create table                              | string $engine(null)  | bool          |
| dump              | dump a table                              | void                  | bool          | 
| primaryKey        | get the primary key of a table            | void                  | string|null   |
| isEmpty           | check if a table is empty                 | void                  | bool          |
| selectById        | select a record by id                     | int $id               | array         |
| deleteById        | delete a record by id                     | int $id               | bool          |
| renameColumn      | rename a column                           | string multiple       | bool          |
| deleteColumn      | delete a column                           | string $column        | bool          |
| exist             | check if a table exist                    | void                  | bool          |
| insert            | insert data in a table                    | array $values         | bool          |
| ignore            | define the tables to ignore               | array $tables         | Table         |
| setDumpPath       | define the dump directory path            | string $path          | Table         |
| show              | get all tables in current database        | void                  | array         |
| count             | count records in a table or all tables    | int $mode             | int|array     |
| setDriver         | Set driver                                | string $driver        | Table         |
| setDatabase       | Set database name                         | string $database      | Table         |
| setUsername       | Set username                              | string $username      | Table         |
| setPassword       | Set username password                     | string $password      | Table         |
| exec              | execute a statement                       | string $statement     | bool          |
| request           | execute a statement                       | string $statement     | array         |
| getRecords        | get all records in a table                | void                  | array         |
| countTable        | count the number of tables in database    | void                  | int           |
| optimize          | optimize a table                          | void                  | bool          |
| modifyColumn      | modify an existing column                 | string multiples      | bool          |
| setEngine         | define the engine                         | string $engine        | bool          |
