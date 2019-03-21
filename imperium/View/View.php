<?php

namespace Imperium\View {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Imperium\Routing\Router;
    use Twig\Environment;
    use Twig\Error\LoaderError as LoaderErrorAlias;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    use Twig\Loader\FilesystemLoader;
    use Twig\TwigFunction;
    use Twig_Extensions_Extension_I18n;


    /**
     *
     * View management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class  View
    {
        /**
         * @var string
         */
        private $view_dir;


        /**
         * @var FilesystemLoader
         */
        private $loader;

        /**
         * @var Environment
         */
        private $twig;

        /**
         * @var array
         */
        private $registered_path;

        /**
         * View constructor.
         *
         *
         * @throws \Exception
         */
        public function __construct()
        {
            $file = 'app';

            Dir::create(core_path(\collection(config('app','dir'))->get('app')));

            $view_dir = core_path(collection(config('app','dir'))->get('app')) . DIRECTORY_SEPARATOR . collection(config('app','dir'))->get('view');

            $config = config($file,'config');

            Dir::create($view_dir);

            $this->view_dir = realpath($view_dir);

            $this->loader = new  FilesystemLoader($this->view_dir);

            $this->twig = new Environment($this->loader,$config);

            $namespace = config('twig','namespaces');

            is_true(!is_array($namespace),true,'The twig namespace config must be an array');

            $this->registered_path = $namespace;

            foreach ($namespace as $k => $v)
               $this->add_path($k,$v);

            $functions = collection();

            $functions->add(new TwigFunction('app',

                function ()
                {
                   return app();

                },
                ['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('display',

                function (string $key)
                {
                    $flash = new Flash();

                    return $flash->has($key) ? $flash->display($key) : '';

                },
                ['is_safe' => ['html']]
            ));

           $functions->add(new TwigFunction('css',

               function (string $name)
               {
                   return css($name);
               },
               ['is_safe' => ['html']]
           ));

            $functions->add(new TwigFunction('copyright',

                function ()
                {
                    return copyright();
                },
                ['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('mobile',

                function ()
                {
                    return is_mobile();
                },
                ['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('back',

                function ()
                {
                    return url();
                },
                ['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('auth',

                function ()
                {
                    return app()->auth();
                },
                ['is_safe' => ['html']]
            ));

           $functions->add(new TwigFunction('csrf_field',

            function ()
            {
                return csrf_field();
            },
            ['is_safe' => ['html']]
        ));

           $functions->add(new TwigFunction('js',

               function (string $name,string $type = '')
               {
                   return js($name,$type);
               }
               ,['is_safe' => ['html']]
           ));

            $functions->add(new TwigFunction('img',

                function (string $name,string $alt)
                {
                    return img($name,$alt);
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('form',

                function ($name)
                {
                    return $name;
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('lang',

                function ()
                {
                    return $this->locale();
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('t',

                function (string $message,array $args = [])
                {
                    return trans($message,$args);
                }
                ,['is_safe' => ['html']]
            ));


            $functions->add(new TwigFunction('_',

                function (string $message)
                {
                    return gettext($message);
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('name',

                function (string $name,string $method = GET)
                {
                    return name($name,$method);
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('print',

                function (string $code)
                {
                    return $code;
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('root',

                function ()
                {
                    return root();
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('route',

                function (string $name,string $method = GET)
                {
                    return route($name,$method);
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('logged',

                function ()
                {
                    return app()->auth()->connected();
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('user',

                function (): Collection
                {
                    return current_user();
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('site',

                function (string $name,string $method = GET)
                {
                    return Router::web($name,$method);
                }
                ,['is_safe' => ['html']]
            ));

            $this->twig->addExtension(new Twig_Extensions_Extension_I18n());

            $this->add_functions($functions->collection());

            putenv("LC_ALL={$this->locale()}");

            setlocale(LC_ALL, $this->locale());

            bindtextdomain($this->domain(),$this->locale_path());

            bind_textdomain_codeset($this->domain(), 'UTF-8');

            textdomain($this->domain());
        }

        /**
         * @return array
         */
        public function registered_path(): array
        {
            $data = \collection();

            foreach ($this->registered_path as $k => $v)
                $data->add($this->loader()->getPaths($k),$k);

            return $data->collection();
        }
        /**
         *
         * Add twig functions
         *
         * @param array $functions
         *
         * @return View
         *
         */
        public function add_functions(array $functions): View
        {
            foreach ($functions as $function)
                $this->twig()->addFunction($function);

            return $this;
        }


        /**
         *
         * Load a view
         *
         * @param string $view
         * @param array $args
         *
         * @return string
         *
         * @throws LoaderErrorAlias
         * @throws RuntimeError
         * @throws SyntaxError
         * @throws Exception
         */
        public function load(string $view,array $args)
        {
            $parts = collection(explode(DIRECTORY_SEPARATOR,$view));

            $dir = ucfirst(strtolower(str_replace('Controller','',$parts->get(0))));

            $view = $dir .DIRECTORY_SEPARATOR . $parts->get(1);

            $dir = $this->view_dir . DIRECTORY_SEPARATOR . $dir;

            Dir::create($dir);

            $file = $dir . DIRECTORY_SEPARATOR . $parts->get(1);

            if (File::not_exist($file))
            {
                File::create($file);
                File::put($file,"{% extends 'layout.twig' %}\n\n{% block title %}\n\n{% endblock %}\n\n{% block description %}\n\n{% endblock %}\n\n{% block content %}\n\n\n\n{% endblock %}\n");
            }

            return $this->twig->render($view,$args);
        }



        /**
         *
         * Add a new global variable
         *
         * @method add_global
         *
         * @param string $name The variable name
         * @param mixed  $value  The variable value
         *
         * @return View
         *
         */
        public function add_global(string $name, $value): View
        {
            $this->twig()->addGlobal($name,$value);

            return $this;
        }


        /**
         *
         * Return all view paths
         *
         * @method paths
         *
         * @return array
         *
         */
        public function paths(): array
        {
            return $this->loader()->getPaths();
        }

        /**
         *
         * Add a new views dir
         *
         * @method add_path
         *
         * @param string $path
         * @param string $namespace
         *
         * @return View
         *
         * @throws LoaderErrorAlias
         *
         */
        public function add_path(string $path, string $namespace): View
        {

            $dir = $this->view_dir .DIRECTORY_SEPARATOR .$path;

            Dir::create($dir);

            $this->loader()->addPath($dir,$namespace);

            return $this;
        }

        /**
         *
         * Return an instance of twig
         *
         * @method twig
         *
         * @return Environment
         *
         */
        public function twig(): Environment
        {
            return $this->twig;
        }

        /**
         *
         * Return an instance of the loader
         *
         * @method loader
         *
         * @return FilesystemLoader
         *
         */
        public function loader(): FilesystemLoader
        {
            return $this->loader;
        }

        /**
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function domain()
        {
            return config('locales', 'domain');
        }

        /**
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function locale()
        {
            return config('locales', 'locale');
        }

        /**
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function locale_path():string
        {
            $dir = dirname(core_path(collection(config('app','dir'))->get('app'))) . DIRECTORY_SEPARATOR . 'po';

            Dir::create($dir);

            return realpath($dir);
        }
    }
}