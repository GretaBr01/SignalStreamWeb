<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestFileController extends Controller
{
     public function showForm()
    {
        return view('test_upload');
    }

    public function uploadAndDownload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $fileName = 'test_' . time() . '.' . $file->getClientOriginalExtension();

        // Salva nel disco "private"
        $path = $file->storeAs('test_files', $fileName, 'private');

        // Genera nome per il download sostituendo / con _
        $downloadName = str_replace('/', '_', $path);

        return Storage::disk('private')->download('series_data/emg/series_0.csv');
    }
}
