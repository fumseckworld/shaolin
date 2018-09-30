<p align="center"><a href="https://discord.gg/qn6yptm" title="discord"><img src="https://zupimages.net/up/18/08/rd2u.png" width="300" alt="imperium"></a></p>
<p align="center">
<img src="https://poser.pugx.org/imperium/imperium/v/stable" alt="Latest Stable Version">
<img src="https://poser.pugx.org/imperium/imperium/downloads" alt="Download">
<img src="https://poser.pugx.org/imperium/imperium/license" alt="Licence"> 
</p>

* [**Imperium**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Imperium.php)
    * [**Bases**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Bases/Base.php)
    * [**Users**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Users/Users.php)
    * [**Tables**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Tables/Table.php)
    * [**Model**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Model/Model.php)
    * [**Collection**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Collection/Collection.php)
    * [**Connexion**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Connexion/Connect.php)
    * [**Debug**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Debug)
    * [**Directory**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Directory/Dir.php)
    * [**File**](https://git.fumseck.eu/cgit/imperium/tree/imperium/File/File.php)
    * [**Json**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Json/Json.php)
    * [**Query**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Query/Query.php)
    * [**Helpers**](https://git.fumseck.eu/cgit/imperium/tree/app/helpers.php)
* [**Coverage**](https://imperium.fumseck.eu)
* [**Discord**](https://discord.gg/qn6yptm)

# What it's ?

It is a free php library written to be useful for everybody and to be included in [Ji](https://git.fumseck.eu/cgit/ji/), and [Shaolin](https://git.fumseck.eu/cgit/shaolin) projects.

# Objectives

Imperium was born to you offer a system to manage your databases more simply without use obligatory a framework.
 
To allow you to use it in your own projects, and group my projects in one to update them more simply.

Its objectives are to supports all [types of databases](http://php.net/manual/en/pdo.drivers.php) possibles, and be useful for all developers to build a admin area more simply.

# **Installation**

`composer require imperium/imperium`

# Type of databases

| Name                  | Supported |
|-----------------------|-----------|    
| MariaDB               |   yes     |
| MySQL                 |   yes     |
| PostgreSQL            |   yes     |
| SQLite                |   yes     |
| Oracle                |   No      |
| SQL Server            |   no      |
| Firebird              |   no      |
| MongoDB               |   no      |
| Other                 |   no      |



# [Collection](https://git.fumseck.eu/cgit/imperium/tree/imperium/Collection/Collection.php) [Coverage](https://imperium.fumseck.eu/imperium/Collection/Collection.php.html)
 
| Method                | arguments | return                     | do                                                                                       |    
|-----------------------|-----------|----------------------------|------------------------------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of Collection  | Save or create an array to manage                                                        |
| empty                 |   void    | A boolean                  | Check if array is empty                                                                  |
| init                  |   void    | An integer                 | Initialize the position to 0 and return the position                                     |
| collection            |   void    | An array                   | Return the array modified                                                                |               
| push                  |   mixed   | An instance of Collection  | Add to the end of the array                                                              |                  
| stack                 |   mixed   | An instance of Collection  | Add to the begin of the array                                                            |               
| merge                 |   mixed   | An instance of Collection  | Merge multiple array inside the array                                                    |               
| last                  |   void    | mixed                      | Return the last element inside the array                                                 |               
| begin                 |   void    | mixed                      | Return the first element inside the array                                                |               
| length                |   void    | An integer                 | Return the number of elements inside the array                                           |               
| add                   |   mixed   | An instance of Collection  | Add inside the array a value with and optional key                                       |               
| reverse               |   bool    | An array                   | Return the reverse of the array                                                          |                
| value_before_key      |   mixed   | mixed                      | Return the value of the array before a key                                               |                   
| has_key               |   mixed   | A boolean                  | Check if the key exist inside the array                                                  |                       
| exist                 |   mixed   | A boolean                  | Check if the value exist inside the array                                                |                
| not_exist             |   mixed   | A boolean                  | Check if the value not exist inside the array                                            |                
| values                |   void    | An array                   | Return all values inside the array                                                       |                
| keys                  |   void    | An array                   | Return all keys inside the array                                                         |                
| before                |   void    | mixed                      | Move the current position before the current position and return the current value       |                
| after                 |   void    | mixed                      | Move the current position after the current position and return the current value        |                
| numeric               |   mixed   | A boolean                  | Check if the value is numeric                                                            |                
| string                |   mixed   | A boolean                  | Check if the value is a string                                                           |                
| get                   |   mixed   | mixed                      | Get a value inside the array by a key                                                    |                
| remove                |   mixed   | An instance of Collection  | Remove a value inside the array by a key                                                 |                
| join                  |   mixed   | A string                   | Join all values inside the array by a string                                       |                
| clear                 |   mixed   | An instance of Collection  | Empty the array                                                                          |                
| join                  |   mixed   | A string                   | Join all values inside the array by a  separator                                         |                
| print                 |   mixed   | A string                   | Generate personal, table, card code to see records information                           |                
| collection            |   void    | An array                   | Return the array modified                                                                |                
| current               |   void    | mixed                      | Return the current element                                                               |                
| key                   |   void    | mixed                      | Return the key of the current element                                                    |            
| valid                 |   void    | mixed                      | Checks if current position is valid                                                      |                
| rewind                |   void    | void                       | Rewind the Iterator to the first element                                                 |                
| next                  |   void    | void                       | Move forward to next element                                                             |                
            

# [Form](https://git.fumseck.eu/cgit/imperium/tree/imperium/Html/Form/Form.php) [Coverage](https://imperium.fumseck.eu/imperium/Html/Form/Form.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| start                 |   mixed   | An instance of the form builder  | Open the form                                                      |
| hide                  |   void    | An instance of the form builder  | Open the hidden block                                              |
| end_hide              |   void    | An instance of the form builder  | Close the hidden block                                             |
| file                  |   mixed   | An instance of the form builder  | Create a file input                                                |
| input                 |   mixed   | An instance of the form builder  | Create a input                                                     |
| button                |   mixed   | An instance of the form builder  | Create a button                                                    |
| csrf                  |   string  | An instance of the form builder  | Add the csrf token field                                           |
| reset                 |   string  | An instance of the form builder  | Create a reset button                                              |
| textarea              |   mixed   | An instance of the form builder  | Create a textarea                                                  |
| create                |   void    | An instance of the form builder  | Return an instance of the form builder                             |
| submit                |   string  | An instance of the form builder  | Create a submit button                                             |
| link                  |   string  | An instance of the form builder  | Create a link button                                               |
| select                |   mixed   | An instance of the form builder  | Create a select input                                              |
| checkbox              |   mixed   | An instance of the form builder  | Create a checkbox input                                            |
| radio                 |   mixed   | An instance of the form builder  | Create a radio input                                               |
| large                 |   bool    | An instance of the form builder  | Set the form size to large                                         | 
| small                 |   bool    | An instance of the form builder  | Set the form size to small                                         | 
| row                   |   void    | An instance of the form builder  | Start a new row                                                    | 
| end_row               |   void    | An instance of the form builder  | Close the row                                                      | 
| end_row_and_new       |   void    | An instance of the form builder  | Close the row and start a new row                                  | 
| validate              |   void    | An instance of the form builder  | Enable the validation                                              | 
| redirect              |   mixed   | An instance of the form builder  | Create a redirect select                                           |
| generate              |   mixed   | A form                           | Create a form to create or update data inside a table              |
| end                   |   void    | The form                         | Close the form and return it                                       |
 


# [Json](https://git.fumseck.eu/cgit/imperium/tree/imperium/Json/Json.php) [Coverage](https://imperium.fumseck.eu/imperium/Json/Json.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of the json manager  | Save the filename                                                  |
| create                |   array   | A boolean                        | Create the json                                                    |
| add                   |   string  | An instance of the json manager  | Add inside the array value(s) with optional key                    |
| sql                   |   mixed   | An instance of the json manager  | Add inside the array value(s) with the result of a query           |
| generate              |   void    | A boolean                        | Generate json file with data added by add or sql function          |              
| decode                |   bool    | mixed                            | Decode a json file or a json string encoded to utf8                |



# [Base](https://git.fumseck.eu/cgit/imperium/tree/imperium/Bases/Base.php) [Coverage](https://imperium.fumseck.eu/imperium/Bases/Base.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   Connect | An instance of Base              | Save the connection and the driver                                 |
| show                  |   void    | An array                         | Display all databases inside the server                            |
| create                |   string  | A boolean                        | Create the database                                                |
| set_charset           |   string  | An instance of Base              | Save the charset for the database                                  |
| set_collation         |   string  | An instance of Base              | Save the collation for the database                                |
| drop                  |   string  | A boolean                        | Remove the database                                                |
| dump                  |   void    | A boolean                        | Dump the database                                                  |
| exist                 |   string  | A boolean                        | Check if a database exist                                          |
| charsets              |   void    | An array                         | Display all charset possibilities                                  |
| collations            |   void    | An array                         | Display all collations possibilities                               |
| hidden                |   array   | Return an instance of base       | Set the database to no display in the app                          |
| has                   |   bool    | A boolean                        | Detect if server has databases                                     |
| change_collation      |   void    | A boolean                        | Change the base collation                                          |
| change_charset        |   void    | A boolean                        | Change the base charset                                            |
| set_name              |   string  | Return an instance of base       | Save the name                                                      | 


# [Model](https://git.fumseck.eu/cgit/imperium/tree/imperium/Model/Model.php) [Coverage](https://imperium.fumseck.eu/imperium/Model/Model.php.html)

| Method                | arguments | return                                    |   do                                                               |    
|-----------------------|-----------|-------------------------------------------|--------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of Model                      | Save the data                                                      |
| show_tables           |   void    | An array                                  | Display all tables inside a base                                   |
| is_mysql              |   void    | A boolean                                 | Check if current driver is mysql                                   |
| is_mysql              |   void    | A boolean                                 | Check if current driver is postgresql                              |
| is_sqlite             |   void    | A boolean                                 | Check if current driver is sqlite                                  |
| get                   |   mixed   | An array                                  | Display selected columns information                               |
| query                 |   void    | An instance of the query builder          | Return query builder                                               |
| all                   |   void    | An array                                  | Return all record inside the table                                 |
| find                  |   int     | An array                                  | Return one record selected by a id                                 |
| find_or_fail          |   int     | An array                                  | Return one record selected by a id on success or throw exception   |
| where                 |   mixed   | An array                                  | Return selected records by a where clause                          |
| remove                |   int     | A boolean                                 | Remove a record inside the table by an id                          |
| remove                |   int     | A boolean                                 | Remove a record by an id                                           |                                                    
| insert                |   mixed   | A boolean                                 | Insert data inside the table                                       |                                                    
| count                 |   void    | A integer                                 | Count all data in a table                                          |                                                    
| truncate              |   void    | A boolean                                 | Empty all records inside the table                                 |                                                     
| update                |   mixed   | A boolean                                 | Update a record inside the table                                   |                                                     
| columns               |   void    | An array                                  | Display all columns inside the table                               |                                                     
| is_empty              |   void    | A boolean                                 | Check if the table is empty                                        |
| pdo                   |   void    | An instance of pdo                        | Return the pdo instance                                            |
| request               |   string  | An array                                  | Return the result of the query inside an array                     |
| execute               |   string  | A boolean                                 | Return the return of the query execution                           |