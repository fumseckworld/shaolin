<?php

namespace Imperium\Versioning\Git {

    use Carbon\Carbon;
    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use NumberFormatter;

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

        const DESCRIPTION =  'description';

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $users_table;

        /**
         * @var string
         */
        private $contributor;

        /**
         * @var string
         */
        private $authors_order_by;

        /**
         *
         * Git constructor.
         *
         * @param string $repository
         *
         * @throws Exception
         *
         */
        public function __construct(string $repository)
        {
            is_false(Dir::is($repository),true,"The $repository repository not exist");

            $this->repository = realpath($repository);

            Dir::checkout($this->repository);

            $this->name = strstr($repository,'.')  ? collection(explode(DIRECTORY_SEPARATOR,getcwd()))->last():  collection(explode(DIRECTORY_SEPARATOR,$repository))->last();

            $this->data = [];

            $this->users_table = collection(config('git','tables'))->get('users');

            $this->contributor = collection(config('git','tables'))->get('contributors');

            $this->authors_order_by = config('git','authors_order_by');

            is_false(Dir::is('.git'),true,"The current repository is not a git project");

            $this->locale = config('locales','locale');

            Carbon::setLocale($this->locale);
        }


        /**
         *
         * Return the numbers of commits on the current branch
         *
         * @return int
         *
         */
        public function commits_size(): int
        {

            $current = $this->current_branch();

            $this->execute("git rev-list --count {$current}");

            return intval($this->data()->last());

        }

        /**
         *
         * Get all commits by the author between today and the 12 last months
         *
         * @param string $author
         *
         * @return Collection
         *
         */
        public function commits_by_year(string $author): Collection
        {
            $today = now()->format('Y-m-d');

            $after = now()->addYears(-1)->format('Y-m-d');

            $this->execute("git log --after=$after --before=$today --pretty=format:'%s' --author='$author'");

            return $this->data();
        }

        /**
         *
         * Get all commits by the author between all months before today
         *
         * @param string $author
         *
         * @return Collection
         *
         */
        public function commits_by_month(string $author): Collection
        {

            if (not_def($this->commits_by_year($author)->collection()))
                return new Collection();

            $data = collection();

            $contributions = collection();

            $today = now()->addMonth()->format('Y-m-d');

            for ($i=0;$i!=14;$i++)
                $data->add(now()->addMonths(-$i)->format('Y-m-d'));

            $months = collection($data->reverse());

            $i = 1;
            $x = 2;
            do{
                $this->data = [];
                if ($i == 13)
                    $this->execute("git log --after={$months->get($i)} --before=$today --pretty=format:'%s' --author='$author'");
                else
                    $this->execute("git log --after={$months->get($i)} --before={$months->get($x)} --pretty=format:'%s' --author='$author'");

                $contributions->add($this->data()->length(),$months->get($i));
                $i++;
                $x++;
            }while($i!=14);

            return $contributions;

        }

        /**
         *
         * Checkout on data
         *
         * @param string $data
         *
         * @return bool
         *
         */
        public function checkout(string $data): bool
        {
            return $this->shell("git checkout $data");
        }

        /**
         *
         * List repository directories
         *
         * @param string $directory
         *
         * @return Collection
         *
         */
        public function directories(string $directory = ''): Collection
        {

            $branches =  $this->branches()->length();
            $this->data = [];

            def($directory) ? $this->execute("git ls-tree -d {$this->current_branch()} --name-only -r  $directory") : $this->execute("git ls-tree -d {$this->current_branch()} --name-only -r");

            for ($i=$branches;$i!=0;$i--)
                $this->data =  $this->data()->shift();

            return $this->data();
        }

        /**
         *
         * List repository files
         *
         * @param string $directory
         *
         * @return Collection
         *
         */
        public function files(string $directory = ''): Collection
        {
            def($directory) ? $this->execute("git ls-files --directory $directory") : $this->execute("git ls-files");

            return $this->data();
        }

        /**
         *
         * Display all contributors
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function contributors(): array
        {
            return app()->model()->from($this->contributor)->all();
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
         * @return Collection
         *
         */
        public function releases(): Collection
        {
            $versions = collection();

            $this->execute('git tag --sort=version:refname');
            foreach ($this->data as $x)
                    $versions->stack($x);

            return $versions;
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
            return $this->format(collection($this->releases())->length());
        }

