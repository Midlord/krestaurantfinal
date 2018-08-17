<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
class Photo extends Model
{
    protected $table = 'restaurant_images';

    protected $fillable = [
        'restaurant_id',
        'label',
        'image'
    ];



    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

}
