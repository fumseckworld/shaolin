<?php


use Phinx\Seed\AbstractSeed;

class DoctorsSeeds extends AbstractSeed
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
        $doctors = [];
        $number = 100;
        for ($i = 0; $i != $number; ++$i)
        {
            $doctors[] = [
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20)
            ];
        }
        $this->insert('doctors', $doctors);
    }
}
