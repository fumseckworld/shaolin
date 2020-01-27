<?php


namespace Testing\Database {


    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use PHPUnit\Framework\TestCase;

    class SqlTest extends TestCase
    {
        /**
         *
         */
        private Sql $mysql;

        /**
         *
         */
        private Sql $pgsql;

        /**
         *
         */
        private Sql $sqlite;

        /**
         * @throws Kedavra
         */
        public function setUp(): void
        {
            $this->mysql = (new Sql(connect(MYSQL,'eywa','eywa','eywa'),'users'));
            $this->pgsql = (new Sql(connect(POSTGRESQL,'eywa','eywa','eywa',5432),'users'));
            $this->sqlite = (new Sql(connect(SQLITE,'eywa.sqlite3'),'users'));
        }

        /**
         * @throws Kedavra
         */
        public function test_find()
        {
            $this->assertNotEmpty($this->mysql->find(1));
            $this->assertNotEmpty($this->pgsql->find(1));
            $this->assertNotEmpty($this->sqlite->find(1));
        }

        /**
         * @throws Kedavra
         */
        public function test_where()
        {
            $this->assertNotEmpty($this->mysql->where('id',EQUAL,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',EQUAL,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',EQUAL,5)->execute());

            $this->assertCount(1,$this->mysql->where('id',EQUAL,5)->execute());
            $this->assertCount(1,$this->pgsql->where('id',EQUAL,5)->execute());
            $this->assertCount(1,$this->sqlite->where('id',EQUAL,5)->execute());


            $this->assertNotEmpty($this->mysql->where('id',DIFFERENT,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',DIFFERENT,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',DIFFERENT,5)->execute());

            $this->assertCount(99,$this->mysql->where('id',DIFFERENT,5)->execute());
            $this->assertCount(99,$this->pgsql->where('id',DIFFERENT,5)->execute());
            $this->assertCount(99,$this->sqlite->where('id',DIFFERENT,5)->execute());

            $this->assertNotEmpty($this->mysql->where('id',INFERIOR,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',INFERIOR,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',INFERIOR,5)->execute());

            $this->assertCount(4,$this->mysql->where('id',INFERIOR,5)->execute());
            $this->assertCount(4,$this->pgsql->where('id',INFERIOR,5)->execute());
            $this->assertCount(4,$this->sqlite->where('id',INFERIOR,5)->execute());

            $this->assertNotEmpty($this->mysql->where('id',SUPERIOR,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',SUPERIOR,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',SUPERIOR,5)->execute());

            $this->assertCount(95,$this->mysql->where('id',SUPERIOR,5)->execute());
            $this->assertCount(95,$this->pgsql->where('id',SUPERIOR,5)->execute());
            $this->assertCount(95,$this->sqlite->where('id',SUPERIOR,5)->execute());



            $this->assertNotEmpty($this->mysql->where('id',INFERIOR_OR_EQUAL,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',INFERIOR_OR_EQUAL,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',INFERIOR_OR_EQUAL,5)->execute());

            $this->assertCount(5,$this->mysql->where('id',INFERIOR_OR_EQUAL,5)->execute());
            $this->assertCount(5,$this->pgsql->where('id',INFERIOR_OR_EQUAL,5)->execute());
            $this->assertCount(5,$this->sqlite->where('id',INFERIOR_OR_EQUAL,5)->execute());

            $this->assertNotEmpty($this->mysql->where('id',SUPERIOR_OR_EQUAL,5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',SUPERIOR_OR_EQUAL,5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',SUPERIOR_OR_EQUAL,5)->execute());

            $this->assertCount(96,$this->mysql->where('id',SUPERIOR_OR_EQUAL,5)->execute());
            $this->assertCount(96,$this->pgsql->where('id',SUPERIOR_OR_EQUAL,5)->execute());
            $this->assertCount(96,$this->sqlite->where('id',SUPERIOR_OR_EQUAL,5)->execute());

        }

