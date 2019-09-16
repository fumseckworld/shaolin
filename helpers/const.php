<?php


define('GET', 'GET');
define('POST', 'POST');

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

define('UNION', 12);
define('UNION_ALL', 13);
define('INNER_JOIN', 14);
define('CROSS_JOIN', 15);
define('LEFT_JOIN', 16);
define('RIGHT_JOIN', 17);
define('FULL_JOIN', 18);
define('SELF_JOIN', 19);
define('NATURAL_JOIN', 20);
define('SELECT', 21);
define('DELETE', 22);
define('UPDATE', 23);
define('INSERT', 24);

define('MYSQL_PORT', 3306);
define('POSTGRESQL_PORT', 5432);

define('DISPLAY_TABLE', 25);
define('DISPLAY_ARTICLE', 26);
define('DISPLAY_CONTRIBUTORS', 27);

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
