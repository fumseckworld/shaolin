<?php

	namespace Imperium
	{

        use Imperium\Asset\Asset;
        use Imperium\Cache\Cache;
        use Imperium\Collection\Collect;
        use Imperium\Connexion\Connect;
        use Imperium\Exception\Kedavra;
        use Imperium\File\File;
        use Imperium\Flash\Flash;
        use Imperium\Html\Form\Form;
        use Imperium\Query\Query;
        use Imperium\Request\Request;
        use Imperium\Routing\Router;
        use Imperium\Session\SessionInterface;
        use Imperium\Tables\Table;
        use Imperium\Security\Auth\Oauth;
        use Imperium\Writing\Write;
        use Psr\Http\Message\ServerRequestInterface;
        use Symfony\Component\HttpFoundation\RedirectResponse;
        use Symfony\Component\HttpFoundation\Response;


        /**
		 * Interface Management
		 *
		 * @author Willy Micieli
		 *
		 * @package Imperium
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		interface Management
		{

			/**
			 *
			 * Get a config value
			 *
			 * @param string $file
			 * @param  mixed $key
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function config(string $file, $key);


			/**
			 *
			 * File management
			 *
			 * @param string $filename
			 * @param string $mode
			 *
			 * @throws Kedavra
			 *
			 * @return File
			 *
			 */
			public function file(string $filename, string $mode = READ_FILE_MODE): File;


			/**
			 *
			 * Check if a table exist
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function exist(string $table): bool;


			/**
			 *
			 * Management of array
			 *
			 * @param mixed $data
			 *
			 * @return Collect
			 *
			 */
			public function collect(array $data = []): Collect;

            /**
             *
             * Display all tables
             *
             * @throws Kedavra
             *
             * @return array
             *
             */
            public function tables(): array;

			/**
			 *
			 * Remove a table
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function remove(string $table): bool;

			/**
			 *
			 * Empty all records in a table
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function truncate(string $table): bool;



			/**
			 * @return Form
			 */
			public function form(): Form;

            /**
             *
             * Get an instance of query
             *
             * @param string $table
             *
             * @return Query
             *
             */
			public function query(string $table): Query;


			/**
			 *
             * Get an instance of table
             *
             * @return Table
             *
			 */
			public function table(): Table;

			/**
			 *
             * Get an instance of database
			 *
             * @return Connect
             *
			 */
			public function connect(): Connect;

			/**
			 *
             * Get an instance of flash message
             *
             * @return Flash
             *
			 */
			public function flash(): Flash;
			

			/**
             * Get an instance of session
             *
			 * @return SessionInterface
             *
			 */
			public function session(): SessionInterface;

            /**
             *
             * Get an instance of request
             *
             * @return \Symfony\Component\HttpFoundation\Request
             */
			public function request(): \Symfony\Component\HttpFoundation\Request;


			/**
             *
             * Get an instance of auth class
             *
			 * @return Oauth
             *
			 */
			public function auth(): Oauth;

			/**
			 *
			 * @param ServerRequestInterface $serverRequest
			 *
			 * @return Router
			 *
			 */
			public function router(ServerRequestInterface $serverRequest): Router;

            /**
             *
             * Run application
             *
             * @return Response
             *
             */
			public function run(): Response;

			/**
             *
             * Get an instance of assets
             *
			 * @param string $filename
			 *
			 * @return Asset
			 *
			 */
			public function assets(string $filename): Asset;

            /**
             *
             * Get the app locale
             *
             * @return string
             *
             */
			public function lang(): string;


			/**
			 *
             * Get an instance of write to send mail
             *
			 * @param string $subject
			 * @param string $message
			 * @param string $author_email
			 * @param string $to
			 *
			 * @throws Kedavra
			 *
			 * @return Write
			 *
			 */
			public function write(string $subject, string $message, string $author_email, string $to): Write;


			/**
			 *
			 * Return a view
			 *
			 * @param string $name
			 * @param array  $args
			 *
			 * @return Response
			 *
			 */
			public function view(string $name, array $args = []): Response;


			/**
			 *
			 * Redirect user to a route
			 *
			 * @param string $route
			 * @param string $message
			 * @param bool   $success
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function redirect(string $route, string $message = '', bool $success = true): RedirectResponse;

			/**
			 *
			 * Redirect user back
			 *
			 * @param string $message
			 * @param bool   $success
			 *
			 * @return RedirectResponse
			 *
			 */
			public function back(string $message = '', bool $success = true): RedirectResponse;


			/**
			 *
			 * Redirect user to an url
			 *
			 * @param string $url
			 * @param string $message
			 * @param bool   $success
			 *
			 * @return RedirectResponse
			 *
			 */
			public function to(string $url, string $message = '', bool $success = true): RedirectResponse;


			/**
			 *
			 * Get the debug bar
			 *
			 * @return string
			 *
			 */
			public function debug_bar(): string;

			/**
			 *
			 * @param string $content
			 * @param int    $status
			 * @param array  $headers
			 *
			 * @return Response
             *
			 */
			public function response(string $content, int $status = 200, array $headers = []): Response;


			/**
			 *
			 * Get cache instance
			 *
			 * @return Cache
			 *
			 */
			public function cache(): Cache;


			/**
			 *
			 * Download a file
			 *
			 * @param string $filename
			 *
			 * @return Response
			 *
			 */
			public function download(string $filename): Response;

			/**
			 *
			 * Generate url string
			 *
			 * @param string $route
			 * @param mixed  $args
			 *
			 * @return string
			 */
			public function url(string $route, ...$args): string;
		}
	}
