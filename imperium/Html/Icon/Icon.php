<?php
	
	namespace Imperium\Html\Icon
	{
		
		use Exception;
		use Imperium\Collection\Collect;
		
		/**
		 *
		 * Class Icon
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Html\Icon
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Icon
		{
			
			/**
			 * @var Collect
			 */
			private $data;
			
			/**
			 * @var string
			 */
			private $html = '';
			
			/**
			 * @var string
			 */
			private $ul_class;
			
			/**
			 * @var string
			 */
			private $icon_class;
			
			/**
			 * @var Collect
			 */
			private $id;
			
			/**
			 * @var string
			 */
			private $li_class;
			
			/**
			 *
			 * constructor.
			 *
			 * @param  string  $ul_class
			 * @param  string  $li_class
			 * @param  string  $icon_class
			 */
			public function __construct(string $ul_class, string $li_class, string $icon_class)
			{
				
				$this->data = collect();
				$this->id = collect();
				$this->ul_class = $ul_class;
				$this->icon_class = $icon_class;
				$this->li_class = $li_class;
			}
			
			/**
			 *
			 * Add and icon
			 *
			 * @param  string  $icon
			 * @param  string  $url
			 * @param  string  $id
			 *
			 * @throws Exception
			 *
			 * @return Icon
			 *
			 */
			public function add(string $icon, string $url, string $id) : Icon
			{
				
				$this->data->put($url, $icon);
				is_true($this->id->exist($id), true, "The id $id is not unique");
				$this->id->put($icon, $id);
				
				return $this;
			}
			
			/**
			 *
			 * Generate the icon bar
			 *
			 * @return string
			 *
			 */
			public function generate() : string
			{
				
				$this->start_ul();
				foreach($this->data->all() as $k => $v)
					$this->save($v, $k, $this->id->get($v));
				$this->end_ul();
				
				return $this->html;
			}
			
			private function end_ul() : void
			{
				
				append($this->html, '</ul>');
			}
			
			private function start_ul() : void
			{
				
				append($this->html, '<ul class="' . $this->ul_class . '">');
			}
			
			private function save(string $icon, string $url, string $id) : void
			{
				
				$x = '<li class="' . $this->li_class . '"><a href="' . $url . '" class="' . $this->icon_class . '" id="' . $id . '">' . $icon . '</a></li>';
				append($this->html, $x);
			}
			
		}
	}