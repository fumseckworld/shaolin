<?php

declare(strict_types=1);

namespace Eywa\Application {

    use Eywa\Application\Environment\Env;
    use Eywa\Cache\Filecache;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
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
    use Redis;

    class App extends Zen implements Eywa
    {

        private Env $env;

        /**
         * @var string
         */
        protected static string $layout = 'layout.php';

        protected static string $directory = '';
        /**
         *
         * @inheritDoc
         *
         */
        public function __construct()
        {
            if (server(DISPLAY_BUGS, false))
                whoops();

            $this->env = new Env();

        }


        /**
         *
         * @inheritDoc
         *
         */
        public function env(string $key)
        {
            return $this->env->get($key);
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
        public function ioc(string $key): Container
        {
            return Container::ioc($key);
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
            return php_sapi_name() == 'cli' ? new Request() :  Request::generate();
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

            return (new RedirectResponse($this->request()->server()->get('c')))->send();

        }

        /**
         *
         * @inheritDoc
         *
         */
        public function to(string $route, string $message = '', bool $success = true): Response
        {
            if (not_cli() && def($message))
                $success ?  $this->flash(SUCCESS,$message) : $this->flash(FAILURE,$message);


            return (new RedirectResponse(route('web',$route)))->send();
        }

        /**
         *
         * @inheritDoc
         *
         */
        public function cache(): Filecache
        {
            return new Filecache();
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
            $session = $this->session();
            if ($session->has(CSRF_TOKEN))
            {
                return different((new Crypter())->decrypt($session->get('server')),Request::generate()->server()->get('SERVER_NAME','eywa'),true,"Form is not valid") || different($session->get('csrf'),collect(explode('==',$session->get(CSRF_TOKEN)))->last(),true,"Csrf token was not found");
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
            return  new Session();
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
    }
}