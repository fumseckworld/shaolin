<?php


use Phinx\Seed\AbstractSeed;

class BaseSeeds extends AbstractSeed
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
        $country = [];


        $number = 100;
        for ($i = 0; $i != $number ; ++$i)
        {


            $country[] = [
                'name' => faker()->name,
                'age' => faker()->numberBetween(1,100),
                'phone' => faker()->randomNumber(8),
                'sex' => faker()->firstNameMale,
                'alive' => rand(0,1) === 1 ? 'true' : 'false',
                'status' => faker()->text(20),
                'days' => faker()->date(),
                'date' => faker()->date(),
            ];
        }
        $this->insert('base', $country);
    }
}
