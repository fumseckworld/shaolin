<?php

namespace Imperium {

    /**
    *
    * All const
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Zen
    {
        /**
         *
         * The key to save and get the field type
         *
         * @var int
         *
         */
        const FIELD_TYPE = 1;

        /**
         *
         * The key to save and get the field name
         *
         * @var int
         *
         */
        const FIELD_NAME = 2;

        /**
         *
         * The key to save and get the field length
         *
         * @var int
         *
         */
        const FIELD_LENGTH = 3;

        /**
         *
         * The key to save and get the primary key
         *
         * @var int
         *
         */
        const FIELD_PRIMARY = 4;

        /**
         *
         * The key to save and get the unique field
         *
         * @var int
         *
         */
        const FIELD_UNIQUE = 5;

        /**
         *
         * The key to save and get the field nullable or not
         *
         * @var int
         *
         */
        const FIELD_NULLABLE = 6;

        /**
         *
         * The key to save and get the field check expected value
         *
         * @var int
         *
         */
        const CHECK_EXPECTED = 7;

        /**
         *
         * The key to save and get the field check condition
         *
         * @var int
         *
         */
        const CHECK_CONDITION = 8;

        /**
         *
         * The key to save and get the field check
         *
         * @var int
         *
         **/
        const CHECK = 9;

        /**
         *
         * The key to save and get the default filed value
         *
         * @var int
         *
         */
        const DEFAULT = 10;

        /**
         *
         * The query mode to execute an union
         *
         * @var int
         *
         */
        const UNION = 11;

        /**
         *
         * The query mode to execute an union all
         *
         * @var int
         *
         */
        const UNION_ALL = 12;

        /**
         *
         * The query mode to execute an inner join
         *
         * @var int
         *
         */
        const INNER_JOIN = 13;

        /**
         *
         * The query mode to execute a cross join
         *
         * @var int
         *
         */
        const CROSS_JOIN = 14;

        /**
         *
         * The query mode to execute a left join
         *
         * @var int
         *
         */
        const LEFT_JOIN = 15;

        /**
         *
         * The query mode to execute a right join
         *
         * @var int
         *
         */
        const RIGHT_JOIN = 16;

        /**
         *
         * The query mode to execute a full join
         *
         * @var int
         *
         */
        const FULL_JOIN = 17;

        /**
         *
         * The query mode to execute a self join
         *
         * @var int
         *
         */
        const SELF_JOIN = 18;

        /**
         *
         * The query mode to execute a natural join
         *
         * @var int
         *
         */
        const NATURAL_JOIN = 19;

        /**
         *
         * The query mode to execute a select
         *
         * @var int
         *
         */
        const SELECT = 20;

        /**
         *
         * The query mode to execute a delete
         *
         * @var int
         *
         */
        const DELETE = 21;

        /**
         *
         * The query mode to execute an update
         *
         * @var int
         *
         */
        const UPDATE =  22;

        /**
         *
         * The query mode to execute an insert
         *
         * @var int
         *
         */
        const INSERT = 23;

        /**
         *
         * The equal condition for a query
         *
         * @var string
         *
         */
        const EQUAL = '=';

        /**
         *
         * The different condition for a query
         *
         * @var string
         *
         */
        const DIFFERENT = '!=';

        /**
         *
         * The superior condition for a query
         *
         * @var string
         *
         */
        const SUPERIOR = '>';

        /**
         *
         * The inferior condition for a query
         *
         * @var string
         *
         */
        const INFERIOR = '<';

        /**
         *
         * The superior or equal condition for a query
         *
         * @var string
         *
         */
        const SUPERIOR_OR_EQUAL = '>=';

        /**
         *
         * The inferior or equal condition for a query
         *
         * @var string
         *
         */
        const INFERIOR_OR_EQUAL = '<=';

        /**
         *
         * The query mode to execute a like
         *
         * @var string
         *
         */
        const LIKE = 'LIKE';

        /**
         *
         * The all query valid operators
         *
         * @var array
         *
         */
        const VALID_OPERATORS =  [
                self::EQUAL,self::DIFFERENT,self::INFERIOR,
                self::INFERIOR_OR_EQUAL,self::SUPERIOR,
                self::SUPERIOR_OR_EQUAL,self::LIKE
        ];

