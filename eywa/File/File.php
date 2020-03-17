<?php
declare(strict_types=1);
namespace Eywa\File {

    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Parsedown;
    use SplFileObject;

    /**
     *
     * Class File
     *
     * @author  Willy Micieli
     *
     * @package Imperium\File
     *
     * @license GPL
     *
     * @version 10
     *
     */
    class File
    {

        /**
         *
         * The file instance
         *
         */
        private SplFileObject $filename;

        /**
         *
         * The open mode
         *
         */
        private string $mode;

        /**
         *
         * File constructor.
         *
         * @param string $filename
         * @param string $mode
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $filename, string $mode = READ_FILE_MODE)
        {
            not_in(FILES_OPEN_MODE, $mode, true, "The open mode is not a valid mode");

            if (!file_exists($filename)) {
                touch($filename);
            }

            $this->mode = $mode;

            $this->filename = new SplFileObject($filename, $mode);
        }

        /***
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function markdown(): string
        {
            return (new Parsedown())->text($this->read());
        }

        /**
         *
         * Check if a file exist
         *
         * @param array<string> $files
         *
         * @return bool
         *
         */
        public static function exist(array $files): bool
        {
            $data = collect();
            foreach ($files as $file) {
                file_exists($file) ? $data->put(true, $file) : $data->put(false, $file);
            }

            return $data->ok();
        }

        /**
         *
         * Create a new file
         *
         * @param string $filename
         *
         * @return bool
         *
         */
        public static function create(string $filename): bool
        {
            return touch($filename);
        }

        /**
         *
         * Search file like a pattern
         *
         * @param string $pattern
         *
         * @return array<string>
         *
         */
        public static function search(string $pattern): array
        {
            return files($pattern);
        }

        /**
         *
         * Remove a file
         *
         * @param string $filename
         *
         * @return bool
         *
         */
        public static function delete(string $filename): bool
        {
            return self::exist([$filename]) ? unlink($filename) : false;
        }

        /***
         *
         * @param array<mixed> $data
         *
         * @return bool
         *
         */
        public function to_json(array $data): bool
        {
            return file_put_contents($this->absolute_path(), json_encode($data, JSON_FORCE_OBJECT)) !== false;
        }

        /**
         *
         * Remove a file if exist
         *
         * @param string $filename
         *
         * @return bool
         *
         */
        public static function remove_if_exist(string $filename): bool
        {
            return self::exist([$filename]) ? self::delete($filename) : false;
        }

        /**
         *
         * Get all lines
         *
         * @return array<string>
         *
         */
        public function lines(): array
        {
            $data = collect();
            $this->rewind();
            while ($this->valid()) {
                $data->set($this->line());
                $this->next();
            }

            return $data->values()->all();
        }

        /**
         *
         * Check if is not the end of the file
         *
         * @return bool
         *
         */
        public function valid(): bool
        {
            return $this->instance()->valid();
        }

        /**
         *
         * Flushes the output to the file
         *
         * @return bool
         *
         */
        public function flush()
        {
            return $this->filename->fflush();
        }

        /**
         *
         * Count all lines in a file
         *
         * @return int
         *
         */
        public function count_lines(): int
        {
            return $this->to(PHP_INT_MAX)->current_line() + 1;
        }

        /**
         *
         * Reached end of file
         *
         * @return bool
         *
         */
        public function eof(): bool
        {
            return $this->instance()->eof();
        }

        /**
         *
         * Gets line from file
         *
         * @return string
         *
         */
        public function line(): string
        {
            return $this->instance()->fgets();
        }

        /**
         *
         * Gets character from file
         *
         * @return string
         *
         */
        public function char(): string
        {
            $x= $this->instance()->fgetc();

            return  $x !== false ? $x :'';
        }

        /**
         *
         * Retrieve current line of file
         *
         * @return array<mixed>|false|string
         *
         */
        public function current()
        {
            return $this->instance()->current();
        }

        /**
         *
         * Return current file position
         *
         * @return int
         *
         */
        public function tell(): int
        {
            $x = $this->filename->ftell();
            return is_bool($x) ? 1 : $x;
        }

