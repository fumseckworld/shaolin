<?php
    
namespace Eywa\Console\App
{
    use Eywa\Console\Shell;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class AppSend extends Command
    {
        protected static $defaultName = 'app:send';


        protected function configure(): void
        {
            $this->setDescription('Send the application to the server');
        }
            
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->success('Starting all tests');
            $x = new Shell(base('vendor', 'bin', 'grumphp') . ' run');
            if ($x->run()) {
                $io->success('Congratulations no errors has been found');

                if (is_dir('.git')) {
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
                    return 0;
                } else {
                    $io->error('We have not found git');
                    return 1;
                }
            }
            $io->error($x->get()->getOutput());
            return 1;
        }
    }
}
