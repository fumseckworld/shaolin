<?php

namespace Imperium\View {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Imperium\Routing\Router;
    use Twig\TwigFunction;
    use Twig_Environment;
    use Twig_Error_Loader;
    use Twig_Error_Runtime;
    use Twig_Error_Syntax;
    use Twig_Extensions_Extension_I18n;
    use Twig_Function;
    use Twig_Loader_Filesystem;

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
         * @var Twig_Loader_Filesystem
         */
        private $loader;

        /**
         * @var Twig_Environment
         */
        private $twig;

        /**
         * View constructor.
         *
         *
         * @throws \Exception
         */
        public function __construct()
        {
            $file = 'app';

            $view_dir = core_path(collection(config('app','dir'))->get('app')) . DIRECTORY_SEPARATOR . collection(config('app','dir'))->get('view');

            $config = config($file,'config');


            Dir::create($view_dir);

            $this->view_dir = realpath($view_dir);

            $this->loader = new Twig_Loader_Filesystem($this->view_dir);

            $this->twig = new Twig_Environment($this->loader,$config);

            $functions = collection();

            $functions->add(new Twig_Function('display',

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
         * @throws Twig_Error_Loader
         * @throws Twig_Error_Runtime
         * @throws Twig_Error_Syntax
         * @throws Exception
         *
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
         * @param string $dir
         * @param string $namespace
         *
         * @return View
         *
         * @throws Twig_Error_Loader
         *
         */
        public function add_path(string $dir, string $namespace): View
        {
            $dir = $this->view_dir .DIRECTORY_SEPARATOR .$dir;

            $this->loader()->addPath($dir,$namespace);

            return $this;
        }

        /**
         *
         * Return an instance of twig
         *
         * @method twig
         *
         * @return Twig_Environment
         *
         */
        public function twig(): Twig_Environment
        {
            return $this->twig;
        }

        /**
         *
         * Return an instance of the loader
         *
         * @method loader
         *
         * @return Twig_Loader_Filesystem
         *
         */
        public function loader(): Twig_Loader_Filesystem
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