<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public static $notRemoved = '0';

    protected $hidden = ['is_removed', 'created_at', 'updated_at'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
