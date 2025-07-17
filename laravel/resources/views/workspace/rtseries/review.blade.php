@extends('workspace.master_workspace')

@section('title', 'Salva Serie')

@section('main_content')
<div class="container mt-4">
    <h3 class="mb-4 text-purple">Salva Gesto Registrato</h3>

    <form action="{{ route('rtseries.store') }}" method="POST">
        @csrf
        <input type="hidden" name="emg" id="emgInput">
        <input type="hidden" name="imu" id="imuInput">

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

        <div class="mb-3">
            <label for="note" class="form-label">Note Aggiuntive</label>
            <textarea class="form-control" name="note" id="note" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salva Serie</button>
    </form>
</div>

<script>
    const emg = JSON.parse(localStorage.getItem("emgData") || "[]");
    const imu = JSON.parse(localStorage.getItem("imuData") || "[]");

    document.getElementById('emgInput').value = JSON.stringify(emg);
    document.getElementById('imuInput').value = JSON.stringify(imu);

    // Pulizia dopo invio
    document.querySelector('form').addEventListener('submit', () => {
        localStorage.removeItem("emgData");
        localStorage.removeItem("imuData");
    });
</script>
@endsection
