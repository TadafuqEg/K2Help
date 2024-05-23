<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Volunteer extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'volunteers';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'location',
        'street_id'
    ];
    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];
    public function street(){
        return $this->belongsTo(Street::class,'street_id','id');
    }
    

    
}
