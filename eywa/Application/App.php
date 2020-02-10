<?php

declare(strict_types=1);

namespace Eywa\Application {

    use Eywa\Application\Environment\Env;
    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\CacheInterface;
    use Eywa\Cache\Filecache;
    use Eywa\Cache\MemcacheCache;
    use Eywa\Cache\RedisCache;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Detection\Detect;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Form\Form;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Request\ServerRequest;
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
    use Eywa\Session\Session;
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

            $this->env = new Env();

            if ($this->env->get(DISPLAY_BUGS)) { whoops(); }


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
            return new Sql(new Connect($this->env('DB_DRIVER'),$this->env('DB_NAME'),$this->env('DB_USERNAME'),$this->env('DB_PASSWORD'),intval($this->env('DB_PORT'))), $table);
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
        public function file(string $key, $default = null)
        {
            return $this->request()->file()->get($key,$default);
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
            return (new Router(ServerRequest::generate()))->run();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function form(string $route,string $method,array $params = [],array $route_args = []): Form
        {
            return new Form($route,$method,$params,$route_args);
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

            return (new RedirectResponse($this->request()->server()->get('HTTP_REFERER')))->send();

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
        public function session(): Session
        {
            return new Session();
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
    }
}