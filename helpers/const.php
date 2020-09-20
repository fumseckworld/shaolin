<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

if (!defined('MYSQL')) {
    /**
     * The pdo driver for mysql.
     */
    define('MYSQL', 'mysql');
}

define('INDEX_PAGE', 'index,follow');

define('NO_INDEX_PAGE', 'noindex, nofollow');

if (!defined('POSTGRESQL')) {
    /**
     * The pdo driver value for postgresql.
     */
    define('POSTGRESQL', 'pgsql');
}

if (!defined('SQLITE')) {
    /**
     * The pdo driver value for sqlite.
     */
    define('SQLITE', 'sqlite');
}

if (!defined('SITE')) {
    define('SITE', 1);
}

if (!defined('ADMIN')) {
    define('ADMIN', 2);
}
if (!defined('TODO')) {
    define('TODO', 3);
}

if (!defined('ROUTING_MODES')) {
    define('ROUTING_MODES', [SITE, ADMIN, TODO]);
}

define('VALIDATOR_EMAIL_NOT_VALID', 1);
define('VALIDATOR_ARGUMENT_NOT_DEFINED', 2);
define('VALIDATOR_ARGUMENT_NOT_NUMERIC', 3);
define('VALIDATOR_ARGUMENT_NOT_UNIQUE', 4);
define('VALIDATOR_ARGUMENT_NOT_BETWEEN', 5);
define('VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE', 6);
define('VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE', 7);
define('VALIDATOR_ARGUMENT_SLUG', 8);
define('VALIDATOR_ARGUMENT_SNAKE', 9);
define('VALIDATOR_ARGUMENT_CAMEL_CASE', 10);
define('VALIDATOR_ARGUMENT_ARRAY', 11);
define('VALIDATOR_ARGUMENT_BOOLEAN', 12);
define('VALIDATOR_ARGUMENT_IMAGE', 13);
define('VALIDATOR_ARGUMENT_JSON', 14);
define('VALIDATOR_ARGUMENT_STRING', 15);
define('VALIDATOR_ARGUMENT_URL', 16);
define('VALIDATOR_ARGUMENT_FLOAT', 17);
define('VALIDATOR_ARGUMENT_INT', 18);
define('VALIDATOR_ARGUMENT_MAC', 19);
define('VALIDATOR_ARGUMENT_IPV4', 20);
define('VALIDATOR_ARGUMENT_IPV6', 21);
define('VALIDATOR_ARGUMENT_DOMAIN', 22);
