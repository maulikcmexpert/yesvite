<form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file">
    <button type="submit">Import CSV</button>
</form>