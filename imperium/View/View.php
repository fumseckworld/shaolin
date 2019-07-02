<?php

namespace Imperium\View {

    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Sinergi\BrowserDetector\Os;
    use Symfony\Bridge\Twig\Extension\TranslationExtension;
    use Twig\Environment;
    use Twig\Error\LoaderError as LoaderErrorAlias;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    use Twig\Extensions\ArrayExtension;
    use Twig\Loader\FilesystemLoader;
    use Twig\TwigFunction;
    use Twig_Extensions_Extension_I18n;
    use Twig_Extensions_Extension_Text;


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
         * @var array
         */
        private $config;



        /**
         * View constructor.
         *
         * @throws Kedavra
         * @throws LoaderErrorAlias
         *
         */
        public function __construct()
        {

            $this->view_dir = views_path();

            $this->config = config('twig','config');

            if (!app()->cache()->has('twig'))
            {

                $this->loader = new  FilesystemLoader($this->view_dir);

                $namespace = config('twig','namespaces');

                is_true(!is_array($namespace),true,'The twig namespace config must be an array');

                $this->registered_path = $namespace;

                foreach ($namespace as $k => $v)
                    $this->loader->addPath(views_path() .DIRECTORY_SEPARATOR . $k ,$v);

                app()->cache()->set('twig_loader',$this->loader);


                $this->twig()->addExtension(new Twig_Extensions_Extension_Text());
                $this->twig()->addExtension(new Twig_Extensions_Extension_I18n());
                $this->twig()->addExtension(new ArrayExtension());
                $this->twig()->addExtension(new TranslationExtension());

                $this->add_extensions(extensions('Extensions'));

                $this->add_filters(extensions('Filters'));

                $this->add_functions(extensions('Functions'));

                $this->add_globals(extensions('Globals'));

                $this->add_tags(extensions('Tags'));

                $this->add_test(extensions('Tests'));

                app()->cache()->set('twig', $this->twig);
            }


            $functions = collection();

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

            $functions->add(new TwigFunction('debug',

                function ()
                {
                    return  app()->debug_bar();
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

            $functions->add(new TwigFunction('os',

                function ()
                {
                    return new Os();
                },
                ['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('bootswatch',

                function (string $theme)
                {
                    return bootswatch($theme);
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

            $functions->add(new TwigFunction('lang',

                function ()
                {
                    return app()->lang();
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

                function (string $name,...$args)
                {
                    return route($name,$args);
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('navbar',


                function (string $app_name,string $class,string ...$names)
                {
                    return  navbar($app_name,$class,$names);
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

                function ()
                {
                    return current_user();
                }
                ,['is_safe' => ['html']]
            ));

            $functions->add(new TwigFunction('development',

                function ()
                {
                    return config('twig','development') === true;
                }
                ,['is_safe' => ['html']]
            ));

            $this->add_functions($functions->collection());

            putenv("LC_ALL={$this->locale()}");

            setlocale(LC_ALL, $this->locale());

            bindtextdomain($this->domain(),$this->locale_path());

            bind_textdomain_codeset($this->domain(), 'UTF-8');

            textdomain($this->domain());
        }

        /**
         * @return Environment
         * @throws Kedavra
         */
        public function twig()
        {
            if (is_null($this->twig))
                $this->twig = new Environment($this->loader(),$this->config);

            return $this->twig;
        }

        /**
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function registered_path(): array
        {
            $data = collection();

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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function load(string $view,array $args=[])
        {
            $parts = collection(explode(DIRECTORY_SEPARATOR,$view));


            $dir = ucfirst(strtolower(str_replace('Controller','',$parts->get(0))));

            $view = $dir .DIRECTORY_SEPARATOR . $parts->get(1);


            $dir = $this->view_dir . DIRECTORY_SEPARATOR . $dir;

            Dir::create($dir);

            $file = $dir . DIRECTORY_SEPARATOR . $parts->get(1);

            if (!File::exist($file))
            {
                (new File($file,EMPTY_AND_WRITE_FILE_MODE))->write("{% extends 'layout.twig' %}\n\n{% block title '' %}\n\n{% block description '' %}\n\n{% block css %}\n\n{% endblock %}\n\n{% block content %}\n\n\n\n{% endblock %}\n\n{% block js %}\n\n\n\n{% endblock %}\n");
            }

            return $this->twig()->render($view,$args);
        }


        /**
         *
         * Add a new global variable
         *
         * @method add_global
         *
         * @param string $name The variable name
         * @param mixed $value The variable value
         *
         * @return View
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function add_path(string $path, string $namespace): View
        {

            $dir = $this->view_dir .DIRECTORY_SEPARATOR .$path;


            $this->loader()->addPath($dir,$namespace);

            return $this;
        }


        /**
         *
         * Return an instance of the loader
         *
         * @method loader
         *
         * @return FilesystemLoader
         *
         * @throws Kedavra
         *
         */
        public function loader(): FilesystemLoader
        {
            return  app()->cache()->has('twig_loader') ? app()->cache()->get('twig_loader') : $this->loader ;
        }

        /**
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function domain()
        {
            return config('locales', 'domain');
        }

        /**
         * @return mixed
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function locale_path(): string
        {
            $dir =  ROOT . DIRECTORY_SEPARATOR . 'po';

            Dir::create($dir);

            return realpath($dir);
        }

        /**
         *
         * Add twig filters
         *
         * @param array $filters
         *
         * @throws Kedavra
         *
         */
        public function add_filters(array $filters): void
        {
            foreach ($filters as $filter)
                $this->twig()->addFilter($filter);

        }

        /**
         *
         * Add twig test
         *
         * @param array $tests
         *
         * @throws Kedavra
         *
         */
        public function add_test(array $tests): void
        {
            foreach ($tests as $test)
                $this->twig()->addTest($test);
        }

        /**
         *
         * Add twig global
         *
         * @param array $globals
         *
         * @throws Kedavra
         *
         */
        public function add_globals(array $globals): void
        {
            foreach ($globals as $k => $v)
                $this->twig()->addGlobal($k,$v);
        }

        /**
         * Add twig extensions
         *
         * @param array $extensions
         *
         * @throws Kedavra
         *
         */
        public function add_extensions(array $extensions): void
        {
            foreach ($extensions as $extension)
                $this->twig()->addExtension($extension);
        }

        /**
         *
         * @param array $extensions
         *
         * @throws Kedavra
         *
         */
        public function add_tags(array $extensions): void
        {
            foreach ($extensions as $extension)
                $this->twig()->addTokenParser($extension);
        }
    }
}
