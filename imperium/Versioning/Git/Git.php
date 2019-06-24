<?php

namespace Imperium\Versioning\Git {

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
         * @var array
         */
        private $contributors;
        /**
         *
         * @var array
         *
         */
        private $archives_ext;
        /**
         * @var string
         */
        private $owner;


        /**
         *
         * Git constructor.
         *
         * @param string $path
         * @param string $repository
         * @param string $owner
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $path,string $repository, string $owner)
        {
            $this->owner =  $owner;

            $owner_dir = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' .DIRECTORY_SEPARATOR . $this->owner() ;
            Dir::create($owner_dir);

            $repository = $owner_dir . DIRECTORY_SEPARATOR . $repository;

            is_false(Dir::is($repository),true,"Repository not found");

            $this->archives_ext = config('git','archives_extension');

            $this->repository = realpath($repository);

            Dir::checkout($this->repository);

            $this->name = strstr($repository,'.')  ? collection(explode(DIRECTORY_SEPARATOR,getcwd()))->last():  collection(explode(DIRECTORY_SEPARATOR,$repository))->last();

            $this->contributors = $this->save_contributors();

            $this->generate_archives();
        }


        /***
         *
         * Get the repository owner
         *
         * @return string
         *
         */
        public function owner(): string
        {
            return $this->owner;
        }

        /**
         *
         * Check if the dir is a remote
         *
         * @return bool
         *
         */
        public function is_remote(): bool
        {
            return Dir::is('hooks') && Dir::is('refs');
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
            $this->execute("git rev-list --count {$this->current_branch()}");

            return intval($this->data()->last());

        }


        public function generate_changes_log()
        {

        }

