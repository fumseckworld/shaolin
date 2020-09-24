<?php

namespace Nol\Messages\Mailing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Swift_Mailer;
    use Swift_Message;
    use Swift_Signers_DKIMSigner;
    use Swift_SmtpTransport;

    /**
     *
     * Send a message from internet to user.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Nol\Messages\Mailing\Message
     * @version 1
     *
     * @property Swift_Message $message The email builder
     */
    class Message
    {

        /**
         *
         * Message constructor.
         *
         * @param string $subject     The message subject.
         * @param string $authorEmail The author email.
         * @param string $message     The message to send.
         * @param array  $args        The message arguments.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function __construct(string $subject, string $authorEmail, string $message, array $args = [])
        {
            if (file_exists(base(app('app-directory'), app('emails-dirname'), $message))) {
                ob_start();
                extract($args);
                require(base(app('app-directory'), app('emails-dirname'), $message));
                $message = trim(strval(ob_get_clean()));
            }
            
            $signer = new Swift_Signers_DKIMSigner(
                strval(file_get_contents(base('dkim.private.key'))),
                nol('site-domain-name', ''),
                nol('site-domain-selector', ''),
                nol('email-private-key-passphrase', '')
            );
            $this->message = new Swift_Message($subject, $message, 'text\html', 'utf-8');
            $this->message->addPart($message, 'text/plain', 'utf-8');
            $this->message->setReplyTo($authorEmail);
            $this->message->attachSigner($signer);
            $this->message->addTo(nol('email-to', ''));
            $this->message->setFrom(nol('email-from', ''));
        }

        /**
         *
         * Send the email.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return boolean
         *
         */
        public function send(): bool
        {
            return (new Swift_Mailer(
                new Swift_SmtpTransport(
                    env('SMTP_HOSTNAME', 'localhost'),
                    env('SMTP_PORT', 25),
                    env('SMTP_ENCRYPTION', null)
                )
            )
                )->send($this->message) !== 0;
        }
    }
}
