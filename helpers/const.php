<?php


define('GET', 'GET');
define('POST', 'POST');
define('PUT', 'PUT');
define('DELETE', 'DELETE');

define('BEFORE_ACTION', 'before_action');
define('AFTER_ACTION', 'after_action');

define('GIT_PERIOD', ['minute', 'minutes', 'day', 'days', 'week', 'weeks', 'month', 'months', 'year', 'years']);
define('GIT_SIZE', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
define('GIT_ARCHIVE_EXT', ['tar', 'tgz', 'tar.gz', 'zip']);

define('ONE_CENTURY', time() + 365 * 24 * 3600 * 100);
define('ONE_YEAR', time() + 365 * 24 * 3600);
define('ONE_MONTH', time() + 30 * 24 * 3600);
define('ONE_WEEK', time() + 7 * 24 * 3600);
define('NEVER', time() + 9999 * 6000 * 3600 );

define('DROP_NEW_LINE', 1);
define('READ_AHEAD', 2);
define('SKIP_EMPTY', 4);
define('READ_CSV', 8);
define('READ_FILE_MODE', 'r');
define('READ_AND_WRITE_FILE_MODE', 'r+');
define('EMPTY_AND_WRITE_FILE_MODE', 'w');
define('EMPTY_READ_AND_WRITE_FILE_MODE', 'w+');
define('WRITE_TO_END_FILE_MODE', 'a');
define('WRITE_AND_READ_TO_END_FILE_MODE', 'a+');
define('CREATE_TO_WRITE_MODE', 'x');
define('CREATE_TO_WRITE_AND_READ_MODE', 'x+');
define('CREATE_WITHOUT_TRUNCATE_ON_WRITE_MODE', 'c');
define('CREATE_WITHOUT_TRUNCATE_ON_READ_AND_WRITE_MODE', 'c+');
define('FILES_OPEN_MODE', [READ_FILE_MODE, READ_AND_WRITE_FILE_MODE, EMPTY_AND_WRITE_FILE_MODE, EMPTY_READ_AND_WRITE_FILE_MODE, WRITE_TO_END_FILE_MODE, WRITE_AND_READ_TO_END_FILE_MODE, CREATE_TO_WRITE_MODE, CREATE_TO_WRITE_AND_READ_MODE, CREATE_WITHOUT_TRUNCATE_ON_WRITE_MODE, CREATE_WITHOUT_TRUNCATE_ON_READ_AND_WRITE_MODE]);


define('LOCALHOST', 'localhost');


define('ASC', 'ASC');
define('DESC', 'DESC');

define('NUMERIC', '([0-9]+)');
define('NOT_NUMERIC', '([^0-9]+)');
define('STRING', '([a-zA-Z]+)');
define('NOT_STRING', '([^A-Za-z]+)');
define('ALPHANUMERIC', '([0-9A-Za-z\-]+)');
define('SLUG', '([0-9A-Za-z\-]+)');

define('BETWEEN', 'BETWEEN');
define('EQUAL', '=');
define('DIFFERENT', '!=');
define('INFERIOR', '<');
define('INFERIOR_OR_EQUAL', '<=');
define('SUPERIOR', '>');
define('SUPERIOR_OR_EQUAL', '>=');
define('LIKE', 'LIKE');

define('MYSQL', 'mysql');
define('POSTGRESQL', 'pgsql');
define('SQLITE', 'sqlite');
define('SQL_SERVER', 'sqlsrv');
define('ORACLE', 'oci');
define('DRIVERS',[MYSQL,POSTGRESQL,SQLITE,SQL_SERVER]);

define('UNION', 'UNION');
define('UNION_ALL','UNION ALL');
define('INNER_JOIN', 'INNER JOIN');
define('CROSS_JOIN', 'CROSS JOIN');
define('LEFT_JOIN', 'LEFT JOIN');
define('RIGHT_JOIN', 'RIGHT JOIN');
define('FULL_JOIN', 'FULL JOIN');
define('SELF_JOIN', 'SELF JOIN');
define('NATURAL_JOIN', 'NATURAL JOIN');

define('SELECT', 'SELECT');
define('UPDATE', 'UPDATE');
define('INSERT', 'INSERT');

define('MYSQL_PORT', 3306);
define('POSTGRESQL_PORT', 5432);


define('QUERY_COLUMN', 'column');
define('QUERY_CONDITION', 'condition');
define('QUERY_EXPECTED', 'expected');
define('QUERY_MODE', 'mode');
define('QUERY_FIRST_TABLE', 'first_table');
define('QUERY_FIRST_PARAM', 'first_param');
define('QUERY_SECOND_TABLE', 'second_table');
define('QUERY_SECOND_PARAM', 'second_param');
define('QUERY_ORDER_KEY', 'key');
define('QUERY_ORDER', 'order');
define('METHOD_SUPPORTED', ['GET', 'POST', 'PUT', 'DELETE']);


define('CSRF_TOKEN', 'csrf_token');

define('DB_DRIVER', 'driver');
define('DB_NAME', 'base');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_HIDDEN_TABLES', 'hidden_tables');


define('DISPLAY_BUGS', 'debug');
define('ENV', 'env');

define('APCU_CACHE',1);
define('ARRAY_CACHE',2);
define('FILE_CACHE',3);
define('MEMCACHED_CACHE',4);
define('PDO_CACHE',5);
define('PHP_FILE',7);
define('REDIS_CACHE',8);
define('SUPPORTED_CACHE',[APCU_CACHE,ARRAY_CACHE,FILE_CACHE,MEMCACHED_CACHE,PDO_CACHE,PHP_FILE,REDIS_CACHE]);


define('SUCCESS','SUCCESS');
define('FAILURE','FAILURE');

define('PHP_ECHO','<?=');
define('PHP_OPEN','<?');
define('PHP_CLOSE','?>');

define('VIEW_PHP_OPEN','{{');
define('VIEW_PHP_CLOSE','}}');


define('OBJECTS',PDO::FETCH_OBJ);
define('COLUMNS',PDO::FETCH_COLUMN);