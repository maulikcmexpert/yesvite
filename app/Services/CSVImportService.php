<?php
// app/Services/CSVImportService.php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CSVImportService
{
    public function import($filePath)
    {
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Assuming the first row contains column headers
        $user = Auth::user();
        $parent_userid = $user->id;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $data['app_user'] =  '0';
            $data['prefer_by'] =  'phone';
            $data['user_parent_id'] =  $parent_userid;
            $data['is_user_phone_contact'] =  '1';
            $data['parent_user_phone_contact'] =  $parent_userid;
            $user_exist = User::where('email',$data['email'])->first();
            if($user_exist == null){
                User::create($data);
            }
        }

        fclose($file);
    }
}
