<?php
                        
$file = 'db';
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
                    "adapter" => config($file,'driver'),
                    "host" => config($file,'host'),
                    "name" => config($file,'base'),
                    "user" => config($file,'username'),
                    "pass" => config($file,'password'),
                    "port" => config($file,'port'),
                ]
        ]
];