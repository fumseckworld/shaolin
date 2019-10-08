<?php
	
	namespace Imperium
	{
		
		use DI\ContainerBuilder;
		use DI\DependencyException;
		use DI\NotFoundException as NotFoundExceptionAlias;
		use Exception;
		use Symfony\Component\HttpFoundation\Request;
		
		/**
		 *
		 * Class Zen
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
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
			const CHECK   = 9;
			
			/**
			 *
			 * The key to save and get if the field as a default value
			 *
			 * @var int
			 *
			 */
			const DEFAULT = 10;
			
			/**
			 *
			 * The key to save and get the default field value
			 *
			 * @var int
			 */
			const DEFAULT_VALUE = 11;
			
			/**
			 *
			 * The query mode to execute an union
			 *
			 * @var int
			 *
			 */
			const UNION = 12;
			
			/**
			 *
			 * The query mode to execute an union all
			 *
			 * @var int
			 *
			 */
			const UNION_ALL = 13;
			
			/**
			 *
			 * The query mode to execute an inner join
			 *
			 * @var int
			 *
			 */
			const INNER_JOIN = 14;
			
			/**
			 *
			 * The query mode to execute a cross join
			 *
			 * @var int
			 *
			 */
			const CROSS_JOIN = 15;
			
			/**
			 *
			 * The query mode to execute a left join
			 *
			 * @var int
			 *
			 */
			const LEFT_JOIN = 16;
			
			/**
			 *
			 * The query mode to execute a right join
			 *
			 * @var int
			 *
			 */
			const RIGHT_JOIN = 17;
			
			/**
			 *
			 * The query mode to execute a full join
			 *
			 * @var int
			 *
			 */
			const FULL_JOIN = 18;
			
			/**
			 *
			 * The query mode to execute a self join
			 *
			 * @var int
			 *
			 */
			const SELF_JOIN = 19;
			
			/**
			 *
			 * The query mode to execute a natural join
			 *
			 * @var int
			 *
			 */
			const NATURAL_JOIN = 20;
			
			/**
			 *
			 * The query mode to execute a select
			 *
			 * @var int
			 *
			 */
			const SELECT = 21;
			
			/**
			 *
			 * The query mode to execute a delete
			 *
			 * @var int
			 *
			 */
			const DELETE = 22;
			
			/**
			 *
			 * The query mode to execute an update
			 *
			 * @var int
			 *
			 */
			const UPDATE = 23;
			
			/**
			 *
			 * The query mode to execute an insert
			 *
			 * @var int
			 *
			 */
			const INSERT       = 24;
			
			const DISPLAY_MODE = [ DISPLAY_TABLE, DISPLAY_ARTICLE, DISPLAY_CONTRIBUTORS ];
			
			/**
			 *
			 * The field primary key name
			 *
			 * @var string
			 *
			 */
			const PRIMARY_KEY = 'id';
			
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
			const VALID_OPERATORS = [ EQUAL, DIFFERENT, INFERIOR, INFERIOR_OR_EQUAL, SUPERIOR, SUPERIOR_OR_EQUAL, LIKE, '<>' ];
			
			const CONDITION       = [ EQUAL => 'EQUAL', DIFFERENT => 'DIFFERENT', INFERIOR => 'INFERIOR', INFERIOR_OR_EQUAL => 'INFERIOR OR EQUAL', SUPERIOR => 'SUPERIOR', SUPERIOR_OR_EQUAL => 'SUPERIOR OR EQUAL', LIKE => 'LIKE' ];
			
			/**
			 *
			 * All modes supported
			 *
			 * @var array
			 *
			 */
			const MODE            = [
				UPDATE       => 'UPDATE',     // 23
				SELECT       => 'SELECT',     // 21
				DELETE       => 'DELETE',     // 22
				INSERT       => 'INSERT',     //24
				UNION        => 'UNION',      //12
				UNION_ALL    => 'UNION_ALL',  //13
				INNER_JOIN   => 'INNER JOIN', // 14
				CROSS_JOIN   => 'CROSS JOIN', //15
				LEFT_JOIN    => 'LEFT JOIN',  //16
				RIGHT_JOIN   => 'RIGHT JOIN', //17
				FULL_JOIN    => 'FULL JOIN',  // 18
				NATURAL_JOIN => 'NATURAL JOIN' ];
			
			const QUERY_VIEW_MODE = [
				SELECT       => 'SELECT',     // 21
				DELETE       => 'DELETE',     // 22
				UNION        => 'UNION',      //12
				UNION_ALL    => 'UNION_ALL',  //13
				INNER_JOIN   => 'INNER JOIN', // 14
				CROSS_JOIN   => 'CROSS JOIN', //15
				LEFT_JOIN    => 'LEFT JOIN',  //16
				RIGHT_JOIN   => 'RIGHT JOIN', //17
				FULL_JOIN    => 'FULL JOIN',  // 18
				NATURAL_JOIN => 'NATURAL JOIN' ];
			
			/**
			 *
			 * All join modes supported
			 *
			 * @var array
			 *
			 */
			const JOIN_MODE  = [ INNER_JOIN, CROSS_JOIN, LEFT_JOIN, RIGHT_JOIN, FULL_JOIN, NATURAL_JOIN ];
			
			const UNION_MODE = [ self::UNION, self::UNION_ALL, ];
			
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
			
			/**
			 * @var ContainerBuilder
			 */
			private static $container;
			
			/**
			 * @param  string  $key
			 *
			 * @throws NotFoundExceptionAlias
			 * @throws DependencyException
			 * @throws Exception
			 * @return mixed
			 */
			public function app(string $key)
			{
				
				return self::container()->get($key);
			}
			
		
			public static function container()
			{
				
				if(is_null(self::$container))
				{
					$c = new ContainerBuilder();
					$c->addDefinitions(base('app') . DIRECTORY_SEPARATOR . 'Container'.DIRECTORY_SEPARATOR.'admin.php');
					$c->addDefinitions(base('app') . DIRECTORY_SEPARATOR . 'Container'.DIRECTORY_SEPARATOR.'web.php');
					$c->enableCompilation(base() . DIRECTORY_SEPARATOR . 'tmp');
					$c->useAnnotations(true);
					$c->useAutowiring(true);
					$c = $c->build();
					$c->set('db.driver', db('driver'));
					$c->set('db.name', db('base'));
					$c->set('db.username', db('username'));
					$c->set('db.password', db('password'));
					$c->set('db.host', db('host'));
					$c->set('db.dump', db('dump'));
					$c->set("views.path",base('app'). DIRECTORY_SEPARATOR . 'Views') ;
					$c->set("views.config", config('twig', 'config'));
					$c->set(Request::class, Request::createFromGlobals());
					self::$container = $c;
				}
				
				return self::$container;
			}
			
		}
	}
