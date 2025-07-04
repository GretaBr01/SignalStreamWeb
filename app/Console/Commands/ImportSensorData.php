<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Serie;
use App\Models\EmgSample;
use App\Models\ImuSample;
use App\Models\User;
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
    protected $description = 'Importa dati EMG e IMU da file CSV, crea serie dinamiche e le associa ad utenti casuali';

    /**
     * Execute the console command.
     */
    public function handle(){
        $emgPath = storage_path('app/' . $this->argument('emgFile'));
        $imuPath = storage_path('app/' . $this->argument('imuFile'));

        if (!file_exists($emgPath) || !file_exists($imuPath)) {
            $this->error('File non trovati.');
            return;
        }


        $users = User::all();
        if ($users->isEmpty()) {
            $this->error("Nessun utente trovato nel database.");
            return;
        }

        $seriesCache = [];

        $imuFile = fopen($imuPath, 'r');
        $imu_header = fgetcsv($imuFile);
        $imu_header = array_map('trim', $imu_header);


        while (($imu_row = fgetcsv($imuFile)) !== false) {
            $imu_data = array_combine($imu_header, $imu_row);

            $seriesKey = $imu_data['series_id'];

            // Se la serie non è ancora stata creata, la creiamo ora
            if (!isset($seriesCache[$seriesKey])) {
                $randomUser = $users->random();

                $newSeries = Serie::create([
                    'user_id' => $randomUser->id,
                    'label' => $imu_data['label'],
                    'started_at' => now(),
                ]);

                $seriesCache[$seriesKey] = $newSeries->id;

                $this->info("Serie ID virtuale {$seriesKey} creata come ID DB {$newSeries->id}, utente {$randomUser->id}");
            }

            ImuSample::create([
                'series_id' => $seriesCache[$seriesKey],
                'timestamp' => $imu_data['timestamp'],
                'acc_x' => $imu_data['acc_x'] ?? null,
                'acc_y' => $imu_data['acc_y'] ?? null,
                'acc_z' => $imu_data['acc_z'] ?? null,
                'gyr_x' => $imu_data['gyr_x'] ?? null,
                'gyr_y' => $imu_data['gyr_y'] ?? null,
                'gyr_z' => $imu_data['gyr_z'] ?? null,
            ]);
        }
        fclose($imuFile);

        $this->info("Importazione imu completata. Serie create: " . count($seriesCache));

        // EMG
        $emgFile = fopen($emgPath, 'r');
        $emg_header = fgetcsv($emgFile);
        $emg_header = array_map('trim', $emg_header);

        while (($emg_row = fgetcsv($emgFile)) !== false) {
            $emg_data = array_combine($emg_header, $emg_row);

            $seriesKey = $emg_data['series_id'];

            // Se la serie non è ancora stata creata, la creiamo ora
            if (!isset($seriesCache[$seriesKey])) {
                $randomUser = $users->random();

                $newSeries = Serie::create([
                    'user_id' => $randomUser->id,
                    'label' => $emg_data['label'],
                    'started_at' => now(),
                ]);

                $seriesCache[$seriesKey] = $newSeries->id;

                $this->info("Serie ID virtuale {$seriesKey} creata come ID DB {$newSeries->id}, utente {$randomUser->id}");
            }

            EmgSample::create([
                'series_id' => $seriesCache[$seriesKey],
                'timestamp' => $emg_data['timestamp'],
                'emg0' =>  $emg_data['emg0'] ?? null,
                'emg1' => $emg_data['emg1'] ?? null,
                'emg2' => $emg_data['emg2'] ?? null,
                'emg3' => $emg_data['emg3'] ?? null,
            ]);
        }
        fclose($emgFile);
        $this->info("Importazione emg completata. Serie create: " . count($seriesCache));
    }
}
