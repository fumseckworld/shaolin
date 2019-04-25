<?php


namespace Imperium\Command {


    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class App extends Command
    {
        protected static $defaultName = 'app:create';
        private $app_dir;
        private $controller_dir;
        private $command_dir;
        private $middleware_dir;
        private $views_dir;
        private $namespace;
        private $cache;
        private $web;
        private $database_dir;

        protected function configure()
        {
            $this->setDescription("Create the application structure")->setAliases(['create']);
        }

        public function interact(InputInterface $input, OutputInterface $output)
        {
            $helper = $this->getHelper('question');

            $question = new Question("<info>Set the application directory</info> <comment>[app]</comment> : ",'app');

            $this->app_dir =  $helper->ask($input,$output,$question);

            $question = new Question("<info>Set the controllers directory</info> <comment>[Controllers]</comment> : ",'Controllers');

            $this->controller_dir =  $helper->ask($input,$output,$question);

            $question = new Question("<info>Set the commands directory</info> <comment>[Command]</comment> : ",'Command');

            $this->command_dir =  $helper->ask($input,$output,$question);

            $question = new Question("<info>Set the middleware directory</info> <comment>[Middleware]</comment> : ",'Middleware');

            $this->middleware_dir =  $helper->ask($input,$output,$question);


            $question = new Question("<info>Set the views directory</info> <comment>[Views]</comment> : ",'Views');

            $this->views_dir =  $helper->ask($input,$output,$question);

            $question = new Question("<info>Set the application namespace </info> <comment>[App]</comment> : ",'App');

            $this->namespace =  $helper->ask($input,$output,$question);

            $this->web = 'web';

            $question = new Question("<info>Cache directory </info> <comment>[cache]</comment> : ",'cache');

            $this->cache =  $helper->ask($input,$output,$question);

            $question = new Question("<info>Set the migrations, seeding, dump directory name </info> <comment>[db]</comment> : ",'db');

            $this->database_dir =  $helper->ask($input,$output,$question);
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $file = 'config/app.yaml';

            File::remove_if_exist($file);

            File::create($file);

            File::put($file,"dir:\n  app: '$this->app_dir'\n  controller: '$this->controller_dir'\n  command: '$this->command_dir'\n  middleware: '$this->middleware_dir'\n  view: '$this->views_dir'\n  db: '$this->database_dir'\n\nnamespace: '$this->namespace'\nweb_root: '$this->web'\ndevelopment_server_port: '3000'\nconfig:\n  cache: '$this->cache'\n  charset: 'utf-8'");

            Dir::create('locales');
            
            Dir::create('po');

            Dir::structure($this->database_dir,'seeds','migrations','dump');


            File::remove_if_exist('phinx.php');
            File::create('phinx.php');
            File::put('phinx.php',"<?php

\$x = app()->connect();

return [
    \"paths\" => [
        \"migrations\" => \"$this->database_dir/migrations\",
        \"seeds\" => \"$this->database_dir/seeds\"
    ],
    \"environments\" =>
        [
            \"default_migration_table\" => \"migrations\",
            'default_database' => 'development',
            'development' =>
                [
                    \"adapter\" =>    \$x->driver(),
                    \"host\" =>       \$x->host(),
                    \"name\" =>       \$x->base(),
                    \"user\" =>       \$x->user(),
                    \"pass\" =>       \$x->password(),
                    \"port\" =>       config('db','port'),
                ]
        ]
];");

            $app = $this->app_dir;
            $web = $this->web;
            $views = $app .DIRECTORY_SEPARATOR . $this->views_dir;


            Dir::structure($app,$this->controller_dir,$this->middleware_dir,$this->views_dir,$this->command_dir,'Helpers');

            Dir::structure("$app/Assets",'js','sass');

            Dir::structure("$app/Twig",'Extensions','Functions','Filters','Tags','Tests','Globals');

            Dir::structure($web,'img','css','js');

            File::structure("$app/Helpers",'web.php','admin.php');

            File::put("$app/Helpers/web.php","<?php\n");
            File::put("$app/Helpers/admin.php","<?php\n");

            Dir::copy('assets',"$app/Assets");

            Dir::remove('assets');

            Dir::checkout($views);

            $layout ='layout.twig';

            File::create($layout);
            File::put($layout,"<!doctype html>
<html lang=\"{{ lang() }}\">
    <head>
        <!-- Required meta tags -->
        <meta charset=\"utf-8\">

        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

        <!-- Bootstrap CSS -->
        {{ css('app') }}
        {% block css %} {% endblock %}
        <title>{% block title %}{% endblock %}</title>

        <meta name=\"description\" content=\"{% block description %} {% endblock %}\">

    </head>
    <body>
        {{navbar('shaolin','navbar navbar-expand-lg navbar-light bg-light shadow fixed-top','')}}

        <main class=\"container mt-5\">
            {{ display('success') }}
            {{ display('failure') }}
            {% block content %} {% endblock %}
        </main>

        {{ js('app.js') }}
        {% block js %} {% endblock %}
    </body>
</html>");

            Dir::checkout("../..");


            Dir::checkout("$web");



            File::create('index.php');
            File::put('index.php',"<?php\n\nrequire_once dirname(__DIR__) . '/vendor/autoload.php';\n\necho app()->run();");
            File::create('.htaccess');
            File::put('.htaccess','<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$  index.php [L]
</IfModule>');

            Dir::checkout('..');
            Dir::remove('tmp');


            return 0;
        }

    }
}