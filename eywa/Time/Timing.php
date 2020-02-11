<?php


declare(strict_types=1);

namespace Eywa\Time {


    use Carbon\Carbon;

    class Timing
    {

        /**
         *
         *
         */
        private Carbon $started_time;
        /**
         *
         *
         */
        private Carbon $end_time;

        public function __construct()
        {
            $this->started_time = now();
        }

        public function end()
        {
            $this->end_time = now();
        }

        public function check():int
        {
            $this->end();

            return $this->end_time->diffInMilliseconds($this->started_time);
        }
    }
}