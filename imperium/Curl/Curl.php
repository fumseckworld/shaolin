<?php
	
	
	namespace Imperium\Curl;
	
	
	class Curl
	{
		
		/**
		 * @var false|resource
		 */
		private $curl;
		
		public function __construct()
		{
			$this->curl = curl_init();
		}
		
		/**
		 * 
		 * Initialise connexion
		 * 
		 * @return Curl
		 * 
		 */
		public static function init(): Curl
		{
			return new static();
		}

		/**
		 *
		 * Set curl url
		 *
		 * @param string $url
		 *
		 * @return Curl
		 *
		 */
		public function url(string $url): Curl
		{
			curl_setopt($this->curl, CURLOPT_URL, $url);
			
			return $this;
		}
		
		
		/**
		 *
		 * Add to curl arguments
		 *
		 * @param array $args
		 *
		 * @return Curl
		 *
		 */
		public function args(array $args): Curl
		{
			curl_setopt_array($this->curl, $args);
			
			return $this;
		}
		
		/**
		 *
		 * Execute session
		 *
		 * @return bool|string
		 *
		 */
		public function run()
		{
			$x = curl_exec($this->curl);
			$this->close();
			return $x;
		}
		
		/**
		 *
		 * Close the curl session
		 *
		 */
		public function close(): void
		{
			curl_close($this->curl);
		}
	}