<?php

namespace Imperium\Versioning\Git {

    use Carbon\Carbon;
    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;

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

        const GIT_DIR = '.git';

        const DESCRIPTION =  'description';

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $contributor;

        /**
         * @var string
         */
        private $contributor_email;
        /**
         * @var bool
         */
        private $dark_mode;

        /**
         * @var string
         */
        private $releases_directory;


        /**
         * @var array
         */
        private $contributors;

        /**
         * @var array
         */
        private $releases;

        /**
         *
         * @var array
         *
         */
        private $archives_ext;


        /**
         *
         * Git constructor.
         *
         * @param string $repository
         * @param string $locale
         *
         * @param bool $dark_mode
         * @throws Kedavra
         */
        public function __construct(string $repository,string $locale = 'en',bool $dark_mode = false)
        {
            $this->archives_ext = config('git','archives_extensions');

            is_false(Dir::is($repository),true,"The $repository repository not exist");

            $this->repository = realpath($repository);

            Dir::checkout($this->repository);

            is_false(Dir::is('.git'),true,"The current repository is not a git project");

            $this->name = strstr($repository,'.')  ? collection(explode(DIRECTORY_SEPARATOR,getcwd()))->last():  collection(explode(DIRECTORY_SEPARATOR,$repository))->last();

            $this->contributors = $this->save_contributors();

            $this->releases = $this->save_releases();

            foreach ($this->archives_ext as $x)
            {
                not_in(GIT_ARCHIVE_EXT,$x,true,'The used archives extension is not valid');
                $this->create_archives($x);

            }

            Carbon::setLocale($locale);

            $this->dark_mode = $dark_mode;

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

            $this->clean();

            $this->execute("git rev-list --count {$this->current_branch()}");

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

            $this->clean();

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

            $date = collection();

            $contributions = collection();

            $today = now()->addMonth()->format('Y-m-d');

            for ($i=0;$i!=14;$i++)
                $date->add(now()->addMonths(-$i)->format('Y-m-d'));

            $date = collection($date->reverse());



            $i = 1;
            $x = 2;
            do{
                $this->clean();
                if ($i == 13)
                    $this->execute("git log --after={$date->get($i)} --before=$today --pretty=format:'%s' --author='$author'");
                else
                    $this->execute("git log --after={$date->get($i)} --before={$date->get($x)} --pretty=format:'%s' --author='$author'");

                $contributions->add($this->data()->length(),$date->get($i));
                $i++;
                $x++;
            }while($i!=14);

            return $contributions;

        }

