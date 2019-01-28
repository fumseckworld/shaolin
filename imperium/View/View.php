<?php

namespace Imperium\View {

    use Imperium\Directory\Dir;
    use Twig_Environment;
    use Twig_Error_Loader;
    use Twig_Error_Runtime;
    use Twig_Error_Syntax;
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
         * @param string $view_dir
         *
         * @param array $config
         * @param array $extensions
         */
        public function __construct(string $view_dir,array $config = [],array $extensions = [])
        {
            $this->view_dir = realpath($view_dir);
            $this->loader = new Twig_Loader_Filesystem($this->view_dir);
            $this->twig = new Twig_Environment($this->loader,$config);

            foreach ($extensions as $extension)
                $this->twig->addExtension($extension);
        }

        /**
         *
         * @param string $view_dir
         * @param array $config
         * @param array $extensions
         *
         * @return View
         *
         */
        public static function init(string $view_dir,array $config= [],array $extensions = []): View
        {
            return new static($view_dir,$config,$extensions);
        }

        /**
         *
         * Return a view
         *
         * @method load
         *
         * @param string $name
         * @param array $args
         *
         * @return string
         *
         * @throws Twig_Error_Loader
         * @throws Twig_Error_Runtime
         * @throws Twig_Error_Syntax
         */
        public function load(string $name,array $args = []): string
        {
            return $this->twig()->render($name,$args);
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
         * Return all globals variables
         *
         * @method globals
         *
         * @return array
         *
         */
        public function globals(): array
        {
            return $this->twig()->getGlobals();
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