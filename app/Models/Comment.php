<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Comment extends Model
{
    use HasFactory,SoftDeletes;
    public $commentImageCollection = 'comment-image';
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'post_id',
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
    public function post(){
        return $this->belongsTo(Post::class,'post_id','id');
    }
    public function replayes()
    {
        return $this->hasMany(Replay::class,'comment_id');
    }

    
}
