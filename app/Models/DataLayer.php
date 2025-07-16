<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage; 

class DataLayer extends Model
{

    protected function findOrFailById($modelClass, $id)
    {
        $model = $modelClass::find($id);
        if (!$model) {
            throw new \Exception("Elemento non trovato per $modelClass con ID $id");
        }
        return $model;
    }

    protected function findById($modelClass, $id)
    {
        return $modelClass::find($id);
    }

    public function editUser($id, $name, $age, $gender, $sport, $training_duration, $email, $role)
    {
        $user = User::find($id);

        if (!$user) {
            throw new \Exception("Utente non trovato");
        }

        if ($name !== null) $user->name = $name;
        if ($age !== null) $user->age = $age;
        if ($gender !== null) $user->gender = $gender;
        if ($sport !== null) $user->sport = $sport;
        if ($training_duration !== null) $user->training_duration = $training_duration;
        if ($email !== null) $user->email = $email;
        if ($role !== null) $user->role = $role;

        $user->save();
    }
    
    private function saveImage($name, $image){
        $name = $name . '.'. $image->getClientOriginalExtension();
        $path = $image->storeAs('image', $name, 'private'); 
        return $path;
    }

    public function editCategory($category_id, $name, $image)
    {
        $category = $this->findOrFailById(Category::class, $category_id);
        $category->name = $name;

        if ($image) {
            // Rimuovi immagine esistente
            if ($category->image && Storage::disk('private')->exists($category->image)) {
                Storage::disk('private')->delete($category->image);
            }

            // Salva nuova immagine
            $path = $this->saveImage($name, $image); 
            $category->image = $path;
        }

        $category->save();
    }

    public function createCategory($name, $image = null)
    {
        $category = new Category();
        $category->name = $name;

        if ($image) {
            $path = $this->saveImage($name, $image);
            $category->image = $path;
        }

        $category->save();
    }

    public function deleteCategory($category_id){
        // Elimina i file associati e i record EMG
        $category = $this->findOrFailById(Category::class, $category_id);

        if ($category->serie()->exists()) {
            throw new \Exception("Impossibile eliminare la categoria: ha serie associate.");
        }
        
        $path = $category->image;
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }

        $category->delete();
    }

    public function listSeries($userID)
    {
        $seriesList = Serie::with('category')->where('user_id',$userID)->get(); 
        return $seriesList;
    }

    public function listAllSeries()
    {
        return Serie::all();
    }

    public function listSeriesFiltered($userId = null, $categoryId = null)
    {
        $query = Serie::query()->with('category');

        if (!is_null($userId)) {
            $query->where('user_id', $userId);
        }

        if (!is_null($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    /*******
     * User
     * *****/
    public function listUsers()
    {
        return User::orderBy('name', 'asc')->get();
    }

    public function listUsersFiltered($role = null)
    {
        $query = User::query();

        if (!is_null($role)) {
            $query->where('role', $role);
        }

        return $query->orderBy('name', 'asc')->get();
    }

    public function listRoles(){
        return User::select('role')->distinct()->pluck('role');
    }

    public function searchUser($email){
        return User::where('email', 'like', '%' . $email . '%')->get(['id', 'name', 'email', 'role']);
    }



    public function listCategories(){
        return Category::orderBy('name','asc')->get();
    }

    public function findSerieById($serieID, $user)
    {
        $query = Serie::where('id', $serieID);
        
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query->first();
    }

    public function findUserById($userID)
    {
        return $this->findById(User::class, $userID);
    }

    public function findCategoryById($categoryID)
    {
        return $this->findById(Category::class, $categoryID);
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

        $emgPath = $emg_file->storeAs('series_data/emg', $emgFileName, 'private');
        $imuPath = $imu_file->storeAs('series_data/imu', $imuFileName, 'private');

        $serie->emgSamples()->create([
            'path' => $emgPath,
        ]);

        $serie->imuSamples()->create([
            'path' => $imuPath,
        ]);
    }

    public function updateNoteSerie($id_serie, $note){
        // Eventualmente: controlla autorizzazioni
        $serie = $this->findById(Serie::class, $id_serie);
        if (auth()->user()->id !== $serie->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }
        $serie->note = $note;
        $serie->save();
    }

    private function arrayToCsvString(array $data): string
    {
        if (empty($data)) return '';

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    public function addRealTimeSerie($note, $user_id, $emg_file, $imu_file, $category_id){
        // Creazione della serie Acquisita da Node.js
        $serie = new Serie();
        $serie->note = $note;
        $serie->user_id = $user_id;
        $serie->category_id = $category_id;
        $serie->save();

        $timestamp = now()->format('Ymd_His');
        $filename = "serie_{$serie->id}_{$this->findCategoryById($category_id)->name}_{$timestamp}_";

        $emgPath = "series_data/emg/{$filename}_emg.csv";
        $imuPath = "series_data/imu/{$filename}_imu.csv";

        // Decodifica JSON -> array
        $emg = json_decode($emg_file, true);
        $imu = json_decode( $imu_file, true);

        // Salva i file
        Storage::disk('private')->put($emgPath, $this->arrayToCsvString($emg));
        Storage::disk('private')->put($imuPath, $this->arrayToCsvString($imu));

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

    public function pathCsvEmg($serie_id)
    {
        $emg_samples = EmgSample::where('series_id', $serie_id)->first();
        if (!$emg_samples) {
            abort(404, 'File EMG non trovato.');
        }

        return $emg_samples->path;
    }

    public function pathCsvImu($serie_id)
    {
        $imu_samples = ImuSample::where('series_id', $serie_id)->first();
        if (!$imu_samples) {
            abort(404, 'File EMG non trovato.');
        }
        
        return $imu_samples->path;
    }


}
