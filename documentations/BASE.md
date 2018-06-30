# Base methods

| Name                  | Do                                        | Arguments                         | Return        |
|-----------------------|-------------------------------------------|-----------------------------------|---------------|    
| show                  | get all databases in server               | void                              | array         |
| create                | create a database                         | void                              | bool          |
| setEncodingOptions    | set the encoding options                  | string  $conf                     | Base          |
| setEncoding           | set the encoding preference               | string  $encoding                 | Base          |
| setCollation          | set database collation                    | string  $collation                | Base          |
| setDriver             | set database driver                       | string  $driver                   | Base          |
| setPassword           | set password                              | string  $password                 | Base          |
| setUser               | set username                              | string  $username                 | Base          |
| setName               | set database name                         | string  $name                     | Base          |
| drop                  | delete a database                         | void                              | bool          |
| dump                  | dump a database                           | void                              | mixed         |
| exist                 | verify if a database exist                | void                              | bool          |
| getCharset            | get all database characters               | void                              | array         |
| getCollation          | get all database collation                | void                              | array         |
| setHidden             | define hidden databases                   | array $databases                  | Base          |
| manage                | start query builder                       | void                              | Base          |
| getInstance           | get a pdo instance                        | void                              | PDO|null      |
| setDumpDirectory      | define the dump directory path            | string $path                      | Base          |
