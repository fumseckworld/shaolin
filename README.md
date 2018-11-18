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
* [**Trello**](https://trello.com/b/28tMSSDG/imperium)

# What it's ?

It is a free php library written to be useful for everybody and to be included in [Ji](https://git.fumseck.eu/cgit/ji/), and [Shaolin](https://git.fumseck.eu/cgit/shaolin) projects.

# Objectives

Imperium was born to you offer a system to manage your databases more simply without use obligatory a framework.
 
To allow you to use it in your own projects, and group my projects in one to update them more simply.

Its objectives are to supports all [types of databases](http://php.net/manual/en/pdo.drivers.php) possibles, and be useful for all developers to build a admin area more simply.

# **Installation**

`composer require imperium/imperium`

* Supported
    * MariaDB
    * MySQL
    * PostgreSQL
    * SQLite
* Future
    * Oracle                
    * SQL Server             
    * Firebird          
    * MongoDB 


# Bugs reports

Please send me an email at **bugzilla@laposte.fr**

# Contribute

Your help are welcome. 

Join me on [**discord**](https://discord.gg/qn6yptm) to can speak together more simply.

# [Base](https://git.fumseck.eu/cgit/imperium/tree/imperium/Bases/Base.php) [Coverage](https://imperium.fumseck.eu/imperium/Bases/Base.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of Base              | Save the connection and the driver                                 |
| show                  |   void    | An array                         | Display all databases inside the server                            |
| create                |   string  | A boolean                        | Create the database                                                |
| create_multiples      |   string  | A boolean                        | Create multiples database   s                                      |
| seed                  |   mixed   | A boolean                        | Seed the database                                                  |
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
| check                 |   void    | An instance of Base              | Check if driver is not sqlite                                      |
| set_name              |   string  | Return an instance of base       | Save the name                                                      | 



# [Users](https://git.fumseck.eu/cgit/imperium/tree/imperium/Users/Users.php) [Coverage](https://imperium.fumseck.eu/imperium/Users/Users.php.html)

| Method                | arguments | return                                    |   do                                                               |    
|-----------------------|-----------|-------------------------------------------|--------------------------------------------------------------------|    
| __construct           |   Connect | An instance of Users                      | Save the data                                                      |
| drop                  |   string  | A boolean                                 | Remove an user                                                     |
| has                   |   void    | A boolean                                 | Check if a server has users                                        |
| hidden                |   array   | An instance of users                      | Make the user not visible                                          |
| show                  |   void    | A array                                   | Show all users not hidden                                          |
| set_name              |   string  | An instance of users                      | Set the username                                                   |
| set_password          |   string  | An instance of users                      | Set the user password                                              |
| create                |   void    | A boolean                                 | Create an user                                                     |
| exist                 |   string  | A boolean                                 | Check if an user exist                                             |
| update_password       |   string  | A boolean                                 | Change the user password                                           |


# [Table](https://git.fumseck.eu/cgit/imperium/tree/imperium/Tables/Table.php) [Coverage](https://imperium.fumseck.eu/imperium/Tables/Table.php.html)
 
| Method                            | arguments | return                     | do                                                                                       |    
|-----------------------------------|-----------|----------------------------|------------------------------------------------------------------------------------------|    
| __construct                       |   Connect | An instance of Table       | Save connexion and driver                                                                |
| type                              |   string  | mixed                      | Return the type of the column                                                            |
| length                            |   string  | An integer                 | Return the length of the column                                                          |
| select                            |   string  | An instance of Table       | Select the table to use                                                                  |
| get_current_table                 |   void    | A string                   | Return the current table name                                                            |
| has                               |   void    | A boolean                  | Return if the base has tables                                                            |
| change_collation                  |   void    | A boolean                  | Change the table collation                                                               |
| change_charset                    |   void    | A boolean                  | Change the table charset                                                                 |
| has_column                        |   string  | A boolean                  | Check if the table has the column                                                        |
| column_not_exist                  |   string  | A boolean                  | Check if the table has not defined the column                                            |
| get_columns                       |   void    | A array                    | Return all columns inside a table                                                        |
| get_columns_types                 |   void    | A array                    | Return all types of columns inside a table                                               |
| drop                              |   string  | A boolean                  | Remove the table                                                                         |
| truncate                          |   string  | A boolean                  | Empty the table                                                                          |                                            
| field                             |   mixed   | An instance of Table       | Append a field in the create tables task                                                 |                                                 
| append_column                     |   mixed   | A boolean                  | Append a column inside an existing table                                                 |
| alter_table                       |   string  | A boolean                  | Alter column table constraint                                                            |
| remove_constraint                 |   string  | A boolean                  | Remove column constraint                                                                 |
| create                            |   void    | A boolean                  | Create the table                                                                         |
| is_the_last_field                 |   mixed   | A boolean                  | Check if the current field is the last                                                   |
| seed                              |   int     | A boolean                  | Seed the table                                                                           |
| hidden                            |   array   | An instance of Table       | Define all table to not display in the app                                               |
| dump                              |   string  | A boolean                  | Dump the table                                                                           |
| get_primary_key                   |   void    | mixed                      | Return the primary key                                                                   |
| is_empty                          |   void    | A boolean                  | Check if a table is empty                                                                |
| select_by_id                      |   int     | A array                    | Select a record by it's id                                                               |
| select_by_id_or_fail              |   int     | A array                    | Select a record by it's id or fails if not found                                         |
| remove_by_id                      |   int     | A boolean                  | Remove a record by it's id                                                               |
| columns_to_string                 |   string  | A string                   | Return all columns separated by a glue                                                   |
| change_columns_name_to_string     |   string  | A string                   | Replace a column by a new name and return columns separated by a glue                    |
| rename_column                     |   string  | A boolean                  | Rename a column                                                                          |
| convert                           |   string  | A boolean                  | Convert table with new charset and collation                                             |
| remove_column                     |   string  | A boolean                  | Remove a column inside a table                                                           |
| exist                             |   string  | A boolean                  | Check if a table exist                                                                   |
| insert                            |   mixed   | A boolean                  | Insert data in the table                                                                 |
| save                              |   mixed   | A boolean                  | Insert data in the table                                                                 |
| show                              |   void    | A array                    | Displays all tables                                                                      |
| count                             |   string  | An int                     | Count number of records inside a table                                                   |
| all                               |   string  | An array                   | Return all records inside a table                                                        |
| found                             |   void    | An integer                 | Return number of tables found inside the database                                        |
| update                            |   mixed   | A boolean                  | Update a record                                                                          | 
| modify_column                     |   mixed   | A boolean                  | Modify a column                                                                          | 
| set_engine                        |   string  | An instance of Table       | Define mysql engine                                                                      |
| drop_all_tables                   |   void    | An boolean                 | Drop all tables                                                                          |
| append_columns                    |   mixed   | An boolean                 | Append multiple columns                                                                  |
| has_column_type                   |   string  | An boolean                 | Check if types exists                                                                    |
| rename                            |   string  | An boolean                 | Rename the table                                                                         |
| set_collation                     |   string  | An instance of Table       | Define the collation                                                                     |
| set_charset                       |   string  | An instance of Table       | Define the charset                                                                       |
| get_current_tmp_table             |   void    | An string                  | Return the sha1 of the current table name                                                |
| insert_multiples                  |   array   | A boolean                  | Insert multiples values                                                                  |
 
 
# [Model](https://git.fumseck.eu/cgit/imperium/tree/imperium/Model/Model.php) [Coverage](https://imperium.fumseck.eu/imperium/Model/Model.php.html)
 
| Method                | arguments | return                                    |   do                                                               |    
|-----------------------|-----------|-------------------------------------------|--------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of Model                      | Save the data                                                      |
| show_tables           |   void    | An array                                  | Display all tables inside a base                                   |
| is_mysql              |   void    | A boolean                                 | Check if current driver is mysql                                   |
| is_postgresql         |   void    | A boolean                                 | Check if current driver is postgresql                              |
| is_sqlite             |   void    | A boolean                                 | Check if current driver is sqlite                                  |
| only                  |   mixed   | An array                                  | Display selected columns information                               |
| query                 |   void    | An instance of the query builder          | Return query builder                                               |
| change_table          |   string  | An instance of Model                      | Return  a new instance of model by changing the table              |
| seed                  |   int     | A Boolean                                 | Seed the current table                                             |
| all                   |   void    | An array                                  | Return all record inside the table                                 |
| find                  |   int     | An array                                  | Return one record selected by a id                                 |
| find_or_fail          |   int     | An array                                  | Return one record selected by a id on success or throw exception   |
| where                 |   mixed   | An array                                  | Return selected records by a where clause                          |
| remove                |   int     | A boolean                                 | Remove a record inside the table by an id                          |
| insert                |   mixed   | A boolean                                 | Insert data inside the table                                       |                                                    
| save                  |   mixed   | A boolean                                 | Insert data inside the table                                       |                                                    
| count                 |   void    | A integer                                 | Count all data in a table                                          |                                                    
| truncate              |   void    | A boolean                                 | Empty all records inside the table                                 |                                                     
| update                |   mixed   | A boolean                                 | Update a record inside the table                                   |                                                     
| columns               |   void    | An array                                  | Display all columns inside the table                               |                                                     
| is_empty              |   void    | A boolean                                 | Check if the table is empty                                        |
| pdo                   |   void    | An instance of pdo                        | Return the pdo instance                                            |
| request               |   string  | An array                                  | Return the result of the query inside an array                     |
| request               |   string  | An array                                  | Return the result of the query inside an array                     |
| set                   |   mixed   | An instance of Model                      | Set a new value for a column                                       |   
| save                  |   void    | A boolean                                 | Insert the new record build with set method                        |   
| news                  |   mixed   | An array                                  | Return the news added records with a limit and order by            |   
| last                  |   mixed   | An array                                  | Return the lasts records with a limit and order by                 |   

# [Collection](https://git.fumseck.eu/cgit/imperium/tree/imperium/Collection/Collection.php) [Coverage](https://imperium.fumseck.eu/imperium/Collection/Collection.php.html)
 
| Method                | arguments | return                     | do                                                                                       |    
|-----------------------|-----------|----------------------------|------------------------------------------------------------------------------------------|    
| __construct           |   array   | An instance of Collection  | Save or create an array to manage                                                        |
| set_new_data          |   array   | An instance of Collection  | Save or create an array to manage                                                        |
| each                  |  callable | An instance of Collection  | Execute the function for each data                                                       |
| search                |   mixed   | An instance of Collection  | Search a value inside the array                                                          |
| get_search            |   void    | Mixed                      | Return the search result                                                                 |
| search_result         |   bool    | Mixed                      | Return the search result                                                                 |
| empty                 |   void    | A boolean                  | Check if array is empty                                                                  |
| init                  |   int     | An integer                 | Initialize the position to 0 and return the position                                     |
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
| remove_values         |   strings | An instance of Collection  | Remove values inside the array                                                           |                
| change_value          |   mixed   | An instance of Collection  | Change a value                                                                           |                
| join                  |   mixed   | A string                   | Join all values inside the array by a string                                             |                
| clear                 |   mixed   | An instance of Collection  | Empty the array                                                                          |                
| join                  |   mixed   | A string                   | Join all values inside the array by a  separator                                         |                
| print                 |   mixed   | A string                   | Generate personal, table, card code to see records information                           |                
| collection            |   void    | An array                   | Return the array modified                                                                |                
| current               |   void    | mixed                      | Return the current element                                                               |                
| key                   |   void    | mixed                      | Return the key of the current element                                                    |            
| valid                 |   void    | mixed                      | Checks if current position is valid                                                      |                
| rewind                |   void    | void                       | Rewind the Iterator to the first element                                                 |                
| next                  |   void    | void                       | Move forward to next element                                                             |                
            

# [Directory](https://git.fumseck.eu/cgit/imperium/tree/imperium/Directory/Dir.php) [Coverage](https://imperium.fumseck.eu/imperium/Directory/Dir.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|     
| ::clear               |   string  | A boolean                        | Remove the directory files                                         |
| ::create              |   string  | A boolean                        | Create the directory if not exist                                  |
| ::remove              |   string  | A boolean                        | Remove the directory if not exist                                  |
| ::is                  |   string  | A boolean                        | Check if arg is a directory                                        | 


# [Connexion](https://git.fumseck.eu/cgit/imperium/tree/imperium/Connexion/Connect.php) [Coverage](https://imperium.fumseck.eu/imperium/Connexion/Connect.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of Connect           | Save the connection of the database                                |
| get_driver            |   void    | An string                        | Return the current driver                                          |
| get_database          |   void    | An string                        | Return the current database                                        |
| get_username          |   void    | An string                        | Return the current username                                        |
| get_password          |   void    | An string                        | Return the current password                                        |
| get_fetch_mode        |   void    | An integer                       | Return the current fetch mode                                      |
| get_dump_path         |   void    | A string                         | Return the current dump path                                       |
| mysql                 |   void    | A boolean                        | Return if the current driver is mysql                              |
| postgresql            |   void    | A boolean                        | Return if the current driver is postgresql                         |
| sqlite                |   void    | A boolean                        | Return if the current driver is sqlite                             |
| instance              |   void    | An instance of pdo               | Get the pdo instance                                               |
| request               |   string  | An array                         | Return the result of a request inside an array                     |
| execute               |   string  | A boolean                        | Return the result of a request                                     |

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
| margin                |   int     | An instance of the form builder  | Add margin                                                         |
| get_margin            |   void    | A string                         | Return the defined margin                                          |
| padding               |   int     | An instance of the form builder  | Add padding                                                        |
| get_padding           |   void    | A string                         | Return the defined padding                                         |
| end                   |   void    | The form                         | Close the form and return it                                       |
 


# [Json](https://git.fumseck.eu/cgit/imperium/tree/imperium/Json/Json.php) [Coverage](https://imperium.fumseck.eu/imperium/Json/Json.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of the json manager  | Save the filename                                                  |
| create                |   array   | A boolean                        | Create the json file                                               |
| set_name              |   string  | An instance of the json manager  | Save the json name                                                 |
| add                   |   mixed   | An instance of the json manager  | Add inside the array a value with an optional key                  |
| sql                   |   mixed   | An instance of the json manager  | Add inside the array the result of a query                         |
| generate              |   void    | A boolean                        | Generate json file with data added by add or sql function          |              
| decode                |   bool    | mixed                            | Decode a json file or a json string encoded to utf8                |


# [Query](https://git.fumseck.eu/cgit/imperium/tree/imperium/Query/Query.php) [Coverage](https://imperium.fumseck.eu/imperium/Query/Query.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of the query builder | Save the connexion                                                 |
| set_current_table_name|   string  | An instance of the query builder | Save the table name                                                |
| sql                   |   void    | A string                         | Return the generated sql query                                     |
| where                 |   mixed   | An instance of the query builder | Generate a where clause                                            |
| between               |   mixed   | An instance of the query builder | Generate a where clause with between                               |
| order_by              |   string  | An instance of the query builder | Generate a order by clause                                         |
| set_columns           |   array   | An instance of the query builder | Define the columns to select                                       |
| connect               |   Connect | An instance of the query builder | Define the connexion                                               |
 
