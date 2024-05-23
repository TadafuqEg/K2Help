<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Country extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];

    public function cities()
    {
        return $this->hasMany(City::class,'country_id');
    }
}
