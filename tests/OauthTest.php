<?php
/**
 * fumseck added OauthTest.php to imperium
 * The 28/09/17 at 14
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
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace tests {

    use Imperium\Auth\Oauth;
    use PHPUnit\Framework\TestCase;

    class OauthTest extends TestCase
    {
        /**
         * @var string
         */
        private $secret;

        /**
         * @var string
         */
        private $qrCode;

        public function setUp()
        {
            $this->secret = Oauth::generateSecret();
            $this->qrCode = Oauth::generateQrCode('Company','username',$this->secret);
        }

        public function testGenerateSecret()
        {
            $this->assertEquals(16,strlen(Oauth::generateSecret()));
            $this->assertEquals(16,strlen($this->secret));
        }
        public function testGenerateQrCode()
        {
            $this->assertEquals("https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth%3A%2F%2Ftotp%2FCompany%3Ausername%3Fsecret%3D$this->secret%26issuer%3DCompany", $this->qrCode);

            $this->assertStringMatchesFormat('%s',$this->qrCode);
        }

        public function testCheckCode()
        {
            $this->assertEquals(false,Oauth::checkCode($this->secret,'021568'));
        }

    }
}
