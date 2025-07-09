<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\DataLayer;
use App\Models\Serie;
use App\Models\EmgSample;
use App\Models\ImuSample;

class SeriesController extends Controller
{
    public function index(Request $request){

        $dl = new DataLayer();

        $user = auth()->user();
        $categoryId = $request->input('category_id');
        $userId = $user->id;

        if ($user->role === 'admin') {
            // Se è admin e viene passato un user_id lo uso per filtrare
            if ($request->filled('user_id')) {
                $userId = $request->input('user_id');
            } else {
                $userId = null;
            }

            $seriesList = $dl->listSeriesFiltered($userId, $categoryId);
            $users = $dl->listUsers();
        } else {
            // Utente normale: può filtrare solo per categoria
            $seriesList = $dl->listSeriesFiltered($userId, $categoryId);
            $users = null;
        }

        $categories = $dl->listCategories();

        return view('workspace.series.index', [
            'series_list' => $seriesList,
            'users' => $users,
            'categories' => $categories,
        ]);

    }

    public function create(){
        $dl = new DataLayer();
        $categories = $dl->listCategories();

        if (auth()->user()->role === 'admin') {
            $users = $dl->listUsers();
            return view('workspace.series.addSerie')->with('categories', $categories)->with('users', $users);
        } else {
            return view('workspace.series.addSerie')->with('categories', $categories);
        }
        
    }

    public function store(Request $request)
    {
        $note = $request->input('note');
        $emg_file = $request->file('emg_file');
        $imu_file = $request->file('imu_file');
        $category_id = $request->input('category_id');

        if (auth()->user()->role === 'admin' && $request->filled('user_id')) {
            $user_id = $request->input('user_id');
        }else{
            $user_id = auth()->id();
        }

        $request->validate([
            'note' => 'nullable|string',
            'emg_file' => 'required|mimes:csv,txt|max:10240',
            'imu_file' => 'required|mimes:csv,txt|max:10240',
            'category_id' => 'required|integer'
        ]);

        $dl = new DataLayer();
        $dl->addSerie($note, $user_id, $emg_file, $imu_file, $category_id);
        return redirect()->route('workspace.series');
    }


    public function show($id){
        $dl = new DataLayer();

        $serie = $dl->findSerieById($id, auth()->user());

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
        $serie = $dl->findSerieById($id, auth()->user());
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

    public function downloadEmg($id)
    {
        $dl = new DataLayer();
        $path = $dl->pathCsvEmg($id);
        return Storage::disk('private')->download($path);
    }

    public function downloadImu($id)
    {
        $dl = new DataLayer();
        $path = $dl->pathCsvImu($id);
        return Storage::disk('private')->download($path);
    }

}
