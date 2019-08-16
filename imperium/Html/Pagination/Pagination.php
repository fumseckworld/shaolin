<?php
	
	namespace Imperium\Html\Pagination
	{
		
		use Imperium\Exception\Kedavra;
		
		/**
		 * Class Pagination
		 *
		 * @package Imperium\Html\Pagination
		 *
		 */
		class Pagination implements PaginationManagement
		{
			
			/**
			 * @var int
			 */
			private $current_page;
			
			/**
			 * @var int
			 */
			private $limit;
			
			/**
			 * @var int
			 */
			private $total;
			
			/**
			 * @var float
			 */
			private $pages;
			
			/**
			 * @var string
			 */
			private $url;
			
			/**
			 *
			 * @param  int  $current_page
			 * @param  int  $limit
			 * @param  int  $total
			 *
			 * @throws Kedavra
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
			 * @return string
			 *
			 */
			public function paginate() : string
			{
				
				$html = '<nav aria-label="Page navigation"><ul class="' . config('pagination', 'ul_class') . '">';
				for($i = 1; $i != $this->pages; $i++)
					$i === $this->current_page ? append($html, '<li class="' . config('pagination', 'li_class') . ' active"><a href="' . $this->url . $i . '" class="' . config('pagination', 'link_class') . '">' . $i . '</a></li>') : append($html, '<li class="' . config('pagination', 'li_class') . '"><a href="' . $this->url . $i . '" class="' . config('pagination', 'link_class') . '">' . $i . '</a></li>');
				append($html, '</ul></nav>');
				
				return $html;
			}
			
		}
	}