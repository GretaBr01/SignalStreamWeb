<!DOCTYPE html>
<html>
<head>
    <title>Test Upload</title>
</head>
<body>
    <h2>Carica un file di test</h2>
    <form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Carica e scarica</button>
    </form>
</body>
</html>
