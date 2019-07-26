<?php

	namespace Imperium\Dump
	{

		use Imperium\Connexion\Connect;
		use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Imperium\Collection\Collect;
		use function Symfony\Component\VarDumper\Dumper\esc;


		/**
		 * Class Dump
		 *
		 * @package Imperium\Dump
		 *
		 */
		class Dump
		{
			/**
			 *
			 * Connection to the base
			 *
			 * @var Connect
			 *
			 */
			private $connexion;

			/**
			 *
			 * To dump a base
			 *
			 * @var bool
			 *
			 */
			private $base;

			/**
			 *
			 * The tables to dump
			 *
			 * @var Collect
			 *
			 */
			private $tables;

			/**
			 *
			 * The dump command
			 *
			 * @var string
			 *
			 */
			private $command;

			/**
			 *
			 * The quote to use
			 *
			 * @var string
			 *
			 */
			private $quote;


			/**
			 *
			 * Dumper constructor
			 *
			 * @method __construct
			 *
			 * @param bool  $base   The option to dump the base
			 * @param array $tables The names of the tables
			 *
			 *
			 */
			public function __construct(bool $base, array $tables)
			{
				$this->connexion = app()->connect();
				$this->base = $base;
				$this->tables = collect($tables);
				$this->command = '';
				$this->quote = $this->determine_quote();
			}

			/**
			 *
			 * Dump a table or a base
			 *
			 * @method dump
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function dump(): bool
			{
				$database = $this->connexion->base();
				$driver = $this->connexion->driver();
				$password = $this->connexion->password();
				$username = $this->connexion->user();
				$dump_path = $this->connexion->dump_path();
				$host = $this->connexion->host();


				Dir::clear($dump_path);

				$filename = equal($driver, SQLITE) ? $dump_path . DIRECTORY_SEPARATOR . collect(explode('.', collect(explode(DIRECTORY_SEPARATOR, $database))->last()))->first() . '.sql' : "$dump_path/$database.sql";

				switch ($driver)
				{
					case MYSQL:
						append($this->command, $this->quote, 'mysqldump', $this->quote, " -u$username", " -p$password");

						if ($this->base)
						{
							append($this->command, " $database > $filename");

							system($this->command);
						} else
						{
							$tables = $this->tables->join(' ');

							append($this->command, " $database", " --tables $tables", " > $filename");

							system($this->command);
						}
					break;
					case POSTGRESQL:

						append($this->command, 'pg_dump', " -U $username", " -h $host", " -d $database", ' -p 5432', ' --clean', ' --if-exists', ' --inserts', ' --no-owner');

						if ($this->base)
						{
							append($this->command, " > $filename");

							system($this->command);

							return File::exist($filename);

						} else
						{
							append($this->command, " -t");

							append($this->command, $this->tables->join(' -t '));

							append($this->command, " > $filename");

							system($this->command);
						}
					break;
					case SQLITE:
						append($this->command, "sqlite3");

						if ($this->base)
						{
							append($this->command, " $database .dump > $filename");

							system($this->command);
						} else
						{
							return  false;
						}
						break;
						default:
							return false;
						break;
				}

				return File::exist($filename);
			}

			/**
			 *
			 *
			 *
			 * @method determine_quote
			 *
			 * @return string
			 *
			 */
			private function determine_quote(): string
			{
				return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '"' : "'";
			}
		}
	}
