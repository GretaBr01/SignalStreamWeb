<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Models\DataLayer;
use App\Models\User;
use Illuminate\Validation\Rule;

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
            'email' => [
                Auth::user()->role === 'admin' ? 'required' : 'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'sport' => 'nullable|string|max:255',
            'training_duration' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);
        
        // Se l'admin non ha ancora confermato, mostra la pagina di conferma
        if ($role === 'admin' && !$request->has('confirm')) {
            return view('workspace.user.confirm_update', [
                'user' => $user,
                'validated' => $validated,
            ]);
        }
        
        $dl->editUser(
            $user->id,
            $validated['name'] ?? null,
            $validated['age'] ?? null,
            $validated['gender'] ?? null,
            $validated['sport'] ?? null,
            $validated['training_duration'] ?? null,
            $validated['email'] ?? null,
            $validated['role'] ?? null
        );

        return redirect()->back()->with('success', 'Nota aggiornata con successo.');
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

    public function search(Request $request)
    {
        $email = $request->input('email');

        $users = User::where('email', 'like', '%' . $email . '%')->get(['id', 'name', 'email', 'role']);

        return response()->json($users);
    }
}
