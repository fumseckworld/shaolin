<?php

namespace Imperium {


    class Zen
    {

        const MODE_ALL_TABLES = 10;

        const MODE_ONE_TABLE = 11;

        const MODE_ALL_DATABASES = 12;

        const MODE_ALL_USERS = 13;

        const MODE_DUMP_DATABASE = 14;

        const MODE_DUMP_TABLE = 15;

        const MODE_PRODUCTION = 16;

        const MODE_DEBUG = 17;

        const MODE_UNION = 18;

        const MODE_UNION_ALL = 19;

        const INNER_JOIN = 20;

        const CROSS_JOIN = 21;

        const LEFT_JOIN = 22;

        const RIGHT_JOIN = 23;

        const FULL_JOIN = 24;

        const SELF_JOIN = 25;

        const NATURAL_JOIN = 26;

        const SELECT = 'SELECT';

        const DELETE = 'DELETE';

        const UPDATE = 'UPDATE';

        const MODE = array(self::SELECT,self::DELETE,self::UPDATE);

        const BETWEEN = "BETWEEN";

        const NOT_BETWEEN = "NOT BETWEEN";

        const DESC = 'DESC';

        const ASC = 'ASC';

        const FIELD_TYPE = 'type';

        const FIELD_NAME = 'name';

        const FIELD_LENGTH = 'length';

        const FIELD_PRIMARY = 'primary';

        const FIELD_UNIQUE = 'unique';

        const FIELD_NULLABLE = 'nullable';

        const DROP_CONSTRAINT = 'drop_constraint';
        /**
        |-------------------------------|
        |          DATA TYPES           |
        |-------------------------------|
         */
        const TINYINT = 'TINYINT';
        const SMALLINT = 'SMALLINT';
        const MEDIUMINT = 'MEDIUMINT';
        const INT = 'INT';
        const REAL = 'REAL';
        const SERIAL = 'SERIAL';
        const BIG_SERIAL = 'BIG SERIAL';
        const BIT_VARYING = 'BIT VARYING';
        const BOOLEAN = 'BOOLEAN';
        const BOX = 'BOX';
        const BITEA = 'BITEA';
        const CHARACTER_VARYING = 'CHARACTER VARYING';
        const CHARACTER  = 'CHARACTER';
        const CIDR  = 'CIDR';
        const CIRCLE  = 'CIRCLE';
        const FLOAT8  = 'FLOAT8';
        const INET  = 'INET';
        const INTERVAL  = 'INTERVAL';
        const LINE  = 'LINE';
        const LSEG  = 'LSEG';
        const MACADDR  = 'MACADDR';
        const MONEY  = 'MONEY';
        const PATH  = 'PATH';
        const POINT  = 'POINT';
        const POLYGON  = 'POLYGON';
        const TIMEZ  = 'TIMEZ';
        const TSQUERY  = 'TSQUERY';
        const TSVECTOR  = 'TSVECTOR';
        const UUID  = 'UUID';
        const XML  = 'XML';
        const INTEGER = 'INTEGER';
        const BIGINT = 'BIGINT';
        const NUMERIC = 'NUMERIC';
        const DECIMAL = 'DECIMAL';
        const BIT = 'BIT';
        const DATE = 'DATE';
        const DATETIME = 'DATETIME';
        const TIMESTAMP = 'TIMESTAMP';
        const TIMESTAMPZ = 'TIMESTAMPZ';
        const CHAR = 'CHAR';
        const VARCHAR = 'VARCHAR';
        const ENUM = 'ENUM';
        const TEXT = 'TEXT';
        const LONGTEXT = 'LONGTEXT';
        const BLOB = 'BLOB';
        const VARBINARY = 'VARBINARY';
        const MEDIUMBLOB = 'MEDIUMBLOB';
        const MEDIUMTEXT = 'MEDIUMTEXT';
        const LONGBLOB = 'LONGBLOB';
        const NULL = 'NULL';
        const NONE = 'NONE';
        const NUMBER = 'NUMBER';
        const VARCHAR2 = 'VARCHAR2';

    }
}