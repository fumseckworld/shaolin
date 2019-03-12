<?php

namespace Imperium\Html\Pagination {


    use Imperium\Versioning\Git\Git;

    class GitPagination
    {
        /**
         * @var Git
         */
        private $git;
        /**
         * @var array
         */
        private $log;

        /**
         * @var int
         */
        private $all;

        public function __construct(Git $git)
        {
           $this->git = $git;

           exec('git log',$this->log);

           $this->all = count($this->log);


        }



    }
}