<?php
namespace Imperium\Databases\Dumper;

use Exception;

class CannotSetParameter extends Exception
{

    public static function conflictingParameters($name, $conflictName)
    {
        return new static("Cannot set `{$name}` because it conflicts with parameter `{$conflictName}`.");
    }
}
