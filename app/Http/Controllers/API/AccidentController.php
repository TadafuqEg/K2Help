<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FAQ;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Country;
use GuzzleHttp\Client;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use App\Models\Accident;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use DB;


class AccidentController extends ApiController
{
    public function create_accident(Request $request){
        $validator  =   Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            
            'phone' => ['required'],
            'description' =>['required'],
            'type'=>['required']
            
            
        ]);
        // dd($request->all());
        if ($validator->fails()) {

            return $this->sendError(null,$validator->errors());
        }
        $map=[];
        if(($request->lat != null && $request->lng != null)||$request->link != null){
          $map['lat']=$request->lat;
          $map['lng']=$request->lng;
          $map['link']=$request->link;
        }else{
            $map=null;
        }
        $accident=Accident::create(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'phone'=>$request->phone,'description'=>$request->description,'location'=>json_encode($map),'street_id'=>$request->street_id,'type'=>$request->type]);
        if($request->images){
            foreach($request->images as $image){
                uploadMedia($image, $accident->accidentImageCollection, $accident);
    
            }
        }
        
        return $this->sendResponse(null,'Help created successfuly');
    }

 

}
