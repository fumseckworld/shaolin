<?php
/**
 * fumseck added UsersTest.php to imperium
 * The 31/10/17 at 15
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

namespace tests;

use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Users\Users;
use PDO;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    /**
     * @var Users
     */
    private $mariadb;

    /**
     * @var Users
     */
    private $pgsql;

    private $postgres = 'postgres';

    private $root = 'root';

    private $pdo = PDO::class;

    public function setUp()
    {
        $this->mariadb = user(Connexion::MYSQL,'root','');
        $this->pgsql = user(Connexion::POSTGRESQL,'postgres','');
    }

    public function testShow()
    {
        $this->assertContains($this->postgres,$this->pgsql->show());
        $this->assertContains($this->root,$this->mariadb->show());
    }

    public function testHidden()
    {
        $this->assertNotContains($this->postgres,$this->pgsql->setHidden([$this->postgres])->show());
        $this->assertNotContains($this->root,$this->mariadb->setHidden([$this->root])->show());
    }

    public function testInstance()
    {
        $this->assertInstanceOf($this->pdo,$this->mariadb->getInstance());
        $this->assertInstanceOf($this->pdo,$this->pgsql->getInstance());
    }

    public function testExist()
    {
        $this->assertEquals(true,$this->mariadb->exist($this->root));
        $this->assertEquals(true,$this->pgsql->exist($this->postgres));

        $this->assertEquals(true,$this->mariadb->setName($this->root)->exist());
        $this->assertEquals(true,$this->pgsql->setName($this->postgres)->exist());
    }

    public function testChangePassword()
    {
        $this->assertEquals(true,$this->mariadb->updatePassword($this->root,''));
        $this->assertEquals(true,$this->pgsql->updatePassword($this->postgres,''));
    }

    public function testDrop()
    {
        $this->assertEquals(true,userAdd(Connexion::POSTGRESQL,'tmp','alex','',root(Connexion::POSTGRESQL)));
        $this->assertEquals(true,$this->pgsql->drop('tmp'));

        $this->assertEquals(true,userAdd(Connexion::MYSQL,'tmp','alex','',root(Connexion::MYSQL)));
        $this->assertEquals(true,$this->mariadb->drop('tmp'));
    }
}