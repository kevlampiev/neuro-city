<?php


namespace App\DataServices\User;

use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileDataservice
{
    public static function provideData(Request $request): array
    {
        $user = auth()->user();
        if (!empty($request->old())) {
            $user->fill($request->old());
        }
        return ['user' => $user];
    }

    public static function storeData(UserProfileRequest $request)
    {
        $user = auth()->user();
        $user->fill($request->only('name', 'email', 'phone_number', 'birthday'));
        $user->updated_at = now();
        if ($request->file('img_file')) {
            $file = $request->file('img_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/img/avatars', $filename); // Сохраняем файл в директорию 'public/img/avatars'
            $user->photo = $filename;
        }
        $user->save();
    }

}
