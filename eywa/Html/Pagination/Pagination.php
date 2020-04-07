<?php

declare(strict_types=1);

namespace Eywa\Html\Pagination {


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
        private int $pages;

        /**
         *
         * @param int $current_page
         * @param int $limit
         * @param int $total
         *
         *
         */
        public function __construct(int $current_page, int $limit, int $total)
        {
            $limit = $limit ===  0 ? 1 : $limit;

            $this->current_page = $current_page;

            $this->limit = $limit;
            $this->total = $total;
            $this->pages = intval(ceil($total / $limit));
        }

        /**
         *
         * Get the pagination
         *
         * @param string $prefix
         *
         * @return string
         *
         *
         */
        public function render(string $prefix): string
        {
            if ($this->current_page > $this->pages) {
                return '';
            }

            $html = '<ul class="' . config('pagination', 'ul_class') . '">';

            $next = strval($this->current_page + 1);
            $previous = strval(($this->current_page - 1)) ;
            if ($this->current_page > 1 && $this->current_page !== 1) {
                $html .= '<li class="' . config('pagination', 'li_class') . '">
                        <a href="' . url($prefix, $previous) . '" class="' . config('pagination', 'link_class') . '">'
                    . config('pagination', 'previous', 'previous') . '
                        </a>
                    </li>';
            }


            if ($this->current_page < $this->pages) {
                $html .= '<li class="' . config('pagination', 'li_class') . '">
                        <a href="' . url($prefix, $next) . '" class="' . config('pagination', 'link_class') . '">'
                . config('pagination', 'next', 'next') . '
                        </a>
                    </li>';
            }
            append($html, '</ul>');

            return $html;
        }
    }
}
