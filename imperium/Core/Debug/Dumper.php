<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 25/08/2018
 * Time: 21:26
 */

namespace Imperium\Core\Debug;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Dumper
{
    /**
     * Dump a value with elegance.
     *
     * @param  mixed  $value
     * @return void
     */
    public function dump($value)
    {
        $dumper = 'cli' === PHP_SAPI ? new CliDumper : new HtmlDumper;
        $dumper->dump((new VarCloner)->cloneVar($value));
    }
}