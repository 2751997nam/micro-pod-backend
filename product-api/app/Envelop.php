<?php

namespace App;

class Envelop
{
    private $data;
    /**
     * Envelop constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}