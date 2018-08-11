<p align="center"><a href="https://discord.gg/qn6yptm" title="discord"><img src="https://zupimages.net/up/18/08/rd2u.png" width="300" alt="imperium"></a></p>
<p align="center">
<img src="https://poser.pugx.org/imperium/imperium/v/stable" alt="Latest Stable Version">
<img src="https://poser.pugx.org/imperium/imperium/downloads" alt="Download">
<img src="https://poser.pugx.org/imperium/imperium/license" alt="Licence"> 
</p>
 

[**Coverage**](coverage/index.html)


# What it's ?

It is a free php library written to be useful for everybody and to be included in [Ji](https://git.fumseck.eu/cgit/ji/), and [Lumos](https://git.fumseck.eu/cgit/lumos) projects.

# Objectives

Imperium was born to you offer a system to manage your databases more simply without use obligatory a framework.
 
To allow you to use it in your own projects, and group my projects in one to update them more simply.

Its objectives are to supports all [types of databases](http://php.net/manual/en/pdo.drivers.php) possibles, and be useful for all developers to build a admin area more simply.

# Installation

`$ composer require imperium/imperium`

# SGBD

| Name                  | Supported |
|-----------------------|-----------|    
| MariaDB               |   yes     |
| MySQL                 |   yes     |
| PostgreSQL            |   yes     |
| SQLite                |   yes     |
| Oracle                |   partly  |
| SQL Server            |   no      |
| Firebird              |   no      |
| MongoDB               |   no      |
| Other                 |   no      |

> oracle support is in development


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




# File methods

| Name                  | Do                                        | Arguments                         | Return        |
|-----------------------|-------------------------------------------|-----------------------------------|---------------|    
| hash                  | Get the MD5 hash of the file              | string  $filename                 | string        |
| lastModified          | Get the file's last modification time     | string  $filename                 | bool|int      |
| download              | download a file                           | string  $filename                 | bool|int      |
| search                | search files likes a pattern              | string  $pattern int $flag        | array         |
| create                | create a file is not exist                | string  filename                  | bool          |
| delete                | delete a file or a folder if exist        | string  filename                  | bool          |
| deleteFolder          | delete a folder if exist                  | string  $folder                   | bool          |
| getLines              | get all lines in a file                   | string  $filename, string $mode   | array         |
| getKeys               | get all keys in a file                    | string  multiples                 | array         |
| getValues             | get all values in a file                  | string  multiples                 | array         |
| copyFolder            | copy a folder to destination              | string  multiples                 | void          |
| copy                  | copy a source to destination              | string  multiples                 | bool          |
| isReadable            | check if a file or a folder is readable   | string  $filename                 | bool          |
| isWritable            | check if a file or a folder is writable   | string  $filename                 | bool          |
| hardLink              | create a hard link                        | string  multiples                 | bool          |
| symlink               | create a symlink link                     |  string  multiples                | bool          |
| isLink                | check if filename is a symlink            | string  $filename                 | bool          |
| getMime               | get the mime of file                      | string  $filename                 | string        |
| getStat               | get the file's info                       | string  $filename                 | array         |
| getStartKey           | get a part of stat by a key               | string  multiples                 | string        |
| write                 | write data on a file                      | string  multiples                 | bool          |
| isFile                | check if filename is a file               | string  $filename                 | bool          |
| isImg                 | check if filename is an image             | string  $filename                 | bool          |
| isHtml                | check if filename is a html file          | string  $filename                 | bool          |
| isPhp                 | check if filename is a php file           |  string  $filename                | bool          |
| isJS                  | check if filename is a js file            |  string  $filename                | bool          |
| isJson                | check if filename is a json file          |  string  $filename                | bool          |
| isXml                 | check if filename is a xml file           |  string  $filename                | bool          |
| isCss                 | check if filename is a css file           |  string  $filename                | bool          |
| isPdf                 | check if filename is a pdf file           |  string  $filename                | bool          |
| isEnd                 | check if is the end of file               |  string  $filename                | bool          |
| getGroup              | get the group of the file                 |  string  $filename                | int           |
| getOwner              | get the owner of the file                 |  string  $filename                | int           |
| loads                 |include all file passed in parameters      |  string multiples                 | void          |
| getContent            | get content in a file                     |  string $filename                 | string        |
| putContents           | write data on a file                      |  string $filename                 | bool          |
| getFile               | get super global $_FILES                  |  void                             | $_FILES       |
| uploadedFileType      | get uploaded file type                    |  string $inputName                | string        |
| uploadedFileSize      | get uploaded file sie                     |  string $inputName                | int           |
| uploadedFileName      | get uploaded file name                    |  string $inputName                | string        |
| uploadedFileTmpPath   | get uploaded file tmp path                |  string $inputName                | string        |
| uploadedFileErrors    | get uploaded file tmp error               |  string $inputName                | int           |
| moveUploadedFile      | move a uploaded file to destination       |  string multiples                 | bool          |
| rename                | rename a file                             |  string multiples                 | bool          |
| verify                | verify a file exist and if is a file      |  string $filename                 | bool          |
| exist                 | verify a file exist                       |  string $filename                 | bool          |
| open                  | open a file with a mode                   |  string multiple                  | resource      |
| isEmptyArgs           | check if function argument is empty       |  void                             | bool          |
| close                 | close a file                              |  resource $file                   | bool          |
| realPath              | get absolute path                         |  resource $file                   | string        |
| chmod                 | Changes file mod                          |  string multiples                 | bool          |
| chgrp                 | Changes file group                        |  string multiples                 | bool          |
| lchgrp                | Changes group ownership of symlink        |  string multiples                 | bool          |
| chown                 | Changes file owner                        |  string multiples                 | bool          |
| isExecutable          | Tells whether the filename is executable  |  string $filename                 | bool          |
| getType               | gets file type                            |  string $filename                 | string        |
| fileTime              | gets last access time of file             |  string $filename                 | int           |
| fileOwner             | gets f


# Form methods

| Name                  | Do                                    | Arguments                 | Return        |
|-----------------------|---------------------------------------|---------------------------|---------------|    
| create                | start form builder                    | string multiple           | Form          |
| startHide             | start hidden input                    | void                      | Form          |
| endHide               | close hidden input                    | void                      | Form          |
| file                  | add a file input                      | string multiples          | Form          |
| input                 | add an input                          | string multiples          | Form          |
| setType               | set form type                         | int  $type                | Form          |
| twoInlineInput        | add two inline input                  | string multiples          | Form          |
| csrf                  | add csrf token in form                | string $csrf              | Form          |
| button                | add a button                          | string multiples          | Form          |
| reset                 | add a reset button                    | string multiples          | Form          |
| textarea              | add a textarea                        | string multiples          | Form          |
| img                   | add an image                          | string multiples          | Form          |
| submit                | add a submit button                   | string multiples          | Form          |
| link                  | add a link button                     | string multiples          | Form          |
| select                | add a select input                    | string multiples          | Form          |
| twoInlineSelect       | add two inline select                 | string multiples          | Form          |
| checkbox              | add a checkbox                        | string multiples          | Form          |
| radio                 | add a radio                           | string multiples          | Form          |
| end                   | close and return form                 | void                      | string        |
| redirectSelect        | add a redirect select                 | string multiples          | Form          |
| twoRedirectSelect     | add two redirect select               | string multiples          | Form          |
| oneSelectAndOneInput  | add one select and one input          | string multiples          | Form          |
| oneInputAndOneSelect  | add one input and one select          | string multiples          | Form          |
  
  


# Query methods

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
 
 
 
# Table methods

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


# To run tests

`$ make`