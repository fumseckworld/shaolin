<?php

    namespace Imperium\Versioning;

    use Imperium\Collection\Collect;
    use Imperium\Exception\Kedavra;
    use Imperium\Html\Pagination\Pagination;
    use Imperium\Redis\Redis;

    class Git
    {
        /**
         *
         * Instance of redis
         *
         * @var Redis
         *
         */
        private $redis;

        /**
         *
         * The repository name
         *
         * @var string
         *
         */
        private $repository;

        /**
         *
         * The repository owner
         *
         * @var string
         *
         */
        private $owner;
        /**
         * @var array
         */
        private $data;

        /**
         * Git constructor.
         *
         * @param string $repository
         * @param string $owner
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $repository,string $owner)
        {
            is_false(is_dir($repository),true,"Repository is not a directory");

            is_false(chdir($repository),true,"Checkout in repository fail");

            $this->redis = new Redis();

            $this->repository =  collect(explode(DIRECTORY_SEPARATOR,realpath($repository)))->last();

            $this->owner = $owner;

        }

        /**
         *
         * Get the repository owner
         *
         * @return string
         *
         */
        public function owner(): string
        {
            return  $this->owner;
        }

        /**cd
         *
         * Get the repository name
         *
         * @return string
         *
         */
        public function name():string
        {
            return $this->repository;
        }

        /**
         *
         *
         * Display the status
         *
         * @return string
         *
         */
        public function status(): string
        {
           return nl2br(html_entity_decode($this->execute('git status | aha')->join("\n")));
        }

        /**
         *
         * Show diff between two version or from head
         *
         * @param string $first_version
         * @param string $second_version
         *
         * @return string
         *
         */
        public function diff(string $first_version ='',string $second_version=''): string
        {
            return def($first_version,$second_version) ?  html_entity_decode($this->execute("git diff $first_version $second_version -p --stat --color=always | aha  ")->join("\n")) : nl2br(html_entity_decode($this->execute('git diff -p --stat --color=always | aha')->join("\n")));
        }

        /**
         * @param string $branch
         * @return int
         */
        public function commits_size(string $branch): int
        {
            return  $this->execute("git rev-list --count $branch")->get(0);
        }

        /**
         *
         * Display all branches
         *
         * @return array
         *
         */
        public function branches(): array
        {
            $x = collect();

            foreach ($this->execute('git branch') as $branch)
            {
                $x->push(str_replace(' ','',str_replace('* ','',$branch)));
            }
            return $x->all();
        }

        /**
         *
         * List
         * @return array
         *
         */
        /**
         *
         * Display all releases
         *
         * @return array
         *
         */
        public function releases(): array
        {
            return  $this->execute('git tag -l --sort=v:refname')->reverse()->all();
        }

        /**
         *
         * Display all news between the last release
         *
         * @return string
         *
         */
        public function news()
        {
            $x = collect($this->releases());
            return $this->diff($x->get(0),$x->get(1));
        }

        /**
         *
         * Show logs
         *
         * @param int $current_page
         *
         * @param string $branch
         * @return string
         *
         * @throws Kedavra
         */
        public function log(int $current_page,string $branch)
        {

            $format = '<div class="card"><div class="card-header" id="commit-%h"><h2 class="mb-0"><button class="btn btn-link" type="button" data-toggle="collapse" data-target="#commit-%H"  aria-controls="commit-%H">%s</button></h2></div><div id="commit-%H" class="collapse" aria-labelledby="commit-%H" data-parent="#logs"><div class="card-body"><p>%s</p><div class="text-center"><a href="mailto:%ae" class="btn btn-outline-primary">%an</a></div></div><div class="card-footer text-muted">%cr</div></div></div>';

            $pagination = (new Pagination($current_page,100,$this->commits_size($branch)))->paginate();
            $html = '<div class="accordion" id="logs">';

            if (equal($current_page,0))
            {
                append($html,html_entity_decode($this->execute("git log -n 100 --pretty=format:'$format' $branch" )->join('')));
            }else{
                $x =  100 * $current_page;
                append($html,html_entity_decode($this->execute("git log --skip=$x -n 100   --pretty=format:'$format'")->join('')));
            }

  
            append($html,'</div>','<div class="container">',$pagination,'</div>');
           return  $html;
        }

        /**
         *
         * Get the current branch
         *
         * @return string
         *
         */
        public function current_branch(): string
        {
            return $this->execute('git branch --show-current')->get(0);
        }
        /**
         *
         * Execute a command an store result
         *
         * @param string $command
         *
         * @return Collect
         *
         */
        private function execute(string $command): Collect
        {
            $this->data = [];
            exec($command,$this->data);
            return collect($this->data);
        }


    }