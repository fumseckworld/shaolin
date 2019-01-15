<?php

namespace Imperium\I18n {


    use Exception;
    use Imperium\Directory\Dir;
    use Twig_Environment;
    use Twig_Extensions_Extension_I18n;

    class Language
    {
        /**
         * @var Twig_Environment
         */
        private $environment;
        /**
         * @var string
         */
        private $locale_path;
        /**
         * @var string
         */
        private $locale;
        /**
         * @var string
         */
        private $domain;

        /**
         * Language constructor.
         *
         * @param Twig_Environment $environment
         * @param string $locale_path
         * @param string $locale
         * @param string $domain
         *
         * @throws Exception
         */
        public function __construct(Twig_Environment $environment,string $locale_path,string $locale,string $domain)
        {
            Dir::create($locale_path);

            $this->environment = $environment;
            $this->locale_path = realpath($locale_path);
            $this->locale = $locale;
            $this->domain = $domain;
        }


        /**
         *
         * Set the locale
         *
         * @param string $locale
         *
         * @return Language
         *
         * @throws Exception
         *
         */
        public function set(string $locale): Language
        {
            return new static($this->environment(),$this->locale_path(),$locale,$this->domain());
        }


        /***
         *
         * Translate the app
         *
         * @return Language
         *
         */
        public function translate(): Language
        {

            putenv("LANG={$this->current()}");
            setlocale(LC_ALL,$this->current());
            bindtextdomain($this->domain(),$this->locale_path());
            textdomain($this->domain());
            bind_textdomain_codeset($this->domain(),'UTF-8');
            $this->environment->addExtension(new Twig_Extensions_Extension_I18n());

            return $this;
        }

        /**
         *
         * Return the domain
         *
         * @return string
         *
         */
        public function domain(): string
        {
            return $this->domain;
        }

        /**
         *
         * Return the locale path
         *
         * @return string
         *
         */
        public function locale_path(): string
        {
            return $this->locale_path;
        }


        /**
         * @return Twig_Environment
         */
        public function environment(): Twig_Environment
        {
            return $this->environment;
        }

        /**
         *
         * Return the current locale
         *
         * @return string
         *
         */
        public function current(): string
        {
            return $this->locale;
        }

    }
}