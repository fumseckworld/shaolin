<?php

namespace Imperium\Json;


class Json
{

    /**
     * @var \Imperium\Collection\Collection
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = collection($data);
    }

    /**
     * @return false|string
     */
    public function encode()
    {
        return json_encode($this->data->collection());
    }
}