<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
use App\User;
class Comment extends Model
{
    protected $fillable = [
      'rate', 
      'comment',
      'user_id',
      'restaurant_id'
    ];

    protected $dates = ['created_at'];

    public function restaurant()
  	{
  		return $this->belongsTo(Restaurant::class);
  	}

    public function user()
    {
      return $this->belongsTo(User::class);
    }



}
