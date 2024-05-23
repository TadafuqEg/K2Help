<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Replay;
use GuzzleHttp\Client;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use App\Events\NotificationDevice;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use DB;


class CommunityController extends ApiController
{
    public function posts(){
        
        $posts=Post::orderBy('id','desc')->select('id','user_id','description')->with(['user'=>function($q){
            $q->select('id','first_name','last_name');
        },'comments'=>function($q){
            $q->select('id','post_id','description','user_id')
              ->with(['user'=>function($qu){
                $qu->select('id','first_name','last_name');
            },'replayes'=>function($qu){
                $qu->select('id','comment_id','description','user_id')
                ->with(['user'=>function($qu){
                    $qu->select('id','first_name','last_name');
                }]);
            }]);
        }])->get()->map(function($post){
            $post->image=getFirstMediaUrl($post,$post->postImageCollection);
            return $post;
        })->toArray();
        return $this->sendResponse($posts);

    }

    public function create_post(Request $request){
        $validator = Validator::make($request->all(), [
            'post' => 'required'
        ]);
      
        if ($validator->fails()) {
            return $this->sendError(null,$validator->errors());
        }

        $post=Post::create(['user_id'=>auth()->user()->id,'description'=>$request->post]);
        if($request->file('image')){
            uploadMedia($request->file('image'),$post->postImageCollection,$post);
        }
        $post->image=getFirstMediaUrl($post,$post->postImageCollection);
        return $this->sendResponse($post,'Post created successfuly');
    }

    public function create_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'post_id' => [
                'required',
                Rule::exists('posts', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ]);
      
        if ($validator->fails()) {
            return $this->sendError(null,$validator->errors());
        }

        $comment=Comment::create(['user_id'=>auth()->user()->id,'post_id'=>$request->post_id,'description'=>$request->comment]);
        if($request->file('image')){
            uploadMedia($request->file('image'),$comment->commentImageCollection,$comment);
        }
        $post_owner=Post::where('id',$request->post_id)->first()->user;
        if(auth()->user()->first_name != null && auth()->user()->last_name != null){
            $comment_owner_name=auth()->user()->first_name . ' ' . auth()->user()->last_name;
        }else{
            $comment_owner_name=auth()->user()->username;
        }
        if(auth()->user()->id != $post_owner->id){
            $message=$comment_owner_name . ' commented on your post';
            event(new NotificationDevice($post_owner->id,$message,"K2Help"));
        }
        
        $all_comments=Comment::where('post_id',$request->post_id)->whereNotIn('user_id',[auth()->user()->id,$post_owner->id])->get();
        if($post_owner->first_name != null && $post_owner->last_name != null){
            $post_owner_name=$post_owner->first_name . ' ' . $post_owner->last_name;
        }else{
            $post_owner_name=$post_owner->username;
        }
        if($post_owner->id == auth()->user()->id ){
            $message=$comment_owner_name . ' commented on his post';
        }else{
            $message=$comment_owner_name . ' commented on ' . $post_owner_name . "'s post";
        }
        
        foreach($all_comments as $comment){
            $user=$comment->user;
            
            event(new NotificationDevice($user->id,$message,"K2Help"));
        }
        return $this->sendResponse($comment,'Comment created successfuly');
    }

    public function create_replay(Request $request){
        $validator = Validator::make($request->all(), [
            'replay' => 'required',
            'comment_id' => [
                'required',
                Rule::exists('comments', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ]);
      
        if ($validator->fails()) {
            return $this->sendError(null,$validator->errors());
        }

        $replay=Replay::create(['user_id'=>auth()->user()->id,'comment_id'=>$request->comment_id,'description'=>$request->replay]);
        if($request->file('image')){
            uploadMedia($request->file('image'),$replay->replayImageCollection,$replay);
        }
        $post_owner=$replay->comment->post->user;
        $comment_owner=$replay->comment->user;
        if(auth()->user()->first_name != null && auth()->user()->last_name != null){
            $replay_owner_name=auth()->user()->first_name . ' ' . auth()->user()->last_name;
        }else{
            $replay_owner_name=auth()->user()->username;
        }
        if($post_owner->first_name != null && $post_owner->last_name != null){
            $post_owner_name=$post_owner->first_name . ' ' . $post_owner->last_name;
        }else{
            $post_owner_name=$post_owner->username;
        }
        if($comment_owner->first_name != null && $comment_owner->last_name != null){
            $comment_owner_name=$comment_owner->first_name . ' ' . $comment_owner->last_name;
        }else{
            $comment_owner_name=$comment_owner->username;
        }
        if(auth()->user()->id != $post_owner->id && auth()->user()->id != $comment_owner->id){
            $message=$replay_owner_name . 'replayed on ' . $comment_owner_name ."'s comment on your post";
            event(new NotificationDevice($post_owner->id,$message,"K2Help"));
        }else if(auth()->user()->id != $post_owner->id && auth()->user()->id == $comment_owner->id){
            $message=$replay_owner_name . 'replayed on his comment on your post';
            event(new NotificationDevice($post_owner->id,$message,"K2Help"));
        }
        if(auth()->user()->id != $comment_owner->id){
            $message=$replay_owner_name . ' replayed on your comment';
            event(new NotificationDevice($comment_owner->id,$message,"K2Help"));
        }
        $all_replayes=Replay::where('comment_id',$request->comment_id)->whereNotIn('user_id',[auth()->user()->id,$comment_owner->id])->get();
        if($comment_owner->id == auth()->user()->id ){
            $message=$replay_owner_name . ' replayed on his comment';
        }else{
            $message=$replay_owner_name . ' replayed on ' . $comment_owner_name . "'s comment";
        }
        foreach($all_replayes as $replay){
            $user=$replay->user;
            
            event(new NotificationDevice($user->id,$message,"K2Help"));
        }
        return $this->sendResponse($replay,'Replay created successfuly');
    }
}