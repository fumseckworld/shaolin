<?php


namespace Testing {


    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
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
            $this->git = new Git('.','symfony', 'sebastien');
        }

        public function test_current_branch()
        {
            $this->assertEquals('4.4',$this->git->current_branch());
        }

        public function test_git_log()
        {
            $this->assertNotEmpty($this->git->log());
        }

        public function test_count_branch()
        {
            $this->assertEquals(22,$this->git->branches_found());
        }

        public function test_commits()
        {
            $this->assertNotNull($this->git->commits_size());
        }


        public function test()
        {
            $this->assertNotEmpty($this->git->commits_by_year('Fabien Potencier')->collection());
            $this->assertNotEmpty($this->git->commits_by_month('Fabien Potencier')->collection());

            $this->assertEmpty($this->git->commits_by_year('Bob Lenon')->collection());
        }

        public function test_files()
        {

            $this->assertNotEmpty($this->git->files()->collection());

            $this->assertNotEmpty($this->git->files('src')->collection());

        }

        public function test_directories()
        {
            $this->assertNotEmpty($this->git->directories()->collection());
            $this->assertNotEmpty($this->git->directories('src')->collection());
        }
        public function test_checkout()
        {

            $this->assertTrue($this->git->checkout($this->git->current_branch()));
        }

        public function test_create()
        {

            $this->assertTrue(Git::create('mario','alexandra','a super mario game'));
            $this->assertTrue( Dir::checkout('../..'));
            $this->assertTrue(Dir::remove('alexandra'));
        }


        public function test_remote()
        {
            $this->assertNotEmpty($this->git->remote()->collection());
            $this->assertContains('origin',$this->git->remote()->collection());
        }

        public function test_contributors()
        {
            $this->assertNotEmpty($this->git->contributors());
        }

        /**
         * @throws Exception
         */
        public function test_equip()
        {
            $this->assertEquals(2385,$this->git->contributors_size());
        }

        public function test_tag()
        {
            $tags = $this->git->releases();

            $this->assertNotEmpty($tags);
            $this->assertContains('v2.4.4',$tags);
            $this->assertContains('v2.5.6',$tags);
        }

        public function test_news()
        {
            $this->assertNotEmpty($this->git->news());
            $this->assertNotEmpty($this->git->change('v2.4.9','v2.4.8'));
            $this->expectException(Kedavra::class);

            $this->git->change('3.2.3','3.2.2');

        }
        public function test_views()
        {

            $this->assertNotEmpty($this->git->contributors_view());
            $this->assertNotEmpty($this->git->git());
            $this->assertNotEmpty($this->git->release_view());

        }
        public function test_tag_size()
        {
            $size = $this->git->release_size();
             $this->assertNotEmpty($size);
             $this->assertEquals(439,$size);
        }

        public function test_repo_name()
        {
            $this->assertEquals('symfony',$this->git->repository());
            $this->assertNotEmpty($this->git->path());
        }

        public function test_month()
        {
            $this->assertEquals(14,$this->git->months()->length());
            $this->assertTrue($this->git->months()->exist('January'));
            $this->assertTrue($this->git->months()->exist('February'));
            $this->assertTrue($this->git->months()->exist('March'));
            $this->assertTrue($this->git->months()->exist('April'));
            $this->assertTrue($this->git->months()->exist('May'));
            $this->assertTrue($this->git->months()->exist('June'));
            $this->assertTrue($this->git->months()->exist('July'));
            $this->assertTrue($this->git->months()->exist('August'));
            $this->assertTrue($this->git->months()->exist('October'));
            $this->assertTrue($this->git->months()->exist('November'));
            $this->assertTrue($this->git->months()->exist('September'));
            $this->assertTrue($this->git->months()->exist('December'));

        }
    }
}