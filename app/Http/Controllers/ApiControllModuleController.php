<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiControllModuleController extends Controller
{
    public function requirement(){
        return view('api-controll-module.program-view.requirement-list');

     }
     public function moduls_list(){
        return view('api-controll-module.program-view.moduls');

     }
}
