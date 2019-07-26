<?php


	namespace Imperium\Validator
	{


		use Exception;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		use Symfony\Component\HttpFoundation\Request;

		/**
		 * Class Validator
		 *
		 * @author Willy Micieli
		 *
		 * @package Imperium\Validator
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Validator
		{

			/**
			 * @var Request
			 */
			private $request;

			public function __construct(Request $request)
			{
				$this->request = $request;
			}

			/**
			 *
			 * Check if an email is valid
			 *
			 * @param string $key
			 *
			 * @throws Exception
			 *
			 * @return Validator|RedirectResponse
			 *
			 */
			public function email(string $key)
			{
				$x = $this->data($key);

				return not_def($x) || !filter_var($x, FILTER_VALIDATE_EMAIL) ? $this->back("The %s email is not valid", $x) : $this;
			}

			/**
			 *
			 * Check if values are defined
			 *
			 * @param string ...$args
			 *
			 * @throws Exception
			 * @return Validator|RedirectResponse
			 *
			 */
			public function define(string ...$args)
			{
				foreach ($args as $arg)
				{
					if (not_def($arg))
						return $this->back("The %s argument is missing", $arg);
				}
				return $this;
			}

			/**
			 * @param string $msg
			 * @param mixed  ...$args
			 *
			 * @throws Exception
			 * @return string
			 */
			private function message(string $msg, ... $args): string
			{
				return trans($msg, $args);
			}

			/**
			 * @param $key
			 *
			 * @return mixed|string
			 */
			private function data($key)
			{
				return $this->request->request->has($key) ? $this->request->request->get($key) : '';
			}

			/**
			 * @param string $message
			 * @param mixed  ...$args
			 *
			 * @throws Exception
			 * @return RedirectResponse
			 */
			private function back(string $message, ...$args): RedirectResponse
			{
				return back($this->message($message, $args), false);
			}
		}
	}