<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = [];

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
        return $this->hasMany(ProductImage::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) { 
            $user->images()->delete();
            // do the rest of the cleanup...
        });
    }
}