        /**
         * @throws Kedavra
         */
        public function test_only()
        {
            $this->assertNotEmpty($this->mysql->where('id', EQUAL, 1)->only(['id'])->execute());
            $this->assertNotEmpty($this->pgsql->where('id', EQUAL, 1)->only(['id'])->execute());
            $this->assertNotEmpty($this->sqlite->where('id', EQUAL, 1)->only(['id'])->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_by()
        {
            $this->assertNotEquals($this->mysql->by('id')->execute(),$this->mysql->by('id',ASC)->execute());
            $this->assertNotEquals($this->pgsql->by('id')->execute(),$this->pgsql->by('id',ASC)->execute());
            $this->assertNotEquals($this->sqlite->by('id')->execute(),$this->sqlite->by('id',ASC)->execute());
        }

        public function test_get_table()
        {
            $this->assertEquals('users',$this->mysql->table());
            $this->assertEquals('users',$this->pgsql->table());
            $this->assertEquals('users',$this->sqlite->table());
        }

        /**
         * @throws Kedavra
         */
        public function test_between()
        {
            $this->assertNotEmpty($this->mysql->between('id',5,10)->execute());
            $this->assertNotEmpty($this->pgsql->between('id',5,10)->execute());
            $this->assertNotEmpty($this->sqlite->between('id',5,10)->execute());
        }

        public function test_columns()
        {
            $this->assertNotEmpty($this->mysql->columns());
            $this->assertNotEmpty($this->pgsql->columns());
            $this->assertNotEmpty($this->sqlite->columns());
        }

        /**
         * @throws Kedavra
         */
        public function test_different()
        {
            $this->assertNotEmpty($this->mysql->different('id',5)->execute());
            $this->assertNotEmpty($this->pgsql->different('id',5)->execute());
            $this->assertNotEmpty($this->sqlite->different('id',5)->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_take()
        {
            $this->assertCount(5,$this->mysql->take(5)->execute());
            $this->assertCount(5,$this->pgsql->take(5)->execute());
            $this->assertCount(5,$this->sqlite->take(5)->execute());

            $this->assertCount(5,$this->mysql->take(5,2)->execute());
            $this->assertCount(5,$this->pgsql->take(5,2)->execute());
            $this->assertCount(5,$this->sqlite->take(5,2)->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_like()
        {
            $this->assertNotEmpty($this->mysql->like('a')->execute());
            $this->assertNotEmpty($this->pgsql->like('a')->execute());
            $this->assertNotEmpty($this->sqlite->like('a')->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_sum()
        {
            $this->assertEquals(100,$this->mysql->sum());
            $this->assertEquals(100,$this->pgsql->sum());
            $this->assertEquals(100,$this->sqlite->sum());
        }

        /**
         * @throws Kedavra
         */
        public function test_and()
        {
            $this->assertNotEmpty($this->mysql->where('id',EQUAL,1)->and('id',INFERIOR,2)->execute());
            $this->assertNotEmpty($this->mysql->where('id',EQUAL,1)->and('id',INFERIOR,2)->execute());
            $this->assertNotEmpty($this->mysql->where('id',EQUAL,1)->and('id',INFERIOR,2)->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_paginate()
        {
            $mysql = $this->mysql->paginate(function ($x){return $x->id;},1,20);
            $pgsql = $this->pgsql->paginate(function ($x){return $x->id;},1,20);
            $sqlite = $this->sqlite->paginate(function ($x){return $x->id;},1,20);

            $this->assertNotEmpty($mysql->content());
            $this->assertNotEmpty($mysql->pagination());

            $this->assertNotEmpty($pgsql->content());
            $this->assertNotEmpty($pgsql->pagination());

            $this->assertNotEmpty($sqlite->content());
            $this->assertNotEmpty($sqlite->pagination());
        }

        /**
         * @throws Kedavra
         */
        public function test_or()
        {
            $this->assertNotEmpty($this->mysql->where('id',EQUAL,4)->or('id',DIFFERENT,4)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',EQUAL,4)->or('id',DIFFERENT,4)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',EQUAL,4)->or('id',DIFFERENT,4)->execute());
        }

        /**
         * @throws Kedavra
         */
        public function test_not()
        {

            $this->assertNotEmpty($this->mysql->not('id',4)->execute());
            $this->assertNotEmpty($this->pgsql->not('id',4)->execute());
            $this->assertNotEmpty($this->sqlite->not('id',4)->execute());

            $this->assertEmpty($this->mysql->where('id',EQUAL,4)->not('id',4)->execute());
            $this->assertEmpty($this->pgsql->where('id',EQUAL,4)->not('id',4)->execute());
            $this->assertEmpty($this->sqlite->where('id',EQUAL,4)->not('id',4)->execute());


            $this->assertNotEmpty($this->mysql->where('id',EQUAL,4)->not('id',5)->execute());
            $this->assertNotEmpty($this->pgsql->where('id',EQUAL,4)->not('id',5)->execute());
            $this->assertNotEmpty($this->sqlite->where('id',EQUAL,4)->not('id',5)->execute());
        }
    }
}