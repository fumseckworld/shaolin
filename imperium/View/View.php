<?php
	
	namespace Imperium\View
	{

        use Imperium\Cookies\Cookies;
        use Imperium\Exception\Kedavra;
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
		
		/**
		 *
		 * Class View
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\View
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
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
             *
             * @throws Kedavra
             * @throws LoaderError
             */
			public function __construct(string $views_path, array $config)
			{
				
				$this->views_path = $views_path;
				
				$this->config = $config;
				
				$cache_dir = collect($this->config)->get('cache');
				
				if(def($cache_dir))
					$this->config = collect($this->config)->refresh('cache', base($cache_dir))->all();
				
				$this->namespaces = config('twig', 'namespaces');
			
				$this->loader = new  FilesystemLoader($this->views_path);
			
				$this->twig = new Environment($this->loader(), $this->config);
			
				$this->twig->addExtension(new Twig_Extensions_Extension_Text());
			
				$this->twig->addExtension(new Twig_Extensions_Extension_I18n());
			
				$this->twig->addExtension(new ArrayExtension());
			
				$this->twig->addExtension(new TranslationExtension());

                $this->loader()->addPath($views_path . DIRECTORY_SEPARATOR . 'crud', 'crud');

                $this->loader()->addPath($views_path . DIRECTORY_SEPARATOR . 'todo', 'todo');

				if(def($this->namespaces))
				{
					foreach($this->namespaces as $k => $v)
						$this->loader()->addPath( $views_path . DIRECTORY_SEPARATOR .$k, $v);
				}
				
				$functions = collect();
				
				$functions->set(new TwigFunction('display', function(string $key)
				{
					
					$flash = new Flash(app()->session());
					
					return $flash->has($key) ? $flash->display($key) : '';
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('css', function(string $name)
				{
					
					return css($name);
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('mobile', function()
				{
					
					return is_mobile();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('os', function()
				{
					
					return new Os();
				}, [ 'is_safe' => [ 'html' ] ]),
					new TwigFunction('history', function()
				{
					
					return history();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('csrf_field', function()
				{
					
					return csrf_field();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('js', function(string $name, string $type = '')
				{
					
					return js($name, $type);
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('img', function(string $name, string $alt)
				{
					
					return img($name, $alt);
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('lang', function()
				{
					
					return app()->lang();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('_', function(string $message)
				{
					
					return gettext($message);
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('print', function(string $code)
				{
					
					return $code;
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('root', function()
				{
					
					return root();
				}, [ 'is_safe' => [ 'html' ] ]),

                new TwigFunction('locale', function(string $locale)
				{
					return equal(app()->lang(),$locale);

				}, [ 'is_safe' => [ 'html' ] ]),


                new TwigFunction('route', function(string $db, string $route,...$args)
				{
					
					return route($db,$route,$args);
				}, [ 'is_safe' => [ 'html' ] ]),new TwigFunction('logged', function()
				{
					
					return app()->auth()->connected();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('user', function()
				{
					
					return app()->auth()->current();
				}, [ 'is_safe' => [ 'html' ] ]), new TwigFunction('development', function()
				{
					
					return config('twig', 'development') === true;
				}, [ 'is_safe' => [ 'html' ] ]),new TwigFunction('url', function(...$args)
				{

					return url($args);
				}, [ 'is_safe' => [ 'html' ] ]));
				
				foreach($functions->all() as $function)
					$this->twig()->addFunction($function);
				
				putenv("LANG={$this->locale()}");
				
				bindtextdomain($this->domain(), $this->locale_path());
				
				bind_textdomain_codeset($this->domain(), 'UTF-8');
				
				textdomain($this->domain());

				$this->add_functions(glob(base('app').DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Functions'.DIRECTORY_SEPARATOR .'*.php'));

				$this->add_filters(glob(base('app').DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Filters'.DIRECTORY_SEPARATOR .'*.php'));

				$this->add_globals(glob(base('app').DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Globals'.DIRECTORY_SEPARATOR .'*.php'));

				$this->add_test(glob(base('app').DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Tests'.DIRECTORY_SEPARATOR .'*.php'));



			}
			
			/**
			 * @return Environment
			 */
			public function twig() : Environment
			{
				return $this->twig;
			}
			
			/**
			 * @param  string  $view
			 * @param  array   $args
			 *
			 * @throws LoaderError
			 * @throws RuntimeError
			 * @throws SyntaxError
			 * @return string
			 */
			public function load(string $view, array $args = []) : string
			{
				
				$view = collect(explode('.', $view))->first();
				
				append($view, '.twig');
				
				return $this->twig()->render($view, $args);
			}
			
			/**
			 * @return FilesystemLoader
			 */
			public function loader() : FilesystemLoader
			{
				return $this->loader;
			}

            /**
             * @return string
             *
             * @throws Kedavra
             *
             */
			public function locale() : string
			{
				 return (new Cookies())->get('locale', config('locales', 'locale'));
			}
			
			/**
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function domain()
			{
				return config('locales', 'domain');
			}
			
			/**
			 *
			 *
			 * @return mixed
			 *
			 */
			public function locale_path() : string
			{
				return base('po');
			}
			
			/**
			 *
			 * Add twig filters
			 *
			 * @param  array  $filters
			 *
			 *
			 */
			public function add_filters(array $filters) : void
			{
			    foreach ($filters as $filter)
                {
                    $x = collect(explode(DIRECTORY_SEPARATOR,$filter))->last();
                    $x = collect(explode('.',$x))->first();
                    $x = "App\Twig\Filters\\$x";

                    foreach ((new $x())->getFilters() as $j)
                        $this->twig->addFilter($j);
                }

			}
			
			/**
			 *
			 * Add twig test
			 *
			 * @param  array  $tests
			 *
			 *
			 */
			public function add_test(array $tests) : void
			{
				foreach($tests as $test)
                {
                    $x = collect(explode(DIRECTORY_SEPARATOR,$test))->last();
                    $x = collect(explode('.',$x))->first();
                    $x = "App\Twig\Tests\\$x";

                    foreach ((new $x())->getTests() as $j)
                        $this->twig->addTest($j);
                }

			}
			
			/**
			 *
			 * Add twig global
			 *
			 * @param  array  $globals
			 *
			 *
			 */
			public function add_globals(array $globals) : void
			{
                foreach($globals as $global)
                {
                    $x = collect(explode(DIRECTORY_SEPARATOR,$global))->last();
                    $x = collect(explode('.',$x))->first();
                    $x = "App\Twig\Globals\\$x";

                    foreach ((new $x())->getGlobals() as $k => $v)
                        $this->twig->addGlobal($k,$v);
                }
			}

			
			/**
			 * @param  array  $functions
			 */
			public function add_functions(array $functions): void
			{
				foreach($functions as $function)
                {
                    $x = collect(explode(DIRECTORY_SEPARATOR,$function))->last();
                    $x = collect(explode('.',$x))->first();
                    $x = "App\Twig\Functions\\$x";

                    foreach ((new $x())->getFunctions() as $j)
                        $this->twig->addFunction($j);
                }
			}
			
		}
	}