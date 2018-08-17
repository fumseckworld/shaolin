<?php
/**
 * fumseck added ConnectionTest.php to imperium
 * The 11/09/17 at 06:34
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
use Imperium\File\File;
use PDO;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    private $base = 'zen';

    public function testMysql()
    {
        $this->assertEquals(null,
            Connexion::connect()
            ->setDriver('mysql')
            ->setDatabase('a')
            ->setUser('a')
            ->setPassword('a')
            ->setEncoding('utf8')->getConnexion());

        $this->assertInstanceOf(PDO::class,connect(Connexion::MYSQL,$this->base,'root','root'));
        $this->assertInstanceOf(PDO::class,Connexion::connect()
            ->setDriver('mysql')->setDatabase($this->base)->setUser('root')->setPassword('root')->getConnexion());
    }

    public function testPostgresql()
    {
        $this->assertEquals(null,
            Connexion::connect()
            ->setDriver('pgsql')
            ->setDatabase('a')
            ->setUser('a')
            ->setPassword('a')
            ->setEncoding('utf8')->getConnexion());

        $this->assertInstanceOf(PDO::class,connect(Connexion::POSTGRESQL,$this->base,'postgres'));
        $this->assertInstanceOf(PDO::class,Connexion::connect()
            ->setDriver(Connexion::POSTGRESQL)->setDatabase($this->base)->setUser('postgres')->getConnexion());
    }

    public function testSqlite()
    {
        $this->assertInstanceOf(PDO::class,Connexion::connect()->setDriver('sqlite')->getConnexion());
        $this->assertInstanceOf(PDO::class,Connexion::connect()->setDriver('sqlite')->setDatabase('alex')->getConnexion());
        $this->assertEquals(true,File::delete('alex'));
    }

    public function testConnectHelper()
    {
        $this->assertEquals(null,connect('mysql','db','user','pass'));
        $this->assertEquals(null,connect('pgsql','db','user','pass'));
        $this->assertInstanceOf(PDO::class,connect('sqlite'));
        $this->assertInstanceOf(PDO::class,connect('sqlite','alex'));
        $this->assertEquals(true,File::delete('alex'));

    }

    public function testNotValid()
    {
        $this->assertEquals(null,Connexion::connect()->setDriver('a')->setDatabase('a')->setUser('a')->setPassword('a')->getConnexion());
        $this->assertEquals(null,Connexion::connect()->setDriver('b')->setDatabase('a')->setUser('a')->setPassword('a')->getConnexion());
        $this->assertEquals(null,Connexion::connect()->setDriver('c')->setDatabase('a')->setUser('a')->setPassword('a')->getConnexion());
        $this->assertEquals(null,Connexion::connect()->setDriver('d')->setDatabase('a')->setUser('a')->setPassword('a')->getConnexion());
    }

    public function testWithoutDatabase()
    {
         $this->assertInstanceOf(PDO::class,connect(Connexion::MYSQL,'','root','root'));
         $this->assertInstanceOf(PDO::class,connect(Connexion::POSTGRESQL,'','postgres'));
         $this->assertInstanceOf(PDO::class,root(Connexion::MYSQL,'root','root'));
         $this->assertInstanceOf(PDO::class,root(Connexion::POSTGRESQL,'postgres'));
         $this->assertEquals(null,root(Connexion::ORACLE,'a'));
    }
}