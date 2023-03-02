<?php

namespace app\form;

use app\src\Model;

class Form
{
    public static function begin(string $action, string $method, string $class): self
    {
        echo sprintf('<form action="%s" method="%s" class="%s">', $action, $method, $class);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute): Field
    {
        return new Field($model, $attribute);
    }
}