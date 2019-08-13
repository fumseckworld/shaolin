<?php

namespace Testing\Dir;

use Imperium\Directory\Dir;
use Imperium\File\File;
use PHPUnit\Framework\TestCase;

class DirTest extends TestCase
{
    
    public function test_structure()
    {
           $this->assertTrue(Dir::create('linux')); 
           $this->assertTrue(Dir::structure('linux','usr','etc','var'));
           $this->assertTrue(Dir::exist('linux/etc'));
           $this->assertTrue(Dir::exist('linux/usr'));
           $this->assertTrue(Dir::exist('linux/var'));
           $this->assertTrue(Dir::contains('linux','etc','usr','var'));
           $this->assertTrue(  File::create('linux/usr/.gitignore'));
           $this->assertTrue(Dir::clear('linux/usr'));
           $this->assertTrue(File::exist('linux/usr/.gitignore'));
           $this->assertTrue(Dir::remove('linux')); 
           $this->assertNotEmpty(Dir::scan('imperium'));
           $this->assertTrue(Dir::checkout('imperium'));
    }
}