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
define('NEVER', time() + 9999 * 6000 * 3600);

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
define('DRIVERS', [MYSQL, POSTGRESQL, SQLITE]);

define('UNION', 'UNION');
define('UNION_ALL', 'UNION ALL');
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


define('DISPLAY_BUGS', 'DEBUG');
define('ENV', 'env');

define('APCU_CACHE', 1);
define('ARRAY_CACHE', 2);
define('FILE_CACHE', 3);
define('MEMCACHE_CACHE', 4);
define('PDO_CACHE', 5);
define('PHP_FILE', 7);
define('REDIS_CACHE', 8);
define('SUPPORTED_CACHE', [APCU_CACHE, FILE_CACHE, MEMCACHE_CACHE, REDIS_CACHE]);


define('SUCCESS', 'SUCCESS');
define('FAILURE', 'FAILURE');

define('PHP_ECHO', '<?=');
define('PHP_OPEN', '<?');
define('PHP_CLOSE', '?>');

define('VIEW_PHP_OPEN', '{{');
define('VIEW_PHP_CLOSE', '}}');


define('OBJECTS', PDO::FETCH_OBJ);
define('COLUMNS', PDO::FETCH_COLUMN);


define('HTTP_REDIRECTION', 301);
define('HTTP_CLIENT_ERROR', 400);
define('HTTP_SERVER_ERROR', 500);
define('HTTP_CONTINUE', 100);
define('HTTP_SWITCH', 101);
define('HTTP_PROCESS', 102);
define('HTTP_EARLY_HINTS', 103);
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_ACCEPTED', 202);
define('HTTP_NON_AUTHORITATIVE_INFORAMTION', 203);
define('HTTP_NO_CONTENT', 204);
define('HTTP_RESET_CONTENT', 205);
define('HTTP_PARTIAL_CONTENT', 206);
define('HTTP_MULTI_STATUS', 207);
define('HTTP_ALREADY_REPORTED', 208);
define('HTTP_IM_USED', 226);
define('HTTP_MULTIPLES_CHOICES', 300);
define('HTTP_MOVE_PERMANENTLY', 301);
define('HTTP_FOUND', 302);
define('HTTP_SEE_OTHER', 303);
define('HTTP_NOT_MODIFIED', 304);
define('HTTP_USE_PROXY', 305);
define('HTTP_TEMPORARY_REDIRECT', 307);
define('HTTP_PERMANENT_REDIRECT', 308);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_PAYMENT_REQUIRED', 402);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOND', 404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_METHOD_NOT_ACCEPTABLE', 406);
define('HTTP_PROXY_AUTHENTICATION_REQUIRED', 407);
define('HTTP_REQUEST_TIMEOUT', 408);
define('HTTP_CONFLICT', 409);
define('HTTP_GONE', 410);
define('HTTP_LENGTH_REQUIRED', 411);
define('HTTP_PRECONDITION_FAILED', 412);
define('HTTP_PAYLOAD_TOO_LARGE', 413);
define('HTTP_URI_TOO_LONG', 414);
define('HTTP_UNSUPORTED_MEDIA_TYPE', 415);
define('HTTP_RANGE_NOT_SATISFIABLE', 416);
define('HTTP_EXPECTATION_FAILED', 417);
define('HTTP_I_AM_TEAPOT', 418);
define('HTTP_MISDIRECTED_REQUEST', 421);
define('HTTP_UMPROCESSABLE_ENTITY', 422);
define('HTTP_LOCKED', 423);
define('HTTP_FAILED_DEPENDENCY', 424);
define('HTTP_TOO_EARLY', 425);
define('HTTP_UPGRADE_REQUIRED', 426);
define('HTTP_PRECONDITION_REQUIRED', 428);
define('HTTP_TOO_MANY_REQUESTS', 429);
define('HTTP_REQUEST_HEADER_FIELD_TOO_LARGE', 431);
define('HTTP_UNAVAILABLE_FOR_LEGAL_REASONS', 451);
define('HTTP_INTERNAL_SERVER_ERROR', 500);
define('HTTP_NOT_IMPLEMENTED', 501);
define('HTTP_BAD_GATEWAY', 502);
define('HTTP_SERVICE_UNAVAILABLE', 503);
define('HTTP_GATEWAY_TIMEOUT', 504);
define('HTTP_VERSION_NOT_SUPPORTED', 505);
define('HTTP_VARIANT_ALSO_NEGOTIATES', 506);
define('HTTP_INSUFFICIANT_STORAGE', 507);
define('HTTP_LOOP_DETECTED', 508);
define('HTTP_NOT_EXTENDED', 510);
define('HTTP_NETWORK_AUTHENTICATIONJ_REQUIRED', 511);


