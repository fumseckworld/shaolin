<?php

declare(strict_types=1);

namespace Eywa\Html\Navigation {


    use Eywa\Exception\Kedavra;

    class Navigation
    {
        private array $links;

        private string $navigation_class;
        private string $navigation_li_class;
        private string $nav_a_class;

        /**
         * Navigation constructor.
         *
         * @throws Kedavra
         *
         */
        public function __construct()
        {
            $this->links = config('navigation', 'links');

            is_false(is_array($this->links), true, 'The links must be an array');

            $this->navigation_class = config('navigation', 'nav-class');

            is_false(is_string($this->navigation_class), true, 'The navigation class must be a string');

            $this->navigation_li_class = config('navigation', 'li-class');
            is_false(is_string($this->navigation_li_class), true, 'The navigation li class must be a string');
            $this->nav_a_class = config('navigation', 'a-class');
            is_false(is_string($this->nav_a_class), true, 'The navigation a class must be a string');
        }

        public function display(): string
        {
            $html = sprintf('<nav class="%s" ><ul>', $this->navigation_class);


            foreach ($this->links as $k => $v) {
                $link = cli()  ? strval($k) : url($k) ;
                append($html, sprintf('<li class="%s"><a href="%s" class="%s">%s</a></li>', $this->navigation_li_class, $link, $this->nav_a_class, $v));
            }
            append($html, '</ul></nav>');
            return $html;
        }
    }
}
