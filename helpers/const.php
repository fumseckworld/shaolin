<?php

if (!defined('MAKE')) {
    /**
     * The type for the container to always generate a new instance, accessible by the namespace.
     */
    define('MAKE', 1);
}

if (!defined('INIT')) {
    /**
     * The type for the container to generate and save the instance, accessible by the namespace.
     */
    define('INIT', 2);
}

if (!defined('SYMBOL')) {
    /**
     * The type for the container to generate and save the instance, accessible by a word.
     */
    define('SYMBOL', 3);
}