define('HTTP_CONTINUE_TEXT', 'Continue');
define('HTTP_SWITCH_TEXT', 'Switching Protocols');
define('HTTP_PROCESS_TEXT', 'Processing');
define('HTTP_EARLY_HINTS_TEXT', 'Early Hints');
define('HTTP_OK_TEXT', 'OK');
define('HTTP_CREATED_TEXT', 'Created');
define('HTTP_ACCEPTED_TEXT', 'Accepted');
define('HTTP_NON_AUTHORITATIVE_INFORAMTION_TEXT', 'Non-Authoritative Information');
define('HTTP_NO_CONTENT_TEXT', 'No Content');
define('HTTP_RESET_CONTENT_TEXT', 'Reset Content');
define('HTTP_PARTIAL_CONTENT_TEXT', 'Partial Content');
define('HTTP_MULTI_STATUS_TEXT', 'Multi-Status');
define('HTTP_ALREADY_REPORTED_TEXT', 'Already Reported');
define('HTTP_IM_USED_TEXT', 'IM Used');
define('HTTP_MULTIPLES_CHOICES_TEXT', 'Multiple Choices');
define('HTTP_MOVE_PERMANENTLY_TEXT', 'Moved Permanently');
define('HTTP_FOUND_TEXT', 'Found');
define('HTTP_SEE_OTHER_TEXT', 'See Other');
define('HTTP_NOT_MODIFIED_TEXT', 'Not Modified');
define('HTTP_USE_PROXY_TEXT', 'Use Proxy');
define('HTTP_TEMPORARY_REDIRECT_TEXT', 'Temporary Redirect');
define('HTTP_PERMANENT_REDIRECT_TEXT', 'Permanent Redirect');
define('HTTP_BAD_REQUEST_TEXT', 'Bad Request');
define('HTTP_UNAUTHORIZED_TEXT', 'Unauthorized');
define('HTTP_PAYMENT_REQUIRED_TEXT', 'Payment Required');
define('HTTP_FORBIDDEN_TEXT', 'Forbidden');
define('HTTP_NOT_FOND_TEXT', 'Not Found');
define('HTTP_METHOD_NOT_ALLOWED_TEXT', 'Method Not Allowed');
define('HTTP_METHOD_NOT_ACCEPTABLE_TEXT', 'Not Acceptable');
define('HTTP_PROXY_AUTHENTICATION_REQUIRED_TEXT', 'Proxy Authentication Required');
define('HTTP_REQUEST_TIMEOUT_TEXT', 'Request Timeout');
define('HTTP_CONFLICT_TEXT', 'Conflict');
define('HTTP_GONE_TEXT', 'Gone');
define('HTTP_LENGTH_REQUIRED_TEXT', 'Length Required');
define('HTTP_PRECONDITION_FAILED_TEXT', 'Precondition Failed');
define('HTTP_PAYLOAD_TOO_LARGE_TEXT', 'Payload Too Large');
define('HTTP_URI_TOO_LONG_TEXT', 'URI Too Long');
define('HTTP_UNSUPORTED_MEDIA_TYPE_TEXT', 'Unsupported Media Type');
define('HTTP_RANGE_NOT_SATISFIABLE_TEXT', 'Range Not Satisfiable');
define('HTTP_EXPECTATION_FAILED_TEXT', 'Expectation Failed');
define('HTTP_I_AM_TEAPOT_TEXT', 'I\'m a teapot');
define('HTTP_MISDIRECTED_REQUEST_TEXT', 'Misdirected Request');
define('HTTP_UMPROCESSABLE_ENTITY_TEXT', 'Unprocessable Entity');
define('HTTP_LOCKED_TEXT', 'Locked');
define('HTTP_FAILED_DEPENDENCY_TEXT', 'Failed Dependency');
define('HTTP_TOO_EARLY_TEXT', 'Too Early');
define('HTTP_UPGRADE_REQUIRED_TEXT', 'Upgrade Required');
define('HTTP_PRECONDITION_REQUIRED_TEXT', 'Precondition Required');
define('HTTP_TOO_MANY_REQUESTS_TEXT', 'Too Many Requests');
define('HTTP_REQUEST_HEADER_FIELD_TOO_LARGE_TEXT', 'Request Header Fields Too Large');
define('HTTP_UNAVAILABLE_FOR_LEGAL_REASONS_TEXT', 'Unavailable For Legal Reasons');
define('HTTP_INTERNAL_SERVER_ERROR_TEXT', 'Internal Serve Error');
define('HTTP_NOT_IMPLEMENTED_TEXT', 'Not Implemented');
define('HTTP_BAD_GATEWAY_TEXT', 'Bad Gateway');
define('HTTP_SERVICE_UNAVAILABLE_TEXT', 'Service Unavailable');
define('HTTP_GATEWAY_TIMEOUT_TEXT', 'Gateway Timeout');
define('HTTP_VERSION_NOT_SUPPORTED_TEXT', 'HTTP Version Not Supported');
define('HTTP_VARIANT_ALSO_NEGOTIATES_TEXT', 'Variant Also Negotiates');
define('HTTP_INSUFFICIANT_STORAGE_TEXT', 'Insufficient Storage');
define('HTTP_LOOP_DETECTED_TEXT', 'Loop Detected');
define('HTTP_NOT_EXTENDED_TEXT', 'Not Extended');
define('HTTP_NETWORK_AUTHENTICATION_REQUIRED_TEXT', 'Network Authentication Required');

