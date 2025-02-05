<?php
// app/Services/CSVImportService.php

namespace App\Services;

use App\Models\contact_sync;
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
            $data['isAppUser'] =  '0';
            $data['visible'] =  '0';
            $data['contact_id'] =  $parent_userid;
            $user_exist = contact_sync::where('email',$data['email'])
            ->orWhere('phone', $data['phone'])
            ->first();
            if($user_exist == null){
                contact_sync::create($data);
            }
        }

        fclose($file);
    }
}
