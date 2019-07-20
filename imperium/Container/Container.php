<?php


namespace Imperium\Container;


use Imperium\App;
use Imperium\Zen;

class Container
{
    private static $instance;

    public static function get(): App
    {
        if (is_null(self::$instance))
        {
            self::$instance = (new Zen())->app(App::class);
        }

        return self::$instance;
    }
}