define('STATUS_CODES', [
    HTTP_CONTINUE,
    HTTP_REDIRECTION,
    HTTP_CLIENT_ERROR,
    HTTP_SERVER_ERROR,
    HTTP_SWITCH,
    HTTP_PROCESS,
    HTTP_EARLY_HINTS,
    HTTP_OK,
    HTTP_CREATED,
    HTTP_ACCEPTED,
    HTTP_NON_AUTHORITATIVE_INFORAMTION,
    HTTP_NO_CONTENT,
    HTTP_RESET_CONTENT,
    HTTP_PARTIAL_CONTENT,
    HTTP_MULTI_STATUS,
    HTTP_ALREADY_REPORTED,
    HTTP_IM_USED,
    HTTP_MULTIPLES_CHOICES,
    HTTP_MOVE_PERMANENTLY,
    HTTP_FOUND,
    HTTP_SEE_OTHER,
    HTTP_NOT_MODIFIED,
    HTTP_USE_PROXY,
    HTTP_TEMPORARY_REDIRECT,
    HTTP_PERMANENT_REDIRECT,
    HTTP_BAD_REQUEST,
    HTTP_UNAUTHORIZED,
    HTTP_PAYMENT_REQUIRED,
    HTTP_FORBIDDEN,
    HTTP_NOT_FOND,
    HTTP_METHOD_NOT_ALLOWED, HTTP_METHOD_NOT_ACCEPTABLE,
    HTTP_PROXY_AUTHENTICATION_REQUIRED,
    HTTP_REQUEST_TIMEOUT,
    HTTP_CONFLICT,
    HTTP_GONE,
    HTTP_LENGTH_REQUIRED,
    HTTP_PRECONDITION_FAILED,
    HTTP_PAYLOAD_TOO_LARGE,
    HTTP_URI_TOO_LONG,
    HTTP_UNSUPORTED_MEDIA_TYPE,
    HTTP_RANGE_NOT_SATISFIABLE,
    HTTP_EXPECTATION_FAILED,
    HTTP_I_AM_TEAPOT,
    HTTP_MISDIRECTED_REQUEST,
    HTTP_UMPROCESSABLE_ENTITY,
    HTTP_LOCKED,
    HTTP_FAILED_DEPENDENCY,
    HTTP_TOO_EARLY,
    HTTP_UPGRADE_REQUIRED,
    HTTP_PRECONDITION_REQUIRED,
    HTTP_TOO_MANY_REQUESTS,
    HTTP_REQUEST_HEADER_FIELD_TOO_LARGE,
    HTTP_UNAVAILABLE_FOR_LEGAL_REASONS,
    HTTP_INTERNAL_SERVER_ERROR,
    HTTP_NOT_IMPLEMENTED,
    HTTP_BAD_GATEWAY,
    HTTP_SERVICE_UNAVAILABLE,
    HTTP_GATEWAY_TIMEOUT,
    HTTP_VERSION_NOT_SUPPORTED,
    HTTP_VARIANT_ALSO_NEGOTIATES,
    HTTP_INSUFFICIANT_STORAGE,
    HTTP_LOOP_DETECTED,
    HTTP_NOT_EXTENDED,
    HTTP_NETWORK_AUTHENTICATIONJ_REQUIRED

]);

