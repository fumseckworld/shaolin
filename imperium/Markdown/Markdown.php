<?php


namespace Imperium\Markdown {


    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Parsedown;

	/**
	 *
	 * Class Markdown
	 *
	 * @package Imperium\Markdown
	 *
	 * @author Willy Micieli
	 *
	 * @license GPL
	 *
	 * @version 10
	 *
	 */
    class Markdown
    {
        /**
         * @var string
         */
        private $text;
        /**
         * @var string
         */
        private $filename;

        /**
         * Markdown constructor.
         *
         * @param  $text
         * @param string $filename
         *
         */
        public function __construct(string $text, string $filename = "")
        {
            $this->text = $text;


            $this->filename = $filename;
        }

        /**
         * @return string
         * @throws Kedavra
         */
        public function markdown(): string
        {
            return def($this->filename) && file_exists($this->filename) ? (new File($this->filename))->markdown() : (new Parsedown())->text($this->text);
        }
    }
}