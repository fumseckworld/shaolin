<?php
use Carbon\Carbon;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Directory\Dir;
use Imperium\Model\Routes;
use Imperium\Routing\Route;
use Imperium\Exception\Kedavra;
use Sinergi\BrowserDetector\Os;
use Imperium\Collection\Collect;
use Imperium\Versioning\Git\Git;
use GuzzleHttp\Psr7\ServerRequest;

if (!function_exists('collect'))
{
    /**
     *
     * Return an instance of collection
     *
     * @method collection
     *
     * @param array $data The started array
     *
     * @return Collect
     *
     */
    function collect(array $data = []): Collect
    {
        return new Collect($data);
    }

}



if (!function_exists('env'))
{
    /**
     *
     * @param $variable
     *
     * @return array|false|string
     *
     */
    function env($variable)
    {
        return getenv($variable);
    }

}
if (!function_exists('connexion'))
{

    function connexion($register_route_name, $login_route_name, $username_text = 'Username', $lastname_text = 'Lastname', $email_address_text = 'Your Email adrress', $password_text = 'Password', $confirm_password_text = 'Confirm the password', $create_account_text = 'Create account', $connexion_text = 'Log in')
    {
        return '   <div class="mt-5 mb-10">
                    <div class="row">
                        <div class="column">
                            <div class="flex">
                                <div class="flex-initial">
                                    <div class="mb-3">
                                        <a class="btn-hollow"  href="' . root() . '">
                                            <i class="material-icons">apps</i> apps
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 mb-10">
                    <div class="row">
                        <div class="column">
                            <div class="flex">
                                <div class="flex-initial">
                                    <div class="mb-3">
                                        <a class="btn-hollow mr-4"  href="#" id="register">
                                            <i class="material-icons">person_add</i> ' . $create_account_text . '
                                        </a>  
                                        <a href="#" class="btn-hollow mr-4" id="login">
                                            <i class="material-icons">person</i>   ' . $connexion_text . '
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="register-form" class="hidden">
                <form action="' . route($register_route_name) . '" method="POST">
                    ' . csrf_field() . '
                    <input name="method" value="POST" class="hidden">' . '
                        <div class="row">
                            <div class="column">
                            <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">alternate_email</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="3" maxlength="254" type="email" placeholder="' . $email_address_text . '" name="email" required="required">
                            </div>                              
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">person</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="3" maxlength="254" type="text" placeholder="' . $username_text . '" name="firstname">
                            </div>                              
                        </div> 
                        <div class="column">
                            <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">person</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="3" maxlength="254" type="text" placeholder="' . $lastname_text . '" name="lastname" required="required">
                            </div>                              
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="column">
                            <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">vpn_key</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="8" maxlength="255"  type="password" placeholder="' . $password_text . '" name="password">
                            </div>                              
                        </div>
                        <div class="column">
                            <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">vpn_key</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="8" maxlength="255" type="password" placeholder="' . $confirm_password_text . '" name="confirm">
                            </div>                              
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <button type="submit"><i class="material-icons">person_add</i> ' . $create_account_text . '</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div id="login-form" class="hidden">
                <form action="' . route($login_route_name) . '" method="POST">
                    ' . csrf_field() . '
                    <input name="method" value="POST" class="hidden">
                    <div class="row">
                        <div class="column">
                                <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">person</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="3" maxlength="255" type="text" placeholder="' . $username_text . '" name="firstname" required="required">
                            </div>      
                        </div>     
                            
                        <div class="column">
                                <div class="input-container">
                                <span class="icon">
                                    <i class="material-icons">vpn_key</i>
                                </span>
                                <input class="input-field" autocomplete="off" minlength="8" maxlength="255" type="password" placeholder="' . $password_text . '" name="password" required="required">
                            </div>      
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <button type="submit"><i class="material-icons">person</i> ' . $connexion_text . '</button>
                        </div>
                    </div>
                </form>
            </div>';


    }
}

if (!function_exists('now'))
{
    /**
     *
     * Return an instance of Carbon
     *
     * @method now
     *
     * @param mixed $tz
     *
     * @return Carbon
     *
     */
    function now($tz = null): Carbon
    {
        return Carbon::now($tz);
    }

}

if (!function_exists('has'))
{
    
  
    /**
     * 
     * Check if the value exist
     * 
     * @method has
     *
     * @param  mixed $needle
     * @param  mixed $array
     *
     * @return bool
     * 
     */
    function has($needle, array $array): bool
    {
        return collect($array)->exist($needle);
    }
}

