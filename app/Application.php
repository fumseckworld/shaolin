<?php

namespace App;

use Eywa\Application\App;
use Eywa\Application\Environment\Env;
use Eywa\Database\Base\Base;
use Eywa\Database\Connexion\Connect;
use Eywa\Database\Table\Table;
use Eywa\Database\User\User;
use Eywa\Http\Request\Request;
use Eywa\Message\Flash\Flash;

class Application
{
    /**
     * @var Connect
     */
    private Connect $connect;
    /**
     * @var Base
     */
    private Base $base;
    /**
     * @var Flash
     */
    private Flash $flash;
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var Table
     */
    private Table $table;
    /**
     * @var App
     */
    private App $app;
    /**
     * @var User
     */
    private User $user;
    /**
     * @var Env
     */
    private Env $env;

    /**
     * Application constructor.
     * @param Connect $connect
     * @param Base $base
     * @param Flash $flash
     * @param Request $request
     * @param Table $table
     * @param App $app
     * @param User $user
     * @param Env $env
     */
    public function __construct(
        Connect $connect,
        Base $base,
        Flash $flash,
        Request $request,
        Table $table,
        App $app,
        User $user,
        Env $env
    ) {
        $this->connect = $connect;
        $this->base = $base;
        $this->flash = $flash;
        $this->request = $request;
        $this->table = $table;
        $this->app = $app;
        $this->user = $user;
        $this->env = $env;
    }
}
