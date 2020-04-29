<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
