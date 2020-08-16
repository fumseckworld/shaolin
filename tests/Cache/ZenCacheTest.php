<?php

namespace Testing\Cache;

use Nol\Testing\Unit;

class ZenCacheTest extends Unit
{

    public function test()
    {
        $this->success(
            app('cache')->add('app'),
            app('cache')->clean('app'),
            app('cache')->clearAll()
        );
    }
}
