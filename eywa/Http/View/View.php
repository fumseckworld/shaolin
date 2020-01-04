<?php

declare(strict_types=1);

namespace Eywa\Http\View {

    use Eywa\Cache\Filecache;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\HttpFoundation\Response;


    class View extends Filecache
    {
        /**
         *
         * The view name
         *
         */
        private string $view;

        /**
         *
         * The view title
         *
         */
        private string $title;

        /**
         *
         * The view description
         *
         */
        private string $description;

        /**
         *
         * The view arguments
         *
         */
        private array $args;

        /**
         *
         * The layout
         *
         */
        private string $layout;

        /**
         *
         * The locale
         *
         */
        private string $locale;

        /**
         *
         * The cache filename
         *
         */
        private string $cache;

        /**
         * View constructor.
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         * @param string $layout
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $view, string $title, string $description, array $args = [],string $layout = 'layout.php')
        {

            $this->view = base('app','Views') .DIRECTORY_SEPARATOR . collect(explode('.',$view))->first() .'.php';


            $this->cache = collect(explode('.',$view))->first() .'.php';

            is_true(not_def($view,$title,$description),true,"You must have defined the view name, the view title and the view desccription");

            if (!file_exists($this->view))
                touch($this->view);

            $this->title = $title;
            $this->description = $description;
            $this->args = $args;
            $this->layout = base('app','Views') .DIRECTORY_SEPARATOR .$layout;

            $this->locale  = config('lang','locale');

            i18n($this->locale);
        }

        /**
         *
         * Render a view
         *
         * @param int $status
         * @param array $headers
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function render(int $status = 200,array $headers = []): Response
        {
            if ($this->has($this->cache))
            {
                ob_start();

                extract($this->args);

                require($this->file($this->cache));

                $html = ltrim(ob_get_clean());
                return (new Response($html,$status,$headers))->send();
            }


            ob_start();

            extract($this->args);

            require($this->view);

            $content = ltrim(ob_get_clean());

            $title = $this->title;

            $description = $this->description;

            $lang = collect(explode('_',$this->locale))->first();


             ob_start();

             extract(compact('content','title','description','lang'));

             require($this->layout);

            $html = ltrim(ob_get_clean());

            $flash = '{{ flash }}';
            if (def(strstr($html,$flash)))
            {
                $html = str_replace($flash,"<?= ioc('flash')->call('display'); ?>",$html);
            }


            $this->set($this->cache,$html);

           return (new Response($html,$status,$headers))->send();
        }
    }
}