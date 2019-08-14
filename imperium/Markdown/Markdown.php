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
		 * @param  string  $filename
		 * @param  string  $text
		 */
        public function __construct(string $filename,string $text ='')
        {

            $this->filename = $filename;
			$this->text = $text;
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