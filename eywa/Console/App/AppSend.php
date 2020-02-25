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
			
			protected function configure()
			{
				$this->setDescription('Send the application to the server');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				$io = new SymfonyStyle($input,$output);



                if (is_dir('.git'))
                {
                    $io->success('Sending the application');

                    $remotes  = [];

                    exec('git remote -v',$remotes);

                    if (not_def($remotes))
                    {
                        $io->error('No server remote has been found');
                        return 1;
                    }
                    $all = collect();


                    foreach ($remotes as $remote)
                    {
                       $x = collect(explode("\t",$remote));
                       $name = $x->first();
                       $url = collect(explode(' ',$x->get(1)))->first();
                       if ($all->has_not($name))
                           $all->put($name,$url);
                    }

                    foreach ($all->all() as $name => $url)
                    {

                        $io->section("Sending the app at the remote server : $url");
                        if((new Shell("git push $name --all && git push $name --tags"))->run())
                        {
                            $io->success("The remote server called $name has been updated successfully");
                        }else{
                            $io->error("Please make sure you have the correct access rights and the repository exists");
                            return 1;
                        }

                    }

                    $io->success('All remote server was updated successfully');
                    return 0;

                }else{
                    $io->error('We have not found git');
                    return 1;
                }



            }
			
		}
	}
