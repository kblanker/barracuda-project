<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{

    protected $table = 'tracking';
    protected $fillable = [
        'location', 'status'
    ];

    protected $hidden = ['id','package_id'];
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}