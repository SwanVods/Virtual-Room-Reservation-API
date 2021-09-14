<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->hasOne(Order::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
