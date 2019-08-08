<?php

	namespace Imperium\Session
	{


		use Imperium\Collection\Collect;

		/**
		 * Class ArraySession
		 *
		 * @package Imperium\Session
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class ArraySession implements SessionInterface
		{
			/**
			 * @var Collect
			 */
			private $session;

			/**
			 * Get a session key
			 *
			 * @param $key
			 *
			 * @return mixed
			 *
			 */
			public function get($key)
			{
				return $this->session->get($key);
			}

			/**
			 *
			 * Check if the session has a key
			 *
			 * @param $key
			 *
			 * @return bool
			 *
			 */
			public function has($key): bool
			{
				return $this->session->has($key);
			}

			/**
			 *
			 * Define a value
			 *
			 * @param $key
			 * @param $value
			 *
			 * @return  void
			 *
			 */
			public function put($key, $value): void
			{
				$this->session->put($key, $value);
			}

			/**
			 *
			 * Remove a key
			 *
			 * @param array $keys
			 *
			 * @return bool
			 */
			public function remove(...$keys): bool
			{
				$data = collect();
				foreach ($keys as $key)
					$data->push($this->session->del($key)->ok());

				return $data->ok();
			}

			/**
			 *
			 * Get all value
			 *
			 * @return array
			 *
			 */
			public function all(): array
			{
				return $this->session->all();
			}

			/**
			 * SessionInterface constructor.
			 */
			public function __construct()
			{
				$this->session = collect();
			}

			/**
			 *
			 * Set and return value
			 *
			 * @param $key
			 * @param $value
			 *
			 * @return mixed
			 *
			 */
			public function def($key, $value)
			{
			    $this->put($key, $value);
			    return $this->get($key);
			}

			/**
			 *
			 * Clear the session
			 *
			 * @return bool
			 *
			 */
			public function clear(): bool
			{
				foreach ($this->all() as $k => $v)
					$this->remove($k);

				return not_def($this->all());
			}
		}
	}