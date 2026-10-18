<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request, User $user)
    {

        if ($user->user_type == 'user_type') {
            abort(403);
        }

        $newPassword = Hash::make($request->new_password);

        $user->password = $newPassword;

        $user->save();

        return back()->with('success', 'تم تغير كلمة المرور');
    }
}
