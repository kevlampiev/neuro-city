<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dataservices\User\UserProfileDataservice;
use App\Http\Requests\UserProfileRequest;

class UserProfileController extends Controller
{
    public function edit(Request $request)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        return view('user.user-profile-edit', UserProfileDataservice::provideData($request));
    }

    public function update(UserProfileRequest $request)
    {
        UserProfileDataservice::storeData($request);
        return redirect()->route('home');
    }
}


