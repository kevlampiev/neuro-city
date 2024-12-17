<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Можно добавить логику редиректа, например, на ссылку внутри уведомления:
        return redirect($notification->data['link'] ?? route('dashboard'))
            ->with('success', 'Уведомление помечено как прочитанное.');
    }

    public function refreshInfo()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        return response()->json([
            'htmlList' => view('partials.notifications-list', compact('notifications'))->render(),
            'htmlButton' => view('partials.notifications-button')->render(),
        ]);
    }


}
