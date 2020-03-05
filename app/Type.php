<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Track;

class Type extends Model
{
    protected $fillable = [
        "name",
    ];

    protected $hidden = [
        "created_at", "updated_at"
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'types';

    /**
     * Get the Track record associated with the Type.
     */

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
