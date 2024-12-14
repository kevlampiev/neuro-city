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
}
