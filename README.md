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



# [Collection](https://git.fumseck.eu/cgit/imperium/tree/imperium/Collection/Collection.php)

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
            