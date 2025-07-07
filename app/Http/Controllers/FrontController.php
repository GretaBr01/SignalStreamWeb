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

    public function getEditUser(){
        $lang = Session::get('language', 'en');
        return view('workspace.editUser');
    }

    public function getStorico(){
        $lang = Session::get('language', 'en');
        return view('workspace.storicoDati');
    }


}
