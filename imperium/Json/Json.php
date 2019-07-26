<?php

	namespace Imperium\Json
	{

		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Imperium\Collection\Collect;

		/**
		 *
		 * Class Json
		 *
		 * @package Imperium\Json
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Json
		{

			/**
			 * @var Collect
			 */
			private $data;

			/**
			 * @var string
			 */
			private $filename;

			const VALID = [0, JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION, JSON_UNESCAPED_UNICODE];

			/**
			 *
			 * Json constructor.
			 *
			 * @param string $filename
			 * @param string $mode
			 *
			 * @throws Kedavra
			 *
			 */
			public function __construct(string $filename, string $mode = EMPTY_AND_WRITE_FILE_MODE)
			{
				$this->data = collect();

				$this->filename = new File($filename, $mode);

			}

			/**
			 *
			 * Create the json file
			 *
			 * @param array $data
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function create(array $data): bool
			{
				return $this->filename->to_json($data);
			}

			/**
			 *
			 * Append a value in the json
			 *
			 * @param mixed $value The value to add
			 * @param mixed $key   The value key
			 *
			 * @return Json
			 *
			 */
			public function add($value, $key = ''): Json
			{
				$this->data->put($key, $value);

				return $this;
			}

			/**
			 *
			 * Execute a query and store data
			 *
			 * @param Connect $connect
			 * @param string  $query
			 * @param string  $key
			 *
			 * @throws Kedavra
			 *
			 * @return Json
			 *
			 */
			public function sql(Connect $connect, string $query, string $key = ''): Json
			{
				$this->add($connect->request($query), $key);

				return $this;
			}

			/**
			 *
			 * generate the json
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function generate(): bool
			{
				return $this->create($this->data->all());
			}

			/**
			 *
			 * Encode
			 *
			 * @param int $option
			 * @param int $depth
			 *
			 * @throws Kedavra
			 *
			 * @return false|string
			 *
			 */
			public function encode(int $option = 0, int $depth = 512)
			{
				not_in(self::VALID, $option, true, "The option used is not valid");

				return json_encode($this->data->all(), $option, $depth);
			}

			/**
			 *
			 * Decode a json file or a json string
			 *
			 * @param bool $assoc
			 *
			 * @throws Kedavra
			 * @return mixed
			 *
			 */
			public function decode(bool $assoc = false)
			{
				return json_decode(utf8_encode($this->filename->read()), $assoc);
			}
		}
	}