        /**
         *
         * Display the equip size
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function equip_size(): string
        {
            return numfmt_format(numfmt_create($this->locale, NumberFormatter::DEFAULT_STYLE),collection($this->contributors())->length());
        }


        /**
         * @return string
         *
         * @throws Exception
         *
         */
        public function contributors_view():string
        {

            $format ='<li class="col-md-4 col-lg-4 col-sm-12 col-xl-4 contributor"><a href="mailto:%ae">%an</a><a href="#" onclick="contributions()" class="'. collection(config('git','class'))->get('contributions').'"> '.config('git','contribution_text').'</a></li>';
            $command = '';
            append($command,"git log --pretty=format:'$format' " );
            $html = '<input type="search" id="search" onkeyup="find()" placeholder="'.collection(config('git','placeholders'))->get('search').'" class="'.collection(config('git','class'))->get('search').'" autofocus="autofocus"><ul class="list-unstyled row" id="contributors">';

            $authors = collection();

            $this->execute($command);

            foreach ($this->data as $x)
            {

                if ($authors->not_exist($x))
                    $authors->add($x);
            }


            return $html . $authors->join('') . '</ul><<canvas id="contrib"></canvas>   
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
                
                
                <script>
                    var ctx = document.getElementById("contrib");
                    var myChart = new Chart(ctx, {
                        type: \'bar\',
                        data: {
                        labels: [\'Red\', \'Blue\', \'Yellow\', \'Green\', \'Purple\', \'Orange\'],
                        datasets: [{
            label: \'# of Votes\',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                \'rgba(255, 99, 132, 0.2)\',
                \'rgba(54, 162, 235, 0.2)\',
                \'rgba(255, 206, 86, 0.2)\',
                \'rgba(75, 192, 192, 0.2)\',
                \'rgba(153, 102, 255, 0.2)\',
                \'rgba(255, 159, 64, 0.2)\'
            ],
            borderColor: [
                \'rgba(255, 99, 132, 1)\',
                \'rgba(54, 162, 235, 1)\',
                \'rgba(255, 206, 86, 1)\',
                \'rgba(75, 192, 192, 1)\',
                \'rgba(153, 102, 255, 1)\',
                \'rgba(255, 159, 64, 1)\'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
                
                <script>
                    function contributions() 
                    {
                      document.getElementById("contributors").style.display = "none";
                      const contrib = document.getElementById("contrib");
                    }
                    function find() 
                    {
                        let input, filter, ul, li, a, i, txtValue;
                        input = document.getElementById("search");
                        filter = input.value.toUpperCase();
                        ul = document.getElementById("contributors");
                        li = ul.getElementsByTagName("li");i
                        for (i = 0; i < li.length; i++) 
                        {
                            a = li[i].getElementsByTagName("a")[0];
                            txtValue = a.textContent || a.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) 
                            {
                                li[i].style.display = "";
                            } else {
                                li[i].style.display = "none";
                            }
                        }
                    }
                </script>';
        }

        /**
         *
         * Clone a repository
         *
         * @param string $url
         * @param string $path
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public static function clone(string $url,string $path): bool
        {
            is_true(equal($path,'.'),true,'The path is not valid');

            is_true(equal($path,'..'),true,'The path is not valid');

            is_true(Dir::is($path),true,'The repository already exist');
            
            return ! is_null(shell_exec("git clone $url $path"));
           
        }

        /**
         *
         * Initialize a new project
         *
         * @param string $project_name
         * @param bool $remote
         * @param string $description
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public static function create(string $project_name,bool $remote = false,string $description = ''): bool
        {
            is_true(Dir::is($project_name),true,"The $project_name directory already exist");

            Dir::create($project_name);

            Dir::checkout($project_name);

            $remote ?  shell_exec('git init --bare') : shell_exec('git init');

            if ($remote)
            {
               is_false(File::write(self::DESCRIPTION,$description,File::EMPTY_AND_WRITE),true,"Failed to write description");
            }

            return $remote ? Dir::exist('hooks') : Dir::exist(self::GIT_DIR);
        }

        /**
         *
         * Display the repository description
         *
         * @param string $project
         *
         * @return string
         *
         */
        public static function description(string $project): string
        {
            Dir::checkout($project);

            return File::content(self::DESCRIPTION);
        }
        /**
         *
         * Execute git add
         *
         * @return Git
         *
         * @throws Exception
         *
         */
        public function add(): Git
        {
            is_false($this->shell('git add  .'),true,'The git add command as fail');

            return $this;
        }

        /**
         *
         * Add a commit message
         *
         * @param string $message
         *
         * @return Git
         *
         * @throws Exception
         *
         */
        public function commit(string $message): Git
        {
            is_false($this->shell("git commit -m '$message'"),true,'The git commit command as fail');

            return $this;
        }


        public function between(string $first,string $second)
        {
            $this->execute("git diff $first $second --stat");

            return collection($this->data);

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

            $limit = get('limit',10);

            append($command, " --max-count=$limit");

            if (def(get('author')))
            {
                $author = $_GET['author'] ;

                append($command," --author=$author ");
            }

            $html = '<table class="table table-bordered"><thead><tr><th>author</th><th>commit</th><th>date</th><th>lines</th><th>diff</th></tr></thead><tbody>';

            $format = '<tr><td><a href="mailto:%ae">%an</a></td><td>%s</td><td>%ar</td><p>%H</p>';

            append($command," --pretty=format:'$format'" );

            $this->execute($command);




            foreach ($this->data as $x)
            {

                $commit = collection(explode('<p>',$x));

                $commit =  str_replace('</p>','',$commit->get(1));


                $x = str_replace("<p>$commit</p>",'',$x);

                $repo = $this->name;

                append($x,"<td>",$this->removed_added($commit),'</td><td>','<a href="/'.$repo .'/commit/'.$commit.'">show diff</a></td></tr>');

                append($html, $x);
            }

            append($html,'</tbody></table>');

            return $html;
        }

        /**
         *
         *
         * @param string $sha1
         *
         * @return string
         *
         */
        public function removed_added(string $sha1):string
        {
            return $this->lines($sha1)->last();
        }

        /**
         *
         * @param string $sha1
         *
         * @return Collection
         *
         */
        public function lines(string $sha1): Collection
        {
            $this->execute("git show $sha1 --stat ");

            return collection($this->data);
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
            $this->data = [];
            $this->execute('git branch --all');
            $branches = collection();

            foreach ($this->data as $x)
            {
                $x = collection(explode('/',$x))->last();
                $branches->add($x);
            }
            return $branches->collection();
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
            return numfmt_format(numfmt_create($this->locale, NumberFormatter::DEFAULT_STYLE),$x);
        }

        /**
         *
         * Show the repositories status
         *
         * @return Collection
         *
         */
        public function status(): Collection
        {
            $this->execute('git status');

            return $this->data();
        }

        /**
         *
         * Display git diff
         *
         * @return Collection
         *
         */
        public function diff(): Collection
        {
            return collection()->add(shell_exec("git diff --color=always --stat" ))->add(shell_exec('git diff --color=always'));

        }
    }
}