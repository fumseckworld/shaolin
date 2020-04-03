<?php

namespace Eywa\Console\Git {

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

                if ($io->confirm('Do you want send the new commits ?', true)) {
                    $io->success('Sending the application');

                    $remotes  = [];

                    exec('git remote -v', $remotes);

                    if (not_def($remotes)) {
                        $io->error('No server remote has been found');
                        return 1;
                    }

                    $all = collect();

                    foreach ($remotes as $remote) {
                        $x = collect(explode("\t", $remote));
                        $name = $x->first();
                        $url = collect(explode(' ', $x->get(1)))->first();

                        $host = collect(explode(':', collect(explode('@', $url))->last()))->first();

                        if ($all->hasNot($name)) {
                            $all->put($name, $host);
                        }
                    }

                    foreach ($all->all() as $name => $url) {
                        $io->warning(sprintf('Sending the application to %s', $url));

                        if ((new Shell(sprintf('git push %s --all && git push %s --tags', $name, $name)))->run()) {
                            $io->success(sprintf('The repository hosted at %s has been updated successfully', $url));
                        } else {
                            $io->error('Please make sure you have the correct access rights and the repository exists');
                            return 1;
                        }
                    }

                    $io->warning('End of all remote servers found');
                    $io->success('All remote servers has been updated successfully');
                } else {
                    return 0;
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
}