        /**
         *
         * All modes supported
         *
         * @var array
         *
         */
        const MODE = [
                self::UPDATE,self::SELECT,self::DELETE,self::INSERT,
                self::UNION,self::UNION_ALL,self::INNER_JOIN,
                self::CROSS_JOIN,self::LEFT_JOIN,
                self::RIGHT_JOIN,self::FULL_JOIN
        ];

        /**
         *
         * All join modes supported
         *
         * @var array
         *
         */
        const JOIN_MODE = [
            self::INNER_JOIN,self::CROSS_JOIN,self::LEFT_JOIN,
            self::RIGHT_JOIN,self::FULL_JOIN,self::NATURAL_JOIN
        ];

        /**
         *
         * The query mode to execute a between
         *
         * @var string
         *
         */
        const BETWEEN = "BETWEEN";

        /**
         *
         *
         * @var string
         */
        const NOT_BETWEEN = "NOT BETWEEN";

        /**
         *
         * The query mode to set the order by at desc
         *
         * @var string
         *
         */
        const DESC = 'DESC';

        /**
         *
         * The query mode to set the order by at asc
         *
         * @var string
         *
         */
        const ASC = 'ASC';

        /**
         *
         * To create a field with the tinyint type
         *
         * @var string
         *
         */
        const TINYINT = 'TINYINT';

        /**
         *
         * To create a field with the smallint type
         *
         * @var string
         *
         */
        const SMALLINT = 'SMALLINT';

        /**
         *
         * To create a field with the mediumint type
         *
         * @var string
         *
         */
        const MEDIUMINT = 'MEDIUMINT';

        /**
         *
         * To create a field with the int type
         *
         * @var string
         *
         */
        const INT = 'INT';

        /**
         *
         * To create a field with the real type
         *
         * @var string
         *
         */
        const REAL = 'REAL';

        /**
         * To create a field with the serial type
         *
         * @var string
         */
        const SERIAL = 'SERIAL';

        /**
         *
         * To create a field with the big serial type
         *
         * @var string
         *
         */
        const BIG_SERIAL = 'BIG SERIAL';

        /**
         *
         * To create a field with the bit varying type
         *
         * @var string
         *
         */
        const BIT_VARYING = 'BIT VARYING';

        /**
         *
         * To create a field with the boolean type
         *
         * @var string
         *
         */
        const BOOLEAN = 'BOOLEAN';

        /**
         *
         * To create a field with the box type
         *
         * @var string
         *
         */
        const BOX = 'BOX';

        /**
         *
         * To create a field with the bitea type
         *
         * @var string
         *
         */
        const BITEA = 'BITEA';

        /**
         *
         * To create a field with the character varying type
         *
         * @var string
         *
         */
        const CHARACTER_VARYING = 'CHARACTER VARYING';

        /**
         *
         * To create a field with the character type
         *
         * @var string
         *
         */
        const CHARACTER  = 'CHARACTER';

        /**
         *
         * To create a field with the cidr type
         *
         * @var string
         *
         */
        const CIDR  = 'CIDR';

        /**
         *
         * To create a field with the circle type
         *
         * @var string
         *
         */
        const CIRCLE  = 'CIRCLE';

        /**
         *
         * To create a field with the float8 type
         *
         * @var string
         *
         */
        const FLOAT8  = 'FLOAT8';

        /**
         *
         * To create a field with the inet type
         *
         * @var string
         *
         */
        const INET  = 'INET';

        /**
         *
         * To create a field with the interval type
         *
         * @var string
         *
         */
        const INTERVAL  = 'INTERVAL';

        /**
         *
         * To create a field with the line type
         *
         * @var string
         *
         */
        const LINE  = 'LINE';

        /**
         *
         * To create a field with the lseg type
         *
         * @var string
         *
         */
        const LSEG  = 'LSEG';

        /**
         *
         * To create a field with the macaddr type
         *
         * @var string
         *
         */
        const MACADDR  = 'MACADDR';

