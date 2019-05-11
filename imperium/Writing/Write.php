<?php


namespace Imperium\Writing {

    use Egulias\EmailValidator\EmailValidator;
    use Egulias\EmailValidator\Validation\RFCValidation;
    use Exception;
    use Imperium\File\File;
    use Swift_Attachment;
    use Swift_Mailer;
    use Swift_Message;
    use Swift_Signers_DKIMSigner;
    use Swift_SmtpTransport;


    class Write
    {

        /**
         * @var Swift_Message
         *
         */
        private $message;
        /**
         * @var Swift_SmtpTransport
         */
        private $transport;

        /**
         * @var Swift_Mailer
         */
        private $mailer;
        /**
         * @var EmailValidator
         */
        private $validator;


        /**
         * @var string
         */
        private $private_key;

        /**
         *
         * @param string $subject
         * @param string $message
         * @param string $author_email
         * @param string $to
         *
         * @throws Exception
         *
         */
        public function __construct(string $subject,string $message,string $author_email,string $to)
        {
            $this->validator = new EmailValidator();

            self::valid($to,$author_email);

            $file = 'mail';

            $this->transport = (new Swift_SmtpTransport(config($file,'smtp') ,config($file,'port')))
                ->setUsername(config($file,'username'))
                ->setPassword(config($file,'password'))
            ;
            $this->message = new Swift_Message();
            $this->message->setFrom(config($file,'from'));
            $this->message->setTo($to);
            $this->message->setSubject($subject);
            $this->message->setReplyTo($author_email);
            $this->mailer = new Swift_Mailer($this->transport);

            config($file,'html') ?    $this->message->setBody(message($message),'text/html','utf-8') : $this->message->setBody($message,'text/plain','utf-8');

            $this->private_key =   File::content(dirname(config_path()) .DIRECTORY_SEPARATOR . 'dkim.private.key');

        }


        /**
         *
         * @return Write
         *
         *
         * @throws Exception
         *
         */
        public function sign(): Write
        {

            $file = 'mail';

            $signer  = new Swift_Signers_DKIMSigner($this->private_key,config($file,'domain'),config($file,'selector'),config($file,'passphrase'));

            $this->message->attachSigner($signer);

            return $this;

        }

        /**
         *
         * @param string $data
         * @param string $filename
         * @param string $type
         * @param string $disposition
         *
         * @return Write
         *
         */
        public function attach(string $data,string $filename,string $type,string $disposition = ''): Write
        {
            if (def($disposition))
                $this->message->attach(Swift_Attachment::fromPath($data,$type)->setFilename($filename)->setDisposition($disposition));
            else
                $this->message->attach(Swift_Attachment::fromPath($data,$type)->setFilename($filename));

            return $this;
        }

        /**
         *
         * @param string[] $emails
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public static function valid(string ...$emails): bool
        {
            foreach ($emails as $email)
                is_false((new EmailValidator())->isValid($email,new RFCValidation()),true,"The email $email is not valid");

            return true;
        }


        /**
         *
         * @param string $email
         * @param string|null $name
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function cc(string $email,string $name = null): Write
        {
            self::valid($email);

            $this->message->setCc($email,$name);

            return $this;
        }

        /**
         *
         * @param string $email
         * @param string|null $name
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function bcc(string $email,string $name = null): Write
        {
            self::valid($email);

            $this->message->setBcc($email,$name);

            return $this;
        }

        /**
         *
         * @param string $email
         * @param string|null $name
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function add_bcc(string $email,string $name = null): Write
        {
            self::valid($email);

            $this->message->addBcc($email,$name);

            return $this;
        }

        /**
         *
         * @param string $email
         * @param string|null $name
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function add_cc(string $email,string $name = null): Write
        {
            self::valid($email);

            $this->message->addCc($email,$name);

            return $this;
        }

        /**
         *
         * @param string $email
         * @param string|null $name
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function add_to(string $email,string $name = null): Write
        {
            self::valid($email);

            $this->message->addTo($email,$name);

            return $this;
        }

      /**
         * @return bool
         */
        public function send(): bool
        {
            return different($this->mailer->send($this->message),0);
        }
    }
}
