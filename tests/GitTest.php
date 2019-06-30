<?php


namespace Testing {


    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\Versioning\Git\Git;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\DependencyInjection\Tests\Compiler\D;

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
         */
        public function setUp(): void
        {
            $this->git = new Git('.', 'willy');
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
            $this->assertEquals('develop',$this->git->current_branch());
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
            $this->assertEquals(4,$this->git->branches_found());
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

            $this->assertNotEmpty($this->git->files()->collection());

            $this->assertNotEmpty($this->git->files('imperium')->collection());

        }

        /**
         *
         */
        public function test_directories()
        {
            $this->assertNotEmpty($this->git->directories()->collection());
            $this->assertNotEmpty($this->git->directories('imperium')->collection());
        }

        /**
         *
         */
        public function test_checkout()
        {

            $this->assertTrue($this->git->checkout($this->git->current_branch()));
        }

        /**
         * @throws Kedavra
         */
        public function test_create()
        {

            $this->assertTrue(Git::create('mario','alexandra','a super mario game'));
            Dir::checkout(ROOT);
            $this->assertTrue( Dir::remove('alexandra'));
        }


        /**
         *
         */
        public function test_remote()
        {
            $this->assertNotEmpty($this->git->remote()->collection());
            $this->assertContains('origin',$this->git->remote()->collection());
        }

        /**
         *
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
            $this->assertEquals(1,$this->git->contributors_size());
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
         * @throws Kedavra
         */
        public function test_news()
        {
            $this->assertNotEmpty($this->git->news());
            $this->assertNotEmpty($this->git->change('6.3','6.2'));
            $this->expectException(Kedavra::class);

            $this->git->change('3.2.3','3.2.2');

        }

        /**
         *
         */
        public function test_views()
        {

            $this->assertNotEmpty($this->git->contributors_view());
            $this->assertNotEmpty($this->git->git());
            $this->assertNotEmpty($this->git->release_view());

        }

        /**
         *
         */
        public function test_tag_size()
        {
            $size = $this->git->release_size();
             $this->assertNotEmpty($size);
             $this->assertEquals(64,$size);
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
            $this->assertEquals(14,$this->git->months()->length());
        }
    }
}