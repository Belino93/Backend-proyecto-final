<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'type',

        'marca',
        'modelo',

    ];

    public function device()
    {

        $this->belongsToMany(Device::class, 'device_repair');

        return $this->belongsToMany(Device::class, 'device_repair');

    }
}
