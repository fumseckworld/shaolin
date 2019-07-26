<?php


	namespace Imperium\Html\Pagination
	{

		/**
		 * Class Pagination
		 *
		 * @package Imperium\Html\Pagination
		 *
		 */
		class Pagination implements PaginationManagement
		{
			/**
			 * current page
			 *
			 * @var int
			 */
			private static $current;

			/**
			 * the per page number
			 *
			 * @var int
			 */
			private static $perPage;

			/**
			 * string the get-parameter for the pager
			 *
			 * @var string
			 */
			private static $instance;

			/**
			 * @var int
			 */
			private static $rows = 0;

			/**
			 * @var string
			 */
			private static $ulClass;

			/**
			 * @var string
			 */
			private static $liCssClass;

			/**
			 * @var string
			 */
			private static $endCssClass;

			/**
			 * @var string
			 */
			private static $startText;

			/**
			 * @var string
			 */
			private static $endText;

			/**
			 * @var int
			 */
			private static $type;

			/**
			 * @var int
			 */
			private static $adjacent = 2;

			/**
			 * @var bool
			 */
			private static $withLinkInCurrentLi = false;

			/**
			 * start pagination
			 *
			 * @param int    $perPage
			 * @param string $instance
			 *
			 * @return Pagination
			 */
			public static function paginate(int $perPage, string $instance): Pagination
			{
				self::$instance = $instance;
				self::$perPage = $perPage;
				self::setInstance();
				return new static();
			}

			/**
			 * set the object parameter
			 */
			private static function setInstance()
			{
				$instance = get(self::$instance);
				if (isset($instance))
				{
					self::$current = get(self::$instance);
				}

				if (!self::$current)
				{
					self::$current = 1;
				}

			}


			/**
			 * @param string $path
			 * @param int    $counter
			 *
			 * @return string
			 */
			private static function createLiCurrentOrNot(string $path, int $counter): string
			{
				// init
				$html = '';

				$textAndOrLink = '<a href="' . $path . self::$instance . '' . $counter . '" class="page-link">' . $counter . '</a>';

				if (self::$withLinkInCurrentLi === false)
					$currentTextAndOrLink = $counter; else
					$currentTextAndOrLink = $textAndOrLink;

				if ($counter == self::$current)
					$html .= '<li class="page-item active"><a href="#" class="page-link">' . $currentTextAndOrLink . ' </a></li>'; else
					$html .= '<li class="page-item">' . $textAndOrLink . '</li>';

				return $html;
			}

			/**
			 * @param string $path
			 * @param int    $counter
			 *
			 * @return string|array
			 */
			private static function createLiCurrentOrNotRaw($path, $counter)
			{
				$textAndOrLink = $path . self::$instance . '' . $counter;

				if (self::$withLinkInCurrentLi === false)
					$currentTextAndOrLink = $counter; else
					$currentTextAndOrLink = $textAndOrLink;

				if ($counter == self::$current)
					return [$currentTextAndOrLink => true]; else
					return [$textAndOrLink => false];

			}


			/**
			 * @param string $path
			 *
			 * @return string
			 */
			private static function createLiFirstAndSecond(string $path): string
			{
				$bool = def(strstr(self::$instance, '='));
				return $bool ? '<li class="' . self::$liCssClass . '"> <a href="' . $path . self::$instance . '1" class="page-link"> 1 </a></li><li class="' . self::$liCssClass . '"> <a href="' . $path . self::$instance . '2" class="page-link"> 2</a></li>' : '<li class="' . self::$liCssClass . '"> <a href="' . $path . self::$instance . '/1" class="page-link"> 1 </a></li><li class="' . self::$liCssClass . '"> <a href="' . $path . self::$instance . '/2" class="page-link"> 2</a></li>';
			}

			/**
			 * @param string $path
			 * @param array  $pagination
			 *
			 * @return void
			 */
			private static function createLiFirstAndSecondRaw($path, array &$pagination)
			{
				$pagination[] = [$path . self::$instance . '/1' => false];
				$pagination[] = [$path . self::$instance . '/2' => false];
			}

			/**
			 * get next and prev meta-links
			 *
			 * @param string $path
			 *
			 * @return string
			 */
			public function getNextPrevLinks(string $path = '?'): string
			{

				$nextLink = '';
				$prevLink = '';
				$prev = self::$current - 1;
				$next = self::$current + 1;
				$last = (int)ceil(self::$rows / self::$perPage);
				if ($last > 1)
				{
					if (self::$current > 1)
					{

						$prevLink = '<li class="' . self::$liCssClass . '"> <a  href="' . $path . self::$instance . '' . $prev . '" class="page-link"></li>';

					}

					if (self::$current < $last)
					{
						$prevLink = '<li class="' . self::$liCssClass . '"> <a  href="' . $path . self::$instance . '' . $next . '" class="page-link"></li>';
					}


				}

				return "$nextLink $prevLink";
			}

			/**
			 * returns the limit for the data source
			 *
			 * @return string LIMIT-String for an SQL-query
			 */
			public function getLimit(): string
			{
				return ' LIMIT ' . $this->getStart() . ',' . self::$perPage;
			}

			/**
			 * create the starting point for getLimit()
			 *
			 * @return int
			 */
			public function getStart(): int
			{
				return (self::$current * self::$perPage) - self::$perPage;
			}

			/**
			 * create pagination
			 *
			 * @param string $path
			 *
			 * @return string
			 */
			public function get(string $path = '?'): string
			{
				$pagination = '';

				// init
				$counter = 0;

				$prev = self::$current - 1;
				$next = self::$current + 1;
				$last = ceil(self::$rows / self::$perPage);
				$tmpSave = $last - 1;

				if (self::$current < $last)
					$nextDataAttribute = $next; else
					$nextDataAttribute = 'false';

				if (self::$current > 1)
					$prevDataAttribute = $prev; else
					$prevDataAttribute = 'false';
				if ($last > 1)
				{
					$pagination .= '<ul class="' . self::$ulClass . '" data-pagination-current="' . self::$current . '" data-pagination-prev="' . $prevDataAttribute . '" data-pagination-next="' . $nextDataAttribute . '" data-pagination-length="' . $last . '">';
					if (self::$current > 1)
						$pagination .= '<li class="' . self::$liCssClass . '"><a href="' . $path . self::$instance . '' . $prev . '" class="page-link"> ' . self::$startText . '</a></li>'; else
						$pagination .= '<li class="disabled ' . self::$liCssClass . '"> <a class="page-link"> ' . self::$startText . '</a> </li>';

					if ($last < 7 + (self::$adjacent * 2))
					{
						for ($counter = 1; $counter <= $last; $counter++)
						{
							$pagination .= $this->createLiCurrentOrNot($path, $counter);
						}

					} elseif (self::$current < 5 && ($last > 5 + (self::$adjacent * 2)))
					{
						if (self::$current < 1 + (self::$adjacent * 2))
						{
							for ($counter = 1; $counter < 4 + (self::$adjacent * 2); $counter++)
							{
								$pagination .= $this->createLiCurrentOrNot($path, $counter);
							}
						}

						$pagination .= '<li class="' . self::$liCssClass . '"><a href="' . $path . self::$instance . '' . $tmpSave . '" class="page-link">   ' . $tmpSave . '</a></li>';
						$pagination .= '<li><a href="' . $path . self::$instance . '' . $last . '" class="page-link">   ' . $last . ' </a></li>';
					} elseif ($last - (self::$adjacent * 2) > self::$current && self::$current > (self::$adjacent * 2))
					{
						$pagination .= $this->createLiFirstAndSecond($path);
						for ($counter = self::$current - self::$adjacent; $counter <= self::$current + self::$adjacent; $counter++)
						{
							$pagination .= $this->createLiCurrentOrNot($path, $counter);
						}

						$pagination .= '<li><a href="' . $path . self::$instance . '' . $tmpSave . '" class="page-link">   ' . $tmpSave . '  </a></li>';
						$pagination .= '<li><a href="' . $path . self::$instance . '' . $last . '" class="page-link"> ' . $last . ' </a></li>';
					} else
					{
						$pagination .= $this->createLiFirstAndSecond($path);

						for ($counter = $last - (2 + (self::$adjacent * 2)); $counter <= $last; $counter++)
						{
							$pagination .= $this->createLiCurrentOrNot($path, $counter);
						}
					}
					if (self::$current < $counter - 1)
					{
						$pagination .= '<li class="' . self::$endCssClass . '"><a href="' . $path . self::$instance . '' . $next . '" class="page-link"> ' . self::$endText . ' </a></li>';
					} else
					{
						$pagination .= '<li class="disabled ' . self::$endCssClass . '" >   <a class="page-link">' . self::$endText . '</a> </li>';
					}
					$pagination .= '</ul>';
				}
				return $pagination;
			}

			/**
			 * create links as array
			 *
			 * @param string $path
			 *
			 * @return array
			 */
			public function linksRaw(string $path = '?'): array
			{
				$counter = 0;
				$pagination = [];
				$prev = self::$current - 1;
				$next = self::$current + 1;
				$last = ceil(self::$rows / self::$perPage);
				$tmpSave = $last - 1;

				if ($last > 1)
				{
					if (self::$current > 1)
					{
						$pagination[] = [$path . self::$instance . '' . $prev => false];
					}
					if ($last < 7 + (self::$adjacent * 2))
					{
						for ($counter = 1; $counter <= $last; $counter++)
						{
							$pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
						}
					} elseif (self::$current < 5 && ($last > 5 + (self::$adjacent * 2)))
					{
						if (self::$current < 1 + (self::$adjacent * 2))
						{
							for ($counter = 1; $counter < 4 + (self::$adjacent * 2); $counter++)
							{
								$pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
							}
						}
						$pagination[] = ['' => false];
						$pagination[] = [$path . self::$instance . '' . $tmpSave => false];
						$pagination[] = [$path . self::$instance . '' . $last => false];
					} elseif ($last - (self::$adjacent * 2) > self::$current && self::$current > (self::$adjacent * 2))
					{
						$this->createLiFirstAndSecondRaw($path, $pagination);
						if (self::$current != 5)
						{
							$pagination[] = ['' => false];
						}
						for ($counter = self::$current - self::$adjacent; $counter <= self::$current + self::$adjacent; $counter++)
						{
							$pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
						}
						$pagination[] = ['' => false];
						$pagination[] = [$path . self::$instance . '' . $tmpSave => false];
						$pagination[] = [$path . self::$instance . '' . $last => false];
					} else
					{
						$this->createLiFirstAndSecondRaw($path, $pagination);
						$pagination[] = ['' => false];
						for ($counter = $last - (2 + (self::$adjacent * 2)); $counter <= $last; $counter++)
						{
							$pagination[] = $this->createLiCurrentOrNot($path, $counter);
						}
					}
					if (self::$current < $counter - 1)
					{
						$pagination[] = [$path . self::$instance . '' . $next => false];
					}
				}
				return $pagination;
			}

			/**
			 * set the adjacent
			 *
			 * @param int $adjacent
			 *
			 * @return Pagination
			 */
			public function setAdjacent(int $adjacent): Pagination
			{
				self::$adjacent = $adjacent;
				return $this;
			}

			/**
			 * set current page
			 *
			 * @param int $current
			 *
			 * @return Pagination
			 */
			public function setCurrent(int $current): Pagination
			{
				self::$current = $current;
				return $this;
			}

			/**
			 * define the end char
			 *
			 * @param string $endChar
			 *
			 * @return Pagination
			 */
			public function setEndChar(string $endChar): Pagination
			{
				self::$endText = $endChar;

				return $this;
			}

			/**
			 * define the char char
			 *
			 * @param string $startChar
			 *
			 * @return Pagination
			 */
			public function setStartChar(string $startChar): Pagination
			{
				self::$startText = $startChar;

				return $this;
			}

			/**
			 * define the end css class
			 *
			 * @param string $endClass
			 *
			 * @return Pagination
			 */
			public function setEndCssClass(string $endClass): Pagination
			{
				self::$endCssClass = $endClass;

				return $this;
			}

			/**
			 * define start css class
			 *
			 * @param string $startClass
			 *
			 * @return Pagination
			 */
			public function setLiCssClass(string $startClass): Pagination
			{
				self::$liCssClass = $startClass;

				return $this;
			}

			/**
			 * define ul pagination class
			 *
			 * @param string $ulClass
			 *
			 * @return Pagination
			 */
			public function setUlCssClass(string $ulClass): Pagination
			{
				self::$ulClass = $ulClass;
				return $this;
			}

			/**
			 * define total of records
			 *
			 * @param int $total
			 *
			 * @return Pagination
			 */
			public function setTotal(int $total): Pagination
			{
				self::$rows = $total;

				return $this;
			}

			/**
			 * @param bool $bool
			 *
			 * @return Pagination
			 */
			public function setWithLinkInCurrentLi(bool $bool): Pagination
			{
				self::$withLinkInCurrentLi = $bool;

				return $this;
			}

			/**
			 * set pagination type
			 *
			 * @param int $type
			 *
			 * @return Pagination
			 */
			public function setType(int $type): Pagination
			{
				self::$type = $type;

				return $this;
			}
		}
	}