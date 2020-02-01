<?php



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

        public function check()
        {
            $this->end();
            d($this->started_time->diffInMilliseconds($this->end_time));
            d($this->end_time - $this->started_time);
        }
    }
}