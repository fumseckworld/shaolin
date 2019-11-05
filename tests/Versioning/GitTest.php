<?php


namespace Testing\Versioning;


use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Testing\Unit;
use Imperium\Versioning\Git;

class GitTest extends Unit
{
    /**
     * @var Git
     */
    private $git;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function setUp(): void
    {
      $this->git = $this->git('/home/willy/imperium','willy');
    }
	
    public function test_base()
    {
        $this->assertEquals('imperium',$this->git->name());
        $this->assertEquals('willy',$this->git->owner());
    }

    public function test_status()
    {
        $this->assertNotEmpty($this->git->status());
    }
    public function test_diff()
    {
        $this->assertNotEmpty($this->git->diff());
        $this->assertNotEmpty($this->git->diff('10.5.2','10.5.3'));
    }
    public function test_branch()
    {
        $this->assertEquals('master',$this->git->current_branch());
    }
    public function test_commit_size()
    {
        $a = $this->git->commits_size('master');
        $b = $this->git->commits_size('develop');

        $this->assertIsInt($a);
        $this->assertIsInt($b);
        $this->assertNotEquals($a,$b);
    }
    public function test_branches()
    {
        $branches = $this->git->branches();
        $this->assertContains('master',$branches);
        $this->assertContains('develop',$branches);

    }

    public function test_log()
    {
        $first = $this->git->log(0,'master');
        $second = $this->git->log(1,'master');
        $this->assertNotEmpty($first);
        $this->assertNotEmpty($second);
        $this->assertTrue(different($first,$second));
        $first_develop = $this->git->log(0,'develop');
        $second_develop = $this->git->log(1,'develop');
        $this->assertNotEmpty($first_develop);
        $this->assertNotEmpty($second_develop);
        $this->assertTrue(different($first,$first_develop));


    }

    public function test_releases()
    {
        $this->assertNotEmpty($this->git->releases());
    }

}
