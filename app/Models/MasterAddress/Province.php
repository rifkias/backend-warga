<?php

namespace App\Models\MasterAddress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'province_name'
    ];

    public function city()
    {
        return $this->hasMany(City::class);
    }
}
