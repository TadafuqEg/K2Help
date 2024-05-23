<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Replay extends Model
{
    use HasFactory,SoftDeletes;
    public $replayImageCollection = 'replay-image';
    protected $table = 'replayes';
    protected $fillable = [
        'user_id',
        'comment_id',
        'description',
        'seen'
    ];
    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function comment(){
        return $this->belongsTo(Post::class,'comment_id','id');
    }
    

    
}
