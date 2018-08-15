<?php
/**
 * fumseck added BaseTest.php to imperium
 * The 11/09/17 at 17:08
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
use PDO;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @var \Imperium\Databases\Eloquent\Bases\Base
     */
    private $mariadb;

    /**
     * @var \Imperium\Databases\Eloquent\Bases\Base
     */
    private $pgsql;
    /**
     * @var \Imperium\Databases\Eloquent\Bases\Base
     */
    private $sqlite;

    /**
     * name of database
     *
     * @var string
     */
    private $base = 'imperiums';

    /**
     *  Pdo
     * @var string
     */
    private $pdo = PDO::class;

    public function setUp()
    {
        $this->mariadb = base(Connexion::MYSQL,'','root','root',"dump");
        $this->pgsql = base(Connexion::POSTGRESQL,'','postgres','',"dump");
        $this->sqlite = base(Connexion::SQLITE,'imperiums','','',"dump");
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testCreateOnExisting()
    {
        $this->assertEquals(false,$this->mariadb->setCollation('utf8')->setEncoding('utf8_general_ci')->create($this->base));
        $this->assertEquals(false,$this->pgsql ->setCollation('UTF8')->setEncoding('')->create($this->base));
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function test()
    {
        $this->assertEquals(true,db($this->mariadb,"a"));
        $this->assertEquals(true,$this->mariadb->drop('a'));

        $this->assertEquals(true,db($this->pgsql,"a"));
        $this->assertEquals(true,$this->pgsql->drop('a'));



    }

    public function testGetInstance()
    {
        $this->assertInstanceOf($this->pdo, $this->mariadb->getInstance());
        $this->assertInstanceOf($this->pdo, $this->pgsql->getInstance());
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testShow()
    {
        $this->assertContains($this->base,$this->mariadb->show());
        $this->assertContains($this->base,$this->pgsql->show());
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testExist()
    {
        $this->assertEquals(true,$this->mariadb->exist($this->base));
        $this->assertEquals(true,$this->pgsql->exist($this->base));
    }


    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testGetCharset()
    {
        $this->assertContains('utf8',$this->mariadb->getCharset());
        $this->assertContains('UTF8',$this->pgsql->getCharset());
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testGetCollation()
    {
        $this->assertContains('utf8_general_ci',$this->mariadb->getCollation());
        $this->assertNotEmpty($this->mariadb->getCollation());
        $this->assertNotEmpty($this->pgsql->getCollation());
    }

    /**
     * @throws \Imperium\Databases\Exception\IdentifierException
     */
    public function testHidden()
    {
        $this->assertNotContains($this->base,$this->pgsql->setHidden([$this->base])->show());
        $this->assertNotContains($this->base,$this->mariadb->setHidden([$this->base])->show());
    }

}