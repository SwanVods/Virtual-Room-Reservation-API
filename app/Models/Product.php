<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    // DB Relations
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facilities');
    }
    public function utilities()
    {
        return $this->belongsToMany(Utility::class, 'room_utilities');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_orders');
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }
    public function users()
    {
        return $this->hasOne(User::class);
    }
}
