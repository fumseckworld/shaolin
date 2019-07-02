<?php

$x = app()->connect();

return [
    "paths" => [
        "migrations" => "db/migrations",
        "seeds" => "db/seeds"
    ],
    "environments" =>
        [
            "default_migration_table" => "migrations",
            'default_database' => 'development',
            'development' =>
                [
                    "adapter" =>    $x->driver(),
                    "host" =>       $x->host(),
                    "name" =>       $x->base(),
                    "user" =>       $x->user(),
                    "pass" =>       $x->password(),
                    "port" =>      db('port'),
                ]
        ]
];