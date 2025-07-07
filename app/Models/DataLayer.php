<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
