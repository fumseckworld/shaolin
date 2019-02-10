<?php

namespace Imperium\Mail {

    use Exception;

    class Mail
    {

        /**
         *
         * Send the email
         *
         * @param string $subject
         * @param string $to
         * @param string $message
         * @return bool
         *
         * @throws Exception
         */
        public static function send(string $subject,string $to,string $message): bool
        {
            return send_mail($subject,$to,$message);
        }
    }
}