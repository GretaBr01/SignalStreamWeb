<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Models\DataLayer;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->input('role');

        $dl = new DataLayer();
        $users = $dl->listUsersFiltered($role);

        $roles = $dl->listRoles();

        return view('workspace.user.index', compact('users', 'roles'));
    }

    public function edit($id){
        $lang = Session::get('language', 'en');
        $dl = new DataLayer();
        $user = $dl->findUserById($id);
        
        if (auth()->user()->role === 'admin') {
            $roles = $dl->listRoles();
            return view('workspace.user.edit')->with('user', $user)->with('roles', $roles);
        }

        return view('workspace.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $dl = new DataLayer();

        $user = Auth::user()->role === 'admin' ? $dl->findUserById($id) : Auth::user();

        $name = $request->input('name');
        $email = $request->input('email');
        $role = $request->input('role');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $sport = $request->input('sport');
        $training_duration = $request->input('training_duration');

        // Validazione dei campi
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'sport' => 'nullable|string|max:255',
            'training_duration' => 'nullable|string|max:255',
        ]);
        
        
        $dl->editUser(
            $user->id,
            $validated['name'] ?? null,
            $validated['age'] ?? null,
            $validated['gender'] ?? null,
            $validated['sport'] ?? null,
            $validated['training_duration'] ?? null,
            $validated['email'] ?? null,
            $role
        );

        return redirect()->route('users.index');
    }

    public function confirmDestroy($id)
    {
        $dl = new DataLayer();
        $user = $dl->findUserById($id);
        if ($user !== null) {
            return view('workspace.user.deleteUser')->with('user', $user);
        } else {
            return view('errors.wrongID')->with('message','Wrong user ID has been used!');
        }
    }

    public function destroy(){
        return view('errors.501');
    }

    
}
