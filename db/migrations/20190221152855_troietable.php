<?php


use Phinx\Migration\AbstractMigration;

class Troietable extends AbstractMigration
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
        $this->table('troy')
        ->addColumn('city','string')
        ->addColumn('prince','string')
        ->addColumn('codex','string')
        ->addColumn('god','string')
        ->addColumn('age','integer')
        ->addColumn('warriors','integer')
        ->addColumn('dead','integer')
        ->addColumn('victors','integer')
        ->addColumn('olympe','date')
        ->addColumn('sparte','date')
        ->addColumn('athene','date')
        ->addColumn('rome','date')
        ->create();

    }
}
