<?php
	
	namespace Imperium\File
	{
		
		use Imperium\Exception\Kedavra;
		use Symfony\Component\HttpFoundation\Response;
		
		/**
		 *
		 * Class Download
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\File
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Download
		{
			
			/**
			 * @var File
			 */
			private $filename;
			
			/**
			 *
			 * Download constructor.
			 *
			 * @param  string  $filename
			 *
			 * @throws Kedavra
			 */
			public function __construct(string $filename)
			{
				
				$this->filename = new File($filename);
			}
			
			/**
			 *
			 * Download the file
			 *
			 * @method download
			 *
			 * @throws Kedavra
			 *
			 * @return Response
			 *
			 */
			public function download() : Response
			{
				
				return $this->filename->download();
			}
			
		}
	}