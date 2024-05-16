<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {

        $title = 'Home';
        $page = 'front.home';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
    public function importVCF(Request $request)
    {
        if ($request->hasFile('vcf_file')) {
            $file = $request->file('vcf_file');
            $vcfData = file_get_contents($file);
            $contacts = $this->parseVCF($vcfData);
            dd($contacts);
            // foreach ($contacts as $contact) {
            //     YourModelName::create([
            //         'name' => $contact['name'],
            //         'email' => $contact['email'],
            //         'phone' => $contact['phone'],
            //         // Add more fields as needed
            //     ]);
            // }

            return redirect()->back()->with('success', 'VCF data has been imported successfully');
        }
        return redirect()->back()->with('error', 'No VCF file found');
    }

    private function parseVCF($vcfData)
    {
        $contacts = [];
        $lines = explode("\n", $vcfData);
        $contact = [];

        foreach ($lines as $line) {
            dd($line);
            if (strpos($line, 'BEGIN:VCARD') !== false) {
                $contact = [];
            } elseif (strpos($line, 'END:VCARD') !== false) {
                $contacts[] = $contact;
            } else {
                $parts = explode(':', $line);
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                switch ($key) {
                    case 'FN':
                        $contact['name'] = $value;
                        break;
                    case 'EMAIL':
                        $contact['email'] = $value;
                        break;
                    case 'TEL':
                        $contact['phone'] = $value;
                        break;
                        // Add more fields as needed
                }
            }
        }

        return $contacts;
    }
}