if (!function_exists('not_in'))
{
   
    /**
     * 
     * Check if a value is not in the array
     * 
     * @method not_in
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  bool   $run_exception
     * @param  string $message
     *
     * @throws Kedavra
     * 
     * @return bool
     * 
     * 
     */
    function not_in(array $array, $value, bool $run_exception = false, string $message = ''): bool
    {
        $x = !in_array($value, $array, true);

        is_true($x, $run_exception, $message);

        return $x;
    }


    if (!function_exists('display_repositories'))
	{

		/**
		 *
		 * Display repositories
		 *
		 * @param string $owner
		 *
		 * @throws Kedavra
		 *
		 * @return string
		 *
		 */
		function display_repositories(string $owner = ''): string
		{

			$username = not_def($owner) ? def(get('owner')) ? get('owner') : '*' : $owner;

			$data = [];
			$owners = collect();

			if (different($username, '*'))
			{

				foreach (Dir::scan(REPOSITORIES) as $owner)
				{
					if ($owner == $username)
					{
						foreach (Dir::scan(realpath(REPOSITORIES . DIRECTORY_SEPARATOR . $owner)) as $repository)
						{
							$data[$owner][] = $repository;
						}
					} else
					{
						$owners->uniq($owner);
					}
				}
			}

			if (equal($username, '*'))
			{
				if (app()->auth()->connected())
				{
					foreach (Dir::scan(REPOSITORIES) as $owner)
					{
						if (different($owner, logged_user()))
						{
							$owners->uniq($owner);

							foreach (Dir::scan(realpath(REPOSITORIES . DIRECTORY_SEPARATOR . $owner)) as $repository)
							{
								$data[$owner][] = $repository;
							}
						}
					}
				} else
				{
					foreach (Dir::scan(REPOSITORIES) as $owner)
					{

						$owners->uniq($owner);
							
				
						foreach (Dir::scan(realpath(REPOSITORIES . DIRECTORY_SEPARATOR . $owner)) as $repository)
						{

							$data[$owner][] = $repository;
						}
					}
				}
			}
			$data = collect($data);
			$request = ServerRequest::fromGlobals();

			if (app()->auth()->connected())
			{
				if (equal($request->getUri()->getPath(), '/home'))
					$code = '<div class="mt-10"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4"  href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow" href="' . route('logout') . '"><i class="material-icons">power_settings_new</i></a></div></div></div></div></div></div>'; else
					$code = '<div class="mt-10"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow mr-4" href="' . route('home') . '"><i class="material-icons">person</i></a><a class="btn-hollow" href="' . route('logout') . '"><i class="material-icons">power_settings_new</i></a></div></div></div></div></div></div>';
			} else
			{
				$code = '<div class="mt-10"><div class="row"><div class="column"><div class="flex"><div class="flex-start"><div class="mb-3"><a class="btn-hollow mr-4" href="' . root() . '"><i class="material-icons">group</i></a><a class="btn-hollow" href="' . route('connexion') . '"><i class="material-icons">person</i></a></div></div></div></div></div></div>';
			}


			$end_code = '';

			if (def($request->getUri()->getQuery()) || equal($request->getUri()->getPath(), '/home'))
			{
				append($code, '<div class="row"><div class="column"><input class="input" type="text" placeholder="Search a project"  onkeyup="search_project()" id="search_project"  autofocus="autofocus"> </div></div><div id="projects"><div class="row">');

			} else
			{
				append($code, '<div class="row"><div class="column"><input class="input" type="text" placeholder="Search a project"  onkeyup="search_project()" id="search_project"  autofocus="autofocus"> </div><div class="column"><select onChange="location = this.options[this.selectedIndex].value"><option value="Select an user" >Select an user</option>');
				foreach ($data->keys() as $x)
					append($code, '<option value="?owner=' . $x . '">' . $x . '</option>');

				append($code, '</select></div></div><div id="projects"><div class="row">');
			}


			$k = 0;


			foreach ($data->all() as $user => $repositories)
			{
                      
				foreach ($repositories as $repository)
				{
					$g = new Git($repository, $user);
					append($code, '
                    
                    <section class="column repository" id="' . $g->repository() . '">  
                      
                        <h2 class="title">' . $g->repository() . '</h2>
                        <hr>
                        <article class="text-center">
                            <p class="text">' . $g->description() . '</p>
                            <div class="inline-flex mt-4 mb-4">
                                <a class="btn-hollow mr-4" href="' . app()->url('repository', $g->owner(), $g->repository(), 'master') . '">
                                    <i class="material-icons">code</i> code
                                </a> ');
					if (!is_mobile())
						append($code, '<a class="btn-hollow mr-4" href="' . app()->url('download', $g->repository(), $g->owner()) . '"> <i class="material-icons">get_app</i>download</a>');

					append($code, ' 
                                <a class="btn-hollow" href="?owner=' . $g->owner() . '">
                                     <i class="material-icons">  person</i>  ' . $g->owner() . '
                                </a>
                            </div>
                            <div class="border-t bg-teal-900 border-teal-300 p-4">
                                ' . $g->last_update() . '
                            </div>
                        </article>             
                    </section>');


					if (!is_pair($k))
						append($code, '</div><div class="row">');


					$k++;
				}
				append($code, '</div><div class="row">');

			}


			append($code, $end_code, '</div></div><script> function search_project()
        { 
            let input, filter, ul, li, i;
            input = document.getElementById("search_project");
            filter = input.value;
            ul = document.getElementById("projects");
            li = ul.getElementsByClassName("repository");
           
            for (i = 0; i < li.length; i++)
            {
                let x = li[i].getAttribute("id");
             
                if (x.indexOf(filter) > -1)
                {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }   
            }
        }     
</script>');

			return $code;

		}
	}
}



if (!function_exists('sum'))
{
    /**
     *
     * Return the length of data
     *
     * @method length
     *
     * @param mixed $data
     *
     * @throws Kedavra
     *
     * @return int
     *
     */
    function sum($data): int
    {
        if (is_array($data))
            return count($data); 
        elseif (is_string($data))
            return strlen($data);
        else

        throw new Kedavra('The parameter must be a string or an array');
    }
}



if (!function_exists('numb'))
{
    #    Output easy-to-read numbers
    #    by james at bandit.co.nz
    function numb(int $x)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $x));

        // now filter it;
        if ($n >= 1000000000000)
            return round(($n / 1000000000000), 2) . ' T';
        else if ($n >= 1000000000)
            return round(($n / 1000000000), 2) . ' B'; 
        else if ($n >= 1000000)
            return round(($n / 1000000), 2) . ' M'; 
        else if ($n >= 1000)
            return round(($n / 1000),2) . ' K';

        return number_format($n);
    }
}
if (!function_exists('root'))
{
    
    /**
     * root
     *
     * @return string
     * 
     */
    function root(): string
    {
        return php_sapi_name() !== 'cli' ?  https() ? 'https://' . request()->server->get('HTTP_HOST') : 'http://' . request()->server->get('HTTP_HOST') : '/';

    }
    
}

