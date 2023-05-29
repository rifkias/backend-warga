<?php

namespace App\Models\MasterAddress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'city_id', 'district_name'
    ];
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function village()
    {
        return $this->hasMany(Village::class);
    }
}
