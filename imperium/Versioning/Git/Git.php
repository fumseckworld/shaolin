<?php

namespace Imperium\Versioning\Git {

    use Carbon\Carbon;
    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use IntlDateFormatter;

    class Git
    {
        /**
         * @var bool|string
         */
        private $repository;

        /**
         * @var array
         */
        private $data;
        /**
         * @var string
         */
        private $locale;

        const GIT_DIR = '.git';

        const DESCRIPTION = self::GIT_DIR . DIRECTORY_SEPARATOR . 'description';

        /**
         * @var string
         */
        private $name;

        /**
         *
         * Git constructor.
         *
         * @param string $repository
         * @param string $locale
         *
         * @throws Exception
         *
         */
        public function __construct(string $repository,string $locale = 'en')
        {
            is_false(Dir::is($repository),true,"The $repository repository not exist");

             
            $this->repository = realpath($repository);

            $this->name = collection(explode(DIRECTORY_SEPARATOR,$repository))->last();
            $this->data = [];

            Dir::checkout($this->repository);

            is_false(Dir::is('.git'),true,"The current repository is not a git project");

            $this->locale = $locale;

            Carbon::setLocale($locale);
        }

        /**
         *
         * @param string $author
         * @param int $limit
         *
         * @return array
         */
        public function show_commit(string $author,int $limit = 300)
        {
            $this->execute("git log --pretty=format:'%s' --max-count=$limit --author='$author'");

            return $this->data;
        }

        /**
         *
         * Get the name of the repository
         *
         * @return string
         *
         */
        public function repository(): string
        {
            return $this->name;
        }

        /**
         * @return array
         */
        public function interval(): array
        {
            $start = Carbon::now()->month  ;
            $end = $start - 13;
            $month = collection();

            for ($i=$start ;$i > $end;$i--)
            {
                $date =  Carbon::createFromTimestamp(Carbon::now()->getTimestamp())->month($i)->day(1);
                  $format = new IntlDateFormatter($this->locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, null, null, "MMM");
                    $current = ucfirst($format->format(mktime(0, 0, 0, $date->month)));

                $month->stack($current);
            }
            return $month->collection();
        }

        /**
         *
         * @param string $author
         *
         * @return array
         *
         */
        public function contributions(string $author)
        {
            $data = collection();
            $contributions = collection();

            $start = Carbon::now()->month ;
            $end = $start - 13 ;

            for ($i=$start;$i > $end;$i--)
            {
                $date =  Carbon::createFromTimestamp(Carbon::now()->getTimestamp())->month($i)->day(1);
                $data->stack($date->format('Y-m-d'));
            }

            foreach ($data->collection() as $k => $v)
            {
                $x = $data->get(++$k);

                if (def($x))
                {

                    $after = $v;
                    $before = $x;
                    $this->data = [];
                    $this->execute("git log --after=$after --before=$before --pretty=format:'%s' --author='$author'");
                    $contributions->add(count($this->data));
                }
            }
            return $contributions->collection();
        }

        /**
         *
         * @return Collection
         *
         */
        public function data(): Collection
        {
            return collection($this->data);
        }

        /**
         *
         * Display all branches found
         *
         * @return string
         *
         */
        public function branches_found(): string
        {
            return $this->format($this->branches()->length());
        }

        /**
         *
         * Return the current branch
         *
         * @return string
         *
         */
        public function current_branch(): string
        {
            foreach ($this->get_branch() as $branch)
            {
                if (strpos($branch,'*') === 0)
                    return trim(str_replace('* ','',$branch));
            }
            return 'master';
        }


        /**
         *
         * Initialize a new project
         *
         * @param string $project
         * @param bool $remote
         * @param string $description
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function init(string $project,bool $remote,string $description): bool
        {
            is_true(Dir::is($project),true,"The $project directory already exist");

            Dir::create($project);

            Dir::checkout($project);

            $remote ?  exec('git init --bare') : exec('git init');

            if ($remote)
                File::put(self::DESCRIPTION,$description);

            return Dir::is($project) && Dir::is(self::GIT_DIR);
        }

        /**
         *
         * Get a collection of all branches
         *
         * @return Collection
         *
         */
        public function branches(): Collection
        {
            $branches = collection();

            foreach ($this->get_branch() as $branch)
                $branches->add(trim(str_replace('* ','',$branch)));

            return $branches;
        }

        /**
         *
         * Display all releases
         *
         * @return array
         *
         */
        public function release()
        {
            $data = collection();

            $this->execute('git tag');

            foreach ($this->data as $release)
               $data->stack($release);

            return $data->collection();
        }


