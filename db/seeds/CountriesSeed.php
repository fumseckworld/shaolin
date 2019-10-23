<?php


use Phinx\Seed\AbstractSeed;

class CountriesSeed extends AbstractSeed
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
        $user = collect();

        for ($i=0;$i!=100;$i++)
            $user->set(['name' => faker()->country]);

        $this->insert('countries',$user->all());
    }
}
