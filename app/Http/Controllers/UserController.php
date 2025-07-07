<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\DataLayer;

class UserController extends Controller
{

    // public function edit(String $id){
    //     $dl = new DataLayer();

    //     $user = Auth::user();

    //     $name = $user-> 'name';
    //     $age = $dl->'age';
    //     $generi = $dl->listGeneri();
    //     $lingueAudio = $dl -> listLingue();
    //     $sottotitoli = $dl -> listLingue();

    //     if($film !== null){
    //         return view('film.editFilm')
    //                     ->with('film', $film)
    //                     ->with('registi', $registi)
    //                     ->with('generi', $generi)
    //                     ->with('lingueAudio', $lingueAudio)
    //                     ->with('lingueSub', $sottotitoli);
    //     } else{
    //         return view('errors.404')->with('message', 'FILM ID SBAGLIATO!');
    //     }
    // }

    public function update(Request $request)
    {
        $name = $request->input('name');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $sport = $request->input('sport');
        $training_duration = $request->input('training_duration');

        $user = Auth::user();

        // Validazione dei campi
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'sport' => 'nullable|string|max:255',
            'training_duration' => 'nullable|string|max:255',
        ]);
        
        $dl = new DataLayer();
        $dl->editUser(
            Auth::id(),
            $validated['name'] ?? null,
            $validated['age'] ?? null,
            $validated['gender'] ?? null,
            $validated['sport'] ?? null,
            $validated['training_duration'] ?? null
        );

        return redirect()->route('home');
    }
}
