<?php


use Phinx\Seed\AbstractSeed;

class GitSeeds extends AbstractSeed
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

        $git =     $git = new \Imperium\Versioning\Git\Git(dirname(core_path('app')) .DIRECTORY_SEPARATOR  .'data/symfony');
        $user = collection();

        foreach ($git->contributors()->collection() as $k => $v )
        {
                if ($user->not_exist($k))
                    $user[] = ['username' => $k ,'email' => $v];

        }


        $this->insert('git',$user);

    }
}
