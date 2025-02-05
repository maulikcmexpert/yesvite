<?php
// app/Services/CSVImportService.php

namespace App\Services;

use App\Models\contact_sync;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CSVImportService
{
    public function import($filePath)
    {
        DB::beginTransaction();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Assuming the first row contains column headers
        $user = Auth::user();
        $parent_userid = $user->id;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $data['isAppUser'] =  '0';
            $data['visible'] =  '0';
            $data['contact_id'] =  $parent_userid;
            // $user_exist = contact_sync::where('email',$data['email'])
            // ->orWhere('phone', $data['phone'])
            // ->first();

            if ($data['email'] != "") {
                $existingContact = contact_sync::where('email', $data['email'])->first();
                if (isset($existingContact)) {
                    $existingContact->update([
                        'isAppUser' => $existingContact->isAppUser,
                        'phone' => '',
                        'firstName' => $data['firstName'] ?? $existingContact->firstName,
                        'lastName' => $data['lastName'] ?? $existingContact->lastName,
                        'photo' => $data['photo'] ?? $existingContact->photo,
                        'phoneWithCode' => '',
                        'visible' => ('0' ?? $existingContact->visible),
                        'preferBy' => $data['prefer_by'] ?? $existingContact->preferBy,
                    ]);
                    $existingContact->sync_id = $existingContact->id;
                    $updatedContacts[] = $existingContact;
                } else {
                    $newContact = new contact_sync();
                    $newContact->userId = null;
                    $newContact->contact_id = $user->id;
                    $newContact->firstName = $data['firstname'] ?? '';
                    $newContact->lastName = $data['lastname'] ?? '';
                    $newContact->phone = '';
                    $newContact->email = $data['email'] ?? '';
                    $newContact->photo = $data['photo'] ?? '';
                    $newContact->phoneWithCode = '';
                    $newContact->isAppUser = '0';
                    $newContact->visible = '0';
                    $newContact->preferBy = $data['prefer_by'] ?? '';
                    $newContact->created_at = now();
                    $newContact->updated_at = now();
                    $newContact->save();

                    $newContact->sync_id = $newContact->id;
                    $newContacts[] = $newContact;
                }
            }

            if ($data['phone'] != "" && strlen($data['phone']) > 5) {
                $existingContact = contact_sync::where('phoneWithCode', $data['phone'])->first();
                if (isset($existingContact)) {
                    $existingContact->update([
                        'isAppUser' => $existingContact->isAppUser,
                        'phone' => $data['phone'] ?? $existingContact->phone,
                        'firstName' => $data['firstName'] ?? $existingContact->firstName,
                        'lastName' => $data['lastName'] ?? $existingContact->lastName,
                        'photo' => $data['photo'] ?? $existingContact->photo,
                        'phoneWithCode' => $data['phone'] ?? $existingContact->phoneWithCode,
                        'visible' => ('0' ?? $existingContact->visible),
                        'preferBy' => $data['prefer_by'] ?? $existingContact->preferBy,
                    ]);
                    $existingContact->sync_id = $existingContact->id;

                    $updatedContacts[] = $existingContact;
                } else {
                    $newContact = new contact_sync();
                    $newContact->userId = null;
                    $newContact->contact_id = $user->id;
                    $newContact->firstName = $data['firstName'] ?? '';
                    $newContact->lastName = $data['lastName'] ?? '';
                    $newContact->phone = $data['phone'] ?? '';
                    $newContact->email = '';
                    $newContact->photo = $data['photo'] ?? '';
                    $newContact->phoneWithCode = $data['phone'] ?? '';
                    $newContact->isAppUser = '0';
                    $newContact->visible = '0';
                    $newContact->preferBy = $data['prefer_by'] ?? '';
                    $newContact->created_at = now();
                    $newContact->updated_at = now();
                    $newContact->save();

                    $newContact->sync_id = $newContact->id;
                    $newContacts[] = $newContact;
                }
            }
            DB::commit();
            $allSyncedContacts = array_merge($newContacts, $updatedContacts);

            // $emails = array_filter(array_column($input, 'email'));
            // $phoneNumbers = array_filter(array_column($input, 'phone_number'));

            $userDetails = User::select('id', 'email', 'phone_number', 'firstname', 'lastname', 'profile', 'app_user', 'visible', 'prefer_by')
                ->where('email', $data['email'])
                ->where('app_user', '1')
                // ->orWhere('phone_number', $contact['phone_number'])
                ->get();
            // dd($userDetails);
            foreach ($userDetails as $userDetail) {

                contact_sync::where('contact_id', $user->id)
                    ->where(function ($query) use ($userDetail) {
                        $query->where('email', $userDetail->email)
                            ->orWhere('phone', $userDetail->phone_number);
                    })
                    ->update([
                        'userId' => $userDetail->id,
                        'firstName' => $userDetail->firstname,
                        'lastName' => $userDetail->lastname
                    ]);
                $index = array_search(true, array_map(function ($allSyncedContact) use ($userDetail) {

                    // return $updatedContacts['email'] === $userDetail->email || $updatedContacts['phone'] === $userDetail->phone_number;
                    if ($allSyncedContact['email'] == $userDetail->email || $allSyncedContact['phone'] == $userDetail->phone_number) {
                        if ($allSyncedContact['email'] == $userDetail->email) {
                            return $allSyncedContact['email'] === $userDetail->email;
                        }

                        if ($userDetail->phone_number != '' && $allSyncedContact['phone'] == $userDetail->phone_number) {
                            // dd($allSyncedContact);
                            return $allSyncedContact['phone'] === $userDetail->phone_number;
                        }
                    }
                }, $allSyncedContacts));
                if ($index !== false) {

                    // Update the matching contact
                    $allSyncedContacts[$index]['userId'] = $userDetail->id;
                    $allSyncedContacts[$index]['isAppUser'] = (int)$userDetail->app_user;
                    $allSyncedContacts[$index]['firstName'] = $userDetail->firstname;
                    $allSyncedContacts[$index]['lastName'] = $userDetail->lastname;
                    $allSyncedContacts[$index]['visible'] = $userDetail->visible;
                    $allSyncedContacts[$index]['email'] = $userDetail->email;
                    $allSyncedContacts[$index]['phone'] = $userDetail->phone_number;
                    $allSyncedContacts[$index]['preferBy'] = $userDetail->prefer_by;
                    $allSyncedContacts[$index]['photo'] = $userDetail->profile ? asset('storage/profile/' . $userDetail->profile) : '';
                }
            }
            // if($user_exist == null){
            //     contact_sync::create($data);
            // }
        }

        fclose($file);
    }
}
