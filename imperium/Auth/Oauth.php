<?php
/**
 * fumseck added Oauth.php to imperium
 * The 09/09/17 at 14:59
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Auth {

    use PragmaRX\Google2FA\Google2FA;

    class Oauth
    {
        /**
         * generate a secret key
         *
         * @return string
         */
        public static function generateSecret(): string
        {
            if (function_exists('generateKey'))
                return generateKey();
            else
                return (new Google2FA())->generateSecretKey();
        }

        /**
         * check if code is valid
         *
         * @param string $secret
         * @param string $code
         *
         * @return bool
         */
        public static function checkCode(string $secret,string $code): bool
        {
            if (function_exists('checkCode'))
                return checkCode($secret,$code);
            else
                return (new Google2FA())->verifyKey($secret,$code);
        }

        /**
         * generate Qr code
         *
         * @param string $company
         * @param string $username
         * @param string $secret
         *
         * @param int $size
         * @return string
         */
        public static function generateQrCode(string $company, string $username, string $secret,int $size = 200): string
        {
            if (function_exists('generateQrCode'))
                return generateQrCode($company,$username,$secret,$size);

            return  (new Google2FA())->getQRCodeGoogleUrl($company,$username,$secret,$size);
        }
    }
}
