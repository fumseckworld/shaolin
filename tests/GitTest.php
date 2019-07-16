<?php


namespace Testing {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\Versioning\Git\Git;
    use PHPUnit\Framework\TestCase;

    /**
     * Class GitTest
     * @package Testing
     */
    class GitTest extends TestCase
    {
        /**
         * @var Git
         */
        private $git;

        /**
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function setUp(): void
        {
            $this->git = new Git('depots/willy/imperium', 'willy');
        }

        /**
         *
         */
        public function test_owner()
        {
            $this->assertIsString($this->git->owner());
            $this->assertEquals('willy',$this->git->owner());
        }

        /**
         *
         */
        public function test_current_branch()
        {
            $this->assertEquals('master',$this->git->current_branch());
        }

        /**
         * @throws Kedavra
         */
        public function test_git_log()
        {
            $this->assertNotEmpty($this->git->log());
        }

        /**
         *
         */
        public function test_count_branch()
        {
            $this->assertEquals(3,$this->git->branches_found());
        }

        /**
         *
         */
        public function test_commits()
        {
            $this->assertNotNull($this->git->commits_size());
        }


        /**
         *
         */
        public function test()
        {
            $this->assertNotEmpty($this->git->commits_by_year('Willy Micieli')->collection());
            $this->assertNotEmpty($this->git->commits_by_month('Willy Micieli')->collection());

            $this->assertEmpty($this->git->commits_by_year('Bob Lenon')->collection());
        }

        /**
         *
         */
        public function test_files()
        {

            $this->assertNotEmpty($this->git->files(''));
            $this->assertContains('README.md',$this->git->files(''));

            $this->assertNotEmpty($this->git->files('imperium'));

        }

        /**
         *
         */
        public function test_directories()
        {
            $this->assertNotEmpty($this->git->directories());
            $this->assertNotEmpty($this->git->directories('imperium'));
            $this->assertContains('Routing',$this->git->directories('imperium'));
        }


        /**
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function test_create()
        {
            Dir::checkout(ROOT);
            $this->assertTrue(Git::create('mario','alexandra','a super mario game','supermario@gmail.om'));
            Dir::checkout(ROOT);
            $this->assertEquals('supermario@gmail.om',(new Git('alexandra/mario',''))->email());
            Dir::checkout(ROOT);
            $this->assertTrue( Dir::remove('alexandra'));
        }

        /***
         * @throws Kedavra
         */
        public function test_contributors()
        {
            $this->assertNotEmpty($this->git->contributors());
        }

        /**
         * @throws Exception
         */
        public function test_equip()
        {
            $this->assertEquals('1',$this->git->contributors_size());
        }

        /**
         *
         */
        public function test_tag()
        {
            $tags = $this->git->releases();

            $this->assertNotEmpty($tags);
            $this->assertContains('6.7',$tags);
            $this->assertContains('6.3',$tags);
        }

        /**
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function test_news()
        {
            $this->assertNotEmpty($this->git->news());
            $this->assertNotEmpty($this->git->change('6.3','6.2'));
            $this->expectException(Kedavra::class);

            $this->git->change('3.2.3','3.2.2');

        }

        /**
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function test_views()
        {

            $this->assertNotEmpty($this->git->contributors_view());
            $this->assertNotEmpty($this->git->git(''));
            $this->assertNotEmpty($this->git->release_view());

        }

        /**
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function test_tag_size()
        {
            $size = $this->git->release_size();
             $this->assertNotEmpty($size);
             $this->assertEquals('64',$size);
        }

        /**
         *
         */
        public function test_repo_name()
        {
            $this->assertEquals('imperium',$this->git->repository());
            $this->assertNotEmpty($this->git->path());
        }

        /**
         *
         */
        public function test_month()
        {
            $this->assertEquals(15,$this->git->months()->length());
        }
    }
}