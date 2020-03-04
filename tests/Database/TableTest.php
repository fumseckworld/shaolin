<?php


namespace Testing\Database {


    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;

    class TableTest extends Unit
    {
        /**
         * @var Table
         */
        private Table $mysql;
        /**
         * @var Table
         */
        private Table $pgsql;
        /**
         * @var Table
         */
        private Table $sqlite;
        /**
         * @var Table
         */
        private Table $sql_server;
        /**
         * @var string
         */
        private string $table;

        /**
         * @throws Kedavra
         */
        public function setUp(): void
        {
            $this->mysql = new Table(connect(MYSQL,'eywa','eywa','eywa'));
            $this->pgsql = new Table(connect(POSTGRESQL,'eywa','eywa','eywa',5432));
            $this->sqlite = new Table(connect(SQLITE,'eywa.sqlite3'));
            $this->table = 'users';
        }

        public function test_show()
        {
            $this->assertNotEmpty($this->mysql->show());
            $this->assertNotEmpty($this->pgsql->show());
            $this->assertNotEmpty($this->sqlite->show());
        }

        /**
         * @throws Kedavra
         */
        public function test_column()
        {
            $this->assertNotEmpty($this->mysql->from($this->table)->columns());
            $this->assertNotEmpty($this->pgsql->from($this->table)->columns());
            $this->assertNotEmpty($this->sqlite->from($this->table)->columns());
        }

        /**
         * @throws Kedavra
         */
        public function test_primary()
        {
            $this->assertEquals('id',$this->mysql->from($this->table)->primary());
            $this->assertEquals('id',$this->pgsql->from($this->table)->primary());
            $this->assertEquals('id',$this->sqlite->from($this->table)->primary());
        }

        /**
         * @throws Kedavra
         */
        public function test_has()
        {
            $this->assertTrue($this->mysql->from($this->table)->has('id'));
            $this->assertTrue($this->pgsql->from($this->table)->has('id'));
            $this->assertTrue($this->sqlite->from($this->table)->has('id'));
        }

        /**
         */
        public function test_exist()
        {
            $this->assertTrue($this->mysql->exist($this->table));
            $this->assertTrue($this->pgsql->exist($this->table));
            $this->assertTrue($this->sqlite->exist($this->table));
        }
    }
}