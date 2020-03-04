<?php


namespace Testing\File {

    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Testing\Unit;
    use SplFileObject;

    /**
     * Class FileTest
     * @package Testing
     */
    class FileTest extends Unit
    {


        /**
         * @throws Kedavra
         */
        public function test_key_and_values()
        {
            $this->assertTrue($this->file('file.yaml',EMPTY_AND_WRITE_FILE_MODE)->write("a:b\nb:c")->flush());
            $this->assertNotEmpty($this->file('file.yaml')->keys(':'));
            $this->assertNotEmpty($this->file('file.yaml')->values(':'));
            $this->assertTrue(unlink('file.yaml'));
        }
        /**
         * @throws Kedavra
         */
        public function test_exception()
        {
            $this->expectException(Kedavra::class);
            $this->file("index.php",'azerty');
        }

        /**
         * @throws Kedavra
         */
        public function test_markdown()
        {
            $this->assertNotEmpty($this->file('README.md')->markdown());
        }

        /**
         * @throws Kedavra
         */
        public function test_json()
        {
            $this->assertTrue($this->file('bidon/file.json',EMPTY_AND_WRITE_FILE_MODE)->to_json(['id'=> 8]));
        }

        /**
         * @throws Kedavra
         */
        public function test_tell()
        {
            $this->assertIsInt($this->file('README.md')->tell());
            $this->assertEquals(0,$this->file('README.md')->tell());
        }

        /**
         * @throws Kedavra
         */
        public function test_max()
        {
            $this->assertEquals(2,$this->file('README.md')->set_max(2)->max());
        }

        /**
         * @throws Kedavra
         */
        public function test_create_not_exist()
        {
            $this->assertTrue($this->file('bidon/alexandra')->remove());
        }

        public function test_multiples()
        {
            $file = 'a.php';
            $this->assertTrue(File::create($file));
            $this->assertTrue(File::exist($file));
            $this->assertTrue(File::remove_if_exist($file));
        }


        /**
         * @throws Kedavra
         */
        public function test_flag()
        {
            $this->assertEquals(SplFileObject::DROP_NEW_LINE,$this->file('README.md')->flag(SplFileObject::DROP_NEW_LINE)->flags());
        }

        /**
         * @throws Kedavra
         */
        public function test_parse()
        {
            $this->assertNotEmpty($this->file('README.md')->parse('%s'));
        }

        /**
         * @throws Kedavra
         */
        public function test_truncate()
        {
            $this->assertNotEmpty($this->file('bidon/app.json',READ_AND_WRITE_FILE_MODE)->truncate(5));
        }

        /**
         * @throws Kedavra
         */
        public function test_get_content()
        {
            $this->assertNotEmpty($this->file(__FILE__)->read());
            $this->assertStringContainsString('<?php',$this->file(__FILE__)->read());
        }

        /**
         * @throws Kedavra
         */
        public function test_lines()
        {
            $file = $this->file('README.md');

            $file->rewind();
            while ($file->valid())
            {
                $this->assertIsInt($file->current_line());
                $this->assertIsString($file->current());
                $this->assertIsString($file->line());
                $this->assertIsString($file->char());
                $file->next();
            }

            $this->assertTrue($file->eof());
        }

        /**
         * @throws Kedavra
         */
        public function test_infos()
        {
            $this->assertNotEmpty($this->file('README.md')->infos()->all());
        }

        /**
         * @throws Kedavra
         */
        public function test_count_lines()
        {

            $this->assertIsInt($this->file('bidon/app.json')->count_lines());
            $this->assertEquals(1,$this->file('bidon/app.json')->count_lines());
        }

        /**
         * @throws Kedavra
         */
        public function test_to()
        {
            $file =$this->file('README.md');

            $lines = $file->count_lines();
            $this->assertEquals(0,$file->rewind()->current_line());

            for($i = 0;$i!=$lines;$i++)
                $this->assertIsInt($file->to($i)->current_line());

        }

        /**
         * @throws Kedavra
         */
        public function test_dir()
        {
            $this->assertFalse($this->file('README.md')->is_dir());
            $this->assertFalse($this->file('README.md')->is_link());
            $this->assertTrue($this->file('README.md')->is_file());
          
        }


        /**
         * @throws Kedavra
         */
        public function test_size()
        {
            $this->assertIsInt($this->file('README.md')->size());
        }

        /**
         *
         */
        public function test_search()
        {
            $this->assertNotEmpty(File::search('*.md'));
        }
        
        /**
         * @throws Kedavra
         */
        public function test_file()
        {
            $this->assertTrue($this->file('README.md')->writable());
            $this->assertFalse($this->file('README.md')->executable());
            $this->assertTrue($this->file('README.md')->readable());
            $this->assertNotEmpty($this->file('README.md')->type());
            $this->assertIsInt($this->file('README.md')->perms());
            $this->assertEquals('README.md',$this->file('README.md')->name());
            $this->assertEquals('md',$this->file('README.md')->ext());
            $this->assertStringNotContainsString('README.md',$this->file('README.md')->base());
            $this->assertStringContainsString('README.md',$this->file('README.md')->base_name());
            $this->assertNotEmpty($this->file('README.md')->absolute_path());
        }
        /**
         * @throws Kedavra
         */
        public function test_file_change_values()
        {

            $this->assertTrue($this->file('bidon/config.yaml.yaml',EMPTY_AND_WRITE_FILE_MODE)->write("game:lol\nsuccess:false")->flush());

            $keys = $this->file('bidon/config.yaml.yaml')->keys();
            $this->assertTrue($this->file('bidon/config.yaml.yaml',EMPTY_AND_WRITE_FILE_MODE)->change_values($keys,['League of legend','false']));
            $this->assertTrue(update_file_values('bidon/config.yaml.yaml',':','League of legend','true'));
        }

        /**
         * @throws Kedavra
         */
        public function test_move()
        {

            $this->assertTrue(touch('app.php'));
            $this->assertTrue($this->file('app.php')->move('web'));
            $this->assertFalse(file_exists('app.php'));
            $this->assertTrue($this->file('web/app.php')->remove());
            $this->assertTrue(touch('app.php'));

            $this->assertTrue($this->file("app.php")->rename('apps.php'));
            $this->assertTrue($this->file("apps.php")->remove());

        }




        /**
         * @throws Kedavra
         */
        public function test_path()
        {
            $this->assertIsString($this->file('README.md')->path());
        }

        /**
         * @throws Kedavra
         */
        public function test_copy()
        {
            $this->assertTrue($this->file('README.md')->copy('TODO.md'));
            $this->assertTrue(file_exists('TODO.md'));
            $this->assertNotEmpty($this->file('TODO.md')->read());
            $this->assertTrue($this->file('TODO.md')->remove());
        }
        /**
         * @throws Kedavra
         */
        public function test_write()
        {
            $this->assertTrue(touch('app.php'));
            $this->assertTrue($this->file('app.php',READ_AND_WRITE_FILE_MODE)->write_line("<?php")->write_line("\trequire '../vendor/autoload.php';")->write_line("\tapp()->run();")->flush());
            $this->assertNotEmpty($this->file('app.php')->lines());
            $this->assertCount(4,$this->file('app.php')->lines());
            $this->assertEquals(4,$this->file('app.php')->count_lines());
            $this->assertTrue($this->file('app.php')->remove());
        }



    }
}