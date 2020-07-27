<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public static $notRemoved = '0';

    public static $removed = '1';

    public static $removedColumn = 'is_removed';

    protected $hidden = ['is_removed', 'created_at', 'updated_at'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function newQuery() {
        try {
            $tableFillables = $this->fillable;
            $tableColumns   = \Schema::getColumnListing(parent::getTable());

            if (in_array(self::$removedColumn, $tableFillables) && in_array(self::$removedColumn, $tableColumns)) {
                return parent::newQuery()->where('is_removed', '=', self::$notRemoved);
            }
        } catch(Exception $exception) {}

        return parent::newQuery();
    }
}
