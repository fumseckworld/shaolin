<?php

declare(strict_types=1);

namespace Eywa\Application {

    class Zen
    {



        /**
         *
         * To create a field with the tinyint type
         *
         * @var string
         *
         */
        const TINYINT = 'tinyint';

        /**
         *
         * To create a field with the smallint type
         *
         * @var string
         *
         */
        const SMALLINT = 'smallint';

        /**
         *
         * To create a field with the mediumint type
         *
         * @var string
         *
         */
        const MEDIUMINT = 'mediumint';

        /**
         *
         * To create a field with the int type
         *
         * @var string
         *
         */
        const INT = 'int';

        /**
         *
         * To create a field with the real type
         *
         * @var string
         *
         */
        const REAL = 'real';

        /**
         * To create a field with the serial type
         *
         * @var string
         */
        const SERIAL = 'serial';

        /**
         *
         * To create a field with the big serial type
         *
         * @var string
         *
         */
        const BIG_SERIAL = 'big serial';

        /**
         *
         * To create a field with the bit varying type
         *
         * @var string
         *
         */
        const BIT_VARYING = 'bit varying';

        /**
         *
         * To create a field with the boolean type
         *
         * @var string
         *
         */
        const BOOLEAN = 'boolean';

        /**
         *
         * To create a field with the box type
         *
         * @var string
         *
         */
        const BOX = 'box';

        /**
         *
         * To create a field with the bitea type
         *
         * @var string
         *
         */
        const BITEA = 'bitea';

        /**
         *
         * To create a field with the character varying type
         *
         * @var string
         *
         */
        const CHARACTER_VARYING = 'character varying';

        /**
         *
         * To create a field with the character type
         *
         * @var string
         *
         */
        const CHARACTER = 'character';

        /**
         *
         * To create a field with the cidr type
         *
         * @var string
         *
         */
        const CIDR = 'cidr';

        /**
         *
         * To create a field with the circle type
         *
         * @var string
         *
         */
        const CIRCLE = 'circle';

        /**
         *
         * To create a field with the float8 type
         *
         * @var string
         *
         */
        const FLOAT8 = 'float8';

        /**
         *
         * To create a field with the inet type
         *
         * @var string
         *
         */
        const INET = 'inet';

        /**
         *
         * To create a field with the interval type
         *
         * @var string
         *
         */
        const INTERVAL = 'interval';

        /**
         *
         * To create a field with the line type
         *
         * @var string
         *
         */
        const LINE = 'line';

        /**
         *
         * To create a field with the lseg type
         *
         * @var string
         *
         */
        const LSEG = 'lseg';

        /**
         *
         * To create a field with the macaddr type
         *
         * @var string
         *
         */
        const MACADDR = 'macaddr';

        /**
         *
         * To create a field with the macaddr8 type
         *
         * @var string
         *
         */
        const MACADDR8 = 'macaddr8';

        /**
         *
         * To create a field with the money type
         *
         * @var string
         *
         */
        const MONEY = 'money';

        /**
         *
         * To create a field with the path type
         *
         * @var string
         *
         */
        const PATH = 'path';

        /**
         *
         * To create a field with the point type
         *
         * @var string
         *
         */
        const POINT = 'point';

        /**
         *
         * To create a field with the polygon type
         *
         * @var string
         *
         */
        const POLYGON = 'polygon';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIME = 'time';

        /**
         *
         * To create a field with the tsquery type
         *
         * @var string
         *
         */
        const TSQUERY = 'tsquery';

        /**
         *
         * To create a field with the tsvector type
         *
         * @var string
         *
         */
        const TSVECTOR = 'tsvector';

        /**
         *
         * To create a field with the uuid type
         *
         * @var string
         *
         */
        const UUID = 'uuid';

        /**
         *
         * To create a field with the xml type
         *
         * @var string
         *
         */
        const XML = 'xml';

        /**
         *
         * To create a field with the integer type
         *
         * @var string
         *
         */
        const INTEGER = 'integer';

        /**
         *
         * To create a field with the bigint type
         *
         * @var string
         *
         */
        const BIGINT = 'bigint';

        /**
         *
         * To create a field with the numeric type
         *
         * @var string
         *
         */
        const NUMERIC = 'numeric';

