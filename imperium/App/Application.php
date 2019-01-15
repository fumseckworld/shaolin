<?php


namespace Imperium\App {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Html\Form\Form;
    use Imperium\I18n\Language;
    use Imperium\Json\Json;
    use Imperium\Router\Router;
    use Twig_Environment;
    use Twig_Loader_Filesystem;

    class Application
    {
        /**
         * @var Router
         */
        private $router;

        /**
         * @var Json
         */
        private $json;
        /**
         * @var Language
         */
        private $app;

        /**
         * @var string
         */
        private $locale_path;
        /**
         * @var string
         */
        private $locale;
        /**
         * @var string
         */
        private $domain;
        /**
         * @var string
         */
        private $view_dir;
        /**
         * @var array
         */
        private $twig_config;

        /**
         * @var Form
         */
        private $form;

        /**
         * @var Twig_Environment
         */
        private $twig;
        /**
         * @var Twig_Loader_Filesystem
         */
        private $loader;

        /**
         * Application constructor.
         *
         * @param Router $router
         * @param string $locale_path
         * @param string $locale
         * @param string $domain
         *
         * @param string $view_dir
         * @param array $twig_config
         * @throws Exception
         */
        public function __construct(Router $router, string $locale_path, string $locale, string $domain,string $view_dir,array $twig_config)
        {
            Dir::create($view_dir);
            Dir::create($locale_path);

            $this->locale_path      = realpath($locale_path);
            $this->view_dir         = realpath($view_dir);
            $this->router           = $router;
            $this->locale           = $locale;
            $this->domain           = $domain;
            $this->twig_config      = $twig_config;

            $this->form             = new Form();
            $this->loader           = new Twig_Loader_Filesystem($this->views_dir());
            $this->twig             = new Twig_Environment($this->loader(),$this->twig_config());
            $this->json             = new Json();
            $this->app              = new Language($this->twig(),$this->locale_path(),$this->locale(),$this->domain());

        }

        /**
         *
         * Return the locale path
         *
         * @return string
         *
         */
        public function locale_path(): string
        {
            return $this->locale_path;
        }

        /**
         *
         * Return the locale
         *
         * @return string
         *
         */
        public function locale()
        {
            return $this->locale;
        }

        /**
         *
         * Return the views dir
         *
         * @return string
         *
         */
        public function views_dir(): string
        {
            return $this->view_dir;
        }

        /**
         *
         * Return the twig instance
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
         * Return twig loader instance
         *
         * @return Twig_Loader_Filesystem
         *
         */
        public function loader(): Twig_Loader_Filesystem
        {
            return $this->loader;
        }

        /**
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function run()
        {
            $this->app->translate();

            return $this->router()->run();
        }

        /**
         * @return Json
         */
        public function json(): Json
        {
            return $this->json;
        }

        /**
         * @return Form
         */
        public function form(): Form
        {
            return $this->form;
        }

        /**
         *
         * Return the domain
         *
         * @return string
         *
         */
        public function domain(): string
        {
            return $this->domain;
        }

        /**
         *
         * Return the twig config
         *
         * @return array
         */
        public function twig_config(): array
        {
            return $this->twig_config;
        }

        /**
         *
         * Add configuration
         *
         * @param array $config
         *
         * @return Application
         *
         * @throws Exception
         *
         */
        public function add_config(array $config): Application
        {
            $this->twig_config = collection($this->twig_config())->merge($config)->collection();
            return new static($this->router(),$this->locale_path(),$this->locale(),$this->domain(),$this->views_dir(),$this->twig_config());
        }

        /**
         *
         * @return Router
         */
        public function router(): Router
        {
            return $this->router;
        }
    }
}