define('STATUS_TEXT', [


    HTTP_SWITCH_TEXT,
    HTTP_PROCESS_TEXT,
    HTTP_EARLY_HINTS_TEXT,
    HTTP_OK_TEXT,
    HTTP_CREATED_TEXT,
    HTTP_ACCEPTED_TEXT,
    HTTP_NON_AUTHORITATIVE_INFORAMTION_TEXT,
    HTTP_NO_CONTENT_TEXT,
    HTTP_RESET_CONTENT_TEXT,
    HTTP_PARTIAL_CONTENT_TEXT,
    HTTP_MULTI_STATUS_TEXT,
    HTTP_ALREADY_REPORTED_TEXT,
    HTTP_IM_USED_TEXT,
    HTTP_MULTIPLES_CHOICES_TEXT,
    HTTP_MOVE_PERMANENTLY_TEXT,
    HTTP_FOUND_TEXT,
    HTTP_SEE_OTHER_TEXT,
    HTTP_NOT_MODIFIED_TEXT,
    HTTP_USE_PROXY_TEXT,
    HTTP_TEMPORARY_REDIRECT_TEXT,
    HTTP_PERMANENT_REDIRECT_TEXT,
    HTTP_BAD_REQUEST_TEXT,
    HTTP_UNAUTHORIZED_TEXT,
    HTTP_PAYMENT_REQUIRED_TEXT,
    HTTP_FORBIDDEN_TEXT,
    HTTP_NOT_FOND_TEXT,
    HTTP_METHOD_NOT_ALLOWED, HTTP_METHOD_NOT_ACCEPTABLE_TEXT,
    HTTP_PROXY_AUTHENTICATION_REQUIRED_TEXT,
    HTTP_REQUEST_TIMEOUT_TEXT,
    HTTP_CONFLICT_TEXT,
    HTTP_GONE_TEXT,
    HTTP_LENGTH_REQUIRED_TEXT,
    HTTP_PRECONDITION_FAILED_TEXT,
    HTTP_PAYLOAD_TOO_LARGE_TEXT,
    HTTP_URI_TOO_LONG_TEXT,
    HTTP_UNSUPORTED_MEDIA_TYPE_TEXT,
    HTTP_RANGE_NOT_SATISFIABLE_TEXT,
    HTTP_EXPECTATION_FAILED_TEXT,
    HTTP_I_AM_TEAPOT_TEXT,
    HTTP_MISDIRECTED_REQUEST_TEXT,
    HTTP_UMPROCESSABLE_ENTITY_TEXT,
    HTTP_LOCKED_TEXT,
    HTTP_FAILED_DEPENDENCY_TEXT,
    HTTP_TOO_EARLY_TEXT,
    HTTP_UPGRADE_REQUIRED_TEXT,
    HTTP_PRECONDITION_REQUIRED_TEXT,
    HTTP_TOO_MANY_REQUESTS_TEXT,
    HTTP_REQUEST_HEADER_FIELD_TOO_LARGE_TEXT,
    HTTP_UNAVAILABLE_FOR_LEGAL_REASONS_TEXT,
    HTTP_INTERNAL_SERVER_ERROR_TEXT,
    HTTP_NOT_IMPLEMENTED_TEXT,
    HTTP_BAD_GATEWAY_TEXT,
    HTTP_SERVICE_UNAVAILABLE_TEXT,
    HTTP_GATEWAY_TIMEOUT_TEXT,
    HTTP_VERSION_NOT_SUPPORTED_TEXT,
    HTTP_VARIANT_ALSO_NEGOTIATES_TEXT,
    HTTP_INSUFFICIANT_STORAGE_TEXT,
    HTTP_LOOP_DETECTED_TEXT,
    HTTP_NOT_EXTENDED_TEXT,
    HTTP_NETWORK_AUTHENTICATION_REQUIRED_TEXT

]);