        /**
         *
         * To create a field with the decimal type
         *
         * @var string
         *
         */
        const DECIMAL = 'decimal';

        /**
         *
         * To create a field with the bit type
         *
         * @var string
         *
         */
        const BIT = 'bit';

        /**
         *
         * To create a field with the date type
         *
         * @var string
         *
         */
        const DATE = 'date';

        /**
         *
         * To create a field with the datetime type
         *
         * @var string
         */
        const DATETIME = 'datetime';

        /**
         *
         * To create a field with the timestamp type
         *
         * @var string
         *
         */
        const TIMESTAMP = 'timestamp';

        /**
         *
         * To create a field with the char type
         *
         * @var string
         *
         */
        const CHAR = 'char';

        /**
         *
         * To create a field with the varchar type
         *
         * @var string
         *
         */
        const VARCHAR = 'varchar';

        /**
         *
         * To create a field with the enum type
         *
         * @var string
         *
         */
        const ENUM = 'enum';

        /**
         *
         * To create a field with the text type
         *
         * @var string
         *
         */
        const TEXT = 'text';

        /**
         *
         * To create a field with the longtext type
         *
         * @var string
         *
         */
        const LONGTEXT = 'longtext';

        /**
         *
         * To create a field with the blob type
         *
         * @var string
         *
         */
        const BLOB = 'blob';

        /**
         *
         * To create a field with the varbinary type
         *
         * @var string
         *
         */
        const VARBINARY = 'varbinary';

        /**
         *
         * To create a field with the mediumblob type
         *
         * @var string
         *
         */
        const MEDIUMBLOB = 'mediumblob';

        /**
         *
         * To create a field with the mediumtext type
         *
         * @var string
         *
         */
        const MEDIUMTEXT = 'mediumtext';

        /**
         *
         * To create a field with the longblob type
         *
         * @var string
         *
         */
        const LONGBLOB = 'longblob';

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
        const NONE = 'none';

        /**
         *
         * To create a field with the number type
         *
         * @var string
         *
         */
        const NUMBER = 'number';

        /**
         *
         * To create a field with the varchar2 type
         *
         * @var string
         */
        const VARCHAR2 = 'varchar2';

        /**
         *
         * To create a field with the year type
         *
         * @var string
         */
        const YEAR = 'year';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIMESTAMP_WITH_TIME_ZONE = 'timestamp with time zone';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIMESTAMP_WITHOUT_TIME_ZONE = 'timestamp without time zone';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIME_WITH_TIME_ZONE = 'time with time zone';

        /**
         *
         * To create a field with the time type
         *
         * @var string
         *
         */
        const TIME_WITHOUT_TIME_ZONE = 'time without time zone';

        /**
         *
         * To create a field with the double presition type
         *
         * @var string
         *
         */
        const DOUBLE_PRECISION = 'double precision';

        /**
         *
         * To create a field with the double  type
         *
         * @var string
         *
         */
        const DOUBLE = 'double';

        /**
         *
         * To create a field with the smallserial type
         *
         * @var string
         *
         */
        const SMALL_SERIAL = 'smallserial';

        /**
         *
         * To create a field with the int2 type
         *
         * @var string
         *
         */
        const INT2 = 'int2';

        /**
         *
         * To create a field with the int4 type
         *
         * @var string
         *
         */
        const INT4 = 'int4';

        /**
         *
         * To create a fied with the int4range type
         *
         * @var string
         *
         */
        const INT4_RANGE = 'int4range';

        /**
         *
         * To create a fied with the int8range type
         *
         * @var string
         *
         */
        const INT8_RANGE = 'int8range';

        /**
         *
         * To create a fied with the numrange type
         *
         * @var string
         *
         */
        const NUMRANGE = 'numrange';

        /**
         *
         * To create a fied with the tsrange type
         *
         * @var string
         *
         */
        const TSRANGE = 'tsrange';

        /**
         *
         * To create a fied with the tstzrange type
         *
         * @var string
         *
         */
        const TSTZRANGE = 'tstzrange';

        /**
         *
         * To create a fied with the int4range type
         *
         * @var string
         *
         */
        const DATE_RANGE = 'daterange';

        /**
         *
         * To create a field with the int8 type
         *
         * @var string
         */
        const INT8 = 'int8';

