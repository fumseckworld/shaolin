<?php

namespace Imperium\Versioning\Git {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\Download;
    use Imperium\File\File;
    use Imperium\Html\Form\Form;
    use Symfony\Component\HttpFoundation\Response;

    class Git
    {
        /**
         * @var bool|string
         */
        private $repository;

        /**
         * @var array
         */
        private $data = [];

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
         * @var string
         */
        private $base_path;

        /**
         * @var string
         */
        private $contributors_key;
        /**
         * @var string
         */
        private $releases_key;
        /**
         * @var string
         */
        private $contributions_key;

        /**
         * @var string
         */
        private $directories_key;

        /**
         * @var string
         */
        private $files_key;

        /**
         *
         * All readme possibilities
         *
         * @var array
         *
         */
        private $all_readme;

        /**
         *
         * The readme content
         *
         * @var string
         */
        private $readme;

        /**
         *
         * All licence possibilities
         *
         * @var array
         *
         */
        private $licences;

        /**
         *
         * The licence content
         *
         * @var string
         *
         */
        private $licence;

        /**
         *
         * Git constructor.
         *
         * @param string $repository
         * @param string $owner
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function __construct(string $repository, string $owner)
        {

            $this->archives_ext = config('git','archives_extension');
            $this->all_readme = config('git','readme');
            $this->licences = config('git','licences');

            $this->owner = $owner;

            $this->repository =  realpath($repository);

            is_false(Dir::is($this->repository),true,"The repository was not found");

            if (app()->session()->has('repo'))
            {

                if (different(app()->session()->get('repo'),$this->repository))
                {

                    $repo = app()->session()->get('repo');
                    app()->cache()->remove("contributors_$repo");
                    app()->cache()->remove("releases_$repo");
                    app()->cache()->remove("contributions_$repo");
                    app()->cache()->remove("directories_$repo");
                    app()->cache()->remove("files_$repo");
                }
            }

            app()->session()->def('repo',$this->repository);


            $this->contributors_key = "contributors_{$this->repository}";

            $this->releases_key = "releases_{$this->repository}";

            $this->contributions_key = "contributions_{$this->repository}";

            $this->directories_key = "directories_{$this->repository}";

            $this->files_key = "files_{$this->repository}";

            Dir::checkout($this->repository);

            $this->name = strstr($repository,'.')  ? collection(explode(DIRECTORY_SEPARATOR,getcwd()))->last():  collection(explode(DIRECTORY_SEPARATOR,$repository))->last();

            foreach (File::search("{$this->repository()}-*.*") as $archive)
                $this->remove_archive($archive);

            $this->save_contributors();
            $this->save_releases();


        }

        /**
         *
         * Display contribution content
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function contribute():string
        {
            return (new File('CONTRIBUTING.md'))->markdown();
        }

        /**
         *
         * Display contribution content
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function change_log():string
        {
            $releases = collection($this->releases());

            return (new File("CHANGELOG-{$releases->get(0)}-{$releases->get(1)}.md"))->markdown();
        }


        /**
         *
         * Display readme content
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function readme()
        {
            if (is_null($this->readme))
            {
                foreach ($this->files('') as $file)
                {
                    if(has($file,$this->all_readme))
                        $this->readme = (new File($file))->markdown();
                }
            }
            return $this->readme;

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
            return str_replace($this->base_path,'',$this->owner);
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
         * @param string $ext
         * @param string $version
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function generate_archives(string $ext,string $version):string
        {

            not_in(GIT_ARCHIVE_EXT,$ext,true,'The used archives extension is not valid');

            return $this->create_archives($ext,$version);
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
         *
         */
        public function commits_by_month(string $author): Collection
        {

            $date = collection();

            $contributions = collection();

            $today = now()->addMonth()->format('Y-m-d');

            for ($i=0;$i!=13;$i++)
                $date->add(now()->addMonths(-$i)->format('F'));

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
            }while($i!=13);

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

