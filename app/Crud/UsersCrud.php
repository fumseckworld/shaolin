<?php

namespace App\Crud;

use Nol\Database\Model\Crud;
use Nol\Http\Request\Request;
use Nol\Http\Response\Response;
use stdClass;

class UsersCrud extends Crud
{
    protected static string $table = 'users';

    /**
     * @inheritDoc
     */
    public function create(Request $request): Response
    {
        return app('response')->send();
    }

    /**
     * @inheritDoc
     */
    public function read(Request $request): Response
    {
        return app('response')->send();
    }

    /**
     * @inheritDoc
     */
    public function update(Request $request): Response
    {
        return app('response')->send();
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request): Response
    {
        return app('response')->send();
    }

    /**
     * @inheritDoc
     */
    public function each(stdClass $record): string
    {
        return '';
    }
}