        /**
         *
         * To create a field with the money type
         *
         * @var string
         *
         */
        const MONEY  = 'MONEY';

        /**
         *
         * To create a field with the path type
         *
         * @var string
         *
         */
        const PATH  = 'PATH';

        /**
         *
         * To create a field with the point type
         *
         * @var string
         *
         */
        const POINT  = 'POINT';

        /**
         *
         * To create a field with the polygon type
         *
         * @var string
         *
         */
        const POLYGON  = 'POLYGON';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIME  = 'TIME';

        /**
         *
         * To create a field with the tsquery type
         *
         * @var string
         *
         */
        const TSQUERY  = 'TSQUERY';

        /**
         *
         * To create a field with the tsvector type
         *
         * @var string
         *
         */
        const TSVECTOR  = 'TSVECTOR';

        /**
         *
         * To create a field with the uuid type
         *
         * @var string
         *
         */
        const UUID  = 'UUID';

        /**
         *
         * To create a field with the xml type
         *
         * @var string
         *
         */
        const XML  = 'XML';

        /**
         *
         * To create a field with the integer type
         *
         * @var string
         *
         */
        const INTEGER = 'INTEGER';

        /**
         *
         * To create a field with the big int type
         *
         * @var string
         *
         */
        const BIGINT = 'BIGINT';

        /**
         *
         * To create a field with the numeric type
         *
         * @var string
         *
         */
        const NUMERIC = 'NUMERIC';

        /**
         *
         * To create a field with the decimal type
         *
         * @var string
         *
         */
        const DECIMAL = 'DECIMAL';

        /**
         *
         * To create a field with the bit type
         *
         * @var string
         *
         */
        const BIT = 'BIT';

        /**
         *
         * To create a field with the date type
         *
         * @var string
         *
         */
        const DATE = 'DATE';

        /**
         *
         * To create a field with the datetime type
         *
         * @var string
         */
        const DATETIME = 'DATETIME';

        /**
         *
         * To create a field with the timestamp type
         *
         * @var string
         *
         */
        const TIMESTAMP = 'TIMESTAMP';

       /**
        *
        * To create a field with the char type
        *
        * @var string
        *
        */
        const CHAR = 'CHAR';

        /**
         *
         * To create a field with the varchar type
         *
         * @var string
         *
         */
        const VARCHAR = 'VARCHAR';

        /**
         *
         * To create a field with the enum type
         *
         * @var string
         *
         */
        const ENUM = 'ENUM';

        /**
         *
         * To create a field with the text type
         *
         * @var string
         *
         */
        const TEXT = 'TEXT';

        /**
         *
         * To create a field with the longtext type
         *
         * @var string
         *
         */
        const LONGTEXT = 'LONGTEXT';

        /**
         *
         * To create a field with the blob type
         *
         * @var string
         *
         */
        const BLOB = 'BLOB';

        /**
         *
         * To create a field with the varbinary type
         *
         * @var string
         *
         */
        const VARBINARY = 'VARBINARY';

        /**
         *
         * To create a field with the mediumblob type
         *
         * @var string
         *
         */
        const MEDIUMBLOB = 'MEDIUMBLOB';

        /**
         *
         * To create a field with the mediumtext type
         *
         * @var string
         *
         */
        const MEDIUMTEXT = 'MEDIUMTEXT';

        /**
         *
         * To create a field with the longblob type
         *
         * @var string
         *
         */
        const LONGBLOB = 'LONGBLOB';

        /**
         *
         * To create a field with the null type
         *
         * @var string
         *
         */
        const NULL = 'NULL';

        /**
         *
         * To create a field with the not null type
         *
         * @var string
         *
         */
        const NOT_NULL = 'NOT NULL';

        /**
         *
         * To create a field with the none type
         *
         * @var string
         *
         */
        const NONE = 'NONE';

        /**
         *
         * To create a field with the number type
         *
         * @var string
         *
         */
        const NUMBER = 'NUMBER';

        /**
         *
         * To create a field with the varchar2 type
         *
         * @var string
         */
        const VARCHAR2 = 'VARCHAR2';

    }
}
