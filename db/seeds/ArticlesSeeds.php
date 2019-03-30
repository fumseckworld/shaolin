<?php


use Phinx\Seed\AbstractSeed;

class ArticlesSeeds extends AbstractSeed
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
        $articles = [];
        for ($i= 0;different($i,100);$i++)
        {
            $articles[] = [
                'img' => 'http://lorempixel.com/400/200',
                'slug' => faker()->slug,
                'content' => faker()->text(),
                'title' => faker()->title(),
                'created_at' => faker()->date(),
                'updated_at' => faker()->date()
            ];
        }
        $this->insert('articles',$articles);
    }
}
