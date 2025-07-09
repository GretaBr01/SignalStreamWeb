<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\DataLayer;
use App\Models\Serie;
use App\Models\EmgSample;
use App\Models\ImuSample;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class SeriesController extends Controller
{
    public function index(){
        $dl = new DataLayer();
        $seriesList = $dl->listSeries(auth()->user()->id);
        return view('workspace.series.index')->with('series_list', $seriesList);

        // $user = auth()->user();
        // $series = $user->series; // Relazione 1:N: User → Series
        // return view('series.index', compact('series'));
    }

    public function create(){
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        return view('workspace.series.addSerie')->with('categories', $categories);
    }

    public function store(Request $request)
    {
        $note = $request->input('note');
        $emg_file = $request->file('emg_file');
        $imu_file = $request->file('imu_file');
        $category_id = $request->input('category_id');

        $request->validate([
            'note' => 'nullable|string',
            'emg_file' => 'required|mimes:csv,txt|max:10240',
            'imu_file' => 'required|mimes:csv,txt|max:10240',
            'category_id' => 'required|integer'
        ]);

        $dl = new DataLayer();
        $dl->addSerie($note, auth()->id(), $emg_file, $imu_file, $category_id);
        return redirect()->route('workspace.series');
    }


    public function show($id){
        $dl = new DataLayer();
        $serie = $dl->findSerieById($id, auth()->user()->id);

        if ($serie !== null) {
            return view('workspace.series.show', compact('serie'));
        } else {
            return view('errors.wrongID')->with('message','Wrong serie ID has been used!');
        }        
    }

    public function getEmgCsv($serie_id){
        $dl = new DataLayer();
        $csv_path = $dl->getEmgPathBySeriesID($serie_id);
        $path = storage_path('app/' . $csv_path);

        if (!file_exists($path)) abort(404);
        return response()->file($path, ['Content-Type' => 'text/csv']);
    }

    public function getImuCsv($serie_id){
        $dl = new DataLayer();
        $csv_path = $dl->getImuPathBySeriesID($serie_id);
        $path = storage_path('app/' . $csv_path);

        if (!file_exists($path)) abort(404);
        return response()->file($path, ['Content-Type' => 'text/csv']);
    }

    public function confirmDestroy($id)
    {
        $dl = new DataLayer();
        $serie = $dl->findSerieById($id, auth()->user()->id);
        if ($serie !== null) {
            return view('workspace.series.deleteSerie')->with('serie', $serie);
        } else {
            return view('errors.wrongID')->with('message','Wrong serie ID has been used!');
        }
    }


    public function destroy($id){
        // echo "Remove the specified resource from storage";
        // abort(501);

        $dl = new DataLayer();
        $dl->deleteSerie($id);
        return Redirect::to(route('workspace.series'));
    }

}
