<?php
/**
 * fumseck added FileTest.php to imperium
 * The 19/09/17 at 18:51
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
 **/


namespace tests;

use Exception;
use Imperium\File\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private $fileDir = "tests/files";
    private $file = "test";
    private $filename = "tests/files/test.txt";
    private $rename = "tests/files/rename";
    private $destination = 'tests/files/destination';
    private $html = "tests/files/index.html";
    private $css = "tests/files/bootstrap.css";
    private $php = "tests/files/file.php";
    private $js = "tests/files/bootstrap.js";

    private function getTestFile(string $ext): string
    {
        return "$this->fileDir/$this->file.$ext";
    }

    private function createTestFile(string $ext)
    {
        $file = $this->getTestFile($ext);
        if (File::exist($file))
        {
            File::delete($file);
        }
        return File::create($file);
    }


    private function deleteTestFile(string $ext)
    {
        return File::delete($this->getTestFile($ext));
    }

    private function create()
    {
        if (File::exist($this->file))
            File::delete($this->file);

        return File::create($this->file);
    }

    private function delete()
    {
        return File::delete($this->file);
    }



    public function testCreateAndDelete()
    {
        $this->assertEquals(true,$this->create());
        $this->assertEquals(true,$this->delete());
    }

    public function testGetContent()
    {
        $this->assertEquals('',File::getContent($this->file));
        $this->create();
        $this->assertNotEquals(false,File::putContents($this->file,'a'));
        $this->assertEquals('a',File::getContent($this->file));
        $this->delete();
    }

    public function testPutContent()
    {
        $this->assertEquals(false,File::putContents($this->file,"a"));
        $this->create();
        $this->assertEquals(true,File::putContents($this->file,'a'));
        $this->assertEquals(true,File::putContents($this->file,'alexandra'));
        $this->delete();
    }

    public function testHash()
    {
        $this->assertEquals('',File::hash($this->file));
        $this->create();
        $this->assertEquals('d41d8cd98f00b204e9800998ecf8427e',File::hash($this->file));
        $this->assertStringMatchesFormat('%S',File::hash($this->file));
        $this->delete();
    }


    public function testExist()
    {
        $this->create();
        $this->assertEquals(true,File::exist($this->file));
        $this->delete();
    }
    public function testNotExist()
    {
        $this->delete();
        $this->assertEquals(false,File::exist($this->file));
    }
    public function testIsFile()
    {
        $this->assertEquals(false,File::isFile($this->file));
        $this->create();
        $this->assertEquals(true,File::isFile($this->file));
        $this->delete();
    }

    public function testDeleteFolder()
    {
        $dir = uniqid();

        $this->assertEquals(false,File::deleteFolder($dir));
        mkdir($dir);
        $this->assertEquals(true,File::deleteFolder($dir));
    }
    public function testCopy()
    {
        $this->create();
        $this->assertEquals(true,File::copy($this->file,$this->destination));
        $this->assertEquals(true,File::delete($this->destination));
        $this->delete();
    }

    public function testSearch()
    {
        $this->createTestFile(File::PHP);
        $this->assertContains($this->getTestFile(File::PHP),File::search("$this->fileDir/*.php"));
        $this->deleteTestFile(File::PHP);

        $this->createTestFile(File::HTML);
        $this->assertContains($this->getTestFile(File::HTML),File::search("$this->fileDir/*.html"));
        $this->deleteTestFile(File::HTML);
        $this->assertEquals([],File::search("$this->fileDir/*.xml"));


    }
    public function testGetLines()
    {
        $this->assertEquals([],File::getLines($this->file));
        $this->create();
        File::putContents($this->file,"a");
        $this->assertEquals([ 0 => "a"],File::getLines($this->file));
        $this->delete();
    }

    public function testCopyDirectory()
    {
        $this->assertEquals(true,File::copyFolder('imperium',"lion"));
    }

    public function testLastModified()
    {
        $this->create();
        $this->assertEquals(!false,File::lastModified($this->file));
        $this->delete();
        $this->assertEquals(false,File::lastModified($this->file));

    }

    public function testCreateExistingFile()
    {
        $this->create();
        $this->assertEquals(false,File::create($this->file));
        $this->delete();
    }

    public function testDelete()
    {
        File::copyFolder('imperium','lion');
        $this->assertEquals(true,File::delete('lion'));
    }

    public function testDeleteNotExistingFolder()
    {
        $this->assertEquals(false,File::delete('lion'));
    }

    public function testGetKeys()
    {
        $this->assertEquals([],File::getKeys($this->file,''));
        $this->assertEquals(['ENV','DB','DB_USERNAME','DB_PASSWORD'],File::getKeys($this->filename,"="));
    }
    public function testGetValues()
    {
        $this->assertEquals([],File::getValues($this->file,''));
        $this->assertEquals(['laravel', 'mars' ,'imperium', 'pass'],File::getValues($this->filename,"="));
    }

    public function testGetSize()
    {
        $this->assertEquals(-1,File::getSize($this->file));
        $this->create();
        $this->assertNotEquals(-1,File::getSize($this->file));
        $this->delete();
    }

    public function testGetExtention()
    {
        $this->assertEquals('txt',File::getExtension($this->filename));
        $this->assertEquals('',File::getExtension($this->file));
    }

    public function testCopySourceNotExist()
    {
        $this->assertEquals(false,File::copy($this->file,$this->destination));
    }

    public function testIsReadable()
    {
        $this->assertEquals(false,File::isReadable($this->file));
        $this->assertEquals(true,File::isReadable('imperium'));
        $this->assertNotEquals(false,File::isReadable($this->filename));
    }
    public function testIsWritable()
    {
        $this->assertEquals(true,File::isWritable('imperium'));
        $this->assertEquals(false,File::isWritable($this->file));
        $this->assertNotEquals(false,File::isWritable($this->filename));
    }



    public function testGetMime()
    {
        $this->assertEquals('',File::getMime($this->file));
        $this->assertEquals('text/plain',File::getMime($this->filename));
    }

    public function testGetStat()
    {
        $this->assertNotEquals([],File::getStat($this->filename));
        $this->assertEquals([],File::getStat($this->file));
    }

    public function testGetStatKey()
    {

        $this->assertEquals(null,File::getStartKey($this->file,'blocks'));
    }

    public function testWrite()
    {
        $this->assertEquals(false,File::write($this->file,"a"));
        File::copy($this->filename,$this->destination);
        $this->assertNotEquals(false,File::write($this->filename,"alexandra")) ;
        File::delete($this->filename);
        File::copy($this->destination,$this->filename);
        File::delete($this->destination);
    }

    public function testIs()
    {
        $this->assertEquals(false,File::isImg($this->filename));
        $this->assertEquals(false,File::isImg($this->file));
        $this->assertEquals(false,File::isLink($this->filename));
        $this->assertEquals(false,File::isLink($this->file));
        $this->assertEquals(true,File::isWritable($this->filename));
        $this->assertEquals(false,File::isWritable($this->file));
        $this->assertEquals(true,File::isReadable($this->filename));
        $this->assertEquals(false,File::isReadable($this->file));
        $this->assertEquals(true,File::isFile($this->filename));
        $this->assertEquals(false,File::isFile($this->file));
        $this->assertEquals(false,File::isCss($this->filename));
        $this->assertEquals(false,File::isCss($this->file));
        $this->assertEquals(false,File::isExecutable($this->filename));
        $this->assertEquals(false,File::isExecutable($this->file));
        $this->assertEquals(false,File::isHtml($this->filename));
        $this->assertEquals(false,File::isHtml($this->file));
        $this->assertEquals(false,File::isJS($this->filename));
        $this->assertEquals(false,File::isJS($this->file));
        $this->assertEquals(false,File::isPhp($this->filename));
        $this->assertEquals(false,File::isPhp($this->file));
        $this->assertEquals(false,File::isXml($this->filename));
        $this->assertEquals(false,File::isXml($this->file));
        $this->assertEquals(false,File::isPdf($this->filename));
        $this->assertEquals(false,File::isPdf($this->file));
        $this->assertEquals(false,File::isJson($this->filename));
        $this->assertEquals(false,File::isJson($this->file));
        $this->assertEquals(true,File::exist($this->filename));
        $this->assertEquals(false,File::exist($this->file));

        $this->assertEquals(true,File::isHtml($this->html));
        $this->assertEquals(false,File::isHtml($this->css));

        $this->assertEquals(true,File::isCss($this->css));
        $this->assertEquals(false,File::isCss($this->php));

        $this->assertEquals(true,File::isPhp($this->php));
        $this->assertEquals(false,File::isPhp($this->css));

        $this->assertEquals(true,File::isJS($this->js));
        $this->assertEquals(false,File::isJS($this->css));

        $this->assertEquals(true,File::isXml('phpunit.xml'));
        $this->assertEquals(false,File::isXml('composer.json'));

        $this->assertEquals(true,File::isJson('composer.json'));
        $this->assertEquals(false,File::isJson('phpunit.xml'));
        $this->assertEquals(false,File::isPdf('phpunit.xml'));



    }


    public function testRename()
    {
        $this->assertEquals(false,File::rename($this->file,$this->destination));
        $this->assertEquals(true,File::rename($this->rename,$this->destination));
        $this->assertEquals(true,File::rename($this->destination,$this->rename));
    }
    public function testGetGroup()
    {
        $this->assertNotEquals(-1,File::getGroup($this->filename));
        $this->assertEquals(-1,File::getGroup($this->file));
    }

    public function testGetsOwner()
    {
        $this->assertNotEquals(-1,File::getOwner($this->filename));
        $this->assertEquals(-1,File::getOwner($this->file));
    }

    /**
     * @throws Exception
     */
    public function testLoad()
    {
        $this->assertEquals(true,File::loads('tests/files/load.php'));
        $this->expectException(Exception::class);
        File::loads();
        File::loads('abc');
    }

    public function testOpen()
    {
        $this->assertEquals(false,File::open($this->file,File::READ));
        $this->assertNotEquals(false,File::open($this->filename,File::READ));
    }

    public function testRealPath()
    {
        $this->assertNotEmpty(File::realPath($this->filename));
        $this->assertEquals('',File::realPath($this->file));
    }


    public function testFileTime()
    {
        $this->assertNotEmpty(File::fileTime($this->filename));
        $this->assertEquals(-1,File::fileTime($this->file));
    }
    public function testChmod()
    {
        $this->assertEquals(true,File::chmod($this->rename,0777));
        $this->assertEquals(false,File::chmod($this->file,0777));
    }


}