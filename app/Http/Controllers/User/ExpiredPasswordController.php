<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordExpiredRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ExpiredPasswordController extends Controller
{
    public function expired(Request $request)
    {
        return view('user.expired');
    }

    public function postExpired(PasswordExpiredRequest $request)
    {
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Не верный текущий пароль']);
        }
        // dd($request->new_password);
        $user = User::find($request->user()->id);
        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = now();
        $user->save();

        // $request->user()->update([
        //     'password' => Hash::make($request->new_password),
        //     'password_changed_at' => now()
        // ]);
        
        return redirect()->route('home')->with(['message' => 'Пароль изменен']);

    }

}
