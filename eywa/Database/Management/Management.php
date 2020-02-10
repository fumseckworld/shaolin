<?php

declare(strict_types=1);
namespace Eywa\Database\Management {


    use Eywa\Exception\Kedavra;

    interface Management
    {

        /**
         *
         * Select a driver
         *
         * @param string $driver
         *
         * @return Management
         *
         * @throws Kedavra
         *
         */
        public function for(string $driver): Management;

        /**
         *
         * Save base to sql file
         *
         * @param string $file
         *
         * @return bool
         *
         */
        public function save(string $file): bool;

        /**
         *
         * Select table to dump
         *
         * @param string ...$tables
         *
         * @return Management
         */
        public function select_table(string ...$tables): Management;

        /**
         *
         * Select base to dump
         *
         * @param string $base
         *
         * @return Management
         *
         */
        public function select_base(string $base): Management;

        /**
         *
         * Add quote
         *
         * @param string $value
         *
         * @return string
         *
         */
        public function quote(string $value): string;
    }
}