        /**
         *
         * To create a field with the float type
         *
         * @var string
         *
         */
        const FLOAT = 'float';

        /**
         *
         * To create a field with the binary type
         *
         * @var string
         *
         */
        const BINARY = 'binary';

        /**
         *
         * To create a field with the tinytext type
         *
         * @var string
         *
         */
        const TINYTEXT = 'tinytext';

        /**
         *
         * To create a field with the set type
         *
         * @var string
         *
         */
        const SET = 'set';

        /**
         *
         * To create a field with the json type
         *
         * @var string
         *
         */
        const JSON = 'json';

        /**
         *
         * To create a field with the jsonb type
         *
         * @var string
         *
         */
        const JSONB = 'jsonb';

        /**
         *
         * To create a field with the geometry type
         *
         * @var string
         *
         */
        const GEOMETRY = 'geometry';

        /**
         *
         * To create a fiel with the linestring type
         *
         * @var string
         *
         */
        const LINESTRING = 'linestring';

        /**
         *
         * To create a field with the multipoint type
         *
         * @var string
         *
         */
        const MULTIPOINT = 'multipoint';

        /**
         *
         * To create a field with the multilinestring type
         *
         * @var string
         *
         */
        const MULTILINESTRING = 'multilinestring';

        /**
         *
         * To create a field with the multipolygon type
         *
         * @var string
         *
         */
        const MULTIPOLYGON = 'multipolygon';

        /**
         *
         * To create a field with the geometrycollection type
         *
         * @var string
         *
         */
        const GEOMETRYCOLLECTION = 'geometrycollection';

        /**
         *
         * All boolean type
         *
         * @var array
         *
         */
        const BOOL = [ self::BOOLEAN ];

        /**
         *
         * All json types
         *
         * @var array
         *
         */
        const JSONS = [ self::JSON ];

        /**
         *
         * ALl date types
         *
         * @var array
         *
         */
        const DATE_TYPES = [ self::DATE, self::DATETIME, self::INTERVAL, self::TIME, self::TIMESTAMP, self::YEAR, self::INTERVAL, self::TIMESTAMP_WITH_TIME_ZONE, self::TIMESTAMP_WITHOUT_TIME_ZONE, self::TIME_WITH_TIME_ZONE, self::TIME_WITHOUT_TIME_ZONE ];

        /**
         *
         * All spacial types
         *
         * @var array
         *
         */
        const SPACIAL_TYPES = [ self::GEOMETRY, self::POINT, self::LINESTRING, self::POLYGON, self::MULTIPOINT, self::MULTILINESTRING, self::MULTIPOLYGON, self::GEOMETRYCOLLECTION ];

        /**
         *
         * All number types
         *
         * @var array
         *
         */
        const NUMERIC_TYPES = [ self::INT, self::INTEGER, self::DECIMAL, self::DOUBLE_PRECISION, self::BIGINT, self::REAL, self::DOUBLE, self::NUMERIC, self::BIG_SERIAL, self::BIT, self::SERIAL, self::SMALL_SERIAL, self::BIG_SERIAL, self::INT2, self::INT4, self::INT8, self::FLOAT, self::TINYINT, self::SMALLINT, self::MEDIUMINT ];

        /**
         *
         * All text types
         *
         * @var array
         *
         */
        const TEXT_TYPES = [ self::VARCHAR, self::CHAR, self::BINARY, self::VARBINARY, self::CHARACTER_VARYING, self::CHARACTER, self::BLOB, self::MEDIUMBLOB, self::ENUM, self::SET, self::TEXT, self::MEDIUMTEXT, self::TINYTEXT, self::TEXT, self::MEDIUMTEXT, self::LONGTEXT ];

