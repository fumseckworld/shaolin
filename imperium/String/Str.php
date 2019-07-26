<?php


	namespace Imperium\String
	{

		use Stringy\Stringy;

		/***
		 *
		 * Class Str
		 *
		 * @package Imperium\String
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Str
		{
			/**
			 *
			 * @var Stringy
			 *
			 */
			private $string;

			/**
			 * Str constructor.
			 *
			 * @param string $data
			 */
			public function __construct(string $data)
			{
				$this->string = Stringy::create($data);
			}
		}
	}