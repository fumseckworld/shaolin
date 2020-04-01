<?php

declare(strict_types=1);

namespace Eywa\Application {

    use Eywa\Application\Environment\Env;
    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\CacheInterface;
    use Eywa\Cache\FileCache;
    use Eywa\Cache\MemcacheCache;
    use Eywa\Cache\RedisCache;
    use Eywa\Collection\Collect;
    use Eywa\Configuration\Config;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Detection\Detect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\JsonResponse;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Http\Routing\Router;
    use Eywa\Http\View\View;
    use Eywa\Ioc\Ioc;
    use Eywa\Message\Email\Write;
    use Eywa\Message\Flash\Flash;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Session\ArraySession;
    use Eywa\Session\Session;
    use Eywa\Session\SessionInterface;
    use Redis;
    use ReflectionClass;

    class App extends Zen implements Eywa
    {
        private Env $env;

        /**
         *
         * The layout name for all views in the controller
         *
         */
        protected static string $layout = 'layout';

        /**
         *
         * The directory for all views inside the controller
         *
         */
        protected static string $directory = '';

        /**
         * @inheritDoc
         */
        public function __construct()
        {

            $this->env = new Env();

            only(strval($this->env->get(DISPLAY_BUGS)) == 'true', 'whoops');
        }


        /**
         * @inheritDoc
         */
        public function env(string $key, $default = '')
        {
            $x =  $this->env->get($key);

            return def($x) ? $x : $default;
        }

        /**
         * @inheritDoc
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return new Write($subject, $message, $author_email, $to);
        }


        /**
         * @inheritDoc
         */
        public function ioc(string $key)
        {
            return Ioc::get($key);
        }



        /**
         * @inheritDoc
         */
        public function crypter(): Crypter
        {
            return new Crypter();
        }

        /**
         * @inheritDoc
         */
        public function view(string $view, string $title, string $description, array $args = []): Response
        {
            return (
                new Response(
                    (new View($view, $title, $description, $args, static::$layout, static::$directory))->render()
                ))->send();
        }

        /**
         * @inheritDoc
         */
        public function get(string $key, $default = null)
        {
            return $this->request()->query()->get($key, $default);
        }

        /**
         * @inheritDoc
         */
        public function post(string $key, $default = null)
        {
            return $this->request()->request()->get($key, $default);
        }

        /**
         * @inheritDoc
         */
        public function cookie(string $key, $default = null)
        {
            return $this->request()->cookie()->get($key, $default);
        }

        /**
         * @inheritDoc
         */
        public function server(string $key, $default = null)
        {
            return $this->request()->server()->get($key, $default);
        }

        /**
         * @inheritDoc
         */
        public function file(string $filename, string $mode = READ_FILE_MODE): File
        {
            return new File($filename, $mode);
        }

        /**
         * @inheritDoc
         */
        public function request(array $args = []): Request
        {
            return cli() ? new Request([], [], [], [], [], $args) :  Request::make($args);
        }

        /**
         * @inheritDoc
         */
        public function run(): Response
        {
            if (cli()) {
                return (new Router((new ServerRequest('/', GET))))->run();
            }

            if (equal($this->config('mode', 'mode'), 'down')) {
                return
                    (new Response(
                        (new View(
                            'maintenance',
                            HTTP_SERVICE_UNAVAILABLE_TEXT,
                            'We comming soon'
                        ))
                        ->render(),
                        '',
                        503,
                        ['Retry-After' => 600]
                    ))->send();
            }

            return (new Router(ServerRequest::generate()))->run();
        }

        /**
         * @inheritDoc
         */
        public function redis(): Redis
        {
            return new Redis();
        }

        /**
         * @inheritDoc
         */
        public function back(string $message = '', bool $success = true): Response
        {
            if (def($message)) {
                $success ?  $this->flash(SUCCESS, $message) : $this->flash(FAILURE, $message);
            }

            return  not_cli()
                ? (new RedirectResponse(
                    $this->request()->server()->get('HTTP_REFERER')
                ))->send()
                : (new RedirectResponse('/'))->send();
        }

        /**
         * @inheritDoc
         *
         */
        public function to(string $route, array $route_args = [], string $message = '', bool $success = true): Response
        {
            if (def($message)) {
                $success ?  $this->flash(SUCCESS, $message) : $this->flash(FAILURE, $message);
            }

            return (new RedirectResponse(route($route, $route_args)))->send();
        }

        /**
         * @inheritDoc
         */
        public function cache(int $type = FILE_CACHE): CacheInterface
        {
            switch ($type) {
                case APCU_CACHE:
                    return  new ApcuCache();
                case FILE_CACHE:
                    return  new FileCache();
                case MEMCACHE_CACHE:
                    return  new MemcacheCache();
                case REDIS_CACHE:
                    return  new RedisCache();
                default:
                    throw new Kedavra('The apdater is not supported');
            }
        }

        /**
         * @inheritDoc
         */
        public function decrypt(string $x, bool $unzerialize = true): string
        {
            return (new Crypter())->decrypt($x, $unzerialize);
        }

        /**
         * @inheritDoc
         */
        public function crypt(string $x, bool $unzerialize = true): string
        {
            return (new Crypter())->encrypt($x, $unzerialize);
        }

        /**
         * @inheritDoc
         */
        public function flash(string $key, string $message): void
        {
            if (not_cli()) {
                (new Flash())->set($key, $message);
            }
        }

        /**
         * @inheritDoc
         */
        public function auth(string $model): Auth
        {
            return  new Auth(new Session(), $model);
        }

        /**
         * @inheritDoc
         */
        public function session(): SessionInterface
        {
            return cli() ? new ArraySession() : new Session();
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
            return $this->request()->cookie()->get('locale', collect(explode('_', config('i18n', 'locale')))->first());
        }

        /**
         * @inheritDoc
         */
        public function config(string $file, string $key)
        {
            return (new Config($file, $key))->value();
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
        public function connexion(): Connect
        {
            return $this->ioc(Connect::class);
        }

        /**
         * @inheritDoc
         */
        public function response(string $content, int $status = 200, array $headers = [], string $url = ''): Response
        {
            return new Response($content, $url, $status, $headers);
        }

        /**
         * @inheritDoc
         */
        public function redirect(string $url, string $message, bool $success, int $status = 301): Response
        {
            $success ? $this->flash(SUCCESS, $message) : $this->flash(FAILURE, $message);

            return (new RedirectResponse($url, $status))->send();
        }

        /**
         * @inheritDoc
         */
        public function json(array $data, int $status = 200): Response
        {
            return  (new JsonResponse($data, $status))->send();
        }


        /**
         * @inheritDoc
         */
        public function sql(string $table): Sql
        {
            return new Sql($this->ioc(Connect::class), $table);
        }

        /**
         * @inheritDoc
         */
        public function form(string $form): string
        {
            $x = new ReflectionClass($form);
            return $x->getMethod('make')->invoke($x->newInstance());
        }

        /**
         * @inheritDoc
         */
        public function check(string $form, Request $request): Response
        {
            $x = new ReflectionClass($form);
            return $x->getMethod('handle')->invokeArgs($x->newInstance(), [$request->request()]);
        }
    }
}
