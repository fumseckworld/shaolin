<?php

declare(strict_types=1);

namespace Eywa\Html\Pagination {


    use Eywa\Exception\Kedavra;

    class Pagination
    {

        /**
         *
         * The current page
         *
         */
        private int $current_page;

        /**
         *
         * The limit
         *
         */
        private int $limit;

        /**
         *
         * The total of records
         *
         */
        private int $total;

        /**
         *
         * The number of pages
         *
         */
        private float $pages;

        /**
         *
         * The url to checkout of pages
         *
         */
        private string $url;

        /**
         *
         * @param int $current_page
         * @param int $limit
         * @param int $total
         *
         * @throws Kedavra
         *
         */
        public function __construct(int $current_page, int $limit, int $total)
        {

            $limit = equal($limit, 0) ? 1 : $limit;

            $this->current_page = $current_page;

            $this->limit = $limit;

            $this->total = $total;

            $this->pages = intval(ceil($total / $limit)) + 1;

            $this->url = config('pagination', 'url');

        }

        /**
         *
         * Get the pagination
         *
         * @throws Kedavra
         *
         * @return string
         *
         */
        public function paginate() : string
        {

            if (superior_or_equal($this->limit,$this->total))
                return '';

            $html = '<ul class="' . config('pagination', 'ul_class') . '">';

            for($i = 1; $i != $this->pages; $i++)
                $i === $this->current_page ? append($html, '<li class="' . config('pagination', 'li_class') . ' active"><a href="' . $this->url . $i . '" class="' . config('pagination', 'link_class') . '">' . $i . '</a></li>') : append($html, '<li class="' . config('pagination', 'li_class') . '"><a href="' . $this->url . $i . '" class="' . config('pagination', 'link_class') . '">' . $i . '</a></li>');

            append($html, '</ul>');

            return $html;
        }
    }
}