        const ALL_TYPES  = [
            self::DATE, self::DATETIME, self::INTERVAL, self::TIME, self::TIMESTAMP, self::YEAR, self::INTERVAL, self::TIMESTAMP_WITH_TIME_ZONE, self::TIMESTAMP_WITHOUT_TIME_ZONE, self::TIME_WITH_TIME_ZONE, self::TIME_WITHOUT_TIME_ZONE, self::GEOMETRY, self::POINT, self::LINESTRING, self::POLYGON, self::MULTIPOINT, self::MULTILINESTRING, self::MULTIPOLYGON, self::GEOMETRYCOLLECTION, self::INT, self::INTEGER, self::DECIMAL, self::DOUBLE_PRECISION, self::BIGINT, self::REAL, self::DOUBLE, self::NUMERIC, self::BIG_SERIAL, self::BIT, self::SERIAL, self::SMALL_SERIAL, self::BIG_SERIAL, self::INT2, self::INT4, self::INT8, self::FLOAT, self::TINYINT, self::SMALLINT, self::MEDIUMINT, self::VARCHAR, self::CHAR, self::BINARY, self::VARBINARY, self::CHARACTER_VARYING, self::CHARACTER, self::BLOB, self::MEDIUMBLOB, self::ENUM, self::SET, self::TEXT, self::MEDIUMTEXT, self::TINYTEXT, self::TEXT, self::MEDIUMTEXT, self::LONGTEXT ];

        /**
         *
         * All floting point types
         *
         * @var array
         *
         */
        const FLOTING_POINT_TYPES = [ self::FLOAT, self::DOUBLE ];

        const MYSQL_TYPES         = [
            // CHARACTER
            self::CHAR, self::VARCHAR, self::BINARY, self::VARBINARY, self::BLOB, self::TINYTEXT, self::MEDIUMTEXT, self::TEXT, self::LONGTEXT, self::ENUM, self::SET, // INTEGER TYPES
            self::INTEGER, self::INT, self::SMALLINT, self::TINYINT, self::MEDIUMINT, self::BIGINT, self::REAL, self::DOUBLE, self::DOUBLE_PRECISION, // FIXED POINT TYPE
            self::DECIMAL, self::NUMERIC, // FLOTING POINT TYPES
            self::FLOAT, self::DOUBLE, // DATE AND TIME TYPES
            self::DATE, self::TIME, self::DATETIME, self::TIMESTAMP, self::YEAR, // JSON TYPES
            self::JSON, // BIT TYPE
            self::BIT, // BOLLEAN TYPES
            self::BOOLEAN, // SPACIAL DATA TYPES
            self::POINT, self::MULTIPOINT, self::LINESTRING, self::MULTILINESTRING, self::GEOMETRY, self::POLYGON, self::MULTIPOLYGON, self::GEOMETRYCOLLECTION ];

        /**
         *
         * All postgresql types
         *
         * @var array
         *
         */
        const POSTGRESQL_TYPES = [
            // CHARACTER
            self::CHAR, self::VARCHAR, self::CHARACTER_VARYING, self::TEXT, self::CHARACTER, // INTEGER TYPES
            self::SMALLINT, self::INTEGER, self::BIGINT, self::DECIMAL, self::NUMERIC, self::REAL, self::DOUBLE_PRECISION, self::SMALL_SERIAL, self::SERIAL, self::BIG_SERIAL, // FIXED POINT TYPE
            self::DECIMAL, self::NUMERIC, // FLOTING POINT TYPES
            self::FLOAT, self::DOUBLE, self::XML, self::MONEY, // RANGE TYPES
            self::INT4_RANGE, self::INT8_RANGE, self::NUMRANGE, self::TSRANGE, self::TSTZRANGE, self::DATE_RANGE, // DATE AND TIME TYPES
            self::TIMESTAMP, self::TIMESTAMP_WITHOUT_TIME_ZONE, self::TIMESTAMP_WITH_TIME_ZONE, self::DATE, self::TIME, self::TIME_WITHOUT_TIME_ZONE, self::TIME_WITH_TIME_ZONE, self::INTERVAL, // JSON TYPES
            self::JSON, self::JSONB, self::TSQUERY, self::TSVECTOR, // BIT TYPE
            self::BITEA, // BOLLEAN TYPES
            self::BOOLEAN, // NETWORKS TYPES
            self::CIDR, self::INET, self::MACADDR, self::MACADDR8, self::UUID, // SPACIAL DATA TYPES
            self::POINT, self::LINE, self::LSEG, self::BOX, self::PATH, self::POLYGON, self::CIRCLE, ];

        const SQLITE_TYPES     = [ self::NULL, self::INTEGER, self::REAL, self::TEXT, self::BLOB, self::NUMERIC, self::NONE ];


    }
}