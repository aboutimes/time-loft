<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Footprint extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['desc', 'lng', 'lat'];

    protected $dateFormat = 'U';

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }
    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
