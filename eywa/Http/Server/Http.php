<?php


namespace Eywa\Http\Server {


    use Eywa\Console\Shell;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Http
    {

        /**
         *
         * The console output
         *
         */
        private SymfonyStyle $io;

        /**
         *
         * The command instance
         *
          */
        private Shell $http;

        /**
         *
         * The server url
         *
         */
        private string $url;

        /**
         *
         * Http constructor.
         *
         * @param SymfonyStyle $io
         * @param string $directory
         * @param int $port
         */
        public function __construct(SymfonyStyle $io, string $directory = 'web', int $port = 3000)
        {
            $this->url = "http://localhost:$port";

            $this->http = new Shell(sprintf('php -S 0.0.0.0:%d -t %s', $port, $directory));

            $this->io = $io;
        }

        /**
         * @return int
         */
        public function run():int
        {
            $this->io->title('Starting the development server');
            $this->io->success(sprintf('The server is running and listen at %s', $this->url));
            $this->http->get()->setTimeout(null)->run();
            return 0;
        }
    }
}
