<?php


namespace Testing;


use Imperium\Asset\Asset;
use PHPUnit\Framework\TestCase;

/**
 * Class AssetsTest
 * @package Testing
 */
class AssetsTest extends TestCase
{

    /**
     *
     */
    public function test_css()
    {
        $this->assertEquals('<link href="/css/app.css"  rel="stylesheet" type="text/css">',app()->assets('app')->css());
    }

    /**
     *
     */
    public function test_js()
    {
        $this->assertEquals('<script src="/js/app.js" ></script>',app()->assets('app.js')->js());
        $this->assertEquals('<script src="/js/app.tsx" type="text/babel"></script>',app()->assets('app.tsx')->js('text/babel'));
    }

    /**
     *
     */
    public function test_img()
    {
        $this->assertEquals('<img src="/img/fumseck.jpg" alt="fumseck">',app()->assets('fumseck.jpg')->img('fumseck'));
    }
}