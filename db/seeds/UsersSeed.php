<?php


use Phinx\Seed\AbstractSeed;

class UsersSeed extends AbstractSeed
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
        $user = collection();

        for ($i=0;$i!=100;$i++)
            $user->push(['firstname' => faker()->firstName,'lastname' => faker()->lastName ,'email' => faker()->email,'password'=> bcrypt('0000')]);

        $this->insert('users',$user->collection());
    }
}
