<?php


namespace Testing;


use Imperium\Exception\Kedavra;
use Imperium\File\File;
use Imperium\Testing\Unit;
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
        $this->assertNotEmpty($this->file('config.yaml')->keys(':'));
        $this->assertNotEmpty($this->file('config.yaml')->values(':'));
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
    public function test_json()
   {
       $this->assertTrue($this->file('file.json')->to_json(['id'=> 8]));
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
       $this->assertTrue($this->file('alexandra')->remove());
   }

    /**
     *
     */
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
       $this->assertNotEmpty($this->file('app.json',READ_AND_WRITE_FILE_MODE)->truncate(5));
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

       $this->assertNotEmpty($this->file('grumphp.yml')->infos()->collection());
   }

    /**
     * @throws Kedavra
     */
   public function test_count_lines()
   {

       $this->assertIsInt($this->file('app.json')->count_lines());
       $this->assertEquals(1,$this->file('app.json')->count_lines());
   }

    /**
     * @throws Kedavra
     */
   public function test_to()
   {
       $file =$this->file('grumphp.yml');

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
       $this->assertFalse($this->file('grumphp.yml')->is_dir());
       $this->assertFalse($this->file('grumphp.yml')->is_link());
       $this->assertTrue($this->file('grumphp.yml')->is_file());
   }

    /**
     * @throws Kedavra
     */
    public function test_download()
   {
       $this->assertTrue($this->file('app.json')->download()->isSuccessful());
       $this->assertTrue(app()->download('app.json')->isSuccessful());
   }
    /**
     * @throws Kedavra
     */
   public function test_size()
   {
       $this->assertIsInt($this->file('grumphp.yml')->size());
   }

   public function test_search()

   {
       $this->assertNotEmpty(File::search('*.md'));
   }
    /**
     * @throws Kedavra
     */
   public function test_file()
   {
       $this->assertTrue($this->file('grumphp.yml')->writable());
       $this->assertFalse($this->file('grumphp.yml')->executable());
       $this->assertTrue($this->file('grumphp.yml')->readable());
       $this->assertNotEmpty($this->file('grumphp.yml')->type());
       $this->assertIsInt($this->file('grumphp.yml')->perms());
       $this->assertEquals('grumphp.yml',$this->file('grumphp.yml')->name());
       $this->assertEquals('yml',$this->file('grumphp.yml')->ext());
       $this->assertStringNotContainsString('grumphp.yml',$this->file('grumphp.yml')->base());
       $this->assertStringContainsString('grumphp.yml',$this->file('grumphp.yml')->base_name());
       $this->assertNotEmpty($this->file('grumphp.yml')->absolute_path());
   }
    /**
     * @throws Kedavra
     */
   public function test_file_change_values()
   {
       $keys = $this->file('config.yaml')->keys();

       $this->assertTrue($this->file('config.yaml',EMPTY_AND_WRITE_FILE_MODE)->change_values($keys,['League of legend','false']));
       $this->assertTrue(update_file_values('config.yaml',':','League of legend','true'));
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
    public function test_copy()
   {
        $this->assertTrue($this->file('.env.example')->copy('.env'));
        $this->assertTrue($this->file('.env')->remove());

        $this->assertTrue($this->file('.env.example')->copy('.env'));
   }
    /**
     * @throws Kedavra
     */
   public function test_path()
   {
       $this->assertIsString($this->file('grumphp.yml')->path());
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