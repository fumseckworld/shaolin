<?php


namespace Imperium\View {


    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Sinergi\BrowserDetector\Os;
    use Symfony\Bridge\Twig\Extension\TranslationExtension;
    use Twig\Environment;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    use Twig\Extensions\ArrayExtension;
    use Twig\Loader\FilesystemLoader;
    use Twig\TwigFunction;
    use Twig_Extensions_Extension_I18n;
    use Twig_Extensions_Extension_Text;

    class View
    {
        /**
         * @var string
         */
        private $views_path;
        /**
         * @var FilesystemLoader
         */
        private $loader;
        /**
         * @var array
         */
        private $namespaces;

        /**
         * @var array
         */
        private $config;

        /**
         * @var Environment
         */
        private $twig;

        /**
         * Twig constructor.
         *
         * @Inject({"views.path", "views.config"})
         *
         * @param string $views_path
         *
         * @param array $config
         * @throws Kedavra
         * @throws LoaderError
         */
        public function __construct(string $views_path,array $config)
        {
            $this->views_path = $views_path;

            $this->config = $config;

            $this->namespaces = config('twig','namespaces');

            $this->loader = new  FilesystemLoader($this->views_path);

            $this->twig = new Environment($this->loader(),$this->config);

            $this->twig->addExtension(new Twig_Extensions_Extension_Text());

            $this->twig->addExtension(new Twig_Extensions_Extension_I18n());

            $this->twig->addExtension(new ArrayExtension());

            $this->twig->addExtension(new TranslationExtension());


            foreach ($this->namespaces as $k => $v)
                $this->loader()->addPath(VIEWS .DIRECTORY_SEPARATOR . $k ,$v);


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

            foreach ($functions->collection() as $function)
                $this->twig()->addFunction($function);

            putenv("LANG={$this->locale()}");

            bindtextdomain($this->domain(),$this->locale_path());

            bind_textdomain_codeset($this->domain(), 'UTF-8');

            textdomain($this->domain());
        }

        /**
         * @return Environment
         */
        public function twig(): Environment
        {
            return $this->twig;
        }

        /**
         * @param string $class
         * @param string $view
         * @param array $args
         *
         * @return string
         *
         * @throws Kedavra
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         *
         */
        public function load(string $class,string $view,array $args=[]): string
        {
            $dir = ucfirst(strtolower(str_replace('Controller','',collection(explode("\\",$class))->last())));

            Dir::create($dir);

            $view = collection(explode('.',$view))->begin();

            append($view,'.twig');
            
            $data = $dir .DIRECTORY_SEPARATOR . $view;

            $file = $this->views_path . DIRECTORY_SEPARATOR .$dir  .DIRECTORY_SEPARATOR .$view;

            if(!file_exists($file))
                (new File($file,EMPTY_AND_WRITE_FILE_MODE))->write("{% extends 'layout.twig' %}\n\n{% block title '' %}\n\n{% block description '' %}\n\n{% block css %}\n\n{% endblock %}\n\n{% block content %}\n\n\n\n{% endblock %}\n\n{% block js %}\n\n\n\n{% endblock %}\n");

            return $this->twig()->render($data,$args);
        }

        /**
         * @return FilesystemLoader
         */
        public function loader(): FilesystemLoader
        {
            return $this->loader;
        }

        /**
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function locale():string
        {
            return config('locales','locale');
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
         *
         */
        public function add_tags(array $extensions): void
        {
            foreach ($extensions as $extension)
                $this->twig()->addTokenParser($extension);
        }

        /**
         * @param array $functions
         */
        public function add_functions(array $functions)
        {
            foreach ($functions as $function)
                $this->twig()->addFunction($function);
        }


    }
}