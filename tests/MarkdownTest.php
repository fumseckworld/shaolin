<?php
	
	namespace Testing;
	
	use Imperium\Markdown\Markdown;
	use PHPUnit\Framework\TestCase;
	
	class MarkdownTest extends TestCase
	{
	
		public function test()
		{
			$this->assertNotEmpty((new Markdown('README.md'))->markdown());
			$this->assertNotEmpty((new Markdown('','#Why ?'))->markdown());
		}
	}