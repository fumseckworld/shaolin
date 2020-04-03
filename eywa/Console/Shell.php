<?php

namespace Eywa\Console {

    use Symfony\Component\Process\Process;

    class Shell
    {
        private Process $process;

        /**
         *
         * Shell constructor.
         *
         * @param string $command
         *
         */
        public function __construct(string $command)
        {
            $this->process = Process::fromShellCommandline($command);
        }

        /**
         * @return Process
         */
        public function get(): Process
        {
            return $this->process;
        }
        /**
         *
         * Run the thell command
         *
         * @param callable|null $callback
         * @param array<mixed> $env
         *
         * @return bool
         *
         */
        public function run(callable $callback = null, array $env = []): bool
        {
            $this->process->run($callback, $env);
            return $this->process->isSuccessful();
        }
    }
}
