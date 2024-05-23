<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Accident extends Model
{
    use HasFactory,SoftDeletes;
    public $accidentImageCollection = 'accident-image';
    protected $table = 'accidents';
    protected $fillable = [
        'first_name',
        'last_name',
        'description',
        'phone',
        'location',
        'street_id',
        'type'
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
