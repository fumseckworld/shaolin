<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace Nol\Html\Pagination {


    /**
     *
     * Generate a pagination.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @version  12
     * @package Imperium\Html\Pagination
     */
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
            $limit = $limit === 0 ? 1 : $limit;

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
            $x = $this->current_page;
            if ($x > $this->pages) {
                return '';
            }

            $html = '<nav><ul class="' . config('pagination', 'ul_class', 'pagination') . '">';

            $next = strval($x + 1);
            $previous = strval(($x - 1));
            $last = strval($this->pages);


            if ($x > 1) {
                if ($x !== $this->pages) {
                    $html .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
                <a href="' . url($prefix) . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                        . config('pagination', 'first', 'first') . '
                    </a>
                </li>';
                }

                $html .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
        <a href="' . url($prefix, $previous) . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                    . config('pagination', 'previous', 'previous') . '
                        </a>
                    </li>';
            }

            if ($x < $this->pages) {
                $html .= '<li class="' . config('pagination', 'li_class', 'page-item') . '"> 
                <a href="' . url($prefix, $next) . '" class="' . config('pagination', 'link_class', 'page-link') . '"> '
                    . config('pagination', 'next', 'next') . '
                </a>
            </li>';
            }

            if ($x + 1 < $this->pages) {
                $html .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
            <a href="' . url($prefix, $last) . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                    . config('pagination', 'last', 'last') . '
            </a>
        </li>';
            }

            $html .= '</ul></nav>';

            return $html;
        }
    }
}
