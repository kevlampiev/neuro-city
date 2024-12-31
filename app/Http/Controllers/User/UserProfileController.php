<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dataservices\User\UserProfileDataservice;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Storage;

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

    public function getAvatar($filename)
    {
        $path = 'public/avatars/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, 'Image not found');
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);

    }
}


