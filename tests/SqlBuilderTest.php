<?php
/**
 * fumseck added SqlBuilderTest.php to imperium
 * The 22/09/17 at 10
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
use Imperium\Databases\Eloquent\Query\Query;
use PDO;
use PHPUnit\Framework\TestCase;

class SqlBuilderTest extends TestCase
{
    private $base = 'imperiums';

    /**
     * @var string
     */
    private $table = 'patients';

    /**
     * @var string
     */
    private $secondTable = 'doctors';

    /**
     * @var PDO
     */
    private $pgsql;

    /**
     * @var PDO
     */
    private $mariadb;

    /**
     * @var PDO
     */
    private $sqlite;

    /**
     * @var Query
     */
    private $sql;

    public function setUp()
    {
        $this->mariadb = connect(Connexion::MYSQL, $this->base, 'root');
        $this->pgsql = connect(Connexion::POSTGRESQL, $this->base, 'postgres');
        $this->sqlite = connect(Connexion::SQLITE, $this->base);
        $this->sql = sql($this->table);
    }

    public function testUnion()
    {
        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());
        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->limit(2, 4)->get());

        $this->assertEquals("SELECT id, article FROM $this->table UNION SELECT address, phone FROM $this->secondTable LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['id', 'article'], ['address', 'phone'])->limit(2, 4)->get());
        $this->assertEquals("SELECT id, article FROM $this->table UNION SELECT address, phone FROM $this->secondTable", union(Query::MODE_UNION, $this->table, $this->secondTable, ['id', 'article'], ['address', 'phone'])->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable ORDER BY id DESC", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable ORDER BY id ASC", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->orderBy('id', 'ASC')->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable ORDER BY id ASC LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->orderBy('id', 'ASC')->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable ORDER BY id DESC LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->limit(2, 4)->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id = 4", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->where('id', '=', 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 20 LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->where('id', '>', 20)->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 20 ORDER BY id DESC", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->where('id', '>', 20)->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 20 ORDER BY id DESC LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->where('id', '>', 20)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20 LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20 ORDER BY id DESC", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->orderBy('id')->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20 ORDER BY id DESC LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->limit(2, 4)->orderBy('id')->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table UNION SELECT articles, price FROM $this->secondTable WHERE id > 20 ORDER BY id DESC LIMIT 2 OFFSET 4", union(Query::MODE_UNION, $this->table, $this->secondTable, ['articles', 'price'], ['articles', 'price'])->where('id', '>', 20)->limit(2, 4)->orderBy('id')->get());


    }

    public function testJoin()
    {
        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());

        $this->assertEquals("SELECT * FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());

        $this->assertEquals("SELECT * FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());


        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());
        $this->assertEquals("SELECT * FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());
        $this->assertEquals("SELECT * FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());
        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->orderBy('id')->get());
        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->orderBy('id')->get());

        $this->assertEquals("SELECT article, quantity FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table LEFT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::LEFT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table RIGHT JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::RIGHT_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());
        $this->assertEquals("SELECT article, quantity FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->get());

        $this->assertEquals("SELECT article, quantity FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->limit(2, 4)->get());

        $this->assertEquals("SELECT article, quantity FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['article', 'quantity'])->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id = 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->where('id', '=', 4)->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20 LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->where('id', '>', 20)->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20 ORDER BY id DESC", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->where('id', '>', 20)->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20 ORDER BY id DESC LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->where('id', '>', 20)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['articles', 'price'])->where('id', '>', 20)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20 LIMIT 2 OFFSET 4", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['articles', 'price'])->where('id', '>', 20)->limit(2, 4)->get());

        $this->assertEquals("SELECT articles, price FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 20", joins(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id', ['articles', 'price'])->where('id', '>', 20)->get());

    }

    public function testSql()
    {


        $this->assertEquals("SELECT * FROM $this->table", sql($this->table)->get());

        $this->assertEquals("SELECT id, product FROM $this->table WHERE id = 4 ORDER BY id DESC LIMIT 1 OFFSET 1", sql($this->table)->where('id', '=', 4)->setColumns(['id', 'product'])->orderBy('id')->limit(1, 1)->get());

        $this->assertEquals("SELECT id, product FROM $this->table WHERE id = 4", sql($this->table)->setColumns(['id', 'product'])->where('id', '=', 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 4 ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->where('id', '>', 4)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 4 ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->join(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->where('id', '>', 4)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table WHERE id > 4 ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->where('id', '>', 4)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table WHERE id > 20 ORDER BY id DESC", sql($this->table)->where('id', '>', 20)->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 4 ORDER BY id DESC", sql($this->table)->where('id', '>', 4)->orderBy('id')->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 4", sql($this->table)->where('id', '>', 4)->orderBy('id')->join(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());

        $this->assertEquals("SELECT * FROM $this->table WHERE id = 4", sql($this->table)->where('id', '=', 4)->get());

        $this->assertEquals("SELECT * FROM $this->table WHERE id > 4 LIMIT 2 OFFSET 4", sql($this->table)->where('id', '>', 4)->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id > 4 LIMIT 2 OFFSET 4", sql($this->table)->where('id', '>', 4)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id > 4 LIMIT 2 OFFSET 4", sql($this->table)->where('id', '>', 4)->join(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());


        $this->assertEquals("SELECT * FROM $this->table WHERE id = 4 LIMIT 2 OFFSET 4", sql($this->table)->where('id', '=', 4)->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable WHERE id = 4", sql($this->table)->where('id', '=', 4)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT * FROM $this->table FULL JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id WHERE id = 4", sql($this->table)->where('id', '=', 4)->join(Query::FULL_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());

        $this->assertEquals("SELECT id, price FROM $this->table LIMIT 2 OFFSET 4", sql($this->table)->setColumns(['id', 'price'])->limit(2, 4)->get());

        $this->assertEquals("SELECT id, price FROM $this->table LIMIT 2 OFFSET 4", sql($this->table)->setColumns(['id', 'price'])->limit(2, 4)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT id, price FROM $this->table LIMIT 2 OFFSET 4", sql($this->table)->setColumns(['id', 'price'])->limit(2, 4)->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC", sql($this->table)->setColumns(['id', 'price'])->orderBy('id')->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC", sql($this->table)->setColumns(['id', 'price'])->orderBy('id')->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC", sql($this->table)->setColumns(['id', 'price'])->orderBy('id')->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());

        $this->assertEquals("SELECT * FROM $this->table ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->orderBy('id')->setColumns(['id', 'price'])->limit(2, 4)->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->orderBy('id')->setColumns(['id', 'price'])->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());

        $this->assertEquals("SELECT id, price FROM $this->table ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->orderBy('id')->setColumns(['id', 'price'])->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->orderBy('id')->limit(2, 4)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable", sql($this->table)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable LIMIT 2 OFFSET 4", sql($this->table)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table UNION SELECT * FROM $this->secondTable ORDER BY id DESC", sql($this->table)->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC", sql($this->table)->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id", sql($this->table)->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id LIMIT 2 OFFSET 4", sql($this->table)->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table INNER JOIN $this->secondTable ON $this->table.id = $this->secondTable.fk_id ORDER BY id DESC LIMIT 2 OFFSET 4", sql($this->table)->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->orderBy('id')->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table LIMIT 2 OFFSET 4", sql($this->table)->limit(2, 4)->get());

        $this->assertEquals("SELECT * FROM $this->table ORDER BY id DESC", sql($this->table)->orderBy('id')->get());

        $this->assertEquals("SELECT article, price FROM $this->table", sql($this->table)->setColumns(['article', 'price'])->get());

        $this->assertEquals("SELECT article, price FROM $this->table", sql($this->table)->setColumns(['article', 'price'])->union(Query::MODE_UNION, $this->table, $this->secondTable, [], [])->get());

        $this->assertEquals("SELECT article, price FROM $this->table", sql($this->table)->setColumns(['article', 'price'])->join(Query::INNER_JOIN, $this->table, $this->secondTable, 'id', 'fk_id')->get());


    }

    public function testCount()
    {
        $this->assertEquals(100, $this->sql->setPdo($this->mariadb)->count());
        $this->assertEquals(100, $this->sql->setPdo($this->pgsql)->count());
    }

    public function testLimit()
    {
        $this->assertNotEmpty($this->sql->setPdo($this->mariadb)->getRecords());
        $this->assertNotEmpty($this->sql->setPdo($this->pgsql)->getRecords());
        $this->assertCount(10, $this->sql->setPdo($this->mariadb)->limit(10, 0)->getRecords());
        $this->assertCount(10, $this->sql->setPdo($this->pgsql)->limit(10, 0)->getRecords());
    }

    public function testOrder()
    {
        foreach ($this->sql->setPdo($this->mariadb)->orderBy('id')->limit(1, 0)->getRecords() as $record)
            $this->assertEquals('100', $record->id);

        foreach ($this->sql->setPdo($this->mariadb)->orderBy('id', 'ASC')->limit(1, 0)->getRecords() as $record)
            $this->assertEquals('1', $record->id);
    }

    public function testDelete()
    {
        $this->assertEquals(false, $this->sql->setPdo($this->mariadb)->delete());
        $this->assertEquals(true, $this->sql->setPdo($this->sqlite)->where('id', '=', 50)->delete());
    }

    public function testGetUnionRecords()
    {
        $this->assertCount(100, $this->sql->setPdo($this->mariadb)->union(Query::MODE_UNION, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());
        $this->assertCount(200, $this->sql->setPdo($this->mariadb)->union(Query::MODE_UNION_ALL, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());

        $this->assertCount(100, $this->sql->setPdo($this->sqlite)->union(Query::MODE_UNION, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());
        $this->assertCount(199, $this->sql->setPdo($this->sqlite)->union(Query::MODE_UNION_ALL, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());

        $this->assertCount(100, $this->sql->setPdo($this->pgsql)->union(Query::MODE_UNION, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());
        $this->assertCount(200, $this->sql->setPdo($this->pgsql)->union(Query::MODE_UNION_ALL, $this->table, $this->secondTable, ['id'], ['id'])->getRecords());
    }

    public function testGetOneRecord()
    {
        $this->assertCount(1, $this->sql->setPdo($this->mariadb)->where('id', '=', 1)->getRecords());
        $this->assertCount(1, $this->sql->setPdo($this->pgsql)->where('id', '=', 1)->getRecords());
    }
}