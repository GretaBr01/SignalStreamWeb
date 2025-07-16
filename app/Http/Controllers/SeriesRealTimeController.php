<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\DataLayer;

class SeriesRealTimeController extends Controller
{

    public function index(){
        return view('workspace.rtseries.acquisizione');
    }

    public function store(Request $request)
    {
        $category_id = $request->input('category_id');
        $note = $request->input('note', '');
        $emg = $request->input('emg', []);
        $imu = $request->input('imu', []);
        $user_id = auth()->id();

        $dl = new DataLayer();
        $dl->addRealTimeSerie($note, $user_id, $emg, $imu, $category_id);
        return redirect()->route('series.index');
    }

    public function review(Request $request)
    {
        $emg = $request->input('emg');
        $imu = $request->input('imu');

        $dl = new DataLayer();
        $categories = $dl->listCategories();

        return view('workspace.rtseries.review')->with('categories', $categories)->with('emg', $emg)->with('imu', $imu);
    }
}
