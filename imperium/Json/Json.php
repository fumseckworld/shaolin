<?php

namespace Imperium\Json;


use Imperium\Connexion\Connect;
use Imperium\File\File;

class Json
{

    /**
     * @var array
     */
    private $data;


    /**
     * @var string
     */
    private $filename;


    public function __construct(string $filename = '')
    {
        $this->data = collection();
        $this->set_name($filename);

    }

    /**
     *
     * @param string $filename
     *
     * @return Json
     *
     */
    public function set_name(string $filename): Json
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     *
     * @param array $data
     *
     * @return bool
     *
     * @throws \Exception
     *
     */
    public function create(array $data): bool
    {
        File::remove_if_exist($this->filename);
        return is_not_false(file_put_contents($this->filename,json_encode($data)));
    }

    /**
     * append value in the json
     *
     * @param $value
     * @param string $key
     *
     * @return Json
     *
     */
    public function add($value,$key = ''): Json
    {
        $this->data->add($value,$key);

        return $this;
    }

    /**
     *
     * Execute a query and store data
     *
     * @param Connect $connect
     * @param string $query
     * @param string $key
     *
     * @return Json
     *
     * @throws \Exception
     *
     */
    public function sql(Connect $connect,string $query,string $key = ''): Json
    {
        $this->add($connect->request($query),$key);

        return $this;
    }

    /**
     *
     * generate the json
     *
     * @return bool
     *
     * @throws \Exception
     *
     */
    public function generate(): bool
    {
        return $this->create($this->data->collection());
    }

    /**
     *
     * Decode a json file or a json string
     *
     * @param bool $assoc
     *
     * @return mixed
     *
     */
    public function decode(bool $assoc = false)
    {
       return File::exist($this->filename) && File::isJson($this->filename) ? json_decode(utf8_encode(File::getContent($this->filename)),$assoc) :  json_decode(utf8_encode($this->filename),$assoc);
    }
}