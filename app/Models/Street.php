<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Street extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'streets';
    protected $fillable = [
        'name',
        'is_active',
        'city_id',
        'lat',
        'lng'
    ];

    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];

    public function streets()
    {
        return $this->hasMany(Street::class,'city_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }
    
}
