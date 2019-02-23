<?php


use Phinx\Seed\AbstractSeed;

class TroieSeeds extends AbstractSeed
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
        $data = [];
        $number = 100;
        for ($i = 0; $i != $number ; ++$i)
        {

            $data[] = [
                'city' => faker()->city,
                'prince' => faker()->firstNameMale,
                'codex' => faker()->text(20),
                'god' => faker()->firstNameMale,
                'age' => faker()->numberBetween(0,200),
                'warriors' => faker()->numberBetween(0,10000),
                'dead' => faker()->numberBetween(0,10000),
                'victors' => faker()->numberBetween(0,10000),
                'olympe' => faker()->date(),
                'sparte' => faker()->date(),
                'athene' => faker()->date(),
                'rome' => faker()->date(),
            ];
        }

        $this->insert('troy',$data);
    }
}
