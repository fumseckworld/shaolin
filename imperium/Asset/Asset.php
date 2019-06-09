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

            if (php_sapi_name() !== 'cli')
                return  https() ?  'https://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'css' .DIRECTORY_SEPARATOR . $filename : 'http://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR. 'css'. DIRECTORY_SEPARATOR . $filename;


            return '<link href="/css/'.$filename.'"  rel="stylesheet" type="text/css">';

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
            if (php_sapi_name() !== 'cli')
                return  https() ?  'https://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR .'js' . DIRECTORY_SEPARATOR . $filename : 'http://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $filename;

            return def($type) ? '<script src="/js/'.$filename.'" type="'.$type.'"></script>' : '<script src="/js/'.$filename.'"></script>';
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
            if (php_sapi_name() !== 'cli')
                return  https() ?  'https://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR . $filename : 'http://' . Request::request()->server->get('HTTP_HOST') . DIRECTORY_SEPARATOR. 'img'. DIRECTORY_SEPARATOR . $filename;

           return '<img src="/img/'.$filename.'" alt="'.$alt.'">';
        }


    }
}