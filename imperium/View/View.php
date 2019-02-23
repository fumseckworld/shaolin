<?php

namespace Imperium\View {

    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Twig\TwigFunction;
    use Twig_Environment;
    use Twig_Error_Loader;
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

            $file = 'views';

            $view_dir = def(request()->server->get('DOCUMENT_ROOT')) ? dirname(request()->server->get('DOCUMENT_ROOT')) . DIRECTORY_SEPARATOR .config($file,'dir') : config($file,'dir');

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
                    return config('locales','locale');
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('_',

                function (string $name,array $args = [])
                {
                    return trans($name,$args);
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

           $this->add_functions($functions->collection());
        }

        /**
         *
         *
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
         * @param string $view
         * @param array $args
         * @return string
         * @throws Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        public function load(string $view,array $args)
        {
            $parts = collection(explode(DIRECTORY_SEPARATOR,$view));

            $dir = strtolower(str_replace('Controller','',$parts->get(0)));

            $view = $dir .DIRECTORY_SEPARATOR . $parts->get(1);

            $dir = $this->view_dir . DIRECTORY_SEPARATOR . $dir;

            Dir::create($dir);

            $file = $dir . DIRECTORY_SEPARATOR . $parts->get(1);

            if (File::not_exist($file))
            {
                File::create($file);
                File::put($file,"{% extends 'layout.twig' %}\n\n{% block content %}\n\n\n\n{% endblock %}\n");
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
    }
}