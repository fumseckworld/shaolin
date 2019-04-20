<p align="center"><a href="https://discord.gg/fUPyd9K" title="discord"><img src="https://zupimages.net/up/18/08/rd2u.png" width="300" alt="imperium"></a></p>

* [**Imperium**](https://git.fumseck.eu/cgit/imperium/tree/imperium/App.php)
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
    * [**Views**](https://git.fumseck.eu/cgit/imperium/tree/imperium/View/View.php)
    * [**Router**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Router/Router.php)
    * [**Oauth**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Security/Auth/Oauth.php)
    * [**Csrf**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Security/Csrf/Csrf.php)
    * [**Hash**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Security/Hashing/Hash.php)
    * [**Asset**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Asset/Asset.php)
    * [**Controllers**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Controller/Controller.php)
    * [**Import**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Import/Import.php)
    * [**Dump**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Dump/Dump.php)
    * [**Flash**](https://git.fumseck.eu/cgit/imperium/tree/imperium/imperium/Flash/Flash.php)
    * [**Email**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Mail/Mail.php)
    * [**Middleware**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Middleware/Middleware.php)
    * [**Request**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Request/Request.php)
    * [**Session**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Session)
    * [**Trans**](https://git.fumseck.eu/cgit/imperium/tree/imperium/Trans/Trans.php)
    * [**Views**](https://git.fumseck.eu/cgit/imperium/tree/imperium/View/View.php)
    * [**Helpers**](https://git.fumseck.eu/cgit/imperium/tree/app/helpers.php)
* [**Discord**](https://discord.gg/fUPyd9K)
* [**Trello**](https://trello.com/b/28tMSSDG/imperium)
* [**Diff**](https://git.fumseck.eu/cgit/imperium/diff/?id=8.1.4&id2=8.1.3&dt=2)

# What it's ?

It is a free php library written to be useful for everybody and to be included in the [Shaolin](https://git.fumseck.eu/cgit/shaolin) project.

# Objectives

Imperium was born to you offer a system to manage your databases more simply without use obligatory a framework.


Its objectives are to supports all [types of databases](http://php.net/manual/en/pdo.drivers.php) possibles, and be useful for all developers to build a admin area more simply.

# Views helpers

`app` The main class instance

`display`  Display a flash message

`csrf_field`  Generate a csrf token

`back`  Generate a back link 

`mobile`  Check if device is a mobile 

`print`  Print code

`css`  Generate a css link

`copyright` Print copyright

`js`  Generate a js link

`img`  Generate a image link

`form`  Get an instance of the form class

`print`  No escape html 

`lang`  Display the current lang

`logged`  check if the user is logged

`route` Return the route url

`user` Return a collection instance with the current user logged if is logged

`t`  translate a message

`root`  The root path

`site` Generate url based on the site 

`_`  translate a message using gettext

`name`  Return the url of the route name

`os` Return an instance of os class

`device` Return an instance of device class


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

# [View](https://git.fumseck.eu/cgit/imperium/tree/imperium/View/View.php) 

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
|  __construct          |  string   | An instance of the view          | Save the data                                                      |
|  load                 |  mixed    | Mixed                            | Return the view content                                            |
|  add_global           |  mixed    | An instance of the view          | Add a global value                                                 |
|  add_path             |  mixed    | An instance of the view          | Add a view path                                                    |
|  paths                |  void     | An array                         | Return the views paths                                             |
|  twig                 |  void     | An instance of twig              | Return the twig instance                                           |
|  loader               |  void     | An instance of the loader        | Return the loader instance                                         |
     
# [Router](https://git.fumseck.eu/cgit/imperium/tree/imperium/Router/Router.php)


The controller and method separator it's **@**

The url **params** must be  prefixed by **:**   

The namespace it's the namespace of your application class
 
| Method                | arguments                 | return                           |   do                                                               |    
|-----------------------|---------------------------|----------------------------------|--------------------------------------------------------------------|    
|  __construct          |  ServerRequestInterface   | An instance of the router        | Save the data                                                      |
|  run                  |  void                     | Mixed                            | Call the callable                                                  |
|  ::url                |  string                   | A string                         | Return the route url                                               |
|  ::callback           |  string                   | A string                         | Return the route callback                                          |
|  ::admin              |  string                   | A string                         | Return the admin routes                                            |
|  ::web                |  string                   | A string                         | Return the web routes                                              |
        
        
# [Base](https://git.fumseck.eu/cgit/imperium/tree/imperium/Bases/Base.php)

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



# [Users](https://git.fumseck.eu/cgit/imperium/tree/imperium/Users/Users.php)

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


# [Table](https://git.fumseck.eu/cgit/imperium/tree/imperium/Tables/Table.php) 

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
| seed                              |   int     | A boolean                  | Seed the table                                                                           |
| dump                              |   string  | A boolean                  | Dump the table                                                                           |
| is_empty                          |   void    | A boolean                  | Check if a table is empty                                                                |
| select                            |   int     | A array                    | Select a record by it's id                                                               |
| select_or_fail                    |   int     | A array                    | Select a record by it's id or fails if not found                                         |
| remove                            |   int     | A boolean                  | Remove a record by it's id                                                               |
| convert                           |   string  | A boolean                  | Convert table with new charset and collation                                             |
| exist                             |   string  | A boolean                  | Check if a table exist                                                                   |
| save                              |   mixed   | A boolean                  | Insert data in the table                                                                 |
| show                              |   void    | A array                    | Displays all tables                                                                      |
| count                             |   string  | An int                     | Count number of records inside a table                                                   |
| all                               |   string  | An array                   | Return all records inside a table                                                        |
| found                             |   void    | An integer                 | Return number of tables found inside the database                                        |
| update_record                     |   mixed   | A boolean                  | Update a record                                                                          |
| rename                            |   string  | An boolean                 | Rename the table                                                                         |
| set_collation                     |   string  | An instance of Table       | Define the collation                                                                     |
| set_charset                       |   string  | An instance of Table       | Define the charset                                                                       |
| get_current_tmp_table             |   void    | An string                  | Return the sha1 of the current table name                                                |
| insert_multiples                  |   array   | A boolean                  | Insert multiples values                                                                  |


# [Model](https://git.fumseck.eu/cgit/imperium/tree/imperium/Model/Model.php)

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
| update                |   void    | A boolean                                 | Update a record inside the table from request                      |                                                     
| update_record         |   void    | A boolean                                 | Update a record by this id                                         |                                                     
| columns               |   void    | An array                                  | Display all columns inside the table                               |                                                     
| is_empty              |   void    | A boolean                                 | Check if the table is empty                                        |
| pdo                   |   void    | An instance of pdo                        | Return the pdo instance                                            |
| request               |   string  | An array                                  | Return the result of the query inside an array                     |
| set                   |   mixed   | An instance of Model                      | Set a new value for a column                                       |   
| save                  |   void    | A boolean                                 | Insert the new record build with set method                        |   
| news                  |   mixed   | An array                                  | Return the news added records with a limit and order by            |   
| last                  |   mixed   | An array                                  | Return the lasts records with a limit and order by                 |   
| edit_form             |   mixed   | A string                                  | Generate a form to update a record                                 |   
| update_form           |   mixed   | A string                                  | Generate a form to create a record                                 |   
| parse                 |   mixed   | A string                                  | Generate a form by a record                                        |   

# [Collection](https://git.fumseck.eu/cgit/imperium/tree/imperium/Collection/Collection.php) 

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


# [Directory](https://git.fumseck.eu/cgit/imperium/tree/imperium/Directory/Dir.php) 

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|     
| ::clear               |   string  | A boolean                        | Remove the directory files                                         |
| ::create              |   string  | A boolean                        | Create the directory if not exist                                  |
| ::remove              |   string  | A boolean                        | Remove the directory if not exist                                  |
| ::is                  |   string  | A boolean                        | Check if arg is a directory                                        |


# [Connexion](https://git.fumseck.eu/cgit/imperium/tree/imperium/Connexion/Connect.php) 

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of Connect           | Save the connection of the database                                |
| driver                |   void    | An string                        | Return the current driver                                          |
| base                  |   void    | An string                        | Return the current database                                        |
| user                  |   void    | An string                        | Return the current username                                        |
| password              |   void    | An string                        | Return the current password                                        |
| fetch_mode            |   void    | An int                           | Return the current fetch mode                                      |
| dump_path             |   void    | A string                         | Return the current dump path                                       |
| mysql                 |   void    | A boolean                        | Return if the current driver is mysql                              |
| postgresql            |   void    | A boolean                        | Return if the current driver is postgresql                         |
| sqlite                |   void    | A boolean                        | Return if the current driver is sqlite                             |
| instance              |   void    | An instance of pdo               | Get the pdo instance                                               |
| request               |   string  | An array                         | Return the result of a request inside an array                     |
| execute               |   string  | A boolean                        | Return the result of a request                                     |
| transaction           |   void    | A instance of Connect            | Start a transaction block                                          |
| rollback              |   void    | A instance of Connect            | Abort the current transaction                                      |
| queries               |   string  | A instance of Connect            | Execute the queries                                                |
| commit                |   void    | A boolean                        | Commit the current transaction                                     |

# [Form](https://git.fumseck.eu/cgit/imperium/tree/imperium/Html/Form/Form.php) 
 
**All forms have a csrf token by default**
 
| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| start                 |   mixed   | An instance of the form builder  | Open the form                                                      |
| hide                  |   void    | An instance of the form builder  | Open the hidden block                                              |
| end_hide              |   void    | An instance of the form builder  | Close the hidden block                                             |
| file                  |   mixed   | An instance of the form builder  | Create a file input                                                |
| input                 |   mixed   | An instance of the form builder  | Create a input                                                     |
| button                |   mixed   | An instance of the form builder  | Create a button                                                    |
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



# [Json](https://git.fumseck.eu/cgit/imperium/tree/imperium/Json/Json.php)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of the json manager  | Save the filename                                                  |
| create                |   array   | A boolean                        | Create the json file                                               |
| set_name              |   string  | An instance of the json manager  | Save the json name                                                 |
| add                   |   mixed   | An instance of the json manager  | Add inside the array a value with an optional key                  |
| sql                   |   mixed   | An instance of the json manager  | Add inside the array the result of a query                         |
| generate              |   void    | A boolean                        | Generate json file with data added by add or sql function          |              
| decode                |   bool    | mixed                            | Decode a json file or a json string encoded to utf8                |


# [Query](https://git.fumseck.eu/cgit/imperium/tree/imperium/Query/Query.php)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   mixed   | An instance of the query builder | Save the connection                                                |
| from                  |   string  | An instance of the query builder | Save the table name                                                |
| sql                   |   void    | A string                         | Return the generated sql query                                     |
| where                 |   mixed   | An instance of the query builder | Generate a where clause                                            |
| only                  |   mixed   | An instance of the query builder | Save the request columns                                           |
| between               |   mixed   | An instance of the query builder | Generate a where clause with between                               |
| order_by              |   string  | An instance of the query builder | Generate a order by clause                                         |
| limit                 |   mixed   | An instance of the query builder | Define the limit                                                   |
| mode                  |   int     | An instance of the query builder | Define the query mode                                              |
| delete                |   void    | A boolean                        | Execute the query to delete                                        |
| join                  |   string  | An instance of the query builder | Generate a join query                                              |
| union                 |   string  | An instance of the query builder | Generate a union query                                             |
| like                  |   string  | An instance of the query builder | Generate a like query                                              |
| get                   |   Void    | An array                         | Execute the query                                                  |


# [Helpers](https://git.fumseck.eu/cgit/imperium/tree/app/helpers.php) 

| Method                | arguments | return                           |   do                                                                  |    
|-----------------------|-----------|----------------------------------|-----------------------------------------------------------------------|    
| quote                 |   mixed   | A string                         | Quote a string to use in a query                                      |
| app                   |   mixed   | An instance of Imperium          | Group all possible class instances in a class                         |
| assign                |   mixed   | void                             | Assign to a variable a content by a condition                         |
| query                 |   mixed   | An instance of Query builder     | Create the instance                                                   |
| is_pair               |   int     | A Boolean                        | Check if a number is pair                                             |
| equal                 |   mixed   | A Boolean                        | Check if two variable are equals                                      |
| is_not_false          |   mixed   | A Boolean                        | Check if data is not equal to false                                   |
| is_false              |   mixed   | A Boolean                        | Check if data is equal to false                                       |
| is_not_true           |   mixed   | A Boolean                        | Check if data is not equal to true                                    |
| is_true               |   mixed   | A Boolean                        | Check if data is equal to true                                        |
| different             |   mixed   | A Boolean                        | Check if two variables are different                                  |
| secure_register_form  |   mixed   | A string                         | Generate a register form based on ip address                          |
| login                 |   string  | A string                         | Generate a login form                                                 |
| bases_to_json         |   mixed   | A Boolean                        | Generate a json file with all bases not hidden with an optional key   |
| users_to_json         |   mixed   | A Boolean                        | Generate a json file with all users not hidden with an optional key   |
| tables_to_json        |   mixed   | A Boolean                        | Generate a json file with all tables not hidden with an optional key  |
| sql_to_json           |   mixed   | A Boolean                        | Generate a json file with the result of all queries                   |
| json                  |   string  | An instance of Json              | Return an instance of Json                                            |   
| query_result          |   mixed   | A string                         | Print the query query result                                          |
| execute_query         |   mixed   | mixed                            | Execute a query                                                       |
| query_view            |   mixed   | A string                         | Display a query form builder                                          |
| length                |   mixed   | A integer                        | Return the length of an array or a string                             |
| connect               |   mixed   | A instance of Connect            | Connect to the base                                                   |
| collection            |   array   | A instance of Collection         | Management of the array                                               |
| def                   |   mixed   | A Boolean                        | Check if a value is define and it's not empty                         |
| not_def               |   mixed   | A Boolean                        | Check if a value is not define                                        |
| zones                 |   string  | An array                         | Display all time zones                                                |
| tables_select         |   mixed   | A string                         | Display all table in a select                                         |
| bases_select          |   mixed   | A string                         | Display all bases in a select                                         |
| users_select          |   mixed   | A string                         | Display all users in a select                                         |
| simply_view           |   mixed   | A string                         | Display all records with pagination in a table                        |
| get_records           |   mixed   | An array                         | Display a limited records for simply view                             |
| _html                 |   mixed   | Void                             | Decode or not decode data in html code                                |
| html                  |   mixed   | A string                         | Create a html tag and put content inside                              |
| id                    |   string  | A string                         | Generate an unique id                                                 |
| submit                |   mixed   | A Boolean                        | Check if a form was submited                                          |
| push                  |   mixed   | Void                             | Add elements to the end of the array                                  |
| stack                 |   mixed   | Void                             | Add elements to the beginning of the array                            |
| has                   |   mixed   | A Boolean                        | Check if a value exist in an array                                    |
| values                |   array   | An array                         | Return all values inside the array                                    |
| keys                  |   array   | An array                         | Return all keys inside the array                                      |
| merge                 |   array   | Void                             | Merge multiples array                                                 |
| session               |   string  | A string                         | Return a $_SESSION key if is define                                   |
| post                  |   string  | A string                         | Return a $_POST key if is define                                      |
| get                   |   string  | A string                         | Return a $_GET key if is define                                       |
| cookie                |   string  | A string                         | Return a $_COOKIE key if is define                                    |
| files                 |   string  | A string                         | Return a $_FILES key if is define                                     |
| server                |   string  | A string                         | Return a $_SERVER key if is define                                    |
| generate              |   mixed   | A string                         | Generate a form to edit or update a record                            |
| collation             |   Connect | An array                         | Display all available collations                                      |
| charset               |   Connect | An array                         | Display all available charsets                                        |
| base                  |   mixed   | An Instance of Base              | Return an instance of Base                                            |
| user                  |   Connect | An Instance of User              | Return an instance of User                                            |
| pass                  |   mixed   | A Boolean                        | Update the user password                                              |
| os                    |   Boolean | Mixed                            | Display os name or return an instance of Os                           |
| device                |   Boolean | Mixed                            | Display device name or return an instance of Device                   |
| browser               |   Boolean | Mixed                            | Display browser name or return an instance of Browser                 |
| is_browser            |   string  | A Boolean                        | Check the current browser                                             |
| is_mobile             |   void    | A Boolean                        | Check if the device is a mobile                                       |
| superior              |   Mixed   | A Boolean                        | Check if the parameter is superior of the expected value              |
| superior_or_equals    |   Mixed   | A Boolean                        | Check if the parameter is superior or equal of the expected value     |
| inferior              |   Mixed   | A Boolean                        | Check if the parameter is inferior of the expected value              |
| inferior_or_equals    |   Mixed   | A Boolean                        | Check if the parameter is inferior or equal of the expected value     |
| whoops                |   Void    | A instance of Run                | Initialize whoops and return the instance                             |
| before_key            |   Mixed   | Mixed                            | Return the value before a key inside an array                         |
| req                   |   Mixed   | An array                         | Execute all queries and return the data in an array                   |
| execute               |   Mixed   | A Boolean                        | Execute all queries and check if it's was expected successfully       |
| faker                 |   string  | An instance of faker             | Return an instance of faker                                           |
| remove_users          |   mixed   | A Boolean                        | Remove the users                                                      |
| remove_tables         |   mixed   | A Boolean                        | Remove the tables                                                     |
| remove_bases          |   mixed   | A Boolean                        | Remove the base                                                       |
| faker                 |   string  | An instance of faker             | Return an instance of faker                                           |
| bcrypt                |   string  | A string                         | Return a hash of the value                                            |
| check                 |   string  | A Boolean                        | Check if the password is equal to the hash                            |
