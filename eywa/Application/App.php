<?php

declare(strict_types=1);

namespace Eywa\Application {

    use Eywa\Application\Environment\Env;
    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\CacheInterface;
    use Eywa\Cache\Filecache;
    use Eywa\Cache\MemcacheCache;
    use Eywa\Cache\RedisCache;
    use Eywa\Collection\Collect;
    use Eywa\Configuration\Config;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Query\Sql;
    use Eywa\Detection\Detect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Html\Form\Form;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\JsonResponse;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Http\Routing\Router;
    use Eywa\Http\View\View;
    use Eywa\Ioc\Container;
    use Eywa\Message\Email\Write;
    use Eywa\Message\Flash\Flash;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Security\Validator\Validator;
    use Eywa\Session\ArraySession;
    use Eywa\Session\Session;
    use Eywa\Session\SessionInterface;
    use Eywa\Time\Timing;
    use Redis;

    class App extends Zen implements Eywa
    {

        private Env $env;

        /**
         *
         * The layout name for all views in the controller
         *
         */
        protected static string $layout = 'layout.php';

        /**
         *
         * The directory for all views inside the controller
         *
         */
        protected static string $directory = '';

        /**
         *
         * @inheritDoc
         *
         */
        public function __construct()
        {
            cli() ? new Timing() : (new Session())->set('time',new Timing());

            $this->env = new Env();

            only($this->env->get(DISPLAY_BUGS) === 'true','whoops');
        }


        /**
         *
         * @inheritDoc
         *
         */
        public function env(string $key,$default = '')
        {
            $x =  $this->env->get($key);

            return def($x) ? $x : $default;
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return new Write($subject, $message, $author_email, $to);
        }


        /**
         *
         * @inheritDoc
         *
         */
        public function ioc(string $key)
        {
            return Container::ioc()->get($key);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function sql(string $table): Sql
        {
            return new Sql($this->connexion(),$table);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function crypter(): Crypter
        {
            return new Crypter();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function view(string $view, string $title, string $description, array $args = []): Response
        {
            return (new Response((new View($view,$title,$description,$args,static::$layout,static::$directory))->render()))->send();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function get(string $key, $default = null)
        {
            return $this->request()->query()->get($key,$default);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function post(string $key, $default = null)
        {
            return $this->request()->request()->get($key,$default);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function cookie(string $key, $default = null)
        {
            return $this->request()->cookie()->get($key,$default);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function server(string $key, $default = null)
        {
           return $this->request()->server()->get($key,$default);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function file(string $filename,string $mode = READ_FILE_MODE): File
        {
            return new File($filename,$mode);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function request(): Request
        {
            return cli() ? new Request() :  Request::generate();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function run(): Response
        {
            if (cli())
                return (new Router((new ServerRequest('/',GET))))->run();

            if (equal($this->config('mode','mode'),'down'))
                return (new Response((new View('maintenance',HTTP_SERVICE_UNAVAILABLE_TEXT,'Site in maintenance we comming soon'))->render(),'',503,['Retry-After'=> 600]))->send();

            return (new Router(ServerRequest::generate()))->run();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function form(string $route,string $method,array $route_args = [],array $params = []): Form
        {
            return new Form($route,$method,$route_args,$params);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function redis(): Redis
        {
            return new Redis();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function back(string $message = '', bool $success = true): Response
        {
            if (def($message))
                $success ?  $this->flash(SUCCESS,$message) : $this->flash(FAILURE,$message);

           return  not_cli() ? (new RedirectResponse($this->request()->server()->get('HTTP_REFERER')))->send() : (new RedirectResponse('/'))->send();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function to(string $route, string $message = '', bool $success = true): Response
        {
            if (def($message))
                $success ?  $this->flash(SUCCESS,$message) : $this->flash(FAILURE,$message);

            return (new RedirectResponse(route('web',$route)))->send();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function cache(int $type = FILE_CACHE): CacheInterface
        {
            switch ($type)
            {
                case APCU_CACHE:
                    return  new ApcuCache();
                break;
                case FILE_CACHE:
                    return  new Filecache();
                break;
                case MEMCACHE_CACHE:
                    return  new MemcacheCache();
                break;
                case REDIS_CACHE:
                    return  new RedisCache();
                break;
                default:
                    throw new Kedavra('The apdater is not supported');
                break;
            }

        }

        /**
         *
         * @inheritDoc
         *
         */
        public function decrypt(string $x, bool $unzerialize = true): string
        {
            return (new Crypter())->decrypt($x,$unzerialize);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function crypt(string $x, bool $unzerialize = true): string
        {
            return (new Crypter())->encrypt($x,$unzerialize);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function flash(string $key, string $message): void
        {
            if(not_cli())
                (new Flash())->set($key,$message);
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function auth(): Auth
        {
           return  new Auth(new Session());
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function check_form(): bool
        {
            if (not_cli())
            {
                $session = $this->session();
                if ($session->has(CSRF_TOKEN))
                {
                    return different((new Crypter())->decrypt($session->get('server')),Request::generate()->server()->get('SERVER_NAME','eywa'),true,"Form is not valid") || different($session->get('csrf'),collect(explode('==',$session->get(CSRF_TOKEN)))->last(),true,"Csrf token was not found");
                }
            }
            throw new Kedavra('Csrf token was not found');
        }
        /**

         *
         * @inheritDoc
         *
         */
        public function session(): SessionInterface
        {
            return cli() ? new ArraySession() : new Session();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function validator(array $data, string $lang = 'en'): Validator
        {
            return new Validator(collect($data),$lang);
        }

        /**
         * @inheritDoc
         */
        public function detect(): Detect
        {
            return new Detect();
        }

        /**
         *
         * Get the lang
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function lang(): string
        {
            return $this->request()->cookie()->get('locale',collect(explode('_',config('i18n','locale')))->first());
        }

        /**
         * @inheritDoc
         */
        public function config(string $file, string $key)
        {
            return (new Config($file,$key))->value();
        }

        /**
         * @inheritDoc
         */
        public function collect(array $data = []): Collect
        {
            return new Collect($data);
        }

        /**
         * @inheritDoc
         */
        public function connexion(): Connexion
        {
            return equal($this->config('mode','connexion'),'prod') ? production() : development();
        }

        /**
         * @inheritDoc
         */
        public function response(string $content, int $status = 200, array $headers = [], string $url = ''): Response
        {
           return new Response($content,$url,$status,$headers);
        }

        /**
         * @inheritDoc
         */
        public function redirect(string $route,array $route_args,string $message,bool $success,int $status = 301): Response
        {
            $success ? $this->flash(SUCCESS,$message) : $this->flash(FAILURE,$message);

            return (new RedirectResponse(route('web',$route,$route_args),$status))->send();
        }

        /**
         * @inheritDoc
         */
        public function files(string $key, $default = null)
        {
            return $this->request()->file()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function json(array $data,int $status = 200): Response
        {
            return  (new JsonResponse($data,$status))->send();
        }
    }
}