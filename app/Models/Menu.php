<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'restaurant_menus';

    protected $fillable = [
        'restaurant_id',
        'menuName',
        'price',
        'image'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