        /**
         *
         * Generate archives
         *
         * @return Git
         *
         * @throws Kedavra
         *
         */
        public function generate_archives(): Git
        {
            foreach ($this->archives_ext as $x)
            {
                not_in(GIT_ARCHIVE_EXT,$x,true,'The used archives extension is not valid');
                $this->create_archives($x);

            }
            return $this;
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

            $branches =  $this->branches_found();

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
         * Repository path
         *
         * @return string
         *
         */
        public function path(): string
        {
            return $this->repository;
        }

        /**
         *
         * Result of command
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
            return $this->is_remote() ? collection(Dir::scan('refs/heads'))->length() : collection($this->branches())->length();
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
            return '';
        }



        /**
         *
         * Get all branches
         *
         * @return array
         *
         */
        public function branches(): array
        {
            $branches = collection();

            foreach ($this->get_branch() as $branch)
                $branches->add(trim(str_replace('* ','',$branch)));

            return $branches->collection();
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

        /**
         * @param string $search_placeholder
         * @return string
         */
        public function contributors_view(string $search_placeholder = 'Search a contributor'):string
        {

            $html = '<input type="search" id="search_contributor" onkeyup="find_contributor()" placeholder="'.$search_placeholder.'" class="form-control mt-5 mb-5 form-control-lg" autofocus="autofocus"><ul class=" list-unstyled row" id="contributors">';

            foreach($this->contributors() as $name => $email)
                append($html,'<li class="col-md-4 col-lg-4 col-sm-12 col-xl-4 "><a href="mailto:'.$email.'">'.$name.'</a></li>');


            append($html,'</ul><canvas id="contrib"></canvas>   
          
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
         * Create a remote repository
         *
         * @param string $project_name
         * @param string $owner
         * @param string $description
         * @param bool $remote
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function create(string $project_name,string $owner,string $description,bool $remote = true): bool
        {
            Dir::create($owner);

            $project_name = $owner . DIRECTORY_SEPARATOR . $project_name;

            is_true(Dir::is($project_name),true,"The $project_name directory already exist");

            Dir::create($project_name);

            Dir::checkout($project_name);

            $remote ?  shell_exec('git init --bare') : shell_exec('git init');

            if ($remote)
            {
                is_false((new File(self::DESCRIPTION,EMPTY_AND_WRITE_FILE_MODE))->write($description,length($description)),true,'Failed to write description');
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
         * @throws Kedavra
         *
         */
        public static function description(string $project): string
        {
            Dir::checkout($project);

            return (new File(self::DESCRIPTION,READ_FILE_MODE))->read();
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
         * Display all last release change
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function news(): string
        {
            is_true(not_def($this->releases()),true,"No releases found");

            return $this->change(collection($this->releases())->get(0),collection($this->releases())->get(1));
        }

        /**
         *
         *Display all changes between two version
         *
         * @param string $new_release
         * @param string $ancient_release
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function change(string $new_release,string $ancient_release): string
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

            return (new File($file,READ_FILE_MODE))->read();

        }


        /**
         *
         * Create repository archives
         *
         * @param string $ext
         *
         * @throws Kedavra
         *
         */
        private function create_archives(string $ext): void
        {

            $dir = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' .DIRECTORY_SEPARATOR . $this->owner() . DIRECTORY_SEPARATOR . $this->repository() .DIRECTORY_SEPARATOR . 'releases';

            Dir::create($dir) ;


            foreach ($this->releases() as $tag)
            {
                $file = $dir  .DIRECTORY_SEPARATOR . $this->repository() .'-'. "$tag.$ext";

                if (!file_exists($file))
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

        /**
         *
         * Display all releases
         *
         * @param string $search_placeholder
         *
         * @return string
         *
         */
        public function release_view(string $search_placeholder = 'Find a version'): string
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

        /**
         *
         * Show git clone urls
         *
         * @return string
         *
         */
        public function clone_url(): string
        {
            $host = request()->getHost();
            return "git://$host{$this->path()} git@$host:{$this->path()}";
        }

        /**
         *
         *
         * @return string
         *
         *
         */
        public function git(): string
        {
            $html = '<div class="mt-5 mb-5"><div class="row"><div class="col-md-3 col-lg-3 col-sm-12 col-xl-3">'.$this->clone_url().' </div><div></div></div></div><div class="row"><div class="col-lg-6 col-sm-12 col-md-6 col-xl-6">'.$this->contributors_view().'</div><div class="col-lg-6 col-sm-12 col-md-6 col-xl-6">'.$this->release_view().'</div></div>';

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
         *
         */
        public function log(int $size = 1,string $period  ='month',bool $after = true): string
        {
            $file = dirname(config_path()) .DIRECTORY_SEPARATOR . 'web' .DIRECTORY_SEPARATOR . 'log.html';

            $host = request()->getHost();
            $diff_url  =https() ? "https://$host/$this->owner/{$this->repository()}/diff/%H" : "http://$host/$this->owner/{$this->repository()}/diff/%H" ;

            $format = '<a href="'.$diff_url.'" class="text-success mr-3">Show diff</a><a href="mailto:%ae">%an</a>   <a class="text-xl-right">%s</a>';
            if ($after)
            {
                if ($this->dark_mode)
                    $command = "git log  -p  --graph --abbrev-commit --stat  --color=always	--after=$size.$period	 | aha    --black --title $this->name > $file";
                else
                    $command = "git log  --oneline --color=always --after=$size.$period  --pretty=format:'$format' | aha   --title $this->name > $file";
            }else
            {
                if ($this->dark_mode)
                    $command = "git log  --word-diff --color-words  --color=always --graph  --oneline  --decorate  --stat | aha  --black  --title $this->name > $file";
                else
                    $command = "git log  -p  --graph --abbrev-commit --stat  --color=always	--before=$size.$period	 | aha   --title $this->name > $file";
            }

            $this->shell($command);
            return html_entity_decode((new File($file,READ_FILE_MODE))->read());
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
         * @throws Kedavra
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
            $this->clean();
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
            $branches = collection();

            if ($this->is_remote())
                $this->data = Dir::scan('refs/heads');
            else
                $this->execute('git branch --all');

            foreach ($this->data()->collection() as $x)
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
            $this->execute('git status');

            return $this->data();

        }

        /**
         *
         *
         * @return array
         *
         */
        public function contributors(): array
        {
            return $this->contributors;
        }

        /**
         *
         * List all versions
         *
         * @return array
         *
         */
        public function releases(): array
        {
            if ($this->is_remote())
                return collection(Dir::scan('refs/tags'))->reverse();

            $this->execute('git tag --sort=version:refname');

            return $this->data()->reverse();

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