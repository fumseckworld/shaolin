<?php
/**
 * fumseck added AuthenticationTest.php to imperium
 * The 09/09/17 at 16:41
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
 */

namespace tests;

use Imperium\Auth\Exceptions\OauthExceptions;
use Imperium\Auth\Oauth;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @var string
     */
    private $secret;

    public function setUp()
    {
        $this->secret = generateKey();
    }

    public function testTwoFactorKeyLength()
    {
         $this->assertEquals(16,strlen($this->secret));
         $this->assertEquals(16,strlen(Oauth::generateSecret()));
    }

    /**
     * @throws OauthExceptions
     */
    public function testIsValidCode()
    {
        $this->assertEquals(false,checkCode($this->secret,'333333'));
        $this->assertEquals(false,Oauth::checkCode($this->secret,'333333'));
    }

    /**
     * @throws OauthExceptions
     */
    public function testIsNotValidCode()
    {
        $this->assertEquals(true,!checkCode($this->secret,'333333'));
        $this->assertEquals(true,!Oauth::checkCode($this->secret,'333333'));
    }

    /**
     * @throws OauthExceptions
     */
    public function testCodeLength()
    {
        $this->expectException(OauthExceptions::class);
        checkCode($this->secret,'333333333');
        Oauth::checkCode($this->secret,'333333333');
    }

    public function testGenerateQrCode()
    {
        $code = generateQrCode('venus','fumseck',$this->secret);
        $this->assertStringMatchesFormat('%s',$code);
        $this->assertContains('venus',$code);
        $this->assertContains('fumseck',$code);
        $this->assertContains($this->secret,$code);

        $code = Oauth::generateQrCode('venus','fumseck',$this->secret);
        $this->assertStringMatchesFormat('%s',$code);
        $this->assertContains('venus',$code);
        $this->assertContains('fumseck',$code);
        $this->assertContains($this->secret,$code);

    }
}