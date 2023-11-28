<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\User;

class AuthRepository
{
    public function saveUser(Request $request)
    {
        $user = $this->checkUserVerified($request);
        if (!$user) {
            User::create([
                'email' => $request->email,
            ], [
                'name' => $request->name,
                'email' => $request->email,
                'password' => encrypt($request->password),
                'dob' => $request->dob
            ]);
        }
        return $user;
    }
 
    public function makeUserVerified(Request $request)
    {
        $user = $this->checkUserVerified($request);

        $user->email_verified_at = now();
        // $user->status = 'ACTIVE';
        $user->save();
        return $user;
    }
 
    public function checkUserExistance($request)
    {
        User::where('email', $request->email)->select('id', 'name', 'email', 'dob')->first();
    }
}