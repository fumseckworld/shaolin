<?php

	
	use Carbon\Carbon;
	use DI\DependencyException;
	use DI\NotFoundException;
	use Imperium\Html\Pagination\Pagination;
	use Imperium\Model\Web;
	use Imperium\Exception\Kedavra;
	use Sinergi\BrowserDetector\Os;
	use Imperium\Collection\Collect;
	use Symfony\Component\Console\Output\OutputInterface;
	
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

	
	if (!function_exists('routes'))
	{
		/**
		 *
		 * List routes
		 *
		 * @param OutputInterface $output
		 * @param array $routes
		 *
		 * @throws Kedavra
		 *
		 */
		function routes(OutputInterface $output, array $routes): void
		{
			
			if (def(request()->server->get('TMUX')))
			{
				if (def($routes))
				{
					$output->write("+----------+--------------------+-----------------------+-----------------------+-----------------------+\n");
					
					foreach ($routes as $route)
					{
						
						$name = "<fg=blue;options=bold>$route->name</>";
						
						$url = "<fg=magenta;options=bold>$route->url</>";
						$controller = "<fg=green;options=bold>$route->controller</>";
						$action = "<fg=yellow;options=bold>$route->action</>";
						$method = "<fg=cyan;options=bold>$route->method</>";
						
						if (sum($route->method) == 6)
							$output->write("|  $method  ");
						elseif (sum($route->method) == 4)
							$output->write("|  $method    ");
						elseif (sum($route->method) == 3)
							$output->write("|  $method     ");
						
						if (sum($route->name) < 5)
							$output->write("|  $name\t\t|");
						
						elseif (sum($route->name) > 10)
							$output->write("|  $name\t|");
						else
							$output->write("|  $name\t\t|");
						
						if (sum($route->url) < 5)
							$output->write("  $url\t\t\t|");
						elseif (sum($route->url) < 12)
							$output->write("  $url\t\t|");
						elseif (sum($route->url) > 18)
							$output->write("  $url\t|");
						else
							$output->write("  $url\t|");
						
						if (sum($route->controller) < 7)
							$output->write("  $controller\t\t|");
						elseif (sum($route->controller) < 10)
							$output->write("  $controller\t|");
						elseif (sum($route->controller) > 10 && sum($route->controller) < 15)
							$output->write("  $controller\t|");
						elseif (sum($route->controller) > 15)
							$output->write("  $controller\t|");
						else
							$output->write("  $controller\t|");
						
						if (sum($route->action) < 5)
							$output->write("  $action\t\t\t|\n");
						elseif (sum($route->action) < 10)
							$output->write("  $action\t\t|\n");
						elseif (sum($route->action) > 12)
							$output->write("  $action\t|\n");
						else
							$output->write("  $action\t|\n");
						$output->write("+----------+--------------------+-----------------------+-----------------------+-----------------------+\n");
					}
				} else
				{
					$output->write("<error>We have not found routes</error>\n");
				}
			} else
			{
				if (def($routes))
				{
					$output->write("+---------------+-------------------------------+---------------------------------------+---------------------------------------+-------------------------------+\n");
					
					foreach ($routes as $route)
					{
						
						$name = "<fg=blue;options=bold>$route->name</>";
						
						$url = "<fg=magenta;options=bold>$route->url</>";
						$controller = "<fg=green;options=bold>$route->controller</>";
						$action = "<fg=yellow;options=bold>$route->action</>";
						$method = "<fg=cyan;options=bold>$route->method</>";
						
						if (sum($route->method) > 4)
							$output->write("|  $method\t");
						else
							$output->write("|  $method\t\t");
						
						if (sum($route->name) < 5)
							$output->write("|  $name\t\t\t\t|");
						
						elseif (sum($route->name) > 10)
							$output->write("|  $name\t\t|");
						else
							$output->write("|  $name\t\t\t|");
						
						if (sum($route->url) < 5)
							$output->write("  $url\t\t\t\t\t|");
						elseif (sum($route->url) < 12)
							$output->write("  $url\t\t\t\t|");
						elseif (sum($route->url) > 18)
							$output->write("  $url\t\t|");
						else
							$output->write("  $url\t\t\t|");
						
						if (sum($route->controller) < 5)
							$output->write("  $controller\t\t\t\t\t|");
						elseif (sum($route->controller) < 8)
							$output->write("  $controller\t\t\t\t|");
						elseif (sum($route->controller) > 8 && sum($route->controller) < 15)
							$output->write("  $controller\t\t\t|");
						elseif (sum($route->controller) > 15)
							$output->write("  $controller\t\t\t|");
						else
							$output->write("  $controller\t\t\t|");
						
						if (sum($route->action) < 5)
							$output->write("  $action\t\t\t\t|\n");
						elseif (sum($route->action) < 10)
							$output->write("  $action\t\t\t|\n");
						elseif (sum($route->action) > 12)
							$output->write("  $action\t\t|\n");
						else
							$output->write("  $action\t\t\t|\n");
						
						$output->write("+---------------+-------------------------------+---------------------------------------+---------------------------------------+-------------------------------+\n");
					}
				} else
				{
					$output->write("<error>We have not found routes</error>\n");
				}
			}
			
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
		
		/**
		 *
		 * @param          $register_route_name
		 * @param          $login_route_name
		 * @param string $username_text
		 * @param string $lastname_text
		 * @param string $email_address_text
		 * @param string $password_text
		 * @param string $confirm_password_text
		 * @param string $create_account_text
		 * @param string $connexion_text
		 *
		 * @return string
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
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
		 * @param mixed $needle
		 * @param mixed $array
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
		 * @param array $array
		 * @param mixed $value
		 * @param bool $run_exception
		 * @param string $message
		 *
		 * @return bool
		 *
		 *
		 * @throws Kedavra
		 *
		 */
		function not_in(array $array, $value, bool $run_exception = false, string $message = ''): bool
		{
			
			$x = !in_array($value, $array, true);
			
			is_true($x, $run_exception, $message);
			
			return $x;
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
		 * @return int
		 *
		 * @throws Kedavra
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
				return round(($n / 1000), 2) . ' K';
			
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
			
			return php_sapi_name() !== 'cli' ? https() ? 'https://' . request()->server->get('HTTP_HOST') : 'http://' . request()->server->get('HTTP_HOST') : '/';
			
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
			
			$x = Web::where('name', EQUAL, $name)->fetch(true)->all();
			
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
		 * @param mixed $params
		 *
		 * @return string
		 *
		 */
		function url(...$params): string
		{
			
			return php_sapi_name() !== 'cli' ? https() ? 'https://' . request()->getHost() . '/' . collect($params)->join('/') : 'http://' . request()->getHost() . '/' . collect($params)->join('/') : '/' . collect($params)->join('/');
			
		}
		
	}
	
	if (!function_exists('append'))
	{
		
		/**
		 *
		 * Append contents to the variable
		 *
		 * @method append
		 *
		 * @param mixed $variable
		 * @param mixed $contents
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
	
	if (!function_exists('pagination'))
	{
		/**
		 * create a pagination
		 *
		 * @param  int  $current_page
		 * @param  int  $limit
		 * @param  int  $total
		 *
		 * @throws Kedavra
		 * @return string
		 */
		function pagination(int $current_page,int $limit,int $total): string
		{
			return  (new Pagination($current_page,$limit,$total))->paginate();
		}
		
	}
	
	if (!function_exists('clear_terminal'))
	{
		/**
		 * @throws Kedavra
		 * @return bool
		 */
		function clear_terminal(): bool
		{
			
			return os(true) === Os::WINDOWS ? is_not_false(system('cls')) : is_not_false(system('clear'));
			
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
		 * @throws NotFoundException
		 * @throws DependencyException
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
		 * @throws NotFoundException
		 * @throws DependencyException
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
		 * @throws NotFoundException
		 * @throws DependencyException
		 */
		function js(string $filename, string $type = ''): string
		{
			
			return app()->assets($filename)->js($type);
			
		}
	}
