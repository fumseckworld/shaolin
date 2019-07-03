<?php


use Imperium\Directory\Dir;
use Phinx\Seed\AbstractSeed;

class RepositorySeed extends AbstractSeed
{
    /**
     * @var array
     */
    private $author;

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

        $authors = collection(scandir('depots'))->remove_values('.','..')->collection();

          foreach ($authors as $author)
        {

            $this->author[$author] =  [];

            foreach (Dir::scan("depots/$author") as $depot)
            {
                $repo = collection()->add($depot,'name')->add('a sublime app','description')->add($author,'owner')->add(now()->toDateTime()->format('Y-m-d'),'created_at')->add(now()->toDateTime()->format('Y-m-d'),'updated_at')->add('http://lorempixel.com/g/400/200','img');

                $this->author[$author][] = $repo->collection();

                $repo->clear();
            }

        }



        foreach ($this->author as $k=> $value)
            foreach ($value as $x)
                $this->insert('repositories',$x);




    }
}
