<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\User;

use Carbon\Carbon;

class AuthRepository
{
    public function saveUser(Request $request)
    {
        $user = $this->checkUserExistance($request);
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
        $user = $this->checkUserExistance($request);

        $user->email_verified_at = Carbon::now('utc');
        $user->save();

        return $user;
    }

    public function checkUserExistance($request)
    {
        return User::where('email', $request->email)->select('id', 'name', 'email', 'dob')->first();
    }
}
