@extends('workspace.master_workspace')

@section('title', 'Crea Nuova Serie')

@section('main_content')
<div class="container mt-4">
    <h4 class="mb-4">Crea una nuova serie</h4>

    <form action="{{ route('series.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Note -->
        <div class="mb-3">
            <label for="note" class="form-label">Note (opzionale):</label>
            <textarea name="note" id="note" class="form-control" rows="3">{{ old('note') }}</textarea>
        </div>

        <!-- File EMG -->
        <div class="mb-3">
            <label for="emg_file" class="form-label">File EMG (.csv):</label>
            <input type="file" name="emg_file" id="emg_file" class="form-control" accept=".csv" required>
        </div>

        <!-- File IMU -->
        <div class="mb-3">
            <label for="imu_file" class="form-label">File IMU (.csv):</label>
            <input type="file" name="imu_file" id="imu_file" class="form-control" accept=".csv" required>
        </div>

        <!-- Categoria -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Categoria:</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Seleziona Categoria --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->display_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crea Serie</button>
    </form>
</div>
@endsection
