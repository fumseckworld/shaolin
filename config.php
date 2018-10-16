<?php

use Imperium\Connexion\Connect;
use Imperium\Imperium;

require_once  'vendor/autoload.php';


trait config
{

    private $mysql;
    private $pgsql;
    private $sqlite;




    protected $base = 'zen';
    protected $mode = PDO::FETCH_OBJ;
    protected $table  = 'doctors';


    /**
     * @return Imperium
     * @throws Exception
     */
   public function mysql():  Imperium
    {
        if ($this->mysql instanceof Imperium)
            return $this->mysql;

        $this->mysql = instance(Connect::MYSQL,'root',$this->base,'root',$this->mode,'dump',$this->table);
        return $this->mysql;
    }

    /**
     * @return Imperium
     * @throws Exception
     */
   public function postgresql():  Imperium
    {
        if ($this->pgsql instanceof Imperium)
            return $this->pgsql;

        $this->pgsql = instance(Connect::POSTGRESQL,'postgres',$this->base,'postgres',$this->mode,'dump',$this->table);
        return $this->pgsql;
    }

    /**
     * @return Imperium
     * @throws Exception
     */
   public function sqlite():  Imperium
    {
        if ($this->sqlite instanceof Imperium)
            return $this->sqlite;


        $this->sqlite = instance(Connect::SQLITE,'',"$this->base.sqlite3",'',$this->mode,'dump',$this->table);
        return $this->sqlite;
    }



}