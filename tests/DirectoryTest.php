<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 16/08/2018
 * Time: 18:31
 */

namespace tests;


use Imperium\Directory\Dir;
use Imperium\File\File;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    /**
     * directory name
     * 
     * @var string
     */
    private $directory;
    
    public function setUp()
    {
        $this->directory = 'tmp';
    }

    public function testDirectory()
    {

        $this->assertEquals(false,Dir::is($this->directory));
        $this->assertEquals(true,Dir::create($this->directory));
        $this->assertEquals(true,Dir::is($this->directory));
        $this->assertEquals(true,Dir::remove($this->directory));
        $this->assertEquals(false,Dir::is(sha1($this->directory)));
        $this->assertEquals(false,Dir::remove(sha1($this->directory)));
    }
}