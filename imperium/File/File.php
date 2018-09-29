<?php
/**
 * fumseck added File.php to imperium
 * The 09/09/17 at 13:26
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\File {

    use Exception;
    use Mimey\MimeTypes;
    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;

    /**
     * Class File
     */
    class File
    {

        /**
         * for search all html files
         */
        const HTML = 'html';

        /**
         * for search all css files
         */
        const CSS = 'css';

        /**
         * for search all php files
         */
        const PHP = 'php';

        /**
         * for search all js files
         */
        const JS = 'js';

        /**
         * for search all jpeg files
         */
        const JPEG = 'jpeg';

        /**
         * for search all jpg files
         */
        const JPG = 'jpg';

        /**
         * for search all svg files
         */
        const SVG = 'svg';

        /**
         * for search all png files
         */
        const PNG = 'png';

        /**
         * for search all json files
         */
        const JSON = 'json';

        /**
         * for search all xml files
         */
        const XML = 'xml';

        /**
         * for search all pdf files
         */
        const PDF = 'pdf';

        /**
         * for search all pdf files
         */
        const GIF = 'gif';

        /**
         *
         */
        const IMG =  array(
            File::PNG,
            File::JPEG,
            File::JPG,
            File::SVG,
            File::GIF
        );

        const MIME_TYPES = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        /**
         * Open for reading only;
         * place the files pointer at the beginning of the files.
         */
        const READ = 'r';

        /**
         * Open for reading and writing;
         * place the files pointer at the beginning of the files.
         */
        const READ_AND_WRITE = 'r+';

        /**
         * Open for writing only;
         * place the files pointer at the beginning of the files and truncate the files
         * to zero length. If the files does not exist, attempt to create it.
         */
        const EMPTY_AND_WRITE = 'w';

        /**
         * Open for reading and writing;
         * place the files pointer at the beginning of the files and truncate the files
         * to zero length. If the files does not exist, attempt to create it.
         */
        const EMPTY_READ_AND_WRITE = 'w+';

        /**
         * Open for writing only;
         * place the files pointer at the end of the files.
         * If the files does not exist, attempt to create it.
         * In this mode, fseek() has no effect, writes are always appended.
         */
        const END_WRITE = 'a';

        /**
         * Open for reading and writing;
         * place the files pointer at the end of the files.
         * If the files does not exist, attempt to create it.
         * In this mode, fseek() only affects the reading position, writes are always appended.
         */
        const END_READ_AND_WRITE = 'a+';

        /**
         * Create and open for writing only;
         * place the files pointer at the beginning of the files.
         * If the files already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING.
         * If the files does not exist, attempt to create it.
         * This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
         */
        const CREATE_ON_WRITE = 'x';

        /**
         * 	Create and open for reading and writing;
         *  otherwise it has the same behavior as 'x'.
         */
        const CREATE_ON_READ_AND_WRITE = 'x+';

        /**
         * 	Open the files for writing only.
         *  If the files does not exist, it is created.
         *  If it exists, it is neither truncated (as opposed to 'w'),
         *  nor the call to this function fails (as is the case with 'x').
         *  The files pointer is positioned on the beginning of the files.
         *  This may be useful if it's desired to get an advisory lock (see flock()) before attempting to modify the files,
         *  as using 'w' could truncate the files before the lock was obtained (if truncation is desired, ftruncate()
         *  can be used after the lock is requested).
         */
        const CREATE_WITHOUT_TRUNCATE_ON_WRITE = 'c';

        /**
         * Open the files for reading and writing; otherwise it has the same behavior as 'c'.
         */
        const CREATE_WITHOUT_TRUNCATE_ON_READ_AND_WRITE = 'c+';

        /**
         * device number
         */
        const DEV = 'dev';

        /**
         * inode number *
         */
        const INO = 'ino';

        /**
         * inode protection mode
         */
        const MODE = 'mode';

        /**
         * number of links
         */
        const LINK = 'nlink';

        /**
         * userid of owner *
         */
        const UID = 'uid';

        /**
         * groupid of owner *
         */
        const GID = 'gid';

        /**
         *  device type, if inode device
         */
        const RDEV = 'rdev';

        /**
         * size in bytes
         */
        const SIZE = 'size';

        /**
         * time of last access (Unix timestamp)
         */
        const ATIME = 'atime';

        /**
         * time of last modification (Unix timestamp)
         */
        const MTIME = 'mtime';

        /**
         * time of last inode change (Unix timestamp)
         */
        const CTIME = 'ctime';

        /**
         * blocksize of filesystem IO **
         */
        const BLOCK_SIZE = 'blksize';

        /**
         * number of 512-byte blocks allocated **
         */
        const BLOCKS = 'blocks';

        /**
         * index in $_FILES
         * to get uploaded files size
         */
        const FILE_SIZE = 'size';

        /**
         * index in $_FILES
         * to get uploaded files type
         */
        const FILE_TYPE = 'type';

        /**
         * index in $_FILES
         * to get uploaded filename
         */
        const FILE_NAME = 'name';

        /**
         * index in $_FILES
         * to get uploaded files tmp
         */
        const FILE_TMP = 'tmp_name';

        /**
         * index in $_FILES
         * to get uploaded files error
         */
        const FILE_ERROR = 'error';

        /**
         * to search all php files
         */
        const ALL_PHP = '*.php';

        /**
         * to search all css files
         */
        const ALL_CSS = '*.css';

        /**
         * to search all js files
         */
        const ALL_JS = '*.js';

        /**
         * to search all html files
         */
        const ALL_HTML = '*.html';

        /**
         * to search all png image
         */
        const ALL_PNG = '*.png';

        /**
         * to search all jpeg image
         */
        const ALL_JPEG = '*.jpeg';

        /**
         * to search all jpg image
         */
        const ALL_JPG = '*.jpg';

        /**
         * to search all svg image
         */
        const ALL_SVG = '*.svg';

        /**
         * to search all gif image
         */
        const ALL_GIF = '*.gif';
        /**
         * to search all json
         */
        const ALL_JSON = '*.json';

        /**
         * Get the MD5 hash of the files at the given path.
         *
         * @param string $filename
         *
         * @return string
         */
        public static function hash(string $filename): string
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::exist($filename))
                return md5_file($filename);
            else
                return '';
        }

        /**
         * Get the files's last modification time.
         *
         * @param string $filename
         *
         * @return bool|int
         */
        public static function lastModified(string $filename)
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::exist($filename))
                return filemtime($filename);
            else
                return false;
        }


        /**
         * download a files
         *
         * @param string $filename
         *
         * @return false|int
         */
        public static function download(string $filename)
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::exist($filename))
            {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($filename).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
               return readfile($filename);
            }
            self::fileNotExist($filename);
            return false;
        }

        /**
         * return files likes pattern
         *
         * @param string $pattern
         * @param int $flags
         *
         * @return array
         */
        public static function search(string $pattern, int $flags = null): array
        {
            self::quitIfEmpty([$pattern],__FUNCTION__);

            $files = array();

            foreach (glob($pattern,$flags) as $file)
            {
                array_push($files,$file);
            }

            return $files;
        }

        /**
         * create a new files if not exist
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function create(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (!self::exist($filename))
            {
                return touch($filename);
            }

            return false;
        }

        /**
         * delete a files if exist
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function delete(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (is_dir($filename))
            {
                return self::deleteFolder($filename);
            }

            if (self::exist($filename))
            {
                return unlink($filename);
            }
            return false;
        }

        /**
         * delete a folder
         *
         * @param $folder
         *
         * @return bool
         */
        public static function deleteFolder(string $folder): bool
        {
            self::quitIfEmpty([$folder],__FUNCTION__);

            if (!is_dir($folder))
            {
                return false;
            }

            $files = array_diff(scandir($folder), array('.','..'));

            foreach ($files as $file)
            {
                (is_dir("$folder/$file")) ? self::deleteFolder("$folder/$file") : unlink("$folder/$file");
            }

            return rmdir($folder);
        }

        /**
         * return all files lines
         *
         * @param string $filename
         * @param string $mode
         *
         * @return array
         */
        public static function getLines(string $filename, string $mode = File::READ): array
        {
            self::quitIfEmpty([$filename,$mode],__FUNCTION__);

            if (self::verify($filename))
            {
                $file = self::open($filename,$mode);

                if ($file)
                {
                    $lines = array();

                    while (!self::isEnd($file))
                    {
                        array_push($lines,fgets($file));
                    }
                    self::close($file);

                    return $lines;
                }
            }
            return array();
        }

        /**
         * get all keys
         *
         * @param string $filename
         * @param string $delimiter
         * @param string $mode
         *
         * @return array
         */
        public static function getKeys(string $filename,string $delimiter,string $mode = File::READ): array
        {
            self::quitIfEmpty([$filename,$mode],__FUNCTION__);

            if (self::verify($filename))
            {
                $file = self::open($filename,$mode);

                if ($file)
                {
                    $lines = array();

                    while (!self::isEnd($file))
                    {
                        $parts = explode($delimiter,fgets($file));
                        if (!empty($parts[0]))
                            array_push($lines,$parts[0]);

                    }
                    self::close($file);
                    return $lines;
                }
            }
            return array();
        }

         /**
         * get all values
         *
         * @param string $filename
         * @param string $delimiter
         * @param string $mode
         *
         * @return array
         */
        public static function getValues(string $filename,string $delimiter,string $mode = File::READ): array
        {
            self::quitIfEmpty([$filename,$mode],__FUNCTION__);

            if (self::verify($filename))
            {
                $file = self::open($filename,$mode);

                if ($file)
                {
                    $lines = array();

                    while (!self::isEnd($file))
                    {
                        $parts = explode($delimiter,fgets($file));
                        if (!empty($parts[1]))
                            array_push($lines,rtrim($parts[1]));
                    }
                    self::close($file);
                    return $lines;
                }
            }
            return array();
        }

        /**
         * return the files size
         *
         * @param string $filename
         *
         * @return int
         */
        public static function getSize(string $filename): int
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return filesize($filename);
            }

            return -1;
        }

        /**
         * Returns the files extensions
         *
         * @param string $filename
         *
         * @return string
         */
        public static function getExtension(string $filename): string
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return pathinfo($filename, PATHINFO_EXTENSION);
            }

            return '';
        }

        /**
         * copy a folder to destination
         *
         * @param string $source
         * @param string $destination
         * @return bool
         */
        public static function copyFolder(string $source, string $destination)
        {
            self::quitIfEmpty([$source,$destination],__FUNCTION__);

            switch (is_dir($destination))
            {
                case true:
                    File::deleteFolder($destination);
                    mkdir($destination);
                break;
                default:
                    mkdir($destination);
                break;
            }

            $dir_iterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);

            $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

            foreach($iterator as $element)
            {
                switch ($element->isDir())
                {
                    case true:
                        mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                    break;
                    default:
                        copy($element, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                    break;
                }
            }
            foreach($iterator as $element)
            {
                switch ($element->isDir()) {
                    case true:
                        if (!is_dir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName()))  { return false; }
                    break;
                    default:
                        if (!self::isFile($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) { return false; }
                    break;
                }
            }
            return true;
        }

        /**
         * copy a files in an other files
         *
         * @param string $source
         * @param string $destination
         *
         * @return bool
         */
        public static function copy(string $source, string $destination): bool
        {
            self::quitIfEmpty([$source,$destination],__FUNCTION__);

            if (self::exist($source))
            {
                return copy($source,$destination);
            }
            return false;
        }

        /**
         * test if a files or a folder is readable
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isReadable(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (is_dir($filename) && is_writable($filename))
            {
                $objects = scandir($filename);

                foreach ($objects as $object)
                {
                    if ($object != "." && $object != "..")
                    {
                        if (!self::isReadable($filename."/".$object))
                        {
                            return false;

                        } else {
                            continue;
                        }
                    }
                }
                return true;
            }

            if (self::exist($filename))
            {
                return is_readable($filename);
            }
            return false;
        }

        /**
         * test if a files is writable
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isWritable(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (is_dir($filename))
            {
                return is_writable($filename);
            }

            if (self::exist($filename))
            {
                return is_writable($filename);
            }
            return false;
        }


        /**
         * create a hard link
         *
         * @param string $target
         * @param string $link
         *
         * @return bool
         */
        public static function hardLink(string $target, string $link): bool
        {
            self::quitIfEmpty([$target,$link],__FUNCTION__);

            if (self::exist($target))
            {
                return link($target, $link);
            }
            return false;
        }

        /**
         * create a symlink link
         *
         * @param string $target
         * @param string $link
         *
         * @return bool
         */
        public static function symlink(string $target, string $link): bool
        {
            self::quitIfEmpty([$target,$link],__FUNCTION__);

            if (self::exist($target))
            {
                return symlink($target, $link);
            }
            return false;
        }

        /**
         * test if filename is a symlink
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isLink(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::exist($filename))
            {
                return is_link($filename);
            }
            return false;
        }

        /**
         * get the mime of files
         *
         * @param string $filename
         *
         * @return string
         */
        public static function getMime(string $filename): string
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return mime_content_type($filename);
            }

            return '';
        }

        /**
         * get the files's info
         *
         * @param string $filename
         *
         * @return array
         */
        public static function getStat(string $filename): array
        {
            self::quitIfEmpty([$filename], __FUNCTION__);

            if (self::verify($filename))
            {
                $stat = stat($filename);

                if ($stat)
                {
                    return $stat;
                }
            }
            return array();
        }

        /**
         * get a part of stat by a key
         *
         * @param string $filename
         * @param string $key
         *
         * @return mixed
         */
        public static function getStartKey(string $filename,string $key)
        {
            self::quitIfEmpty([$filename,$key],__FUNCTION__);

            if (self::verify($filename))
            {
                $file = self::getStat($filename);
                return $file[$key];
            }
            return null;
        }

        /**
         * write data on a files
         *
         * @param string$filename
         * @param string$data
         * @param string $mode
         *
         * @return bool
         */
        public static function write(string $filename, string $data,string $mode = File::READ_AND_WRITE): bool
        {
            self::quitIfEmpty([$filename,$data,$mode],__FUNCTION__);

            if (self::verify($filename))
            {
                $file = self::open($filename, $mode);
                fwrite($file, $data, strlen($data));
                return self::close($file);
            }
            return false;
        }

        /**
         * check if filename is a files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isFile(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            return is_file($filename);
        }

        public static function mimes()
        {
            return new MimeTypes;
        }

        /**
         * @param string $filename
         *
         * @return bool
         */
        public static function isImg(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::verify($filename))
            {
                return  in_array(self::getExtension($filename),File::IMG,true);
            }
            return false;
        }

        /**
         * test if filename is a html files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isHtml(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::verify($filename))
                return self::getExtension($filename) == File::HTML && strcmp('text/html', self::mimes()->getMimeType('html')) == 0;

            return false;
        }


        /**
         * test if filename is a php files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isPhp(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
                return self::getExtension($filename) == File::PHP && strcmp(mime_content_type($filename),'text/x-php') == 0;

            return false;
        }

        /**
         * test if filename is a js files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isJS(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
                return self::getExtension($filename) == File::JS  && strcmp('application/javascript',self::mimes()->getMimeType('js') == 0);

            return false;
        }

        /**
         * test if filename is a json files
         *<
         * @param string $filename
         *
         * @return bool
         */
        public static function isJson(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
                return self::getExtension($filename) == File::JSON  && strcmp('application/json',self::mimes()->getMimeType('json')) == 0;
            return false;
        }

        /**
         * test if filename is a xml files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isXml(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::verify($filename))
                return self::getExtension($filename) == File::XML  && strcmp('application/xml',self::mimes()->getMimeType('xml')) == 0;
            return false;
        }

        /**
         * test if filename is a css files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isCss(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
                return self::getExtension($filename) == File::CSS && strcmp('text/css',self::mimes()->getMimeType('css')) == 0;

            return false;
        }

        /**
         * test if filename is a pdf files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isPdf(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return self::getExtension($filename) === File::PDF && strcmp('application/pdf', self::mimes()->getMimeType('pdf')) == 0;
            }
            return false;
        }

        /**
         * test the end of files
         *
         * @param $file
         *
         * @return bool
         */
        public static function isEnd($file): bool
        {
            self::quitIfEmpty([$file],__FUNCTION__);

            return feof($file);

        }

        /**
         * get the group of the files
         *
         * @param string $filename
         *
         * @return int
         */
        public static function getGroup(string $filename): int
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return filegroup($filename);
            }

            return -1;
        }

        /**
         * get the owner of the files
         *
         * @param string $filename
         *
         * @return int
         */
        public static function getOwner(string $filename): int
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return fileowner($filename);
            }
            return -1;
        }

        /**
         * Include in page all files passed by parameters
         *
         * @throws Exception
         *
         * @return bool
         */
        public static function loads(): bool
        {
            if (func_num_args() == 0)
                throw new Exception("Load function require files in parameters");


            foreach (func_get_args() as $file)
            {
                if (self::verify($file))
                    require_once "$file";
                else
                    throw new Exception("$file not exist");
            }
            return true;
        }

        /**
         * return the content of a files
         *
         * @param string $filename
         *
         * @return string
         */
        public static function getContent(string $filename): string
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
            {
                return file_get_contents($filename);
            }

            return '';
        }

        /**
         * write data on a files
         *
         * @param string $filename
         * @param mixed  $data
         * @param int    $flags
         *
         * @return bool|int
         */
        public static function putContents(string $filename, $data, int $flags = 0)
        {
            self::quitIfEmpty([$filename,$data],__FUNCTION__);

            if (self::verify($filename))
            {
                return file_put_contents($filename,$data,$flags);
            }
            return false;
        }

        /**
         * return $_FILES
         *
         * @return array
         */
        public static function getFile():array
        {
            return $_FILES;
        }

        /**
         * get the uploaded files type
         *
         * @param string $inputName
         *
         * @return string
         */
        public static function uploadedFileType(string $inputName): string
        {
            self::quitIfEmpty([$inputName],__FUNCTION__);

            return $_FILES[$inputName][File::FILE_TYPE];
        }

        /**
         * return the uploaded files size
         *
         * @param string $inputName
         *
         * @return int
         */
        public static function uploadedFileSize(string $inputName): int
        {
            self::quitIfEmpty([$inputName],__FUNCTION__);

            return $_FILES[$inputName][File::FILE_SIZE];
        }

        /**
         * return the uploaded files name
         *
         * @param string $inputName
         *
         * @return string
         */
        public static function uploadedFileName(string $inputName): string
        {
            self::quitIfEmpty([$inputName],__FUNCTION__);

            return $_FILES[$inputName][File::FILE_NAME];
        }

        /**
         *  return the uploaded files tmp directory
         *
         * @param string $inputName
         *
         * @return string
         */
        public static function uploadedFileTmpPath(string $inputName): string
        {
            self::quitIfEmpty([$inputName],__FUNCTION__);

            return $_FILES[$inputName][File::FILE_TMP];
        }

        /**
         * return the uploaded errors
         *
         * @param string $inputName
         *
         * @return int
         */
        public static function uploadedFileErrors(string $inputName): int
        {
            self::quitIfEmpty([$inputName],__FUNCTION__);

            return $_FILES[$inputName][File::FILE_ERROR];
        }

        /**
         * change filename old on new
         *
         * @param string $old
         * @param string $new
         *
         * @return bool
         */
        public static function rename(string $old, string $new): bool
        {
            self::quitIfEmpty([$old,$new],__FUNCTION__);

            if (self::exist($old) && !self::exist($new))
                return rename($old,$new);

            return false;
        }

        /**
         * move a uploaded files to destination
         *
         * @param string $inputName
         * @param string $destination
         *
         * @return bool
         */
        public static function moveUploadedFile(string $inputName,string $destination): bool
        {
            return move_uploaded_file(self::uploadedFileTmpPath($inputName),$destination);
        }

        /**
         * test if it's files exist and if it's a files
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function verify(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            return self::isFile($filename) && self::exist($filename);
        }

        /**
         * verify if it's files exist
         *
         * @param string $file
         *
         * @return bool
         */
        public static function exist(string $file): bool
        {
            self::quitIfEmpty([$file],__FUNCTION__);

            return file_exists($file);
        }

        /**
         * exit the app with a message
         *
         * @param $function
         */
        private static function nameIsEmpty(string $function)
        {
            die("Please enter the input name parameter on $function function");
        }

        /**
         * verify if files is empty
         *
         * @param   $name
         * @return bool
         */
        private static function isEmpty($name)
        {
            return empty($name);
        }

        /**
         * open a files
         *
         * @param string $filename
         * @param string $mode
         *
         * @return bool|resource
         */
        public static function open(string $filename, string $mode)
        {
            self::quitIfEmpty([$filename,$mode],__FUNCTION__);

            if (self::verify($filename))
            {
                return fopen($filename,$mode);
            }

            return false;
        }

        /**
         * quit app
         *
         * @param array  $name
         * @param string $function
         */
        private static function quitIfEmpty(array $name,string $function)
        {
            foreach ($name as $item)
            {
                if (self::isEmpty($item))
                {
                    self::nameIsEmpty($function);
                }
            }

        }

        /**
         * quit app
         *
         * @param string $filename
         */
        private static function fileNotExist(string $filename)
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            die("$filename does'nt exist ");
        }

        /**
         *
         * remove a file if exist
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function remove_if_exist(string $filename)
        {
            return self::exist($filename)? self::delete($filename) : false;
        }

        /**
         * quit app with a different message
         *
         * @param string $message
         */
        private static function quit(string $message)
        {
            self::quitIfEmpty([$message],__FUNCTION__);
            die($message);
        }

        /**
         * close a files
         *
         * @param resource $filename
         *
         * @return bool
         */
        public static function close($filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            return fclose($filename);
        }

        /**
         * get absolute path
         *
         * @param  string $path
         *
         * @return string
         */
        public static function realPath(string $path): string
        {
            self::quitIfEmpty([$path],__FUNCTION__);
            if (self::verify($path))
                return realpath($path);

            return '';
        }

        /**
         * Changes files mod
         *
         * @param string $filename
         * @param int    $mode
         *
         * @return bool
         */
        public static function chmod(string $filename, int $mode): bool
        {
            self::quitIfEmpty([$filename,$mode],__FUNCTION__);
            if (self::verify($filename))
                return chmod($filename,$mode);
            return false;
        }

        /**
         * Changes files group
         *
         * @param string $filename
         * @param mixed  $group
         *
         * @return bool
         */
        public static function chgrp(string $filename, $group): bool
        {
            self::quitIfEmpty([$filename,$group],__FUNCTION__);
            if (self::verify($filename))
                return chgrp($filename,$group);

            return false;
        }

        /**
         * Changes group ownership of symlink
         *
         * @param string $filename
         * @param mixed  $group
         *
         * @return bool
         */
        public static function lchgrp(string $filename, $group): bool
        {
            self::quitIfEmpty([$filename,$group],__FUNCTION__);

            return lchgrp($filename,$group);
        }

        /**
         * Changes files owner
         *
         * @param string $filename
         * @param mixed  $user
         *
         * @return bool
         */
        public static function chown(string $filename, $user): bool
        {
            self::quitIfEmpty([$filename,$user],__FUNCTION__);
            if (self::verify($filename))
                return chown($filename,$user);
            return false;
        }

        /**
         * Tells whether the filename is executable
         *
         * @param string $filename
         *
         * @return bool
         */
        public static function isExecutable(string $filename): bool
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            return is_executable($filename);
        }

        /**
         * Gets files type
         *
         * @param string $filename
         *
         * @return string
         */
        public static function getType(string $filename): string
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::verify($filename))
                return filetype($filename);

            return '';
        }

        /**
         * Gets last access time of files
         *
         * @param string $filename
         *
         * @return int
         */
        public static function fileTime(string $filename): int
        {
            self::quitIfEmpty([$filename],__FUNCTION__);
            if (self::verify($filename))
                return fileatime($filename);
            return -1;
        }

        /**
         * Gets files owner
         *
         * @param string $filename
         *
         * @return int
         */
        public static function fileOwner(string $filename): int
        {
            self::quitIfEmpty([$filename],__FUNCTION__);

            if (self::verify($filename))
                return fileowner($filename);

            return -1;
        }

    }
}