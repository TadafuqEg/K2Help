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

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use DB;


class BaseGetController extends ApiController
{
    public function allCountries(){
        $countries = Country::where('is_active', 1)
                                ->with(['cities' => function ($q) {
                                    $q->where('is_active', 1)
                                        ->with(['streets' => function ($qu) {
                                            $qu->where('is_active', 1)
                                                ->select(['id', 'name','city_id']);
                                        }])
                                        ->select(['id', 'name','country_id']);
                                }])
                                ->select(['id', 'name'])
                                ->get();
        return $this->sendResponse($countries);
   }

    public function about(){
        $response['about']=Setting::where('key','about_app')->where('category','general')->where('type','textarea')->first()->value;
        $response['intro_image']=url(Setting::where('key','intro_image')->where('category','general')->where('type','file')->first()->value);
        return $this->sendResponse($response);
    }

    public function FAQs(){
        $FAQs=FAQ::where('is_active',1)->select('question','answer')->get();
        return $this->sendResponse($FAQs);
    }

}