            def($directory) ? $this->execute("git ls-tree --name-only -d  {$this->current_branch()} -r  $directory") : $this->execute("git ls-tree --name-only -d  {$this->current_branch()} ");

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
        public function files(string $directory ): Collection
        {
            $this->execute("git ls-tree --name-only -r  {$this->current_branch()} ");

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
            return 'master';
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
         * @throws Kedavra
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
         * @throws Kedavra
         */
        public function contributors_view(string $search_placeholder = 'Search a contributor'):string
        {

            $html = ' <div class="input-group mb-5">
                  <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">
                                group
                            </i>
                        </span>
                  </div>
                <input type="search" id="search_contributor" onkeyup="find_contributor()"   placeholder="'.$search_placeholder.'" class="form-control form-control-lg" autofocus="autofocus">
            </div>
            <ul class=" list-unstyled row" id="contributors">';

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
                is_false((new File(self::DESCRIPTION,EMPTY_AND_WRITE_FILE_MODE))->write($description),true,'Failed to write description');
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
            if (not_def($this->releases()))
                return 'No releases found';

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
         * @param string $version
         * @return string
         */
        private function create_archives(string $ext,string $version): string
        {

                $file =  $this->repository() .'-'. "$version.$ext";

                if (!file_exists($file))
                {
                    switch ($ext)
                    {
                        case 'zip':
                            $this->shell("git archive --format=$ext --prefix=$this->name-$version/ $version  > $file");
                        break;
                        default:
                            $this->shell("git archive --format=$ext --prefix=$this->name-$version/ $version |  gzip > $file");
                        break;
                    }
                }

                return $file;
        }

        /**
         *
         * Display all releases
         *
         * @param string $search_placeholder
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function release_view(string $search_placeholder = 'Find a version'): string
        {
            $html = '<div class="mt-5 mb-2">'.$this->compare_form().'</div> 
            <div class="input-group mb-3">
                  <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">
                                search
                            </i>
                        </span>
                  </div>
                <input type="search" id="search_release"  onkeyup="find_releases()" placeholder="'.$search_placeholder.'" class="form-control form-control-lg">
            </div>
            
            <ul class=" list-unstyled row" id="releases">';


            foreach ($this->archives_ext as $ext)
            {
                foreach ($this->releases() as $tag)
                {
                    $x = php_sapi_name() !== 'cli' ? https() ? 'https://'. request()->getHost() . '/' . $this->repository() . '/refs/' . $tag.$ext  : 'http://' . request()->getHost() . '/' . $this->repository() . '/refs/' . $tag.$ext : "/{$this->repository()}/refs/$tag.$ext";

                    append($html,'<li class="col-md-3 col-lg-3 col-sm-12 col-xl-3"><a href="'.$x.'">'.$this->repository() ."-$tag.$ext".'</a></li>');

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
         * Download a tag
         *
         * @param $archive
         * @return Response
         *
         * @throws Kedavra
         */
        public function download($archive): Response
        {
            $x = \collection(explode('-',$archive));
            $version = $x->begin();
            $ext = $x->last();


            return (new Download( $this->generate_archives($ext,$version)))->download();
        }

        /**
         *
         *
         * @return string
         *
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function git(): string
        {
            $html = '<nav>
  <div class="nav nav-tabs" id="git" role="tablist">
    <a class="nav-item nav-link active" id="nav-readme-tab" data-toggle="tab" href="#nav-readme" role="tab" aria-controls="nav-readme" aria-selected="true">Readme</a>
    <a class="nav-item nav-link " id="nav-licence-tab" data-toggle="tab" href="#nav-licence" role="tab" aria-controls="nav-licence" aria-selected="false">Licence</a>
    <a class="nav-item nav-link" id="nav-code-tab" data-toggle="tab" href="#nav-code" role="tab" aria-controls="nav-code" aria-selected="true">Code</a>
    <a class="nav-item nav-link" id="nav-news-tab" data-toggle="tab" href="#nav-news" role="tab" aria-controls="nav-news" aria-selected="false">News</a>
    <a class="nav-item nav-link" id="nav-change-logs-tab" data-toggle="tab" href="#nav-change-logs" role="tab" aria-controls="nav-change-logs" aria-selected="false">Changelog</a>
    <a class="nav-item nav-link" id="nav-logs-tab" data-toggle="tab" href="#nav-logs" role="tab" aria-controls="nav-logs" aria-selected="false">Logs</a>
    <a class="nav-item nav-link" id="nav-releases-tab" data-toggle="tab" href="#nav-releases" role="tab" aria-controls="nav-releases" aria-selected="false">Tags</a>
    <a class="nav-item nav-link" id="nav-branches-tab" data-toggle="tab" href="#nav-branches" role="tab" aria-controls="nav-branches" aria-selected="false">Branches</a>
    <a class="nav-item nav-link" id="nav-contributors-tab" data-toggle="tab" href="#nav-contributors" role="tab" aria-controls="nav-contributors" aria-selected="false">Contributors</a>
    <a class="nav-item nav-link" id="nav-contributions-tab" data-toggle="tab" href="#nav-contributions" role="tab" aria-controls="nav-contributions" aria-selected="false">Contributions</a>
    <a class="nav-item nav-link " id="nav-contribute-tab" data-toggle="tab" href="#nav-contribute" role="tab" aria-controls="nav-contribute" aria-selected="false">Contribute</a>
    <a class="nav-item nav-link " id="nav-todo-tab" data-toggle="tab" href="#nav-todo" role="tab" aria-controls="nav-todo" aria-selected="false">Todo</a>
    <a class="nav-item nav-link " id="nav-wiki-tab" data-toggle="tab" href="#nav-wiki" role="tab" aria-controls="nav-wiki" aria-selected="false">Wiki</a>
    <a class="nav-item nav-link " id="nav-issues-tab" data-toggle="tab" href="#nav-issues" role="tab" aria-controls="nav-issues" aria-selected="false">Issues</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-readme" role="tabpanel" aria-labelledby="nav-readme-tab"><div class="mt-5 mb-5">'.$this->readme().'</div></div>
  <div class="tab-pane fade show"  id="nav-code" role="tabpanel" aria-labelledby="nav-code-tab"><div class="mt-5 mb-5">'.$this->tree().'</div></div>
  <div class="tab-pane fade show " id="nav-news" role="tabpanel" aria-labelledby="nav-news-tab"><div class="mt-5 mb-5">'.$this->news().'</div></div>
  <div class="tab-pane fade show " id="nav-logs" role="tabpanel" aria-labelledby="nav-logs-tab"><div class="mt-5 mb-5">'.$this->log().'</div></div>
  <div class="tab-pane fade show " id="nav-change-logs" role="tabpanel" aria-labelledby="nav-change-logs-tab"><div class="mt-5 mb-5">'.$this->change_log().'</div></div>
  <div class="tab-pane fade show " id="nav-releases" role="tabpanel" aria-labelledby="nav-releases-tab"><div class="mt-5 mb-5">'.$this->release_view().'</div></div>
  <div class="tab-pane fade show " id="nav-branches" role="tabpanel" aria-labelledby="nav-branches-tab"><div class="mt-5 mb-5">'.$this->branches_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contributors" role="tabpanel" aria-labelledby="nav-contributors-tab"><div class="mt-5 mb-5">'.$this->contributors_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contributions" role="tabpanel" aria-labelledby="nav-contributions-tab"><div class="mt-5 mb-5">'.$this->contributions_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contribute" role="tabpanel" aria-labelledby="nav-contribute-tab"><div class="mt-5 mb-5">'.$this->contribute().'</div></div>
  <div class="tab-pane fade show " id="nav-todo" role="tabpanel" aria-labelledby="nav-todo-tab"><div class="mt-5 mb-5">'.$this->todo().'</div></div>
  <div class="tab-pane fade show " id="nav-wiki" role="tabpanel" aria-labelledby="nav-wiki-tab"><div class="mt-5 mb-5">'.$this->wiki().'</div></div>
  <div class="tab-pane fade show " id="nav-issues" role="tabpanel" aria-labelledby="nav-issues-tab"><div class="mt-5 mb-5">'.$this->report_bugs_view().'</div></div>
  <div class="tab-pane fade show " id="nav-licence" role="tabpanel" aria-labelledby="nav-licence-tab"><div class="mt-5 mb-5">'.$this->licence().'</div></div>
</div>';
            return $html;
        }


        /**
         *
         * Show form to compare two version
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function compare_form(): string
        {
            return (new Form())->start('compare')->row()->select(false,'first',$this->releases(),'<i class="material-icons">
trip_origin
</i>')->end_row_and_new()->select(false,'second',$this->releases(),'<i class="material-icons">
all_out
</i>')->end_row_and_new()->submit('compare','<i class="material-icons">
send
</i>')->end_row()->get();
        }

        /**
         *
         * Compare to version
         *
         * @param string $first
         * @param string $second
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function compare(string $first,string $second): string
        {
            return $this->change($first,$second);
        }

        /***
         *
         * Clone view
         *
         * @return string
         *
         */
        public function clone_view()
        {
            return '';
        }

        /**
         *
         * Display log
         *
         * @return string
         *
         * @throws Kedavra
         */
        public function log(): string
        {

            $size = intval(get('size',1));


            $period = get('period','month');

            $author = get('author','');

            not_in(GIT_PERIOD,$period,true,"Current period not valid");

            not_in(GIT_SIZE,$size,true,"Current size not valid");


            $format = '<a href="'.base_url($this->repository(),'diff',"%h").'"> %h</a> <a href="'.base_url().'?author=%an">%an</a> %s  %ar';

            $command = "git log --graph --oneline --color=always --after=$size.$period";

            if (def($author))
                append($command," --author='$author'");


            append($command," --pretty=format:'$format'");

            append($command," | aha > {$this->log_file()} ");

            $this->shell($command);

            return html_entity_decode((new File($this->log_file(),READ_FILE_MODE))->read());
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
         * @return array
         *
         */
        public function execute(string $command): array
        {

            exec($command,$this->data);

             return $this->data;
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
                return  Dir::scan('refs/heads');
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function contributors(): array
        {
            return app()->cache()->get($this->contributors_key);
        }

        /**
         *
         * List all versions
         *
         * @return array
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        private function save_releases(): array
        {
            if (app()->cache()->not($this->releases_key))
            {
                if ($this->is_remote())
                {
                    $releases =  collection(Dir::scan('refs/tags'))->reverse();
                }
                else
                {
                    $this->execute('git tag --sort=version:refname');
                    $releases =  $this->data()->reverse();
                }
                app()->cache()->set($this->releases_key,$releases,3600);
            }
            return app()->cache()->get($this->releases_key);
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
         * @throws Kedavra
         *
         */
        private function save_contributors(): array
        {
            if (app()->cache()->not($this->contributors_key))
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
                app()->cache()->set($this->contributors_key,$x->collection(),3600);
            }
          return  app()->cache()->get($this->contributors_key);
        }

        /**
         *
         *
         * @param string $sha1
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function modified(string $sha1)
        {

            $command = "git diff  -p  $sha1 --stat  --color=always | aha > {$this->log_file()}";

            $this->shell($command);

            return (new File($this->log_file()))->read();

        }

        public function tree(string $dir ='')
        {

            $data = '<div class="mt-5 mb-3">
                        <input type="search" class="form-control form-control-lg" id="search-file" onkeyup="search_files()">
                    </div>
                    <nav aria-label="breadcrumb">
                         <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">'.$this->repository().'</a>
                            </li>';

                            if (def($dir))
                            {
                                $dirs = explode(DIRECTORY_SEPARATOR,$dir);
                                $i = 0;
                                foreach ($dirs as $k => $v)
                                {
                                    if ($k === 0)
                                        append($data, ' <li class="breadcrumb-item"><a href="/'.$this->repository().'/'.$v.'">'.$v.'</a></li>');
                                    else
                                        append($data, ' <li class="breadcrumb-item"><a href="/'.$this->repository().'/'.$v[$i].'/'.$v[$k].'">'.$v[$k].'</a></li>');
                                }
                            }
            append($data,'</ol></nav><table  class="table table-bordered" id="files"><tbody>');


            foreach ($this->directories($dir) as $directory)
            {

                $x = collection(explode(DIRECTORY_SEPARATOR,$directory))->begin();

                append($data,'<tr><td> <a href="/'.$this->repository().'/tree/' . $x.'"><i class="material-icons">folder</i> ' .$x.'</a></td></tr>');

            }






            append($data,'</tbody></table>');
            return $data;
        }

        private function log_file()
        {
            return WEB_ROOT .DIRECTORY_SEPARATOR .'log.html';
        }

        /**
         *
         * A
         *
         * @return string
         *
         */
        public function todo(): string
        {
            return '';
        }


        /**
         *
         * Wiki views
         *
         * @return string
         *
         */
        public function wiki(): string
        {
            return '';
        }

        /**
         *
         * Report bugs views
         *
         * @return string
         *
         */
        public function report_bugs_view(): string
        {
            return '';
        }

        /**
         *
         * Display branches view
         *
         * @return string
         *
         */
        public function branches_view(): string
        {
            return '';
        }

        /**
         *
         * Display contribution view
         *
         * @param string $search_placeholder
         * @return string
         * @throws Kedavra
         */
        public function contributions_view(string $search_placeholder =  'Search a contributor'): string
        {

            $form = '<select class=" form-control-lg form-control" data-repository="'.$this->name.'" id="contributors_select"  data-months="'.$this->months()->join(',').'"><option value="Select a contributor">Select a contributor</option>                 
 ';
            foreach ($this->contributors() as  $k => $v)
                append($form,'<option value="'.$k.'" > '.$k.'</option>');

            append($form,'</select>');
            return '<div class="input-group mb-5">
                      <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">
                                    group
                                </i>
                            </span>
                      </div>
                    '.$form.' 
                </div>
                <canvas id="contributions"></canvas>';
        }

        /**
         *
         * Display the  licence
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function licence(): string
        {

            return '';



        }

        /**
         *
         * Remove an archive
         *
         * @param string $archive
         *
         * @return bool
         *
         */
        public function remove_archive(string $archive): bool
        {
            return File::exist($archive) ? File::delete($archive) : false;
        }

        /**
         *
         * Get release
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function releases(): array
        {
            return app()->cache()->get($this->releases_key);
        }

        /**
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function contributors_contributions(): array
        {
            return [];
        }

    }
}