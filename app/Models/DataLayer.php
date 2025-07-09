<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage; 

class DataLayer extends Model
{
    public function editUser($id, $name, $age, $gender, $sport, $training_duration)
    {
        $user = User::find($id);

        if (!$user) {
            throw new \Exception("Utente non trovato");
        }

        if ($name !== null) {
            $user->name = $name;
        }

        if ($age !== null) {
            $user->age = $age;
        }

        if ($gender !== null) {
            $user->gender = $gender;
        }

        if ($sport !== null) {
            $user->sport = $sport;
        }

        if ($training_duration !== null) {
            $user->training_duration = $training_duration;
        }

        $user->save();
    }

    public function listSeries($userID)
    {
        $seriesList = Serie::with('category')->where('user_id',$userID)->get(); 
        return $seriesList;
    }

    public function listCategories(){
        return Category::orderBy('name','asc')->get();
    }

    public function findSerieById($serieID, $userID){
        return Serie::where('id', $serieID)->where('user_id', $userID)->first();
    }


    public function getEmgPathBySeriesID($serie_id){
        $sample = EmgSample::where('series_id', $serie_id)->first();
        return $sample->path;
    }

    public function getImuPathBySeriesID($serie_id){
        $sample = ImuSample::where('series_id', $serie_id)->first();
        return $sample->path;
    }

    public function addSerie($note, $user_id, $emg_file, $imu_file, $category_id){
        // Creazione della serie
        $serie = new Serie();
        $serie->note = $note;
        $serie->user_id = $user_id;
        $serie->category_id = $category_id;
        $serie->save();

        // Salvataggio del file    
        $originalName = pathinfo($emg_file->getClientOriginalName(), PATHINFO_FILENAME);
        $emgFileName = 'series_' . $serie->id . '_emg_' . $originalName . '.'. $emg_file->getClientOriginalExtension();

        $originalName = pathinfo($imu_file->getClientOriginalName(), PATHINFO_FILENAME);
        $imuFileName = 'series_' . $serie->id . '_imu_' . $originalName . '.'. $imu_file->getClientOriginalExtension();

        $emgPath = $emg_file->storeAs('series_data/emg', $emgFileName);
        $imuPath = $imu_file->storeAs('series_data/imu', $imuFileName);

        $serie->emgSamples()->create([
            'path' => $emgPath,
        ]);

        $serie->imuSamples()->create([
            'path' => $imuPath,
        ]);
    }

    public function deleteSerie($serie_id){
        // Elimina i file associati e i record EMG
        $series = Serie::find($serie_id);
        $emg_samples = EmgSample::where('series_id', $serie_id)->get();
        foreach ($emg_samples as $emg) {
            if ($emg->path && Storage::exists($emg->path)) {
                Storage::delete($emg->path);
            }
            $emg->delete();
        }

        // Elimina i file associati e i record IMU
        $imu_samples = ImuSample::where('series_id', $serie_id)->get();;
        foreach ($imu_samples as $imu) {
            if ($imu->path && Storage::exists($imu->path)) {
                Storage::delete($imu->path);
            }
            $imu->delete();
        }

        // Elimina la serie
        $series->delete();
    }


}
