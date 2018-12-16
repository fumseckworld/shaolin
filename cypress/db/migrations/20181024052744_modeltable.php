<?php


use Phinx\Migration\AbstractMigration;

class Modeltable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('model')
            ->addColumn('name', 'string')
            ->addColumn('age', 'integer')
            ->addColumn('phone', 'integer')
            ->addColumn('sex', 'string')
            ->addColumn('status','string')
            ->addColumn('days','datetime')
            ->addColumn('date','datetime')
            ->create();
    }
}