        /**
         *
         * @return Collection
         *
         */
        public function months(): Collection
        {
            $months= collection();

            for ($i=0;$i!=14;$i++)
                $months->add(now()->addMonths(-$i)->format('F'));

            return collection($months->reverse());
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
            return ! $this->shell("git checkout $data");
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

            $this->clean();

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
         * @return int
         *
         */
        public function branches_found(): int
        {
            return $this->branches()->length();
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
            return str_replace('* ','',collection($this->get_branch())->get(0));
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
         * Display all release size
         *
         * @return int
         *
         */
        public function release_size(): int
        {
            return collection($this->releases())->length();
        }

        /**
         *
         * Display the equip size
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function contributors_size(): int
        {
            return collection($this->contributors())->length();
        }


        public function commit_view()
        {
            return form(route('commit'),'')->row()->textarea('message','message')->end_row_and_new()->submit('commit')->end_row()->get();
        }
        /**
         * @param string $search_placeholder
         * @param string $contribution_text
         * @return string
         *
         * @throws Exception
         */
        public function contributors_view(string $search_placeholder = 'Search a contributor',string $contribution_text ='Contributions'):string
        {

            $html = '<input type="search" id="search_contributor" onkeyup="find_contributor()" placeholder="'.$search_placeholder.'" class="form-control mt-5 mb-5 form-control-lg" autofocus="autofocus"><ul class=" list-unstyled row" id="contributors">';

            foreach($this->contributors() as $name => $email)
                append($html,'<li class="col-md-4 col-lg-4 col-sm-12 col-xl-4 "><a href="mailto:'.$email.'">'.$name.'</a></li>');


            append($html,'</ul><canvas id="contrib"></canvas>   
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
            <script>
               
                function find_contributor() 
                {
                    let input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("search_contributor");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("contributors");
                    li = ul.getElementsByTagName("li");
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
            </script>');

            return $html;
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
         * @throws kedavra
         *
         */
        public static function clone(string $url,string $path): bool
        {
            is_true(equal($path,'.'),true,'The path is not valid');

            is_true(equal($path,'..'),true,'The path is not valid');

            is_true(Dir::is($path),true,'The repository already exist');
            
            return is_null(shell_exec("git clone $url $path"));
           
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
         * @throws Kedavra
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

        /**
         *
         * @param string $new_release
         * @param string $ancient_release
         *
         * @return string
         *
         * @throws Kedavra
         */
        public function news(string $new_release,string $ancient_release): string
        {
            $file = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' .DIRECTORY_SEPARATOR . 'diff.html';

            $tags = $this->releases();

            not_in($tags,$ancient_release,true,"The release $ancient_release was not found in the {$this->repository()} repository");

            not_in($tags,$new_release,true,"The release $new_release was not found in the {$this->repository()} repository");

            $this->clean();

            if ($this->dark_mode)
                $this->shell("git diff --word-diff --color-words $ancient_release $new_release  --patch-with-stat | aha  --black --title $this->name > $file");
            else
                $this->shell("git diff --word-diff --color-words $ancient_release $new_release  --patch-with-stat | aha  --title $this->name > $file");

            return File::content($file);

        }


        /**
         *
         * Create repository archive
         *
         * @param string $ext
         * @throws Kedavra
         */
        private function create_archives(string $ext): void
        {

            $path = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' ;

            Dir::create("$path" .DIRECTORY_SEPARATOR . $this->repository()) ;
            Dir::create("$path" .DIRECTORY_SEPARATOR . $this->repository() .DIRECTORY_SEPARATOR . 'releases') ;

            $this->releases_directory =  $path   .DIRECTORY_SEPARATOR . $this->repository() .DIRECTORY_SEPARATOR . 'releases';



                foreach ($this->releases() as $tag)
                {
                    $file = $this->releases_directory  .DIRECTORY_SEPARATOR . $this->repository() .'-'. "$tag.$ext";

                    if (File::not_exist($file))
                    {
                        switch ($ext)
                        {
                            case 'zip':
                                $this->shell("git archive --format=$ext --prefix=$this->name-$tag/ $tag  > $file");
                            break;
                            default:
                                $this->shell("git archive --format=$ext --prefix=$this->name-$tag/ $tag |  gzip > $file");
                            break;
                        }
                    }

                }


        }

        public function release_view(string $search_placeholder = 'Find a version')
        {
            $html = '<input type="search" id="search_release" onkeyup="find_releases()" placeholder="'.$search_placeholder.'" class="form-control mt-5 mb-5 form-control-lg" autofocus="autofocus"><ul class=" list-unstyled row" id="releases">';


            foreach ($this->archives_ext as $ext)
            {
                foreach ($this->releases() as $tag)
                {

                    $archive = $this->repository() .DIRECTORY_SEPARATOR .'releases'.DIRECTORY_SEPARATOR.  $this->repository() . '-' . "$tag." . $ext;

                    if (php_sapi_name() != 'cli')
                        $x = https() ? 'https://' . request()->getHost() .'/' . $archive : 'http://' . request()->getHost() .'/'. $archive;
                    else
                        $x = "/$archive";
                    append($html,'<li class="col-md-3 col-lg-3 col-sm-12 col-xl-3"><a href="'.$x.'">'.collection(explode(DIRECTORY_SEPARATOR,$archive))->last().'</a></li>');

                }
            }


            append($html,'</ul>  <script>
               
                function find_releases() 
                {
                    let input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("search_release");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("releases");
                    li = ul.getElementsByTagName("li");
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
            </script>');
            return $html;
        }
        public function git(): string
        {
          $html = "<div class='row'><div class='col-lg-6 col-sm-12 col-md-6 col-xl-6'>{$this->contributors_view()}</div><div class='col-lg-6 col-sm-12 col-md-6 col-xl-6'>{$this->release_view()}</div></dov>";

            return $html;
        }

        /**
         *
         * Display log
         *
         * @param int $size
         * @param string $period
         * @param bool $after
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function log(int $size = 1,string $period  ='month',bool $after = true): string
        {

            not_in(GIT_PERIOD,$period,true,"The current period is not valid please choose one of this values :  {$this->valid_period()}");

            not_in(GIT_SIZE,$size,true,"The current size is not valid please choose one of this values : {$this->valid_size()}");


            $file = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' .DIRECTORY_SEPARATOR . 'log.html';

            if ($after)
            {
                if ($this->dark_mode)
                    $command = "git log  -p  --graph --abbrev-commit --stat  --color=always	--after=$size.$period	 | aha    --black --title $this->name > $file";
                else
                    $command = "git log  -p  --graph --abbrev-commit --stat  --color=always	--after=$size.$period	 | aha   --title $this->name > $file";
            }else
            {
                if ($this->dark_mode)
                    $command = "git log  --word-diff --color-words  --color=always --graph  --oneline  --decorate  --stat | aha  --black  --title $this->name > $file";
                else
                    $command = "git log  -p  --graph --abbrev-commit --stat  --color=always	--before=$size.$period	 | aha   --title $this->name > $file";
            }

            $this->shell($command);
            return File::content($file);
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
            $this->clean();
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
            $this->clean();
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
            $this->clean();
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
            return is_null(shell_exec($command));
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
            $this->clean();
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
         * Show the repositories status
         *
         * @return Collection
         *
         */
        public function status(): Collection
        {
            $this->clean();

            $this->execute('git status');

            return $this->data();

        }

        public function contributors(): array
        {
            return $this->contributors;
        }

        public function releases(): array
        {
            return $this->releases;
        }

        /**
         *
         * List valid period
         *
         * @return string
         *
         */
        private function valid_period(): string
        {
            return collection(GIT_PERIOD)->join(', ');
        }
        /**
         *
         * clean data
         *
         * @return void
         *
         */
        private function clean():void
        {
            $this->data = [];
        }

        private function valid_size()
        {
            return collection(GIT_SIZE)->join(' ');
        }


        private function save_releases(): array
        {
            $this->clean();
            $versions = collection();

            $this->execute('git tag --sort=version:refname');
            foreach ($this->data as $x)
                $versions->stack($x);

            $this->clean();

            return $versions->collection();
        }
        /**
         *
         * Display all contributors
         *
         * @return array
         *
         *
         */
        private function save_contributors(): array
        {
            $x =  collection();

            $this->clean();

            $this->execute("git shortlog -sne --all");

            foreach ($this->data()->collection() as $k => $v)
            {
                $parts = collection(preg_split('/\s+/', $v));

                $this->contributor = $parts->get(2) .' ' . $parts->get(3);

                foreach ($parts as $key => $value)
                {
                    if (def($value))
                    {

                        if (!is_numeric($value))
                        {
                            if (strpos($value,'<') === 0)
                            {
                                $this->contributor_email = str_replace('<','',str_replace('>','',$value));

                                if (strrchr($this->contributor,'<'))
                                {
                                    $tmp = collection(explode('<',$this->contributor));

                                    $this->contributor = trim($tmp->get(0));

                                }
                            }
                        }
                    }

                }
                if ($x->not_exist($this->contributor))
                    $x->add($this->contributor_email,$this->contributor);
            }
            $this->contributor = null;
            $this->contributor_email = null;
            return $x->collection();
        }


    }
}