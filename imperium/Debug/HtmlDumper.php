<?php

namespace Imperium\Debug;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as SymfonyHtmlDumper;

class HtmlDumper extends SymfonyHtmlDumper
{
    protected static $themes = array(

        'dark' => array(
            'default' => 'background:none; color:#222; line-height:1.2em; font:12px  Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
            'ellipsis' => 'color:#CC7832',
            'ns' => 'user-select:none;',

            'num' => 'color:#a71d5d',
            'const' => 'color:#795da3',
            'str' => 'color:#df5000',
            'cchr' => 'color:#222',
            'note' => 'color:#a71d5d',
            'ref' => 'color:#a0a0a0',
            'public' => 'color:#795da3',
            'protected' => 'color:#795da3',
            'private' => 'color:#795da3',
            'meta' => 'color:#795da3',
            'key' => 'color:#df5000',
            'index' => 'color:#a71d5d',
        ),
    );
}
