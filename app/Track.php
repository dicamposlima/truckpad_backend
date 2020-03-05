<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Type;

class Track extends Model
{
    protected $fillable = [
        "latitude", "longitude", "on_way", "has_truckload"
    ];

    protected $hidden = [
        "id", "driver_id"
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracks';

}
