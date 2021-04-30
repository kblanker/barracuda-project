<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

    protected $fillable = [
        'origin', 'status', 'destination', 'weight'
    ];

    protected $hidden = ['tracking','created_at','updated_at'];

    protected $casts = [
        'delivered' => 'boolean',
    ];

    public function tracking()
    {
        return $this->hasMany(Tracking::class);
    }
}