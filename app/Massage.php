<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Laravel\Scout\Searchable;

class Massage extends BaseModel
{
    use Searchable;

    protected $fillable = [
        'name',
        'image',
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'image' => ['string', 'max:255']
        ]);
    }

    public function timing()
    {
        return $this->hasMany('App\MassageTiming', 'massage_id', 'id');
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {}

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
    }

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {}

    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {}
}
