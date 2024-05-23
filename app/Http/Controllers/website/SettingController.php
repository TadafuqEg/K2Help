<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FAQ;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Validation\Rule;
use Image;
use Str;
use File;

class SettingController extends ApiController
{
    public function index(){
        $settings=Setting::orderBy('id','desc')->paginate(10);
        return view('website.settings.index',compact('settings'));

    }
    public function edit_setting($id){
        $setting=Setting::where('id',$id)->first();
        return view('website.settings.setting.edit',compact('setting'));
    }
    public function update_setting(Request $request,$id){
        //dd($request->all());
        $setting=Setting::where('id',$id)->first();
        if($setting->type=='file'&& array_key_exists('value',$request->all())){
            $invitation_code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 12);
            $image = $setting->id.'_'.$invitation_code.'_'.time() . '.' . $request->value->extension();
        
            $request->value->move(public_path('setting_images/'), $image);
            $path = ('/setting_images/') . $image;
            $input['label']=$request->label;
            $input['value']=$path;
            $setting->fill($input)->save();
        }else{
            $input['label']=$request->label;
            $input['value']=$request->value;
            $setting->fill($input)->save();
        }
        return redirect('/settings');

    }
}