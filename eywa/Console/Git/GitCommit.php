<?php

namespace Eywa\Console\Git;

use Eywa\Console\Shell;
use Eywa\Exception\Kedavra;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class GitCommit extends Command
{
    protected static $defaultName = 'git:commit';


    protected function configure(): void
    {
        $this->setDescription('run git commit interative command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Kedavra
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('Running all tests');
        $x = new Shell(base('vendor', 'bin', 'grumphp') . ' run');
        if ($x->run()) {
            if ($io->confirm('Run git add ? ', true)) {
                if ((new Shell('git add .'))->run()) {
                    $io->success('Modification has been added');
                } else {
                    $io->error('Git add has failed');
                    return 1;
                }
            }
            do {
                $commit =  $io->askQuestion(
                    (new Question(
                        'Enter your commit message : ',
                        ''
                    ))->setAutocompleterValues(
                        [
                            'build:',
                            'ci:',
                            'core:',
                            'docs:',
                            'feat:',
                            'fix:',
                            'perf:',
                            'refactor:',
                            'revert:',
                            'design:',
                            'test:'
                        ]
                    )
                );
            } while (is_null($commit) || sum($commit) < 0 || sum($commit) > 60);

            if ((new Shell(sprintf("git commit -m '%s'", $commit)))->run()) {
                $io->success('Commit has been send');
            } else {
                $io->error('Git commit has failed');
                return 1;
            }
        } else {
            $io->error($x->get()->getOutput());
            return 1;
        }
        return 0;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('Bye');

        return 0;
    }
}
