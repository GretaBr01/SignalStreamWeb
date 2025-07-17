<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Serie;
use App\Models\EmgSample;
use App\Models\ImuSample;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createUsers();
        $this->createCategories();
        $this->importSensorData();
    }

    private function createUsers() {

        User::factory()->create([
            'name' => 'Greta Brognoli',
            'email' => 'greta.brognoli@gmail.com',
            'password' => Hash::make('greta'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Marco Verdi',
            'email' => 'marco.verdi@libero.it',
            'password' => Hash::make('greta')
        ]);

        User::factory()->create([
            'name' => 'Alessandra Rossi',
            'email' => 'alessandra.rossi@gmail.com',
            'password' => Hash::make('greta')
        ]);
    }

    private function createCategories(){
        Category::factory()->create([
            'name' => 'left',
            'image' => 'image/left.jpg'
        ]);

        Category::factory()->create([
            'name' => 'turning',
            'image' => 'image/right.jpg'
        ]);

        Category::factory()->create([
            'name' => 'stopping',
            'image' => 'image/stop.jpg'
        ]);

        Category::factory()->create([
            'name' => 'notevent'
        ]);
    }

    private function importSensorData() {
        $emgFile = 'emg_dataset.csv';
        $imuFile = 'imu_dataset.csv';


        if (!file_exists(storage_path("app/$emgFile")) || !file_exists(storage_path("app/$imuFile"))) {
            echo "File CSV non trovati in storage/app/: $emgFile o $imuFile\n";
            return;
        }

        $users = User::all();
        if ($users->isEmpty()) {
            echo "Nessun utente trovato, importazione annullata.\n";
            return;
        }

        $seriesMap = [];

        $this->processCsvBySeries('emg', $emgFile, $seriesMap, $users);
        $this->processCsvBySeries('imu', $imuFile, $seriesMap, $users);

        foreach ($seriesMap as $seriesId => $info) {
            $category = Category::where('name', $info['label'])->first();
            if (!$category) {
                echo "Categoria '{$info['label']}' non trovata. Serie $seriesId saltata.\n";
                continue;
            }

            $serie = Serie::create([
                'category_id' => $category->id,
                'user_id' => $info['user_id'],
                'note' => null,
            ]);

            if (isset($info['emg_path'])) {
                EmgSample::create([
                    'series_id' => $serie->id,
                    'path' => $info['emg_path'],
                ]);
            }

            if (isset($info['imu_path'])) {
                ImuSample::create([
                    'series_id' => $serie->id,
                    'path' => $info['imu_path'],
                ]);
            }

            echo "Serie $seriesId salvata per utente ID {$info['user_id']}\n";
        }
    }

    private function processCsvBySeries($type, $filename, &$seriesMap, $users)
    {
        $handle = fopen(storage_path("app/$filename"), 'r');
        if (!$handle) {
            echo "Errore nell'apertura del file: $filename\n";
            return;
        }

        $header = fgetcsv($handle);
        $seriesWriters = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $seriesId = $data['series_id'];

            if (!isset($seriesMap[$seriesId])) {
                $user = $users->random();
                $seriesMap[$seriesId] = [
                    'user_id' => $user->id,
                    'label' => $data['label'] ?? null,
                    'emg_path' => null,
                    'imu_path' => null,
                ];
            }

            $path = "series_data/$type/series_$seriesId.csv";
            $fullPath = storage_path("app/$path");

            if (!isset($seriesWriters[$seriesId])) {
                Storage::makeDirectory("series_data/$type");
                $seriesWriters[$seriesId] = fopen($fullPath, 'w');
                fputcsv($seriesWriters[$seriesId], $header);
                $seriesMap[$seriesId]["{$type}_path"] = $path;
            }

            fputcsv($seriesWriters[$seriesId], $row);
        }

        fclose($handle);
        foreach ($seriesWriters as $writer) {
            fclose($writer);
        }
    }

}
