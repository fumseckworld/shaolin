<?php

namespace Imperium\Asset {

    use Imperium\Request\Request;

    class Asset
    {

        /**
         *
         * Generate a css link
         *
         * @param string $filename
         *
         * @return string
         *
         */
        public static  function css(string $filename): string
        {
            $filename = collection(explode('.',$filename))->begin();

            append($filename,'.css');

            return php_sapi_name() != 'cli' ? https() ? '<link href="https://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'css' .DIRECTORY_SEPARATOR . $filename .'"  rel="stylesheet" type="text/css">': '<link href="http://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'css' .DIRECTORY_SEPARATOR . $filename .'"  rel="stylesheet" type="text/css">': '<link href="/css/'.$filename.'"  rel="stylesheet" type="text/css">';
        }

        /**
         *
         * Generate a js link
         *
         * @param string $filename
         *
         * @param string $type
         *
         * @return string
         */
        public static function js(string $filename,string $type =''): string
        {

            $type = def($type) ? 'type="'.$type.'"' : '';
            return php_sapi_name() != 'cli' ? https() ? '<script src="https://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'js' .DIRECTORY_SEPARATOR . $filename .'" '.$type.'>': '<script src="http://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'js' .DIRECTORY_SEPARATOR . $filename .'"  '.$type.'>': '<script src="/js' .DIRECTORY_SEPARATOR . $filename .'" '.$type.'></script>';

        }

        /**
         *
         * Generate a image link
         *
         * @param string $filename
         * @param string $alt
         *
         * @return string
         *
         */
        public static function img(string $filename,string $alt): string
        {
            return php_sapi_name() != 'cli' ? https() ? '<img src="https://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR . $filename .'" alt="'.$alt.'">': '<img src="http://'. Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR . $filename .'" alt="'.$alt.'">': '<img src="/img' .DIRECTORY_SEPARATOR . $filename .'" alt="'.$alt.'">';
        }

    }
}