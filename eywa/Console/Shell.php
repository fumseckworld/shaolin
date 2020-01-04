<?php


namespace Eywa\Console;


use Traversable;
use Symfony\Component\Process\Process;

class Shell
{
    /**
     * @var array|string[]
     */
    private array $commands;
    /**
     * @var Process
     */
    private Process $process;

    /**
     * Shell constructor.
     * @param string $command
     */
    public function __construct(string $command)
    {
        $this->process = Process::fromShellCommandline($command);
    }

    /**
     *
     * Get the command
     *
     * @return string
     *
     */
    public function command(): string
    {
        return $this->process->getCommandLine();
    }

    /**
     *
     * Get the env values
     *
     * @return array
     *
     */
    public function env(): array
    {
        return $this->process->getEnv();
    }

    /**
     *
     * Set env data
     *
     * @param mixed ...$env
     *
     * @return Shell
     *
     */
    public function set_env(...$env): Shell
    {
        $this->process->setEnv($env);

        return $this;
    }

    /**
     *
     * Set env data
     *
     * @param float $timeout
     *
     * @return Shell
     *
     */
    public function set_idle_timeout(float $timeout): Shell
    {
        $this->process->setIdleTimeout($timeout);

        return $this;
    }

    /**
     *
     * Set env data
     *
     * @param string|int|float|bool|resource|Traversable|null $input
     *
     * @return Shell
     *
     */
    public function set_input($input): Shell
    {
        $this->process->setInput($input);

        return $this;
    }

    /**
     *
     * Set env data
     *
     * @param bool $tty
     * @return Shell
     */
    public function set_tty(bool $tty): Shell
    {
        $this->process->setTty($tty);

        return $this;
    }


    /**
     *
     * Set env data
     *
     * @param float $timeout
     *
     * @return Shell
     *
     */
    public function set_timeout(float $timeout): Shell
    {
        $this->process->setTimeout($timeout);

        return $this;
    }

    /**
     *
     * Set env data
     *
     * @param bool $pty
     * @return Shell
     */
    public function set_pty(bool $pty): Shell
    {
        $this->process->setPty($pty);

        return $this;
    }

    /**
     *
     * Check if is tty mode
     *
     * @return bool
     *
     */
    public function is_tty():bool
    {
        return $this->process->isTty();
    }

    /**
     *
     * Check if is pty mode
     *
     * @return bool
     *
     */
    public function is_pty():bool
    {
        return $this->process->isPty();
    }

    /**
     *
     * @return bool
     *
     */
    public function success()
    {
        return $this->process->isSuccessful();
    }
    /**
     *
     * Run the thell command
     *
     * @param callable|null $callback
     * @param array $env
     *
     * @return bool
     *
     */
    public function run(callable $callback = null, array $env = []): bool
    {
         $this->process->run($callback, $env);
        return $this->success();
    }
}