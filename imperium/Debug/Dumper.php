<?php
	
	namespace Imperium\Debug
	{
		
		use Symfony\Component\VarDumper\Cloner\VarCloner;
		use Symfony\Component\VarDumper\Dumper\CliDumper;
		
		/**
		 *
		 * Class Dumper
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Debug
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Dumper
		{
			
			/**
			 * Dump a value with elegance.
			 *
			 * @param  mixed  $value
			 *
			 * @return void
			 */
			public function dump($value)
			{
				
				$dumper = 'cli' === PHP_SAPI ? new CliDumper : new HtmlDumper;
				$dumper->dump((new VarCloner)->cloneVar($value));
			}
			
		}
	}
