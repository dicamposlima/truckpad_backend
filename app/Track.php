<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        "latitude", "longitude", "on_way", "has_truckload"
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracks';
}