        /**
         *
         * Display all release size
         *
         * @return string
         *
         */
        public function release_size(): string
        {
            return $this->format(collection($this->release())->length());
        }

        /**
         *
         * Display the equip size
         *
         * @return string
         *
         */
        public function equip_size(): string
        {
            return numfmt_format(numfmt_create($this->locale,\NumberFormatter::DEFAULT_STYLE),$this->contributors()->length());
        }

        /**
         *
         * Get a collection of all contributors
         *
         * @return Collection
         *
         */
        public function contributors(): Collection
        {
            $authors = collection();

            $this->execute('git log --pretty=format:"%an : %ae" ');

            foreach ($this->data as $x)
            {
               $a = collection( explode(':',$x));

               if ($authors->not_exist(trim($a->get(0))))
                    $authors->add(trim($a->get(1)),trim($a->get(0)));
            }
            return $authors;
        }

        public function contributors_view():string
        {

            $format ='<div class="col-4"><div class="card ml-4 mr-4 mt-4 mb-4"><div class="card-body"><h5 class="card-title text-center text-uppercase">%an</h5> </div><div class="card-footer">
      <small class="text-muted"><a href="mailto:%ae">Send an email</a></small></div></div></div></div>';
            $command = '';
            append($command,"git log --pretty=format:'$format' " );


            $authors = collection();

            $this->execute($command);

            foreach ($this->data as $x)
            {

                if ($authors->not_exist($x))
                    $authors->add($x);
            }

            return $authors->join('');
        }

        /**
         *
         * Add a commit message
         *
         * @param string $message
         *
         * @return bool
         *
         */
        public function commit(string $message): bool
        {
            $this->execute('git add .');

            return $this->shell("git commit -m '$message'");
        }


        /**
         *
         * Get all commits
         *
         * @return Collection
         *
         */
        public function commits(): Collection
        {
            $this->execute('git log --pretty=format:"%s"');

            return $this->data();
        }

        /**
         *
         * Return a formatted number witch represent all commit found
         *
         * @return string
         *
         */
        public function commits_found(): string
        {
           return $this->format($this->commits()->length());
        }




        /**
         *
         * Display log
         *
         * @return string
         *
         */
        public function log(): string
        {
            $command = 'git log ';

            if (def(get('limit')))
                append($command, " --max-count=",intval($_GET['limit']));
            else
                append($command,' --max-count=10 ');

            if (def(get('author')))
            {
                $author = $_GET['author'] ;

                append($command," --author=$author ");

            }
            $html = '<table class="table table-bordered"><thead><tr><th>author</th><th>commit</th><th>date</th><th>diff</th></tr></thead><tbody>';

            $format = '<tr><td><a href="mailto:%ae">%an</a></td><td>%s</td><td>%ar</td><td><a href="commit/%H">show diff</a></td></tr>';

            append($command," --pretty=format:'$format' " );


            $this->execute($command);

            foreach ($this->data as $x)
                append($html,$x);


            append($html,'</tbody></table>');
            exec("git log",$logs);
            append($html,pagination(10,'?after=',1,count($logs),'previous','next'));
            return $html;



        }


        /**
         *
         * Display the repository description
         *
         * @return string
         *
         */
        public function description(): string
        {
            return File::content(self::DESCRIPTION);
        }

        /**
         *
         * Get a collection of all remotes
         *
         * @return Collection
         *
         */
        public function remote(): Collection
        {
            $this->execute('git remote');

            return $this->data();
        }

        /**
         *
         * Send all modifications to the server
         *
         * @return  bool
         *
         * @throws Exception
         *
         */
        public function push(): bool
        {
            foreach ($this->remote()->collection() as $remote)
            {
                is_false($this->shell("git push $remote --all"),true,"Failed to send modifications");

                is_false($this->shell("git push $remote --tags"),true,"Failed to send new release");
            }

            return true;
        }

        /**
         *
         * Execute  a shall command
         *
         * @param string $command
         *
         * @return bool
         *
         */
        public function shell(string $command): bool
        {
            return ! is_null(shell_exec($command));
        }

        /**
         *
         * @param string $command
         *
         * @return string
         *
         */
        public function execute(string $command): string
        {
            return exec($command,$this->data);
        }

        /**
         *
         * Return all branch
         *
         * @return array
         */
        private function get_branch(): array
        {
            $this->execute('git show-branch --list');

            return $this->data;
        }

        /**
         *
         * Format a number to string
         *
         * @param int $x
         *
         * @return string
         *
         */
        private function format(int $x): string
        {
            return numfmt_format(numfmt_create($this->locale,\NumberFormatter::DEFAULT_STYLE),$x);
        }
    }
}