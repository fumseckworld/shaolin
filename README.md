<p align="center"><a href="https://discord.gg/qn6yptm" title="discord"><img src="https://zupimages.net/up/18/08/rd2u.png" width="300" alt="imperium"></a></p>
<p align="center">
<img src="https://poser.pugx.org/imperium/imperium/v/stable" alt="Latest Stable Version">
<img src="https://poser.pugx.org/imperium/imperium/downloads" alt="Download">
<img src="https://poser.pugx.org/imperium/imperium/license" alt="Licence"> 
</p>


* [**Discord**](https://discord.gg/qn6yptm)
    * [**Coverage**](https://imperium.fumseck.eu)

> run make in order to see the coverage and run tests

# What it's ?

It is a free php library written to be useful for everybody and to be included in [Ji](https://git.fumseck.eu/cgit/ji/), and [Shaolin](https://git.fumseck.eu/cgit/shaolin) projects.

# Objectives

Imperium was born to you offer a system to manage your databases more simply without use obligatory a framework.
 
To allow you to use it in your own projects, and group my projects in one to update them more simply.

Its objectives are to supports all [types of databases](http://php.net/manual/en/pdo.drivers.php) possibles, and be useful for all developers to build a admin area more simply.

# **Installation**

`$ composer require imperium/imperium`

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
| empty                 |   void    | bool                       | Check if array is empty                                                                  |
| init                  |   void    | int                        | Initialize the position to 0 and return the position                                     |
| collection            |   void    | array                      | Return the array modified                                                                |               
| push                  |   mixed   | An instance of Collection  | Add to the end of the array                                                              |                  
| stack                 |   mixed   | An instance of Collection  | Add to the begin of the array                                                            |               
| merge                 |   mixed   | An instance of Collection  | Merge multiple array inside the array                                                    |               
| last                  |   void    | mixed                      | Return the last element inside the array                                                 |               
| begin                 |   void    | mixed                      | Return the first element inside the array                                                |               
| length                |   void    | int                        | Return the number of elements inside the array                                           |               
| add                   |   mixed   | An instance of Collection  | Add inside the array a value with and optional key                                       |               
| reverse               |   bool    | array                      | Return the reverse of the array                                                          |                
| value_before_key      |   mixed   | mixed                      | Return the value of the array before a key                                               |                   
| has_key               |   mixed   | bool                       | Check if the key exist inside the array                                                  |                       
| exist                 |   mixed   | bool                       | Check if the value exist inside the array                                                |                
| not_exist             |   mixed   | bool                       | Check if the value not exist inside the array                                            |                
| values                |   void    | array                      | Return all values inside the array                                                       |                
| keys                  |   void    | array                      | Return all keys inside the array                                                         |                
| before                |   void    | mixed                      | Move the current position before the current position and return the current value       |                
| after                 |   void    | mixed                      | Move the current position after the current position and return the current value        |                
| numeric               |   mixed   | bool                       | Check if the value is numeric                                                            |                
| string                |   mixed   | bool                       | Check if the value is a string                                                           |                
| get                   |   mixed   | mixed                      | Get a value inside the array by a key                                                    |                
| remove                |   mixed   | An instance of Collection  | Remove a value inside the array by a key                                                 |                
| join                  |   mixed   | sting                      | Join all values inside the array by a string                                       |                
| clear                 |   mixed   | An instance of Collection  | Empty the array                                                                          |                
| join                  |   mixed   | sting                      | Join all values inside the array by a  separator                                         |                
| print                 |   mixed   | sting                      | Generate personal, table, card code to see records information                           |                
| collection            |   void    | array                      | Return the array modified                                                                |                
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
| get      


# [Json](https://git.fumseck.eu/cgit/imperium/tree/imperium/Json/Json.php) [Coverage](https://imperium.fumseck.eu/imperium/Json/Json.php.html)

| Method                | arguments | return                           |   do                                                               |    
|-----------------------|-----------|----------------------------------|--------------------------------------------------------------------|    
| __construct           |   string  | An instance of the json manager  | Save the filename                                                  |
| create                |   array   | Return true on success           | Create the json                                                    |
| add                   |   string  | An instance of the json manager  | Add inside the array value(s) with optional key                    |
| sql                   |   mixed   | An instance of the json manager  | Add inside the array value(s) with the result of a query           |
| generate              |   void    | True on success                  | Generate json file with data added by add or sql function          |              
| decode                |   bool    | Data on success                  | Decode a json file or a json string encoded to utf8                |