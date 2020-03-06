<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        "name", "cpf", "gender", "has_vehicles", "cnh_type", "cnh", "date_of_birth","phone"
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
     * Get the Tracks record associated with the Drivers where hasTruckload.
     */

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
