<?php


namespace Imperium\String {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Collection\Collect;
    use Imperium\Encrypt\Crypt;
    use Imperium\Exception\Kedavra;
    use Imperium\Markdown\Markdown;

    /**
     *
     * Class Text
     *
     * @package Imperium\String
     *
     *
     */
    class Text
    {
        /**
         *
         * @var string
         */
        private $text;

        /**
         * Text constructor.
         *
         * @param string $text
         *
         */
        public function __construct(string $text)
        {
            $this->text = $text;
        }

        /**
         *
         * Convert string to markdown
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function markdown(): string
        {
            return (new Markdown('',$this->text))->markdown();
        }

        /**
         *
         *
         * Remove space before and after
         *
         * @return Text
         *
         */
        public function clean(): Text
        {
            return $this->trim()->rtrim();
        }

        /**
         *
         * Take a part of the sting
         *
         * @param int $limit
         * @param int $offset
         * @return Text
         */
        public function take(int $limit,$offset = 0): Text
        {
            $this->text = substr($this->text,$offset,$limit);

            return $this;
        }

        /**
         *
         * Strip whitespace (or other characters) from the end of a string.
         *
         * @param string $chars
         *
         * @return Text
         *
         */
        public function rtrim(string $chars =" \t\n\r\0\x0B"): Text
        {
            $this->text = rtrim($this->text,$chars);

            return $this;
        }

        /**
         * @param string $chars
         * @return Text
         */
        public function trim(string $chars = " \t\n\r\0\x0B"): Text
        {
            $this->text = trim($this->text,$chars);

            return $this;
        }

        /**
         *
         * Find the first occurrence of a string
         *
         * @param string $search
         * @param bool|null $before
         *
         * @return Text
         */
        public function search(string $search,bool $before = null): Text
        {
            $this->text =  strstr($this->text,$search,$before);

            return  $this;
        }

        /**
         *
         * Check if the text is equal
         *
         * @param string $expected
         *
         * @return bool
         *
         */
        public function equal(string $expected): bool
        {
            return strcmp($this->text,$expected) === 0;
        }

        /**
         *
         * Check if the text is equal to required
         *
         * @param string $expected
         *
         * @return bool
         */
        public function different(string $expected): bool
        {
            return strcmp($this->text,$expected) !== 0;
        }

        /**
         *
         * Get the somme of chars
         *
         * @return int
         *
         */
        public function length(): int
        {
            return strlen($this->text);
        }

        /**
         *
         * Add br to line
         *
         * @return Text
         *
         */
        public function nl2br(): Text
        {
            $this->text = nl2br($this->text);

            return $this;
        }

        /**
         *
         * Put the text to uppercase
         *
         * @return Text
         *
         */
        public function upper(): Text
        {
            $this->text = strtoupper($this->text);

            return $this;
        }

        /**
         *
         * Slit the text
         *
         * @param int $length
         * @param string $end
         *
         *
         * @return Text
         *
         */
        public function chunk(int $length = 76,string $end = "\r\n"): Text
        {
            $this->text = chunk_split($this->text,$length,$end);

            return $this;
        }

        /**
         *
         * Split string by a regular expression
         *
         * @param string $pattern
         * @param int $limit
         * @param int $flags
         *
         * @return Collect
         *
         */
        public function split(string $pattern,int $limit = -1,int $flags = 0): Collect
        {
            $x = preg_split ($pattern,$this->text,$limit, $flags);

            return  is_array($x) ? collect($x) : collect();
        }


        /**
         *
         *
         * @param string $pattern
         * @param int $offset
         *
         * @return Collect
         *
         */
        public function match(string $pattern,int $offset = 0): Collect
        {
            $matches = [];

            $x = preg_match($pattern,$this->text,$matches,PREG_OFFSET_CAPTURE,$offset);

            return $x === 1 ? collect($matches) : collect();
        }

