<?php


use Phinx\Seed\AbstractSeed;

class CountrySeeds extends AbstractSeed
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
        for ($i = 0; $i !=$number ; ++$i)
        {
            $country[] = [
                'name' => faker()->country,
            ];
        }
        $this->insert('country', $country);
    }
}
