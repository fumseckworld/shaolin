<?php

declare(strict_types=1);

namespace Imperium\Html\Pagination;

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
