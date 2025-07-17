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

    public function confirmUpdate(Request $request, $id)
    {
        $user = auth()->user()->role === 'admin' ? (new DataLayer())->findUserById($id) : auth()->user();

        $validated = session('validated_data');

        if (!$validated) {
            return redirect()->route('users.edit', $id)->with('error', 'Nessuna modifica da confermare.');
        }

        return view('workspace.user.confirm_update', compact('user', 'validated'));
    }

    public function update(Request $request, $id)
    {
        $dl = new DataLayer();

        $user = auth()->user()->role === 'admin' ? $dl->findUserById($id) : Auth::user();

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
                auth()->user()->role === 'admin' ? 'required' : 'nullable',
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
        if (auth()->user()->role === 'admin' && !$request->has('confirm')) {
            // Salvo i dati in sessione per la conferma
            return redirect()
                ->route('user.confirm-update', $user->id)
                ->with('validated_data', $validated);
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

        return redirect()->route('users.edit', $id)->with('success', 'Modifica salvata con successo.');
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
        $dl = new DataLayer();
        $email = $request->input('email');
        $users = $dl->searchUser($email);

        return response()->json($users);
    }
}
