<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        "name",
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'types';
}
