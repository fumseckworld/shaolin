<?php

namespace App\Controllers\Email {

    use App\Forms\Write\ContactForm;
    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use ReflectionException;

    class WriteController extends Controller
    {
        protected static string $layout = 'layout';
    
        protected static string $directory = 'Email';


        /**
         * @inheritDoc
         */
        public function before(Request $request): void
        {
            // TODO: Implement before() method.
        }

        /**
         * @inheritDoc
         */
        public function after(Request $request): void
        {
            // TODO: Implement after() method.
        }


        public function showForm()
        {
            $contact = $this->form(ContactForm::class);
            return $this->view('contact', 'write', 'a suberb app', compact('contact'));
        }

        /**
         *
         * Send the email
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws ReflectionException
         *
         */
        public function send(Request $request)
        {
            return $this->check(ContactForm::class, $request);
        }
    }
}
