<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'is_reprint',
        'reprint_url',
        'content',
        'read_number',
        'like',
        'dislike',
        'is_top'
    ];

    protected $dateFormat = 'U';

    public function user()
    {
        return $this->belongsTo('\App\User');
    }
    public function category()
    {
        return $this->belongsTo('\App\Category');
    }
}
