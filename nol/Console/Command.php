<?php

namespace Nol\Console;

use Exception;
use Symfony\Component\Console\Application;

/**
 * Class Command
 *
 * @package Imperium\Console\Command
 * @version 12
 * @property Application $app The console instance.
 */
class Command
{
    /**
     * Command constructor.
     *
     * @param string $name
     * @param string $version
     */
    public function __construct(string $name, string $version)
    {
        $this->app = new Application($name, $version);
    }

    public function add(array $commands): Command
    {
        collect($commands)->for([$this->app,'add']);
        return $this;
    }

    /**
     * @throws Exception
     * @return int
     */
    public function run(): int
    {
        return $this->app->run();
    }
}
