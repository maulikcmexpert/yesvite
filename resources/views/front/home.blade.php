<form action="{{ route('import.vcf') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="vcf_file">
    <button type="submit">Import VCF</button>
</form>