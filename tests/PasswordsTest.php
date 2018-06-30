<?php
/**
 * fumseck added PasswordsTest.php to imperium
 * The 11/09/17 at 08:19
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
 **/


namespace tests;


use Imperium\Databases\Eloquent\Connexion\Connexion;
use PHPUnit\Framework\TestCase;

class PasswordsTest extends TestCase
{
    public function testUpdatedPass()
    {
        $this->assertEquals(true,pass(Connexion::MYSQL,'root','',''));
        $this->assertEquals(true,pass(Connexion::POSTGRESQL,'postgres','',''));
    }

}
