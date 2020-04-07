<?php

namespace Eywa\Console\Database {

    use Eywa\Database\Convert\BaseConverter;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Convert extends Command
    {
        protected static $defaultName = 'db:convert';

        /**
         */
        protected function configure(): void
        {
            $this->setDescription('Convert a database');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            return (new BaseConverter($io))->choose()->password()->select()->configure()->init()->create()->success();
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            return 0;
        }
    }
}