define('STATUS', [

    HTTP_CONTINUE => HTTP_CONTINUE_TEXT,

    HTTP_SWITCH => HTTP_SWITCH_TEXT,
    HTTP_PROCESS => HTTP_PROCESS_TEXT,
    HTTP_EARLY_HINTS => HTTP_EARLY_HINTS_TEXT,
    HTTP_OK => HTTP_OK_TEXT,
    HTTP_CREATED => HTTP_CREATED_TEXT,
    HTTP_ACCEPTED => HTTP_ACCEPTED_TEXT,
    HTTP_NON_AUTHORITATIVE_INFORAMTION => HTTP_NON_AUTHORITATIVE_INFORAMTION_TEXT,
    HTTP_NO_CONTENT => HTTP_NO_CONTENT_TEXT,
    HTTP_RESET_CONTENT => HTTP_RESET_CONTENT_TEXT,
    HTTP_PARTIAL_CONTENT => HTTP_PARTIAL_CONTENT_TEXT,
    HTTP_MULTI_STATUS => HTTP_MULTI_STATUS_TEXT,
    HTTP_ALREADY_REPORTED => HTTP_ALREADY_REPORTED_TEXT,
    HTTP_IM_USED => HTTP_IM_USED_TEXT,
    HTTP_MULTIPLES_CHOICES => HTTP_MULTIPLES_CHOICES_TEXT,
    HTTP_MOVE_PERMANENTLY => HTTP_MOVE_PERMANENTLY_TEXT,
    HTTP_FOUND => HTTP_FOUND_TEXT,
    HTTP_SEE_OTHER => HTTP_SEE_OTHER_TEXT,
    HTTP_NOT_MODIFIED => HTTP_NOT_MODIFIED_TEXT,
    HTTP_USE_PROXY => HTTP_USE_PROXY_TEXT,
    HTTP_TEMPORARY_REDIRECT => HTTP_TEMPORARY_REDIRECT_TEXT,
    HTTP_PERMANENT_REDIRECT => HTTP_PERMANENT_REDIRECT_TEXT,
    HTTP_BAD_REQUEST => HTTP_BAD_REQUEST_TEXT,
    HTTP_UNAUTHORIZED => HTTP_UNAUTHORIZED_TEXT,
    HTTP_PAYMENT_REQUIRED => HTTP_PAYMENT_REQUIRED_TEXT,
    HTTP_FORBIDDEN => HTTP_FORBIDDEN_TEXT,
    HTTP_NOT_FOND => HTTP_NOT_FOND_TEXT,
    HTTP_METHOD_NOT_ALLOWED => HTTP_METHOD_NOT_ALLOWED_TEXT,
    HTTP_METHOD_NOT_ACCEPTABLE => HTTP_METHOD_NOT_ACCEPTABLE_TEXT,
    HTTP_PROXY_AUTHENTICATION_REQUIRED => HTTP_PROXY_AUTHENTICATION_REQUIRED_TEXT,
    HTTP_REQUEST_TIMEOUT=> HTTP_REQUEST_TIMEOUT_TEXT,
    HTTP_CONFLICT => HTTP_CONFLICT_TEXT,
    HTTP_GONE =>HTTP_GONE_TEXT ,
    HTTP_LENGTH_REQUIRED => HTTP_LENGTH_REQUIRED_TEXT ,
    HTTP_PRECONDITION_FAILED =>HTTP_PRECONDITION_FAILED_TEXT,
    HTTP_PAYLOAD_TOO_LARGE => HTTP_PAYLOAD_TOO_LARGE_TEXT,
    HTTP_URI_TOO_LONG => HTTP_URI_TOO_LONG_TEXT ,
    HTTP_UNSUPORTED_MEDIA_TYPE => HTTP_UNSUPORTED_MEDIA_TYPE_TEXT,
    HTTP_RANGE_NOT_SATISFIABLE => HTTP_RANGE_NOT_SATISFIABLE_TEXT,
    HTTP_EXPECTATION_FAILED => HTTP_EXPECTATION_FAILED_TEXT ,
    HTTP_I_AM_TEAPOT => HTTP_I_AM_TEAPOT_TEXT ,
    HTTP_MISDIRECTED_REQUEST =>HTTP_MISDIRECTED_REQUEST_TEXT ,
    HTTP_UMPROCESSABLE_ENTITY =>HTTP_UMPROCESSABLE_ENTITY_TEXT,
    HTTP_LOCKED =>HTTP_LOCKED_TEXT,
    HTTP_FAILED_DEPENDENCY=>HTTP_FAILED_DEPENDENCY_TEXT,
    HTTP_TOO_EARLY=>HTTP_TOO_EARLY_TEXT,
    HTTP_UPGRADE_REQUIRED=>HTTP_UPGRADE_REQUIRED_TEXT,
    HTTP_PRECONDITION_REQUIRED=>HTTP_PRECONDITION_REQUIRED_TEXT,
    HTTP_TOO_MANY_REQUESTS=>HTTP_TOO_MANY_REQUESTS_TEXT,
    HTTP_REQUEST_HEADER_FIELD_TOO_LARGE=>HTTP_REQUEST_HEADER_FIELD_TOO_LARGE_TEXT,
    HTTP_UNAVAILABLE_FOR_LEGAL_REASONS=>HTTP_UNAVAILABLE_FOR_LEGAL_REASONS_TEXT,
    HTTP_INTERNAL_SERVER_ERROR=>HTTP_INTERNAL_SERVER_ERROR_TEXT,
    HTTP_NOT_IMPLEMENTED=>     HTTP_NOT_IMPLEMENTED_TEXT,
    HTTP_BAD_GATEWAY =>HTTP_BAD_GATEWAY_TEXT,
    HTTP_SERVICE_UNAVAILABLE =>HTTP_SERVICE_UNAVAILABLE_TEXT,
    HTTP_GATEWAY_TIMEOUT =>HTTP_GATEWAY_TIMEOUT_TEXT,
    HTTP_VERSION_NOT_SUPPORTED =>HTTP_VERSION_NOT_SUPPORTED_TEXT,
    HTTP_VARIANT_ALSO_NEGOTIATES=> HTTP_VARIANT_ALSO_NEGOTIATES_TEXT,
    HTTP_INSUFFICIANT_STORAGE=>HTTP_INSUFFICIANT_STORAGE_TEXT,
    HTTP_LOOP_DETECTED =>HTTP_LOOP_DETECTED_TEXT,
    HTTP_NOT_EXTENDED=>HTTP_NOT_EXTENDED_TEXT,
    HTTP_NETWORK_AUTHENTICATIONJ_REQUIRED => HTTP_NETWORK_AUTHENTICATION_REQUIRED_TEXT

]);

define('CACHE_DEFAULT_TTL',7200); // 120m | 2h | 7200s