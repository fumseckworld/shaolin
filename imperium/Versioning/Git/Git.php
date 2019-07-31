<?php
	
	namespace Imperium\Versioning\Git
	{
		
		use Exception;
		use Highlight\Highlighter;
		use Imperium\Collection\Collect;
		use Imperium\Connexion\Connect;
		use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
		use Imperium\File\Download;
		use Imperium\File\File;
		use Imperium\Html\Form\Form;
		use Imperium\Markdown\Markdown;
		use Imperium\Model\Model;
		use Imperium\Query\Query;
		use Imperium\Request\Request;
		use Imperium\Tables\Table;
		use Imperium\Writing\Write;
		use Sinergi\BrowserDetector\Os;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		use Symfony\Component\HttpFoundation\Response;
		
		/**
		 *
		 * Class Git
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Versioning\Git
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Git
		{
			
			/**
			 * @var Model
			 */
			private static $model;
			
			/**
			 * @var bool|string
			 */
			private static $repository;
			
			/**
			 * @var Connect
			 */
			private static $connect;
			
			/**
			 * @var string
			 */
			private static $name;
			
			/**
			 * @var Table
			 */
			private static $table;
			
			/**
			 * @var Query
			 */
			private static $query;
			
			/**
			 * @var array
			 */
			private $data = [];
			
			const DESCRIPTION = 'description';
			
			/**
			 * @var string
			 */
			private $owner;
			
			/**
			 *
			 * The readme content
			 *
			 * @var string
			 */
			private $readme;
			
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
			 * @var string
			 */
			private $contribute;
			
			const CONTRIBUTORS_TABLE = 'CREATE TABLE IF NOT EXISTS contributors ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE)';
			
			const BUGS_TABLE         = 'CREATE TABLE IF NOT EXISTS bugs ( id INTEGER PRIMARY KEY AUTOINCREMENT, subject TEXT(255) NOT NULL,email TEXT(255) NOT NULL,content TEXT(255) NOT NULL,created_at DATETIME NOT NULL )';
			
			const TODO_TABLE         = 'CREATE TABLE IF NOT EXISTS todo ( id INTEGER PRIMARY KEY AUTOINCREMENT, task TEXT(255) NOT NULL,contributor TEXT(255), finish_at DATETIME NOT NULL,created_at DATETIME NOT NULL )';
			
			/**
			 *
			 * Git constructor.
			 *
			 * @param  string  $repository
			 * @param  string  $owner
			 *
			 * @throws Kedavra
			 */
			public function __construct( string $repository, string $owner )
			{
				
				$this->owner = $owner;
				
				if ( different(app()->session()->get('repo'), realpath($repository)) )
				{
					app()->cache()->clear();
				}
				
				app()->session()->put('repo', realpath($repository));
				
				self::$repository = REPOSITORIES . DIRECTORY_SEPARATOR . $owner . DIRECTORY_SEPARATOR . $repository;
			
				self::model();
			
				self::connect();
			
				self::$name = collect(explode(DIRECTORY_SEPARATOR, $repository))->last();
			
				Dir::checkout(self::$repository);
				
			}
			
			/**
			 *
			 *
			 * @return Table
			 *
			 */
			public static function table() : Table
			{
				
				if ( is_null(self::$table) )
					self::$table = new Table(self::connect());
				
				return self::$table;
			}
			
			public static function query() : Query
			{
				
				if ( is_null(self::$query) )
					self::$query = new Query(self::table(), self::connect());
				
				return self::$query;
			}
			
			/**
			 *
			 * @return Model
			 *
			 */
			public static function model() : Model
			{
				
				if ( is_null(self::$model) )
					self::$model = new Model(self::connect(), self::table(), self::query(), new Request());
				
				return self::$model;
			}
			
			/**
			 *
			 * @return Connect
			 *
			 *
			 */
			public static function connect() : Connect
			{
				
				if ( is_null(self::$connect) )
					self::$connect = connect(SQLITE, self::$repository . DIRECTORY_SEPARATOR . self::$name . '.sqlite3', '', '', '', 'dump');
				
				return self::$connect;
			}
			
			/**
			 * @throws Kedavra
			 */
			private static function create_tables() : bool
			{
				
				return collect()->set(self::connect()->execute(self::CONTRIBUTORS_TABLE), self::connect()->execute(self::BUGS_TABLE), self::connect()->execute(self::TODO_TABLE))->ok();
			}
			
			/**
			 *
			 * Get archives extensions
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function archives_extensions() : array
			{
				
				return config('git', 'archives_extensions');
			}
			
			/**
			 *
			 * Get readme files
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function all_readme() : array
			{
				
				return config('git', 'readme');
			}
			
			/**
			 *
			 * Get readme files
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function contributing() : array
			{
				
				return config('git', 'contributing');
			}
			
			/**
			 *
			 * Get licences files
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function licences() : array
			{
				
				return config('git', 'licences');
			}
			
			/**
			 *
			 * Send the bug to the developer
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function send_bug() : RedirectResponse
			{
				
				$data = collect();
				
				foreach ( $this->model()->from('bugs')->columns() as $column )
					$data->set(request()->request->get($column));
				
				$x = $this->model()->from('bugs')->insert_new_record($this->model(), $data->all());
				
				if ( is_false($x) )
					return back('Failed to insert data', false);
				
				return ( new Write(request()->get('subject'), request()->get('content'), request()->get('email'), $this->email()) )->send() && $x ? back('Bug was send') : back('Email send has fail', false);
				
			}
			
			/**
			 *
			 * Download the latest version
			 *
			 * @throws Kedavra
			 * @return Response
			 *
			 */
			public function download() : Response
			{
				
				$x = intval(( new File("download") )->read());
				
				def($x) ? $x++ : $x = 1;
				
				( new File('download', EMPTY_AND_WRITE_FILE_MODE) )->write("$x")->flush();
				
				$version = collect($this->releases())->first();
				
				return equal(os(true), Os::LINUX) ? ( new Download($this->create_archives('tar.gz', $version)) )->download() : ( new Download($this->create_archives('zip', $version)) )->download();
				
			}
			
			/**
			 *
			 * @throws Kedavra
			 *
			 * @return Response
			 *
			 */
			public function stars() : Response
			{
				
				$x = intval(( new File("stars") )->read());
				
				def($x) ? $x++ : $x = 1;
				
				( new File('stars', EMPTY_AND_WRITE_FILE_MODE) )->write("$x")->flush();
				
				return back();
			}
			
			/**
			 *
			 * List only file in a directory
			 *
			 * @param  string  $directory
			 *
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function files( string $directory, string $branch = 'master' ) : array
			{
				
				def($directory) ? $this->execute("git ls-tree  $branch -r $directory") : $this->execute("git ls-tree  $branch ");
				
				$x = collect();
				
				foreach ( $this->data as $datum )
				{
					if ( def($directory) )
					{
						
						if ( strstr($datum, 'blob') )
						{
							$y = strstr(str_replace("$directory/", '', $datum), '/');
							
							if ( is_false($y) )
							{
								$file = str_replace("$directory/", '', collect(string_parse($datum))->last());
								$x->set($file);
							}
						}
						
					}
					else
					{
						if ( strstr($datum, 'blob') )
						{
							
							if ( not_def(strstr($datum, '/')) )
							{
								$x->uniq(collect(string_parse($datum))->last());
							}
						}
					}
				}
				
				return $x->all();
			}
			
			/**
			 *
			 * Display contribution content
			 *
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function contribute( string $branch = 'master' ) : string
			{
				
				if ( not_def($this->contribute) )
				{
					$files = $this->files('', $branch);
					foreach ( $this->contributing() as $contribute )
					{
						if ( has($contribute, $files) )
						{
							if ( ( new File($contribute) )->ext() == 'md' )
							{
								$this->contribute = ( new Markdown($this->show($contribute, $branch)) )->markdown();
							}
							else
							{
								$this->contribute = $this->show($contribute);
							}
						}
					}
					assign(is_null($this->contribute), $this->contribute, 'We have not found a contribute file');
				}
				
				return $this->contribute;
			}
			
			/**
			 *
			 * Display contribution content
			 *
			 * @return string
			 *
			 */
			public function changelog() : string
			{
				
				return '';
				
			}
			
			/**
			 *
			 * Display readme content
			 *
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function readme( string $branch = 'master' )
			{
				
				if ( is_null($this->readme) )
				{
					$files = $this->files('', $branch);
					
					foreach ( $this->all_readme() as $readme )
					{
						if ( has($readme, $files) )
							$this->readme = ( new Markdown($this->show($readme)) )->markdown();
					}
					assign(is_null($this->readme), $this->readme, 'We have not found a readme');
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
			public function owner() : string
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
			public function is_remote() : bool
			{
				
				return Dir::is('hooks') && Dir::is('refs');
			}
			
			/**
			 *
			 * Return the numbers of commits on the current branch
			 *
			 * @param  string  $branch
			 *
			 * @return string
			 *
			 */
			public function commits_size( string $branch ) : string
			{
				
				$this->execute("git rev-list --count $branch");
				
				return '<button type="button" class="btn btn-primary"><i class="material-icons">history</i> <span>' . numb(intval($this->data()->last())) . ' Commits</span></button>';
				
			}
			
			public function generate_changes_log()
			{
			
			}
			
			/**
			 *
			 * Generate archives
			 *
			 * @param  string  $ext
			 * @param  string  $version
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function generate_archives( string $ext, string $version ) : string
			{
				
				not_in(GIT_ARCHIVE_EXT, $ext, true, 'The used archives extension is not valid');
				
				return $this->create_archives($ext, $version);
			}
			
			/**
			 *
			 * Get all commits by the author between today and the 12 last months
			 *
			 * @param  string  $author
			 *
			 * @return Collect
			 *
			 */
			public function commits_by_year( string $author ) : Collect
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
			 * @param  string  $author
			 *
			 * @return Collect
			 *
			 *
			 */
			public function commits_by_month( string $author ) : Collect
			{
				
				$contributions = collect();
				$now = now()->days(1)->format('Y-m-d');
				$month = $this->months();
				
				$i = 0;
				$x = 1;
				do
				{
					
					if ( $i == 26 )
						$this->execute("git log --after=$now --before={$month->get($i)} --pretty=format:'%s' --author='$author'");
					else
						$this->execute("git log --after={$month->get($i)} --before={$month->get($x)} --pretty=format:'%s' --author='$author'");
					
					$contributions->put($month->get($i), $this->data()->sum());
					$i++;
					$x++;
				} while ( $i != $month->sum() );
				
				return $contributions;
				
			}
			
			/**
			 *
			 * @return Collect
			 *
			 */
			public function months() : Collect
			{
				
				$months = collect();
				$months->set(now()->addMonths(1)->days(1)->format('Y-m-d'));
				for ( $i = 0; $i != 14; $i++ )
				{
					$x = now()->addMonths(-$i)->days(1)->format('Y-m-d');
					$months->set($x);
				}
				
				return $months->reverse();
			}
			
			/**
			 *
			 * List repository directories
			 *
			 * @param  string  $directory
			 *
			 * @param  string  $branch
			 *
			 * @return array
			 *
			 */
			public function directories( string $directory = '', string $branch = 'master' ) : array
			{
				
				if ( def($directory) )
					$this->execute("git ls-tree --name-only -d $branch  -r $directory");
				else
					$this->execute("git ls-tree --name-only -d $branch ");
				
				$directories = collect();
				
				if ( def($directory) )
				{
					if ( $this->data()->sum() === 2 )
						return [];
					
					foreach ( $this->data as $dir )
					{
						
						$x = str_replace("$directory/", '', $dir);
						
						if ( strpos($directory, $dir) !== 0 )
						{
							
							if ( ! strstr($x, '/') )
								$directories->uniq($x);
							else
								$directories->uniq(collect(explode(DIRECTORY_SEPARATOR, $x))->first());
							
						}
					}
					
					return $directories->all();
				}
				
				return $this->data()->all();
				
			}
			
			/**
			 *
			 * List repository
			 *
			 * @param  string  $directory
			 *
			 * @param  string  $file
			 * @param  string  $branch
			 *
			 * @throws Exception
			 * @return string
			 */
			public function tree( string $directory, string $file = '', string $branch = 'master' ) : string
			{
				
				$files = $this->files($directory, $branch);
				
				$complete = request()->getRequestUri();
				
				$parts = collect(explode('/tree', $complete));
				
				$current_directory = trim($parts->get(1), '/');
				
				$directories = $this->directories($directory, $branch);
				
				$data = '
                         <nav>
                         <ul class="breadcrumb">
                            <li>
                                <a href="' . base_url($this->owner(), $this->repository(), $branch) . '">' . $this->repository() . '</a>
                            </li>';
				
				$x = collect(explode('/', $current_directory));
				
				if ( not_def($file) )
				{
					$ancient = '';
					
					foreach ( $x->all() as $k => $v )
					{
						if ( def($v) )
						{
							append($ancient, "$v/");
							
							append($data, '<li>  <a href="' . base_url($this->owner(), $this->repository(), $branch, 'tree', trim($ancient, '/')) . '">' . $v . '</a></li>');
						}
						
					}
					
				}
				else
				{
					
					$parts = collect(explode('/file', $complete))->get(1);
					$x = collect(explode('/', $parts));
					
					$ancient = '';
					
					foreach ( $x->all() as $k => $v )
					{
						if ( def($v) )
						{
							append($ancient, "$v/");
							
							append($data, '<li>  <a href="' . base_url($this->owner(), $this->repository(), $branch, 'tree', trim($ancient, '/')) . '">' . $v . '</a></li>');
							
						}
					}
				}
				
				append($data, '</ul></nav><table  class="tree" id="files"><tbody>');
				
				if ( not_def($file) )
				{
					
					foreach ( $directories as $k => $v )
					{
						
						if ( def($current_directory) )
							append($data, '<tr><td> <a href="' . base_url($this->owner(), $this->repository(), $branch, 'tree', $current_directory, $v) . '"><i class="material-icons">folder</i> ' . $v . '</a></td><td>' . shell_exec("git log $branch --pretty=format:'%s' -n1 -- $current_directory/$v") . '</td><td>' . shell_exec("git log $branch --pretty=format:'%ar' -n1 -- $current_directory/$v") . '</td></tr>');
						else
							append($data, '<tr><td> <a href="' . base_url($this->owner(), $this->repository(), $branch, 'tree', $v) . '"><i class="material-icons">folder</i> ' . $v . '</a></td><td>' . shell_exec("git log $branch --pretty=format:'%s' -n1 -- $v") . '</td><td>' . shell_exec("git log $branch --pretty=format:'%ar' -n1 -- $v") . '</td></tr>');
						
					}
					
					foreach ( $files as $file )
						
						if ( def($current_directory) )
							append($data, '<tr><td> <a href="' . base_url($this->owner(), $this->repository(), $branch, 'file', $current_directory, $file) . '"><i class="material-icons">insert_drive_file</i> ' . $file . '</a></td><td>' . shell_exec("git log $branch --pretty=format:'%s' -n1 -- $current_directory/$file") . '</td><td>' . shell_exec("git log $branch --pretty=format:'%ar' -n1 -- $current_directory/$file") . '</td></tr>');
						else
							
							append($data, '<tr><td> <a href="' . base_url($this->owner(), $this->repository(), $branch, 'file', $file) . '"><i class="material-icons">insert_drive_file</i> ' . $file . '</a></td><td>' . shell_exec("git log $branch --pretty=format:'%s' -n1 -- $file") . '</td><td>' . shell_exec("git log $branch --pretty=format:'%ar' -n1 -- $file") . '</td></tr>');
					
				}
				else
				{
					$x = new Highlighter();
					
					$code = $x->highlightAuto($this->show($file, $branch));
					
					$class = 'hljs ' . $code->language;
					
					$content = '<pre class="' . $class . '">' . $code->value . '</pre>';
					
					append($data, $content);
				}
				
				append($data, '</tbody></table>');
				
				return $data;
			}
			
			/**
			 *
			 * Get the name of the repository
			 *
			 * @return string
			 *
			 */
			public function repository() : string
			{
				
				return self::$name;
			}
			
			/**
			 *
			 * Repository path
			 *
			 * @return string
			 *
			 */
			public function path() : string
			{
				
				return self::$repository;
			}
			
			/**
			 *
			 * Result of command
			 *
			 * @return Collect
			 *
			 */
			public function data() : Collect
			{
				
				return collect($this->data);
			}
			
			/**
			 *
			 * Display all branches found
			 *
			 * @return string
			 *
			 */
			public function branches_found() : string
			{
				
				return $this->is_remote() ? numb(collect(Dir::scan('refs/heads'))->sum()) : numb(collect($this->branches())->sum());
			}
			
			/**
			 *
			 * Return the current branch
			 *
			 * @return string
			 *
			 */
			public function current_branch() : string
			{
				
				foreach ( $this->get_branch() as $branch )
				{
					if ( strpos($branch, '*') === 0 )
						return trim(str_replace('* ', '', $branch));
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
			public function branches() : array
			{
				
				$branches = collect();
				
				foreach ( $this->get_branch() as $branch )
					$branches->push(trim(str_replace('* ', '', $branch)));
				
				return $branches->all();
			}
			
			/**
			 *
			 * Display all release size
			 *
			 * @return string
			 *
			 */
			public function release_size() : string
			{
				
				return '<button type="button" class="btn btn-primary"><i class="material-icons">all_out</i> <span>' . numb(collect($this->releases())->sum()) . ' Releases</span></button>';
				
			}
			
			/**
			 *
			 * Display the equip size
			 *
			 * @throws Exception
			 *
			 * @return string
			 *
			 */
			public function contributors_size() : string
			{
				
				return '<button type="button" class="btn btn-primary"><i class="material-icons">group</i> <span>' . numb(collect($this->contributors())->sum()) . ' Contributors</span></button>';
			}
			
			/**
			 * @param  string  $search_placeholder
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function contributors_view( string $search_placeholder = 'Search a contributor' ) : string
			{
				
				$html = ' <div class="input-group mb-3">
                  <div class="input-group-prepend">
                        <span class="input-group-text bg-primary text-white">
                            <i class="material-icons">
                                group
                            </i>
                        </span>
                  </div>
                <input type="search" id="search_contributor"  placeholder="' . $search_placeholder . '" class="form-control form-control-lg">
            </div>
            <ul class=" list-unstyled row" id="contributors">';
				
				foreach ( $this->contributors() as $contributor )
					append($html, '<li class="col-md-4 col-lg-4 col-sm-12 col-xl-4 "><a href="">' . $contributor . '</a></li>');
				
				append($html, '</ul><canvas id="contrib"></canvas>');
				
				return $html;
			}
			
			/**
			 *
			 * Clone a repository
			 *
			 * @param  string  $url
			 * @param  string  $path
			 *
			 * @throws kedavra
			 *
			 * @return bool
			 *
			 */
			public static function clone( string $url, string $path ) : bool
			{
				
				is_true(equal($path, '.'), true, 'The path is not valid');
				
				is_true(equal($path, '..'), true, 'The path is not valid');
				
				is_true(Dir::is($path), true, 'The repository already exist');
				
				return is_null(shell_exec("git clone $url $path"));
				
			}
			
			/**
			 *
			 * Create a remote repository
			 *
			 * @param  string  $project_name
			 * @param  string  $owner
			 * @param  string  $description
			 * @param  string  $email
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public static function create( string $project_name, string $owner, string $description, string $email ) : bool
			{
				Dir::checkout(REPOSITORIES);

				if ( ! Dir::exist($owner) )
				{
					Dir::create($owner);
				}
				
				Dir::checkout($owner);
				
				if ( Dir::exist($project_name) )
					return false;
				
				Dir::create($project_name);
				
				Dir::checkout($project_name);
				
				( new File('email', EMPTY_AND_WRITE_FILE_MODE) )->write($email)->flush();
				
				shell_exec('git init --bare');
				
				is_false(( new File(self::DESCRIPTION, EMPTY_AND_WRITE_FILE_MODE) )->write($description), true, 'Failed to write description');
				
				return ( connect(SQLITE, "$project_name.sqlite3", '', '', '', '') )->queries(self::BUGS_TABLE, self::TODO_TABLE, self::CONTRIBUTORS_TABLE);
				
			}
			public static function remove(string $repository,string $owner): bool
			{
				Dir::checkout(REPOSITORIES);
				return Dir::remove($owner . DIRECTORY_SEPARATOR . $repository);
			}
			
			/**
			 *
			 * Get the issues email
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function email() : string
			{
				
				return ( new File('email') )->read();
			}
			
			/**
			 * @throws Kedavra
			 * @return Git
			 */
			public function save() : Git
			{
				
				$this->contributors();
				
				return $this;
			}
			
			/**
			 *
			 * Display the repository description
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function description() : string
			{
				
				return substr(( new File(self::DESCRIPTION) )->read(), 0, 50);
			}
			
			/**
			 *
			 * Execute git add
			 *
			 * @throws Exception
			 *
			 * @return Git
			 *
			 */
			public function add() : Git
			{
				
				is_false($this->shell('git add  .'), true, 'The git add command as fail');
				
				return $this;
			}
			
			/**
			 *
			 * Add a commit message
			 *
			 * @param  string  $message
			 *
			 * @throws Exception
			 *
			 * @return Git
			 *
			 */
			public function commit( string $message ) : Git
			{
				
				is_false($this->shell("git commit -m '$message'"), true, 'The git commit command as fail');
				
				return $this;
			}
			
			/**
			 *
			 * Display all last release change
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function news() : string
			{
				
				return not_def($this->releases()) ? 'No releases found' : $this->change(collect($this->releases())->get(0), collect($this->releases())->get(1));
			}
			
			/**
			 *
			 *Display all changes between two version
			 *
			 * @param  string  $new_release
			 * @param  string  $ancient_release
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function change( string $new_release, string $ancient_release ) : string
			{
				
				$tags = $this->releases();
				
				not_in($tags, $ancient_release, true, "The release $ancient_release was not found in the {$this->repository()} repository");
				
				not_in($tags, $new_release, true, "The release $new_release was not found in the {$this->repository()} repository");
				
				return shell_exec("git diff -p --stat --word-diff --color-words $ancient_release $new_release|  aha");
			}
			
			/**
			 *
			 * Create repository archives
			 *
			 * @param  string  $ext
			 *
			 * @param  string  $version
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function create_archives( string $ext, string $version ) : string
			{
				
				Dir::checkout(self::$repository);
				
				Dir::create('releases');
				
				Dir::checkout('releases');
				
				$file = $this->repository() . '-' . "$version.$ext";
				
				$name = self::$name;
				if ( ! File::exist($file) )
				{
					switch ( $ext )
					{
						case 'zip':
							$this->shell("git archive --format=$ext --prefix=$name-$version/ $version  > $file");
							break;
						default:
							$this->shell("git archive --format=$ext --prefix=$name-$version/ $version |  gzip > $file");
							break;
					}
				}
				
				return $file;
			}
			
			/**
			 *
			 * Display all releases
			 *
			 * @param  string  $search_placeholder
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function release_view( string $search_placeholder = 'Find a version' ) : string
			{
				
				$html = $this->compare_form() . '<div class="d-none" id="releases">

            <div class="input-group mb-3">
                  <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">
                                search
                            </i>
                        </span>
                  </div>
                <input type="search" id="search_release"  placeholder="' . $search_placeholder . '" class="form-control form-control-lg">
            </div>
            
            <ul class=" list-unstyled row" id="releases">';
				
				foreach ( $this->archives_extensions() as $ext )
				{
					foreach ( $this->releases() as $tag )
					{
						$x = php_sapi_name() !== 'cli' ? app()->url('archive', $this->repository(), $this->owner(), "$tag", $ext) : "/{$this->repository()}/refs/$tag.$ext";
						
						append($html, '<li class="col-md-3 col-lg-3 col-sm-12 col-xl-3"><a href="' . $x . '">' . $this->repository() . "-$tag.$ext" . '</a></li>');
						
					}
				}
				append($html, '</ul></div>');
				
				return $html;
			}
			
			/**
			 *
			 *
			 * @param  string  $tree
			 * @param  string  $file
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 * @throws Exception
			 *
			 * @return string
			 *
			 *
			 */
			public function git( string $tree, string $file = '', string $branch = 'master' ) : string
			{
				
				if ( app()->auth()->connected() )
					$code = '<div class="text-center"><div class="row"><div class="column"><div class="mb-3"><a class="btn-hollow mr-4" title="Home" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow mr-4" title="Home" href="' . route('home') . '"><i class="material-icons">person</i></a><a href="' . route('logout') . '" class="btn-hollow mr-4" title="Logout"><i class="material-icons">power_settings_new</i></a><a class="btn-hollow mr-4" title="Download the latest version for your system" href="' . route('download', [ $this->repository(), $this->owner() ]) . '"><i class="material-icons">get_app</i></a><a class="btn-hollow mr-4" title="Star the project" href="' . route('stars', [ $this->repository(), $this->owner() ]) . '"><i class="material-icons">star</i></a><a href="#" class="btn-hollow" id="report-bugs"><i class="material-icons">bug_report</i></a></div></div></div></div>';
				else
					$code = '<div class="text-center"><div class="row"><div class="column"><div class="mb-3"><a class="btn-hollow mr-4" title="Home" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow mr-4" title="Connexion" href="' . route('connexion') . '"><i class="material-icons">person</i></a><a class="btn-hollow mr-4" title="Download the latest version for your system" href="' . route('download', [ $this->repository(), $this->owner() ]) . '"><i class="material-icons">get_app</i></a><a class="btn-hollow mr-4" title="Star the project" href="' . route('stars', [ $this->repository(), $this->owner() ]) . '"><i class="material-icons">star</i></a><a href="#" class="btn-hollow" id="report-bugs"><i class="material-icons">bug_report</i></a></div></div></div></div>';
				
				$html = $code . '
                        <div class="hidden" id="report-bugs-form">
                            <form action="' . route('bug_report') . '" method="POST" accept-charset="utf-8">
                                
                                ' . csrf_field() . '
                                <div class="hidden">
                                    <input type="hidden" value="POST"   name="method">
                                    <input type="hidden" class="hidden" name="created_at" value="' . now()->toDateTimeString() . '" autocomplete="off">     
                                    <input type="hidden" class="hidden" name="id" value="id" >
                                    <input type="hidden" class="hidden" name="repository" value="' . self::$repository . '" autocomplete="off">
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <div class="input-container">
                                            <span class="icon">
                                                <i class="material-icons">bug_report</i>
                                            </span>
                                            <input type="text" class="input-field" required="required" placeholder="The bug subject" name="subject" autocomplete="off">
                                        </div>                           
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <div class="input-container">
                                            <span class="icon"><i class="material-icons">alternate_email</i></span>
                                            <input type="email" class="input-field" required="required" placeholder="Your Email address" name="email" value="" autocomplete="off">
                                        </div>
                                     </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <textarea  rows="10" placeholder="Explain the bug" required="required" name="content"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <button type="submit" class="btn"><i class="material-icons">send</i> Send the bug</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <article>
                            <header>    
                                <h1>' . $this->description() . '</h1>
                                <hr> 
                            </header>        
                            <div class="row">
                                <div class="column">
                                      <div class="input-container">
                                            <span class="icon cursor-pointer" onclick="copy_public_clone_url()">
                                                <i class="material-icons">link</i>
                                            </span>
                                            <input type="text" class="input-field" id="clone" value="git clone git://' . request()->getHost() . '/' . $this->owner() . '/' . $this->repository() . '">
                                      </div>
                                </div> 
                                <div class="column">
                                     <div class="input-container">
                                          <span class="icon cursor-pointer" onclick="copy_contributor_clone_url()">
                                                <i class="material-icons">link</i>
                                            </span>
                                            <input type="text" class="form-control form-control-lg" id="contributor_clone" value="git clone git@' . request()->getHost() . ':' . $this->owner() . '/' . $this->repository() . '">
                                      </div>
                                </div>
                            </div>
                          ' . $this->branches_view() . '
                                    
                              
                        </article>
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
                    
                        
                        <div class="row">
                            <div class="column">
                                 ' . $this->tree($tree, $file, $branch) . '
                                 <div id="readme">' . $this->readme($branch) . ' </div>
                            </div>
                        </div>
                     
                   ';
				
				return $html;
			}
			
			/**
			 *
			 * Show form to compare two version
			 *
			 * @return string
			 *
			 */
			public function compare_form() : string
			{
				
				$data = '';
				
				foreach ( $this->releases() as $release )
					append($data, '<option value="' . $release . '">' . $release . '</option>');
				
				return '<div id="compare-form"><div class="mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">trip_origin</i></span>
                            </div> 
                            <select id="first-release" class="form-control form-control-lg">' . $data . '</select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">all_out</i></span>
                            </div>
                            <select id="second-release" class="form-control form-control-lg">' . $data . '</select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="btn-group" role="group">
                            <button type="button"  id="compare-version"  data-content="' . self::$repository . '" class="btn btn-secondary">compare</button>
                            <button type="button"  id="compare-version-clear" class="btn btn-secondary">clear</button>
                            <button type="button" id="search-version" class="btn btn-secondary">show</button>           
                        </div>
                    </div> 
                </div>
                <div id="changes_content" class="mt-3"></div>';
			}
			
			/**
			 *
			 * Compare to version
			 *
			 * @param  string  $first
			 * @param  string  $second
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function compare( string $first, string $second ) : string
			{
				
				return $this->change($first, $second);
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
				
				return '<div class="row"><div class="column"><input type="text" value="git:://' . request()->getHost() . '/' . $this->owner() . '/' . $this->repository() . '"></div></div>';
			}
			
			/**
			 *
			 * Display log
			 *
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function log( string $branch = 'master' ) : string
			{
				
				$size = intval(get('size', 1));
				
				$period = get('period', 'month');
				
				$author = get('author', '');
				
				not_in(GIT_PERIOD, $period, true, "Current period not valid");
				
				not_in(GIT_SIZE, $size, true, "Current size not valid");
				
				$format = '<a href="' . base_url($this->owner(), $this->repository(), $branch, 'diff', "%h") . '"> %h</a> <a href="' . '?author=%an">%an</a> %s  %ar';
				
				$command = "git log  --stat --graph --oneline --color=always --after=$size.$period $branch";
				
				if ( def($author) )
					append($command, " --author='$author'");
				
				append($command, " --pretty=format:'$format'");
				
				append($command, " | aha ");
				
				return html_entity_decode(shell_exec($command));
			}
			
			/**
			 *
			 *
			 * @param  string  $sha1
			 *
			 * @return string
			 *
			 */
			public function removed_added( string $sha1 ) : string
			{
				
				return $this->lines($sha1)->last();
			}
			
			/**
			 *
			 * @param  string  $sha1
			 *
			 * @return Collect
			 *
			 */
			public function lines( string $sha1 ) : Collect
			{
				
				$this->execute("git show $sha1 --stat ");
				
				return $this->data();
			}
			
			/**
			 *
			 * Get a collection of all remotes
			 *
			 * @return Collect
			 *
			 */
			public function remote() : Collect
			{
				
				$this->execute('git remote');
				
				return $this->data();
			}
			
			/**
			 *
			 * Send all modifications to the server
			 *
			 * @throws Kedavra
			 *
			 * @return  bool
			 *
			 */
			public function push() : bool
			{
				
				foreach ( $this->remote()->all() as $remote )
				{
					is_false($this->shell("git push $remote --all"), true, "Failed to send modifications");
					
					is_false($this->shell("git push $remote --tags"), true, "Failed to send new release");
				}
				
				return true;
			}
			
			/**
			 *
			 * Execute  a shall command
			 *
			 * @param  string  $command
			 *
			 * @return bool
			 *
			 */
			public function shell( string $command ) : bool
			{
				
				return is_null(shell_exec($command));
			}
			
			/**
			 *
			 * @param  string  $command
			 *
			 * @return array
			 *
			 */
			public function execute( string $command ) : array
			{
				
				$this->data = [];
				
				exec($command, $this->data);
				
				return $this->data;
			}
			
			public function commits( string $directory, $branch = 'master' )
			{
				
				$data = collect();
				foreach ( $this->directories($directory, $branch) as $v )
					
					$data->set(shell_exec("git log $branch -n1  --pretty=format='%s' -- $directory/$v"));
				
				return $data;
			}
			
			/**
			 *
			 * Return all branch
			 *
			 * @return array
			 */
			private function get_branch() : array
			{
				
				return Dir::scan('refs/heads');
			}
			
			/**
			 *
			 * Show the repositories status
			 *
			 * @return Collect
			 *
			 */
			public function status() : Collect
			{
				
				$this->execute('git status');
				
				return $this->data();
				
			}
			
			/**
			 *
			 * List all versions
			 *
			 * @return array
			 *
			 */
			public function releases() : array
			{
				
				if ( is_null($this->releases) )
					$this->releases = collect(Dir::scan('refs/tags'))->reverse()->all();
				
				return $this->releases;
			}
			
			/**
			 *
			 * Get all contributors
			 *
			 * @throws Kedavra
			 * @return array
			 *
			 *
			 */
			public function contributors() : array
			{
				
				self::create_tables();
				
				$contributors = collect();
				
				foreach ( $this->execute("git shortlog -n --all") as $contributor )
					$contributors->uniq(collect(string_parse($contributor))->pop()->join(" "));
				
				foreach ( $contributors->reverse() as $contributor )
				{
					self::model()->from('contributors')->insert_new_record(self::model(), [ 'id' => 'id', 'name' => $contributor ]);
					
				}
				
				return $contributors->all();
			}
			
			/**
			 *
			 *
			 * @param  string  $sha1
			 *
			 * @return string
			 *
			 *
			 */
			public function modified( string $sha1 )
			{
				
				return shell_exec("git diff  -p  $sha1 --stat  --color=always | aha");
			}
			
			/**
			 *
			 * @param  array  $data
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function add_todo( array $data ) : bool
			{
				
				return $this->model()->from('todo')->insert_new_record($this->model(), $data);
			}
			
			/**
			 *
			 * A
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function todo() : string
			{
				
				$column = collect(config('auth', 'columns'))->get('auth');
				
				if ( app()->auth()->connected() && equal(current_user()->$column, $this->owner()) )
				{
					
					$x = '<div class="col-lg-4 col-md-4 col-sm-12 col-xl-4 mt-3"> <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">group</i></span>
                                  </div><select class=" form-control-lg form-control"  id="todo-contributor" ><option value="Select a contributor">Select a contributor</option>';
					
					foreach ( $this->contributors() as $contributor )
						append($x, '<option value="' . $contributor->name . '" > ' . $contributor->name . '</option>');
					
					append($x, '</select></div></div>');
					
					$html = '';
					append($html, '<div class="mt-3">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4 mt-3">
                               <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">note</i></span>
                                  </div>
                                  <input type="text" id="todo-task" class="form-control form-control-lg" placeholder="todo" autofocus="autofocus">
                                </div>
                            </div>
                            ' . $x . '
                             <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4 mt-3">
                               <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">timer</i></span>
                                  </div>
                                  <input type="date" id="todo-end"  class="form-control form-control-lg"  placeholder="task">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-primary" type="button" id="add-todo" data-repository="' . $this->path() . '" data-date="' . now()->toDateTimeString() . '" ><i class="material-icons">add</i></button>
                                  </div>   
                                   <div class="input-group-prepend">
                                        <button class="btn btn-danger" type="button" id="close-all-todo" data-repository="' . $this->path() . '"  ><i class="material-icons">done_all</i></button>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="d-none mt-3 alert" id="todo-response"></div>');
				}
				else
				{
					$html = '';
				}
				
				return \Imperium\Html\Table\Table::table($this->model()->from('todo')->columns(), $this->model()->from('todo')->all(), 'table-responsive mt-3', '', $html, '')->remove_action('close', 'Are you sure ?', app()->url('close_todo', $this->owner(), $this->repository()))->use_ago()->generate('table');
				
			}
			
			/**
			 *
			 * Wiki views
			 *
			 * @return string
			 *
			 */
			public function wiki() : string
			{
				
				return '';
			}
			
			/**
			 *
			 * Report bugs views
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function report_bugs_view() : string
			{
				
				$form = ( new Form() )->validate()->start('bug_report', 'Send the bug ?')->hide()->input(Form::HIDDEN, 'created_at', '', '', 'a', 'a', now()->toDateTimeString())->input(Form::HIDDEN, 'id', '', '', 'a', 'a', 'id')->input(Form::HIDDEN, 'repository', 'repository', '', 'a', 'a', $this->path())->end_hide()->row()->input(Form::TEXT, 'subject', 'The bug subject', '<i class="material-icons">bug_report</i>', 'The subject will be used', 'Subject cannot be empty')->end_row_and_new()->input(Form::EMAIL, 'email', 'Your Email address', '<i class="material-icons">email</i>', 'Email address will be used', 'The email address cannot be empty')->end_row_and_new()->textarea('content', 'Explain the bug', 'The message will be use', 'The message cannot be empty')->end_row_and_new()->submit('Send the bug', '<i class="material-icons">send</i>')->end_row()->get();
				
				$bugs = $this->model()->from('bugs')->all();
				append($form, '<div class=""><table class=" table"><thead><tr><th>id</th><th>subject</th><th>content</th><th>ago</th></tr></thead><tbody>');
				
				foreach ( $bugs as $bug )
					append($form, '<tr><td>#' . $bug->id . '</td><td>' . $bug->subject . '</td><td>' . $bug->content . '</td><td>' . ago('en', $bug->created_at) . '</td></tr>');
				
				append($form, '</tbody></table></div>');
				
				return $form;
				
			}
			
			/**
			 *
			 * Display branches view
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function branches_view() : string
			{
				
				$html = '<div class="row"><div class="column"><div class="input-container"><span class="icon"><i class="material-icons">explore</i></span><select class="input-field" onChange="location = this.options[this.selectedIndex].value"><option value="Select a branch">Select a branch</option>';
				
				foreach ( $this->branches() as $branch )
					append($html, '<option value="' . route('repository', [ $this->owner(), $this->repository(), $branch ]) . '">' . $branch . '</option>');
				
				append($html, '</select></div></div></div>');
				
				return $html;
			}
			
			/**
			 *
			 * Display contribution view
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function contributions_view() : string
			{
				
				$form = '<select class=" form-control-lg form-control" data-repository="' . self::$repository . '" id="contributors_select"  data-months="' . $this->months()->join(',') . '"><option value="Select a contributor">Select a contributor</option>';
				
				foreach ( $this->contributors() as $contributor )
					append($form, '<option value="' . $contributor . '" > ' . $contributor . '</option>');
				
				append($form, '</select>');
				
				return '<div class="input-group mb-3">
                      <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">
                                    group
                                </i>
                            </span>
                      </div>
                    ' . $form . ' 
                </div>
                <canvas id="contributions"></canvas>';
			}
			
			/**
			 *
			 * Display the  licence
			 *
			 * @param  string  $branch
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public function licence( $branch = 'master' ) : string
			{
				
				if ( is_null($this->licence) )
				{
					$files = $this->files('', $branch);
					foreach ( $this->licences() as $licence )
					{
						if ( has($licence, $files) )
							$this->licence = nl2br($this->show($licence));
					}
					assign(is_null($this->licence), $this->licence, 'We have not found a licence');
					
				}
				
				return $this->licence;
			}
			
			/**
			 *
			 * Remove an archive
			 *
			 * @param  string  $archive
			 *
			 * @return bool
			 *
			 */
			public function remove_archive( string $archive ) : bool
			{
				
				return File::exist($archive) ? File::delete($archive) : false;
			}
			
			/**
			 *
			 * Show file content
			 *
			 * @param  string  $file
			 *
			 * @param  string  $branch
			 *
			 * @return string
			 */
			public function show( string $file, string $branch = 'master' ) : string
			{
				
				$x = shell_exec("git show --color-words $branch:$file");
				
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
			public function last_update() : string
			{
				
				$x = shell_exec('git log -1 --format=%ar');
				
				return is_null($x) ? 'No commits found' : $x;
			}
			
			public function tags()
			{
				
				return '';
			}
			
			/**
			 *
			 * Remove a task
			 *
			 * @param  int  $id
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function close_todo( int $id ) : RedirectResponse
			{
				
				return $this->model()->from('todo')->remove($id) ? back('Todo was deleted successfully') : back('Failed to close the todo', false);
			}
			
			/**
			 *
			 * Close all task
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function close_all_todo() : bool
			{
				
				return self::model()->table()->drop('todo') && self::model()->execute(self::TODO_TABLE);
			}
			
		}
	}
