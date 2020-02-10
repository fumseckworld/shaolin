<?php

declare(strict_types=1);

namespace Eywa\Http\View {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Cache\FileCache;
    use Eywa\Exception\Kedavra;


    class View extends FileCache
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
         * @var string
         */
        private string $directory;

        /**
         * View constructor.
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         * @param string $layout
         * @param string $directory
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function __construct(string $view, string $title, string $description, array $args = [],string $layout = 'layout.php',string $directory='')
        {

            $x =  collect(explode('.',$view))->first() .'.php';

            $this->view = def($directory) ? base('app','Views',$directory,$x) : base('app','Views',$x);

            $this->cache = $x;

            is_true(not_def($view,$title,$description),true,"You must have defined the view name, the view title and the view desccription");

            if (!file_exists($this->view))
                touch($this->view);

            $this->title = $title;

            $this->description = $description;

            $this->args = $args;

            $this->layout = base('app','Views',$layout);

            $this->locale  = not_cli() ? app()->lang() : config('i18n','locale');

            i18n($this->locale);

            $this->directory = $directory;
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

            $this
                ->replace('#{{ ([\$a-zA-Z-0-9\_]+) }}#','<?=  htmlentities($${1},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#{{ ([\$a-zA-Z-0-9\_]+).([\$a-zA-Z0-9\_]+) }}#','<?=  htmlentities($${1}->${2},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#@print\(([a-zA-Z0-9 ]+)\)#','<?=  html_entity_decode($${1},ENT_QUOTES,"UTF-8");?>',$html,$html)
                ->replace('#@d\(([\$a-zA-Z0-9 ]+)\)#','<?=  (new \Eywa\Debug\Dumper())->dump($${1});?>',$html,$html)
                ->replace('#@trans\(([a-zA-Z0-9 ]+)\)#','<?=  _("${1}");?>',$html,$html)
                ->replace('#@trans\(([\$a-zA-Z0-9 ]+)\)#','<?=  _(${1});?>',$html,$html)
                ->replace('#@if\(([\$a-zA-Z0-9]+)\)#','<?php if($${1}) :?>',$html,$html)
                ->replace('#@elseif\(([\$a-zA-Z0-9]+)\)#','<?php elseif ($${1}) :?>',$html,$html)
                ->replace('#@else#','<?php else :?>',$html,$html)
                ->replace('#@endif#','<?php endif;?>',$html,$html)
                ->replace('#@for ([\$a-zA-Z-0-9]+) in ([\$a-zA-Z0-9]+)#','<?php foreach($${1} ?? [] as $${2}) : ?>',$html,$html)
                ->replace('#@endfor#','<?php endforeach;  ?>',$html,$html)
                ->replace('#@switch\(([\$a-zA-Z0-9]+)\)#','<?php switch($${1}): ',$html,$html)
                ->replace('#@case\(([\$a-zA-Z]+)\)#','case "${1}" :  ?>',$html,$html)
                ->replace('#@flash#','<?=  ioc(\'flash\')->call(\'display\'); ?>',$html,$html)
                ->replace('#@alert\(([a-zA-Z0-9\_\- ]+),([\$a-zA-Z0-9\_ ]+)\)#','<div class="alert ${1}">${2}</div>',$html,$html)
                ->replace('#@case\(([0-9]+)\)#','case ${1} :  ?>',$html,$html)
                ->replace('#@break#','<?php break;  ?>',$html,$html)
                ->replace('#@default#','<?php default :   ?>',$html,$html)
                ->replace('#@logged#','<?php if(logged()) :?>',$html,$html)
                ->replace('#@mobile#','<?php if(app()->detect()->mobile()) :?>',$html,$html)
                ->replace('#@tablet#','<?php if(app()->detect()->tablet()) :?>',$html,$html)
                ->replace('#@desktop#','<?php if(app()->detect()->desktop()) :?>',$html,$html)
                ->replace('#@guest#','<?php if(guest()) :?>',$html,$html)
                ->replace('#@endlogged#','<?php endif;?>',$html,$html)
                ->replace('#@endguest#','<?php endif;?>',$html,$html)
                ->replace('#@unless\(([\$a-zA-Z0-9]+)\)#','<?php if(is_false($${1})) :?>',$html,$html)
                ->replace('#@endunless#','<?php endif;?>',$html,$html)
                ->replace('#@endmobile#','<?php endif;?>',$html,$html)
                ->replace('#@endtablet#','<?php endif;?>',$html,$html)
                ->replace('#@enddesktop#','<?php endif;?>',$html,$html)
                ->replace('#@css\(([a-zA-Z0-9]+)\)#','<link rel="stylesheet"  href="/css/${1}.css">',$html,$html)
                ->replace('#@js\(([a-zA-Z0-9]+)\)#','<script src="/js/${1}.js"></script>',$html,$html)
                ->replace('#@endswitch#','<?php endswitch ;  ?>',$html,$html);


            $this->set($this->cache,$html);

            ob_start();

            extract($this->args);

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