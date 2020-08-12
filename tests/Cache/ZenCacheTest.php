<?php

namespace Testing\Cache;

use Imperium\Testing\Unit;

class ZenCacheTest extends Unit
{

    public function test()
    {
        $this->success(
            app('cache')->cache('app'),
            app('cache')->clean('app'),
            app('cache')->clearAll()
        );
    }
}
