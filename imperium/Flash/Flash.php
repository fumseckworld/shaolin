<?php
	
	namespace Imperium\Flash
	{
		
		use Imperium\Exception\Kedavra;
		use Imperium\Session\SessionInterface;
		
		/**
		 *
		 * Class Flash
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Flash
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Flash
		{
			
			/**
			 *
			 * The success message flash key
			 *
			 * @var string
			 *
			 */
			const SUCCESS_KEY = 'success';
			
			/**
			 *
			 * The failure message flash key
			 *
			 * @var string
			 *
			 */
			const FAILURE_KEY = 'failure';
			
			/**
			 *
			 * All valid keys
			 *
			 * @var array
			 *
			 */
			const VALID = [ self::SUCCESS_KEY, self::FAILURE_KEY ];
			
			/**
			 * @var SessionInterface
			 */
			private $session;
			
			/**
			 *
			 * Flash constructor
			 *
			 *
			 * @param  SessionInterface  $session
			 *
			 */
			public function __construct(SessionInterface $session)
			{
				
				$this->session = $session;
			}
			
			/**
			 *
			 * Add a success message
			 *
			 * @method success
			 *
			 * @param  string  $message
			 *
			 * @return void
			 *
			 */
			public function success(string $message) : void
			{
				
				$this->session->put(self::SUCCESS_KEY, $message);
			}
			
			/**
			 *
			 * Check if a key is defined
			 *
			 * @method has
			 *
			 * @param  string  $key
			 *
			 * @return bool
			 *
			 */
			public function has(string $key) : bool
			{
				
				return $this->session->has($key);
			}
			
			/**
			 *
			 * Generate the alert
			 *
			 * @method display
			 *
			 * @param  string  $key
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function display(string $key) : string
			{
				
				$success = equal($key, self::SUCCESS_KEY);
				
				$success_class = config('flash','success_class');
				
				$failure_class = config('flash','failure_class');
				
				$column_class = config('flash','column_class');
				
				$message = $this->get($key);
				
				if(def($message))
				{
					return $success ? '<div class="row"><div class="'.$column_class.'"><div class="'.$success_class.'" role="alert"><div class="flex"><p class="font-bold">' . $message . '</p></div></div></div></div>' : '<div class="row"><div class="'.$column_class.'"><div class="'.$failure_class.'" role="alert"><div class="flex"><p class="font-bold">' . $message . '</p></div></div></div></div>';
				}
				
				return '';
			}
			
			/**
			 *
			 * Add a failure message
			 *
			 * @method failure
			 *
			 * @param  string  $message
			 *
			 * @return  void
			 *
			 */
			public function failure(string $message) : void
			{
				
				$this->session->put(self::FAILURE_KEY, $message);
			}
			
			/**
			 *
			 * Get the value
			 *
			 * @method get
			 *
			 * @param  string  $key
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function get(string $key) : string
			{
				
				not_in(self::VALID, $key, true, "The current key is not valid");
				$message = $this->session->get($key);
				$this->session->remove($key);
				
				return $message;
			}
			
		}
	}