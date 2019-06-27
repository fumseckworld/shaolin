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
        $this->assertEquals('<link href="/css/app.css"  rel="stylesheet" type="text/css">',Asset::css('app'));
    }

    /**
     *
     */
    public function test_js()
    {
        $this->assertEquals('<script src="/js/app.js" ></script>',Asset::js('app.js'));
        $this->assertEquals('<script src="/js/app.tsx" type="text/babel"></script>',Asset::js('app.tsx','text/babel'));
    }

    /**
     *
     */
    public function test_img()
    {
        $this->assertEquals('<img src="/img/fumseck.jpg" alt="fumseck">',Asset::img('fumseck.jpg','fumseck'));
    }
}