<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function getHome(){
        $lang = Session::get('language', 'en');
        // $filePath = "citations/{$lang}.txt";

        // if (!Storage::exists($filePath)) {
        //     $filePath = 'citations/en.txt';
        // }

        // $content = Storage::get($filePath);

        // return view('index')->with('content', $content);
        return view('index');
    }
}
