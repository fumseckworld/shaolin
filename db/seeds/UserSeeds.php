<?php


use Phinx\Seed\AbstractSeed;

class UserSeeds extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $user = [ 'username' => 'will' ,'password' => bcrypt('will')];
        $this->insert('users', $user);

    }
}
