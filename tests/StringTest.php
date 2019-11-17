<?php


namespace Testing;


use Imperium\App;
use Imperium\Testing\Unit;

class StringTest extends Unit
{

    public function test_equal()
    {
        $this->assertTrue($this->text('a')->equal('a'));
        $this->assertFalse($this->text('a')->equal('b'));
    }

    public function test_take()
    {
        $this->assertEquals('abcdef',$this->text('abcdefghijklmnlop')->take(6)->get());
    }
    public function test_markdown()
    {
        $this->assertNotEmpty($this->text("a")->markdown());
    }
    public function test_split()
    {
        $this->assertNotEmpty($this->text('langage hypertexte, programmation')->split('/[\s,]+/')->all());
    }

    public function test_upper()
    {
        $this->assertEquals('A',$this->text('a')->upper()->get());
    }
    public function test_lower()
    {
        $this->assertEquals('a',$this->text('A')->lower()->get());
    }

    public function test_ucfirst()
    {
        $this->assertEquals('AzertyLol',$this->text('azertyLol')->uc_first()->get());
    }

    public function test_explode()
    {

        $this->assertNotEmpty($this->text('a+b+z+e')->explode('+')->all());

    }
    public function test_pos()
    {
        $this->assertEquals(3,$this->text('abze')->pos('e'));
    }

    public function test_match()
    {
        $this->assertNotEmpty($this->text('abcdef')->match('/^abcdef/')->all());
    }

    public function test_wrap()
    {
        $this->assertEquals('abcdefght',$this->text('abcdefght')->wrap(6)->get());
    }

    public function test_chunk()
    {
        $this->assertNotEmpty($this->text('abcdefght')->chunk(6)->get());
    }

    public function test_different()
    {
        $this->assertTrue($this->text('a')->different('b'));
        $this->assertFalse($this->text('a')->different('a'));
    }

    public function test_addslaches()
    {
        $this->assertNotEmpty($this->text('a')->add_slash()->get());
    }

    public function test_contains()
    {
        $this->assertTrue($this->text('abc')->contains('a'));
        $this->assertFalse($this->text('abc')->contains('z'));
    }

    public function test_crypt()
    {
        $a = 'a';
        $this->assertNotEquals($a,$this->text($a)->encrypt()->get());
        $this->assertEquals($a,$this->text($this->text($a)->encrypt()->get())->decrypt()->get());
    }

    public function test_length()
    {
        $this->assertEquals(5,$this->text('12345')->length());
    }

    public function test_nl2br()
    {
        $this->assertNotEmpty($this->text('i 
        am the boss')->nl2br()->get());
    }

    public function test_repeat()
    {
        $this->assertEquals('aaa',$this->text('a')->repeat(3)->get());
    }
    public function test_hash()
    {
        $a = 'a';
        $this->assertNotEquals($a,$this->text($a)->hash()->get());
        $this->assertEquals($a,$this->text($a)->hash()->decode()->get());
    }
    public function test_word()
    {
        $this->assertEquals(2,$this->text('a b')->words());
    }

    public function test_search()
    {
        $this->assertNotEmpty($this->text('abcdef')->search('a')->get());
    }

    public function test_trim()
    {
        $this->assertEquals('abc',$this->text(' abc')->trim()->get());
    }
    public function test_rtrim()
    {
        $this->assertEquals(' abc',$this->text(' abc ')->rtrim()->get());
    }

    public function test_trim_expanded()
    {
        $this->assertEquals('abc',$this->text(' abc ')->clean()->get());
    }
    public function test_uc_words()
    {
        $this->assertEquals('Abc Loi',$this->text('abc loi')->uc_words()->get());
    }

    public function test_start()
    {
        $this->assertTrue($this->text('abc')->start('a'));
        $this->assertTrue($this->text('zabc')->start('z'));
        $this->assertFalse($this->text('zabc')->start('a'));
        $this->assertFalse($this->text('zabc')->start('c'));
    }

    public function test_shuffle()
    {
        $this->assertNotEquals('abcd',$this->text('abcd')->shuffle()->get());

    }

    public function test_lcfirst()
    {
        $this->assertNotEquals('Abcd',$this->text('abcd')->lc_first()->get());

    }
    public function test_refresh()
    {
        $this->assertEquals('Abcd',$this->text('abcd')->refresh('a','A')->get());
    }

    public function test_quote()
    {
        $this->assertNotEmpty($this->text("a'azae'abe'b")->refresh('a','A')->quote()->get());
    }
   
}