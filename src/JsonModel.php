<?php

namespace app\src;

use app\src\helpers\JsonFileHandler;
use app\src\helpers\Logger;

abstract class JsonModel extends Model
{
    public function getLogger(): Logger
    {
        return new Logger("{$this->fileName()}.log");
    }


    abstract public static function fileName(): string;

    public function primaryKey(): string
    {
        return 'id';
    }

    public function save()
    {
        $fileName = $this->fileName();
        $jsonHandler = new JsonFileHandler($fileName);

        $attributes = $this->attributes();
        $data = [];

        foreach ($attributes as $attribute) {
            $data[$this->id][$attribute] = $this->{$attribute};
        }

        $jsonHandler->write($data);

        return true;
    }


}