if (!function_exists('route'))
{

    /**
     *
     * Get a route url
     *
     * @method route
     *
     * @param mixed $name
     * @param mixed $args
     *
     * @return string
     *
     * @throws Kedavra
     */
    function route(string $name, array $args = []): string
    {
        $x = Routes::where('name',EQUAL,$name)->fetch(true)->all();
        if (def($args))
        {

            $url = '';


            $data = explode('/', $x->url);
            $i = 0;
            foreach ($data as $k => $v)
            {

                if (def($v))
                {

                    if (strpos($v, ':') === 0)
                    {


                        if (collect($args)->has($i))
                        {
                            append($url, '/' . $args[$i]);
                            $i++;
                        }

                    } else
                    {

                        append($url, "/$v");
                    }
                }
            }

            return url(trim($url, '/'));

        }
        return url(trim($x->url, '/'));
    }

}

if (!function_exists('url'))
{
    
    /**
     * 
     * 
     * 
     * @method url
     *
     * @param  mixed $params
     *
     * @return string
     * 
     */
    function url(...$params): string
    {

        return php_sapi_name() !== 'cli' ? https() ? 'https://' . request()->getHost() . '/' .collect($params)->join('/') : 'http://' . request()->getHost() . '/' .collect($params)->join('/') : '/' . collect($params)->join('/');

    }
    
}


if (! function_exists('append'))
{
    
    /**
     * 
     * Append contents to the variable
     * 
     * @method append
     *
     * @param  mixed $variable
     * @param  mixed $contents
     *
     * @return void
     * 
     */
    function append(&$variable, ...$contents): void
    {
        foreach ($contents as $content)
            $variable .= $content;

    }

}

if (!function_exists('clear_terminal'))
{
    function clear_terminal(): bool
    {
        return os(true) === Os::WINDOWS ? def(system('cls')) : def(system('clear'));
    }
    
}

if (!function_exists('is_mobile'))
{
    /**
     *
     * Check if device is mobile
     *
     * @method is_mobile
     *
     * @return bool
     *
     */
    function is_mobile(): bool
    {
        return (new Os())->isMobile();
    }
}

if (!function_exists('is_pair'))
{
    /**
     * Check if number is pair
     *
     * @method is_pair
     *
     * @param int $x
     *
     * @return bool
     *
     */
    function is_pair(int $x): bool
    {
        return $x % 2 === 0;
    }
}
if (!function_exists('css'))
{
    /**
     *
     * Generate a css link
     *
     * @param string $filename
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    function css(string $filename): string
    {
        return app()->assets($filename)->css();
    }
}

if (!function_exists('img'))
{
    /**
     *
     * Generate a image link
     *
     * @param string $filename
     * @param string $alt
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    function img(string $filename, string $alt): string
    {
        return app()->assets($filename)->img($alt);
    }
}

if (!function_exists('js'))
{
    /**
     *
     * Generate a js link
     *
     * @param string $filename
     *
     * @param string $type
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    function js(string $filename, string $type = ''): string
    {
        return app()->assets($filename)->js($type);

    }
}
