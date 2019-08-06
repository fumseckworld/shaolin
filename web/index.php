<?php


use Imperium\Model\Routes;

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

foreach (Routes::where('method',EQUAL,GET)->all() as $route)
{

    d($route->url);
    foreach ($routes as $route)
    {
        d($route);
        if ($this->match($route->url))
        {
            $this->route = $route;
            return $this->result();
        }
    }


}