<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

use App\Models\DataLayer;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        return view('workspace.categories.index')->with('categories', $categories);
    }

    public function showImage($path)
    {
        $fullPath = $path;

        if (!Storage::disk('private')->exists($fullPath)) {
            abort(404, 'Immagine non trovata');
        }

        return response()->file(Storage::disk('private')->path($fullPath));
    }

    public function edit($id){
        $lang = Session::get('language', 'en');
        $dl = new DataLayer();
        $category = $dl->findCategoryById($id);

        return view('workspace.categories.edit', compact('category'));
    }

    public function update(Request $request, $category_id)
    {
        // Validazione input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category_id,
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $image = $request->file('image');
        $name = $validated['name'];

        $dl = new DataLayer();
        $dl->editCategory($category_id, $name, $image);

        return redirect()->route('categories.index');
    }

    public function store(Request $request)
    {
        // Validazione input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|max:2048',
        ]);

        $image = $request->file('image');

        $dl = new DataLayer();
        $dl->createCategory($validated['name'], $image);

        return redirect()->route('categories.index');
    }

    public function confirmDestroy($id)
    {
        $dl = new DataLayer();
        $category = $dl->findCategoryById($id);
        if ($category !== null) {
            return view('workspace.categories.delete')->with('category', $category);
        } else {
            return view('errors.wrongID')->with('message','Wrong category ID has been used!');
        }
    }

    public function destroy($id){
        // return view('errors.501');
        $dl = new DataLayer();
        $dl->deleteCategory($id);
        return Redirect::to(route('categories.index'));
    }

}
