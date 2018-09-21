<?php


use Phinx\Seed\AbstractSeed;

class PatientsSeeds extends AbstractSeed
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
        $patients = [];
        $number = 100;
        for ($i = 0; $i != $number; ++$i)
        {
            $patients[] = [
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20),
                'date' => faker()->date()
            ];
        }
        $this->insert('patients', $patients);
    }
}