        /**
         *
         * Wraps a string to a given number of characters
         *
         * @param int $width
         * @param string $break
         * @param bool $cut
         *
         * @return Text
         *
         */
        public function wrap(int $width ,string $break ='\n',bool $cut =false): Text
        {
            $this->text = wordwrap($this->text,$width,$break,$cut);
            return $this;
        }
        /**
         *
         * Find the position of the last occurrence of a substring in a string
         *
         * @param string $search
         * @param int $offset
         *
         * @return int
         *
         */
        public function pos(string $search,int $offset = 0):int
        {
            return  strpos($this->text,$search,$offset);
        }
        /**
         *
         * Put the first letter in uppercase
         *
         * @return Text
         *
         */
        public function uc_first(): Text
        {
            $this->text = ucfirst($this->text);
            return $this;
        }

        /**
         *
         * Put the first letter in uppercase
         *
         * @param string $delimiter
         * @return Text
         */
        public function uc_words(string $delimiter =''): Text
        {
            $this->text = def($delimiter)? ucwords($this->text,$delimiter) : ucwords($this->text);

            return $this;
        }

        /**
         *
         * Repeat the text
         *
         * @param int $x
         *
         * @return Text
         *
         */
        public function repeat(int $x): Text
        {
            $this->text = str_repeat($this->text,$x);
            return $this;
        }
        /**
         *
         * Replace a string in the text
         *
         * @param string $search
         * @param string $replace
         *
         * @return Text
         *
         */
        public function refresh(string $search,string $replace): Text
        {
            $this->text = str_replace($search,$replace,$this->text);

            return $this;
        }
        /**
         *
         * Shuffle the text
         *
         * @return Text
         *
         */
        public function shuffle(): Text
        {
            $this->text = str_shuffle($this->text);
            return $this;
        }
        /**
         *
         * Make a string lowercase
         *
         * @return Text
         *
         */
        public function lower(): Text
        {
            $this->text = strtolower($this->text);

            return $this;
        }

        /**
         *
         * Check if the string start with x4
         *
         * @param string $x
         *
         * @return bool
         *
         */
        public function start(string $x): bool
        {
            return strpos($this->text,$x) === 0;
        }

        /**
         *
         * Encrypt the text
         *
         * @return Text
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         */
        public function encrypt(): Text
        {
            $this->text =   (new Crypt())->encrypt($this->text);

            return $this;
        }

        /**
         *
         * Decrypt the text
         *
         * @return Text
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function decrypt(): Text
        {
            $this->text = (new Crypt())->decrypt($this->text);
            return  $this;
        }

        /**
         *
         * Quote regular expression characters
         *
         * @param string $delimiter
         *
         * @return Text
         *
         */
        public function quote(string $delimiter = ''): Text
        {
            $this->text= def($delimiter) ? preg_quote($this->text,$delimiter) : preg_quote($this->text);

            return $this;
        }

        /**
         *
         * Get the number of words inside the string
         *
         * @param string $chars_list
         *
         * @return int
         */
        public function words(string $chars_list = ''): int
        {
            return def($chars_list) ? str_word_count($this->text,$chars_list) : str_word_count($this->text);
        }

        /**
         *
         * Convert binary data into hexadecimal representation
         *
         * @return Text
         *
         */
        public function hash(): Text
        {
            $this->text = bin2hex($this->text);
            return  $this;
        }

        /**
         *
         * Decodes a hexadecimally encoded binary string
         *
         * @return Text
         *
         */
        public function decode(): Text
        {
            $this->text =  hex2bin($this->text);
            return  $this;
        }


        /**
         *
         * Make a string's first character lowercase
         *
         * @return Text
         *
         */
        public function lc_first(): Text
        {
            $this->text = lcfirst($this->text);

            return $this;
        }


        /**
         *
         * Split a string by a string
         *
         * @param string $delimiter
         *
         * @return Collect
         *
         */
        public function explode(string $delimiter): Collect
        {
            return  collect(explode($delimiter,$this->text));
        }
        /**
         *
         * Check if the text is found
         *
         * @param string $text
         *
         * @return bool
         *
         */
        public function contains(string $text): bool
        {
            return strstr($this->text,$text) !== false;
        }

        /**
         *
         * Quote string with slashes in a C style
         *
         * @return Text
         *é
         */
        public function add_slash(): Text
        {
            $this->text = addslashes($this->text);

            return $this;
        }

        /**
         *
         * Get the string content modified
         *
         * @return string
         *
         */
        public function get(): string
        {
            return $this->text;
        }
    }
}