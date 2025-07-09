<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Serie;
use App\Models\EmgSample;
use App\Models\ImuSample;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ImportSensorData extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'app:import-sensor-data';
    protected $signature = 'import:sensordata {emgFile} {imuFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa dati EMG e IMU da file CSV, crea un csv per ogni serie e le associa ad utenti casuali';

    /**
     * Execute the console command.
     */
    public function handle(){
        $emg_file_name = $this->argument('emgFile');
        $imu_file_name = $this->argument('imuFile');

        $users = User::all();
        if ($users->isEmpty()) {
            $this->error("Nessun utente trovato.");
            return;
        }

        $seriesMap = [];

        $this->processCsvBySeries('emg', $emg_file_name, $seriesMap, $users);
        $this->processCsvBySeries('imu', $imu_file_name, $seriesMap, $users);

        foreach ($seriesMap as $seriesId => $info) {

            $category = Category::where('name', $info['label'])->first();
            if (!$category) {
                $this->error("Categoria '{$info['label']}' non trovata. Serie $seriesId saltata.");
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
            
            $this->info("Serie $seriesId salvata per utente {$info['user_id']}");
        }
    }

    private function processCsvBySeries($type, $filename, &$seriesMap, $users)
    {
        $handle = fopen(storage_path("app/$filename"), 'r');
        if (!$handle) {
            $this->error("Errore nell'apertura del file: $filename");
            return;
        }

        $header = fgetcsv($handle);
        $seriesWriters = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $seriesId = $data['series_id'];

            // Se è la prima volta che vede questa serie
            if (!isset($seriesMap[$seriesId])) {
                $user = $users->random();
                $seriesMap[$seriesId] = [
                    'user_id' => $user->id,
                    'label' => $data['label'] ?? null,
                    'emg_path' => null,
                    'imu_path' => null,
                ];
            }

            // Scrittura su file CSV
            $path = "private/series_data/$type/series_$seriesId.csv";
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
