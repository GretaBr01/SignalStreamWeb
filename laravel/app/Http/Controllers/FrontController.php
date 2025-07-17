<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function getHome(){
        $lang = Session::get('language', 'en');
        return view('index');
    }

    public function getHowWorksPage(){
        $lang = Session::get('language', 'en');
        return view('how_it_works');
    }


}
