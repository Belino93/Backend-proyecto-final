<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'marca',
        'modelo',
    ];

    public function repair()
    {
        return $this->belongsToMany(Repair::class, 'device_repair');
    }
}
