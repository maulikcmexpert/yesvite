<?php
// app/Services/CSVImportService.php

namespace App\Services;

use App\Models\User;

class CSVImportService
{
    public function import($filePath)
    {
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Assuming the first row contains column headers

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            dd($data);
            // Insert data into the database
            User::create($data);
        }

        fclose($file);
    }
}
