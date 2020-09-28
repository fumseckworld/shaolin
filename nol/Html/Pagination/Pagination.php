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

    use DI\DependencyException;
    use DI\NotFoundException;


    /**
     *
     * Generate a pagination.
     *
     * @author   Willy Micieli <fumseck@fumseck.org>
     * @version  12
     * @package  Imperium\Html\Pagination
     *
     * @property int $current The current page.
     * @property int $limit   The per page limit.
     * @property int $total   The sum of the elements.
     * @property int $pages   The pages to create.
     *
     */
    class Pagination
    {

        /**
         *
         * @param int $current_page
         * @param int $limit
         * @param int $total
         */
        public function __construct(int $current_page, int $limit, int $total)
        {

            $limit = $limit === 0 ? 1 : $limit;

            $this->current = $current_page;
            $this->limit = $limit;
            $this->total = $total;
            $this->pages = intval(ceil($total / $limit));
        }

        /**
         *
         * Get the pagination
         *
         * @param array $prefix
         *
         * @return string
         *
         */
        public function render(array $prefix)
        {
            $x = $this->current;
            if ($x > $this->pages) {
                return '';
            }


            $next = strval($x + 1);
            $previous = strval(($x - 1));
            $last = strval($this->pages);

            $li = '';

            if ($x > 1) {
                if ($x !== $this->pages) {
                    $li .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
                <a href="' . url($prefix, '1') . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                        . config('pagination', 'first', 'first') . '
                    </a>
                </li>';
                }

                $li .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
        <a href="' . url($prefix, $previous) . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                    . config('pagination', 'previous', 'previous') . '
                        </a>
                    </li>';
            }

            if ($x < $this->pages) {
                $li .= '<li class="' . config('pagination', 'li_class', 'page-item') . '"> 
                <a href="' . url($prefix, $next) . '" class="' . config('pagination', 'link_class', 'page-link') . '"> '
                    . config('pagination', 'next', 'next') . '
                </a>
            </li>';
            }

            if ($x + 1 < $this->pages) {
                $li .= '<li class="' . config('pagination', 'li_class', 'page-item') . '">
            <a href="' . url($prefix, $last) . '" class="' . config('pagination', 'link_class', 'page-link') . '">'
                    . config('pagination', 'last', 'last') . '
            </a>
        </li>';
            }

            return sprintf(
                '<nav><ul class="pagination">%s</ul></nav>',
                $li
            );
        }

        /**
         *
         * Display all records match the request.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function found(): string
        {
            return str_replace('%d', strval($this->total), sprintf(
                '<div class="pagination-results">%s</div>',
                app('pagination-results-text'),
            ));
        }
    }
}
