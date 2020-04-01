<?php

declare(strict_types=1);

namespace Eywa\Detection {

    use Mobile_Detect;
    use Sinergi\BrowserDetector\Browser;
    use Sinergi\BrowserDetector\Device;
    use Sinergi\BrowserDetector\Os;

    class Detect
    {

        /**
         *
         * Detect the os
         *
         * @return string
         *
         */
        public function os(): string
        {
            return (new Os())->getName();
        }

        /**
         *
         * CHeck if the os is a mobile
         *
         * @return bool
         *
         */
        public function mobile(): bool
        {
            return (new Mobile_Detect())->isMobile();
        }

        /**
         *
         * CHeck if the os is a tablet
         *
         * @return bool
         *
         */
        public function tablet(): bool
        {
            return (new Mobile_Detect())->isTablet();
        }


        /**
         *
         * CHeck if the os is a desktop
         *
         * @return bool
         *
         */
        public function desktop(): bool
        {
            return ! $this->mobile() && ! $this->tablet();
        }

        /**
         *
         * Check if the os is linux
         *
         * @return bool
         *
         */
        public function linux(): bool
        {
            return $this->os() === Os::LINUX;
        }

        /**
         *
         * Check if the os is windows
         *
         * @return bool
         *
         */
        public function windows(): bool
        {
            return $this->os() === Os::WINDOWS;
        }

        /**
         *
         * Check if the os is osx
         *
         * @return bool
         *
         */
        public function osx(): bool
        {
            return $this->os() === Os::OSX;
        }

        /**
         *
         * Check if the os is ios
         *
         * @return bool
         *
         */
        public function ios(): bool
        {
            return $this->os() === Os::IOS;
        }

        /**
         *
         * Check if the os is android
         *
         * @return bool
         *
         */
        public function android(): bool
        {
            return $this->os() === Os::ANDROID;
        }


        /**
         *
         * Check if the os is blackberry
         *
         * @return bool
         *
         */
        public function blackberry(): bool
        {
            return $this->os() === Os::BLACKBERRY;
        }

        /**
         *
         * Check if the os is chrome os
         *
         * @return bool
         *
         */
        public function chromeOs(): bool
        {
            return $this->os() === Os::CHROME_OS;
        }

        /**
         *
         * Check if the os is nokia
         *
         * @return bool
         *
         */
        public function nokia(): bool
        {
            return $this->os() === Os::NOKIA;
        }

        /**
         *
         * Check if the os is symbos
         *
         * @return bool
         *
         */
        public function symbos(): bool
        {
            return $this->os() === Os::SYMBOS;
        }

        /**
         *
         * Check if the os is freebsd
         *
         * @return bool
         *
         */
        public function freebsd(): bool
        {
            return $this->os() === Os::FREEBSD;
        }

        /**
         *
         * Check if the os is openbsd
         *
         * @return bool
         *
         */
        public function openbsd(): bool
        {
            return $this->os() === Os::OPENBSD;
        }

        /**
         *
         * Check if the os is netbsd
         *
         * @return bool
         *
         */
        public function netbsd(): bool
        {
            return $this->os() === Os::NETBSD;
        }

        /**
         *
         * Check if the os is opensolaris
         *
         * @return bool
         *
         */
        public function opensolaris(): bool
        {
            return $this->os() === Os::OPENSOLARIS;
        }

        /**
         *
         * Check if the os is sunos
         *
         * @return bool
         *
         */
        public function sunos(): bool
        {
            return $this->os() === Os::SUNOS;
        }


        /**
         *
         * Check if the os is os2
         *
         * @return bool
         *
         */
        public function os2(): bool
        {
            return $this->os() === Os::OS2;
        }

        /**
         *
         * Check if the os is beos
         *
         * @return bool
         *
         */
        public function beos(): bool
        {
            return $this->os() === Os::BEOS;
        }

        /**
         *
         * Check if the os is beos
         *
         * @return bool
         *
         */
        public function unknown(): bool
        {
            return $this->os() === Os::UNKNOWN;
        }

        /**
         *
         * Get the browser name
         *
         * @return string
         *
         */
        public function browser(): string
        {
            return (new Browser())->getName();
        }

        /**
         *
         *
         * Check if the browser is vivaldi
         *
         * @return bool
         *
         */
        public function vivaldi(): bool
        {
            return $this->browser() === Browser::VIVALDI;
        }

        /**
         *
         *
         * Check if the browser is opera
         *
         * @return bool
         *
         */
        public function opera(): bool
        {
            return $this->browser() === Browser::OPERA;
        }

        /**
         *
         *
         * Check if the browser is opera mini
         *
         * @return bool
         *
         */
        public function operaMini(): bool
        {
            return $this->browser() === Browser::OPERA_MINI;
        }