        /**
         *
         * Gets information about the file
         *
         * @return Collect
         *
         */
        public function infos(): Collect
        {
            $x = $this->instance()->fstat();

            return is_array($x) ? collect($x) : collect();
        }

        /**
         *
         * Truncates the file to a given length
         *
         * @param int $size
         *
         * @return bool
         *
         */
        public function truncate(int $size): bool
        {
            return $this->instance()->ftruncate($size);
        }

        /**
         *
         *
         * Read the file
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function read(): string
        {
            if (superior($this->size(), 0)) {
                $x = $this->instance()->fread($this->size());
                return is_bool($x) ? '' : $x;
            }
            return '';
        }

        /**
         *
         * Gets file size
         *
         * @return int
         *
         */
        public function size(): int
        {
            return $this->instance()->getSize();
        }

        /**
         *
         * Get the current line
         *
         * @return int
         *
         */
        public function current_line(): int
        {
            return $this->instance()->key();
        }

        /**
         *
         * Write in the  file
         *
         * @param string $text
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function write(string $text): File
        {
            if ($this->writable()) {
                is_true($this->instance()->fwrite($text, sum($text)) === 0, true, "Fail to write data");
            }

            return $this;
        }

        /**
         *
         * @param string $line_content
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function write_line(string $line_content): File
        {
            return $this->write("$line_content\n");
        }

        /**
         *
         * Seek to a specified line
         *
         * @param int $line
         *
         * @return File
         *
         */
        public function to(int $line): File
        {
            $this->instance()->seek($line);

            return $this;
        }

        /**
         *
         * Rewind the file to the first line
         *
         * @return File
         *
         */
        public function rewind(): File
        {
            $this->instance()->rewind();

            return $this;
        }

        /**
         * @param int $max
         *
         * @return File
         *
         */
        public function set_max(int $max): File
        {
            $this->instance()->setMaxLineLen($max);

            return $this;
        }

        /**
         *
         * Sets flags for the SplFileObject
         *
         * @param int $flag
         *
         * @return File
         *
         */
        public function flag(int $flag): File
        {
            $this->instance()->setFlags($flag);

            return $this;
        }

        /**
         *
         * Parse the file
         *
         * @param string $format
         * @param mixed ...$args
         *
         * @return mixed
         *
         */
        public function parse(string $format, ...$args)
        {
            return $this->instance()->fscanf($format, $args);
        }

        /**
         *
         * Check if the filename is a dir
         *
         * @return bool
         *
         */
        public function is_dir(): bool
        {
            return $this->instance()->isDir();
        }

        /**
         *
         * Verify if it's a file
         *
         * @return bool
         *
         */
        public function is_file(): bool
        {
            return $this->instance()->isFile();
        }

        /**
         *
         * Check if the file is writable
         *
         * @return bool
         *
         */
        public function writable(): bool
        {
            return $this->instance()->isWritable();
        }

        /**
         *
         * Check if the file is readable
         *
         * @return bool
         *
         */
        public function readable(): bool
        {
            return $this->instance()->isReadable();
        }

        /**
         *
         * Check if the filename is a dir
         *
         * @return bool
         *
         */
        public function executable(): bool
        {
            return $this->instance()->isExecutable();
        }

        /**
         *
         * Get the file type
         *
         * @return string
         *
         */
        public function type(): string
        {
            return $this->instance()->getType();
        }

        /**
         *
         * Get the file perms
         *
         * @return int
         *
         */
        public function perms(): int
        {
            return $this->instance()->getPerms();
        }

        /**
         *
         * Get the filename
         *
         * @return string
         *
         */
        public function name(): string
        {
            return $this->instance()->getFilename();
        }

        /**
         *
         * Get the base filename
         *
         * @param string $suffix
         *
         * @return string
         *
         */
        public function base_name(string $suffix = ''): string
        {
            return $this->instance()->getBasename($suffix);
        }

