<?php

namespace Imperium\Asset {


    use Symfony\Component\HttpFoundation\Request;

    class Asset
    {

        /**
         * @var string
         */
        private $filename;

        /**
         * @var Request
         */
        private $request;

        public function __construct(string $filename)
        {
            $this->filename = $filename;
            $this->request = request();
        }

        /**
         *
         * Generate a css link
         *
         * @return string
         *
         */
        public function css(): string
        {
            $filename = collect(explode('.',$this->filename))->first();

            append($filename,'.css');

            return php_sapi_name() != 'cli' ? https() ? '<link href="https://'. $this->request->getHost() . DIRECTORY_SEPARATOR . 'css' .DIRECTORY_SEPARATOR . $filename .'"  rel="stylesheet" type="text/css">': '<link href="http://'. $this->request->getHost() . DIRECTORY_SEPARATOR . 'css' .DIRECTORY_SEPARATOR . $filename .'"  rel="stylesheet" type="text/css">': '<link href="/css/'.$filename.'"  rel="stylesheet" type="text/css">';
        }

        /**
         *
         * Generate a js link
         *
         * @param string $type
         *
         * @return string
         */
        public function js(string $type =''): string
        {

            $type = def($type) ? 'type="'.$type.'"' : '';
            return php_sapi_name() != 'cli' ? https() ? '<script src="https://'. $this->request->getHost() . DIRECTORY_SEPARATOR . 'js' .DIRECTORY_SEPARATOR . $this->filename .'" '.$type.'></script>': '<script src="http://'.$this->request->getHost() . DIRECTORY_SEPARATOR . 'js' .DIRECTORY_SEPARATOR . $this->filename .'"  '.$type.'></script>': '<script src="/js' .DIRECTORY_SEPARATOR . $this->filename .'" '.$type.'></script>';

        }

        /**
         *
         * Generate a image link
         *
         * @param string $alt
         *
         * @return string
         */
        public function img(string $alt): string
        {
            return php_sapi_name() != 'cli' ? https() ? '<img src="https://'. $this->request->getHost() . DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR . $this->filename .'" alt="'.$alt.'">': '<img src="http://'. $this->request->getHost() . DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR . $this->filename .'" alt="'.$alt.'">': '<img src="/img' .DIRECTORY_SEPARATOR . $this->filename .'" alt="'.$alt.'">';
        }

    }
}