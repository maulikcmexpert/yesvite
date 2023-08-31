<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\forgotpasswordMail;
use Validator;

class ApiAuthController extends Controller
{
    public function signup(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);     
        if ($validator->fails()) {         
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->accessToken;
        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'email' => 'required|email',
            'password' => 'required',
        ]);      
        if ($validator->fails()) {         
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' =>401,
            ]);
        }
       
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::user()->createToken('API Token')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    
    public function passwordLink(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'email' => 'required|email',
        ]);      
        if ($validator->fails()) {         
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' =>401,
            ]);
        }
       
        Password_reset::create([
            'email' => $user->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(5), // Set expiration time as 5 minutes from now
        ]);
        Mail::to($req->input('email'))->send(new forgotpasswordMail($token));
        return response()->json(['message' => 'Email send successful'], 200);
       
    }
    public function resetPassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'email' => 'required|email',
            'token' => 'required|numeric|digits:4',
            'password' => 'required|min:8|confirmed',
        ]);      
        if ($validator->fails()) {         
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' =>401,
            ]);
        }

        $token = Password_reset::where('email', $request->email)
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$token) {
            // Token not found or expired
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token record
        $token->delete();

        return response()->json(['message' => 'Password reset successful'], 200);
    }
    public function updateProfile(Request $request)
    {
        

    }
}
