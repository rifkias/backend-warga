<?php

namespace App\Models\MasterAddress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'province_id', 'city_name'
    ];
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function district()
    {
        return $this->hasMany(District::class);
    }
}
