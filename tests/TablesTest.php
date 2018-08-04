<?php
/**
 * fumseck added TablesTest.php to imperium
 * The 12/09/17 at 15:30
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

use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Tables\Table;
use PHPUnit\Framework\TestCase;

class TablesTest extends TestCase
{
    /**
     * @var Table
     */
    private $pgsql;

    /**
     * @var Table
     */
    private $mysql;

    /**
     * @var Table
     */
    private $sqlite;

    /**
     * @var Table
     */
    private $oracle;

    /**
     * @var Table
     */
    private $db;
    
    private $base = 'imperiums';

    private $mariadb = 'sql/mariadb.sql';

    private $postgres = 'sql/pgsql.sql';
    /**
     * @var Base
     */
    private $mariadbBase;
    /**
     * @var Base
     */
    private $pgsqlBase;

    public function setUp()
    {
        $table = 'country';

        $this->mariadbBase = base(Connexion::MYSQL,'','root','','');
        $this->pgsqlBase = base(Connexion::POSTGRESQL,'','postgres','','');

        $this->pgsql    = table(Connexion::POSTGRESQL,$this->base,'postgres','','dump')->setName($table);
        $this->mysql    = table(Connexion::MYSQL,$this->base,'root','','dump')->setName($table);
        $this->sqlite   = table(Connexion::SQLITE,$this->base,'','','dump')->setName($table);


    }



    public function testMysql()
    {
        $this->assertEquals('CREATE TABLE IF NOT EXISTS `country` ( `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL)',$this->mysql->addField(Table::INT,'id',true,0,false,false)->get());
        $this->assertEquals('CREATE TABLE IF NOT EXISTS `country` ( `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `country` INT NOT NULL)',$this->mysql->addField(Table::INT,'id',true,0,false,false)->addField(Table::INT,'country',false,0,false)->get());
        $this->assertEquals('CREATE TABLE IF NOT EXISTS `country` ( `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `email` VARCHAR(255) UNIQUE)',$this->mysql->addField(Table::INT,'id',true,0,false,false)->addField(Table::VARCHAR,'email',false,255,true,true)->get());
        $this->assertEquals('CREATE TABLE IF NOT EXISTS `country` ( `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `email` VARCHAR(255))',$this->mysql->addField(Table::INT,'id',true,0,false,false)->addField(Table::VARCHAR,'email',false,255,false,true)->get());
        $this->assertEquals('CREATE TABLE IF NOT EXISTS `country` ( `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL, `email` VARCHAR(255) UNIQUE NOT NULL, `phone` VARCHAR(255) UNIQUE NOT NULL)',$this->mysql->addField(Table::INT,'id',true,0,false,false)->addField(Table::VARCHAR,'email',false,255)->addField(Table::VARCHAR,'phone',false,255)->get());
    }

    public function testSqlite()
    {
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)",$this->sqlite->addField(Table::INTEGER,'id',true,0,false,false)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'country' INTEGER NOT NULL)",$this->sqlite->addField(Table::INTEGER,'id',true,0,false,false)->addField(Table::INTEGER,'country',false,0,false)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'email' TEXT(255) UNIQUE)",$this->sqlite->addField(Table::INTEGER,'id',true,0,false,false)->addField(Table::TEXT,'email',false,255,true,true)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'email' TEXT(255))",$this->sqlite->addField(Table::INTEGER,'id',true,0,false,false)->addField(Table::TEXT,'email',false,255,false,true)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'email' TEXT(255) UNIQUE NOT NULL, 'phone' TEXT(255) UNIQUE NOT NULL)",$this->sqlite->addField(Table::INTEGER,'id',true,0,false,false)->addField(Table::TEXT,'email',false,255)->addField(Table::TEXT,'phone',false,255)->get());
    }

    public function testPostgresql()
    {
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( id SERIAL PRIMARY KEY NOT NULL)",$this->pgsql->addField(Table::SERIAL,'id',true,0,false,false)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( id SERIAL PRIMARY KEY NOT NULL, country INTEGER NOT NULL)",$this->pgsql->addField(Table::SERIAL,'id',true,0,false,false)->addField(Table::INTEGER,'country',false,0,false)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( id SERIAL PRIMARY KEY NOT NULL, email CHARACTER VARYING(255) UNIQUE)",$this->pgsql->addField(Table::SERIAL,'id',true,0,false,false)->addField(Table::CHARACTER_VARYING,'email',false,255,true,true)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( id SERIAL PRIMARY KEY NOT NULL, email CHARACTER VARYING(255))",$this->pgsql->addField(Table::SERIAL,'id',true,0,false,false)->addField(Table::CHARACTER_VARYING,'email',false,255,false,true)->get());
        $this->assertEquals("CREATE TABLE IF NOT EXISTS country ( id SERIAL PRIMARY KEY NOT NULL, email CHARACTER VARYING(255) UNIQUE NOT NULL, phone CHARACTER VARYING(255) UNIQUE NOT NULL)",$this->pgsql->addField(Table::SERIAL,'id',true,0,false,false)->addField(Table::CHARACTER_VARYING,'email',false,255)->addField(Table::CHARACTER_VARYING,'phone',false,255)->get());
    }

    public function testRename()
    {

        $this->assertEquals(true,$this->mysql->setNewName('a')->rename());
        $this->assertEquals(true,$this->mysql->setName('a')->setNewName('country')->rename());


        $this->assertEquals(true,$this->pgsql->setNewName('a')->rename());
        $this->assertEquals(true,$this->pgsql->setName('a')->setNewName('country')->rename());

        $this->assertEquals(true,$this->sqlite->setNewName('user')->rename());
        $this->assertEquals(true,$this->sqlite->setName('user')->setNewName('country')->rename());

    }

    public function testHas()
    {
        $this->assertEquals(true,$this->mysql->has());
        $this->assertEquals(true,$this->pgsql->has());
        $this->assertEquals(true,$this->sqlite->has());
    }

    public function testHasColumn()
    {
        $this->assertEquals(true,$this->mysql->hasColumn('id'));
        $this->assertEquals(true,$this->pgsql->hasColumn('id'));
        $this->assertEquals(true,$this->sqlite->hasColumn('id'));
    }
    public function testGetColumn()
    {
        $this->assertNotEmpty($this->mysql->getColumns());
        $this->assertNotEmpty($this->pgsql->getColumns());
        $this->assertNotEmpty($this->sqlite->getColumns());
    }

    public function testGetColumnType()
    {
        $this->assertNotEmpty($this->mysql->getColumnsTypes());
        $this->assertNotEmpty($this->pgsql->setName('country')->getColumnsTypes());
        $this->assertNotEmpty($this->sqlite->getColumnsTypes());

    }
    public function testAddColumn()
    {
        $column = 'moon';
        $expected = true;

        $this->assertEquals($expected,$this->mysql->addColumn($column,Table::VARCHAR,255));
        $this->assertEquals($expected,$this->pgsql->addColumn($column,Table::CHARACTER_VARYING,255));

        $this->assertEquals($expected,$this->pgsql->deleteColumn($column));
        $this->assertEquals($expected,$this->mysql->deleteColumn($column));

    }
    public function testAddColumns()
    {

        $table = 'alexandra';
        $this->assertEquals(true,$this->mysql->setName($table)->addField(Table::INT,'id',true)->addField(Table::VARCHAR,'city',false,255)->addField(Table::VARCHAR,'country',false,255)->addField(Table::VARCHAR,'code',false,255)->create());


        $this->assertEquals(true,$this->mysql->appendColumns($table,$this->mysql,['a','b','c'],[Table::VARCHAR,Table::INT,Table::DATE],[255,'',''],['FIRST','AFTER','AFTER'],['code','country','city'],[true,false,true],[false,true,false]));
    }


}