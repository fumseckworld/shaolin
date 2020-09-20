<?php

namespace Nol\Http\Views {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Http\Response\Response;

    /**
     * Class View
     *
     * @author  Willy Micieli <micieli@outlook.fr>
     * @package Imperium\Http\Views\View
     * @version 12
     *
     * @property string $view                  The view name.
     * @property string $title                 The view title.
     * @property string $description           The view description.
     * @property string $layout                The view layout.
     * @property string $directory             The view directory.
     * @property array  $args                  The view arguments.
     * @property string $controller            The controller class name.
     * @property string $robots                The robot indexes directives.
     * @property string $keywords              The view keywords.
     * @property string $author                The view author.
     * @property string $creator               The view creator.
     *
     */
    final class View
    {

        /**
         * View constructor.
         *
         * @param string $directory
         * @param string $view        The view name.
         * @param string $title       The view title.
         * @param string $description The view description.
         * @param string $layout      The view layout.
         * @param array  $keywords
         * @param string $robot       The robots directive.
         * @param array  $args        The view arguments.
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function __construct(
            string $directory,
            string $view,
            string $title,
            string $description,
            string $layout,
            array $keywords,
            string $robot = INDEX_PAGE,
            array $args = []
        ) {
            $this->directory = $directory;
            $this->view = sprintf(
                '%s%s%s%s%s%s%s%s',
                app('app-directory'),
                DIRECTORY_SEPARATOR,
                app('views-directory'),
                DIRECTORY_SEPARATOR,
                $this->directory,
                DIRECTORY_SEPARATOR,
                ucfirst(strtolower($view)),
                '.php'
            );
            $this->title = $title;
            $this->description = $description;
            $this->layout = sprintf(
                '%s%s%s%s%s.php',
                app('app-directory'),
                DIRECTORY_SEPARATOR,
                app('views-directory'),
                DIRECTORY_SEPARATOR,
                $layout
            );
            $this->args = $args;
            $this->robots = $robot;
            $this->keywords = collect($keywords)->join(',');
            $this->author = env('AUTHOR', '');
            $this->creator = env('CREATOR', '');
        }

        /**
         *
         * Send the view.
         *
         * @throws DependencyException
         * @throws notFoundException
         *
         * @return Response
         *
         */
        final public function send(): Response
        {
            extract($this->args);

            ob_start();

            require($this->view);

            $title = $this->title;
            $description = $this->description;
            $author = $this->author;
            $keywords = $this->keywords;
            $creator = $this->creator;
            $robots = $this->robots;
            $content = ob_get_clean();

            ob_start();

            require($this->layout);

            return app('response')->setContent(strval(ob_get_clean()))->send();
        }
    }
}
