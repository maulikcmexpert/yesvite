<?php
// app/Services/CSVImportService.php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class CSVImportService
{
    public function import($filePath)
    {
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Assuming the first row contains column headers
        $parent_userid =  decrypt(Session::get('user')['id']);
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $data['app_user'] =  '0';
            $data['prefer_by'] =  'phone';
            $data['user_parent_id'] =  $parent_userid;
            $data['is_user_phone_contact'] =  '1';
            $data['parent_user_phone_contact'] =  $parent_userid;



            // Insert data into the database
            User::create($data);
        }

        fclose($file);
    }
}
