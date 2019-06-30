<?php


use Phinx\Seed\AbstractSeed;

class RepositorySeed extends AbstractSeed
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
        $repository = collection();



        for ($i=0;$i!=10;$i++)
        {
            $x = 0;
            $y = 1;
            do
            {
                $y++;
                $x++;
                $repository->push(['img'=> 'http://lorempixel.com/400/200','name' => faker()->name(),'owner' => "$y-".faker()->name(),'description' => faker()->text(),'created_at' => faker()->dateTimeInInterval()->format('Y-m-d'),'updated_at'=> faker()->dateTimeInInterval()->format('Y-m-d') ]);
            }while ($x !== 10000);
            $this->insert('repositories',$repository->collection());
            $repository->clear();
        }



    }
}
