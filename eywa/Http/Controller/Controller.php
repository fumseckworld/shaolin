<?php

declare(strict_types=1);
namespace Eywa\Http\Controller {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Application\App;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Response\Response;
    use Eywa\Http\View\View;

    abstract class Controller extends App
    {

    }
}