        /**
         *
         *
         * Check if the browser is WebTV
         *
         * @return bool
         *
         */
        public function webTv(): bool
        {
            return $this->browser() === Browser::WEBTV;
        }

        /**
         *
         *
         * Check if the browser is ie
         *
         * @return bool
         *
         */
        public function ie(): bool
        {
            return $this->browser() === Browser::IE;
        }

        /**
         *
         *
         * Check if the browser is ie
         *
         * @return bool
         *
         */
        public function pocketIe(): bool
        {
            return $this->browser() === Browser::POCKET_IE;
        }

        /**
         *
         *
         * Check if the browser is konqueror
         *
         * @return bool
         *
         */
        public function konqueror(): bool
        {
            return $this->browser() === Browser::KONQUEROR;
        }


        /**
         *
         *
         * Check if the browser is icab
         *
         * @return bool
         *
         */
        public function icab(): bool
        {
            return $this->browser() === Browser::ICAB;
        }

        /**
         *
         *
         * Check if the browser is omniweb
         *
         * @return bool
         *
         */
        public function omniweb(): bool
        {
            return $this->browser() === Browser::OMNIWEB;
        }

        /**
         *
         *
         * Check if the browser is firebird
         *
         * @return bool
         *
         */
        public function firebird(): bool
        {
            return $this->browser() === Browser::FIREBIRD;
        }

        /**
         *
         *
         * Check if the browser is firefox
         *
         * @return bool
         *
         */
        public function firefox(): bool
        {
            return $this->browser() === Browser::FIREFOX;
        }

        /**
         *
         *
         * Check if the browser is firefox
         *
         * @return bool
         *
         */
        public function seamonkey(): bool
        {
            return $this->browser() === Browser::SEAMONKEY;
        }

        /**
         *
         *
         * Check if the browser is iceweasel
         *
         * @return bool
         *
         */
        public function iceweasel(): bool
        {
            return $this->browser() === Browser::ICEWEASEL;
        }

        /**
         *
         *
         * Check if the browser is shiretoko
         *
         * @return bool
         *
         */
        public function shiretoko(): bool
        {
            return $this->browser() === Browser::SHIRETOKO;
        }


        /**
         *
         *
         * Check if the browser is mozilla
         *
         * @return bool
         *
         */
        public function mozilla(): bool
        {
            return $this->browser() === Browser::MOZILLA;
        }

        /**
         *
         *
         * Check if the browser is amaya
         *
         * @return bool
         *
         */
        public function amaya(): bool
        {
            return $this->browser() === Browser::AMAYA;
        }

        /**
         *
         *
         * Check if the browser is lynx
         *
         * @return bool
         *
         */
        public function lynx(): bool
        {
            return $this->browser() === Browser::LYNX;
        }

        /**
         *
         *
         * Check if the browser is safari
         *
         * @return bool
         *
         */
        public function safari(): bool
        {
            return $this->browser() === Browser::SAFARI;
        }

        /**
         *
         *
         * Check if the browser is samsung
         *
         * @return bool
         *
         */
        public function samsungBrowser(): bool
        {
            return $this->browser() === Browser::SAMSUNG_BROWSER;
        }

        /**
         *
         *
         * Check if the browser is samsung
         *
         * @return bool
         *
         */
        public function chrome(): bool
        {
            return $this->browser() === Browser::CHROME;
        }

        /**
         *
         *
         * Check if the browser is samsung
         *
         * @return bool
         *
         */
        public function googlebot(): bool
        {
            return $this->browser() === Browser::GOOGLEBOT;
        }

        /**
         *
         *
         * Check if the browser is ice cat
         *
         * @return bool
         *
         */
        public function icecat(): bool
        {
            return $this->browser() === Browser::ICECAT;
        }

        /**
         *
         *
         * Check if the browser is edge
         *
         * @return bool
         *
         */
        public function edge(): bool
        {
            return $this->browser() === Browser::EDGE;
        }

        /**
         * @return string
         */
        public function device(): string
        {
            return (new Device())->getName();
        }

        /**
         *
         * Check if the device is ipad
         *
         * @return bool
         *
         */
        public function ipad(): bool
        {
            return $this->device() === Device::IPAD;
        }


        /**
         *
         * Check if the device is iphone
         *
         * @return bool
         *
         */
        public function iphone(): bool
        {
            return $this->device() === Device::IPHONE;
        }

        /**
         *
         * Check if the device is Windows Phone
         *
         * @return bool
         *
         */
        public function windowsPhone(): bool
        {
            return $this->device() === Device::WINDOWS_PHONE;
        }
    }
}
