<?php

namespace Imperium\Versioning\Git {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Highlight\Highlighter;
    use Imperium\Collection\Collection;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\Download;
    use Imperium\File\File;
    use Imperium\Html\Form\Form;
    use Imperium\Markdown\Markdown;
    use Symfony\Component\DependencyInjection\Tests\Compiler\D;
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
         * @var array
         */
        private $releases;

        /**
         * @var array
         */
        private $contributors;
        /**
         * @var string
         */
        private $changelog;

        /**
         * @var array
         */
        private $all_changelog;

        private $all_contributing;
        /**
         * @var string
         */
        private $contribute;
        /**
         * @var string
         */
        private $image;


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
            $this->all_contributing = config('git','contributing');
            $this->licences = config('git','licences');
            $this->all_changelog = config('git','changelog');

            $this->owner = $owner;


            if (different(app()->session()->get('repo'),realpath($repository)))
            {
                app()->cache()->clear();
            }

            app()->session()->set('repo',realpath($repository));

            $this->repository =  realpath($repository);

            is_false(Dir::is($this->repository),true,"The repository was not found");

            Dir::checkout($this->repository);
            $this->image = (new File('img'))->read();

            $this->name = strstr($repository,'.')  ? collection(explode(DIRECTORY_SEPARATOR,getcwd()))->last():  collection(explode(DIRECTORY_SEPARATOR,$repository))->last();
        }

        /**
         *
         * Download the latest version
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function download(): Response
        {

            $x = intval((new File("download"))->read());

            if (def($x))
                $x++;
            else
                $x = 1;

            (new File('download',EMPTY_AND_WRITE_FILE_MODE))->write("$x")->flush();


            $version = collection($this->releases())->begin();

            if (equal(os(true),'Windows'))
                return (new Download($this->create_archives('zip',$version)))->download();

            return (new Download($this->create_archives('tar.gz',$version)))->download();

        }


        /**
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function stars(): Response
        {

            $x = intval((new File("stars"))->read());

            if (def($x))
                $x++;
            else
                $x = 1;

            (new File('stars',EMPTY_AND_WRITE_FILE_MODE))->write("$x")->flush();

            return back();
        }

        public function search_files()
        {

        }
        /**
         *
         * List only file in a directory
         *
         * @param string $directory
         *
         * @param string $branch
         *
         * @return array
         *
         */
        public function files(string $directory,string $branch ='master'): array
        {
            def($directory) ?  $this->execute("git ls-tree  $branch -r $directory") : $this->execute("git ls-tree  $branch ");

            $x =  collection();

            foreach ($this->data as $datum)
            {
                if (def($directory))
                {
                    if (strstr($datum,'blob'))
                    {
                         $t =  collection(string_parse($datum))->last();

                         $t = str_replace("$directory/",'',$t);

                         if (!strstr($t,'/'))
                            $x->add($t);
                    }
                }else{
                    if (strstr($datum,'blob'))
                        $x->add(\collection(string_parse($datum))->last());
                }
            }

            return $x->collection();
        }

        /**
         *
         * Display contribution content
         *
         * @param string $branch
         * @return string
         * @throws Kedavra
         */
        public function contribute(string $branch ='master'):string
        {

            if (not_def($this->contribute))
            {
                $files = $this->files('',$branch);
                foreach ($this->all_contributing as $contribute)
                {
                    if (has($contribute,$files))
                    {
                        if ((new File($contribute))->ext() == 'md')
                        {
                            $this->contribute = (new Markdown($this->show($contribute)))->markdown();
                        }else{
                            $this->contribute =  $this->show($contribute);
                        }
                    }
                }
                assign(is_null($this->contribute),$this->contribute, 'We have not found a contribute file');
            }

            return $this->contribute;
        }

        /**
         *
         * Display contribution content
         *
         * @return string
         *
         * @throws Kedavra
         */
        public function changelog():string
        {

            if (is_null($this->changelog))
            {
                foreach ($this->all_changelog as $change)
                {
                    if (has($change,$this->all_changelog))
                        $this->changelog = (new Markdown($this->show($change)))->markdown();
                }

                assign(is_null($this->changelog),$this->changelog, 'We have not found a changelog');

            }
            return $this->changelog;

        }


        /**
         *
         * Display readme content
         *
         * @param string $branch
         * @return string
         *
         * @throws Kedavra
         */
        public function readme(string $branch = 'master')
        {

            if (is_null($this->readme))
            {
                $files = $this->files('',$branch);

                foreach ($this->all_readme as $readme)
                {
                    if (has($readme,$files))
                        $this->readme = (new Markdown($this->show($readme)))->markdown();
                }
                assign(is_null($this->readme),$this->readme, 'We have not found a readme');
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
         * @param string $branch
         * @return array
         *
         */
        public function directories(string $directory = '',string $branch = 'master'): array
        {

            if (def($directory))
                $this->execute("git ls-tree --name-only -d $branch  -r $directory");
            else
                $this->execute("git ls-tree --name-only -d $branch ");


            $directories = collection();

            foreach ($this->data as $dir)
            {
                if ($dir !== $directory)
                {
                    if (strpos($directory,$dir) !== 0)
                    {
                        $directories->add(\collection(explode('/',$dir))->last());
                    }

                }
            }

            return $directories->collection();
        }

        /**
         *
         * List repository
         *
         * @param string $directory
         *
         * @param string $file
         * @param string $branch
         * @return string
         * @throws Exception
         */
        public function tree(string $directory,string $file ='',string $branch = 'master'): string
        {
            $files = $this->files($directory,$branch);

            $complete = request()->getRequestUri();

            $parts = collection(explode('/tree',$complete));


            $current_directory = trim($parts->get(1),'/');

            $directories = $this->directories($directory,$branch);


            $data = '
                    <nav aria-label="breadcrumb">
                         <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch]).'">'.$this->repository().'</a>
                            </li>';



                $x = collection(explode('/',$current_directory));


                if (not_def($file))
                {
                    $ancient ='';

                    foreach ($x->collection() as $k=> $v)
                    {
                        append($ancient,"$v/");
                        if ($k ==0)
                            append($data, ' <li class="breadcrumb-item"><a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',$v]).'">'.$v.'</a></li>');
                        else
                            append($data,'<li class="breadcrumb-item">  <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',trim($ancient,'/')]) .'">' .$v.'</a></li>');
                    }

                }else
                {


                    $parts = collection(explode('/file',$complete));
                    $dir = trim($parts->get(1),'/');
                    $x = collection(explode('/',$dir));
                    $ancient ='';

                    foreach ($x->collection() as $k=> $v)
                    {
                        append($ancient,"$v/");
                        if ($k ==0)
                            append($data, ' <li class="breadcrumb-item"><a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',$v]).'">'.$v.'</a></li>');
                        else
                            append($data,'<li class="breadcrumb-item">  <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',trim($ancient,'/')]) .'">' .$v.'</a></li>');
                    }
                }
            append($data,'</ol></nav><table  class="table table-bordered" id="files"><tbody>');

            if (not_def($file))
            {

                foreach ($directories as $k => $v )
                {

                    if (def($current_directory))
                        append($data,'<tr><td> <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',$current_directory,$v]) .'"><i class="material-icons">folder</i> ' .$v.'</a></td></tr>');
                    else
                        append($data,'<tr><td> <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'tree',$v]) .'"><i class="material-icons">folder</i> ' .$v.'</a></td></tr>');


                }

                foreach ($files as  $file)

                    if (def($current_directory))
                        append($data,'<tr><td> <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'file',$current_directory,$file]).'"><i class="material-icons">insert_drive_file</i> ' .$file.'</a></td></tr>');
                    else

                    append($data,'<tr><td> <a href="'.base_url(['repo',$this->owner(),$this->repository(),$branch,'file',$file]).'"><i class="material-icons">insert_drive_file</i> ' .$file.'</a></td></tr>');


            }else
            {
                $x = new Highlighter();

                $x->setAutodetectLanguages(LANGUAGES);

                $code = $x->highlightAuto($this->show($file,$branch));

                $class = 'hljs ' . $code->language;

                $content = '<pre class="'.$class.'">' . $code->value . '</pre>';

                append($data,$content);
            }

            append($data,'</tbody></table>');

            return $data;
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function release_size(): int
        {
            return collection($this->releases())->length();
        }


        /**
         *
         * Change the repository image
         *
         * @param string $url
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function change_image(string $url)
        {
            return (new File('img',EMPTY_AND_WRITE_FILE_MODE))->write($url)->flush();
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function contributors_view(string $search_placeholder = 'Search a contributor'):string
        {

            $html = ' <div class="input-group mb-3">
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
         * @return string
         *
         * @throws Kedavra
         */
        public static function description(): string
        {
            return substr((new File(self::DESCRIPTION))->read(),0,50);
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function change(string $new_release,string $ancient_release): string
        {

            $tags = $this->releases();

            not_in($tags,$ancient_release,true,"The release $ancient_release was not found in the {$this->repository()} repository");

            not_in($tags,$new_release,true,"The release $new_release was not found in the {$this->repository()} repository");

            $this->clean();

             return shell_exec("git diff --word-diff --color-words $ancient_release $new_release  --patch-with-stat | aha");

        }


        /**
         *
         * Create repository archives
         *
         * @param string $ext
         *
         * @param string $version
         * @return string
         * @throws Kedavra
         */
        public function create_archives(string $ext,string $version): string
        {

            Dir::checkout($this->repository);
            Dir::create('releases');
            Dir::checkout('releases');
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * 
         */
        public function release_view(string $search_placeholder = 'Find a version'): string
        {
            $html = '<div class="mt-3 mb-2">'.$this->compare_form().'</div> 
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
         *
         * @param string $tree
         * @param string $file
         * @param string $branch
         * @return string
         *
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * @throws Exception
         */
        public function git(string $tree,string $file='',string $branch= 'master'): string
        {
            $this->checkout($branch);
            $html = '
                        <a href="'.root().'" class="btn btn-secondary"><i class="material-icons">apps</i>Apps</a> 
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4 mt-3">
                                <div class="input-group">
                                     <div class="input-group-prepend">
                                        <button class="btn btn-secondary" type="button" onclick="copy_public_clone_url()"><i class="material-icons">link</i></button>
                                     </div>
                                    <input type="text" class="form-control form-control-lg " id="clone" value="git://'.request()->getHost().'/'.$this->owner().'/'.$this->repository().'">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
                                <div class="input-group mt-3">
                                     <div class="input-group-prepend">
                                        <button class="btn btn-secondary" type="button" onclick="copy_contributor_clone_url()"><i class="material-icons">link</i></button>
                                     </div>
                                     <input type="text" class="form-control form-control-lg" id="contributor_clone" value="git@'.request()->getHost().':'.$this->owner().'/'.$this->repository().'">
                                </div>
                            </div> 
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4 mt-3">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="'.route('download',[$this->repository(),$this->owner()]).'" class="btn btn-secondary"><i class="material-icons">get_app</i>download <span class="badge badge-light">'.numb(intval((new File('download'))->read())).'</span></a>
                                   <a href="'.route('stars',[$this->repository(),$this->owner()]).'" class="btn btn-secondary"><i class="material-icons">star</i>Stars <span class="badge badge-light">'.numb(intval((new File('stars'))->read())).'</span></a>
                                </div>
                            </div>
                        </div>
                            
                      
                            '.$this->branches_view().'
                      
                           
                            <script>
                                function copy_public_clone_url() 
                                {
                                    /* Get the text field */
                                    let copyText = document.getElementById("clone");
                        
                                    /* Select the text field */
                                    copyText.select();
                        
                                    /* Copy the text inside the text field */
                                    document.execCommand("copy");
                                }  
                                function copy_contributor_clone_url() 
                                {
                                    /* Get the text field */
                                    let copyText = document.getElementById("contributor_clone");
                        
                                    /* Select the text field */
                                    copyText.select();
                        
                                    /* Copy the text inside the text field */
                                    document.execCommand("copy");
                                }
                            </script>
                            <section>
                       
                            <article>
                             <div class="nav nav-tabs" id="git" role="tablist">
     <a class="nav-item nav-link active" id="nav-code-tab" data-toggle="tab" href="#nav-code" role="tab" aria-controls="nav-code" aria-selected="true">Code</a>
    <a class="nav-item nav-link" id="nav-readme-tab" data-toggle="tab" href="#nav-readme" role="tab" aria-controls="nav-readme" aria-selected="true">Readme</a>
    <a class="nav-item nav-link " id="nav-licence-tab" data-toggle="tab" href="#nav-licence" role="tab" aria-controls="nav-licence" aria-selected="false">Licence</a>
    <a class="nav-item nav-link" id="nav-news-tab" data-toggle="tab" href="#nav-news" role="tab" aria-controls="nav-news" aria-selected="false">News</a>
    <a class="nav-item nav-link" id="nav-change-logs-tab" data-toggle="tab" href="#nav-change-logs" role="tab" aria-controls="nav-change-logs" aria-selected="false">Changelog</a>
    <a class="nav-item nav-link" id="nav-logs-tab" data-toggle="tab" href="#nav-logs" role="tab" aria-controls="nav-logs" aria-selected="false">Logs</a>
    <a class="nav-item nav-link" id="nav-releases-tab" data-toggle="tab" href="#nav-releases" role="tab" aria-controls="nav-releases" aria-selected="false">Versions</a>
    <a class="nav-item nav-link" id="nav-hooks-tab" data-toggle="tab" href="#nav-hooks" role="tab" aria-controls="nav-hooks" aria-selected="false">Hook</a>
    <a class="nav-item nav-link" id="nav-contributors-tab" data-toggle="tab" href="#nav-contributors" role="tab" aria-controls="nav-contributors" aria-selected="false">Contributors</a>
    <a class="nav-item nav-link" id="nav-contributions-tab" data-toggle="tab" href="#nav-contributions" role="tab" aria-controls="nav-contributions" aria-selected="false">Contributions</a>
    <a class="nav-item nav-link " id="nav-contribute-tab" data-toggle="tab" href="#nav-contribute" role="tab" aria-controls="nav-contribute" aria-selected="false">Contribute</a>
    <a class="nav-item nav-link " id="nav-todo-tab" data-toggle="tab" href="#nav-todo" role="tab" aria-controls="nav-todo" aria-selected="false">Todo</a>
    <a class="nav-item nav-link " id="nav-wiki-tab" data-toggle="tab" href="#nav-wiki" role="tab" aria-controls="nav-wiki" aria-selected="false">Wiki</a>
    <a class="nav-item nav-link " id="nav-issues-tab" data-toggle="tab" href="#nav-issues" role="tab" aria-controls="nav-issues" aria-selected="false">Issues</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show" id="nav-readme" role="tabpanel" aria-labelledby="nav-readme-tab"><div class="mt-3 mb-3">'.$this->readme().'</div></div>
  <div class="tab-pane fade show active"  id="nav-code" role="tabpanel" aria-labelledby="nav-code-tab"><div class="mt-3 mb-3">'.$this->tree($tree,$file,$branch).'</div></div>
  <div class="tab-pane fade show " id="nav-news" role="tabpanel" aria-labelledby="nav-news-tab"><div class="mt-3 mb-3">'.$this->news().'</div></div>
  <div class="tab-pane fade show " id="nav-logs" role="tabpanel" aria-labelledby="nav-logs-tab"><div class="mt-3 mb-3">'.$this->log($branch).'</div></div>
  <div class="tab-pane fade show " id="nav-change-logs" role="tabpanel" aria-labelledby="nav-change-logs-tab"><div class="mt-3 mb-3">'.$this->changelog().'</div></div>
  <div class="tab-pane fade show " id="nav-releases" role="tabpanel" aria-labelledby="nav-releases-tab"><div class="mt-3 mb-3">'.$this->release_view().'</div></div>
  <div class="tab-pane fade show " id="nav-hooks" role="tabpanel" aria-labelledby="nav-hooks-tab"><div class="mt-3 mb-3">'.$this->hooks_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contributors" role="tabpanel" aria-labelledby="nav-contributors-tab"><div class="mt-3 mb-3">'.$this->contributors_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contributions" role="tabpanel" aria-labelledby="nav-contributions-tab"><div class="mt-3 mb-3">'.$this->contributions_view().'</div></div>
  <div class="tab-pane fade show " id="nav-contribute" role="tabpanel" aria-labelledby="nav-contribute-tab"><div class="mt-3 mb-3">'.$this->contribute().'</div></div>
  <div class="tab-pane fade show " id="nav-todo" role="tabpanel" aria-labelledby="nav-todo-tab"><div class="mt-3 mb-3">'.$this->todo().'</div></div>
  <div class="tab-pane fade show " id="nav-wiki" role="tabpanel" aria-labelledby="nav-wiki-tab"><div class="mt-3 mb-3">'.$this->wiki().'</div></div>
  <div class="tab-pane fade show " id="nav-issues" role="tabpanel" aria-labelledby="nav-issues-tab"><div class="mt-3 mb-3">'.$this->report_bugs_view().'</div></div>
  <div class="tab-pane fade show " id="nav-licence" role="tabpanel" aria-labelledby="nav-licence-tab"><div class="mt-3 mb-3">'.$this->licence().'</div></div>
</div>
                        </article>
</section>
 ';
            return $html;
        }


        /**
         *
         * Show form to compare two version
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @param string $branch
         * @return string
         *
         * @throws Kedavra
         */
        public function log(string $branch = 'master'): string
        {

            $size = intval(get('size',1));


            $period = get('period','month');

            $author = get('author','');


            not_in(GIT_PERIOD,$period,true,"Current period not valid");

            not_in(GIT_SIZE,$size,true,"Current size not valid");


            $format = '<a href="'.base_url([$this->owner(),$this->repository(),$branch,'diff',"%h"]).'"> %h</a> <a href="'.'?author=%an">%an</a> %s  %ar';

            $command = "git log  --stat --graph --oneline --color=always --after=$size.$period $branch";

            if (def($author))
                append($command," --author='$author'");


            append($command," --pretty=format:'$format'");

            append($command," | aha ");


               return  html_entity_decode(shell_exec($command));
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
            $this->data = [];

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
         * List all versions
         *
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function releases(): array
        {

            if (app()->cache()->has(__FUNCTION__))
                return app()->cache()->get(__FUNCTION__);

           if (is_null($this->releases))
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
                app()->cache()->set(__FUNCTION__,$releases);
                $this->releases = $releases;
            }
            return $this->releases;
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function contributors(): array
        {
            if (app()->cache()->has(__FUNCTION__))
                return app()->cache()->get(__FUNCTION__);


            if (is_null($this->contributors))
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
                app()->cache()->set(__FUNCTION__,$x->collection());
              $this->contributors = $x->collection();
            }
          return  $this->contributors;
        }

        /**
         *
         *
         * @param string $sha1
         * @return string
         *
         *
         */
        public function modified(string $sha1)
        {
            return shell_exec( "git diff  -p  $sha1 --stat  --color=always | aha");
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function branches_view(): string
        {
            $html = '<div class="row">';
            $x = collection(['#'=> 'Select a branch']);
            foreach ($this->branches() as $branch)
                $x->add($branch,route('repository',['repo',$this->owner(),$this->repository(),$branch]));

            $branches = (new Form())
                ->start('checkout_branch')
                    ->row()
                        ->redirect('branch',$x->collection(),'<i class="material-icons">track_changes</i>')
                    ->end_row()
                ->get();

            append($html,'<div class="col-lg-12 col-md-12 col-sm-12 mt-3 col-xl-12>'.$branches.'</div>');

            $x = collection(['#'=> 'Select a period']);

            foreach (GIT_PERIOD as $period)
            {
                $z = '?period='.$period .'&size=' .get('size','') .'&author='.get('author','');

                $x->add($period,$z);
            }

            $time = (new Form())
                ->start('checkout_branch')
                ->row()
                ->redirect('period',$x->collection(),'<i class="material-icons">access_time</i>')
                ->end_row()
                ->get();
            append($html,'<div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">'.$time.'</div>');

            $x = collection(['#'=> 'Select a size']);

            foreach (GIT_SIZE as $size)
            {
                $z = '?size='.$size .'&period=' .get('period','months') .'&author='.get('author','');

                $x->add($size,$z);
            }

            $size = (new Form())
                ->start('checkout_branch')
                ->row()
                ->redirect('size',$x->collection(),'<i class="material-icons">access_time</i>')
                ->end_row()
                ->get();

            append($html,'<div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">'.$size.'</div>');
            $x = collection(['#'=> 'Select a contributor']);

            foreach (collection($this->contributors())->keys() as $contributor)
            {
                $z = '?author='.$contributor .'&period=' .get('period','') .'&size='.get('size','');

                $x->add($contributor,$z);
            }

            $author = (new Form())
                ->start('checkout_branch')
                ->row()
                ->redirect('author',$x->collection(),'<i class="material-icons">group</i>')
                ->end_row()
                ->get();
            append($html,'<div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">'.$author.'</div>');
            append($html,'</div>');

            return $html;
        }

        /**
         *
         * Display contribution view
         *
         * @return string
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function contributions_view(): string
        {

            $form = '<select class=" form-control-lg form-control" data-repository="'.$this->name.'" id="contributors_select"  data-months="'.$this->months()->join(',').'"><option value="Select a contributor">Select a contributor</option>                 
 ';
            foreach ($this->contributors() as  $k => $v)
                append($form,'<option value="'.$k.'" > '.$k.'</option>');

            append($form,'</select>');
            return '<div class="input-group mb-3">
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
         * @param string $branch
         * @return string
         */
        public function licence($branch= 'master'): string
        {
            
            if (is_null($this->licence))
            {

                $files = $this->files('',$branch);
                foreach ($this->licences as $licence)
                {
                    if (has($licence,$files))
                        $this->licence = nl2br($this->show($licence));
                }
                assign(is_null($this->licence),$this->licence, 'We have not found a licence');

            }
            return $this->licence;
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
         * Show file content
         *
         * @param string $file
         *
         * @param string $branch
         * @return string
         */
        public function show(string $file,string $branch ='master'): string
        {
            $x  = shell_exec("git show --color-words $branch:$file");

            return is_null($x) ? '' : $x;

        }

        public function hooks_view()
        {
            return '';
        }

        /**
         *
         * Display last update date
         *
         * @return string|null
         *
         */
        public function last_update(): string
        {
            $x =  shell_exec('git log -1 --format=%ar');
            return is_null($x) ? 'No commits found' : $x;
        }

        public function tags()
        {
            return '';
        }


    }
}