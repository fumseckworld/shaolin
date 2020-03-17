<?php


declare(strict_types=1);

namespace Eywa\Time {


    use Carbon\Carbon;

    class Timing
    {

        /**
         *
         * The starter time
         *
         */
        private Carbon $started_time;

        /**
         *
         * The end of time
         *
         */
        private Carbon $end_time;

        /**
         *
         * Timing constructor.
         *
         */
        public function __construct()
        {
            $this->started_time = now();
        }

        /**
         *
         * Get the difference time in ms
         *
         * @return int
         *
         */
        public function check():int
        {
            $this->end_time = now();

            return $this->end_time->diffInMilliseconds($this->started_time);
        }
    }
}
