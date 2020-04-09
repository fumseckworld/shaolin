<?php

namespace Eywa\Console\Git {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class GitStats extends Command
    {
        protected static $defaultName = 'git:stats';


        protected function configure(): void
        {
            $this->setDescription('Show git logs by months')
            ->addArgument('author', InputArgument::REQUIRED, 'The commit author name')
            ->addArgument('years', InputArgument::OPTIONAL, 'The years size');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $results = [];


            $years = def($input->getArgument('years')) ? intval($input->getArgument('years')) : 1;
            $first = sprintf(
                'git log --all --numstat --pretty="%s" --author="%s" --since=%d.year',
                '%H',
                strval($input->getArgument('author')),
                $years
            );
            $second = ' | awk \'NF==3 {plus+=$1; minus+=$2} NF==1 {total++} 
            END {printf("lines added: %d\nlines deleted: %d\ntotal commits: %d\n", plus, minus, total)}\'';


            exec(sprintf('%s %s', $first, $second), $log);
            $stats['added'] =   trim(number_format(collect(explode(':', $log[0]))->last()));
            $stats['removed'] =  trim(number_format(collect(explode(':', $log[1]))->last()));
            $stats['commits'] =  trim(number_format(collect(explode(':', $log[2]))->last()));


            array_push($results, $stats);
            $x = new Table($output);
            $x->setStyle('box')->setHeaders(['Added','Removed','Commits'])->setRows($results)->render();

            return 0;
        }
    }
}
