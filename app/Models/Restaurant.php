<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;
use App\User;
class Restaurant extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'address',
        'longitude',
        'latitude',
        'about',
        'contact_no',
        'open_hour',
        'close_hour',
        'url'
    ];

 	public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }


}