        /**
         *
         * Get the filename extension
         *
         * @return string
         *
         */
        public function ext(): string
        {
            return $this->instance()->getExtension();
        }

        /**
         *
         * Gets the path to the file
         *
         * @return string
         *
         */
        public function path()
        {
            return $this->instance()->getPathname();
        }

        /**
         *
         * Gets absolute path to file
         *
         * @return string
         *
         */
        public function absolute_path():string
        {
            $x = $this->instance()->getRealPath();

            return is_bool($x) ? '' : $x;
        }

        /**
         *
         * Gets the path without filename
         *
         * @return string
         *
         */
        public function base(): string
        {
            return $this->instance()->getPath();
        }

        /**
         *
         * @return bool
         *
         */
        public function is_link(): bool
        {
            return $this->instance()->isLink();
        }

        /**
         *
         * Get the instance
         *
         * @return SplFileObject
         *
         */
        public function instance(): SplFileObject
        {
            return $this->filename;
        }

        /**
         *
         * Gets flags for the SplFileObject
         *
         * @return int
         *
         */
        public function flags(): int
        {
            return $this->instance()->getFlags();
        }

        /***
         *
         * Copy current file to dest
         *
         * @param string $dest
         *
         * @return bool
         *
         */
        public function copy(string $dest): bool
        {
            return is_dir($dest) ? copy($this->absolute_path(), "$dest" . DIRECTORY_SEPARATOR . $this->name()) : copy($this->absolute_path(), $dest);
        }

        /**
         *
         * Remove the file
         *
         * @return bool
         *
         */
        public function remove(): bool
        {
            return unlink($this->absolute_path());
        }

        /**
         *
         * Move the file to dest and remove origin
         *
         * @param string $dest
         *
         * @return bool
         *
         */
        public function move(string $dest)
        {
            return is_dir($dest)  ? copy($this->absolute_path(), "$dest" . DIRECTORY_SEPARATOR . $this->name()) && $this->remove() : copy($this->absolute_path(), $dest) && $this->remove();
        }

        /**
         *
         * Rename a file
         *
         * @param string $new_name
         *
         * @return bool
         *
         */
        public function rename(string $new_name): bool
        {
            return rename($this->name(), $new_name);
        }

        /**
         *
         * Get maximum line length
         *
         * @return int
         *
         */
        public function max(): int
        {
            return $this->instance()->getMaxLineLen();
        }

        /**
         *
         * @return File
         *
         */
        public function next(): File
        {
            if ($this->valid()) {
                $this->instance()->next();
            }

            return $this;
        }

        /**
         *
         * Get file keys
         *
         * @param string $delimiter
         *
         * @return array<mixed>
         *
         */
        public function keys(string $delimiter = ':'):array
        {
            $data = collect();

            foreach ($this->lines() as $line) {
                if (def($line)) {
                    $x = explode($delimiter, $line);
                    if (is_array($x)) {
                        $data->set(collect($x)->first());
                    }
                }
            }

            return $data->values()->all();
        }

        /**
         *
         * Get file values
         *
         * @param string $delimiter
         *
         * @return array<mixed>
         *
         */
        public function values(string $delimiter): array
        {
            $data = collect();
            foreach ($this->lines() as $line) {
                $x = explode($delimiter, $line);
                if (is_array($x)) {
                    $data->set(collect($x)->last());
                }
            }

            return $data->values()->all();
        }

        /**
         * @param array<mixed> $keys
         * @param array<mixed> $values
         * @param string $delimiter
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function change_values(array $keys, array $values, string $delimiter = ':'): bool
        {
            if (sum($keys) !==  sum($values)) {
                throw new Kedavra('The keys and values size are different');
            }
            $keys = collect($keys);
            $values = collect($values);
            foreach ($keys->all() as $k => $v) {
                $key = $keys->get($k);
                $value = $values->get($k);
                $line = is_numeric($value) || is_bool($value) ? "$key$delimiter $value" : "$key$delimiter '$value'";
                $this->write_line($line);
            }

            return $this->flush();
        }
    }
}
