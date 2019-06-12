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
            $this->assertEquals(5,$this->git->branches_found());
        }

        public function test_commits()
        {
            $this->assertNotNull($this->git->commits_size());
        }


        public function test()
        {
            $this->assertNotEmpty($this->git->commits_by_year('Willy Micieli')->collection());
            $this->assertNotEmpty($this->git->commits_by_month('Willy Micieli')->collection());

            $this->assertEmpty($this->git->commits_by_year('Bob Lenon')->collection());
        }

        public function test_files()
        {
            $this->assertNotEmpty($this->git->files()->collection());

            $this->assertNotEmpty($this->git->files('tests')->collection());

            $this->assertTrue($this->git->files('tests')->exist('tests/GitTest.php'));

        }

        public function test_directories()
        {
            $this->assertNotEmpty($this->git->directories()->collection());


            $this->assertNotEmpty($this->git->directories('tests')->collection());

            $this->assertFalse($this->git->directories('imperium')->exist('imperium/Write'));
            $this->assertTrue($this->git->directories('imperium')->exist('imperium/Writing'));

        }
        public function test_checkout()
        {
            $this->assertTrue($this->git->checkout($this->git->current_branch()));
        }

        public function test_create()
        {
            $this->assertTrue(Git::create('mario'));
            $this->assertTrue( Dir::checkout('..'));
            $this->assertTrue(Dir::remove('mario'));

            $this->assertTrue(Git::create('mario',true,'supermario'));
            $this->assertEquals('supermario',Git::description('mario'));
            $this->assertTrue( Dir::checkout('..'));
            $this->assertTrue(Dir::remove('mario'));
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
            $this->assertEquals(1,$this->git->contributors_size());
        }

        public function test_tag()
        {
            $tags = $this->git->releases();

            $this->assertNotEmpty($tags);
            $this->assertContains('8.3',$tags);
            $this->assertContains('6.5',$tags);
            $this->assertContains('5.0',$tags);
        }

        public function test_news()
        {
            $this->assertNotEmpty($this->git->news('8.2.3','8.2.2'));

            $this->assertNotEmpty($this->git->news('8.2.3','8.2.2',false));

            $this->expectException(Kedavra::class);

            $this->git->news('3.2.3','3.2.2');

        }

        public function test_clone()
        {

            $dir  = dirname(config_path()). DIRECTORY_SEPARATOR .'legend';

            $this->assertTrue(Git::clone('git://git.fumseck.eu/library/legend',$dir));

            $this->expectException(Kedavra::class);

            Git::clone('git://git.fumseck.eu/library/legend',$dir);

        }

        public function test_suppress()
        {

            $dir  = dirname(config_path()). DIRECTORY_SEPARATOR .'legend';
            $this->assertTrue(Dir::remove($dir));
        }
        public function test_contributor_view()
        {

            $this->assertNotEmpty($this->git->contributors_view());

        }
        public function test_tag_size()
        {
            $size = $this->git->release_size();
             $this->assertNotEmpty($size);
             $this->assertEquals(64,$size);
        }

        public function test_repo_name()
        {
            $this->assertEquals('imperium',$this->git->repository());
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