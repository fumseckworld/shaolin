<?php

declare(strict_types=1);

namespace Eywa\Http\View {

    use Eywa\Cache\Filecache;
    use Eywa\Exception\Kedavra;


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
            $this->layout = base('app','Views',$layout);

            $this->locale  = config('lang','locale');

            i18n($this->locale);
        }

        /**
         *
         * Render a view
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function render(): string
        {
            if ($this->has($this->cache))
            {
                ob_start();

                extract($this->args);

                require($this->file($this->cache));

                return  ltrim(ob_get_clean());
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


            $this
                ->replace('#{{ ([\$a-zA-Z-0-9]+) }}#','<?=  htmlentities($${1},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#{{ ([\$a-zA-Z-0-9]+).([\$a-zA-Z0-9]+) }}#','<?=  htmlentities($${1}->${2},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#@print\(([a-zA-Z0-9 ]+)\)#','<?=  html_entity_decode($${1},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#@d\(([\$a-zA-Z0-9 ]+)\)#','<?=  (new \Eywa\Debug\Dumper())->dump($${1});?>',$html,$html)
                ->replace('#@if\(([\$a-zA-Z0-9]+)\)#','<?php if($${1}) :?>',$html,$html)
                ->replace('#@elseif\(([\$a-zA-Z0-9]+)\)#','<?php elseif ($${1}) :?>',$html,$html)
                ->replace('#@else#','<?php else :?>',$html,$html)
                ->replace('#@endif#','<?php endif;?>',$html,$html)
                ->replace('#@for ([\$a-zA-Z-0-9]+) in ([\$a-zA-Z0-9]+)#','<?php foreach($${1} ?? [] as $${2}) : ?>',$html,$html)
                ->replace('#@endfor#','<?php endforeach;  ?>',$html,$html)
                ->replace('#@switch\(([\$a-zA-Z0-9]+)\)#','<?php switch($${1}): ',$html,$html)
                ->replace('#@case\(([\$a-zA-Z]+)\)#','case "${1}" :  ?>',$html,$html)
                ->replace('#@flash#','<?=  ioc(\'flash\')->call(\'display\'); ?>',$html,$html)
                ->replace('#@case\(([0-9]+)\)#','case ${1} :  ?>',$html,$html)
                ->replace('#@break#','<?php break;  ?>',$html,$html)
                ->replace('#@default#','<?php default :   ?>',$html,$html)
                ->replace('#@css\(([a-zA-Z0-9]+)\)#','<link rel="stylesheet"  href="/css/${1}.css">',$html,$html)
                ->replace('#@js\(([a-zA-Z0-9]+)\)#','<script src="/js/${1}.js"></script>',$html,$html)
                ->replace('#@endswitch#','<?php endswitch ;  ?>',$html,$html);


            $this->set($this->cache,$html);

            ob_start();

            require($this->file($this->cache));

            return  ltrim(ob_get_clean());

        }


        /**
         * @param string $regex
         * @param string $new
         * @param string $html
         * @param $content
         * @return View
         */
        private function replace(string $regex,string $new,string $html,&$content): View
        {
             $content = preg_replace($regex,$new,$html);
             return $this;
        }
    }
}