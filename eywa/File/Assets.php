<?php


namespace Eywa\File {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;

    class Assets
    {

        /**
         *
         * The type filename
         *
         */
        private string $filename;
        /**
         *
         * The type of balise to create
         *
         */
        private string $type;

        /**
         *
         * Assets constructor.
         *
         * @param string $type
         * @param string $filename
         *
         * @throws Kedavra
         */
        public function __construct(string $type, string $filename)
        {
            not_in(['css','js'],$type,true,'The type must be css or js');

            $this->filename = $filename;
            $this->type = $type;
        }

        /**
         *
         * Genrate the link
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function make():string
        {
            switch ($this->type)
            {
                case 'css':
                    return sprintf('<link rel="stylesheet" href="%s">',$this->css());
                case 'js':
                    return sprintf('<script src="%s"></script>',$this->js());
                default:
                    return '';
            }
        }

        /**
         *
         *
         * Generate js url
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        private function js(): string
        {
            return https() ? 'https://'. Request::make()->server()->get('SERVER_NAME') . '/js/' . $this->filename :   'http://'. Request::make()->server()->get('SERVER_NAME') . '/js/' . $this->filename;
        }

        /**
         *
         * Generate css url
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        private function css(): string
        {
            return https() ? 'https://'. Request::make()->server()->get('SERVER_NAME') . '/css/' . $this->filename :   'http://'. Request::make()->server()->get('SERVER_NAME') . '/css/' . $this->filename;
        }


    }
}