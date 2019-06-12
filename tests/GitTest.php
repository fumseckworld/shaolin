<?php


namespace Testing {


    use Imperium\Versioning\Git\Git;
    use PHPUnit\Framework\TestCase;

    class GitTest extends TestCase
    {
        /**
         * @var Git
         */
        private $git;

        public function setUp(): void
        {
            $this->git = new Git('.');
        }

        public function test_current_branch()
        {
            $this->assertEquals('develop',$this->git->current_branch());
        }

        public function test_git_log()
        {
            $this->assertNotEmpty($this->git->log());
        }

        public function test_count_branch()
        {
            $this->assertEquals('5',$this->git->branches_found());
        }

        public function test_commits()
        {
            $this->assertNotNull($this->git->commits_size());
        }

        public function test_commit()
        {
            $this->assertNotEmpty($this->git->commits_by_year('Willy Micieli')->collection());
            $this->assertNotEmpty($this->git->commits_by_month('Willy Micieli')->collection());

            $this->assertEmpty($this->git->commits_by_year('Bob Lenon')->collection());
            $this->assertEmpty($this->git->commits_by_month('Bob Lenon')->collection());
        }

        public function test()
        {
            $this->assertNotEmpty($this->git->commits_by_year('Willy Micieli')->collection());
            $this->assertNotEmpty($this->git->commits_by_month('Willy Micieli')->collection());

            $this->assertEmpty($this->git->commits_by_year('Bob Lenon')->collection());
            $this->assertEmpty($this->git->commits_by_month('Bob Lenon')->collection());
        }
    }
}